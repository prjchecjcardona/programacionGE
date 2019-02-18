$(function(){

});

let id_zona = getParam("id_zona");
let id_ejec = getParam("id_ejec");

function getParam(param) {
  param = param.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + param + "=([^&#]*)");
  var results = regex.exec(location.search);
  return results === null ?
    "" :
    decodeURIComponent(results[1].replace(/\+/g, " "));
}


function getDetallesEjecucion(){
  $.ajax({
    type: "POST",
    url: "server/getEjecuciones.php",
    data: {
      id_plan : id_plan
    },
    dataType: "json",
    success: function (response) {
      
    }
  });
}

function checkLogged() {
  $.ajax({
    type: "POST",
    url: "server/checkLog.php",
    data: {
      zona: id_zona
    },
    dataType: "json"
  }).done(function (data) {
    if (data.error) {
      swal({
        type: "info",
        title: "Usuario",
        text: data.message
      }).then(function () {
        window.location.href = "iniciarSesion.html";
      });
    } else {
      $('#homeBtn').attr('href', `home.html?user=${data.rol}&id_zona=${data.zona}`);
    }
  });
}