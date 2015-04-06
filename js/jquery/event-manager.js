if (!window.ccsJQuery)
    window.ccsJQuery = jQuery;
else
    $ = jQuery = window.ccsJQuery;

(function ($) {
    var UrlParameter = function(param) {
        if (arguments.length == 0) return this;
        var parts = [];
        if (param == "") {
            this.isEmpty = true;
        } else if (arguments.length == 2) {
            parts = arguments;
        } else if (typeof(param) == "string") {
            parts = param.replace( /\+/ig , " ").split("=");
            this.name = decodeURIComponent(parts[0]);
            this.value = decodeURIComponent(parts[1]);
            return this;
        } else {
            parts = param;
        };
        this.name = parts[0];
        this.value = parts[1];
        return this;
    };

    UrlParameter.prototype.toString = function() {
        if (this.isEmpty)
            return "";
        return encodeURIComponent(this.name) + "=" + encodeURIComponent(this.value);
    };

    var Url = function(url) {
        if (!url)
            url = window.location.href;

        var anchorIndex = url.indexOf("#");
        if (anchorIndex != -1) {
            this._anchor = url.substring(anchorIndex + 1);
            url = url.substring(0, anchorIndex);
        }

        this._params = [];
        var paramIndex = url.indexOf("?");
        if (paramIndex != -1) {
            this._baseUrl = url.substring(0, paramIndex);
            this.params(url.substring(paramIndex + 1));
        } else {
            this._baseUrl = url;
        }
    };

    Url.prototype.params = function(params) {
        var parts = params.split("&");
        for (var i = 0; i < parts.length; i++) {
            this.addParameter(parts[i]);
        };
    };

    Url.prototype.addParameter = function() {
        var param = UrlParameter.apply(new UrlParameter(), arguments);
        this._params.push(param);
        return param;
    };

    Url.prototype.removeParameter = function(name) {
        var newParams = [];
        for (var i = 0; i < this._params.length; i++) {
            var param = this._params[i];
            if (param.name != name)
                newParams.push(param);
        };
        this._params = newParams;
    };

    Url.prototype.getParameter = function(name) {
        for (var i = 0; i < this._params.length; i++) {
            var param = this._params[i];
            if (param.name == name)
                return param;
        };
        return null;
    };

    Url.prototype.getParameterValue = function(name) {
        var p = this.getParameter(name);
        return (!p) ? null : p.value;
    };

    Url.prototype.parameter = function(name, value) {
        var p = this.getParameter(name);
        if (arguments.length == 2) {
            if (p)
                p.value = value;
            else
                this.addParameter(name, value);
            return value;
        } else {
            return (!p) ? null : p.value;
        }
    };

    Url.prototype.anchor = function(anchor) {
        if (arguments.length > 0)
            this._anchor = anchor;
        return this._anchor;
    };

    Url.prototype.parameterString = function() {
        var r = [];
        for (var i = 0; i < this._params.length; i++) {
            r.push(this._params[i].toString());
        }
        return r.join("&");
    };

    Url.prototype.toString = function() {
        var res = this._baseUrl;
        if (this._params.length > 0)
            res += "?" + this.parameterString();
        if (this._anchor)
            res += "#" + this._anchor;
        return res;
    };

    $.fn.Url = Url;
})(jQuery);


(function ($) {
    
    var methods = {};

    var binds = {};

    methods.bind = function (func) {
        func.call(this);
        if (!binds[this.selector])
            binds[this.selector] = [];
        binds[this.selector].push(func);
    };

    methods.reBind = function () {
        var c = this;
        $.each(binds, function (selector) {
            $.each(this, function () {
                this.call(c.find(selector));
            });
        });
    };

    $.fn.ccsBind = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'function' || !method) {
            return methods.bind.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.ccsBind');
            return $;
        };
    };
})(jQuery);



