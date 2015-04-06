(function ($) {
    var dataParameterName = "ccsTabs";
    var activeTabIndex = -1;
    var defaults = {
        template: '<li><a href="#{@id}" data-do-not-refresh="do-not-refresh">{@caption}</a></li>',   // format {@placeholder_name}
        tabs: [],   // format [{ selector: "selector", caption: "name" }, ...]
        rememberActiveTab: true
    };

    var events = {
        onTabSelect: function (event, ui) {
            var settings = $(this).data(dataParameterName);
            ui.newPanel.trigger($.Event("select.ccsTabs"), [ui]);
            if (settings.rememberActiveTab) {
                activeTabIndex = ui.newTab.index();
                // $.cookie("ccsActiveTab-" + settings.self.ccsId(), ui.newTab.index(), { expires: 1, path: '/' }); // store cookie for a day, without, it would be a session cookie
            }
        }
    };
    var methods = {
        init: function (options) {
            var settings = $.extend({}, defaults, options);
            var parent = $(this);
            settings = $.extend({}, settings, { self: parent });
            /* if ('undefined' !== typeof $.cookie("ccsActiveTab-" + parent.ccsId())) {
                activeTabIndex = parseInt($.cookie("ccsActiveTab-" + parent.ccsId()), 10);
            } */
            if (settings.rememberActiveTab && activeTabIndex != -1) {
                settings = $.extend({}, settings, { active: activeTabIndex });
            }
            parent.data(dataParameterName, settings);
            methods.prepare(parent, settings);
            parent.tabs(settings);
            parent.bind("tabsactivate", events.onTabSelect);
            return parent;
        },
        fill: function (options) {
            var result = "";
            $.each(options.tabs, function (i, item) {
                $(item.selector).each(function (j, element) {
                    if (!!element.id) {
                        result += options.template.replace(new RegExp("\{@id\}", "g"), element.id).replace(new RegExp("\{@caption\}", "g"), !item.caption ? element.id : item.caption);
                        $(this).data(dataParameterName, options);
                    }
                });
            });
            return result;
        },
        prepare: function (self, options) {
            var content = methods.fill(options);
            $("<ul></ul>").append(content).prependTo(self);
        },
        activate: function () {
            var settings = $(this).data(dataParameterName);
            var index = $(this).index();
            if (index) {
                settings.self.tabs("option", "active", index - 1);
            }
        }

    };

    $.fn.ccsTabs = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.ccsTabs');
            return $;
        };
    };
})(jQuery);