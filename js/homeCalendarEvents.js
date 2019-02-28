$(function () {
  getMunicipiosXZ($("#calendarZona").val());
  getEstrategiasCalendar();
  getTemasCalendar();

  var containerEl = $(".myCalendar");
  testCalendar();
  containerEl.fullCalendar({
    monthNames: [
      "Enero",
      "Febrero",
      "Marzo",
      "Abril",
      "Mayo",
      "Junio",
      "Julio",
      "Agosto",
      "Septiembre",
      "Octubre",
      "Noviembre",
      "Diciembre"
    ],
    monthNamesShort: [
      "Ene",
      "Feb",
      "Mar",
      "Abr",
      "May",
      "Jun",
      "Jul",
      "Ago",
      "Sep",
      "Oct",
      "Nov",
      "Dic"
    ],
    themeSystem: "bootstrap4",
    customButtons: {
      detalles: {
        text: "Indicador Calendario",
        click: function () {
          $("#modalCalendarDetails").modal("toggle");
        }
      },
      filtros: {
        text: "Aplicar filtros",
        click: () => {
          $("#modalCalendarFilters").modal("toggle");
        }
      }
    },
    height: getCalendarHeight(),
    header: {
      left: "prev,next, today, detalles, filtros",
      center: "title",
      right: "month,listWeek,agendaDay"
    },
    navLinks: true, // can click day/week names to navigate views
    editable: false,
    draggable: false,
    eventLimit: true, // allow "more" link when too many events

    eventRender: function eventRender(event, element, view) {
      return (
        ["0", String(event.id_zona)].indexOf($("#calendarZona").val()) >= 0 && ["0", String(event.municipio)].indexOf($("#calendarMunicipio").val()) >=
        0 && ["0", String(event.tema)].indexOf($("#calendarTema").val()) >= 0 && ["0", String(event.estrategia)].indexOf(
          $("#calendarEstrategia").val()
        ) >= 0 && ["0", String(event.tipo_gestion)].indexOf($("#calendarTipoG").val()) >=
        0 && ["0", String(event.status)].indexOf($("#estadoPlaneacion").val()) >= 0
      );
    },
    eventClick: function (event, jsEvent, view) {
      generateModal(event);
    }
  });

  //
  $(window).resize(function () {
    $("#calendar").fullCalendar("option", "height", getCalendarHeight());
  });

  //Adds showNone to calendar class when created.
  var calendar = document.getElementById("calendar");
  calendar.className += " showNone";
});

$("#calendarZona").change(() => {
  getMunicipiosXZ($("#calendarZona").val());
});

/* PARAMETERS */
id_zona = getParam("id_zona");
user = getParam("user");

const swalWithBootstrapButtons = Swal.mixin({
  confirmButtonClass: "btn btn-success",
  cancelButtonClass: "btn btn-danger",
  buttonsStyling: false
});

function getParam(param) {
  param = param.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + param + "=([^&#]*)");
  var results = regex.exec(location.search);
  return results === null ?
    "" :
    decodeURIComponent(results[1].replace(/\+/g, " "));
}

function testCalendar() {
  $("#loaderCalendar").fadeIn();
  $.ajax({
    type: "POST",
    url: "server/getPlaneacionesCalendar.php",
    data: {
      getPlans: '',
      id_zona: id_zona
    },
    dataType: "json",
    success: function (response) {
      $('#calendar').fullCalendar("addEventSource", response.no_ejecutados);
      $('#calendar').fullCalendar("addEventSource", response.en_ejecucion_ejecutados);
      $('#calendar').fullCalendar("addEventSource", response.en_planeacion);
      getTrabajoAdministrativo();
    }
  });
}

function getTrabajoAdministrativo() {
  $.ajax({
    type: "POST",
    url: "server/getTAdministrativos.php",
    data: "",
    dataType: "json",
    success: function (response) {
      $("#calendar").fullCalendar("addEventSource", response);
      $("#calendar").fullCalendar("rerenderEvents");
    },
    complete: function () {
      $("#calendar").fadeIn();
      $("#calendar").removeClass("showNone");
      $("#loaderCalendar").fadeOut();

      $(
        "#calendarZona, #calendarMunicipio, #calendarEstrategia, #calendarTema, #calendarTipoG, #estadoPlaneacion"
      ).on("change", function () {
        $("#calendar").fullCalendar("rerenderEvents");
      });

      $("#calendar").fullCalendar("rerenderEvents");
      getCalendarEventsZona();
    }
  });
}

function getCalendarHeight() {
  return $(window).height() - 151;
}

function getCalendarEventsZona() {
  if (id_zona != "all") {
    $("#calendarZona").val(id_zona);
    $("#calendar").fullCalendar("rerenderEvents");
  }
}

function setModal(color) {
  if (color != "#edbe00" && color != "#269226") {
    if (!$("#modalEventDescription #left").hasClass("planeado")) {
      $("#modalEventDescription #left").addClass("planeado");
      $("#modalEventDescription #right").addClass("showNone");
    }
  } else {
    if ($("#modalEventDescription #left").hasClass("planeado")) {
      $("#modalEventDescription #left").removeClass("planeado");
      $("#modalEventDescription #right").removeClass("showNone");
    }
  }
}

