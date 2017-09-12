  $(function() {
    $("#datepicker").datepicker({
    dateFormat: "yy-mm-dd"
    });

  $("#siContacto").click(function(){
    $("#preguntaContacto").show()
  });
  $("#noContacto").click(function(){
    $("#preguntaContacto").hide()
  });

  });
