(function ($, undefined) {
    var dataParameterName = "ccsDialog";
    var state = {};

    var defaults = {
        autoOpen: false, // visible
        closeOnEscape: true,
        width: "auto"
    };

    var events = {
        onShow: function (event, ui) {
            var settings = $(this).data(dataParameterName);
            if (settings.self.dialog("option", "width") == "auto")
                settings.self.dialog("option", "width", (settings.self.parent().width()) );
            if (settings.self.dialog("option", "height") == "auto")
                settings.self.dialog("option", "height", (settings.self.parent().outerHeight()) );
            settings.self.trigger($.Event("show.ccsDialog"), [ui]);
            state[settings.self.selector] = $.extend({}, state[settings.self.selector], { show: true });
        },
        onHide: function (event, ui) {
            var settings = $(this).data(dataParameterName);
            settings.self.trigger($.Event("hide.ccsDialog"), [ui]);
            state[settings.self.selector] = $.extend({}, state[settings.self.selector], { show: false });
        },
        onResizeStop: function (event, ui) {
            var settings = $(this).data(dataParameterName);
            settings.self.trigger($.Event("dialogresizestop.ccsDialog"), [ui]);
            // state[settings.self.selector] = $.extend({}, state[settings.self.selector], { size: ui.size });
            var pos = settings.self.parent().offset();
            state[settings.self.selector] = $.extend({}, state[settings.self.selector], { size: { height: (settings.self.parent().outerHeight()), width: (settings.self.parent().width())} });
            state[settings.self.selector] = $.extend({}, state[settings.self.selector], { position: [pos.left, pos.top] });
        },
        onDialogDragStop: function (event, ui) {
            var settings = $(this).data(dataParameterName);
            settings.self.trigger($.Event("dialogdragstop.ccsDialog"), [ui]);
            var pos = settings.self.parent().offset();
            state[settings.self.selector] = $.extend({}, state[settings.self.selector], { size: { height: (settings.self.parent().outerHeight()), width: (settings.self.parent().width())} });
            state[settings.self.selector] = $.extend({}, state[settings.self.selector], { position: [pos.left, pos.top] });
        }
    };

    var methods = {
        init: function (options) {
            var settings = $.extend({}, defaults, options);
            var object = $(this);
            var parent = object.parent(); // #CCS-159005
            $.extend(settings, settings, { self: object });
            if (state[settings.self.selector] != undefined) {
                if (state[settings.self.selector].show != undefined) {
                    $.extend(settings, settings, { autoOpen: state[settings.self.selector].show });
                }
                if (state[settings.self.selector].position != undefined) {
                    $.extend(settings, settings, { position: state[settings.self.selector].position });
                }
                if (state[settings.self.selector].size != undefined) {
                    $.extend(settings, settings, { width: state[settings.self.selector].size.width, height: state[settings.self.selector].size.height });
                }
            }
            object.data(dataParameterName, settings);

            object.dialog(settings);

            object.bind("dialogopen", events.onShow);
            object.bind("dialogclose", events.onHide);
            object.bind("dialogresizestop", events.onResizeStop);
            object.bind("dialogdragstop", events.onDialogDragStop);

            object.parent().appendTo(parent);
            $(".ui-widget-overlay").prependTo(parent);
            return object;
        },
        show: function (options) {
            var settings = $(this).data(dataParameterName);
            settings.self.dialog('open');
            if (!!state[settings.self.selector] && state[settings.self.selector].position)
                settings.self.dialog('option', "position", state[settings.self.selector].position);
            if (!!state[settings.self.selector] && state[settings.self.selector].size != undefined) {
                settings.self.dialog('option', "width", state[settings.self.selector].size.width);
                settings.self.dialog('option', "height", state[settings.self.selector].size.height);
            }
            var parent = settings.self.parent().parent(); //hack for jQuery UI - v1.10.0
            $(".ui-widget-overlay").prependTo(parent);
        },
        hide: function (options) {
            var settings = $(this).data(dataParameterName);
            settings.self.dialog('close');
        }

    };

    $.fn.ccsDialog = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.ccsDialog');
            return $;
        };
    };
})(jQuery);