(function ($) {
    
    function stringToRegExp(string) {
        var str = String(string);
        str = str.replace(/\\/g, "\\\\");
        str = str.replace(/\//g, "\\/");
        str = str.replace(/\./g, "\\.");
        str = str.replace(/\(/g, "\\(");
        str = str.replace(/\)/g, "\\)");
        str = str.replace(/\[/g, "\\[");
        str = str.replace(/\]/g, "\\]");
        return str;
    };

    function parseParams(text,substitutions) {
        // replace the {0}, ... with corresponded substitution string and return the result
        var resString = text;
        if (resString != "" && substitutions != null) {
            var array = (typeof(substitutions) != "object") ? (new Array(substitutions)) : substitutions;
            var icount = array.length;
            for (var i = 0; i < icount; i++)
                resString = resString.replace("{" + i + "}", array[i]);
            delete array;
            array = null;
        }
        return resString;
    };
    
    function ccsShowError(control, msg) {
        alert(msg);
        control.focus();
        return false;
    };

    function validate(control) {
        
        var errorMessage = control.data("ccsErrorMessage");
        var customErrorMessage = (typeof(errorMessage) != "undefined");

        var ccsCaption = control.data("ccsCaption");
        var ccsRequired = control.data("ccsRequired");
        var ccsMinLength = control.data("ccsMinLength");
        var ccsMaxLength = control.data("ccsMaxLength");
        var ccsInputMask = control.data("ccsInputMask");
        var ccsRegExp = control.data("ccsRegExp");
        var ccsDateFormat = control.data("ccsDateFormat");
        var ccsValidator = control.data("ccsValidator");
        var value = control.val();


        if (typeof(ccsRequired) == "boolean" && ccsRequired)
            if (value == "")
                return ccsShowError(control, customErrorMessage ? errorMessage :
                    parseParams(Globalize.cultures["default"].messages["CCS_RequiredField"], ccsCaption));

        if (typeof(ccsMinLength) == "number")
            if (value != "" && value.length < parseInt(ccsMinLength))
                return ccsShowError(control, customErrorMessage ? errorMessage :
                    parseParams(Globalize.cultures["default"].messages["CCS_MinimumLength"], [ccsCaption, parseInt(ccsMinLength)]));

        if (typeof(ccsMaxLength) == "number")
            if (value != "" && value.length > parseInt(ccsMaxLength))
                return ccsShowError(control, customErrorMessage ? errorMessage :
                    parseParams(Globalize.cultures["default"].messages["CCS_MaximumLength"], [ccsCaption, parseInt(ccsMaxLength)]));

        if (typeof(ccsInputMask) == "string") {
            var mask = ccsInputMask;
            var maskRE = new RegExp(stringToRegExp(mask).replace(/\u01a0/g, "\\d").replace(/\u01a0/g, "[A-Za-z]"), "i");
            if (value != "" && (value.search(maskRE) == -1))
                return ccsShowError(control, customErrorMessage ? errorMessage :
                    parseParams(Globalize.cultures["default"].messages["CCS_IncorrectValue"], ccsCaption));
        }

        if (typeof(ccsRegExp) == "string")
            if (value != "" && (value.search(new RegExp(ccsRegExp, "i")) == -1))
                return ccsShowError(control, customErrorMessage ? errorMessage :
                    parseParams(Globalize.cultures["default"].messages["CCS_IncorrectValue"], ccsCaption));

        if (typeof(ccsDateFormat) == "string") {
            if (value != "" && ! Globalize.parseDate(value, ccsDateFormat) )
                return ccsShowError(control, customErrorMessage ? errorMessage :
                    parseParams(Globalize.cultures["default"].messages["CCS_IncorrectFormat"], [ccsCaption, ccsDateFormat]));
        }

        if (typeof(ccsValidator) == "function")
            if (!ccsValidator())
                return ccsShowError(control, customErrorMessage ? errorMessage :
                    parseParams(Globalize.cultures["default"].messages["CCS_IncorrectValue"], ccsCaption));

        return true;
    };
    
    $.fn.ccsValidate = function () {
        if ($("body").data("disableValidation")) return true;
        var res = true;
        $(this).each(function(){
            $(this).find("*").each(function(){
                res = res && validate($(this)); 
            });
        });
        return res;
    };
})(jQuery);

(function ($) {
    $(function() {
        var Url = $.fn.Url;
        var changeSizeHandler = function(formName, size) {
            var u = new Url(window.location.href.toString());
            u.parameter(formName + "PageSize", size);
            u.parameter(formName + "Page", 1);
            window.location.href = u.toString();
        };
        $("select[data-grid-size]").ccsBind(function() {
            $(this).bind("change", function() {
                var obj = $(this);
                var handler = obj.data("ccsChangeSize");
                if (handler)
                    handler(obj.data("grid-size"), obj.val());
            });
            $(this).each(function() {
                if (!$(this).data("ccsChangeSize"))
                    $(this).data("ccsChangeSize", changeSizeHandler);
            });
        });
        
        var changePageHandler = function(formName, page) {
            var u = new Url(window.location.href.toString());
            u.parameter(formName + "Page", page);
            window.location.href = u.toString();
        };
        $("input[data-grid-page]").ccsBind(function() {
            $(this).bind("click", function() {
                var obj = $(this);
                var handler = obj.data("ccsChangePage");
                if (handler)
                    handler(obj.data("grid-page"),  obj.parent().find("input[name='" + obj.data("grid-page") + "Page']").val());
            });
            $(this).each(function() {
                if (!$(this).data("ccsChangePage"))
                    $(this).data("ccsChangePage", changePageHandler);
            });
        });

        $("div[data-emulate-form]").ccsBind(function() {
            this.each(function() {
                var self = $(this);
                self.find("input[type='submit'], input[type='image']").click(function() {
                    $("form[data-need-form-emulation]").data("emulate-form", self);
                });
            });
        });

        $("form[data-need-form-emulation]").bind("submit", function () {
            return $($(this).data("emulate-form")).triggerHandler($.Event("submit"), {});
        });
    });
})(jQuery);


(function($) {
    var isO = !!window.opera, isG = (navigator.userAgent.indexOf("Firefox") > -1);

    var nonConstantCharacters = { "\u01a0": "\u01a0", "\u01a1": "\u01a1" };

    function isConstantCharacter(mask, num) {
        return !nonConstantCharacters[mask.charAt(num)];
    };

    var keys = {
        "backspace": 8, //  backspace
        "tab": 9, //  tab
        "enter": 13, //  enter
        "shift": 16, //  shift
        "ctrl": 17, //  ctrl
        "alt": 18, //  alt
        "pauseBreak": 19, //  pause/break
        "capsLock": 20, //  caps lock
        "escape": 27, //  escape
        "pageUp": 33, // page up, to avoid displaying alternate character and confusing people	         
        "pageDown": 34, // page down
        "end": 35, // end
        "home": 36, // home
        "leftArrow": 37, // left arrow
        "upArrow": 38, // up arrow
        "rightArrow": 39, // right arrow
        "downArrow": 40, // down arrow
        "insert": 45, // insert
        "delete": 46, // delete
        "del": 46, // delete
        "leftWindow": 91, // left window
        "rightWindow": 92, // right window
        "selectKey": 93, // select key
        "numpad0": 96, // numpad 0
        "numpad1": 97, // numpad 1
        "numpad2": 98, // numpad 2
        "numpad3": 99, // numpad 3
        "numpad4": 100, // numpad 4
        "numpad5": 101, // numpad 5
        "numpad6": 102, // numpad 6
        "numpad7": 103, // numpad 7
        "numpad8": 104, // numpad 8
        "numpad9": 105, // numpad 9
        "multiply": 106, // multiply
        "add": 107, // add
        "subtract": 109, // subtract
        "decimalPoint": 110, // decimal point
        "divide": 111, // divide
        "F1": 112, // F1
        "F2": 113, // F2
        "F3": 114, // F3
        "F4": 115, // F4
        "F5": 116, // F5
        "F6": 117, // F6
        "F7": 118, // F7
        "F8": 119, // F8
        "F9": 120, // F9
        "F10": 121, // F10
        "F11": 122, // F11
        "F12": 123, // F12
        "numLock": 144, // num lock
        "scrollLock": 145, // scroll lock
        ";": 186, // semi-colon
        "=": 187, // equal-sign
        ",": 188, // comma
        "-": 189, // dash
        ".": 190, // period
        "/": 191, // forward slash
        "`": 192, // grave accent
        "[": 219, // open bracket
        "\\": 220, // back slash
        "]": 221, // close bracket
        "'": 222 // single quote
    };

    function caret(element, begin, end) {
        if (typeof begin == 'number') {
            end = (typeof end == 'number') ? end : begin;
            if (element.setSelectionRange) {
                element.focus();
                element.setSelectionRange(begin, end);
            } else if (element.createTextRange) {
                var range = element.createTextRange();
                range.collapse(true);
                range.moveEnd('character', end);
                range.moveStart('character', begin);
                range.select();
            }
        } else {
            if (element.setSelectionRange) {
                begin = element.selectionStart;
                end = element.selectionEnd;
            } else if (document.selection && document.selection.createRange) {
                var range = document.selection.createRange();
                begin = 0 - range.duplicate().moveStart('character', -100000);
                end = begin + range.text.length;
            }
            return { begin: begin, end: end };
        }
    };

    function getKeycode(e) {
        if (window.event && window.event.keyCode)
            return window.event.keyCode;
        else
            return e.which;
    };

    function genSequence(str, times) {
        var res = '';
        for (var i = 0; i < times; i++)
            res += str;
        return res;
    };

    function sType(c, placeholder) {
        if (c.charAt(0).match(/[0-9]/g)) return '\u01a0';
        if (c.charAt(0).match(/[a-zA-Z]/g)) return '\u01a1';
        if (c.charAt(0) == placeholder) return placeholder;
        return '';
    };

    function getValueFromMaskedValue(val, mask, placeholder) {
        var res = "";
        var i;
        for (i = 0; i < val.length; i++)
            if (!isConstantCharacter(mask, i) && sType(val.charAt(i), placeholder) != '')
                res += val.charAt(i);
        return res;
    };

    function maskCanBeApplied(val, mask, placeholder) {
        var i = 0, j = 0, res = '';
        for (i = 0; i < mask.length; i++)
            if (j < val.length && mask.charAt(i) == sType(val.charAt(j), placeholder) && !isConstantCharacter(mask, i)) {
                res += val.charAt(j);
                j++;
            } else if (isConstantCharacter(mask, i)) {
                res += mask.charAt(i);
            } else if (sType(val.charAt(j), placeholder) == placeholder) {
                res += placeholder;
                j++;
            } else if (j >= val.length) {
                res += isConstantCharacter(mask, i) ? mask.charAt(i) : placeholder;
            } else
                return { ans: false };
        return { ans: true, result: res };
    };

    function getNextSymbolPosition(str, start, placeholder) {
        for (var i = start; i < str.length; i++)
            if (sType(str.charAt(i), placeholder) != '')
                return i + 1;
        return start + 1;
    };

    function calculatedValue(e, control, keycode) {
        var s = caret(control);
        var $control = $(control);
        var placeholder = $control.data("ccsPlaceholder");
        var mask = $control.data("ccsInputMaskValue");
        var res;
        var mid = '';
        if (keycode == keys.backspace || keycode == keys.del) {
            if (s.begin == s.end)
                if (keycode == keys.backspace) {
                    s.begin--;
                    for (var i = s.begin; i > 0; i--) {
                        if (!isConstantCharacter(mask, i)) {
                            s.begin = i;
                            $control.data("newCaretPosition", i);
                            break;
                        }
                    }
                } else {
                    s.end++;
                    for (var i = s.end; i < mask.length; i++) {
                        if (!isConstantCharacter(mask, i)) {
                            s.end = i;
                            break;
                        }
                    }
                }
            res = control.value.substring(0, Math.max(0, s.begin));
            for (var i = s.begin; i < s.end; i++) {
                res += isConstantCharacter(mask, i) ? mask.charAt(i) : placeholder;
                if (!$control.data("newCaretPosition"))
                    $control.data("newCaretPosition", i);
            }
            res += control.value.substring(Math.min(s.end, control.value.length));
        } else {
            if (s.begin == s.end)
                s.end++;
            res = control.value.substring(0, s.begin);
            var pos;
            for (pos = s.begin; pos < control.value.length; pos++)
                if (isConstantCharacter(mask, pos))
                    res += mask.charAt(pos);
                else {
                    res += String.fromCharCode(keycode);
                    $control.data("newCaretPosition", pos+1);
                    break;
                }
            if (!$control.data("newCaretPosition"))
                $control.data("newCaretPosition", s.begin);
            for (pos++; pos < s.end; pos++)
                if (isConstantCharacter(mask, pos))
                    res += mask.charAt(pos);
                else
                    res += placeholder;
            res += control.value.substring(pos);
        }
        return res;
    };

    function getNewCaretPosition(control, keycode, newValue) {
        var res;
        var cur = caret(control);
        var $control = $(control);
        var placeholder = $control.data("ccsPlaceholder");
        var mask = $control.data("ccsInputMaskValue");
        if ($control.data("newCaretPosition")) {
            var pos = $control.data("newCaretPosition");
            $control.data("newCaretPosition", null);
            for (var i = pos; i < mask.length; i++)
                if (!isConstantCharacter(mask, i))
                    return i;
            for (var i = mask.length; i >= 0; i--)
                if (!isConstantCharacter(mask, i))
                    return i + 1;
        }

        if (keycode == keys.leftArrow) {
            if (cur.begin != cur.end)
                return cur.begin;
            for (var i = cur.begin - 1; i >= 0; i--)
                if (!isConstantCharacter(mask, i))
                    return i;
            return -1;
        }

        if (keycode == keys.rightArrow) {
            if (cur.begin != cur.end)
                return cur.end;
            for (var i = cur.end + 1; i < mask.length; i++)
                if (!isConstantCharacter(mask, i))
                    return i;
            for (var i = mask.length; i >= 0; i--)
                if (!isConstantCharacter(mask, i))
                    return i + 1;
            return -1;
        }

        if (keycode == keys.backspace) {
            res = cur.begin;
            if (cur.end - cur.begin > 0)
                return cur.begin;
            if (res != 0 && sType(newValue.charAt(res - 1), placeholder) != '')
                return res - 1;
            for (var i = cur.begin - 1; i >= 0; i--)
                if (sType(newValue.charAt(i), placeholder) != '')
                    return i + 1;
            return res;
        } else if (keycode == keys.del) {
            res = cur.begin;
            for (var i = res; i < newValue.length; i++)
                if (sType(newValue.charAt(i), placeholder) != '')
                    return i;
            return newValue.length;
        } else {
            res = cur.begin;
            if (res + 1 == newValue.length)
                return res + 1;
            if (sType(newValue.charAt(res), placeholder) != '')
                return res + 1;
            for (var i = res; i < newValue.length; i++)
                if (sType(newValue.charAt(i), placeholder) != '')
                    return i + 1;
        }
        return -1;
    };

    function doShift(control, clearValue, keycode) {
        if (keycode == keys.del) {
            var $control = $(control);
            var mask = $control.data("ccsInputMaskValue");
            var placeholder = $control.data("ccsPlaceholder");
            var c = caret(control);
            if (c.begin == c.end)
                c.end++;
            var clearC = { };
            var clearMask = "";
            var j = -1;
            for (var i = 0; i<mask.length; i++) {
                if (!isConstantCharacter(mask, i)) {
                    clearMask += mask.charAt(i);
                    j++;
                    if (isNaN(clearC.begin) && i >= c.begin)
                        clearC.begin = j;
                    else if (!isNaN(clearC.begin) && isNaN(clearC.end) && i >= c.end)
                        clearC.end = j;
                }
            }
            var shiftLength = 0;
            var lastChar = clearMask.charAt(clearC.end - 1);
            for (var i = clearC.end-1; i >= clearC.begin; i--) {
                if (clearMask.charAt(i) != lastChar)
                    break;
                shiftLength++;
            }
            var lastShiftPosition;
            for (var i = clearC.end; i < clearMask.length; i++)
                if (clearMask.charAt(i) != lastChar) {
                    lastShiftPosition = i;
                    break;
                }
            if (isNaN(lastShiftPosition))
                lastShiftPosition = clearMask.length;
            var shiftFragment = clearValue.substring(clearC.end - shiftLength, lastShiftPosition);
            var res = clearValue.substring(0, clearC.end - shiftLength);
            res += shiftFragment.substring(shiftLength) + genSequence(placeholder, shiftLength);
            res += clearValue.substring(lastShiftPosition);
            return res;
        }
        return clearValue;
    }

    function inputMask(e, sender) {
        var $sender = $(sender);
        var placeholder = $sender.data("ccsPlaceholder");
        var keycode = getKeycode(e, placeholder);
        if (keycode < 32 && keycode != keys.backspace)
            if (keycode == 0) {
                var mask = $sender.data("ccsInputMaskValue");
                var c = caret(sender);
                var val = $sender.val();
                if (mask.length == val.length)
                    setTimeout(function(parameters) {
                        $sender.val(val);
                        caret(sender, c.begin);
                    }, 100);
                var pasteValue = val.substr(c.begin - 1, 1);
                var newVal = val.substring(0, c.begin - 1);
                for (var i = c.begin; i < mask.length - (val.length - c.begin) + 1; i++)
                    newVal += isConstantCharacter(mask, i) ? mask.charAt(i) : placeholder;
                newVal += val.substring(mask.length - (val.length - c.begin) + 1);
                $sender.val(newVal);
                caret(sender, c.begin - 1);
                keycode = pasteValue.charCodeAt(0);
            } else {
                return true;
            }
        if (!String.fromCharCode(keycode).match(/[a-zA-Z0-9]/g) && keycode != keys.backspace && keycode != keys.del)
            return false;
        var newControlVal = calculatedValue(e, sender, keycode);
        var newVal = getValueFromMaskedValue(newControlVal, $sender.data("ccsInputMaskValue"), placeholder);
        newVal = doShift(sender, newVal, keycode);
        var newMaskedValue = maskCanBeApplied(newVal, $sender.data("ccsInputMaskValue"), placeholder);
        if (newMaskedValue.ans) {
            var cp = getNewCaretPosition(sender, keycode, newMaskedValue.result);
            sender.value = newMaskedValue.result;
            caret(sender, cp);
        }
        return false;
    };

    var events = { };
    events.onkeypress = function(e) {
        var keycode = getKeycode(e);
        if ((e.shiftKey || e.ctrlKey || e.altKey) && (keycode != keys.del && keycode != keys.backspace)) {
            return true;
        }
        return inputMask(e, this);
    };

    events.onkeydown = function(e) {
        var keycode = getKeycode(e);
        if ((e.shiftKey || e.ctrlKey || e.altKey) && (keycode != keys.del && keycode != keys.backspace)) {
            return true;
        }
        if (keycode == keys.backspace && !isG || keycode == keys.del || keycode == 0)
            return inputMask(e, this);
        if ((keycode == keys.leftArrow || keycode == keys.rightArrow) && !e.shiftKey) {
            var pos = getNewCaretPosition(this, keycode);
            if (pos != -1)
                caret(this, pos);
            return false;
        }
    };

    events.onfocus = function(e) {
        var $this = $(this);
        var oThis = this;
        if (this.value == '') {
            this.value = $this.data("blankValue");
            setTimeout(function() {
                caret(oThis, $this.data("blankValue").indexOf($this.data("ccsPlaceholder")));
            }, 10);
        }
    };

    events.onblur = function(e) {
        var $this = $(this);
        if (this.value == $this.data("blankValue"))
            this.value = '';
    };

    events.onpaste = function(e) {
        var $this = $(this);
        var oThis = this;
        var c = caret(this);
        var oldVal = $this.val();
        var placeholder = $this.data("ccsPlaceholder");
        var mask = $this.data("ccsInputMaskValue");
        var last = c.begin;
        setTimeout(function() {
            var pasteValue = $this.val().substring(c.begin, $this.val().length - (oldVal.length - c.end));
            var j = 0;
            var res = oldVal.substring(0, c.begin);
            for (var i = c.begin; i < mask.length; i++) {
                if (j >= pasteValue.length) {
                    res += oldVal.charAt(i);
                } else if (mask.charAt(i) == pasteValue.charAt(j) || (!isConstantCharacter(mask, i) && (pasteValue.charAt(j) == placeholder || sType(pasteValue.charAt(j), placeholder) == mask.charAt(i)))) {
                    res += pasteValue.charAt(j);
                    last = i + 1;
                    j++;
                } else {
                    res += (isConstantCharacter(mask, i) ? mask.charAt(i) : oldVal.charAt(i));
                }
            }
            $this.val(res);
            $this.data("newCaretPosition", last);
            var cu = getNewCaretPosition(oThis);
            if (cu != -1)
                caret(oThis, cu);
        }, 20);
    };

    events.oncut = function(e) {
        var $this = $(this);
        var c = caret(this);
        var placeholder = $this.data("ccsPlaceholder");
        var mask = $this.data("ccsInputMaskValue");
        var res = $this.val().substring(0, c.begin);
        for (var i = c.begin; i < c.end; i++) {
            res += isConstantCharacter(mask, i) ? mask.charAt(i) : placeholder;
        }
        res += $this.val().substring(c.end);
        $this.val(res);
        caret(this, c.begin);
    };


    var defaults = {
        placeholder: "_"
    };

    var methods = {
        init: function(options) {
            var settings = $.extend({ }, defaults, options);

            return this.each(function() {
                var control = $(this);
                control.data("ccsPlaceholder", settings.placeholder);
                if (settings.inputMask)
                    control.data("ccsInputMaskValue", settings.inputMask);
                var res = '';
                for (var i = 0; i < settings.inputMask.length; i++)
                    if (!isConstantCharacter(settings.inputMask, i))
                        res += settings.placeholder;
                    else
                        res += settings.inputMask.charAt(i);
                control.data("blankValue", res);
                control.bind("keypress", events.onkeypress);
                control.bind("keydown", events.onkeydown);
                control.bind("focus", events.onfocus);
                control.bind("blur", events.onblur);
                control.bind("paste", events.onpaste);
                control.bind("cut", events.oncut);
            });
        }
    };

    $.fn.ccsInputMask = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            return methods.init.call(this, { inputMask: method });
        }
    };
})(jQuery);


