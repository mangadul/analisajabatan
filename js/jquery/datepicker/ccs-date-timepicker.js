(function ($) {
    var dataParameterName = "ccsDateTimePicker";
    var nativeGenerateHTML, nativeUpdateDatepicker, nativeShowDatepicker;

    var defaults = {
        dateFormat: 'MM/dd/yy hh:mm:ss tt', // See format options on https://github.com/jquery/globalize
        showTime: true, // Visible Time
        isWeekend: false,
        showButtonPanel: true,
        zIndex: 1005
    };
    // Global regional settings https://github.com/jquery/globalize
    var regional = {
        monthNames: Globalize.culture().calendars.standard.months.names, // ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'], // Names of months for drop-down and formatting
        monthNamesShort: Globalize.culture().calendars.standard.months.namesAbbr, // ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], // For formatting
        dayNames: Globalize.culture().calendars.standard.days.names, // ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], // For formatting
        dayNamesShort: Globalize.culture().calendars.standard.days.namesAbbr, // ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'], // For formatting
        dayNamesMin: Globalize.culture().calendars.standard.days.namesShort, // ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'], // Column headings for days starting at Sunday
        firstDay: Globalize.culture().calendars.standard.firstDay // The first day of the week, Sun = 0, Mon = 1, ...
    };

    var events = {
        onClose: function (dateText, inst) {
            var settings = $(this).data(dataParameterName);
            settings.self.trigger($.Event("close.ccsDateTimePicker"), [dateText, inst]);
        },
        onSelect: function (dateText, inst) {
            var settings = $(this).data(dataParameterName);
            settings.self.trigger($.Event("select.ccsDateTimePicker"), [dateText, inst]);
        },
        beforeShow: function (input, inst) {
            var settings = $(this).data(dataParameterName);
            settings.self.trigger($.Event("beforeShow.ccsDateTimePicker"), [input, inst]);
        }
    };

    var methods = {
        init: function (options) {
            var settings = $.extend({}, defaults, options);
            $.extend(settings, settings, regional);
            $.extend(settings, settings, {
                onClose: events.onClose,
                onSelect: events.onSelect,
                beforeShow: events.beforeShow
            });

            return $(this).each(function () {
                var opts = $.extend({}, settings, {
                    self: $(this)
                });
                if (Globalize.culture().calendars.standard.patterns[opts.dateFormat] != undefined) {
                    opts.dateFormat = Globalize.culture().calendars.standard.patterns[opts.dateFormat];
                }
                if (options.showTime == undefined) {
                    opts.showTime = methods.checkTimeFormat(opts.dateFormat);
                }
                $(this).data(dataParameterName, opts);

                $(this).datepicker(opts);
                methods.addWeekend(opts);
            });
        },
        checkTimeFormat: function (format) {
            return format.search(/[hHmst]/) > -1;
        },
        parseDate: function (format, value, settings) {
            if (typeof value === "string" && value != "") {
                if (Number(value) == value) {
                    return Number(value);
                }
                return Globalize.parseDate(value, format);
            } else {
                value = window.Globalize ? Globalize.parseFloat(value) : +value;
            }
            return value === "" || isNaN(value) ? null : new Date(value);
        },
        formatDate: function (format, date, settings) {
            if (!date) {
                return "";
            }
            return Globalize.format(new Date(date), format);
        },
        _formatDate: function (inst, day, month, year) {
            if (!day) {
                inst.currentDay = inst.selectedDay;
                inst.currentMonth = inst.selectedMonth;
                inst.currentYear = inst.selectedYear;
            }
            var date = (day ? (typeof day == 'object' ? day : this._daylightSavingAdjust(new Date(year, month, day))) : this._daylightSavingAdjust(new Date(inst.currentYear, inst.currentMonth, inst.currentDay)));
            date.setHours(inst.currentHour);
            date.setMinutes(inst.currentMinute);
            date.setSeconds(inst.currentSecond);
            return methods.formatDate(inst.settings.dateFormat, date);
        },
        updateDatepicker: function (inst) {
            nativeUpdateDatepicker.apply(this, arguments);
            inst.dpDiv.find(".timepicker").data(dataParameterName, inst);
            inst.dpDiv.find(".ui-datepicker-calendar").addClass("ccs-calendar"); // ccs styles by ccs-jquery-ui-calendar.css
            methods.addTimePickerHandlers();
            methods.addNowButtonHandler(inst.input[0]); // bind button 'Now'
        },
        showDatepicker: function (input) {
            nativeShowDatepicker.apply(this, arguments);
            input = input.target || input;
            var inst = $.datepicker._getInst(input);
            inst.dpDiv.css('z-index', inst.settings.zIndex); // fix problem with  'z-index'
            if (inst.settings.dateFormat.search(/[dMy]/g) == -1) {
                inst.dpDiv.css('width', "auto"); // fix width if calendar is not needed 
                inst.dpDiv.find(".timepicker").css('border-top', 0);
            }
        },
        selectDate: function (id, dateStr) {
            var target = $(id);
            var inst = this._getInst(target[0]);
            dateStr = (dateStr != null ? dateStr : this._formatDate(inst));
            if (inst.input)
                inst.input.val(dateStr);
            this._updateAlternate(inst);
            var onSelect = this._get(inst, 'onSelect');
            if (onSelect)
                onSelect.apply((inst.input ? inst.input[0] : null), [dateStr, inst]);  // trigger custom callback
            else if (inst.input)
                inst.input.trigger('change'); // fire the change event
            if (inst.settings.showTime) {
                this._updateDatepicker(inst);
            } else {
                var control = $(inst.input[0]);
                control.blur(); // unfocus input
                var val = control.val();
                control.val("").val(val);
                window.setTimeout(function (parameters) {
                    control.datepicker("hide");
                }, 1);
            }
        },
        _daylightSavingAdjust: function (date) {
            if (!date) return null;
            date.setHours(date.getHours() > 12 ? date.getHours() + 2 : 0);
            return date;
        },
        setDateFromField: function (inst, noDefault) {
            if (inst.input.val() == inst.lastVal) {
                return;
            }
            var format = inst.settings.dateFormat;
            var dates = inst.lastVal = inst.input ? inst.input.val() : null;
            var date, defaultDate;
            date = defaultDate = this._getDefaultDate(inst);
            var settings = this._getFormatConfig(inst);
            try {
                date = this.parseDate(format, dates, settings) || defaultDate;
            	if ('object' !== typeof date) {
					date = new Date(date);
            	}
            } catch (event) {
                dates = (noDefault ? '' : dates);
            }
            var curDate = new Date();
            inst.currentDate = date;
            if (!dates) {
                inst.currentDate.setHours(curDate.getHours());
                inst.currentDate.setMinutes(curDate.getMinutes());
            }
            inst.selectedDay = date.getDate();
            inst.drawMonth = inst.selectedMonth = date.getMonth();
            inst.drawYear = inst.selectedYear = date.getFullYear();
            inst.currentDay = (dates ? date.getDate() : 0);
            inst.currentMonth = (dates ? date.getMonth() : 0);
            inst.currentYear = (dates ? date.getFullYear() : 0);
            inst.currentHour = (dates ? date.getHours() : curDate.getHours());
            inst.currentMinute = (dates ? date.getMinutes() : curDate.getMinutes());
            inst.currentSecond = (dates ? date.getSeconds() : 0);
            this._adjustInstDate(inst);
        },
        addWeekend: function (options) {
            if (!options.isWeekend) {
                return;
            }
            /*****************************************************************************************
            This code should be placed at the end of the file jquery-ui.css :
            .ccs-calendar-week-end .ui-datepicker-week-end .ui-state-default { border: 1px solid #ffa1a1; }   */
            /*****************************************************************************************/
            var calendar = $.datepicker._getInst(options.self[0]); // $("#ui-datepicker-div");
            calendar.dpDiv.attr("class", calendar.dpDiv.attr("class") + " ccs-calendar-week-end");

            /************************************************************************
            .ui-datepicker-week-end .ui-state-default {
            border: 1px solid #ffa1a1;
            }
            .ui-state-custom {
            border: 1px solid #f0f !important;
            }                                                                       */
            /************************************************************************/
            /* var render = $('<style type="text/css" media="screen" />');
            render.html(
            '.ui-datepicker-week-end .ui-state-default { border: 1px solid #ffa1a1; }' +
            '.ui-state-custom { border: 1px solid #f0f !important; }'
            );
            $('body').append(render); */
        },
        addTimePicker: function (inst) {
            var calendarHTML = nativeGenerateHTML.apply(this, arguments);
            var format = inst.settings.dateFormat;
            var timePickerHTML =
                '<div class="timepicker">' +
                '  <table cellpadding="0" cellspacing="0" border="0"><tbody><tr>';
            if (format.search(/h/i) > -1) { // add hours input
                var hours = methods.getSetHourByFormat(inst.currentDate, format);
                timePickerHTML += '<td id="hours"><div class="timepicker-up"><span class="timepicker-up-bg"></span></div><input type="text" class="timepicker-input" value="' + hours + '"><div class="timepicker-down"><span class="timepicker-down-bg"></span></div></td>';
            }
            if (format.search(/m/) > -1) { // add minutes input
                var minutes = methods.getSetMinutesByFormat(inst.currentDate, format);
                timePickerHTML += '<td><span style="text-align: center;">:</span></td><td id="minutes"><div class="timepicker-up"><span class="timepicker-up-bg"></span></div><input type="text" class="timepicker-input" value="' + minutes + '"><div class="timepicker-down"><span class="timepicker-down-bg"></span></div></td>';
            }
            if (format.search(/s/) > -1) { // add seconds input
                var seconds = methods.getSetSecondsByFormat(inst.currentDate, format);
                timePickerHTML += '<td><span style="text-align: center;">:</span></td><td id="seconds"><div class="timepicker-up"><span class="timepicker-up-bg"></span></div><input type="text" class="timepicker-input" value="' + seconds + '"><div class="timepicker-down"><span class="timepicker-down-bg"></span></div></td>';
            }
            if (format.search(/t/) > -1) { // add am pm using 12-hour clock
                var am = inst.currentHour < 12 ? "-active" : "";
                var pm = inst.currentHour >= 12 ? "-active" : "";
                if (am == pm && am == "") {
                    am = "-active"; // default
                }
                timePickerHTML += '<td><div class="timepicker-ampm' + am + '">AM</div><div class="timepicker-ampm' + pm + '">PM</div></td>';
            }
            timePickerHTML +=
                '  </tr></tbody></table>' +
                '</div>';
            /**
            *  Hide calendar if it is not needed 
            */
            if (format.search(/[dMy]/g) == -1) {
                calendarHTML = "";
            }
            if (!inst.settings.showTime) {
                timePickerHTML = "";
            }
            var temp = $('<div>' + calendarHTML + timePickerHTML + '</div>');
            temp.append(temp.find('.ui-datepicker-buttonpane, .ui-widget-content'));
            /**
            *  Add button 'Now'
            */
            if (inst.settings.showButtonPanel) {
                var buttonPane = temp.find(".ui-datepicker-buttonpane");
                buttonPane.html(""); // clear default
                var nowText = Globalize.culture().messages["CCS_Now"] != "" ? Globalize.culture().messages["CCS_Now"] : "Now";
                var now = $('<button id="ui-button-now">' + nowText + '</button>');
                now.appendTo(buttonPane).addClass("ui-datepicker-now ui-state-default ui-priority-primary ui-corner-all");
            }
            return temp.html();
        },
        addNowButtonHandler: function (input) {
            $('#ui-button-now').unbind("click").bind("click", function () {
                var inst = $.datepicker._getInst(input.target || input);
                var dateStr = $.datepicker._formatDate(inst);
                $(input).val(methods.formatDate(inst.settings.dateFormat, new Date(), inst.settings));
                gotoToday.apply($.datepicker, ["#" + inst.id]);
                methods.setDateFromField.apply($.datepicker, [inst]);
                var onSelect = inst.settings.onSelect;
                if (onSelect)
                    onSelect.apply((inst.input ? inst.input[0] : null), [dateStr, inst]);  // trigger custom callback
                else if (inst.input)
                    inst.input.trigger('change'); // fire the change event
                /*if (inst.settings.showTime) {
                    methods.updateDatepicker.apply($.datepicker, [inst]);
                } else { */
                    var control = $(inst.input[0]);
                    control.blur(); // unfocus input
                    var val = control.val();
                    control.val("").val(val);
                    window.setTimeout(function (parameters) {
                        control.datepicker("hide");
                    }, 1);
                /*} */
            });
        },
        switchAMPM: function (value) {
            $(".timepicker-ampm, .timepicker-ampm-active").parent().find('div').each(function () {
                if ($(this).text() == value) {
                    $(this).attr('class', "timepicker-ampm-active");
                } else {
                    $(this).attr('class', "timepicker-ampm");
                }
            });
        },
        timeUp: function (event) {
            methods.updateTimeDialog(this, "timeUp");
        },
        timeDown: function (event) {
            methods.updateTimeDialog(this, "timeDown");
        },
        updateTimeDialog: function (input, action) {
            var inst = $(".timepicker").data(dataParameterName);
            var format = inst.settings.dateFormat;
            var value = parseInt($(input).parent().find("input").val(), 10);
            value = isNaN(value) ? 0 : value;
            value = $(input).parent().attr('id') == "hours" && value != inst.currentDate.getHours() ? inst.currentDate.getHours() : value; // fix for AM/PM
            value = (action == "timeDown") ? value - 1 : value + 1;
            switch ($(input).parent().attr('id')) {
                case "hours":
                    value = methods.getSetHourByFormat(inst.currentDate, format, value);
                    var ampm = methods.getSetHourByFormat12(inst.currentDate, format).replace(/\d*/, "");
                    inst.currentHour = inst.currentDate.getHours();
                    methods.switchAMPM(ampm);
                    break;
                case "minutes":
                    value = methods.getSetMinutesByFormat(inst.currentDate, format, value);
                    var hours = methods.getSetHourByFormat(inst.currentDate, format);
                    var ampm = methods.getSetHourByFormat12(inst.currentDate, format).replace(/\d*/, "");
                    inst.currentMinute = inst.currentDate.getMinutes(); ;
                    inst.currentHour = inst.currentDate.getHours();
                    $(input).parent().parent().find('td[id="hours"]').find("input").val(hours);
                    methods.switchAMPM(ampm);
                    break;
                case "seconds":
                    value = methods.getSetSecondsByFormat(inst.currentDate, format, value);
                    var minutes = methods.getSetMinutesByFormat(inst.currentDate, format);
                    var hours = methods.getSetHourByFormat(inst.currentDate, format);
                    var ampm = methods.getSetHourByFormat12(inst.currentDate, format).replace(/\d*/, "");
                    inst.currentSecond = inst.currentDate.getSeconds();
                    inst.currentMinute = inst.currentDate.getMinutes(); ;
                    inst.currentHour = inst.currentDate.getHours();
                    $(input).parent().parent().find('td[id="minutes"]').find("input").val(minutes);
                    $(input).parent().parent().find('td[id="hours"]').find("input").val(hours);
                    methods.switchAMPM(ampm);
                    break;
                default: value = 0;
            }
            $(input).parent().find("input").val(value);

            var dateStr = methods._formatDate(inst, inst.currentDay, inst.currentMonth, inst.currentYear);
            $('#' + inst.id).val(dateStr);
        },
        addTimePickerHandlers: function () {
            var interval = 200;
            var isClicked = false;
            var changeInterval = function (doReset) {
                if (doReset) {
                    interval = 200;
                    return;
                }
                if (interval > 50)
                    interval -= 50;
            }
            $(".timepicker-up").mouseup(function (event) {
                isClicked = false;
                changeInterval(true);
            }).mousedown(function () {
                methods.timeUp.apply(this, arguments);
                var self = this;
                var changeFunc = function () {
                    if (!isClicked)
                        return;
                    methods.timeUp.apply(self, arguments);
                    changeInterval();
                    setTimeout(changeFunc, interval);
                }
                isClicked = true;
                setTimeout(changeFunc, interval);
            }).mouseout(function () {
                isClicked = false;
            }).mouseover(function () {

            });

            $(".timepicker-down").mouseup(function () {
                isClicked = false;
                changeInterval(true);
            }).mousedown(function () {
                methods.timeDown.apply(this, arguments);
                var self = this;
                var changeFunc = function () {
                    if (!isClicked) {
                        return;
                    }
                    methods.timeDown.apply(self, arguments);
                    changeInterval();
                    setTimeout(changeFunc, interval);
                }
                isClicked = true;
                setTimeout(changeFunc, interval);
            }).mouseout(function () {
                isClicked = false;
            }).mouseover(function () {

            });
            $('.timepicker-up, .timepicker-down').hover(
                function () { $(this).addClass('timepicker-state-hover'); },
                function () { $(this).removeClass('timepicker-state-hover'); }
            );
            $(".timepicker-ampm, .timepicker-ampm-active").click(function () {
                if ($(this).attr('class') == "timepicker-ampm-active") {
                    return;
                }
                methods.switchAMPM($(this).text());
                var inst = $(".timepicker").data(dataParameterName);
                var hours = inst.currentDate.getHours();
                /* hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12' */
                if (hours >= 12 && $(this).text() == "AM") {
                    methods.getSetHourByFormat12(inst.currentDate, inst.settings.dateFormat, inst.currentDate.getHours() - 12);
                }
                if (hours < 12 && $(this).text() == "PM") {
                    methods.getSetHourByFormat12(inst.currentDate, inst.settings.dateFormat, inst.currentDate.getHours() + 12);
                }
                inst.currentHour = inst.currentDate.getHours();
                var dateStr = methods._formatDate(inst, inst.currentDay, inst.currentMonth, inst.currentYear);
                $('#' + inst.id).val(dateStr);
            });
        },

        getSetHourByFormat: function (inDate, format, value) {
            var date = typeof inDate == 'object' ? inDate : new Date(inDate);
            if (value != undefined) {
                date.setHours(value);
            }
            var hour = format.replace(/.*?(h{1,2}).*/i, function (str, group) {
                return group.length == 1 ? Globalize.format(date, group + " ").replace(" ", "") : Globalize.format(date, group);
            });
            return hour == format ? "" : hour;
            // return Globalize.format(date, format.replace(/.*?(h+).*/i, "$1"));
        },
        getSetHourByFormat12: function (inDate, format, value) {
            var date = typeof inDate == 'object' ? inDate : new Date(inDate);
            if (value != undefined) {
                date.setHours(value);
            }
            var ampm = format.replace(/.*?(t{1,2}).*/, "$1");
            var hour = format.replace(/.*?(h{1,2}).*/i, function (str, group) {
                ampm = ampm == format ? "" : ampm;
                return group.length == 1 ? Globalize.format(date, group + ampm + " ").replace(" ", "") : Globalize.format(date, group + ampm);
            });
            return hour == format ? "" : hour;
            // return Globalize.format(date, format.replace(/.*?(h+).*/i, "$1") + format.replace(/.*?(t+).*/, "$1"));
        },
        getSetMinutesByFormat: function (inDate, format, value) {
            var date = typeof inDate == 'object' ? inDate : new Date(inDate);
            if (value != undefined) {
                date.setMinutes(value);
            }
            var minute = format.replace(/.*?(m{1,2}).*/, function (str, group) {
                return group.length == 1 ? Globalize.format(date, group + " ").replace(" ", "") : Globalize.format(date, group);
            });
            return minute == format ? "" : minute;
            // return Globalize.format(date, format.replace(/.*?(m+).*/, "$1"));
        },
        getSetSecondsByFormat: function (inDate, format, value) {
            var date = typeof inDate == 'object' ? inDate : new Date(inDate);
            if (value != undefined) {
                date.setSeconds(value);
            }
            var second = format.replace(/.*?(s{1,2}).*/, function (str, group) {
                return group.length == 1 ? Globalize.format(date, group + " ").replace(" ", "") : Globalize.format(date, group);
            });
            return second == format ? "" : second;
            // return Globalize.format(date, format.replace(/.*?(s+).*/, "$1"));
        }
    };

    $.datepicker.parseDate = methods.parseDate
    $.datepicker.formatDate = methods.formatDate;
    $.datepicker._setDateFromField = methods.setDateFromField;
    $.datepicker._selectDate = methods.selectDate;
    $.datepicker._formatDate = methods._formatDate;

    nativeGenerateHTML = $.datepicker._generateHTML;
    $.datepicker._generateHTML = methods.addTimePicker;
    nativeUpdateDatepicker = $.datepicker._updateDatepicker;
    $.datepicker._updateDatepicker = methods.updateDatepicker;
    nativeShowDatepicker = $.datepicker._showDatepicker;
    $.datepicker._showDatepicker = methods.showDatepicker;
    gotoToday = $.datepicker._gotoToday;

    $.fn.ccsDateTimePicker = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.ccsDateTimePicker');
            return $;
        };
    };
})(jQuery);