function getMunicipiosXZ(zona) {
  $("#calendarMunicipio").prop("disabled", true);
  $.ajax({
    type: "POST",
    url: "server/getMunicipios.php",
    data: {
      zona: zona
    },
    dataType: "json",
    success: function (response) {
      $("#calendarMunicipio").html(`<option value="0" selected>Todos</option>`);
      response.forEach(element => {
        $("#calendarMunicipio").append(
          `<option value="${element.municipio}">${element.municipio}</option>`
        );
      });
    }
  }).done(() => {
    $("#calendarMunicipio").prop("disabled", false);
  });
}

function getEstrategiasCalendar() {
  $.ajax({
    type: "POST",
    url: "server/getEstrategias.php",
    data: "",
    dataType: "json",
    success: function (response) {
      $("#calendarEstrategia").html(
        `<option value="0" selected>Todos</option>`
      );
      response.forEach(element => {
        $("#calendarEstrategia").append(
          `<option value="${element.nombre_estrategia}">${
            element.nombre_estrategia
          }</option>`
        );
      });
    }
  });
}

function getTacticosXEstrategias(estrat) {
  $.ajax({
    type: "POST",
    url: "server/getEstrategias.php",
    data: "",
    dataType: "json",
    success: function (response) {
      $("#calendarEstrategia").html(
        `<option value="0" selected>Todos</option>`
      );
      response.forEach(element => {
        $("#calendarEstrategia").append(
          `<option value="${element.nombre_estrategia}">${
            element.nombre_estrategia
          }</option>`
        );
      });
    }
  });
}

function eliminarPlaneacion(id_plan, type) {
  swal({
    type: "warning",
    title: "Eliminar Planeación",
    text: "Está seguro de eliminar una planeación",
    showCancelButton: true,
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
    reverseButtons: true
  }).then(result => {
    if (result.value) {
      $.ajax({
        type: "POST",
        url: "server/eliminar.php",
        data: {
          id_plan: id_plan,
          type: type
        },
        dataType: "json",
        success: function (response) {
          if (response.error == 0) {
            id = $('#modalEventsCalendar').val();
            $('#calendar').fullCalendar('removeEvents', id);
            $('#modalEventsCalendar').modal('hide');
            swalWithBootstrapButtons.fire(
              'Eliminado!',
              'Planeación eliminado con éxito',
              'success'
            );
          } else {
            swalWithBootstrapButtons.fire(
              "No se pudo eliminar planeación",
              response.error_message,
              "error"
            );
          }
        }
      });
    } else if (
      // Read more about handling dismissals
      result.dismiss === Swal.DismissReason.cancel
    ) {
      swalWithBootstrapButtons.fire("Cancelado", "", "error");
    }
  });
}

function eliminarEjecucion(id_plan, type) {
  swal({
    type: "warning",
    title: "Eliminar Ejecución",
    text: "Está seguro de eliminar una ejecución",
    showCancelButton: true,
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
    reverseButtons: true
  }).then(result => {
    if (result.value) {
      $.ajax({
        type: "POST",
        url: "server/eliminar.php",
        data: {
          id_plan: id_plan,
          type: type
        },
        dataType: "json",
        success: function (response) {
          if (response.error == 0) {

            returnToEjecucion();

            $(`#editarejec_${id_plan}`).css('display', 'none');
            $(`#eliminarejec_${id_plan}`).css('display', 'none');
            swalWithBootstrapButtons.fire(
              'Eliminado!',
              "Ejecución eliminado con éxito",
              'success'
            );
          } else {
            swalWithBootstrapButtons.fire(
              "No se pudo eliminar ejecución",
              response.error_message,
              "error"
            );
          }
        }
      });
    } else if (
      // Read more about handling dismissals
      result.dismiss === Swal.DismissReason.cancel
    ) {
      swalWithBootstrapButtons.fire("Cancelado", "", "error");
    }
  });
}

