(function ($) {

    var Url = $.fn.Url;

    var dataParameterName = "ccsUpdatePanel";

    var defaults = {
        cache: false
    };

    var defaultRequest = {
        method: "GET"
    };

    var panels = ({});


    var sortFunc = function (a, b) {
        if (!a.handler.order)
            a.handler.order = 0;
        if (!b.handler.order)
            b.handler.order = 0;
        return a.handler.order - b.handler.order;
    };

    if (!$.fn.oldOn) {
        $.fn.oldOn = $.fn.on;
        $.fn.on = function () {
            var res = $.fn.oldOn.apply(this, arguments);
            res.each(function () {
                $.each($._data(this)["events"], function () {
                    this.sort(sortFunc);
                });
            });
            return res;
        };
    }
    
    var replacemetFunctions = {
        _updatePanel: function (updatePanelElement, rendering) {
            var $updatePanelElement = $(updatePanelElement);
            $updatePanelElement.html(rendering);
            events.onafterrefresh($updatePanelElement);
            $updatePanelElement.trigger($.Event("afterrefresh.ccsUpdatePanel"), {});
        }
    };


    var events = {
        onLinkClick: function (event) {
            var obj = $(this);
            var href = obj.attr("href");
            if (href.indexOf("javascript:") != 0)
                return true;
            eval(href.replace(/^javascript:/, ""));
            return false;
        },
        onSubmitClick: function (event) {
            var obj = $(this);
            event.data.lastClick = obj;
        },
        onFormSubmit: function (event) {
            var obj = $(this);
            var method = obj.attr("method").toUpperCase();
            method = method == "" ? "GET" : method;
            var ajaxParams = { url: obj.attr("action"), type: method };
            event.data.self.trigger($.Event("beforeresubmit.ccsUpdatePanel"), {});
            if (method == "POST") {
                var u = new Url("?" + obj.serialize());
                if (event.data.lastClick)
                    u.parameter(event.data.lastClick.attr("name"), event.data.lastClick.attr("value"));
                ajaxParams.data = u.parameterString();
            }
            else {
                var u = new Url(ajaxParams.url);
                u._params = [];
                u.params(obj.serialize());
                if (event.data.lastClick)
                    u.parameter(event.data.lastClick.attr("name"), event.data.lastClick.attr("value"));
                ajaxParams.url = u.toString();
            };
            methods.refresh.call(event.data.self, ajaxParams);
            return false;
        },
        onafterrefresh: function (obj) {
            methods.bindHandlers.call(obj, {});
            obj.ccsBind("reBind");
        },
        onbeforerefresh: function (obj) {
        },
        onafterrequest: function (obj) {
        }
    };

    events.onFormSubmit.order = 1;
    events.onSubmitClick.order = 1;
    events.onLinkClick.order = 1;

    var methods = {
        init: function (options) {
            var settings = $.extend({}, defaults, options);

            return this.each(function () {
                var obj = $(this);
                var id = obj.attr("id");
                panels[id] = obj;
                var opts = $.extend({}, settings, { self: obj, currentUrl: window.location.href, state: "ready" });
                obj.data(dataParameterName, opts);
                methods.bindHandlers.call(obj, options);
            });
        },
        getReplacementFunction: function (funcName) {
            return replacemetFunctions[funcName];
        },
        invoke: function (clientId, eventName) {
            var obj = typeof (clientId) == "string" ? $("#" + clientId) : $(clientId);
            events["on" + eventName](obj);
            obj.trigger($.Event(eventName + ".ccsUpdatePanel"), {});
        },
        getPanelId: function (e) {
            var params = e.get_response().get_responseData().split('|');
            for (var i = 0; i < params.length; i++) {
                if (params[i] == "updatePanel") {
                    return params[i + 1];
                }
            }
            return "";
        },
        getPanelByPostback: function (e) {
            var obj = e.get_postBackElement();
            var node = obj;
            while (node != document) {
                if ($(node).data(dataParameterName))
                    return node;
                node = node.parentNode;
            }
            return null;
        },
        refresh: function (options) {
            if (!options) {
                options = this.data(dataParameterName);
                options.url = options.currentUrl;
            }
            var url = options.url;
            if (url == "#")
                return;
            var obj = $(this);
            var data = obj.data(dataParameterName);
            var name = data.name;
            url = new Url(url);
            url.parameter("FormFilter", name);
            url = url.toString();
            obj.trigger($.Event("beforerefresh.ccsUpdatePanel"), {});
            $.ajax(url, options)
                .done(function (text) {
                    var index = text.indexOf("|");
                    data.currentUrl = text.substring(0, index);
                    obj.html(text.substr(index + 1));
                    obj.ccsBind("reBind");
                    methods.bindHandlers.call(obj, options);
                    obj.trigger($.Event("afterrefresh.ccsUpdatePanel"), {});
                })
                .fail(function () {
                    obj.trigger($.Event("afterrefresh.ccsUpdatePanel"), {});
                    url = new Url(url);
                    url.removeParameter("FormFilter");
                    window.location.href = url.toString();
                });
        },
        bindHandlers: function (options) {
            var obj = $(this);
            var data = obj.data(dataParameterName);

            obj.find("a").each(function () {
                var control = $(this);
                if (control.data(dataParameterName)) return;
                control.data(dataParameterName, data);
                control.bind("click", data, events.onLinkClick);
            });
        }
    };

    $.fn.ccsUpdatePanel = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.ccsUpdatePanel');
            return $;
        };
    };
})(jQuery);