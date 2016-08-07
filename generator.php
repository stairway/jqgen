<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . '/env.php';

$typeMap = array(
  '1' => 'jquerySimple', 
  '2' => 'jqueryFnSimple', 
  '3' => 'jqueryFnApi'
);

$includeLicense     = $_REQUEST['includeLicense'];
$licenseOrg         = trim($_REQUEST['licenseOrg']) ? trim($_REQUEST['licenseOrg']) : 'jQGen';
$copyrightYear      = trim($_REQUEST['copyrightYear']) ? trim($_REQUEST['copyrightYear']) : date('Y');
$pluginVersion      = trim($_REQUEST['pluginVersion']) ? trim($_REQUEST['pluginVersion']) : "1.0.0";
$pluginStyle        = $_REQUEST['pluginStyle'];
$pluginName         = trim($_REQUEST['pluginName']) ? trim($_REQUEST['pluginName']) : "MyPlugin";
$pluginDescription  = trim($_REQUEST['pluginDescription']) ? 
                        trim($_REQUEST['pluginDescription']) : 
                          "A jQuery plugin";
$pluginDescriptionDefault = <<<JS


@usage: $('selector').{$pluginName}({}, callback)
@usage: $('selector').{$pluginName}(callback)
JS;
$pluginDescription  = $pluginDescription . $pluginDescriptionDefault;
$pluginPrefix       = trim($_REQUEST['pluginPrefix']) ? 
                        trim($_REQUEST['pluginPrefix']) :
                          substr(strtolower($pluginName), 0, 1) . preg_replace('/[aeiou]/i', '', substr(strtolower($pluginName), 1));
$pluginNamespace    = trim($_REQUEST['pluginNamespace']) ?
                        trim($_REQUEST['pluginNamespace']) : 
                          lcfirst(trim($pluginName));
$globalVars         = trim($_REQUEST['globalVars']);
$globalVarsJquery   = trim($_REQUEST['globalVarsJquery']);
$helperMethods      = trim($_REQUEST['helperMethods']);
$apiMethods         = trim($_REQUEST['apiMethods']);
$defaultOptions     = trim($_REQUEST['defaultOptions']);
$pluginBody         = $_REQUEST['pluginBody'];

$coreEvents         = "init, ready, complete";
$pluginEvents       = trim($_REQUEST['pluginEvents']) ? $coreEvents . ", " . trim($_REQUEST['pluginEvents']) : $coreEvents;
$generatedBy        = "http://".$_SERVER['SERVER_NAME'];

/**
 * Build Options
 */
function buildOptions($defaultOptions) {
  $options = "";
  if ($defaultOptions) {
    $options .= <<<JS
    
    var settings = $.extend({},{
JS;
    foreach(explode(',', $defaultOptions) as $option) {
      $option = trim($option);
      $options .= <<<JS
      
      {$option},
JS;
    }
  $options .= <<<JS
    
    }, options));
    
JS;
  }
    
  return $options;
}

/**
 * Method Parts
 */
function getMethodParts($apiMethods) {
  preg_match_all('/([^\(|,|\s]*)(?:,|\(([^\)]*)\))?/', $apiMethods, $matches, PREG_SET_ORDER);
    
  foreach ($matches as $i => $match) {
    if (empty($match[0]) || $match[0] === ",") {
      unset($matches[$i]);
    }
  }
  foreach ($matches as $i => $match) {
    if (count($match) > 2 && empty($match[2])) {
      unset($matches[$i][2]);
    }
  }
  $matches = array_values($matches);
  $names = array();
  $args = array();
  foreach ($matches as $i => $match) {
    $names[$i] = $match[1];
    $args[$i] = $match[2] ? $match[2] : "";
  }
  
  return array('names' => $names, 'args' => $args);
}

/**
 * Build Helper Methods
 */
