//evento para cambiar la funcion del boton iniciar ejecución de actividad a finalizar.

$('#iniciar_ejecucion').click(function () {

    $("#iniciar_ejecucion")
        .removeClass("btn-success")
        .addClass("btn-danger")
        .html('<i class="fa fa-map-marker" aria-hidden="true"></i> Finalizar Ejecución de Actividad');

});