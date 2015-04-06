(function ($) {
    var dataParameterName = "ccsSlider";
    var nativeChange; // save native funtions

    var defaults = {
        animate: true,
        position: "after",
        isViewHiddenText: true
    };

    var events = {
        onChange: function (event, ui) {
            var settings = $(this).data(dataParameterName);
            settings.parent.val(ui.value);
            if (settings.parent.is('input:hidden') && settings.isViewHiddenText) {
                var label = $("label[for = " + settings.parent.attr("id") + "]").length > 0 ? $("label[for = " + settings.parent.attr("id") + "]") : $('<label for="' + settings.parent.attr("id") + '"></label>');
                label.text("Value: " + ui.value);
                settings.parent.before(label);
            }
            settings.parent.trigger($.Event("sliderMove.ccsSlider"), [ui]);
            // methods.valign(settings);
        }
    };

    var methods = {
        init: function (options) {
            var settings = $.extend({}, defaults, options);
            return $(this).each(function () {
                var parent = $(this);
                var opts = $.extend({}, settings, {
                    parent: parent
                });
                parent.data(dataParameterName, opts);

                var obj = methods.create(opts);
                opts = $.extend({}, opts, {
                    self: obj
                });
                var value = methods.isNumber(parent.val()) && parent.val() >= opts.min && parent.val() <= opts.max ? parent.val() : (!!opts.value ? opts.value : opts.min);
                opts.value = value;
                obj.data(dataParameterName, opts);

                obj.slider(opts);
                nativeChange = obj.data("uiSlider")._change;
                obj.data("uiSlider")._change = methods.change;
                obj.bind("slidechange", events.onChange);
                methods.size(opts);
                return obj;
            });
        },
        isNumber: function (n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        },
        create: function (options) {
            var obj;
            if (options.parent.is('input:text')) {
                var paddingTop = options.parent.css('padding-top');
                var marginTop = isNaN(parseInt(options.parent.css('margin-top'), 10)) ? 0 : parseInt(options.parent.css('margin-top'), 10);
                marginTop += options.parent.height() - 12;
                obj = $('<div id="' + options.parent.attr("id") + '_slider-bg" style="float: left; position: relative; top: ' + paddingTop + '; margin: ' + marginTop + 'px 10px 0 10px;"/>');
                options.parent.css("float", "left");

            }
            if (options.parent.is('input:hidden')) {
                obj = $('<div id="' + options.parent.attr("id") + '_slider-bg" style="margin: 2px 0 0 10px;"/>');
            }
            if (!obj) {
                return $('<div id="' + options.parent.attr("id") + '_slider-bg"/>');
            }
            if (options.position == "before") {
                options.parent.before(obj);
            } else {
                options.parent.after(obj);
            }
            return obj;
        },
        change: function (event, index) {
            this.options.parent[0].value = this.value();
            nativeChange.apply(this, arguments);
        },
        disable: function (options) {
            var settings = $.extend({}, $(this).data(dataParameterName), options);
            settings.self.slider("option", "disabled", true);
        },
        size: function (options) {
            var settings = $.extend({}, $(this).data(dataParameterName), options);
            if (!settings.size) return;

            if (!!settings.orientation && settings.orientation == "vertical") {
                settings.self.css("height", settings.size);
            } else {
                settings.self.css("width", settings.size);
            }
        }

    };

    $.fn.ccsSlider = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.ccsSlider');
            return $;
        };
    };
})(jQuery);