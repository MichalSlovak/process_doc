/*! sm.easing v1.0.1 | (c) SPORTISIMO s. r. o. 2015 */
;typeof sm.easing !== 'undefined' || (function(sm){
  'use strict';

  var
    /**
     * Zakladni objekt se statickymi metodami.
     */
    easing = {};
    
  /**
   * Funkce pro upravu casu.
   */
  easing.linear = function(t)
  {
    return t;
  }; // linear()

  /**
   * Funkce pro upravu casu.
   */
  easing.inQuint = function(t)
  {
    return t * t * t * t * t;
  }; // inQuint()

  /**
   * Funkce pro upravu casu.
   */
  easing.outQuint = function(t)
  {
    return (t -= 1) * t * t * t * t + 1;
  }; // outQuint()

  /**
   * Funkce pro upravu casu.
   */
  easing.inOutQuint = function(t)
  {
    return (t *= 2) < 1 ? 1 / 2 * t * t * t * t * t : 1 / 2 * ((t -= 2) * t * t * t * t + 2);
  }; // inOutQuint()

  /**
   * Rozsireni easing funkci jQuery pro pouziti ve funkci $.animate.
   */
  (function(easing)
  {
    $.extend(jQuery.easing,
    {
      sm_linear:easing.linear,
      sm_inQuint:easing.inQuint,
      sm_outQuint:easing.outQuint,
      sm_inOutQuint:easing.inOutQuint
    });
  })(easing);

  sm.easing = easing;
})(window.sm);

// sm.easing.js