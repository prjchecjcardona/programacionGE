$(function() {
  var containerEl = $("#calendar");
  getPlaneacionesCalendar();
  containerEl.fullCalendar({
    themeSystem: 'bootstrap4',
    header: {
      left: "prev,next today",
      center: "title",
      right: "month,listWeek,agendaDay"
    },
    defaultDate: "2018-01-12",
    navLinks: true, // can click day/week names to navigate views
    editable: true,
    draggable: false,
    eventLimit: true, // allow "more" link when too many events
  });

  //Adds showNone to calendar class when created. 
  var calendar = document.getElementById('calendar');
  calendar.className += " showNone";

});

function getPlaneacionesCalendar(){
  $.ajax({
    type: "POST",
    url: "server/getPlaneacionesCalendar.php",
    data: "",
    dataType: "json",
    success: function (data) {
      $('#calendar').fullCalendar('removeEvents');
      $('#calendar').fullCalendar('addEventSource', data);
      $('#calendar').fullCalendar('rerenderEvents' );
    },
    complete: function(){
      $('#calendar').fadeIn();
      $('#calendar').removeClass('showNone')
      $('#loaderCalendar').fadeOut();
    }
  });
}