function buildHelperMethods($helperMethods) {
  $methods = "";
  if ($helperMethods) {
    foreach(explode(',', $helperMethods) as $method) {
      $method = trim($method);
      $methods .= <<<JS
        
    function {$method} () {}

JS;
    }
  }
  return $methods;
}

/**
 * Build Body - Simple
 */
function buildBody_simple($str) {
  $lines .= <<<JS
      
      // MAIN BODY - BEGIN
      
      
JS;
  if ($str) {
    $eol = PHP_EOL;
    foreach(preg_split('/(?:'.$eol.'|\n)/', $str) as $line) {
      $lines .= <<<JS
{$line}
      
JS;
    }
  } else {
    $lines .= <<<JS
/* .............. */

JS;
  }
  $body = <<<JS
    
    function init() {
      {$lines}
      // MAIN BODY - END
      
    }
    
    init();
JS;
  return $body;
}

/**
 * Build Body - Fn Simple
 */
function buildBody_fnSimple($str) {
  $lines = <<<JS
      
      // MAIN BODY - BEGIN
      
      
JS;
  if ($str) {
    $eol = PHP_EOL;
    foreach(preg_split('/(?:'.$eol.'|\n)/', $str) as $line) {
      $lines .= <<<JS
{$line}
      
JS;
    }
  } else {
    $lines .= <<<JS
/* .............. */

JS;
  }
  
  $body = <<<JS
    
    return this.each(function () {
      {$lines}
      // MAIN BODY - END
      
    });
JS;
  return $body;
}

/**
 * Build Body - Fn API
 */
function buildWrapper_fnApi($pluginName) {
  $wrapper = <<<JS

  /* ============================================================= 
  PLUGIN DEFINITION
  ============================================================= */
   
  /**
   * The following method should be called with it's context manually specified, e.g.
   * using .bind(), .call(), or .apply();
   */
  function _prep ( element, option, callback ) {
    var options = typeof option === "object" && option, data;
    /*
     * Use "$.data" to save each instance of the plugin in case
     * the user wants to modify it. Using "$.data" in this way
     * ensures the data is removed when the DOM element(s) are
     * removed via jQuery methods, as well as when the user leaves
     * the page. It's a smart way to prevent memory leaks.
     *
     * More: http://api.jquery.com/jquery.data/
     */
    data = $.data(this, "plugin_" + pluginPrefix);
    if(!data) { $.data(this, "plugin_" + pluginPrefix, (data = new {$pluginName}(element, options, callback))); }
    /*
     * Call data[option]() like this if the option is a string, instead of
     * an object. The string should be the name of an api function.
     */
    if (typeof option === "string") { return data[option](); }
    return data;
  }
  
  /**
   * The actual jQuery plugin defintion
   */
  $.fn[pluginName] = $[pluginName] = function ( option, callback ) {
    if (typeof option === "function") { callback = option; option = null; }
    return test(this) ?
      _prep.call(this, \$events, option, callback) :
        this.each(function () {
          _prep.call(this, this, option, callback); // most common
        });
  };
JS;
  return $wrapper;
}

/**
 * Build Vars
 */
function buildVars_fnApi($pluginName, $pluginPrefix, $pluginNamespace, $pluginVersion, $events, $defaults, $varsG, $varsJ) {
  $out = <<<JS
  
  var 
  
  /* =============================================================
  GLOBALS - Branding
  ============================================================= */
  
  pluginName = "{$pluginName}",
  pluginPrefix = "{$pluginPrefix}",
  pluginNamespace = "{$pluginNamespace}",
  pluginVersion = "{$pluginVersion}",
  ${events}
  /* =============================================================
  GLOBALS - Cached jQuery object variables
  ============================================================= */
  
  \$events = $('<a/>'), // $({}) would be prefered, but there is an issue with jQuery 1.4.2  
  \$source = false,
JS;
  if ($varsJ) {
    $out .= <<<JS
  {$varsJ}
  
JS;
  } else {
    $out .= <<<JS
   
   
JS;
  }
  
  $out .= <<<JS
  
  /* =============================================================
  GLOBALS - Other variables for cached values 
  or use across multiple functions
  ============================================================= */
  
  __debug = true,
  __table = "console.table",
  
  self = false,
  settings = false,
  triggerContext = document,
JS;
  if ($varsG) {
    $out .= <<<JS
  {$varsG}  
JS;
  }
  
  $out .= <<<JS
  
  ${defaults};
JS;

  return $out;
}

