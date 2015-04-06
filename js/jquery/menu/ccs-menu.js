(function ($) {
    function isIncluded(href1, href2) {
        if (href1 == null || href2 == null)
            return href1 == href2;
        if (href1.indexOf("?") == -1 || href1.split("?")[1] == "")
            return href1.split("?")[0] == href2.split("?")[0];
        if (href2.indexOf("?") == -1 || href2.split("?")[1] == "")
            return href1.replace("?", "") == href2.replace("?", "");
        if (href1.split("?")[0] != href2.split("?")[0])
            return false;
        var params = href1.split("?")[1];
        params = params.split("&");
        var i, par1, par2, nv;
        par1 = new Array();
        for (i in params) {
            if (typeof (params[i]) == "function")
                continue;
            nv = params[i].split("=");
            if (nv[0] != "FormFilter")
                par1[nv[0]] = nv[1];
        }
        params = href2.split("?")[1];
        params = params.split("&");
        par2 = new Array();
        for (i in params) {
            if (typeof (params[i]) == "function")
                continue;
            nv = params[i].split("=");
            if (nv[0] != "FormFilter")
                par2[nv[0]] = nv[1];
        }
        /*if (par1.length != par2.length)
        return false;*/
        for (i in par1)
            if (par1[i] != par2[i])
                return false;
        return true;
    };
    /*- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    WCH.js - Windowed Controls Hider v3.20
    www.aplus.co.yu/wch/
    - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    (c) Copyright 2003 and on, Aleksandar Vacic, www.aplus.co.yu
    This work is licensed under the Creative Commons Attribution License.
    To view a copy of this license, visit http://creativecommons.org/licenses/by/2.0/ or
    send a letter to Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA
    - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    Credits: Mike Foster for x functions (cross-browser.com)
    Credits: Tim Connor for short and sweet way of dealing with IE5.0 - dynamic creation of style rule (www.infosauce.com)
    - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    Based on idea presented by Joe King. Works with IE5.0+/Win
    IE 5.5+: place iFrame below the layer to hide windowed controls
    IE 5.0 : hide/show all elements that have "WCHhider" class
    - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
    var WCH_Constructor = function () {
        //	exit point for anything but IE5.0+/Win
        if (!(document.all && document.getElementById && !window.opera && navigator.userAgent.toLowerCase().indexOf("mac") == -1)) {
            this.Apply = function () {
            };
            this.Discard = function () {
            };
            return;
        }

        //	private properties
        var _bIE55 = false;
        var _bIE6 = false;
        var _oRule = null;
        var _bSetup = true;
        var _oSelf = this;

        //	public: hides windowed controls
        this.Apply = function (vLayer, vContainer, bResize) {
            if (_bSetup) _Setup();

            if (_bIE55 && (oIframe = _Hider(vLayer, vContainer, bResize))) {
                oIframe.style.visibility = "visible";
            } else if (_oRule != null) {
                _oRule.style.visibility = "hidden";
            }

        };

        //	public: shows windowed controls
        this.Discard = function (vLayer, vContainer) {
            if (_bIE55 && (oIframe = _Hider(vLayer, vContainer, false))) {
                oIframe.style.visibility = "hidden";
            } else if (_oRule != null) {
                _oRule.style.visibility = "visible";
            }
        };

        //	private: returns iFrame reference for IE5.5+

        function _Hider(vLayer, vContainer, bResize) {
            var oLayer = _GetObj(vLayer);
            var oContainer = ((oTmp = _GetObj(vContainer)) ? oTmp : document.getElementsByTagName("body")[0]);
            if (!oLayer || !oContainer) return;

            //	is it there already?
            //		1. first check does the layer has an ID at all. if not, assign one, using current timestamp, so we avoid duplicates
            if (oLayer.id == "")
                oLayer.id = "WCHid" + (new Date()).getTime();
            //		2. then try to locate the hiding iFrame
            var oIframe = document.getElementById("WCHhider" + oLayer.id);

            //	if not, create it
            if (!oIframe) {
                //	IE 6 has this property, IE 5 not. IE 5.5(even SP2) crashes when filter is applied, hence the check
                var sFilter = (_bIE6) ? "filter:progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0);" : "";
                //	get z-index of the object
                var zIndex = oLayer.style.zIndex;
                if (zIndex == "") zIndex = oLayer.currentStyle.zIndex;
                zIndex = parseInt(zIndex);
                //	if no z-index, do nothing
                if (isNaN(zIndex)) return null;
                //	if z-index is below 2, do nothing (no room for Hider)
                if (zIndex < 2) return null;
                //	go one step below for Hider
                zIndex--;
                var sHiderID = "WCHhider" + oLayer.id;
                oContainer.insertAdjacentHTML("afterBegin", '<iframe class="WCHiframe" src="javascript:false;" id="' + sHiderID + '" scroll="no" frameborder="0" style="position:absolute;visibility:hidden;' + sFilter + 'border:0;top:0;left;0;width:0;height:0;background-color:#ccc;z-index:' + zIndex + ';"></iframe>');
                oIframe = document.getElementById(sHiderID);
                //	then do calculation
                _SetPos(oIframe, oLayer);
            } else if (bResize) {
                //	resize the iFrame if asked
                _SetPos(oIframe, oLayer);
            }
            return oIframe;
        }

        ;

        //	private: set size and position of the Hider

        function _SetPos(oIframe, oLayer) {
            //	fetch and set size
            oIframe.style.width = oLayer.offsetWidth + "px";
            oIframe.style.height = oLayer.offsetHeight + "px";
            //	move to specified position
            oIframe.style.left = oLayer.offsetLeft + "px";
            oIframe.style.top = oLayer.offsetTop + "px";
        }

        ;

        //	private: returns object reference

        function _GetObj(vObj) {
            var oObj = null;
            switch (typeof (vObj)) {
                case "object":
                    oObj = vObj;
                    break;
                case "string":
                    oObj = document.getElementById(vObj);
                    break;
            }
            return oObj;
        }

        ;

        //	private: setup properties on first call to Apply

        function _Setup() {
            _bIE55 = (typeof (document.body.contentEditable) != "undefined");
            _bIE6 = (typeof (document.compatMode) != "undefined");

            if (!_bIE55) {
                if (document.styleSheets.length == 0)
                    document.createStyleSheet();
                var oSheet = document.styleSheets[0];
                oSheet.addRule(".WCHhider", "visibility:visible");
                _oRule = oSheet.rules(oSheet.rules.length - 1);
            }

            _bSetup = false;
        }

        ;
    };
    var WCH = new WCH_Constructor();


    /* ADxMenu_IESetup - (c) Copyright 2003, Aleksandar Vacic, www.aplus.co.yu */

    function ADxMenu_IESetup(id) {

        var aTmp2, i, j, oLI, aUL, aA;
        var aTmp = document.getElementById(id);

        aTmp2 = aTmp.getElementsByTagName("li");
        for (j = 0; j < aTmp2.length; j++) {
            oLI = aTmp2[j];
            aUL = oLI.getElementsByTagName("ul");
            //	if item has submenu, then make the item hoverable
            if (aUL && aUL.length) {
                oLI.UL = aUL[0]; //	direct submenu
                aA = oLI.getElementsByTagName("a");
                if (aA && aA.length)
                    oLI.A = aA[0]; //	direct child link
                //	li:hover
                oLI.onmouseenter = function () {
                    this.className += " adxmhover";
                    this.UL.className += " adxmhoverUL";
                    if (this.A) this.A.className += " adxmhoverA";
                    if (WCH) WCH.Apply(this.UL, this, true);
                };
                //	li:blur
                oLI.onmouseleave = function () {
                    this.className = this.className.replace(/adxmhover/, "");
                    this.UL.className = this.UL.className.replace(/adxmhoverUL/, "");
                    if (this.A) this.A.className = this.A.className.replace(/adxmhoverA/, "");
                    if (WCH) WCH.Discard(this.UL, this);
                };
            }
        } //for-li.submenu
    }

    //	adds support for WCH. if you need WCH, then load WCH.js BEFORE this file
    if (typeof (WCH) == "undefined") WCH = null;

    /*	xGetElementsByClassName()
    Returns an array of elements which are
    descendants of parentEle and have tagName and clsName.
    If parentEle is null or not present, document will be used.
    if tagName is null or not present, "*" will be used.
    credits: Mike Foster, cross-browser.com.
    */

    function xGetElementsByClassName(clsName, parentEle, tagName) {
        var elements = null;
        var found = new Array();
        var re = new RegExp('\\b' + clsName + '\\b');
        if (!parentEle) parentEle = document;
        if (!tagName) tagName = '*';
        if (parentEle.getElementsByTagName) {
            elements = parentEle.getElementsByTagName(tagName);
        } else if (document.all) {
            elements = document.all.tags(tagName);
        }
        if (elements) {
            for (var i = 0; i < elements.length; ++i) {
                if (elements[i].className.search(re) != -1) {
                    found[found.length] = elements[i];
                }
            }
        }
        return found;
    }

    function CCSMenu_TreeMenuSetup(id) {
        var treeMenu = document.getElementById(id);
        if (treeMenu.getElementsByTagName("ul")[0].className.indexOf("menu_vlr_tree") == -1) return;
        var childNodes = treeMenu.getElementsByTagName("ul")[0].childNodes;
        for (var j = 0; j < childNodes.length; j++) {
            if (!childNodes[j].tagName || childNodes[j].tagName.toLowerCase() != "li") continue;
            var li = childNodes[j];
            var selected = (li.className.indexOf("selected") != -1);
            var link = childNodes[j].childNodes[0];
            link.setAttribute("href", "javascript: ;");
            link.onclick = function () {
                var re = /(menu_vlr_tree_openedA|menu_vlr_tree_openedUL|menu_vlr_tree_closedUL)/gi;
                var submenu = this.parentNode.getElementsByTagName("ul")[0];
                var closed = (this.className.toLowerCase().indexOf("menu_vlr_tree_openeda") == -1);
                this.className = this.className.replace(re, "").replace(/[\s]{2,}/gi, " ");
                if (submenu) submenu.className = submenu.className.replace(re, "").replace(/[\s]{2,}/gi, " ");
                if (closed) {
                    this.className += " menu_vlr_tree_openedA";
                    if (submenu) submenu.className += " menu_vlr_tree_openedUL";
                } else {
                    if (submenu) submenu.className += " menu_vlr_tree_closedUL";
                }
            };
            if (selected) link.onclick();
        }
    }

    function CCSMenu_SpansSetup(id) {
        var menu = document.getElementById(id);
        var spans = "<span class=\"text\">{text}</span><span class=\"right2\"></span>";
        var elements = menu.getElementsByTagName("a");
        for (var j = 0; j < elements.length; j++) {
            var a = elements[j];
            var inner = a.innerHTML;
            if (inner.toLowerCase().indexOf("<span") == -1) a.innerHTML = spans.replace(/\{text\}/gi, inner);
        }
    }

    function menuMarkActLink(menuId) {
        var menu = document.getElementById(menuId);
        var aTags = menu.getElementsByTagName("a");
        var i, cA = null;
        for (i = 0; i < aTags.length; i++)
        //if (aTags[i].href == window.location.href)
            if (isIncluded(aTags[i].href, window.location.href)) {
                cA = aTags[i];
                break;
            }
        if (cA == null)
            return;
        var par = cA.parentNode;
        while (par.parentNode.tagName.toLowerCase() == "ul" && par.parentNode.parentNode.tagName.toLowerCase() == "li")
            par = par.parentNode.parentNode;
        par.className = "selected";
    }

    function load_ADxMenu(sender) {
        if (sender.id && sender.id != "") {
            var isIE = navigator.userAgent.toLowerCase().indexOf("msie") != -1;
            var isIE6 = navigator.userAgent.toLowerCase().indexOf("msie 6.0") != -1;

            var ul = sender.getElementsByTagName("ul");

            if (ul.length == 0) return;


            CCSMenu_SpansSetup(sender.id);
            if (isIE && isIE6) ADxMenu_IESetup(sender.id);
            CCSMenu_TreeMenuSetup(sender.id);
            menuMarkActLink(sender.id);
            
        }
    }

    // fix ie blinking
    var m = document.uniqueID
        && document.compatMode
        && !window.XMLHttpRequest
        && document.execCommand;

    try {
        if (!!m) {
            m("BackgroundImageCache", false, true);
        }
    } catch (oh) {
    };


    $.fn.ccsMenu = function () {
        this.each(function () {
            load_ADxMenu(this);
        });
    };
})(jQuery);