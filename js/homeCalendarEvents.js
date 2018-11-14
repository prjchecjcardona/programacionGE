$(function() {
  var containerEl = $("#calendar");
  getPlaneacionesCalendar();
  containerEl.fullCalendar({
    themeSystem: "bootstrap4",
    header: {
      left: "prev,next, today",
      center: "title",
      right: "month,listWeek,agendaDay"
    },
    defaultDate: "2018-01-12",
    navLinks: true, // can click day/week names to navigate views
    editable: true,
    draggable: false,
    eventLimit: true, // allow "more" link when too many events

    eventRender: function eventRender( event, element, view ) {
      return ['all', event.school].indexOf($('#school_selector').val()) >= 0
    }
  });

  //Adds showNone to calendar class when created.
  var calendar = document.getElementById("calendar");
  calendar.className += " showNone";
});

function getPlaneacionesCalendar() {
  $.ajax({
    type: "POST",
    url: "server/getPlaneacionesCalendar.php",
    data: "",
    dataType: "json",
    success: function(data) {
      $("#calendar").fullCalendar("removeEvents");
      $("#calendar").fullCalendar("addEventSource", data);
      getTrabajoAdministrativo();
    },
    complete: function() {
      /* Append filter for calendar to choose specific zones */
      $(".fc-left").append(
        `<select class="custom-select" id="calendarSelect">
          <option selected>Seleccione ZONA</option>
          <option value="1">Centro</option>
          <option value="2">Suroccidente</option>
          <option value="3">Occidente</option>
          <option value="4">Noroccidente</option>
          <option value="5">Oriente</option>
        </select>`
      );
    }
  });
}

function getTrabajoAdministrativo(){
  $.ajax({
    type: "POST",
    url: "server/getTAdministrativos.php",
    data: "",
    dataType: "json",
    success: function (response) {
      $("#calendar").fullCalendar("addEventSource", response);
      $("#calendar").fullCalendar("rerenderEvents");
    },
    complete: function(){
      $("#calendar").fadeIn();
      $("#calendar").removeClass("showNone");
      $("#loaderCalendar").fadeOut();
    }
  });
}