/**
 * Build Events
 */
function buildEvents_fnApi($pluginEvents, $pluginPrefix, $pluginNamespace) {
  $out = "";
  if ($pluginEvents) {
    $i = 0;
    $events = "";
    $eventsArr = explode(',', $pluginEvents);
    foreach($eventsArr as $event) {
      $event = trim($event);
      $events .= <<<JS
  
  /**
   * name:    {$pluginPrefix}:{$event}
   * listen:  \$(document).on("{$pluginPrefix}:{$event}.{$pluginNamespace}", function (event, api) {});
   * trigger: trigger (event_{$event}, [this]);
   */
  event_{$event} = pluginPrefix + ":${event}",
  
JS;
    }
    $out .= <<<JS
  
  /* =============================================================
  GLOBALS - Events
  ============================================================= */
  ${events}
JS;
  }

  return $out;
}

/**
 * Build Defaults Object
 */
function buildDefaults_fnApi($defaultOptions) {
  $defaults = <<<JS
  
  /* =============================================================
  GLOBALS - Default Options
  ============================================================= */
  
JS;
  if ($defaultOptions) {
    $options = "";
    $defaultsArr = explode(',', $defaultOptions);
    foreach($defaultsArr as $option) {
      $option = trim($option);
    
      if ($i < (count($defaultsArr) - 1)) {
        $options .= <<<JS
    
    {$option},
JS;
      } else {
        $options .= <<<JS
    
    {$option}
  
JS;
      }
    }
  
    $defaults .= <<<JS
  
  defaults = {{$options}}
JS;
  } else {
    $defaults .= <<<JS
    
  defaults = {}
JS;
  }
  return $defaults;
}

/**
 * Build Global Vars
 */
function buildGlobalVars_fnApi($globalVars) {
  $varsArr = array();
  $vars = "";
  if ($globalVars) {
    $varsArr = explode(',', $globalVars);
    foreach($varsArr as $i => $var) {
      $var = trim($var);
      $arg = trim($args[$i]);
      
      if ($i < (count($varsArr) - 1)) {
        $vars .= <<<JS
        
  {$var},
JS;
      } else {
        $vars .= <<<JS
        
  {$var}
JS;
      }
    }
  }
  return $vars;
}

/**
 * Build Global jQuery Vars
 */
function buildGlobalVarsJquery_fnApi($globalVarsJquery) {
  $varsArr = array();
  $vars = "";
  if ($globalVarsJquery) {
    $varsArr = explode(',', $globalVarsJquery);
    foreach($varsArr as $i => $var) {
      $var = trim($var);
      $arg = trim($args[$i]);
      
      if ($i < (count($varsArr) - 1)) {
        $vars .= <<<JS
        
  {$var},
JS;
      } else {
        $vars .= <<<JS
        
  {$var},
JS;
      }
    }
  }
  return $vars;
}

/**
 * Build Helper Methods - Core
 */
