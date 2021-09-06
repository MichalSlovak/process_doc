/*! sm.countdown v1.0.1 | (c) SPORTISIMO s. r. o. 2015 */
;
typeof sm.countdown !== 'undefined' || (function (sm) {
  'use strict';

  var
  /**
   * Zakladni objekt se statickymi metodami.
   */
  countdown = {
    init: null
  },
  /**
   * Vychozi konfigurace
   */
  defCountdownData = {
    seconds: null,
    now: null,
    finish: null,
    events: {
      id: null,
      event: null
    },
    dId: null,
    hId: null,
    mId: null,
    sId: null,
    zeroFill: true,
    forceZeroFill: false,
    css: {
      normalCss: null,
      countingCss: 'counting',
      finishedCss: 'finished'
    }
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
  countdown.init = function (scope)
  {
    var $countdowns = $(((typeof scope !== undefinedStr) ? "#" + scope + " " : "") + "[data-sm_countdown]");
    for (var i = 0; i < $countdowns.length; i++)
    {
      var $countdown = $countdowns[i],
      data = $.extend(true, {}, defCountdownData, JSON.parse($countdown.getAttribute("data-sm_countdown"))),
      countdown = null;

      countdown = new CountDown(data);
      countdown.init();
    }
  }; // init()

  /**
   * Trida countdownu.
   * @param data JSON data object.
   */
  function CountDown(data)
  {
    var 
      _this = null,
      intervalId = null,
      finished = false,
    
      events = null,
      dateNow = null,
      dateTo = null,
      
      secCount = null,
    
      miliseconds = 0,
      seconds = 0,
      minutes = 0,
      hours = 0,
      days = 0;
    
    this.init = function()
    {
      if(data.seconds)
      {
        secCount = data.seconds;
      }
      else
      {
        if(data.now)
          dateNow = new Date(data.now).getTime();
        else return false;

        if(data.finish && dateNow)
        {
          dateTo = new Date(data.finish).getTime();
          if(dateNow > dateTo) return false;
        }
        else return false;
      }
      
      if(data.events) events = data.events;
      
      this.start();
    };
    
    this.start = function()
    {
      if(data.secondsId !== null)
      {
        this.update();
        return true;
      }
      return false;
    };
    
    /**
     * Provede aktualizaci pocitadla. 
     * @returns Pokud je definovana callback funkce, po uplynuti casu je zavolana.
     */
    this.update = function()
    {
      if(_this === null)
      {
        _this = this;
        intervalId = window.setInterval(_this.update, 1000);
      }
      if(finished)
      {
        window.clearInterval(intervalId);
        return;
      }

      var diff = secCount ? secCount-- * 1000 : dateTo - dateNow;
      if(diff > 0)
      {
        calculate(diff);
      }
      else
      {
        finished = true;
        seconds = 0;
        minutes = 0;
        hours = 0;
        days = 0;
      }
      
      //updateClasses();
      cute();
      
      var d = data;
      if(d.dId !== null && document.getElementById(d.dId))
        $('#' + d.dId).html(days);

      if(d.hId !== null && document.getElementById(d.hId))
        $('#' + d.hId).html(hours);

      if(d.mId !== null && document.getElementById(d.mId))
        $('#' + d.mId).html(minutes);

      if(d.sId !== null && document.getElementById(d.sId))
        $('#' + d.sId).html(seconds);

      if(finished && events) doCallback();
    };
    
    /**
     * Rozpocita zbyvajici cas do jednotlivych (definovanych) jednotek.
     * @param diff Casovy interval, ktery se ma rozpocitat.
     */
    function calculate(diff)
    {
      var d = data;
      miliseconds = diff % 1000;
      diff -= miliseconds;
      diff /= 1000;
      seconds = diff % 60;
      diff -= seconds;
      diff /= 60;
      minutes = diff % 60;
      if(d.minutesId === null)
        seconds += minutes * 60;
      diff -= minutes;
      diff /= 60;
      hours = diff % 24;
      if(d.hoursId === null)
        minutes += hours * 60;
      diff -= hours;
      diff /= 24;
      days = diff;
      if(d.daysId === null)
        hours += days * 24;

      dateNow += 1000;
    }
    
    /**
     * Upravi zobrazovani pocitadla, doplni chybejici nuly.
     * Nejlevejsi zadany element nebude mit zero fill nikdy (pokud neni force).
     */
    function cute()
    {
      var d = data;
      if(d.zeroFill || d.forceZeroFill)
      {
        if((d.mId !== null || d.forceZeroFill) && seconds <= 9)
          seconds = '0' + seconds;
        if((d.hId !== null || d.forceZeroFill) && minutes <= 9)
          minutes = '0' + minutes;
        if((d.dId !== null || d.forceZeroFill) && hours <= 9)
          hours = '0' + hours;
      }
    }
    
    function doCallback()
    {
      if($.isArray(events))
      {
        for(var key in events)
        {
          console.log('id:' + events[key].id + ', event:' + events[key].event);
          if(document.getElementById(events[key].id))
          {
            // Jedna se o click
            if(events[key].event === "click")
            {
              $('#' + events[key].id)[0].click();
            }
            // Jedna se o funkci
            else if (typeof events[key] !== undefinedStr && typeof events[key].event === "function")
            {
              $('#' + events[key].id).trigger(events[key].event);
            }
          }
        }
      }
    } // doCallback()
    
    /**
     * TODO musel by se definovat obalovy div, kteremu by se nastavovala trida 
     * dle stavu dopocitavadla.
     * Provede aktualizaci CSS tridy.
     */
//    function updateClasses()
//    {
//      var beforeFinished = false;
//      var d = data;
//      
//      if(days === 0 || days === '00')
//      {
//        if(d.dId !== null)
//          d.dId.className = d.css.finishedClass;
//        beforeFinished = true;
//      }
//      
//      if(beforeFinished && (hours === 0 || hours === '00'))
//      {
//        if(d.hId !== null)
//          d.hId.className = d.css.finishedClass;
//        beforeFinished = true;
//      }
//      else
//        beforeFinished = false;
//      
//      if(beforeFinished && (minutes === 0 || minutes === '00'))
//      {
//        if(d.mId !== null)
//          d.mId.className = d.css.finishedClass;
//        beforeFinished = true;
//      }
//      else
//        beforeFinished = false;
//
//      if(beforeFinished && (seconds === 0 || seconds === '00'))
//        if(d.sId !== null)  
//          d.sId.className = d.css.finishedClass;
//    }
    
  }; // CountDown()
  
  sm.countdown = countdown;
})(window.sm);

// sm.countdown.js
    