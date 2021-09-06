/*! sm.menu v1.0.1 | (c) SPORTISIMO s. r. o. 2015 */
;typeof sm.menu !== "undefined" || (function(sm){
  "use strict";

  var
    /**
     * Pole definovanych menu.
     */
    menuList = [],

    /**
     * Pole definovanych menu.
     */
    menuPaneList = [],

    /**
     * Zakladni objekt se statickymi metodami.
     */
    menu = {
      init: null
    },

    /**
     * Vychozi konfigurace polozky menu
     */
    defData = {
      menu: null,            // ID menu, do ktereho bude polozka menu patrit
      item: null,            // ID polozky menu
      parent: null,          // ID rodicovske polozky menu
      selected: false,       // Priznak, zda je polozka menu vybrana - true | false
      show: {
        event: "click",      // Udalost, na zaklade ktere se bude polozka otvirat - "click" | "mouseover"
        type: null,          // Typ zobrazeni polozky - "fade" | "slide" | "css" | null
        time: 100,           // Doba trvani efektu zobrazeni polozky - 100 | null
        delay: null,         // Zpozdeni po kterem dojde k zobrazeni polozky - 500 | null
        rwd: null,           // Responsivni layouty, pro ktere ma byt polozka zobrazena, pokud aktualni layout nevyhovuje, polozka se neotvira - [320,480,720] | null
        css: null,           // CSS trida, ktera se prida cele polozce menu po zobrazeni
        onshow: false        // Priznak, zda se polozka zobrazi po udalosti sm_show
      },
      hide: {
        event: "close",      // Udalost, na zaklade ktere se bude polozka zavirat - "close" | "mouseout"
        type: null,          // "fade" | "slide" | "css" | null
        time: 100,           // 100 | null
        delay: null,         // 500 | null
        auto: null,          // Zpozdeni, po kterem dojde automaticky k uzavreni. Zruseno mouseover. 3000 | null
        amc: false,          // Rusi mouseover chovani u automatickeho uzavirani - true | false
        modal: false,        // Priznak, zda se jedna o modalni okno - true | false
        url: null,           // URL, na kterou se ma po zavreni polozky presmerovat
        reload: false,       // Priznak, zda se ma po schovani polozky menu obnovit stranka - true | false
        onhide: false        // Priznak, zda se polozka schova po udalosti sm_hide
      },
      head: {
        id: null,              // Id nebo pole id hlavicek, ktere budou slouzit k otevreni polozky
        close: false,          // Priznak, zda se bude polozka po znovu kliknuti na hlavicku zavirat - true | false
        css: null,             // CSS trida, ktera se prida hlavicce po otevreni polozky
        sound: null,           // Data sm.sound
        preventDefault: true,  // Priznak, zda budou potlaceny vychozi funkce hlavicky - true | false
        stopPropagation: true  // Priznak, zda bude zastavena propagace udalosti hlavicky - true | false
      },
      popup: {
        id: null,            // ID elementu, ktery se zobrazuje a schovava a uplatnuji se na nej efekty zobrazeni a schovani
        css: null,           // CSS trida, ktera se prida elementu po dokonceni zobrazeni
        wrap: null           // Objekt obaloveho elementu - {id: null, css: null}
      },
      cnt: {
        id: null,              // Id obsahu polozky, pokud nezadano vygeneruje div do popupu (resp. wrapu) s ID dle ID polozky
        url: null,             // URL, ze ktere bude nacten obsah polozky
        method: "post",        // Metoda, kterou budou predany parametry pro ziskani obsahu polozky - "get" | "post"
        params: null,          // Parametry, ktere budou pouzity pro ziskani obsahu polozky
        type: "async",         // Zpusob nacteni obsahu polozky - "async" | "iframe"
        preload: false,        // Priznak, zda se ma obsah polozky nacist dopredu - true | false
        rwd: false,            // Priznak, zda se ma pri zmene responsivniho layoutu resetovat obsah polozky - true | false
        css: null,             // CSS trida, ktera se prida elementu po dokonceni zobrazeni
        delay: null,           // Zpozdeni, po kterem dojde k pridani CSS tridy cssActive
        cssActive:null,        // CSS trida pridavana k jiz aktivnimu contentu; v pripade zadani delay zpozdena o zadany pocet ms
        onreset: false,        // Priznak, zda se ma obsah polozky menu resetovat po obdrzeni eventu sm_reset
        focus: null,           // Id elementu, kteremu se ma po nacteni poslat focus event - null | "id"
        stopPropagation: true  // Priznak, zda bude zastavena propagace udalosti sm_show obsahu - true | false
      },
      close: null,           // Data nebo pole dat zaviraciho tlacitka - {id: null, css: null, title: null, url: null, method: null, params: null}
      loading: null,         // Data sm.loading
      overlay: null,         // Data sm.overlay
      anchor: null           // Data sm.anchor
    },

    /**
     * Vychozi konfigurace polozky menu
     */
    defDataPane = {
      menu: null,            // ID menu, ke kteremu panel patri
      pane: null,            // ID panelu
      width: false,          // Priznak, zda se ma prizpusobovat sirka panelu dle aktualne zobrazene polozky
      height: true           // Priznak, zda se ma prizpusobovat vyska panelu dle aktualne zobrazene polozky
    },

    /**
     * Retezec "undefined"
     */
    undefinedStr = typeof undefined;

  /**
   * Funkce pro inicializaci menu.
   * @param scope Id HTML elementu, v ramci ktereho ma dojit k inicializaci menu.
   */
  menu.init = function(scope)
  {
    var
      $menuItems = $(((typeof scope !== undefinedStr) ? "#"+scope+" " : "") + "[data-sm_menu]"),
      $menuPanes = $(((typeof scope !== undefinedStr) ? "#"+scope+" " : "") + "[data-sm_menu_pane]"),
      selectedItems = [];

    // Inicializace jednotlivych polozek menu
    for(var i = 0; i < $menuItems.length; i++)
    {
      var
        menuItem = $menuItems[i],
        data = $.extend(true, {}, defData, JSON.parse(menuItem.getAttribute("data-sm_menu"))),
        menuId = data.menu,
        itemId = data.item,
        parentArrayIndex = (data.parent !== null) ? data.parent : "null",
        item = null;

      if(menuId === null || itemId === null) continue;

      if(typeof menuList[menuId] === undefinedStr)
      {
        menuList[menuId] = {};
      }
      item = new Item(data);
      if(item.init())
      {
        if(typeof menuList[menuId][parentArrayIndex] === undefinedStr)
        {
          menuList[menuId][parentArrayIndex] = new Array();
        }
        menuList[menuId][parentArrayIndex][itemId] = item;

        // Ulozeni vybrane polozky k inicialnimu zobrazeni
        if(data.selected) selectedItems.push(item);
      }
    }

    // Inicializace jednotlivych panelu polozek menu
    for(var i = 0; i < $menuPanes.length; i++)
    {
      var
        menuPane = $menuPanes[i],
        data = $.extend(true, {}, defDataPane, JSON.parse(menuPane.getAttribute("data-sm_menu_pane"))),
        menuId = data.menu,
        paneId = data.pane,
        pane = null;

      if(menuId === null || paneId === null) continue;

      pane = new Pane(data);
      if(pane.init())
      {
        menuPaneList[menuId] = pane;
      }
    }

    // Zobrazeni vybranych polozek menu
    for(var i in selectedItems)
    {
      var selectedItem = selectedItems[i];
      selectedItem.show(true);
    }
  }; // init()

  /**
   * Trida polozky menu.
   * @param data JSON data object.
   */
  function Item(data)
  {
    var
      _this = this,
      menuId = data.menu,
      itemId = data.item,
      parent = data.parent,
      $item = null,
      $heads = null,
      $popup = null,
      $popupWrap = null,
      $cnt = null,
      $iframe = null,
      $close = null,
      sm_loading = null,
      sm_overlay = null,
      sm_anchor = null,
      sm_sound = null,
      loading = false,
      loaded = false,
      rwd = false,
      rwdLayout = null,
      rwdCnt = false,
      selected = false,
      sTimeout = null,
      hTimeout = null,
      aTimeout = null,
      cssActiveTimeout = null,
      ns = "sm.menu."+menuId+"."+itemId;

    /* Verejne metody */
    /**
     * Provede inicializaci polozky menu.
     * @return Vraci true, pokud inicializace probehla v poradku, jinak false.
     */
    _this.init = function()
    {
      if(menuId === null || itemId === null)
      {
        return false;
      }

      initItem();
      initHeads();
      initPopup();
      initCnt();
      initClose();
      initLoading();
      initOverlay();
      initAnchor();
      initSound();

      // Prednacteni polozky
      preloadCnt();

      return true;
    }; // init()

    /**
     * Funkce pro zobrazeni polozky menu.
     * @param now Voliteny parametr, ktery vynucuje okamzite zobrazeni polozky bez pripadneho zpozdeni.
     */
    _this.show = function(now)
    {
      // Zastaveni pripadneho schovani polozky
      if(data.hide.delay !== null)
      {
        if(hTimeout !== null)
        {
          // Vycisteni schovavaciho timeoutu
          window.clearTimeout(hTimeout);
          hTimeout = null;
        }
      }

      // Nastaveni zpozdeni otevreni polozky
      if(data.show.delay !== null)
      {
        if(!now)
        {
          if(sTimeout !== null)
          {
            // Prave probiha uz jiny timeout otevreni, necha se dobehnout a dalsi otevreni se nekona
            return;
          }

          // Nastaveni timeoutu
          sTimeout = window.setTimeout(function(){_this.show(true);}, data.show.delay);
          return;
        }
        else
        {
          // Vycisteni zobrazovaciho timeoutu
          window.clearTimeout(sTimeout);
          sTimeout = null;
        }
      }
      // Dojde ke zobrazeni polozky pouze, pokud neni RWD nebo je definovana pro aktualni RWD layout
      if(rwd && !checkRwd()) return;
      // Schovani ostatnich polozek menu
      hideOtherItems();

      // Zamezeni znovu zobrazeni polozky menu
      if(selected) return;

      // Nastaveni vybrane polozky
      selected = true;

      // Nastaveni modalniho zavirani
      if(!data.hide.modal)
      {
        $(document).bind("click."+ns, function(e)
        {
          if(!$item.is(e.target) && $item.has(e.target).length === 0)
          {
            _this.hide();
          }
        });
      }

      // Nastaveni tridy polozky menu
      var css = data.show.css;
      if(css !== null && $item !== null) $item.addClass(css);

      // Zobrazeni hlavicky
      showHeads();

      // Prehrani zvuku
      playSound();

      // Zobrazeni overlay
      showOverlay();

      // Zobrazeni obsahu
      showPopup();

      // Poslani focus eventu
      sendFocus();

      if(data.hide.auto !== null)
      {
        if(aTimeout !== null)
        {
          clearTimeout(aTimeout);
          aTimeout = null;
        }

        aTimeout = setTimeout(function()
        {
          _this.hide();
          aTimeout = null;
        }, data.hide.auto);

        if(!data.hide.amc)
        {
          $item.bind("mouseover", function()
          {
            if(aTimeout !== null)
            {
              window.clearTimeout(aTimeout);
              aTimeout = null;
            }
          });
        }
      }

      // Scroll na head ID elem
//      if(data.show.scrollToHead !== false && $heads !== null && $heads.length !== 0)
//      {
//        var topOffset = null;
//        for(var i in $heads)
//        {
//          topOffset = $heads[i].offset().top;
//          break;
//        }
//        $('html, body').animate(
//        {
//          scrollTop: topOffset
//        }, 100);
//      }
    }; // show()

    /**
     * Funkce pro schovani polozky menu.
     * @param now Voliteny parametr, ktery vynucuje okamzite schovani polozky bez pripadneho zpozdeni.
     */
    _this.hide = function(now)
    {
      var hideData = data.hide;

      // Zastaveni pripadneho zobrazeni polozky
      if(data.show.delay !== null)
      {
        if(sTimeout !== null)
        {
          // Vycisteni schovavaciho timeoutu
          window.clearTimeout(sTimeout);
          sTimeout = null;
        }
      }

      // Nastaveni zpozdeni schovani polozky
      if(hideData.delay !== null)
      {
        if(!now)
        {
          if(hTimeout !== null)
          {
            // Prave probiha uz jiny timeout schovani, necha se dobehnout a dalsi schovani se nekona
            return;
          }

          // Odebrani cssActiveTridy u cnt
          hideCnt();

          // Nastaveni timeoutu
          hTimeout = window.setTimeout(function(){_this.hide(true);}, hideData.delay);
          return;
        }
        else
        {
          // Vycisteni schovavaciho timeoutu
          window.clearTimeout(hTimeout);
          hTimeout = null;

          // Odebrani cssActiveTridy u cnt
          hideCnt();
        }
      }

      // Schovani dcerinnych polozek
      hideChildrenItems();

      // Schovani polozky, ktera neni otevrena
      if(!selected) return;

      // Nastaveni vybrane polozky
      selected = false;

      // Zruseni nemodalniho zavirani
      if(!hideData.modal)
      {
        $(document).unbind("click."+ns);
      }

      // Schovani zaviraciho tlacitka
      hideClose();

      // Schovani vyjizdeci casti polozky
      hidePopup(function()
      {
        // Schovani obsahu polozky
//        hideCnt();

        // Schovani progresu nacitani obsahu polozky
//        hideLoading();

        // Schovani overlay
        hideOverlay();

        // Schovani hlavicky
        hideHeads();

        // Odstraneni tridy polozky menu
        var css = data.show.css;
        if(css !== null && $item !== null) $item.removeClass(css);

        // Provedeni akce po zavreni polozky
        if(hideData.url !== null)
        {
          window.location.href = hideData.url;
        }
        else if(hideData.reload)
        {
          window.location.reload(false);
        }
      });
    }; // hide()

    /**
     * Funkce pro ziskani sirky obsahu polozky.
     * @return Vraci sirku obsahu polozky.
     */
    _this.getPopupWidth = function()
    {
      return $popup.outerWidth();
    }; // getPopupWidth()

    /**
     * Funkce pro ziskani vysky obsahu polozky.
     * @return Vraci vysku obsahu polozky.
     */
    _this.getPopupHeight = function()
    {
      return $popup.outerHeight();
    }; // getPopupHeight()

    /* Privatni metody */
    /**
     * Funkce pro inicializaci polozky menu.
     */
    function initItem()
    {
      var showData = data.show, hideData = data.hide;

      $item = $("#"+itemId);

      if(showData.event === "mouseover")
      {
        $item.bind("mouseover", function()
        {
          // Dojde ke zobrazeni polozky pouze, pokud neni RWD nebo je definovana pro aktualni RWD layout
          if(!rwd || checkRwd()) _this.show();
        });
      }

      if(hideData.event === "mouseout")
      {
        $item.bind("mouseout", function()
        {
          _this.hide();
        });
      }

      // Nastaveni sm_show
      if(showData.onshow)
      {
        $item.bind("sm_show", function(e)
        {
          e.stopPropagation();

          // Dojde ke zobrazeni polozky pouze, pokud neni RWD nebo je definovana pro aktualni RWD layout
          if(!rwd || checkRwd()) _this.show();

          return false;
        });
      }

      // Nastaveni sm_hide
      if(hideData.onhide)
      {
        $item.bind("sm_hide", function(e)
        {
          e.stopPropagation();

          _this.hide();

          return false;
        });
      }

      // Nastaveni RWD
      if(showData.rwd !== null && typeof sm.rwd !== undefinedStr)
      {
        rwd = true;
        rwdLayout = sm.rwd.getLayout();
        sm.rwd.bind(function(layout)
        {
          // Nastaveni aktualniho RWD layoutu
          rwdLayout = layout;

          // Kontrola, zda je polozka definovana pro aktualni RWD layout
          if(checkRwd())
          {
            // Pokud se ma reloadovat pri zmene RWD layoutu i obsah polozky, resetuje ji
            if(rwdCnt)
            {
              var sel = selected;
              if(sel) _this.hide(true);
              resetCnt();
              if(sel) _this.show(true);
            }
          }
          // Pokud polozka neni definovana pro aktualni RWD layout a je vybrana, dojde k jeji zavreni
          else if(selected)
          {
            _this.hide(true);
          }
        });
      }
    } // initItem()

    /**
     * Funkce pro inicializaci hlavicky polozky menu.
     */
    function initHeads()
    {
      var
        ids = data.head.id,
        preventDefault = data.head.preventDefault,
        stopPropagation = data.head.stopPropagation,
        isArray = (typeof ids === "object") ? true : false;

      if(ids === null) return;

      // Vytvoreni pole hlavicek
      $heads = [];
      if(isArray)
      {
        for(var i in ids)
        {
          var id = ids[i];
          $heads.push($("#"+id));
        }
      }
      else
      {
        $heads.push($("#"+ids));
      }

      for(var i in $heads)
      {
        var $head = $heads[i];
        if(data.show.event === "click" || data.show.event.indexOf("touch") >= 0)
        {
          if(data.head.close)
          {
            $head.bind(data.show.event, function(e)
            {
              if(!selected)
              {
                if(!rwd || checkRwd())
                {
                  if(stopPropagation) e.stopPropagation();
                  if(preventDefault) e.preventDefault();
                  _this.show();
                }
              }
              else
              {
                if(stopPropagation) e.stopPropagation();
                if(preventDefault) e.preventDefault();
                _this.hide();
              }
            });
          }
          else
          {
            $head.bind(data.show.event, function(e)
            {
              if(!selected && (!rwd || checkRwd()))
              {
                if(stopPropagation) e.stopPropagation();
                if(preventDefault) e.preventDefault();
                _this.show();
              }
            });
          }
        }
        else if($head.is("a"))
        {
          $head.bind("click", function(e)
          {
            if(!selected && (!rwd || checkRwd()))
            {
              e.stopPropagation();
              return false;
            }
          });
        }
      }
    } // initHeads()

    /**
     * Funkce pro inicializaci vyjizdeci casti polozky menu.
     */
    function initPopup()
    {
      var popupData = data.popup, id = popupData.id, wrapData = popupData.wrap;

      if(id !== null)
      {
        $popup = $("#"+id);
      }
      else
      {
        id = itemId + "_popup";
        data.popup.id = id;
        var elem = document.createElement("div");
        elem.id = id;
        $item.append(elem);
        $popup = $(elem);
      }

      if(popupData.css !== null) $popup.addClass(popupData.css);

      if(wrapData !== null)
      {
        if(typeof wrapData.id !== undefinedStr && wrapData.id !== null)
        {
          $popupWrap = $("#"+wrapData.id);
        }
        else
        {
          id += "_wrap";
          data.popup.wrap.id = id;
          var elem = document.createElement("div");
          elem.id = id;
          $popup.append(elem);
          $popupWrap = $(elem);
        }

        if(wrapData.css !== null) $popupWrap.addClass(wrapData.css);
      }
    } // initPopup()

    /**
     * Funkce pro inicializaci obsahu polozky menu.
     */
    function initCnt()
    {
      var cntData = data.cnt, id = cntData.id;

      if(id !== null)
      {
        $cnt = $("#"+id);
      }
      else
      {
        id = itemId + "_cnt";
        data.cnt.id = id;
        var elem = document.createElement("div");
        elem.id = id;
        var $p = ($popupWrap !== null) ? $popupWrap : $popup;
        $p.append(elem);

        $cnt = $(elem);
      }

      if(cntData.css !== null) $cnt.addClass(cntData.css);

      if(cntData.url !== null)
      {
        loaded = false;

        // Nastaveni typu obsahu
        if(cntData.type === "iframe")
        {
          var elem = document.createElement("iframe");
          $cnt.append(elem);
          $iframe = $(elem);
          $iframe.attr("allowtransparency","true");
        }

        // Nastaveni RWD
        if(rwd && cntData.rwd)
        {
          rwdCnt = true;
        }

        // Nastaveni sm_reset
        if(cntData.onreset)
        {
          $item.bind("sm_reset", function(e)
          {
            e.stopPropagation();

            resetCnt();

            return false;
          });
        }
      }
      else
      {
        loaded = true;
      }

      // Odchyceni show a hide udalosti, aby se nepropagovaly dal
      $cnt.bind("sm_show", function(e)
      {
        if(cntData.stopPropagation === true){e.stopPropagation();return false;}
      });
      $cnt.bind("sm_hide", function(e)
      {
        if(cntData.stopPropagation === true){e.stopPropagation();return false;}
      });
    } // initCnt()

    /**
     * Funkce pro inicializaci zaviraciho tlacitka polozky menu.
     */
    function initClose()
    {
      var closeData = data.close;

      if(closeData === null) return;

      var
        ids = closeData.id,
        ids = [],
        isArray = (typeof closeData.id === "object") ? true : false;

      if(isArray)
      {
        for(var i in closeData.id)
        {
          var id = closeData.id[i];
          ids.push(id);
        }
      }
      else
      {
        ids.push(closeData.id);
      }

      for(var _id in ids)
      {
        var id = (typeof ids[_id] === undefinedStr) ? null : ids[_id];

        if(id !== null)
        {
          $close = $("#"+id);
        }
        else
        {
          // Dogenerovani zaviraciho tlacitka
          id = itemId + "_close";
          if(isArray)
          {
            data.close.id[_id] = id;
          }
          else
          {
            data.close.id = id;
          }
          var elem = document.createElement("div"), iElem = document.createElement("i");
          elem.id = id;
          elem.appendChild(iElem);
          if(typeof closeData.title !== undefinedStr && closeData.title !== null)
          {
            var spanElem = document.createElement("span");
            spanElem.innerHTML = closeData.title;
            elem.appendChild(spanElem);
          }

          var $p = ($popupWrap !== null) ? $popupWrap : $popup;
          $p.append(elem);

          $close = $(elem);
        }

        if(typeof closeData.css !== undefinedStr && closeData.css !== null) $close.addClass(closeData.css);

        var event = "click";
        if(data.show.event.indexOf("touch") >= 0) event = data.show.event;

        $close.bind(event, function(e)
        {
          e.stopPropagation();
          _this.hide();

          // Provedeni akce po zavreni polozky
          if(typeof closeData.url !== undefinedStr && closeData.url !== null)
          {
            var
              url = closeData.url,
              method = (typeof closeData.method !== undefinedStr && closeData.method !== null) ? closeData.method : "post",
              params = (typeof closeData.params !== undefinedStr && closeData.params !== null) ? closeData.params : null;

            $.ajax({
              type: method,
              url: url,
              data: params
            });
          }

          return false;
        });
      }
    } // initClose()

    /**
     * Funkce pro inicializaci loadingu nacitani polozky menu.
     */
    function initLoading()
    {
      if(data.loading === null) return;

      sm_loading = new sm.loading(data.loading);
      var $p = ($popupWrap !== null) ? $popupWrap : $popup;
      $p.prepend(sm_loading.get());
    } // initLoading()

    /**
  * Funkce pro inicializaci prekryvu polozky menu.
  */
  function initOverlay()
  {
    if(data.overlay === null) return;

    sm_overlay = new sm.overlay(data.overlay);
    sm_overlay.bind("click", function(e)
      {
        e.stopPropagation();
        if(!data.hide.modal)
        {
          _this.hide();
        }
        return false;
      }
    );
  } // initOverlay()

    /**
     * Funkce pro inicializaci kotvy.
     */
    function initAnchor()
    {
      if(data.anchor === null) return;

      sm_anchor = sm.anchor.construct(data.anchor);
    } // initAnchor()

    /**
     * Funkce pro inicializaci zvuku pomoci knihovny sm.sound.
     * @param soundData Data sm.sound
     */
    function initSound()
    {
      if(data.head.sound === null || typeof sm.sound === undefinedStr) return;
      sm_sound = new sm.sound(data.head.sound);
    } // initSound()

    /**
     * Funkce pro schovani ostatnich polozek menu.
     */
    function hideOtherItems()
    {
      var pIdx = (parent !== null) ? parent : "null",
          m = menuList[menuId][pIdx],
          i = null,
          item = null;

      for(i in m)
      {
        if(i === itemId) continue;
        item = m[i];
        item.hide(true);
      }
    } // hideOtherItem()

    /**
     * Funkce pro schovani dcerinnych polozek menu.
     */
    function hideChildrenItems()
    {
      var pIdx = itemId,
          m = menuList[menuId][pIdx],
          i = null,
          item = null;

      // Kontrola, zda ma polozka nejake dcerinne polozky
      if(typeof m === undefinedStr) return;

      for(i in m)
      {
        item = m[i];
        item.hide(true);
      }
    } // hideChildrenItems()

    /**
     * Funkce pro zobrazeni hlavicky polozky menu.
     */
    function showHeads()
    {
      if($heads === null || $heads.length === 0) return;

      var css = data.head.css;
      if(css !== null)
      {
        for(var i in $heads)
        {
          $heads[i].addClass(css);
        }
      }
    } // showHeads()

    /**
     * Funkce pro schovani hlavicky polozky menu.
     */
    function hideHeads()
    {
      if($heads === null || $heads.length === 0) return;

      var css = data.head.css;
      if(css !== null)
      {
        for(var i in $heads)
        {
          $heads[i].removeClass(css);
        }
      }
    } // hideHeads()

    /**
     * Zobrazi vyjizdeci cast polozky menu.
     */
    function showPopup()
    {
      if($popup === null) return;

      var cntData = data.cnt, clb = null;
      if(cntData.url !== null && !loaded && loading)
      {
        // Zobrazeni progressu nacteni obsahu polozky
        showLoading();

        // Zobrazeni zaviraciho tlacitka
        showClose();
      }
      else if(cntData.url !== null && !loaded && !loading)
      {
        loading = true;

        // Zobrazeni progressu nacteni obsahu polozky
        showLoading();

        // Zobrazeni zaviraciho tlacitka
        showClose();

        // Ziskani obsahu polozky
        loadCnt();
      }
      else if(cntData.url === null || loaded)
      {
        // Zobrazeni obsahu polozky
        showCnt();

        // Zobrazeni zaviraciho tlacitka
        showClose();

        clb = function()
        {
          // Odeslani udalosti potomkum; pokud se udalost nema propagovat dal, posle se jen prvni urovni (children), jinak vsem (find)
          if(cntData.stopPropagation === true)
          {
            $cnt.children().trigger("sm_show");
          }
          else
          {
            $cnt.find("*").trigger("sm_show");
          }

          // Scrollovani na definovany element
          if(sm_anchor !== null)
          {
            sm_anchor.scrollTo();
          }
        };
      }

      // Zastaveni probihajicich animaci
      $popup.stop();

      // Zobrazeni vyjizdeci casti
      switch(data.show.type)
      {
        case "fade":
          $popup.fadeIn(data.show.time, clb);
          break;
        case "slide":
          $popup.slideDown(data.show.time, clb);
          break;
        case "css":
          if(clb !== null) clb();
          break;
        default:
          $popup.show(0, clb);
          break;
      }

      // Nastaveni rozmeru panelu
      if(typeof menuPaneList[menuId] !== undefinedStr)
      {
        menuPaneList[menuId].update(_this);
      }
    } // showPopup()

    /**
     * Schova vyjizdeci cast polozky menu.
     * @param callback Funkce, ktera se ma provest po dokonceni efektu schovani.
     */
    function hidePopup(callback)
    {
      if($popup === null) return;

      // Zastaveni probihajicich animaci
      $popup.stop();

      switch(data.hide.type)
      {
        case "fade":
          $popup.fadeOut(data.hide.time, callback);
          break;
        case "slide":
          $popup.slideUp(data.hide.time, callback);
          break;
        case "css":
          callback();
          break;
        default:
          $popup.hide(0, callback);
          break;
      }
    } // hidePopup()

    /**
     * Funkce pro zobrazeni nacteneho obsahu polozky menu.
     */
    function showCnt()
    {
      if($cnt === null) return;
      $cnt.show(0);

      // Pridani CSS tridy se zpozdenim
      if(data.cnt.cssActive !== null && data.cnt.delay !== null)
      {
        if(cssActiveTimeout) clearTimeout(cssActiveTimeout);
        cssActiveTimeout = setTimeout(function()
        {
          $cnt.addClass(data.cnt.cssActive);
        }, data.cnt.delay);
      }
      // Pridani CSS tridy okamzite
      else if(data.cnt.cssActive !== null)
      {
        $cnt.addClass(data.cnt.cssActive);
      }
    } // showCnt()

    /**
     * Funkce pro schovani nacteneho obsahu polozky menu.
     */
    function hideCnt()
    {
      if($cnt === null) return;
      // $cnt.hide(0);

      // Odeslani udalosti potomkum
      // $cnt.children().trigger("sm_hide");

      // Odebrani CSS tridy se zpozdenim
      if(data.cnt.cssActive !== null && data.cnt.delay !== null)
      {
        if(cssActiveTimeout) clearTimeout(cssActiveTimeout);
        cssActiveTimeout = setTimeout(function()
        {
          $cnt.removeClass(data.cnt.cssActive);
        }, data.cnt.delay);
      }
      // Odebrani CSS tridy okamzite
      else if(data.cnt.cssActive !== null)
      {
        $cnt.removeClass(data.cnt.cssActive);
      }
    } // hideCnt()

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
     * Funkce pro zobrazeni zaviraciho tlacitka polozky menu.
     */
    function showClose()
    {
      if($close === null) return;

      $close.show(0);
    } // showClose()

    /**
     * Funkce pro schovani zaviraciho tlacitka polozky menu.
     */
    function hideClose()
    {
      if($close === null) return;

      $close.hide();
    } // hideClose()

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

    function playSound()
    {
      if(sm_sound === null) return;

      sm_sound.play();
    } // playSound();

    /**
     * Funkce pro kontrolu, zda je polozka definovana pro dany layout.
     * @return Vraci true, pokud je polozka definovana pro dany layout, jinak false.
     */
    function checkRwd()
    {
      if(rwd && rwdLayout !== null)
      {
        var rwdLayouts = data.show.rwd;
        return ($.inArray(rwdLayout.id, rwdLayouts) >= 0);
      }

      return true;
    } // checkRwd()

    /**
     * Funkce pro nacteni obsahu polozky.
     */
    function loadCnt()
    {
      var cntData = data.cnt, params = cntData.params;

      if(rwdCnt)
      {
        var l = rwdLayout;
        if(l !== null)
        {
          params += ((params !== null && params !== "") ? "&" : "") + "sm_rwd-layout=" + l.id;
        }
      }

      if(cntData.type === "iframe")
      {
        var paramConcat = cntData.url.indexOf("?") === -1 ? "?" : "&";
        var src = cntData.url + ((params !== null && params !== "") ? paramConcat + params : "");
        $iframe.bind("load", function()
        {
          loaded = true;
          loading = false;
          $iframe.unbind("load");

          // Schovani progressu nacteni obsahu polozky
          hideLoading();

          // Zobrazeni obsahu polozky
          if(selected)
          {
            showCnt();

            // Odeslani udalosti potomkum; pokud se udalost nema propagovat dal, posle se jen prvni urovni (children), jinak vsem (find)
            if(cntData.stopPropagation === true)
            {
              $cnt.children().trigger("sm_show");
            }
            else
            {
              $cnt.find("*").trigger("sm_show");
            }
          }

          // Poslani focus eventu
          sendFocus();
        });
        $iframe.attr("src", src);
      }
      else
      {
        $.ajax({
          type: cntData.method,
          url: cntData.url,
          data: params,
          dataType: "json",
          success: function(d)
          {
            loaded = true;

            setCnt(d);
          },
          complete: function()
          {
            loading = false;

            // Schovani progressu nacteni obsahu polozky
            hideLoading();

            // Zobrazeni obsahu polozky
            if(selected) showCnt();

            // Poslani focus eventu
            sendFocus();
          }
        });
      }
    } // loadCnt()

    /**
     * Funkce pro prednacteni obsahu polozky.
     */
    function preloadCnt()
    {
      var cntData = data.cnt;

      // Pokud je zapnuto prednacitani obsahu polozky a neprobiha prave nacteni polozky, nebo neni polozka nactena
      if(cntData.preload && cntData.url !== null && !loaded && !loading)
      {
        loading = true;

        // Zobrazeni progressu nacteni obsahu polozky
        showLoading();

        // Ziskani obsahu polozky
        loadCnt();
      }
    } // preloadCnt()

    /**
     * Funkce pro nastaveni obsahu polozky.
     * @param d Nacteny obsah polozky v pripade typu "async".
     */
    function setCnt(d)
    {
      if(typeof d === undefinedStr) return;

      // Inicializace externe nacteneho obsahu polozky
      if(typeof d.html !== undefinedStr)
      {
        $cnt.html(d.html);
      }

      // Inicializace js modulu v kontextu externe nacteneho obsahu polozky
      var id = data.cnt.id;
      if(typeof d.init !== undefinedStr && typeof id !== undefinedStr && id !== null)
      {
        var i = null;
        for(i in d.init)
        {
          if(typeof sm[d.init[i]] !== undefinedStr && typeof sm[d.init[i]].init === "function")
          {
            sm[d.init[i]].init(id);
          }
        }
      }
    } // setCnt()

    /**
     * Funkce pro znovu nacteni obsahu menu.
     */
    function resetCnt()
    {
      var cntData = data.cnt;

      loaded = false;
      if(cntData.type === "iframe")
      {
        $iframe.html("");
        $iframe.attr("src", "");
      }
      else
      {
        $cnt.html("");
      }
    } // resetCnt()

    /**
     * Funkce pro poslani focusu zadefinovanemu elementu.
     */
    function sendFocus()
    {
      var elemId = data.cnt.focus;
      if(elemId !== null)
      {
        // Nejprve je nutne poslat focus na iframe
        if(data.cnt.type === "iframe" && $iframe !== null)
        {
          $iframe[0].contentWindow.focus();
        }
        $("#" + elemId).focus();
      }
    } // sendFocus()

  } // Item


  /**
   * Trida panelu polozek menu.
   * @param data JSON data object.
   */
  function Pane(data)
  {
    var
      _this = this,
      menuId = data.menu,
      paneId = data.pane,
      width = data.width,
      height = data.height,
      $pane = null;

    /* Verejne metody */
    /**
     * Provede inicializaci panelu polozek menu.
     * @return Vraci true, pokud inicializace probehla v poradku, jinak false.
     */
    _this.init = function()
    {
      if(menuId === null || paneId === null)
      {
        return false;
      }

      $pane = $("#" + paneId);

      return true;
    }; // init()

    /**
     * Funkce pro aktualizaci rozmeru panelu polozek menu.
     * @param item Aktualne vybrana polozka, podle ktere ma byt nastaven rozmer panelu.
     */
    _this.update = function(item)
    {
      if(item === null) return;

      if(width) $pane.css("min-width", item.getPopupWidth() + "px");
      if(height) $pane.css("min-height", item.getPopupHeight() + "px");
    }; // update()

  } // Pane

  sm.menu = menu;
})(window.sm);

// sm.menu.js