function buildHelperMethodsCore_fnApi() {
  $methods = <<<JS
  
  /* =============================================================
  GLOBALS - CORE HELPER FUNCTIONS
  ============================================================= */
  
  function test ( input, type ) {
    type = type || "function";
    if ( typeof input === type ) {
      return true;
    }
    return false;
  }

  function trigger ( event, args ) {
    var context = triggerContext;
    // for external use
    $(context).trigger(event, args);
    // for internal use
    \$events.triggerHandler(event);
  }

  function log ( label /*, arg2, arg3, ... argN */ ) {
    if ( __debug && typeof console === "object" ) {
      var 
      separator = " --- ",
      args = (arguments.length === 1 ? [arguments[0]] : Array.apply(null, arguments));
      if ( label === __table && console.hasOwnProperty("table") ) {
        args.shift();
        console.table.apply(this, args);
      } else {
        if ( console.hasOwnProperty("log") ) {
          args[0] = (label.toString() + separator);
          console.log.apply(this, args);
        }
      }
    }
  }

  function getDOMSelector ( el, full ) {
    var selector = $(el)
      .parents()
      .map(function () { return el.tagName; })
      .get()
      .reverse()
      .concat([el.nodeName])
      .join(" > ");

    var id = $(el).attr("id");
    if ( id ) { 
      selector += "#"+ id;
    }

    var classNames = $(el).attr("class");
    if ( classNames ) {
      selector += "." + $.trim(classNames).replace(/\s/gi, ".");
    }
    
    if ( !full ) {
      var parts = selector.split("#");
      if ( parts.length > 1 ) {
        parts.shift();
        selector = "#" + parts.join();
      }
    }
    
    return selector;
  }

  function getSelector ( el ) {
    var \$el = $(el);

    var id = \$el.attr("id");
    if ( id ) { //"should" only be one of these if theres an ID
        return "#"+ id;
    }

    var selector = \$el
      .parents()
      .map(function () { return this.tagName; })
      .get()
      .reverse()
      .join(" ");

    if ( selector ) {
        selector += " "+ \$el[0].nodeName;
    }

    var classNames = \$el.attr("class");
    if ( classNames ) {
        selector += "." + $.trim(classNames).replace(/\s/gi, ".");
    }

    var name = \$el.attr('name');
    if ( name ) {
        selector += "[name='" + name + "']";
    }
    if ( !name ){
        var index = \$el.index();
        if ( index ) {
            index = index + 1;
            selector += ":nth-child(" + index + ")";
        }
    }
    
    return selector;
  }

  function doCallback ( callback, argsArr, context ) {
    context = context || self;
    if ( test(callback) ) {
      callback.apply(self, argsArr);
    }
  }
JS;

  return $methods;
}

/**
 * Build Helper Methods - Custom
 */
function buildHelperMethodsCustom_fnApi($helperMethods) {
  $methods = "";
  
  if ($helperMethods) {
    $methodsArr = explode(',', $helperMethods);
    $methods .= <<<JS

    
  /* ============================================================= 
  CUSTOM HELPER FUNCTIONS
  ============================================================= */
   
JS;
    $methodsArr = getMethodParts($helperMethods);
    $names = $methodsArr['names'];
    $args = $methodsArr['args'];
    
    $methodsArr = $names;
    
    foreach($methodsArr as $i => $method) {
      $method = trim($method);
      $arg = trim($args[$i]);
      
      if ($i < (count($methodsArr) - 1)) {
        $methods .= <<<JS
        
  function {$method} ( {$arg} ) {}

JS;
      } else {
        $methods .= <<<JS
        
  function {$method} ( {$arg} ) {}
JS;
      }
    }
  }
  return $methods;
}

/**
 * Build Api Methods
 */
