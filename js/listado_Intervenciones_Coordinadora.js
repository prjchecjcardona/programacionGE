$(function () {

    traerNombre();

    var table = $('#detalle_intervencion_CHEC').DataTable({
        ajax: {
            url: "php/listado_Intervenciones.php?idZona=" + getZona(),
            dataSrc: "content"
        },
        columnDefs: [{
            "targets": -1,
            "data": "id_intervenciones",
            "render": function (data, type, row, meta) {
                return '<a href="detalle_Intervencion_Coordinadora.html?idIntervencion=' + data + '"><i class="fa fa-arrow-right"></i></a>';
            },
            "className": "dt-body-center"
        }],
        columns: [{
                data: 'municipio'
            },
            {
                data: 'comportamientos'
            },
            {
                data: 'tipo_intervencion'
            },
            {
                data: 'fecha'
            },
            null
        ],
        "language": {
            select: {
                rows: "%d Filas seleccionadas"
            },
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "select-item": "Seleccionando fila",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }

        },


        searching: true,
        paging: true,
        ordering: true,
        select: true,
        scrollY: 300,
        autofill: true

    });
    table.on('xhr', function (e, settings, json) {
        $('.loader').hide();
    });

});

function getZona() {
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

    return $.get('zona');
}

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
                window.location.href = "welcome_Coordinadora.html";
            }
        }, "json");

}

function listIntervenciones(idZona) {

    $.post("php/listado_Intervenciones.php", {
            idZona: idZona

        },
        function (data) {

            if (data.error != 1) {


            } else {
                swal(
                    'Error', //titulo
                    'Ocurrió un error, inténtalo de nuevo.',
                    'error'
                );
            }
        }, "json");
}