$(function () {

    /*Extrae los parametros que llegan en la url
	 * parametro: 
	 */
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

    //evento para cambiar la funcion del boton iniciar ejecución de actividad a finalizar.

    $('#iniciar_ejecucion').click(function () {

        $("#iniciar_ejecucion")
            .removeClass("btn-success")
            .addClass("btn-danger")
            .html('<i class="fa fa-map-marker" aria-hidden="true"></i> Finalizar Ejecución de Actividad');

    });

    idPlaneacion = $.get('id_planeacion');
    


});


