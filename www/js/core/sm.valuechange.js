/*! sm.valuechange v1.0.1 | (c) SPORTISIMO s. r. o. 2018 */
;
typeof sm.valuechange !== 'undefined' || (function (sm) {
  'use strict';

  var
  /**
   * Zakladni objekt se statickymi metodami.
   */
  valuechange = {
    init: null
  },
  /**
   * Vychozi konfigurace
   */
  defValueChangeData = {
    inputId: null,        // Prvek se zdrojovymi daty, input nebo textarea (.val()); nebo obecny prvek (.text())
    valId: null,          // Cilovy prvek; data se prepisi jako .text()
    event: "change",      // Udalost, na zaklade ktere dojde k nahrazeni textu
    prefix: "",           // Prefix doplneny pred text
    suffix: ""            // Suffix doplneny za text
  },
  /**
   * Retezec "undefined"
   */
  undefinedStr = typeof undefined,
  dataNS = "sm_valuechange"
  ;

  /**
   * Funkce pro inicializaci.
   * @param scope Id HTML elementu, v ramci ktereho ma dojit k inicializaci.
   */
  valuechange.init = function (scope)
  {
    var $valuechanges = $(((typeof scope !== undefinedStr) ? "#" + scope + " " : "") + "[data-" + dataNS + "]");
    for (var i = 0; i < $valuechanges.length; i++)
    {
      var valuechange = $valuechanges[i],
      data = $.extend(true, {}, defValueChangeData, JSON.parse(valuechange.getAttribute("data-" + dataNS))),
      valuechangeObj = null;

      valuechangeObj = new ValueChange(data);
      valuechangeObj.init();
    }
  }; // init()

  /**
   * Trida ValueChange.
   * @param data JSON data object.
   */
  function ValueChange(data)
  {
    var
      _this = this,
      event = data.event,
      inputId = data.inputId,
      $input = null,
      valId = data.valId,
      $val = null,
      prefix = data.prefix,
      suffix = data.suffix
    ;

    _this.init = function ()
    {
      console.log("init");
      if(valId && inputId)
      {
        $val = $("#"+valId);
        $input = $("#"+inputId);
        if(!$val.length || !$input.length)
        {
          console.error("sm.valuechange: Nepodarilo se najit selektor " + valId + " nebo " + inputId);
          return false;
        }

        // Navazani udalosti pri zmene hodnoty
        $input.bind(event, replaceVal);
      }
    }; // init()

    /**
     * Funkce pro nahrazeni hodnoty
     * @param e udalost
     */
    function replaceVal(e)
    {
      if($input.is("input") || $input.is("textarea"))
      {
        $val.text(prefix + $input.val() + suffix);
      }
      else
      {
        $val.text(prefix + $input.text() + suffix);
      }
    } // replaceVal()
  } // ValueChange()

  sm.valuechange = valuechange;
})(window.sm);

// sm.valuechange.js