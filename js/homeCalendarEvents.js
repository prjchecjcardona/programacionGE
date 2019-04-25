$(function() {
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
        click: function() {
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
      if (event.adjunto == 1) {
        element
          .find(".fc-title")
          .prepend('<i class="fas fa-paperclip"></i>&nbsp');
      }
      return (
        ["0", String(event.id_zona)].indexOf($("#calendarZona").val()) >= 0 &&
        ["0", String(event.municipio)].indexOf($("#calendarMunicipio").val()) >=
          0 &&
        ["0", String(event.tema)].indexOf($("#calendarTema").val()) >= 0 &&
        ["0", String(event.estrategia)].indexOf(
          $("#calendarEstrategia").val()
        ) >= 0 &&
        ["0", String(event.tipo_gestion)].indexOf($("#calendarTipoG").val()) >=
          0 &&
        ["0", String(event.status)].indexOf($("#estadoPlaneacion").val()) >= 0
      );
    },
    eventClick: function(event, jsEvent, view) {
      generateModal(event);
    }
  });

  //
  $(window).resize(() => {
    $("#calendar").fullCalendar("option", "height", getCalendarHeight());
    widthCarousel();
  });

  //Adds showNone to calendar class when created.
  var calendar = document.getElementById("calendar");
  calendar.className += " showNone";
});

$("#calendarZona").change(() => {
  getMunicipiosXZ($("#calendarZona").val());
});

