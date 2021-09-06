/*! sm.valueplusminus v1.0.1 | (c) SPORTISIMO s. r. o. 2017 */
;
typeof sm.valueplusminus !== 'undefined' || (function (sm) {
  'use strict';

  var
  /**
   * Zakladni objekt se statickymi metodami.
   */
  valueplusminus = {
    init: null
  },
  /**
   * Vychozi konfigurace
   */
  defValuePlusMinusData = {
    input: null,
    mId: null,
    pId: null,
    max: null,
    min: null,
    disabledCss: "inactive",
    loadCorrect: true,
    btnId: null
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
  valueplusminus.init = function (scope)
  {
    var $valueplusminuses = $(((typeof scope !== undefinedStr) ? "#" + scope + " " : "") + "[data-sm_valueplusminus]");
    for (var i = 0; i < $valueplusminuses.length; i++)
    {
      var valueplusminus = $valueplusminuses[i],
      data = $.extend(true, {}, defValuePlusMinusData, JSON.parse(valueplusminus.getAttribute("data-sm_valueplusminus"))),
      valueplusminusObj = null;

      valueplusminusObj = new ValuePlusMinus(data);
      valueplusminusObj.init();
    }
  }; // init()

  /**
   * Trida ValuePlusMinus.
   * @param data JSON data object.
   */
  function ValuePlusMinus(data)
  {
    var
      _this = this,
      $plus = null,
      $minus = null,
      $input = null,
      max = data.max,
      min = data.min,
      disabledCss = data.disabledCss,
      loadCorrect = data.loadCorrect,
      $btn = null;

    /**
     * Funkce pro inicializaci objektu.
     * @returns {boolean} true v pripade uspesne inicializace, jinak false
     */
    _this.init = function ()
    {
      // Kontrola povinne zadavanych init parametru
      if(data.input === null || data.mId === null || data.pId === null) {
        console.error("Error: input id|mId|pId not defined");
        return false;
      }

      if(data.btnId !== null && $("#"+data.btnId).length)
      {
        $btn = $("#"+data.btnId);
      }

      // Prvni inicializace
      if($input === null)
      {
        $input = $("#"+data.input);
        if(!$input.length){
          console.error("Error: input id not exists");
          return false;
        }

        $plus = $("#"+data.pId);
        if(!$plus.length){
          console.error("Error: plus id not exists");
          return false;
        }

        $minus = $("#"+data.mId);
        if(!$minus.length){
          console.error("Error: minus id not exists");
          return false;
        }

        if(min !== null && max !== null && min >= max)
        {
          console.error("Error: min is greater or equal to max");
          return false;
        }

        // Kontrola vlozeneho cisla
        check();
        $input.bind("change", check);
      }

      $plus.unbind("click");
      $minus.unbind("click");

      $plus.click(plus);
      $minus.click(minus);

      return true;
    }; // init()

    /**
     * Funkce pro zpracovani inkrementu.
     * @param e event
     */
    function plus(e)
    {
      e.preventDefault();
      var currentVal = parseInt($input.val());
      if (!isNaN(currentVal))
      {
        // Inkrement
        if(max === null)
        {
          $input.val(currentVal + 1).change();
        }
        else
        {
          if(currentVal < max)
          {
            $input.val(++currentVal).change();
          }
        }
      }
      else
      {
        // Vlozit nulu
        $input.val(min).change();
      }
    }  // plus

    /**
     * Funkce pro zpracovani dekrementu.
     * @param e event
     */
    function minus(e)
    {
      e.preventDefault();
      var currentVal = parseInt($input.val());
      if (!isNaN(currentVal))
      {
        // Dekrement
        if(min === null)
        {
          $input.val(currentVal - 1).change();
        }
        else
        {
          if(currentVal > min)
          {
            $input.val(--currentVal).change();
          }
        }
      }
      else
      {
        // Vlozit nulu
        $input.val(min).change();
      }
    } // minus()

    /**
     *
     */
    function check()
    {
      var val = parseInt($input.val());
      if (!isNaN(val))
      {
        if(max !== null && val >= max)
        {
          if(loadCorrect) $input.val(max);
          $plus.addClass(disabledCss);
        }
        else
        {
          $plus.removeClass(disabledCss);
        }

        if(min !== null && val <= min)
        {
          if(loadCorrect) $input.val(min);
          $minus.addClass(disabledCss);
        }
        else
        {
          $minus.removeClass(disabledCss);
        }

        if(!loadCorrect && $btn !== null && ((min !== null && val < min) || (max !== null && val > max)))
        {
          $btn.addClass(disabledCss);
          $btn.attr("disabled", true);
        }
        else if($btn !== null)
        {
          $btn.attr("disabled", false);
          $btn.removeClass(disabledCss);
        }
      }
      else
      {
        $input.val(0).change();
      }

      // Po inicialni kontrole jiz vzdy opravovat zadavanou hodnotu dle min a max hodnoty
      loadCorrect = true;
    } // check()
  }; // ValuePlusMinus()

  sm.valueplusminus = valueplusminus;
})(window.sm);

// sm.valueplusminus.js