$(function () {

  getMunicipiosXZ($('#calendarZona').val());
  getEstrategiasCalendar();
  getTemasCalendar();

  var containerEl = $(".myCalendar");
  getPlaneacionesCalendar();
  containerEl.fullCalendar({
    themeSystem: "bootstrap4",
    customButtons: {
      detalles: {
        text: "Indicador Calendario",
        click: function () {
          $("#modalCalendarDetails").modal("toggle");
        }
      },
      filtros: {
        text: "Filtros",
        click: () => {
          $('#modalCalendarFilters').modal("toggle");
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
    editable: true,
    draggable: false,
    eventLimit: true, // allow "more" link when too many events

    eventRender: function eventRender(event, element, view) {
      return (["0", String(event.id_zona)].indexOf($("#calendarZona").val()) >= 0) &&
        (["0", String(event.municipio)].indexOf($('#calendarMunicipio').val()) >= 0) &&
        (["0", String(event.tema)].indexOf($('#calendarTema').val()) >= 0) &&
        (["0", String(event.estrategia)].indexOf($('#calendarEstrategia').val()) >= 0) &&
        (["0", String(event.tipo_gestion)].indexOf($('#calendarTipoG').val()) >= 0)
    },
    eventClick: function (event, jsEvent, view) {
      document.getElementById(
        "modalEventHeader"
      ).style.cssText = `background-color: ${event.color} !important`;
      setModal(event.color)
      $("#modalEventTitle").html(event.title);
      $("#modalEventTitle").css('color', 'white');
      $("#modalEventDescription #left").html(event.description);
      $("#modalEventDescription #right").html(event.estado);
      $("#modalEventsCalendar").modal();
    }
  });

  //
  $(window).resize(function () {
    $('#calendar').fullCalendar('option', 'height', getCalendarHeight());
  });

  //Adds showNone to calendar class when created.
  var calendar = document.getElementById("calendar");
  calendar.className += " showNone";
});

$('#calendarZona').change(() => {
  getMunicipiosXZ($('#calendarZona').val());
});

/* PARAMETERS */
id_zona = getParam("id_zona");
user = getParam("user");

function getParam(param) {
  param = param.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + param + "=([^&#]*)");
  var results = regex.exec(location.search);
  return results === null ?
    "" :
    decodeURIComponent(results[1].replace(/\+/g, " "));
}

function getPlaneacionesCalendar() {
  $("#loaderCalendar").fadeIn();
  $.ajax({
    type: "POST",
    url: "server/getPlaneacionesCalendar.php",
    data: {
      planEjec_cal: "",
      id_zona: id_zona
    },
    dataType: "json",
    success: function (dataEjecutado) {
      $("#calendar").fullCalendar("addEventSource", dataEjecutado);

      $.ajax({
        type: "POST",
        url: "server/getPlaneacionesCalendar.php",
        data: {
          no_ejec: "",
          id_zona: id_zona
        },
        dataType: "json",
        success: function (dataNoEjecutado) {
          $("#calendar").fullCalendar("addEventSource", dataNoEjecutado);
          var fullArrayPlans = dataEjecutado;

          if (fullArrayPlans == "") {
            fullArrayPlans = "";
          }

          $.ajax({
            type: "POST",
            url: "server/getPlaneacionesCalendar.php",
            data: {
              plan_cal: fullArrayPlans,
              id_zona : id_zona
            },
            dataType: "json",
            success: function (dataPlans) {
              if (!dataPlans.error) {
                $("#calendar").fullCalendar("addEventSource", dataPlans);
              }
              getTrabajoAdministrativo();
            }
          });

          checkZonaFilterCalendar();
        }
      });
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

      $("#calendarZona, #calendarMunicipio, #calendarEstrategia, #calendarTema, #calendarTipoG").on("change", function () {
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
    var zonas = {
      1: "Centro",
      2: "Suroccidente",
      3: "Occidente",
      4: "Noroccidente",
      5: "Oriente"
    }

    $('#calendarZona').val(zonas[id_zona]);
    $("#calendar").fullCalendar("rerenderEvents");
  }
}

function setModal(color) {
  if (color != '#edbe00' && color != '#269226') {
    if (!$("#modalEventDescription #left").hasClass('planeado')) {
      $("#modalEventDescription #left").addClass('planeado');
      $("#modalEventDescription #right").addClass('showNone');
    }
  } else {
    if ($("#modalEventDescription #left").hasClass('planeado')) {
      $("#modalEventDescription #left").removeClass('planeado');
      $("#modalEventDescription #right").removeClass('showNone');
    }
  }
}

function getMunicipiosXZ(zona) {
  $('#calendarMunicipio').prop("disabled", true);
  $.ajax({
    type: "POST",
    url: "server/getMunicipios.php",
    data: {
      zona: zona
    },
    dataType: "json",
    success: function (response) {
      $('#calendarMunicipio').html(
        `<option value="0" selected>Todos</option>`
      );
      response.forEach(element => {
        $('#calendarMunicipio').append(
          `<option value="${element.municipio}">${element.municipio}</option>`
        );
      });
    }
  }).done(() => {
    $('#calendarMunicipio').prop("disabled", false);
  });
}

function getEstrategiasCalendar() {
  $.ajax({
    type: "POST",
    url: "server/getEstrategias.php",
    data: '',
    dataType: "json",
    success: function (response) {
      $('#calendarEstrategia').html(
        `<option value="0" selected>Todos</option>`
      );
      response.forEach(element => {
        $('#calendarEstrategia').append(
          `<option value="${element.nombre_estrategia}">${element.nombre_estrategia}</option>`
        );
      });
    }
  });
}

function getTacticosXEstrategias(estrat) {
  $.ajax({
    type: "POST",
    url: "server/getEstrategias.php",
    data: '',
    dataType: "json",
    success: function (response) {
      $('#calendarEstrategia').html(
        `<option value="0" selected>Todos</option>`
      );
      response.forEach(element => {
        $('#calendarEstrategia').append(
          `<option value="${element.nombre_estrategia}">${element.nombre_estrategia}</option>`
        );
      });
    }
  });
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
      $('#calendarTema').html(
        `<option value="0" selected>Todos</option>`
      );
      response.forEach(element => {
        $('#calendarTema').append(
          `<option value="${element.temas}">${element.temas}</option>`
        );
      });
    }
  });
}