$(function(){

    $('#calendar').fullCalendar({
        events: {
<<<<<<< HEAD
            url: 'http://localhost/gestioneducativa/server/testEvents.php',
=======
            url: 'http://localhost/programacionge/server/testEvents.php',
>>>>>>> ba1883ad96c6cc1da812cdce73d2ff872dbb9397
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
