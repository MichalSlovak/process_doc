/*! sm.overlay v1.0.1 | (c) SPORTISIMO s. r. o. 2015 */
;typeof sm.overlay !== 'undefined' || (function(sm){
  'use strict';

  var
    /**
     * Vychozi konfigurace
     */
    defData = {
      parent: null,  // ID HTML DOM elementu, ke kteremu bude element prekryvu pripojen
      css: null,     // CSS trida/y, ktere budou elementu prekryvu nastaveny
      show: {
        type: null,  // Efekt zobrazeni - "fade" | null
        time: 100    // Delka trvani efektu zobrazeni
      },
      hide: {
        type: null,  // Efekt schovani - "fade" | null
        time: 100    // Delka trvani efektu zobrazeni
      },
      scroll: true   // Priznak, zda ma byt povoleno scrollovani hlavniho okna
    },

    /**
     * Retezec "undefined"
     */
    undefinedStr = typeof undefined;

  /**
   * Trida pro praci s prekryvem.
   * @param dataStr Inicializacni data.
   */
  sm.overlay = function(dataStr)
  {
    var
      data = $.extend(true, {}, defData, ((typeof dataStr !== undefinedStr) ? ((typeof dataStr !== "object") ? JSON.parse(dataStr) : dataStr) : {})),
      elem = document.createElement("div"),
      parentElem = (data.parent !== null) ? document.getElementById(data.parent) : document.body,
      bodyOverlay = null;

    // Vychozi zobrazeni
    if(data.css !== null)
    {
      $(elem).addClass(data.css);
    }

    // Schovani prekryvu
    $(elem).hide();

    /**
     * Funkce pro ziskani HTML DOM elementu s inicializovanym prekryvem.
     * @returns Vraci HTML DOM element s inicializovanym prekryvem.
     */
    this.get = function()
    {
      return elem;
    }; // get()

    /**
     * Funkce pro zobrazeni prekryvu.
     */
    this.show = function()
    {
      // Pridani prekryvu k rodici
      try
      {
        parentElem.appendChild(elem);
      }
      catch(e)
      {
      }

      // Zamezeni scrollovani hlavniho okna
      if(!data.scroll)
      {
        bodyOverlay = $("body").css("overflow");
        $("body").css("overflow", "hidden");
      }

      if(data.show.type === "fade")
      {
        $(elem).fadeIn(data.show.time);
      }
      else
      {
        $(elem).show(0);
      }
    }; // show()

    /**
     * Funkce pro schovani prekryvu.
     */
    this.hide = function()
    {
      var
        clb = function()
        {
          // Odstraneni od rodice
          try
          {
            parentElem.removeChild(elem);
          }
          catch(e)
          {
          }
        };

      // Zruseni zamezeni scrollovani hlavniho okna
      if(!data.scroll)
      {
        $("body").css("overflow", bodyOverlay);
        bodyOverlay = null;
      }

      if(data.hide.type === "fade")
      {
        $(elem).fadeOut(data.hide.time, clb);
      }
      else
      {
        $(elem).hide(0, clb);
      }
    }; // hide()

    /**
     * Funkce pro napojeni eventu k prekryvu.
     * @param event Udalost, pri ktere se ma provest akce.
     * @param callback Akce, ktera se ma provest pri dane udalosti.
     */
    this.bind = function(event, callback)
    {
      $(elem).on(event, callback);
    }; // bind()
  };
})(window.sm);

// sm.overlay.js