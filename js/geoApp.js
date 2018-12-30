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
  $('#zona').addClass('hide');
  $.ajax({
    type: "POST",
    url: "server/getPlaneaciones.php",
    data: {
      geoAppPlan : zona
    },
    dataType: "json",
    success: function (response) {
      if(response == ""){
        $('#planeaciones div').html(
          `<div class="alert alert-warning" role="alert">
            No hay planeaciones digitadas para hoy! <a class="btn btn-success" id="returnBtn">Regresar</a>
          </div>
          <a class="btn btn-primary" id="startGeo">INICIAR GEOLOCALIZADOR</a>`
        );

        /* Function for return btn  */
        $('#returnBtn').click(() => {
          $('#planeaciones').addClass('hide');
          $('#zona').removeClass('hide');
        });

        /* Function for geolocation */
        $('#startGeo').click(() => {
          getLocalizacion();
        });
      }else{
        response.forEach(element => {
          /* Switch for state of planeacion */
          switch(element.estado){
            case "En ejecucion": 
            break;
          }
          $('#divPlan').append(
            `<div class="card">
              <div class="card-header">
                ${element.municipio}
              </div>
              <div class="card-body">
                <h5 class="card-title">${element.comportamientos} - ${element.competencia}</h5>
                <p class="card-text">${element.nombre_estrategia}</p>
                <a href="#" class="btn btn-primary">Ver detalles <i class="fas fa-info-circle"></i></a>
                <a href="#" id="${element.id_planeacion}" onclick="getLocalizacion(${element.id_planeacion})" class="btn btn-success geoloc">Iniciar actividad <i class="fas fa-map-marker-alt"></i></a>
              </div>
            </div>`
          );
        });
      }
    },
    complete: function(){
      $('#planeaciones').removeClass('hide');
    }
  });
}

function getLocalizacion(id_plan){
  if(navigator.geolocation){
    navigator.geolocation.getCurrentPosition(position => {
      $.ajax({
        type: "POST",
        url: "server/geoLocation.php",
        data: {
          id_plan: id_plan,
          latitude: position.coords.latitude,
          longitude: position.coords.longitude
        },
        dataType: "json",
        success: function (response) {
          if(response.etapa == "Iniciada"){
            $(`#${id_plan}`).addClass('en-ejecucion');
            $(`#${id_plan}`).html('Finalizar actividad <i class="fas fa-map-marker-alt"></i>');
          }else if(response.etapa == "Finalizada"){
              $(`#${id_plan}`).fadeOut();
          }
        }
      });
    });
  }else{
    alert('La geolocalización no se encuentra disponible en este navegador');
  }
}

function verifyState(id_plan){
  $.ajax({
    type: "POST",
    url: "server/getState.php",
    data: {
      id_plan : id_plan
    },
    dataType: "json",
    success: function (response) {
      
    }
  });
}