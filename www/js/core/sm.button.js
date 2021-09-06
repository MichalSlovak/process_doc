/*! sm.button v1.0.1 | (c) SPORTISIMO s. r. o. 2017 */
;
typeof sm.button !== 'undefined' || (function(sm) {
  'use strict';

  var
    /**
     * Jmenny prostor pro funkce pluginu
     */
    _NS_ = 'button',
    /**
     * Zakladni objekt se statickymi metodami pro praci s tlacitkem.
     */
    button = {
      init: null
    },
    /**
     * Vychozi konfigurace
     */
    defData = {
      content: null,                // null | obsah tlacitka
      css:
      {
        css: '',
        activeCss: 'active',
        disabledCss: 'disabled',
        clickCss: 'click',
        clickedCss: 'clicked'
      },
      clickTimeoutDelay: 200,
      clickSound: null,
      clickSoundType: 'audio/ogg',
      inId: null,                  // null | ID inputu, kam vepsat obsah tlacitka
      triggers: null,
      active: true,
      clicked: false               // zda uz bylo na tlacitko kliknuto
    },

    /**
     * Struktura objektu, ktery se pridava k elementu.
     */
    /*
    buttonObject = {
      _this: null,
      active: true,
      clicked: false,
      clickTimeout: null,
      callback: null,
      options: {},
      setActive: null,
      setClicked: null,
      soundElem: null
    },*/

    /**
     * Retezec 'undefined'
     */
    undefinedStr = typeof undefined,

    /**
     * Namespace pro nacitani dat
     */
    dataNS = "sm_button";

  /**
   * Funkce pro inicializaci.
   * @param scope Id HTML elementu, v ramci ktereho ma dojit k inicializaci.
   */
  button.init = function(scope)
  {
    var $buttons = $(((typeof scope !== undefinedStr) ? "#" + scope + " " : "") + "[data-"+dataNS+"]");
    for (var i = 0; i < $buttons.length; i++)
    {
      var
      button = $buttons[i],
      data = $.extend(true, {}, defData, JSON.parse(button.getAttribute("data-"+dataNS))),
      btnObj = null;

      btnObj = new Button(button, data, null);
      btnObj.init();
    }
  }; // init ()

  button.get = function($elem, _data, cbk)
  {
    console.log("Ok constructor " + _data);
    if(!_data)
    {
      console.error("Error: sm_button data undefined.");
      return;
    }
    var
    data = $.extend(true, {}, defData, _data),
    btnObj = new Button($elem, data, cbk);
    btnObj.init();
  };

  /**
   * Trida tlacitka.
   * @param elem HTML DOM element
   * @param data JSON data object
   * @param cbk Callback funkce
   */
  function Button(elem, data, cbk)
  {
    var
      _this = this,
      $btn = null,
      isActive = data.active,
      isClicked = data.clicked,
      timeoutId = null,
      clickTD = data.clickTimeoutDelay,
      clickSound = data.clickSound,
      clickSoundType = data.clickSoundType,
      audio = null,
      bCss = data.css.css,
      activeCss = data.css.activeCss,
      disabledCss = data.css.disabledCss,
      clickCss = data.css.clickCss,
      clickedCss = data.css.clickedCss,
      inId = data.inId,
      triggers = data.triggers
    ;

//    this.clickTD = data.clickTimeoutDelay

    /**
     * Public metody
     */

    /**
     *
     * @returns {Boolean}
     */
    _this.init = function()
    {

      // Pocatecni inicializace
      if($btn === null)
      {
        $btn = $(elem);
        if(!$btn.length) return false;

        if(bCss) $btn.addClass(bCss)

        // soundElem = renderSoundElem();
        if(clickSound) audio = new Audio(clickSound);

        // Navazani callback funkci
        $($btn).bind('touchstart', click);
        $($btn).bind('MSPointerDown', click);
        $($btn).bind('mousedown', click);

        // Zastaveni udalosti
        $($btn).bind('touchend', stopEvent);
        $($btn).bind('MSPointerUp', stopEvent);
        $($btn).bind('mouseup', stopEvent);

        $($btn).bind('click', stopEvent);

        $($btn).bind('mouseover', stopEvent);
        $($btn).bind('MSPointerOver', stopEvent);
        $($btn).bind('mouseout', stopEvent);
        $($btn).bind('MSPointerOut', stopEvent);

        $($btn).bind('touchmove', stopEvent);
        $($btn).bind('touchcancel', stopEvent);
        $($btn).bind('gesturestart', stopEvent);
        $($btn).bind('gesturechange', stopEvent);
        $($btn).bind('gestureend', stopEvent);
        $($btn).bind('touchcancel', stopEvent);
        $($btn).bind('MSPointerMove', stopEvent);
        $($btn).bind('MSPointerHover', stopEvent);
        $($btn).bind('MSGestureTap', stopEvent);
        $($btn).bind('MSGestureHold', stopEvent);
        $($btn).bind('MSGestureStart', stopEvent);
        $($btn).bind('MSGestureChange', stopEvent);
        $($btn).bind('MSGestureEnd', stopEvent);
        $($btn).bind('MSInertiaStart', stopEvent);

        $($btn).bind('focus', function(){this.blur();});
      }
    }; // init()

    /**
     *
     * @param {type} active
     * @returns {undefined}
     */
    _this.setActive = function(active)
    {
      isActive = active;

      if(isActive)
      {
        // Nastaveni tridy active
        if(activeCss) $btn.addClass(activeCss);

        // Nastaveni tridy clicked
        if(isClicked && clickedCss) $btn.addClass(clickedCss);

        // Odstrateni tridy disabled
        if(disabledCss) $btn.removeClass(disabledCss);
      }
      else
      {
        // Odstraneni tridy active
        if(activeCss) $btn.removeClass(activeCss);

        // Odstraneni tridy clicked
        if(clickedCss) $btn.removeClass(clickedCss);

        // Nastaveni tridy disabled
        if(disabledCss) $btn.addClass(disabledCss);
      }
    }; // setActive()

    /**
     *
     * @param {type} clicked
     * @returns {undefined}
     */
    _this.setClicked = function(clicked)
    {
      isClicked = clicked;

      if(isClicked && isActive)
      {
        // Nastaveni tridy clicked
        if(clickedCss) $btn.addClass(clickedCss);
      }
      else if(!isClicked && isActive)
      {
        // Nastaveni tridy clicked
        if(clickedCss) $btn.removeClass(clickedCss);
      }
    }; // setClicked()

//  Button.prototype =
//  {
//    constructor: Button,
//    setClicked: function(clicked)
//    {
//      console.log(this.clickTD);
//      console.log(clicked);
//    },
//    setActive: function(active)
//    {
//
//    }
//  };

    /**
     * Private metody
     */
    /**
     * Callback funkce pro stisknuti tlacitka.
     * @param e Udalost kliknuti.
     * @return Vraci true, pokud je tlacitko aktivni, jinak false pro zastaveni zpracovani udalosti.
     */
    function click(e)
    {
      e.stopPropagation();
      console.log("Click event...");

      if(isActive)
      {
        //SM_button.setClicked(true);
        _this.setClicked(true);

        // Vycisteni timeoutu pro oznaceni tlacitka, ze je prave kliknuto
        window.clearTimeout(clickTD);

        // Nastaveni tridy stisknuteho tlacitka
        if(clickCss)
        {
          $btn.addClass(clickCss);
        }

        // Spusteni zvuku tlacitka
        if(audio)
        {
          audio.pause();
          audio.currentTime = 0;
          audio.play();
        }

        // Nastaveni timeoutu pro zruseni tridy stisknuteho tlacitka
        timeoutId = window.setTimeout(function()
        {
          if(clickCss) $btn.removeClass(clickCss);
        }, clickTD);

        window.setTimeout(function()
        {
          // Nastaveni zpracovani udalosti
          processTriggers(triggers);

          // Zpracovani callback udalosti
          if(cbk && typeof cbk === 'function') cbk();

          // Zpracovani keyboard udalosti
//          if(inId)
//          {
//            var $inId = $("#"+inId);
//            if($inId.length) $inId.val($inId.val() + $btn.text());
//          }
        }, 10);




//        if(SM_button.callback !== null)
//        {
//          window.setTimeout(function(){SM_button.callback()}, 10);
//        }
      }

      return false;
    }; // click()

    /**
     * Callback funkce pro zastaveni udalosti.
     * @param e Udalost.
     */
    function stopEvent(e)
    {
      e.stopPropagation();
      return false;
    }; // stopEvent()

   /**
    * Funkce pro provedeni spousti.
    * @param triggers Pole spousti.
    */
   function processTriggers(triggers)
   {
     if(triggers === null) return;
     for(var i in triggers)
     {
       $("#" + triggers[i].id).trigger(triggers[i].e);
     }
   } // processTriggers()

  } // Button()

  sm.button = button;
})(sm);

// sm.button.js