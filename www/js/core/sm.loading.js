/*! sm.loading v1.0.1 | (c) SPORTISIMO s. r. o. 2015 */
;typeof sm.loading !== 'undefined' || (function(sm){
  'use strict';

  var
    /**
     * Vychozi konfigurace
     */
    defData = {
      src: null,
      css: null,
      w: 0,       // sirka
      h: 0,       // vyska
      f: 0,
      s: 0,       // rychlost zmeny
      o: 0,       // vertikalni offset obrazku
      title: null // Titulek
    },

    /**
     * Retezec "undefined"
     */
    undefinedStr = typeof undefined;

  /**
   * Trida pro praci s loadingem.
   * @param dataStr Inicializacni data.
   */
  sm.loading = function(dataStr)
  {
    var
      data = $.extend(true, {}, defData, ((typeof dataStr !== undefinedStr) ? ((typeof dataStr !== "object") ? JSON.parse(dataStr) : dataStr) : {})),
      img = null,
      rootElem = document.createElement("div"),
      elem = document.createElement("div"),
      titleElem = (data.title !== null) ? document.createElement("p") : null,
      f = 0,
      x = 0,
      t = null,
      stopped = true;

    // Vychozi zobrazeni
    elem.style.width = data.w + "px";
    elem.style.height = data.h + "px";
    elem.style.backgroundPosition =  x + "px " + data.o + "px";
    elem.style.backgroundRepeat = "no-repeat";
    $(rootElem).addClass(data.css);
    rootElem.appendChild(elem);
    if(titleElem !== null)
    {
      titleElem.innerHTML = data.title;
      rootElem.appendChild(titleElem);
    }

    // Nacteni obrazku
    img = new Image();
    img.onload = function()
    {
      elem.style.backgroundImage = "url('" + img.src + "')";

      // Spusteni animace
//      animate();
    };
    img.src = data.src;

    /**
     * Funkce pro ziskani HTML DOM elementu s inicializovanym loadingem.
     * @returns Vraci HTML DOM element s inicializovanym loadingem.
     */
    this.get = function()
    {
      return rootElem;
    }; // get()

    /**
     * Funkce pro nastartovani loadingu.
     */
    this.start = function()
    {
      if(!stopped) return;
      stopped = false;
      animate();
    }; // stop()

    /**
     * Funkce pro zastaveni loadingu.
     */
    this.stop = function()
    {
      if(stopped) return;
      stopped = true;
      if(t !== null) window.clearTimeout(t);
    }; // stop()

    /**
     * Funkce pro provedeni animace.
     */
    function animate()
    {
      if(f >= data.f)
      {
        f = 0;
        x = 0;
      }
      x = - f*data.w;

      elem.style.backgroundPosition =  x + "px " + data.o + "px";
      if(!stopped)
      {
        t = window.setTimeout(animate, data.s);
      }
      f++;
    } // animate()
  };
})(window.sm);

// sm.loading.js