$(function() {
  checkLogged();
});

var id_zona = getParam("id_zona");
let id_plan = getParam("id_plan");
let id_foc = getParam("id_foc");
let user = getParam("user");

function getParam(param) {
  param = param.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + param + "=([^&#]*)");
  var results = regex.exec(location.search);
  return results === null
    ? ""
    : decodeURIComponent(results[1].replace(/\+/g, " "));
}

$('#evaluacion').click(function(){
  $('.modEvaluacion').removeClass('showNone');
  $('#welcome1').addClass('showNone');
});

$('#return').click(function(){
  $('.modEvaluacion').addClass('showNone');
  $('#welcome1').removeClass('showNone');
});

function checkLogged() {
  $.ajax({
    type: "POST",
    url: "server/checkLog.php",
    data: {
      zona: id_zona
    },
    dataType: "json"
  }).done(function(data) {
    if (data.error) {
      swal({
        type: "info",
        title: "Usuario",
        text: data.message
      }).then(function() {
        window.location.href = "iniciarSesion.html";
      });
    } else {
      $("#menu").click(function () {
        window.location.href = `home.html?user=${data.rol}&id_zona=${data.zona}`;
      });
    }
  });
}