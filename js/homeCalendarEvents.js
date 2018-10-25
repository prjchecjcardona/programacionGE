$(function() {
  var containerEl = $("#calendar");

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
    eventLimit: true, // allow "more" link when too many events
    eventColor: 'red',
    events: [
      {
        title: "All Day Event",
        start: "2018-01-01",
        color: '#378006'
      },
      {
        title: "Long Event",
        start: "2018-01-07",
        end: "2018-01-10"
      },
      {
        id: 999,
        title: "Repeating Event",
        start: "2018-01-09T16:00:00",
        resourceEditable: false
      },
      {
        id: 999,
        title: "Repeating Event",
        start: "2018-01-16T16:00:00"
      },
      {
        title: "Conference",
        start: "2018-01-11",
        end: "2018-01-13",
        color: 'green'
      },
      {
        title: "Meeting",
        start: "2018-01-12T10:30:00",
        end: "2018-01-12T12:30:00",
        color: '#ffc900'
      },
      {
        title: "Lunch",
        start: "2018-01-12T12:00:00",
        color: 'rgb(20%,60%,0%)'
      },
      {
        title: "Meeting",
        start: "2018-01-12T14:30:00",
        color: '#99CC00'
      },
      {
        title: "Happy Hour",
        start: "2018-01-12T17:30:00",
        color: '#ffc900'
      },
      {
        title: "Dinner",
        start: "2018-01-12T20:00:00"
      },
      {
        title: "Birthday Party",
        start: "2018-01-13T07:00:00",
        color: '#ffc900'
      },
      {
        title: "Click for Google",
        url: "http://google.com/",
        start: "2018-01-28"
      }
    ]
  });
});