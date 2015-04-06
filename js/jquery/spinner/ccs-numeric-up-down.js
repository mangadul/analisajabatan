/** !
 * CCS jQuery UI Numeric Up Down
 *
 * Depends:
 *	jquery.external.jquery.mousewheel.js
 *	jquery.external.globalize.js
 *	jquery.ui.core.js
 *	jquery.ui.widget.js
 *	jquery.ui.button.js
 *	jquery.ui.spinner.js
 */
 
(function ($) {
    var dataParameterName = "ccsNumericUpDown";

    var defaults = {
        sourceType: "Date",         // type {"Date", "Time", "Numeric"}
        min: 0,                     // min date
        step: 1000 * 60 * 60 * 24,  // one day
        dateFormat: "yyyy/M/d",     // default format
        altStep: 10                 // alternate increment 
    };

    var events = {
        onStart: function (event) {
            var settings = $(this).data(dataParameterName);
            if ((settings.sourceType.toLowerCase() == "date" || settings.sourceType.toLowerCase() == "time") && $(this).val() == "") {
                var value = new Date().valueOf();
                if (!!settings.min) {
                    minValue = +Globalize.parseDate(settings.min, settings.dateFormat);
                    value = value - minValue > 0 ? value : minValue;
                } else {
                    if (!!settings.max) {
                        maxValue = +Globalize.parseDate(settings.max, settings.dateFormat);
                        value = value - maxValue > 0 ? maxValue : value;
                    }
                }
                $(this).val(Globalize.format(new Date(value), settings.dateFormat));
            }
            settings.self.trigger($.Event("onStart.ccsNumericUpDown"), [event]);
        },
        onSpin: function (event, step) {
            var settings = $(this).data(dataParameterName);
            settings.self.trigger($.Event("onSpin.ccsNumericUpDown"), [event, step]);
        },
        onStop: function (event) {
            var settings = $(this).data(dataParameterName);
            settings.self.trigger($.Event("onStop.ccsNumericUpDown"), [event]);
        },
        onChange: function (event) {
            var settings = $(this).data(dataParameterName);
            settings.self.trigger($.Event("onChange.ccsNumericUpDown"), [event]);
        }
    };

    var methods = {
        init: function (options) {
            var settings = $.extend({}, defaults, options);
            //$.extend(settings, settings, regional);
            $.extend(settings, settings, {
                start: events.onStart,
                spin: events.onSpin,
                stop: events.onStop,
                change: events.onChange
            });

            return $(this).each(function () {
                var opts = $.extend({}, settings, {
                    self: $(this),
                    page: settings.altStep
                });
                if (Globalize.culture().calendars.standard.patterns[opts.dateFormat] != undefined) {
                    opts.dateFormat = Globalize.culture().calendars.standard.patterns[opts.dateFormat];
                }

                // validate min/max value by format
                if (opts.sourceType !== "Numeric") {
                    opts.min = +Globalize.parseDate(opts.min, opts.dateFormat) > 0 ? opts.min : "";
                    opts.max = +Globalize.parseDate(opts.max, opts.dateFormat) > 0 ? opts.max : "";
                }
                switch (opts.sourceType.toLowerCase()) {
                    case "date":
                        opts.step = opts.step * 1000 * 60 * 60 * 24;  // one day
                        break;
                    case "time":
                        opts.step = opts.dateFormat.search(/s+/) > -1 ? opts.step * 1000 : opts.step * 1000 * 60;  // one second or one minute
                        break;
                    case "numeric":
                        opts.step = 1;
                        break;
                    default: opts.step = 1;
                }
                $(this).data(dataParameterName, opts);
                $(this).spinner(opts);
            });
        },
        parse: function (value) {
            if (typeof value === "string" && value != "") {
                if (this.options.sourceType.toLowerCase() === "date" || this.options.sourceType.toLowerCase() === "time") {
                    if (Number(value) == value) {
                        return Number(value);
                    }
                    return +Globalize.parseDate(value, this.options.dateFormat);
                }
                else {
                    value = window.Globalize && this.options.numberFormat ? Globalize.parseFloat(value, 10, this.options.culture) : +value;
                }
            }
            return value === "" || isNaN(value) ? null : value;
        },

        format: function (value) {
            if (value === "") {
                return "";
            }
            if (this.options.sourceType.toLowerCase() === "date" || this.options.sourceType.toLowerCase() === "time") {
                return Globalize.format(new Date(value), this.options.dateFormat);
            }
            else {
                return window.Globalize && this.options.numberFormat ? Globalize.format(value, this.options.numberFormat, this.options.culture) : value;
            }
        }
    };

    $.ui.spinner.prototype._parse = methods.parse;
    $.ui.spinner.prototype._format = methods.format;

    $.fn.ccsNumericUpDown = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.ccsNumericUpDown');
            return $;
        };
    };
})(jQuery);