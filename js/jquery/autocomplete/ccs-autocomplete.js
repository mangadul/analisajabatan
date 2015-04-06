(function ($) {
    var dataParameterName = "ccsAutoComplete";
    // BEGIN default templates
    var defaultTemplates = {
        "{@text}" : '<a>{@label}</a>', // format labels '{@value}'... 
        "{@text_desc}" : '<a><div class="ccs-item-container"><div class="ccs-label">{@label}</div><div class="ccs-description">{@desc}</div></div></a>',
        "{@image_text_desc}" : '<a><div class="ccs-item-container"><div class="ccs-image"><img src="{@image}"/></div><div class="ccs-label">{@label}</div><div class="ccs-description">{@desc}</div></div></a>'
    }
    // END default templates
    var defaults = {
        value: "value"
    };

    var events = {
        onSelect: function (event, ui) {
            var settings = $(this).data(dataParameterName);
            //settings.self.val(ui.item.value);    
            settings.self.val(ui.item[settings.value]);
            settings.self.trigger($.Event("itemselect.ccsAutoComplete"), [ui]);
            return false;
        }
    };

    var methods = {
        init: function (options) {
            var settings = $.extend({}, defaults, { select: events.onSelect }); // define method select
            settings = $.extend({}, settings, options);
            if (!settings.template) {
                settings.template = defaultTemplates["{@text}"];
            }
            if (defaultTemplates[settings.template]) {
                settings.template = defaultTemplates[settings.template];
            }
            
            return $(this).each(function () {
                var opts = $.extend({}, settings, {
                    self: $(this)
                });
                $(this).data(dataParameterName, opts);
                $(this).autocomplete(opts);
                $(this).data("ui-autocomplete").menu.element.addClass(opts["customCssClassName"]);
            });
        },

        fillItem: function (template, item) {
            var result = template;
            $.each(item, function (key, value) {
                result = result.replace(new RegExp("\{@" + key + "\}", "g"), value);
            });
            return result;
        },

        renderItem: function (ul, item) {
            var innerHtml = methods.fillItem(this.options.template, item);
            return $("<li></li>").data("item.autocomplete", item).append(innerHtml).appendTo(ul);
        },

        normalize: function (items) {
            if (items.length && items[0].label && items[0].value) {
                return items;
            }
            var itemNames = new Array();
            if (!!items[0]) {
                $.each(items[0], function (key, value) {
                    itemNames[itemNames.length] = key;
                });
            }
            return $.map(items, function (item) {
                if (typeof item === "string") {
                    return {
                        label: item,
                        value: item
                    };
                }
                if (itemNames.length == 1) {
                    return $.extend({
                        label: item[itemNames[0]],
                        value: item[itemNames[0]]
                    }, item);
                }
                return $.extend({
                    label: item.label || item.value,
                    value: item.value || item.label
                }, item);
            });
        }

    };

    $.ui.autocomplete.prototype._renderItem = methods.renderItem;
    $.ui.autocomplete.prototype._normalize = methods.normalize;

    $.fn.ccsAutoComplete = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.ccsAutoComplete');
            return $;
        };
    };
})(jQuery);