function generateModal(event) {
  $('#right').html('');
  var header = document.getElementById("modalEventHeader");
  var tacticos = $.map(event.tacticos, (v) => {
    return v;
  }).join(', ');


  header.style.cssText = `background-color: ${event.color} !important`;
  $('#modalEventsCalendar').val(event.id);
  $('#modalEventTitle').html(event.title);
  $("#modalEventTitle").css('color', 'white');


  if(event.color == '#a2a1a0' || event.color == 'blue'){
    $('#modalEventDescription #left').html(event.description);
    $('#modalEventsCalendar>.modal-dialog').css('width', '45%');
  }else{
    $('#modalEventsCalendar>.modal-dialog').css('width', '75%');
    $('#left').html(
      `<ul>
        <li>Fecha: ${event.descripcion.fecha}</li>  
        <li>Jornada: ${event.descripcion.jornada}</li>  
        <hr>
        <li>Comportamiento: ${event.descripcion.comportamiento}</li> 
        <li>Competencia: ${event.descripcion.competencia}</li> 
        <li>Tema: ${event.descripcion.tema}</li> 
        <hr>
        <li>Zona: ${event.descripcion.zona}</li>
        <li>Municipio: ${event.municipio}</li>
        <li>Lugar de encuentro: ${event.descripcion.lugar}</li>
        <hr>
        <li>Estrategia: ${event.descripcion.estrategia}</li>  
        <li>Tacticos: ${tacticos}</li> 
        <hr>
        <li>Tipo de gestión: ${event.descripcion.gestion}</li>  
        <li>Estado: ${event.status}</li> 
        <li>Gestor: ${event.descripcion.gestor}</li>
      </ul>`
    );
  }

  if(event.color == '#269226' || event.color == '#edbe00'){

    $('#right').show();
    $('#left').css('width', '48%');

    var requisitos = $.map(event.requisitos, (v) => {
      return v;
    }).join(' ');

    $('#right').html(
      `<i class="${event.icon}" style="font-size: 3em;color: ${event.color};align-self: center;"></i>
      <div>
        <div class="row">
          <h4>Hora de Inicio: </h4>
          <h3> ${event.hora.hora_inicio} </h3>
        </div>
        <div class="row">
          <h4>Hora de Finalización: </h4>
          <h3> ${event.hora.hora_fin} </h3>
        </div>
      </div>
      <div class="accordion" id="requisitosPlan">
        <div class="card">
          <div class="card-header" id="headingOne">
            <h2 class="mb-0">
              <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne"
                aria-expanded="false" aria-controls="collapseOne">Mira lo que falta para ejecutar por completo la
                planeación</button>
            </h2>
          </div>
  
          <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#requisitosPlan">
            <div class="card-body">
              <ul>
                ${requisitos}
              </ul>
            </div>
          </div>
        </div>
      </div>`
    );
  }else{
    $('#right').hide();
    $('#left').css('width', '100%');
  }

  var ejec = '';
  if(event.valid_ejec){
    ejec = 
      `<button type="button" id="editarejec_${event.id}" class="btn btn-success"><i class="fas fa-edit"></i> Ejecución</button>
        <button type="button" id="eliminarejec_${event.id}" onclick=eliminarEjecucion(${event.id},1) class="btn btn-danger"><i class="fas fa-trash-alt"></i> Ejecución</button>`;

    if(event.tipo_gestion == 1){
      $('#right').append(
        `<hr>
        <div class="accordion" id="totalAsistentes">
          <div class="card">
            <div class="card-header" id="headingOne">
              <h2 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#total"
                  aria-expanded="false" aria-controls="total">Total Asistentes</button>
              </h2>
            </div>
  
            <div id="total" class="collapse" aria-labelledby="headingOne" data-parent="#totalAsistentes">
              <div class="card-body">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Tipo Población</th>
                      <th scope="col">Total</th>
                    </tr>
                  </thead>
                  <tbody id="totalBody">
  
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>`
      );
  
      var sum = 0;
      event.total_participantes.forEach(element => {
        sum += element.total;
        $('#totalBody').append(
          `<tr>
            <td>${element.tipo}</td>
            <td>${element.total}</td>
          </tr>`
        )
      });
  
      $('#totalBody').append(
        `<tr>
          <td>Total</td>
          <td>${sum}</td>
        </tr>`
      )
    }
  }

  if(event.color == '#a2a1a0' || event.color == 'blue'){
    $('#modalEventsCalendar .modal-footer').html(
      `<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>`
    );
  }else{
    $('#modalEventsCalendar .modal-footer').html(
      `<div id="editar_borrar">
      <button type="button" class="btn btn-success"><i class="fas fa-edit"></i> Planeación</button>
      <button type="button" onclick=eliminarPlaneacion(${event.id},0) class="btn btn-danger"><i class="fas fa-trash-alt"></i> Planeación</button>
      ${ejec}
      </div>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>`
    );
  }

  $('#modalEventsCalendar').modal();
}

function getTemasCalendar() {
  $.ajax({
    type: "POST",
    url: "server/getTemas.php",
    data: {
      comportamiento: ""
    },
    dataType: "json",
    success: function (response) {
      $("#calendarTema").html(`<option value="0" selected>Todos</option>`);
      response.forEach(element => {
        $("#calendarTema").append(
          `<option value="${element.temas}">${element.temas}</option>`
        );
      });
    }
  });
}

function returnToEjecucion(){
  var id = $('#modalEventsCalendar').val();
  var event = $('#calendar').fullCalendar('clientEvents', id);
  event[0].color = '#edbe00';
  event[0].icon = 'fas fa-minus-circle'
  event[0].valid_ejec = false;
  event[0].requisitos.push('<li> Registrar la ejecución de la actividad </li>');
  $('#calendar').fullCalendar('updateEvent', event[0]);
  generateModal(event[0]);
}