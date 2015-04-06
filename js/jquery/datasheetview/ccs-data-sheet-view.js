(function ($) {
    var dataParameterName = "ccsDataSheetView";
    var className = "gw";

    var defaults = {
        deleteControl: "*",
        submitControl: "*",
        controls: [],
        autoSubmit: false
    };

    var events = {
    };

    var lastClickedId = "";

    function prepareToHtml(val) {
        return val.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\r?\n/g, "<br>");
    }

    function getVal(t) {
        if (t.length > 0 && t[0].tagName.toLowerCase() == "select")
            return prepareToHtml(t.find('option:selected').text());
        return prepareToHtml(t.val());
    }

    function initEditableControls(controls, button, autoSubmit, editableGrid) {
        controls.each(function () {
            var self = this;
            var tagName = self.tagName.toLowerCase();
            var $this = $(this);
            var bigDiv = $("<div class='" + className + "_par'></div>");
            bigDiv.insertAfter($this);
            bigDiv.append($this);
            var h = bigDiv.height();
            if (tagName != "textarea")
                bigDiv.height(bigDiv.height());
            bigDiv.width(bigDiv.width());
            var span = $("<div class='" + className + "'>" + getVal($this) + "</div>");
            var oldVal = getVal($this);
            span.insertAfter($this);
            $this.hide();
            if (tagName == "textarea") {
                bigDiv.height(Math.max(bigDiv.height(), h));
                $this.height("100%");
                $this.css("box-sizing", "border-box");
            }
            if (lastClickedId == $this.ccsId()) {
                $this.show();
                span.hide();
                $this.focus();
            }
            span.click(function () {
                lastClickedId = $this.ccsId();
                if (editableGrid.data("nowSubmitting"))
                    return;
                if (tagName == "textarea") {
                    $this.css("box-sizing", "content-box");
                    $this.height(span.height());
                    $this.css("box-sizing", "border-box");
                }
                span.hide();
                $this.show();
                $this.focus();
            });
            var changeFunc = function () {
                $this.hide();
                span.show();
                lastClickedId = "";
                if (oldVal != getVal($this)) {
                    span.html(getVal($this));
                    if (autoSubmit) {
                        button.click();
                        editableGrid.data("nowSubmitting", true);
                    }
                    oldVal = getVal($this);
                }
            };
            $this.blur(changeFunc);
            if (tagName == "select")
                $this.change(changeFunc);
            $this.keypress(function (event) {
                if (event.which == 13 && tagName != "textarea") {
                    event.preventDefault();
                    changeFunc();
                }
            });
        });
    }

    function enlargeDivs() {
        $("." + className + "_par").each(function () {
            var $this = $(this);
            var parent = $this.parent();
            $this.height(parent.height());
            $this.css("box-sizing", "border-box");
        });
        $("." + className).each(function () {
            var $this = $(this);
            var parent = $this.parent();
            $this.height(parent.height());
        });
    }

    var methods = {
        init: function (options) {
            var settings = $.extend({}, defaults, options);
            $("body").data("handleDomManips", false);
            var res = this.each(function () {
                var obj = $(this);
                obj.data("nowSubmitting", false);
                var button = $(settings.submitControl);
                if (settings.autoSubmit)
                    button.hide();
                for (var i = 0; i < settings.controls.length; i++)
                    initEditableControls($(settings.controls[i]), button, settings.autoSubmit, obj);
                $(settings.deleteControl).each(function () {
                    var $this = $(this);
                    var link = $("<a href='#' data-do-not-refresh='data-do-not-refresh'>Delete</a>");
                    $this.hide();
                    link.insertAfter($this);
                    link.click(function () {
                        $this.prop('checked', true);
                        button.click();
                        return false;
                    });
                });
            });
            enlargeDivs();
            $("body").data("handleDomManips", true);
            return res;
        }
    };

    $.fn.ccsDataSheetView = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.ccsDataSheetView');
            return $;
        };
    };
})(jQuery);