function buildApiMethods_fnApi($pluginName, $apiMethods) {
  $methods = <<<JS
    
    onReady: function () {
      var callback = function () {
        if ( test(self._callback, "function") ) {
          self._callback.call(self);
        }
        trigger(event_ready, [this]);
      }
      
      callback.call(this.\$element, this);
      return this.\$element;
    },
    
    addBindings: function () {
      
      // $(document).on(...
      
      return this.\$element;
    },
    
    prep: function () {
      if ( this._DOMSelector ) {
        \$source = this.\$element;
      } else {

      }
    }
JS;
  if ($apiMethods) {
    $methodsArr = explode(',', $apiMethods);
    $methods .= ",";
    $methods .= <<<JS

    
JS;
    
    $methodsArr = getMethodParts($apiMethods);
    $names = $methodsArr['names'];
    $args = $methodsArr['args'];
    
    $methodsArr = $names;
    
    foreach($methodsArr as $i => $method) {
      $method = trim($method);
      $arg = trim($args[$i]);
      
      if ($i < (count($methodsArr) - 1)) {
        $methods .= <<<JS
        
    {$method}: function ( {$arg} ) {},
  
JS;
      } else {
        $methods .= <<<JS
        
    {$method}: function ( {$arg} ) {}
JS;
      }
    }
  }
  return $methods;
}

/**
 * Build Constructor
 */
function buildConstructor_fnApi($pluginName) {
  $constructor = <<<JS
  
  /* ============================================================= 
  PUBLIC CLASS DEFINITION
  ============================================================= */
  
  /**
   * ${pluginName}
   * 
   * @usage: new {$pluginName}(element, options, callback)
   */
  function {$pluginName} ( element, options, callback ) {
    this._defaults = defaults;
    this._options = options;
    this._callback = callback;
    this._name = pluginName;
    this._version = pluginVersion;
    this._DOMSelector = getDOMSelector(element);

    this.element = element;
    this.\$element = $(element);
    this.selector = getSelector(element);

    /*
     * This next line takes advantage of HTML5 data attributes
     * to support customization of the plugin on a per-element
     * basis. For example,
     * <div class='item' data-plugin-options='{"message":"Goodbye World!"}'></div>
     */
    this.metadata = this.\$element.data( "plugin-options" );
    
    this.settings = $.extend({}, this._defaults, this._options, this.metadata);

    this.init();
    this.onReady();
  }
JS;

  return $constructor;
}

/**
 * Build Body - Fn Api
 */
function buildBody_fnApi($pluginName, $str) {
  $lines .= <<<JS
      
      self = this;
      settings = this.settings;
      
      this.prep();
      this.addBindings();
      
      // MAIN BODY - BEGIN
      
      
JS;
  if ($str) {
    $eol = PHP_EOL;
    foreach(preg_split('/(?:'.$eol.'|\n)/', $str) as $line) {
      $lines .= <<<JS
{$line}
      
JS;
    }
  } else {
    $lines .= <<<JS
/* .............. */

JS;
  }
  
  $body = <<<JS
  
    init: function () {{$lines}
      // MAIN BODY - END
      
      trigger(event_init, [this]);
      return this.\$element;
    },
    
JS;
  return $body;
}

/**
 * Build Description
 */
function buildDescription($name, $str, $version = "") {
  $lines = <<<JS
/**
 * {$name}
JS;
  if ($str) {
    $lines .= <<<JS

 *
JS;
    $eol = PHP_EOL;
    foreach(preg_split('/(?:'.$eol.'|\n)/', $str) as $line) {
      $lines .= <<<JS

 * {$line}
JS;
    }
  }
  
  $lines .= <<<JS
  
 *
 * @version: {$version}
 */
JS;
  return $lines;
}

/**
 * Build License
 */
function buildLicense($licensOrg, $copyrightYear, $pluginName, $pluginVersion, $pluginDescription, $generatedBy = "") {
  $license = <<<JS
/* =============================================================

jQuery Plugin - {$pluginName}
v{$pluginVersion}

{$pluginDescription}

Generated by: {$generatedBy}

================================================================
* Copyright {$copyrightYear} {$licensOrg}
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
============================================================= */

JS;
  return $license;
}

