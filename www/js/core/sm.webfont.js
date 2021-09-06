/*! sm.webfont v1.0.1 | (c) SPORTISIMO s. r. o. 2017 */
;
typeof sm.webfont !== 'undefined' || (function (sm) {
  'use strict';

  var
    /**
     * Zakladni objekt se statickymi metodami.
     */
    webfont = {
      init: null
    },
    /**
     * Vychozi konfigurace
     */
    defData = {
      families: "", // Open+Sans:300,400,400italic,600,700,700italic:latin,latin-ext
      cookieName: "sm_wf",
      loadLimit: 1000
    },
    /**
     * Retezec "undefined"
     */
    undefinedStr = typeof undefined;

  /**
   * Funkce pro inicializaci.
   * @param scope Id HTML elementu, v ramci ktereho ma dojit k inicializaci.
   */
  webfont.init = function (scope)
  {
    var $webfonts = $(((typeof scope !== undefinedStr) ? "#" + scope + " " : "") + "[data-sm_webfont]");
    for (var i = 0; i < $webfonts.length; i++)
    {
      var
        webFont = $webfonts[i],
        data = $.extend(true, {}, defData, JSON.parse(webFont.getAttribute("data-sm_webfont"))),
        webFontObj = null;

      webFontObj = new WebFont(data);
      webFontObj.init();
    }
  }; // init()

  /**
   * Trida web fontu.
   * @param data JSON data object.
   */
  function WebFont(data)
  {
    var
      _this = this;

    _this.init = function()
    {
      // console.log("WebFont lib init!");
      // Nadefinovani globalni promenne daty z definice
      window.WebFontConfig = {
        google: { families: [data.families] },
        active: function() { document.cookie=data.cookieName+'=1; expires='+(new Date(new Date().getTime() + 86400000)).toGMTString()+'; path=/' }
      };

      window.el = document.documentElement;
      el.className += ' wf-loading';
      setTimeout(function()
      {
        el.className = el.className.replace(/(^|\s)wf-loading(\s|$)/g, ' ');
      }, data.loadLimit);

      var $script = $('<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js" async="" defer=""></script>');
      $("body").append($script);
    }; // init()
  }; // WebFont()

  sm.webfont = webfont;
})(window.sm);

// sm.webfont.js