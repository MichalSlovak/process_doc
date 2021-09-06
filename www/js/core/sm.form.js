/*! sm.form v1.0.1 | (c) SPORTISIMO s. r. o. 2015 */
;
typeof sm.form !== "undefined" || (function (sm) {
  "use strict";

  var
  /**
   * Zakladni objekt se statickymi metodami.
   */
  form = {
    init: null
  },
  /**
   * Vychozi konfigurace
   */
  defFormsubmitData = {
    event: "change",    // Udalost inputu, na kterou je zaregistrovane odeslani formulare
    target: null,       // Objekt targetu, viz 282
    timeout: 0,         // Prodleva, po jake bude provedeno odeslani formulare.
    type: "sync",       // Zpusob odeslani formulare "sync" | "async"
    params: null,       // Odesilane parametry.
    triggers: null,     // Akce, ktere se maji vykonat po prijeti odepovedi.
    rwd: null,          // Responsivni layouty, pro ktere ma byt formular odeslan pomoci teto knihovny, pokud aktualni layout nevyhovuje, formular se odesle standardne - [320,480,720] | null
    anchorId: null      // Id elementu, ktery se prida do hash
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
  form.init = function (scope)
  {
    // Sekce formsubmit
    var $formsubmits = $(((typeof scope !== undefinedStr) ? "#" + scope + " " : "") + "[data-sm_formsubmit]");
    for (var i = 0; i < $formsubmits.length; i++)
    {
      var formsubmit = $formsubmits[i],
      data = $.extend(true, {}, defFormsubmitData, JSON.parse(formsubmit.getAttribute("data-sm_formsubmit"))),
      formsubmitObj = null;

      formsubmitObj = new FormSubmit(formsubmit, data);
      formsubmitObj.init();
    }
    // Dalsi sekce
  }; // init()

  /**
   * Trida formsubmitu.
   * @param formsubmitCnt HTML DOM element kontejneru formsubmitu.
   * @param data JSON data object.
   */
  function FormSubmit(formsubmitCnt, data)
  {
    var
      _this = this,
      form = null,
      timeoutId = null,
      targets = Array(),
      value = null,
      isSubmit = false,
      rwd = false,
      rwdLayout = null,
      anchorId = data.anchorId;

    _this.init = function ()
    {
      if($(formsubmitCnt).attr("type") === "submit") isSubmit = true;
      if(data.rwd !== null && typeof sm.rwd !== undefinedStr) rwd = true;

      if(typeof formsubmitCnt.form !== undefinedStr)
      {
        form = formsubmitCnt.form;
      }

      $(formsubmitCnt).bind(isSubmit ? "click" : data.event, function(e)
      {
        if(rwd) rwdLayout = sm.rwd.getLayout();
        if(data.type === "async" && (!rwd || checkRwd()))
        {
          sendAsync();
          if(isSubmit) e.preventDefault();
        }
        else if(rwd && !checkRwd())
        {
          // Kdyz je definovan RWD a zaroven se nehodi, nic nedelej
        }
        else
        {
          sendSync();
        }
      });

      // Zakazani odeslani formulare enterem
      if(data.type === "async")
      {
        $(formsubmitCnt).keydown(function(e)
        {
          if(rwd) rwdLayout = sm.rwd.getLayout();
          if(e.keyCode === 13 && (!rwd || checkRwd()))
          {
            sendAsync();
            e.preventDefault();
            return false;
          }
        });
      }

      processTargets();
    }; // init()

    function sendSync()
    {
      clearTimeout();
      setTimeout(function()
      {
        if(form !== null)
        {
          if(anchorId)
          {
            modifyAction(anchorId);
          }
          form.submit();
        }
      });
    } // sendSync()

    function sendAsync()
    {
      if(value === $(formsubmitCnt).val() && !isSubmit) return;
      clearTimeout();
      setTimeout(function()
      {
        // Serializace dat formulare a pridani definovanych params
        var params = $(form).serializeArray();
        if(data.params !== null)
        {
          var pitems = data.params.split("&");
          for(var key in pitems)
          {
            var pairs = {};
            if(pitems.hasOwnProperty(key))
            {
              var keyValuePair = pitems[key].replace(/ /g,"").split("=");
              pairs["name"] = keyValuePair[0];
              pairs["value"] = keyValuePair[1];
              params.push(pairs);
            }
          }
        }
        // Zobrazeni overlay a loading nad vsemi targety
        for(var key in targets)
        {
          if(targets.hasOwnProperty(key))
          {
            targets[key].showOverlay();
            targets[key].showLoading();
          }

        }
        // Asynchronni odeslani dat
        $.ajax({
          url: $(form).attr("action"),
          type: $(form).attr("method"),
          data: params,
          success:
            function(response)
            {
              processResponse(response);
            }
        });
      });
    } // sendAsync()

    function modifyAction(anchor)
    {
      var action = $(form).attr('action');
      if(action.indexOf("#") !== -1)
      {
        // Odebrat soucasnou hash
        action = action.substring(0, action.indexOf("#"));
      }
      $(form).attr('action', action + "#" + anchor);
    } // modifyAction

    /**
     * Funkce pro zpracovani pole definovanych targetu
     */
    function processTargets()
    {
      if(data.target === null) return;
      if($.isArray(data.target))
      {
        for(var key in data.target)
        {
          if(data.target.hasOwnProperty(key))
          {
            var target = new Target(data.target[key]);
            target.init();
            targets.push(target);
          }
        }
      }
      else
      {
        var target = new Target(data.target);
        target.init();
        targets.push(target);
      }
    } // processTargets()

    /**
     * Funkce pro zpracovani asynchroni odpovedi.
     * @param response JSON odpoved
     */
    function processResponse(response)
    {
      if(response !== null && response !== undefinedStr)
      {
        // Ulozeni akt. hodnoty
        value = $(formsubmitCnt).val();
        // Zpracovani pole HTML - v poradi, v jakem jsou definovany targety
        for(var i = 0; i < targets.length; i++)
        {
          // Schovani loading a overlay
          targets[i].hideLoading();
          targets[i].hideOverlay();
          // Zpracovani HTML
          var d = response.data[i];
          if(d.html !== undefinedStr || d.html !== null)
          {
            targets[i].setHtml(d.html);
          }
          // Zpracovani Initu
          if(typeof d.init !== undefinedStr && targets[i].getId() !== null)
          {
            var j = null;
            for(j in d.init)
            {
              if(typeof sm[d.init[j]] !== undefinedStr && typeof sm[d.init[j]].init === "function")
              {
                sm[d.init[j]].init(targets[i].getId());
              }
            }
          }
        }
        // Zpracovani triggers
        /*
         * 1. Dotaz na status v response.
         * 2. Dotaz na triggery.
         * 3. Zpracovani vsech triggeru v poli na indexu zadanem v response. Funkce.
         */
        if(response.status !== undefinedStr)
        {
          if(data.triggers !== null && data.triggers[response.status] !== null && typeof data.triggers[response.status] !== undefinedStr)
          {
            processTriggers(response.status);
          }
        }
      }
    } // processResponse()

    /**
     * Zpracuje a provede definovane triggery.
     * @param {type} key Klic pro ziskani dat z pole triggeru, definovanych
     * v datech.
     */
    function processTriggers(key)
    {
      if(data.triggers.hasOwnProperty(key))
      {
        var triggers = data.triggers[key];
        if($.isArray(triggers))
        {
          for(var id in triggers)
          {
            var trigger = triggers[id];
            $("#" + trigger.id).trigger(trigger.e);
          }
        }
      }
    } // processTriggers()

    /**
     * Funkce pro kontrolu, zda je polozka definovana pro dany layout.
     * @return Vraci true, pokud je polozka definovana pro dany layout, jinak false.
     */
    function checkRwd()
    {
      if(rwd && rwdLayout !== null)
      {
        var rwdLayouts = data.rwd;
        return ($.inArray(rwdLayout.id, rwdLayouts) >= 0);
      }

      return true;
    } // checkRwd()

    function setTimeout(callback)
    {
      timeoutId = window.setTimeout(callback, data.timeout);
    } // setTimeout()

    function clearTimeout()
    {
      if(timeoutId !== null)
        window.clearTimeout(timeoutId);
    } // clearTimeout()
  }; // FormSubmit()

  /**
   * Trida targetu.
   * @param data JSON data object.
   */
  function Target(data)
  {
    var
      _this = this,
      $cnt = null,
      $wrap = null,
      sm_loading = null,
      sm_overlay = null,
      td = null,
      defTargetData =
      {
        id: null,              // ID targetu
        wrapId: null,          // ID wrapu obalujiciho target
        loading: null,         // Data sm.loading
        overlay: null          // Data sm.overlay
      };

    _this.init = function()
    {
      // targetData
      td = $.extend(true, {}, defTargetData, data);

      if(td.id === null || !document.getElementById(td.id)) return; // Nevalidni Target. Objekt ale musi byt vytvoren kvuli zachovani poradi.
      $cnt = $("#"+td.id);

      if(td.wrapId !== null)
      {
        $wrap = $("#"+td.wrapId);
        $cnt.prepend($wrap);
        initLoading();
      }

      initOverlay();
    }; // init()

    _this.setHtml = function(htmlContent)
    {
      if($cnt !== null)
        $cnt.html(htmlContent);
    }; // setHtml()

    _this.getId = function()
    {
      return td.id;
    }; // getId()

    /**
     * Funkce pro inicializaci loadingu nacitani polozky menu.
     */
    function initLoading()
    {
      if(data.loading === null) return;

      sm_loading = new sm.loading(data.loading);
      $wrap.prepend(sm_loading.get());
    } // initLoading()

    /**
     * Funkce pro inicializaci prekryvu polozky menu.
     */
    function initOverlay()
    {
      if(data.overlay === null) return;

      sm_overlay = new sm.overlay(data.overlay);
      sm_overlay.bind("click", function(e){e.stopPropagation(); _this.hide(); return false;});
    } // initOverlay()

    /**
     * Funkce pro zobrazeni nacitani.
     */
    _this.showLoading = function()
    {
      if(sm_loading === null) return;

      sm_loading.start();

      $(sm_loading.get()).show();
    }; // showLoading()

    /**
     * Funkce pro schovani nacitani.
     */
    _this.hideLoading = function()
    {
      if(sm_loading === null) return;

      sm_loading.stop();

      $(sm_loading.get()).hide();
    }; // hideLoading()

    /**
     * Funkce pro zobrazeni prekryvu.
     */
    _this.showOverlay = function()
    {
      if(sm_overlay === null) return;

      sm_overlay.show();
    }; // showOverlay()

    /**
     * Funkce pro schovani prekryvu.
     */
    _this.hideOverlay = function()
    {
      if(sm_overlay === null) return;

      sm_overlay.hide();
    }; // hideOverlay()

  }; // Target

  sm.form = form;
})(window.sm);

// sm.form.js