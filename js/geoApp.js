$(function () {
  checkLogged();

  $('#salir').click(() => {
    $('#logout').submit();
  });
});

var id_zona = getParam("id_zona");
let id_plan = getParam("id_plan");
let id_foc = getParam("id_foc");
let user = getParam("user");

function getParam(param) {
  param = param.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + param + "=([^&#]*)");
  var results = regex.exec(location.search);
  return results === null ?
    "" :
    decodeURIComponent(results[1].replace(/\+/g, " "));
}

if (id_zona == "all") {
  getZonas();
} else {
  getPlaneaciones(id_zona);
}

function getZonas() {
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

function getPlaneaciones(zona) {
  $('#planeaciones div').html(
    `<div class="alert alert-warning" role="alert">
      <a class="btn btn-success" onclick="rtnBtn()" id="returnBtn">Regresar</a>
    </div>`
  );
  $('#zona').addClass('hide');
  $.ajax({
    type: "POST",
    url: "server/getPlaneaciones.php",
    data: {
      geoAppPlan: zona
    },
    dataType: "json",
    success: function (response) {
      if (response == "") {
        if (id_zona == "all") {
          $('#planeaciones div').html(
            `<div class="alert alert-warning" role="alert">
              No hay planeaciones digitadas para hoy! <a class="btn btn-success" onclick="rtnBtn()" id="returnBtn">Regresar</a>
            </div>`
          );
        } else {
          $('#planeaciones div').html(
            `<div class="alert alert-warning" role="alert">
              No hay planeaciones digitadas para hoy!
            </div>`
          );
        }

      } else {
        response.forEach(element => {
          $('#divPlan').append(
            `<div id="card${element.id_planeacion}" class="card">
              <div class="card-header">
                ${element.municipio}
              </div>
              <div class="card-body">
                <h5 class="card-title">${element.comportamientos} - ${element.competencia}</h5>
                <p class="card-text">${element.nombre_estrategia}</p>
                <p class="card-text">${element.nombre_entidad}</p>
                <a class="btn btn-primary" data-toggle="modal" data-target="#detalle_${element.id_planeacion}">Ver detalles <i class="fas fa-info-circle"></i></a>
                <a id="${element.id_planeacion}" onclick="getLocalizacion(${element.id_planeacion})" class="btn btn-success geoloc"> Iniciar actividad <i class="fas fa-map-marker-alt"></i></a>
                </div>
            </div>`
          );

          getDetallePlaneacion(element.id_planeacion);

          getEtapaPlaneacion(element.id_planeacion);

        });
      }
    },
    complete: function () {
      $('#planeaciones').removeClass('hide');
    }
  });
}

function getLocalizacion(id_plan) {
  $(".loader").fadeIn();
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(position => {
      $.ajax({
        type: "POST",
        url: "server/geoLocation.php",
        data: {
          geo : {
            id_plan: id_plan,
            latitude: position.coords.latitude,
            longitude: position.coords.longitude
          }
        },
        dataType: "json",
        success: function (response) {
          if (response.etapa == "Iniciada") {
            $(`#${id_plan}`).addClass('en-ejecucion');
            $(`#${id_plan}`).html('Finalizar actividad <i class="fas fa-map-marker-alt"></i>');
            $(".loader").fadeOut();
          } else if (response.etapa == "Finalizada") {
            $(`#${id_plan}`).remove();
            $(`#card${id_plan} .card-body`).append(
              `<div class="alert alert-success" role="alert">
              <i class="fas fa-check-circle" style="font-size: 2em;"></i>
              </div>`
            );
            $(".loader").fadeOut();
          }
        }
      });
    });
  } else {
    alert('La geolocalización no se encuentra disponible en este navegador');
  }
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
      $("#menu").click(function () {
        window.location.href = `home.html?user=${data.rol}&id_zona=${data.zona}`;
      });
      $('#nombre h3').html(`${data.nombre}`);
    }
  });
}