$("#calendarZonaConsulta").change(() => {
  getMunicipiosXZ($("#calendarZonaConsulta").val());
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
  return results === null
    ? ""
    : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function testCalendar() {
  $("#loaderCalendar").fadeIn();
  $.ajax({
    type: "POST",
    url: "server/getPlaneacionesCalendar.php",
    data: {
      getPlans: "",
      id_zona: id_zona
    },
    dataType: "json",
    success: function(response) {
      $("#calendar").fullCalendar("addEventSource", response.no_ejecutados);
      $("#calendar").fullCalendar(
        "addEventSource",
        response.en_ejecucion_ejecutados
      );
      $("#calendar").fullCalendar("addEventSource", response.en_planeacion);
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
    success: function(response) {
      $("#calendar").fullCalendar("addEventSource", response);
      $("#calendar").fullCalendar("rerenderEvents");
    },
    complete: function() {
      $("#calendar").fadeIn();
      $("#calendar").removeClass("showNone");
      $("#loaderCalendar").fadeOut();

      $(
        "#calendarZona, #calendarMunicipio, #calendarEstrategia, #calendarTema, #calendarTipoG, #estadoPlaneacion"
      ).on("change", function() {
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
    success: function(response) {
      $("#calendarMunicipio").html(`<option value="0" selected>Todos</option>`);
      $("#calendarMunicipioConsulta").html(`<option value="0" selected>Todos</option>`);

      response.forEach(element => {
        $("#calendarMunicipio").append(
          `<option value="${element.municipio}">${element.municipio}</option>`
        );

        $("#calendarMunicipioConsulta").append(
          `<option value="${element.id_municipio}">${element.municipio}</option>`
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
    success: function(response) {
      $("#calendarEstrategia, #calendarEstrategiaConsulta").html(
        `<option value="0" selected>Todos</option>`
      );
      $("#calendarEstrategiaConsulta").html(
        `<option value="0" selected>Todos</option>`
      );
      response.forEach(element => {
        $("#calendarEstrategia").append(
          `<option value="${element.nombre_estrategia}">${
            element.nombre_estrategia
          }</option>`
        );

        $("#calendarEstrategiaConsulta").append(
          `<option value="${element.id_estrategia}">${
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
    success: function(response) {
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
        success: function(response) {
          if (response.error == 0) {
            id = $("#modalEventsCalendar").val();
            $("#calendar").fullCalendar("removeEvents", id);
            $("#modalEventsCalendar").modal("hide");
            swalWithBootstrapButtons.fire(
              "Eliminado!",
              "Planeación eliminado con éxito",
              "success"
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
        success: function(response) {
          if (response.error == 0) {
            returnToEjecucion();

            $(`#editarejec_${id_plan}`).css("display", "none");
            $(`#eliminarejec_${id_plan}`).css("display", "none");
            swalWithBootstrapButtons.fire(
              "Eliminado!",
              "Ejecución eliminado con éxito",
              "success"
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
  $("#right").html("");
  var header = document.getElementById("modalEventHeader");
  var tacticos = $.map(event.tacticos, v => {
    return v;
  }).join(", ");

  header.style.cssText = `background-color: ${event.color} !important`;
  $("#modalEventsCalendar").val(event.id);

  if (event.adjunto == 0) {
    $("#modalEventTitle").html(`${event.title}`);
  } else {
    $("#modalEventTitle").html(
      `<i class="fas fa-paperclip" ></i> ${event.title}`
    );
  }

  $("#modalEventTitle").css("color", "white");

  if (event.color == "#a2a1a0" || event.color == "blue") {
    $("#modalEventDescription #left").html(event.description);
    $("#modalEventsCalendar>.modal-dialog").css("width", "45%");
  } else {
    $("#modalEventsCalendar>.modal-dialog").css("width", "75%");
    $("#left").html(
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

  if (event.color == "#269226" || event.color == "#edbe00") {
    $("#right").show();
    $("#left").css("width", "48%");

    var requisitos = $.map(event.requisitos, v => {
      return v;
    }).join(" ");

    $("#right").html(
      `<i class="${event.icon}" style="font-size: 3em;color: ${
        event.color
      };align-self: center;"></i>
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

    if (requisitos == "") {
      $("#requisitosPlan").hide();
    }
  } else {
    $("#right").hide();
    $("#left").css("width", "100%");
  }

  var ejec = "";
  if (event.valid_ejec) {
    ejec =
    `<button type="button" onclick="editarEjecucion(${event.id})" id="editarejec_${event.id}" class="btn btn-success edit edit_plan"><i class="fas fa-edit"></i> Ejecución</button>
      <button type="button" id="eliminarejec_${event.id}" onclick=eliminarEjecucion(${event.id},1) class="btn btn-danger edit eliminarEvent"><i class="fas fa-trash-alt"></i> Ejecución</button>
    `;

    if (event.tipo_gestion == 1) {
      $("#right").append(
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
        $("#totalBody").append(
          `<tr>
            <td>${element.tipo}</td>
            <td>${element.total}</td>
          </tr>`
        );
      });

      $("#totalBody").append(
        `<tr>
          <td>Total</td>
          <td>${sum}</td>
        </tr>`
      );
    }
  }

  $("#right").append(`<div id="adjuntos"></div>`);

  if (event.adjunto == 1) {
    if (event.tipo_gestion == 2) {
      $("#right").append(
        `<div id="adjuntos">
          <div>
            <i class="fas fa-folder" onclick="generateCarousel(1)"></i>
            <h3>Actas</h3>
          </div>
        </div>`
      );
    } else {
      if (event.evidencias != "") {
        $("#adjuntos").append(
          ` <div>
              <i class="fas fa-folder" onclick="generateCarousel(2)"></i>
              <h3>Evidencias</h3>
            </div>`
        );
      }

      if (event.asistencias != "") {
        $("#adjuntos").append(
          ` <div>
              <i class="fas fa-folder" onclick="generateCarousel(3)"></i>
              <h3>Asistencias</h3>
            </div>`
        );
      }
    }
  }

  if (event.color == "#a2a1a0" || event.color == "blue") {
    $("#modalEventsCalendar .modal-footer").html(
      `<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>`
    );
  } else {
    $("#modalEventsCalendar #footer-detail").html(
      `<div id="editar_borrar">
      <button type="button" class="btn btn-success"><i class="fas fa-edit edit edit_plan"></i> Planeación</button>
      <button type="button" onclick=eliminarPlaneacion(${event.id},0) class="btn btn-danger edit eliminarEvent"><i class="fas fa-trash-alt"></i> Planeación</button>
      ${ejec}
      </div>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>`
    );
  }

  // Remover los botones de eliminar planeacion/ejecucion si el usuario es gestor
  if (user == 3) {
    $('.eliminarEvent').remove();
  }else if (user == 4) {
  // Remover todos los botones de eliminar y editar si el usuario es invitado
    $('.edit').remove();
  }

  $("#modalEventsCalendar").modal();
}

function getTemasCalendar() {
  $.ajax({
    type: "POST",
    url: "server/getTemas.php",
    data: {
      comportamiento: ""
    },
    dataType: "json",
    success: function(response) {
      $("#calendarTema").html(`<option value="0" selected>Todos</option>`);
      $("#calendarTemaConsulta").html(`<option value="0" selected>Todos</option>`);
      response.forEach(element => {
        $("#calendarTema").append(
          `<option value="${element.temas}">${element.temas}</option>`
        );

        $("#calendarTemaConsulta").append(
          `<option value="${element.id_temas}">${element.temas}</option>`
        );
      });
    }
  });
}

function generateCarousel(type) {
  let is_img = true;
  let id = $("#modalEventsCalendar").val();
  let resource = $("#calendar").fullCalendar("clientEvents", id);

  if (type == 1) {
    let img = resource[0].actas;
    img.forEach(element => {
      if (
        element.split(".").pop().slice(0, 3) == "pdf"
      ) {
        $(".carousel-inner").html(
          `<iframe src="${element.substr(3)}" class="img-adjuntos" frameborder="0"
          width="655" height="550" marginheight="0" marginwidth="0" id="pdf">
          </iframe>`
        );
        is_img = false;
      } else {
        $(".carousel-inner").html(
          `<div class="carousel-item active">
            <img src="${element.substr(3)}" class="d-block w-100 img-adjuntos" alt="">
          </div>`
        );
      }
    });
  }

  if (type != 1) {
    $(".carousel-inner").html("");

    if (type == 2) {
      var i = 0;
      let img = resource[0].evidencias;

      img.forEach(element => {
        if (i <= 0) {
          $(".carousel-inner").append(
            `<div class="carousel-item active">
              <img src="${element.substr(3)}" class="d-block w-100 img-adjuntos" alt="">
            </div>`
          );
          i++;
        } else {
          $(".carousel-inner").append(
            `<div class="carousel-item">
              <img src="${element.substr(3)}" class="d-block w-100 img-adjuntos" alt="">
            </div>`
          );
        }
      });
    } else {
      let img = resource[0].asistencias;

      img.forEach(element => {
        if (
          element.split(".").pop().slice(0, 3) == "pdf"
        ) {
          $(".carousel-inner").html(
            `<iframe src="${element.substr(3)}" class="img-adjuntos" frameborder="0"
            width="655" height="550" marginheight="0" marginwidth="0" id="pdf">
            </iframe>`
          );
          is_img = false;
        } else {
          $(".carousel-inner").append(
            `<div class="carousel-item active">
              <img src="${element.substr(3)}" class="d-block w-100 img-adjuntos" alt="">
            </div>`
          );
        }
      });
    }
  }

  if (is_img) {
    $(".carousel-control-prev").show();
    $(".carousel-control-next").show();
  } else {
    $(".carousel-control-prev").hide();
    $(".carousel-control-next").hide();
  }

  showImages();
}

function showImages() {
  $("#right, #left").hide();
  $(".carousel").removeClass("showNone");
  widthCarousel();
  $("#footer-detail").hide();
  $("#footer-image").removeClass("showNone");
}

function removeImages() {
  $("#right, #left").show();
  $(".carousel").addClass("showNone");
  $("#footer-detail").show();
  $("#footer-image").addClass("showNone");
}

function returnToEjecucion() {
  var id = $("#modalEventsCalendar").val();
  var event = $("#calendar").fullCalendar("clientEvents", id);
  event[0].color = "#edbe00";
  event[0].icon = "fas fa-minus-circle";
  event[0].valid_ejec = false;
  event[0].requisitos.push("<li> Registrar la ejecución de la actividad </li>");
  $("#calendar").fullCalendar("updateEvent", event[0]);
  generateModal(event[0]);
}

function editarEjecucion(editar) {
  window.location.href = `editarEjecucion.html?id_zona=${id_zona}&id_plan=${editar}`
}

function generateDescargable() {
  $.ajax({
    type: "POST",
    url: "server/generateExcel.php",
    data: $('#generateExcelForm').serialize()
  }).done(function(data){
    dat = JSON.parse(data);
    window.open(`server/${dat.name}`, '_blank');
  });
}

function widthCarousel() {
  var body_width = $(".modal-body").width();
  var body_height = $(".modal-body").height();

  var imgs = document.getElementsByClassName("img-adjuntos");

  for (let i = 0; i < imgs.length; i++) {
    let element = imgs[i];
    element.style.cssText = `width: ${body_width}px !important; height: ${body_height}px !important`;
  }
}
