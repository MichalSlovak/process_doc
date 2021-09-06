/*! sm.click v1.0.1 | (c) SPORTISIMO s. r. o. 2015 */
;
typeof sm.click !== 'undefined' || (function (sm) {
  'use strict';

  var
    /**
     * Zakladni objekt se statickymi metodami.
     */
    click = {
      init: null
    },
    /**
     * Vychozi konfigurace
     */
    defData = {
      selector: null,        // JS selektor (nebo pole), pro ktery se udalost zaregistruje
      excludeSel: null,      // JS selektor (nebo pole), pro ktery se nema udalost zaregistrovat
      initOffSel: null,      // JS selektor, pro ktery se hned po nacteni knihovny deaktivuje CSS trida initOffCss
      initOffCss: null,      // CSS trida k deaktivaci po nacteni
      // JS event, ktery bude spoustet udalost na danem selektoru
      event: "touchstart MSPointerDown mousedown",
      trigger: "sm_bc",      // JS event, ktery spousti udalost - TODO event
      loading: null,         // Data sm.loading
      overlay: null,         // Data sm.overlay
      css: "wrap",           // CSS trida zobrazovane polozky
      modal: true            // Modalni chovani true|false
    },
    /**
     * Retezec "undefined"
     */
    undefinedStr = typeof undefined
  ;

  /**
   * Funkce pro inicializaci.
   * @param scope Id HTML elementu, v ramci ktereho ma dojit k inicializaci.
   */
  click.init = function (scope)
  {
    var $clicks = $(((typeof scope !== undefinedStr) ? "#" + scope + " " : "") + "[data-sm_click]");
    for (var i = 0; i < $clicks.length; i++)
    {
      var click = $clicks[i],
        data = $.extend(true, {}, defData, JSON.parse(click.getAttribute("data-sm_click"))),
        click = null;

      click = new Click(data);
      click.init();
    }
  }; // init()

  function Click(data)
  {
    var
      _this = this,
      $selector = null,
      $item = null,
      clb = null,
      initOffSelector = data.initOffSel,
      initOffCss = data.initOffCss,
      sm_loading = null,
      sm_overlay = null
    ;

    _this.init = function()
    {
      // Pocatecni inicializace
      if($selector === null)
      {
        var sel = data.selector, isSelArray = (typeof data.selector === "object") ? true : false,
            exSel = data.excludeSel, isExSelArray = (typeof data.excludeSel === "object") ? true : false;

        if(sel === null) return;

        isSelArray ? sel = sel.join(",") : sel;
        if(exSel)
        {
          isExSelArray ? exSel = exSel.join(",") : exSel;
          $selector = $(sel).not(exSel);
        }
        else
        {
          $selector = $(sel);
        }

        if(!$selector.length)
        {
          console.warn("No sm.click objects found.");
          // return;
        }

        // Vytvoreni kontejneru
        $item = $("<div class='"+data.css+"'></div>");
        $item.hide();
        $("body").append($item);

        // Navazani udalosti
        if(data.event)
        {
          $selector.on(data.event, function(e)
          {
            //e.preventDefault();
            show();
            doClb();
            if(this.nodeName.toLowerCase() === "a" && this.href)
            {
              window.location.href = this.href;
            }
            if((( this.nodeName.toLowerCase() === "input" && this.type.toLowerCase() === "submit")
              || (this.nodeName.toLowerCase() === "button" && this.type.toLowerCase() === "submit"))
              &&  this.form !== undefinedStr)
            {
              this.form.submit();
            }

            return false;
          });
        }
        if(data.trigger)
        {
          $(document).on(data.trigger, function(e){show(); doClb();});
        }
        $(document).ajaxComplete(function(){hide();});
      }

      initLoading();
      initOverlay();

      // Kontrola prvku, ktere se maji deaktivovat hned po nacteni JS
      var $initOffSelector = $(initOffSelector);
      if($initOffSelector.length && initOffCss)
      {
        $initOffSelector.removeClass(initOffCss);
      }
    }; // init()

    /**
     * Navazani callback funkce.
     * @param clbFc
     */
    _this.initClb = function(clbFc)
    {
      if(clbFc && typeof clbFc === "function")
      {
        clb = clbFc;
      }
    }; // initClb

    /**
     * Funkce pro zobrazeni kontejneru se sm.loading a sm.overlay
     */
    function show()
    {
      $item.show();
      showOverlay();
      showLoading();
    } // show()

    /**
     * Prozatim neni potreba.
     */
    function hide()
    {
      hideLoading();
      hideOverlay();
      $item.hide();
    } // hide()

    /**
     * Funkce pro inicializaci loadingu nacitani polozky menu.
     */
    function initLoading()
    {
      if(data.loading === null) return;

      sm_loading = new sm.loading(data.loading);
      $item.prepend(sm_loading.get());
    } // initLoading()

    /**
     * Funkce pro inicializaci prekryvu polozky menu.
     */
    function initOverlay()
    {
      if(data.overlay === null) return;

      sm_overlay = new sm.overlay(data.overlay);
      sm_overlay.bind(data.event, function(e)
      {
        e.stopPropagation();
        if(!data.modal)
        {
          hide();
        }
        return false;
      });
    } // initOverlay()

    /**
     * Funkce pro zobrazeni nacitani obsahu polozky menu.
     */
    function showLoading()
    {
      if(sm_loading === null) return;

      sm_loading.start();

      $(sm_loading.get()).show();
    } // showLoading()

    /**
     * Funkce pro schovani nacitani obsahu polozky menu.
     */
    function hideLoading()
    {
      if(sm_loading === null) return;

      sm_loading.stop();

      $(sm_loading.get()).hide();
    } // hideLoading()

    /**
     * Funkce pro zobrazeni prekryvu polozky menu.
     */
    function showOverlay()
    {
      if(sm_overlay === null) return;

      sm_overlay.show();
    } // showOverlay()

    /**
     * Funkce pro schovani prekryvu polozky menu.
     */
    function hideOverlay()
    {
      if(sm_overlay === null) return;

      sm_overlay.hide();
    } // hideOverlay()

    /**
     * Funkce pro provedeni callback funkce.
     */
    function doClb()
    {
      if(clb) clb();
    } // doClb()


  } // Click

  sm.click = click;
})(window.sm);

// sm.click.js