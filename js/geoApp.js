$(function(){
  getZonas();
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

function getZonas(){
  $.ajax({
    type: "POST",
    url: "server/getZonas.php",
    data: "",
    dataType: "json",
    success: function (response) {
      response.forEach(element => {
        $('#zona div').append(
          `<button type="button" onclick="getPlaneaciones(${element.id_zona})" class="btn zona">${element.zonas}</button>`
        );
      });
    }
  });
}

function getPlaneaciones(zona){
  $('#planeaciones div').html('');
  $('#zona').fadeOut();
  $('#planeaciones').fadeIn();
  $.ajax({
    type: "POST",
    url: "server/getPlaneaciones.php",
    data: {
      geoAppPlan : zona
    },
    dataType: "json",
    success: function (response) {
      if(response == ""){
        $('#planeaciones div').html('No existe ninguna planeación registrada para hoy');
      }else{
        response.forEach(element => {
          $('#divPlan').append(
            `<div class="card">
              <div class="card-header">
                ${element.municipio}
              </div>
              <div class="card-body">
                <h5 class="card-title">${element.comportamientos} - ${element.competencia}</h5>
                <p class="card-text">${element.nombre_estrategia}</p>
                <a href="#" class="btn btn-primary">Ver detalles <i class="fas fa-info-circle"></i></a>
                <a href="#" class="btn btn-success">Iniciar actividad <i class="fas fa-map-marker-alt"></i></a>
              </div>
            </div>`
          );
        });
      }
    }
  });
}

function getLocalizacion(){
  
}