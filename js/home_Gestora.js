$(function () {

    traerNombre();

    $.get = function (key) {
		key = key.replace(/[\[]/, '\\[');
		key = key.replace(/[\]]/, '\\]');
		var pattern = "[\\?&]" + key + "=([^&#]*)";
		var regex = new RegExp(pattern);
		var url = unescape(window.location.href);
		var results = regex.exec(url);
		if (results === null) {
			return null;
		} else {
			return results[1];
		}
	}
    
    interevensionesPorZona($.get('id_zona'));
   
    $('#calendar').fullCalendar({
        events: {
            url: 'http://localhost/gestioneducativa/server/testEvents.php',
            type: 'POST', // Send post data
            error: function (err) {
                alert('There was an error while fetching events.');
                console.log(err)
            }
        },
        eventClick: function (calEvent, jsEvent, view) {
            $('.modal-title').html(calEvent.title);
            $('#eventFecha').html(calEvent.start);
            $('#eventLugar').html(calEvent.lugar);
            $('#eventDescripcion').html(calEvent.descripcion);
            $('#eventModal').modal()
        }
    })
})

function traerNombre() {

    $.post("php/CapturaVariableSession.php", {
        accion: 'traerNombre'


    },
        function (data) {
            if (data != "") {
                $('#Nombre').html(data);
            } else {
                swal(
                    '', //titulo
                    'Debes iniciar sesion!',
                    'error'
                );
                window.location.href = "welcome_Gestora.html";
            }
        }, "json");

}

function interevensionesPorZona(id_zona) {
        console.log(id_zona);
        $.post("php/home_Gestora.php", {
                accion: 'interevensionesPorZona',
                id_zona: id_zona
            },
            function (data) {
                if (data.error != 1) {
    
                    $('.interv-container').append(data.html);
                }
                // else{
                // mostrarPopUpError(data.error);
                // }
    
    
            }, "json");
    }
    
    function mostrarDetalleIntervencion(idIntervencion) {
        window.location.href = "detalle_Intervencion_Gestora.html?idIntervencion=" + idIntervencion;
    }
    
    function agregarIntervencion(idZona) {
        window.location.href = "nueva_Intervencion_Gestora.html?idZona=" + idZona;
    }
