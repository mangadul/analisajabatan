(function ($) {
    var dataParameterName = "ccsDependentListboxData";

    var defaults = {
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
            var settings = $.extend({ }, defaults, options);
            var masterPath = settings["masterPath"];

            return this.each(function () {
                var depObj = $(this);
                var masterObj = $("*:ccsSameLevelControl(" + depObj.ccsId() + ", " + masterPath + ")");
                var opts = $.extend({}, settings, { dependentObject: depObj, masterObject: masterObj, self: depObj });
                depObj.data(dataParameterName, opts);
                masterObj.bind("change", opts, events.onChange);
                if (opts.fillOnStart)
                    methods.fill.call(depObj, opts);
            });
        },
        fill: function (options) {
            return this.each(function () {
                var params = {};
                var obj = $(this);
                var opts = obj.data(dataParameterName);
                var masterObject = opts["masterObject"];
                var dependentObject = opts["dependentObject"];
                params[opts["searchParameter"]] = masterObject.val();
                obj.trigger($.Event("start.ccsDependentListbox"), {});
                $.ajax({ type: "GET", url: opts["serviceUrl"], data: params, dataType: "json", cache: opts["cache"] })
                    .done(function (result) {
                        var value = dependentObject.val();
                        dependentObject.find('option[value!=""]').remove();
                        $.each(result, function () {
                            dependentObject.append($("<option />").val(this[0]).text(this[1]));
                        });
                        dependentObject.val(value);
                        obj.trigger($.Event("success.ccsDependentListbox"), {});
                        if (value != dependentObject.val())
                            obj.trigger($.Event("change"), {});
                    })
                    .fail(function () {
                        obj.trigger($.Event("failure.ccsDependentListbox"), {});
                    });
                obj.trigger($.Event("finish.ccsDependentListbox"), {});
            });
        }
    };

    $.fn.ccsDependentListbox = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.ccsDependentListbox');
            return $;
        };
    };
})(jQuery);