(function($, window, document, undefined){
          
  "use strict";
  
  var editor = ace.edit("editor");
  editor.setTheme("ace/theme/textmate");
  editor.getSession().setMode("ace/mode/javascript");
  editor.getSession().setUseSoftTabs(false);
  editor.getSession().setTabSize(2);
  editor.setShowInvisibles(true);
  editor.setAnimatedScroll(true);
  editor.setReadOnly(true);
  editor.getSession().setFoldStyle("markbeginend");
  editor.setShowFoldWidgets(true);
  editor.setOption("vScrollBarAlwaysVisible", true);
  
  var pluginBody = document.getElementById("pluginBody");
  editor.getSession().setValue(pluginBody.value);
  
  var urlOptions = {}
  try {
    window.location.search.slice(1).split(/[&]/).forEach(function(e) {
      var parts = e.split("=");
      urlOptions[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1]);
    });
  } catch(e) {
    console.error(e);
  }
  
  function saveOption(el, val) {
    if (!el.onchange && !el.onclick) {
      return;
    }

    if (el.type === "checkbox" || el.type === "radio") {
      if (typeof val !== "undefined") {
        el.checked = val;
      }
      try {
        window.localStorage.setItem(el.id, el.checked ? 1 : 0);
      } catch(e) {
        
      }
    } else {
      if (typeof val !== "undefined") {
        el.value = val;
      }
      try {
        window.localStorage && localStorage.setItem(el.id, el.value);
      } catch(e) {
        
      }
    }
  }
  
  function bindCheckbox(id, callback, noInit) {
    if (typeof id == "string") {
      var el = document.getElementById(id);
    } else {
      var el = id;
      id = el.id;
    }
    var el = document.getElementById(id);
    
    if (urlOptions[id]) {
      el.checked = urlOptions[id] == "1";
    } else if (localStorage && localStorage.getItem(id)) {
      el.checked = localStorage.getItem(id) == "1";
    }

    var onCheck = function() {
      callback(!!el.checked);
      saveOption(el);
    };
    el.onclick = onCheck;
    noInit || onCheck();
    return el;
  }
  
  function bindTextfield(id, callback, noInit) {
    if (typeof id == "string") {
      var el = document.getElementById(id);
    } else {
      var el = id;
      id = el.id;
    }
    var el = document.getElementById(id);
    
    if (urlOptions[id]) {
      el.value = urlOptions[id].value;
    } else if (localStorage && localStorage.getItem(id)) {
      el.value = localStorage.getItem(id).value;
    }

    var onChange = function() {
      var value = parseInt(el.value) || parseInt(el.defaultValue);
      callback(value);
      saveOption(el, value);
    };
    el.onchange = onChange;
    noInit || onChange();
    return el;
  }
  
  bindCheckbox("readOnly", function(checked){
    editor.setReadOnly(checked);
  });
  
  bindCheckbox("softTabs", function(checked){
    editor.getSession().setUseSoftTabs(!checked);
  });
  
  bindCheckbox("showInvisibles", function(checked){
    editor.setShowInvisibles(checked);
  });
  
  bindTextfield("tabSize", function(value){
    editor.getSession().setTabSize(value);
  });
  
  //var raw = document.getElementById("raw");
  //raw.addEventListener("click", function(e){
  //  var code = editor.getValue();
  //  var blob = new Blob([code], {type: "text/plain"});
  //  var url = URL.createObjectURL(blob);
  //  window.open(url, '_blank');
  //}, false);
  
  $(document).ready(function(){
    var shadowTimeout = false;
    var toolbarAttached = false;
    
    var raw = $("#raw");
    raw.on("click", function(){
      var code = editor.getValue();
      var blob = new Blob([code], {type: "text/plain"});
      var url = URL.createObjectURL(blob);
      window.open(url, '_blank');
    });
    
    function setToolbarAttached() {
      toolbarAttached = true;
      $("#toolbar").removeClass("hidden");
      $("body").addClass("toolbar-attached");
      editor.resize();
      $(document).off("mousemove.jqgen");
      $("#toolbar").off("mouseenter.jqgen");
      $("#toolbar").off("mouseleave.jqgen");
      $(document).off("touchstart.jqgen");
      $(document).off("touchend.jqgen");
      if (shadowTimeout) {
        clearTimeout(shadowTimeout);
      }
    }
    
    function setToolbarDetached() {
      toolbarAttached = false;
      $("body").removeClass("toolbar-attached");
      editor.resize();
      $(document).on("touchstart.jqgen", function(){
        $("#toolbar").removeClass("hidden");
      }).on("touchend.jqgen", function(){
        shadowTimeout = setTimeout(function(){
          $("#toolbar").addClass("hidden");
          clearTimeout(shadowTimeout);
          shadowTimeout = false;
        }, 3000);
      });
      
      $(document).on("mousemove.jqgen", function(){
        $("#toolbar").removeClass("hidden");
        
        $("#toolbar").on("mouseenter.jqgen", function(){
          $("#toolbar").addClass("over");
        }).on("mouseleave", function(){
          $("#toolbar").addClass("hidden");
          $("#toolbar").removeClass("over");
        });
        
        if (shadowTimeout) {
          clearTimeout(shadowTimeout);
          shadowTimeout = false;
        }
        
        if (!($("#toolbar").is(".over")) || ($("#toolbar").is(".hidden") && $("#toolbar").is(".over"))) {
          shadowTimeout = setTimeout(function(){
            $("#toolbar").addClass("hidden");
          }, 1000);
        }
      });
    }
    
    function toolbarClick() {
      $("#toolbar").on("click.jqgen", function(e){
        if ( $(e.target).is("#toolbar") || $(e.target).is("#editorOptions") ) {
          setToolbarAttached();
          $("#detachToolbar").show();
          $("#toolbar").off("click.jqgen");
        }
      });
    }
    
    function detachClick() {
      $("#detachToolbar").on("click.jqgen", function(e){
        $(this).hide();
        $("#toolbar").addClass("hidden");
        setToolbarDetached();
        var timeout = setTimeout(function(){
          toolbarClick();
          clearTimeout(timeout);
        }, 1000);
        e.preventDefault();
      });
    }
    
    detachClick();
    
    var deviceWidthSm = 480;
    
    $(window).resize(function(){
      if ($(this).width() <= deviceWidthSm) {
        $("#detachToolbar").hide();
        if (!toolbarAttached) {
          setToolbarAttached();
        }
      } else {
        if (toolbarAttached) {
          setToolbarDetached();
          toolbarClick();
        }
      }
    });
    
    $("#editorOptions").on("submit", function(e){
      e.preventDefault();
      return false;
    });
    
    if ($(window).width() <= deviceWidthSm) {
      $("#detachToolbar").hide();
      setToolbarAttached();
    } else {
      setToolbarDetached();
      toolbarClick();
    }
  });
})(window.jQuery, window, document);