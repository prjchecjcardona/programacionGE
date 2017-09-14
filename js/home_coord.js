$(function(){

    $('#calendar').fullCalendar({
        events: {
            url: 'http://localhost/programacion_GE/server/testEvents.php',
            type: 'POST', // Send post data
            error: function(err) {
                alert('There was an error while fetching events.');
                console.log(err)
            }
        },
        eventClick: function(calEvent, jsEvent, view){
            $('.modal-title').html(calEvent.title);
            $('#eventFecha').html(calEvent.start);
            $('#eventLugar').html(calEvent.lugar);
            $('#eventDescripcion').html(calEvent.descripcion);
            $('#eventModal').modal()
        }
    })
})