function rtnBtn() {
  $('#planeaciones').addClass('hide');
  $('#zona').removeClass('hide');
}

function getEtapaPlaneacion(id_plan) {

  $.ajax({
    type: "POST",
    url: "server/geoLocation.php",
    data: {
      estado: id_plan
    },
    dataType: "json",
    success: function (response) {
      /* Switch for state of planeacion */
      switch (response.estado) {
        case "En Ejecución":
          $(`#${id_plan}`).addClass('en-ejecucion');
          $(`#${id_plan}`).html('Finalizar actividad <i class="fas fa-map-marker-alt"></i>');
          break;
        case "Ejecutado":
          $(`#${id_plan}`).remove();
          $(`#card${id_plan} .card-body`).append(
            `<div class="alert alert-success" role="alert">
              <i class="fas fa-check-circle" style="font-size: 2em;"></i>
            </div>`
          );
          break;
      }
    }
  });
}

function getDetallePlaneacion(id_plan) {
  $.ajax({
    type: "POST",
    url: "server/getPlaneaciones.php",
    data: {
      detallePlaneacion: id_plan
    },
    dataType: "json",
    success: function (response) {
      $('body').append(
        `<div id="detalle_${id_plan}" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
          aria-hidden="true" id="detalleEjecModal">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div id="detalleCard">
                <div id="headerDetalleModal">
                  <h2>Detalle Planeación</h2>
                </div>
                <div id="detalleEjecModalBody${id_plan}" class="detalleEjec detalleModal">
                  <div id="detalleCardContentGI">
                    <div class="row">
                      <h5 class="title">
                        Fecha de la planeación: &nbsp;<h6 id="fechaDetallePlan"> ${response[1].fecha}</h6>
                      </h5>
                    </div>
                    <hr>
                    <div class="row">
                      <h5 class="title">
                        Gestor: <h6> &nbsp; ${response[1].gestor}</h6>
                      </h5>
                    </div>
                    <hr>
                    <div class="row">
                      <h5 class="title">
                        Zona: <h6> &nbsp; ${response[1].zona}</h6>
                      </h5>
                    </div>
                    <hr>
                    <div class="row">
                      <h5 class="title">
                        Municipio: <h6> &nbsp; ${response[1].mun}</h6>
                      </h5>
                    </div>
                    <hr>
                    <div class="row">
                      <h5 class="title">
                        Entidad: <h6> &nbsp; ${response[1].entidad}</h6>
                      </h5>
                    </div>
                    <hr>
                    <div class="row">
                      <h5 class="title">
                        Comportamiento / Competencia: <h6> &nbsp; ${response[1].compor} / ${response[1].compe}</h6>
                      </h5>
                    </div>
                  </div>
                  <hr>
                  <div id="detalleCardContentGF">
                    <div class="row">
                      <h5 class="title">
                        Estrategias: <h6> &nbsp; ${response[1].estrategias}</h6>
                      </h5>
                    </div>
                    <hr>
                    <div class="row">
                      <h5 class="title">
                        Tacticos: <h6 id="tacticosList${id_plan}"> &nbsp; <ul></ul></h6>
                      </h5>
                    </div>
                    <hr>
                    <div class="row">
                      <h5 class="title">
                        Temas: <h6> &nbsp; ${response[1].temas}</h6>
                      </h5>
                    </div>
                    <hr>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>`
      )

      $(`#detalleEjecModalBody${id_plan}`).append(
        `<div class="row rowForms">
          <div class="col-lg-12 col-md-12 col-sm-12 colBtnDetalle">
            <button id="cerrarDetalleModal" data-toggle="modal" data-target="#detalle_${id_plan}" type="button" class="btn btn-danger">Cerrar</button>
          </div>
        </div>`
      )

      var tactic = response[1].tacticos;
      for (let index = 0; index < tactic.length; index++) {
        const element = tactic[index];
        $(`#tacticosList${id_plan} ul`).append(
          `<li>${element}</li>`
        )
      }
    }
  });
}