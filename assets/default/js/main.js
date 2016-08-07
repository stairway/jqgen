;(function($, window, document, undefined) {
  $(document).ready(function(){
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/clouds");
    editor.getSession().setMode("ace/mode/javascript");
    
    $("a.btn[data-form]").on("click", function(e){
      var 
      $styleTarget = $("input[name=pluginStyle]:checked"),
      $nameTarget = $("input[name=pluginName]");
      
      if (!$styleTarget.length) {
        $("input[value=jqueryFnApi]").prop("checked", true);
      }
      
      if (!$nameTarget.val()) {
        $nameTarget.val($nameTarget.attr("placeholder"));
      }
      
      $("#"+$(this).data("form")).submit();
      
      e.preventDefault();
    });
    
    $("form").on("submit", function(){
      $("#pluginBody").val(editor.getSession().getValue());
    });
    
    editor.getSession().setValue($("#pluginBody").val());
  });
})(jQuery, window, document);