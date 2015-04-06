(function ($) {
    var dataParameterName = "ccsAutoFill";

    var defaults = {
        valueField: "value",
        searchParameter: "keyword",
        cache: false,
        fillOnStart: true
    };

    var events = {
        onChange: function (event) {
            methods.fill.call(event.data.self, event.data);
        }
    };

    var methods = {
        init: function (options) {
            var settings = $.extend({}, defaults, options);

            return this.each(function () {
                var obj = $(this);
                var id = obj.ccsId();
                var opts = $.extend({}, settings, { self: obj });
                opts.controls = opts.controls.slice();
                $.each(opts.controls, function (i) {
                    opts.controls[i] = $.extend({}, opts.controls[i]);
                    opts.controls[i].reference = $("*:ccsSameLevelControl(" + id + ", " + this.path + ")");
                });
                obj.data(dataParameterName, opts);
                obj.bind("change", opts, events.onChange);
                if (opts.fillOnStart)
                    methods.fill.call(obj, opts);
            });
        },
        start: function (options) { return methods.fill.apply(this, Array.prototype.slice.call(arguments, 1)); },
        fill: function (options) {
            return this.each(function () {
                var params = {};
                var obj = $(this);
                var opts = obj.data(dataParameterName);
                params[opts["searchParameter"]] = opts["valueField"] == "innerHTML" ? obj.html() : obj.prop(opts["valueField"]);
                obj.trigger($.Event("start.ccsAutoFill"), {});
                $.ajax({ type: "GET", url: opts["serviceUrl"], data: params, dataType: "json", cache: opts["cache"] })
                    .done(function (result) {
                        $.each(opts.controls, function () {
                            if (this.field == "innerHTML")
                                this.reference.html(result.length == 0 ? "" : result[0][this.source]);
                            else
                                this.reference.prop(this.field, result.length == 0 ? "" : result[0][this.source]);
                            this.reference.trigger($.Event("change"), {});
                        });
                        obj.trigger($.Event("success.ccsAutoFill"), {recordsCount: result.length, result: result});
                    }).fail(function () {
                        obj.trigger($.Event("failure.ccsAutoFill"), {recordsCount: 0, result: []});
                    });
                obj.trigger($.Event("finish.ccsAutoFill"), {});
            });
        }
    };

    $.fn.ccsAutoFill = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.ccsAutoFill');
            return $;
        };
    };
})(jQuery);