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

    function qualifyURL(url) {
        var img = document.createElement('img');
        img.src = url;
        url = img.src;
        img.src = null;
        return url;
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
    if (!$.fn.oldDomManip) {
        $.fn.oldDomManip = $.fn.domManip;
        $.fn.domManip = function () {
            var res = $.fn.oldDomManip.apply(this, arguments);
            if (!$("body").data("handleDomManips"))
                return res;
            var r = ({});
            $.each(panels, function (key) {
                var panel = this;
                $.each(res, function () {
                    if (panel.has(this).length > 0) {
                        r[key] = panel;
                        return false;
                    }
                });
            });
            $.each(r, function (key) {
                this.ccsUpdatePanel("bindHandlers");
            });
            return res;
        };
    }
    var events = {
        onLinkClick: function (event) {
            var obj = $(this);
            if (!!obj.data("do-not-refresh")) // for ccs tabs
                return false;
            var u = obj.prop("href");
            var cur = new Url();
            var url = new Url(qualifyURL(u));
            if (url._baseUrl != cur._baseUrl)
                return true;
            methods.refresh.call(event.data.self, { url: u });
            return false;
        },
        onSubmitClick: function (event) {
            var obj = $(this);
            event.data.lastClick = obj;
        },
        onFormSubmit: function (event) {
            var obj = $(this);
            var method = obj.prop("method").toUpperCase();
            method = method == "" ? "GET" : method;
            var ajaxParams = { url: obj.prop("action"), type: method };
            event.data.self.trigger($.Event("beforeresubmit.ccsUpdatePanel"), {});
            if (method == "POST") {
                var u = new Url("?" + obj.serialize());
                if (event.data.lastClick)
                    u.parameter(event.data.lastClick.prop("name"), event.data.lastClick.prop("value"));
                ajaxParams.data = u.parameterString();
            }
            else {
                var u = new Url(ajaxParams.url);
                u._params = [];
                u.params(obj.serialize());
                if (event.data.lastClick)
                    u.parameter(event.data.lastClick.prop("name"), event.data.lastClick.prop("value"));
                ajaxParams.url = u.toString();
            };
            methods.refresh.call(event.data.self, ajaxParams);
            return false;
        }
    };

    events.onFormSubmit.order = 1;
    events.onSubmitClick.order = 1;
    events.onLinkClick.order = 1;

    var methods = {
        init: function (options) {
            var settings = $.extend({}, defaults, options);
            $("body").data("handleDomManips", true);
            return this.each(function () {
                var obj = $(this);
                var id = obj.prop("id");
                panels[id] = obj;
                var opts = $.extend({}, settings, { self: obj, currentUrl: window.location.href, state: "ready" });
                obj.data(dataParameterName, opts);
                methods.bindHandlers.call(obj, options);
            });
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
                    var cur = new Url();
                    obj.find("a").each(function () {
                        var $this = $(this);
                        var u = new Url(qualifyURL($this.prop("href")));
                        if (u._baseUrl != cur._baseUrl) {
                            u.removeParameter("FormFilter");
                            $this.prop("href", u.toString());
                        }
                    });
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

            obj.find("input[type='submit'], input[type='image']").each(function () {
                var control = $(this);
                if (control.data(dataParameterName)) return;
                control.data(dataParameterName, data);
                control.bind("click", data, events.onSubmitClick);
            });

            obj.find("form").each(function () {
                var control = $(this);
                if (control.data(dataParameterName)) return;
                control.data(dataParameterName, data);
                control.bind("submit", data, events.onFormSubmit);
            });

            obj.find("select[data-grid-size]").each(function () {
                $(this).data("ccsChangeSize", function (formName, size) {
                    var u = new Url(data.currentUrl);
                    u.parameter(formName + "PageSize", size);
                    u.parameter(formName + "Page", 1);
                    methods.refresh.call(obj, { url: u.toString() });
                });
            });

            obj.find("input[data-grid-page]").each(function () {
                $(this).data("ccsChangePage", function (formName, page) {
                    var u = new Url(data.currentUrl);
                    u.parameter(formName + "Page", page);
                    methods.refresh.call(obj, { url: u.toString() });
                });
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