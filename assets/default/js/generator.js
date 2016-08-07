(function($, window, document, undefined){
          
  "use strict";
  
  var editor = ace.edit("editor");
  editor.setTheme("ace/theme/textmate");
  editor.getSession().setMode("ace/mode/javascript");
  editor.getSession().setUseSoftTabs(true);
  editor.setShowInvisibles(true);
  editor.setAnimatedScroll(true);
  editor.setReadOnly(true);
  editor.getSession().setFoldStyle("markbeginend");
  editor.setShowFoldWidgets(true);
  
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

    if ("checked" in el) {
      if (typeof val !== "undefined") {
        el.checked = val;
      }
      localStorage && localStorage.setItem(el.id, el.checked ? 1 : 0);
    } else {
      if (typeof val !== "undefined") {
        el.value = val;
      }                  
      localStorage && localStorage.setItem(el.id, el.value);
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
  };
  
  bindCheckbox("readOnly", function(checked){
    editor.setReadOnly(checked);
  });
  
  bindCheckbox("softTabs", function(checked){
    editor.getSession().setUseSoftTabs(!checked);
  });
  
  bindCheckbox("showInvisibles", function(checked){
    editor.setShowInvisibles(checked);
  });
  
  var raw = document.getElementById("raw");
  raw.addEventListener("click", function(e){
    var code = editor.getValue();
    var blob = new Blob([code], {type: "text/plain"});
    var url = URL.createObjectURL(blob);
    window.open(url, '_blank');
  }, false);
  
  $(document).ready(function(){
    var shadowTimeout = false;
    var toolbarAttached = false;
    
    function setToolbarAttached() {
      toolbarAttached = true;
      $("#toolbar").removeClass("hidden");
      $("body").addClass("toolbar-attached");
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
      $("#toolbar").on("click.jqgen", function(){
        setToolbarAttached();
        $("#toolbar").off("click.jqgen");
      });
    }
    
    $("#detachToolbar").on("click", function(e){
      setToolbarDetached();
      var timeout = setTimeout(function(){
        toolbarClick();
        clearTimeout(timeout);
      }, 1000);
      e.preventDefault();
    });
    
    var deviceWidthSm = 480;
    
    $(window).resize(function(){
      if ($(this).width() <= deviceWidthSm) {
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
    
    if ($(window).width() <= deviceWidthSm) {
      setToolbarAttached();
    } else {
      setToolbarDetached();
      toolbarClick();
    }
  });
})(window.jQuery, window, document);