/*
    json2.js
    2011-10-19

    Public Domain.
*/

var JSON;
if (!JSON) {
    JSON = {};
}

(function () {

    function f(n) {
        return n < 10 ? '0' + n : n;
    }

    if (typeof Date.prototype.toJSON !== 'function') {

        Date.prototype.toJSON = function (key) {

            return isFinite(this.valueOf())
                ? this.getUTCFullYear()     + '-' +
                    f(this.getUTCMonth() + 1) + '-' +
                    f(this.getUTCDate())      + 'T' +
                    f(this.getUTCHours())     + ':' +
                    f(this.getUTCMinutes())   + ':' +
                    f(this.getUTCSeconds())   + 'Z'
                : null;
        };

        String.prototype.toJSON      =
            Number.prototype.toJSON  =
            Boolean.prototype.toJSON = function (key) {
                return this.valueOf();
            };
    }

    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap,
        indent,
        meta = {
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        rep;

    function quote(string) {

        escapable.lastIndex = 0;
        return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
            var c = meta[a];
            return typeof c === 'string'
                ? c
                : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
        }) + '"' : '"' + string + '"';
    }


    function str(key, holder) {
        var i,
            k,
            v,
            length,
            mind = gap,
            partial,
            value = holder[key];

        if (value && typeof value === 'object' &&
                typeof value.toJSON === 'function') {
            value = value.toJSON(key);
        }


        if (typeof rep === 'function') {
            value = rep.call(holder, key, value);
        }

        switch (typeof value) {
        case 'string':
            return quote(value);

        case 'number':

            return isFinite(value) ? String(value) : 'null';

        case 'boolean':
        case 'null':
            return String(value);
        case 'object':
            if (!value) {
                return 'null';
            }
            gap += indent;
            partial = [];
            if (Object.prototype.toString.apply(value) === '[object Array]') {
                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || 'null';
                }
                v = partial.length === 0
                    ? '[]'
                    : gap
                    ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']'
                    : '[' + partial.join(',') + ']';
                gap = mind;
                return v;
            }
            if (rep && typeof rep === 'object') {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    if (typeof rep[i] === 'string') {
                        k = rep[i];
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            } else {
                for (k in value) {
                    if (Object.prototype.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }
            v = partial.length === 0
                ? '{}'
                : gap
                ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}'
                : '{' + partial.join(',') + '}';
            gap = mind;
            return v;
        }
    }
    if (typeof JSON.stringify !== 'function') {
        JSON.stringify = function (value, replacer, space) {
            var i;
            gap = '';
            indent = '';
            if (typeof space === 'number') {
                for (i = 0; i < space; i += 1) {
                    indent += ' ';
                }
            } else if (typeof space === 'string') {
                indent = space;
            }
            rep = replacer;
            if (replacer && typeof replacer !== 'function' &&
                    (typeof replacer !== 'object' ||
                    typeof replacer.length !== 'number')) {
                throw new Error('JSON.stringify');
            }
            return str('', {'': value});
        };
    }
    if (typeof JSON.parse !== 'function') {
        JSON.parse = function (text, reviver) {
            var j;

            function walk(holder, key) {
                var k, v, value = holder[key];
                if (value && typeof value === 'object') {
                    for (k in value) {
                        if (Object.prototype.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v;
                            } else {
                                delete value[k];
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value);
            }
            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function (a) {
                    return '\\u' +
                        ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
                });
            }
            if (/^[\],:{}\s]*$/
                    .test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
                        .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
                        .replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                j = eval('(' + text + ')');
                return typeof reviver === 'function'
                    ? walk({'': j}, '')
                    : j;
            }
            throw new SyntaxError('JSON.parse');
        };
    }
}());