switch ($pluginStyle) {
  case $typeMap['1']:
    $body       = buildBody_simple($pluginBody);
    $options    = buildOptions($defaultOptions);
    $optionsArg = $defaultOptions ? "options" : "";
    $methods    = buildHelperMethods($helperMethods);
    
    $end = <<<JS

  
JS;
    
    $out = <<<JS
;(function ($, window, document, undefined) {
  $.{$pluginName} = function ({$optionsArg}) {{$options}{$methods}{$body}{$end}};
})(window.jQuery, window, document);
JS;
    break;
    
  case $typeMap['2']:
    $body       = buildBody_fnSimple($pluginBody);
    $options    = buildOptions($defaultOptions);
    $optionsArg = $defaultOptions ? "options" : "";
    $methods    = buildHelperMethods($helperMethods);
    
    $end = <<<JS

  
JS;

    $out = <<<JS
;(function ($, window, document, undefined) {
  $.fn.{$pluginName} = function ({$optionsArg}) {{$options}{$methods}{$body}{$end}};
})(window.jQuery, window, document);
JS;
    break;
    
  case $typeMap['3']:
    $defaults     = buildDefaults_fnApi($defaultOptions);
    $events       = buildEvents_fnApi($pluginEvents, $pluginPrefix, $pluginNamespace);
    $varsG        = buildGlobalVars_fnApi($globalVars);
    $varsJ        = buildGlobalVarsJquery_fnApi($globalVarsJquery);
    $vars         = buildVars_fnApi($pluginName, $pluginPrefix, $pluginNamespace, $pluginVersion, $events, $defaults, $varsG, $varsJ);
    
    $wrapper      = buildWrapper_fnApi($pluginName);
    $constructor  = buildConstructor_fnApi($pluginName);
    $body         = buildBody_fnApi($pluginName, $pluginBody);
    $methodsHCore = buildHelperMethodsCore_fnApi();
    $methodsH     = buildHelperMethodsCustom_fnApi($helperMethods);
    $methodsA     = buildApiMethods_fnApi($pluginName, $apiMethods);
    $description  = buildDescription($pluginName, $pluginDescription, $pluginVersion);
    $license      = buildLicense($licenseOrg, $copyrightYear, $pluginName, $pluginVersion, $pluginDescription, $generatedBy);
    
    $out = "";
    if ($includeLicense) {
      $out .= <<<JS
{$license}

JS;
    }
    $out .= <<<JS
{$description}
;(function ( $, window, document, undefined ) {
  
  "use strict";
  {$vars}{$methodsH}
  {$constructor}
  
  /* =============================================================
  API OBJECT PROTOTYPE
  ============================================================= */
  
  {$pluginName}.prototype = {
    constructor: {$pluginName},
    {$body}{$methodsA}
  };
  {$methodsHCore}
  {$wrapper}
  
  /*
   * Make defaults publicly accessible
   */
  $.fn[pluginName].defaults = defaults;
  
  /* 
   * This next line makes the constructor publicly accessible.
   *
   * Detailed Explanation:
   * Each jQuery plugin defines it's primary method on the $.fn object, 
   * which has access to the constructors it needs via the closure. 
   * But the constructor itself is private to that closure. 
   * Assigning it to $.fn.myplugin.Constructor makes that constructor 
   * accessible to other code, allowing you to have advanced control if necesary.
   *
   * For Example:
   * var myAlert = new $.fn.alert.Constructor('Hello World');
   *
   * Now you could something like this instead:
   * $.fn.alert.Alert = Alert;
   *
   * This is, subjectively, ugly and redundant. And now you would have to 
   * translate or guess the property name that leads to the constructor. 
   * If you say each plugin is implemented with a single class, 
   * and each class constructor can be found at $.fn.myplugin.Constructor 
   * then you have a consistent interface for accessing the classes behind each plugin.
   */
  $.fn[pluginName].Constructor = {$pluginName};
  
})( window.jQuery, window, document );
JS;
    break;
    
  default:
    $out = "n/a";
    break;
}

function display($return = false) {
    global $out;
    if ($return) {
      return $out;
    } else {
      echo $out;
    }
}
