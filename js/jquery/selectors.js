(function ($) {
    $.fn.ccsId = function () {
        var id = $(this).data("id");
        if (!id)
            id = $(this).attr("id");
        return id;
    };
})(jQuery);


(function ($) {
    jQuery.find.selectors.pseudos.ccsControl = jQuery.find.selectors.createPseudo(function (selector) {
        return function (el) {
            var id = $(el).ccsId();
            if (!id)
                return false;
            var args = selector.split(/\s*,\s*/);
            var doSearchAtLevel = !isNaN(args[args.length - 1]);
            var searchAtLevel = "";
            if (doSearchAtLevel)
                searchAtLevel = args.pop();
            var prefix = args.join("");
            return !!(id === prefix || id.match(new RegExp("^" + prefix + "_" + (doSearchAtLevel ? searchAtLevel : "\\d+") + "$")));
        };
    });
    
    jQuery.find.selectors.pseudos.ccsSameLevelControl = jQuery.find.selectors.createPseudo(function(selector) {
        return function(el) {
            var id = $(el).ccsId();
            if (!id)
                return false;
            var args = selector.split( /\s*,\s*/ );
            var curId = args[0];
            args[0] = "";
            var idParts = curId.split("_");
            var level = idParts[idParts.length - 1];
            var hasLevel = !isNaN(level);
            return id === (args.join("") + (hasLevel ? ("_" + level) : ""));
        };
    });

})(jQuery);