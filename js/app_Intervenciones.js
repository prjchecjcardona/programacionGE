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

    intervencionesPorZona($.get('id_zona'));

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
                window.location.href = "app_Login.html";
            }
        }, "json");

}

function intervencionesPorZona(id_zona) {

    $.post("php/home_Gestora.php", {
        accion: 'app_intervencionesPorZona',
        id_zona: id_zona
    },
        function (data) {
            if (data.error != 1) {
                data.html.forEach((element, index) => {
                    $('#accordion').append(`
                    <div class="card">
                        <div class="card-header" role="tab" id="addIntervencion3">
                            <h5 class="mb-0">
                                <a data-toggle="collapse" href="#collapseIntervencion${index}" aria-expanded="true" aria-controls="collapseIntervencion3">
                                    <i class="fa fa-pencil-square-o" aria-hidden="false"></i>
                                    ${element.municipio} - ${getIniciales(element.comportamientos)}
                                </a>
                            </h5>
                        </div>
        
                        <!-- Formulario Nivel de Intervencion -->
                        <div id="collapseIntervencion${index}" class="collapse" role="tabpanel" aria-labelledby="addIntervencion${index}" data-parent="#accordion">
                            <div class="card-body">
        
                                <!-- Campo Nivel de Intervencion -->
                                <div class="col-md-12">
                                    
                                        <span>Tipo de Intervención: </span>
                                        <span class="grisTexto"> ${element.tipo_intervencion}</span>
                                        <br>
                                        <span>Comportamiento: </span>
                                        <span class="grisTexto"> ${element.comportamientos}</span>
                                        <br>
                                        <span>Competencia: </span>
                                        <span class="grisTexto"> ${element.competencia}</span>
                                        <br>
        
                                </div>
                                <br>
                                <span>Selecciona la actividad a ejecutar</span>
                                <!-- Boton Nivel de Intervencion -->
                                <div id="but-cont${index}" class="botonContenedor espaciado">
                                    

                                </div>
                            </div>
                        </div>
                    </div>
                    
                    `);

                    element.planeaciones.forEach(plan => {
                        $('#but-cont'+index).append(`
                        <button type="button" class="btn btn-success btn-lg btn-block" onclick="window.location.href='app_Planeaciones.html?id_planeacion=${plan.id_planeacion}'">
                            <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                            ${plan.fecha} - ${plan.jornada}
                        </button>
                        `);
                    });
                });
            }else{
                swal(
					'Error',
					'No se cargaron los datos intentalo de nuevo',
					'error'
				)
            }


        }, "json");
}

function mostrarDetalleIntervencion(idIntervencion) {
    window.location.href = "detalle_Intervencion_Gestora.html?idIntervencion=" + idIntervencion;
}

function agregarIntervencion(idZona) {
    window.location.href = "nueva_Intervencion_Gestora.html?idZona=" + idZona;
}

function getIniciales(palabra){
    switch (palabra) {
        case 'Uso responsable de energía':
            return 'U.R.E.';
            break;
        case 'Disfrute del servicio de energía como aporte a la calidad de vida':
            return 'D.S.E.A.C.V';
            break;
        case 'Uso de canales vanguardistas':
            return 'U.C.V.';
        break;
        case 'Cultura de pago':
            return 'C.P.';
        break;
    
        default:
            break;
    }
}