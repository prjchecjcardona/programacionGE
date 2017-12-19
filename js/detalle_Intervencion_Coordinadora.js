$(function () {
	traerNombre();
	initFileInput();

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


	idIntervencion = $.get("idIntervencion");
	comportamientos = "";
	competencia = "";
	idComportamientos = "";
	idCompetencia = "";
	idEntidad = "";
	cargarDetalleIntervencion(idIntervencion);
	cargarPlaneacionesPorIntrevencion(idIntervencion);

});

//Invocacion del archivo File Input para nueva intervencion coordinadora
function initFileInput() {
	$('.upload_files_input').fileinput({
		language: 'es',
		'theme': 'fa',
		uploadUrl: '#',
		allowedFileExtensions: ['jpg', 'png', 'gif', 'pdf', 'doc', 'docx',
			'xlsx', 'xls', 'ppt', 'pptx', 'mp4', 'avi', 'mov', 'mpeg4'
		]
	});
}


/*Consulta el nombre de la persona que inicio sesión
 * parametro: 
 */
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

/*Consulta el detalle de la intervención  
 * parametro: idIntervencion
 */
function cargarDetalleIntervencion(idIntervencion) {

	$.post("php/detalle_Intervencion_Coordindora.php", {
			accion: 'cargarDetalleIntervencion',
			idIntervencion: idIntervencion

		},
		function (data) {
			if (data.error == 0) {
				comportamientos = data.html.comportamientos;
				competencia = data.html.competencia;
				idComportamientos = data.html.id_comportamientos
				idCompetencia = data.html.id_competencia
				idEntidad = data.html.id_entidad

				$('#lblMunicipio').html("<b>Municipio</b>: " + data.html.municipio);
				$('#lblEntidad').html("<b>Entidad</b>: " + data.html.nombreentidad);
				$('#lblCoportamiento').html("<b>Comportamiento</b>: " + data.html.comportamientos);
				$('#lblCompetencia').html("<b>Competencia</b>: " + data.html.competencia);
				$('#lblTipoIntervencion').html("<b>Tipo Intervención</b>: " + data.html.tipo_intervencion);
				$("#indicadoresChec").html("<b>Indicadores</b>: ");
				$("#indicadoresChec").append(data.html.indicador);

			} else {
				swal(
					'', //titulo
					'No se cargo la información',
					'error'
				);
			}



		}, "json");
}

/*Consulta las planeaciones por intervencion
* parametro: idIntervencion
*/
function cargarPlaneacionesPorIntrevencion(idIntervencion){

	$.post("php/detalle_Intervencion_Coordindora.php",{
         accion : 'cargarPlaneacionesPorIntrevencion',
		 idIntervencion:idIntervencion
              				
         },
          function (data) {
						if(data.error != 1){
								$.post("php/detalle_Intervencion_Coordindora.php",{
									accion: 'checkPlaneacionesEjecutadas'
								},
								function (ejecutadas){
									cargarInformacionEnTabla(data);
									setTimeout(() => {
										identificarEjecutadas(data, JSON.parse(ejecutadas));
									}, 1000);
									
								})
								
							}		
				},"json");
}

/*Carga la respuesta de la base de datos en el datatable
* parametro: data
*/
 function cargarInformacionEnTabla(data){ //alert(data);
        table = $('#coordinadora_tabla').DataTable({
            "data": data,
            columns: [
			{ title: "Id", className: "idColEjec" },
			{ title: "Etapa" },
			{ title: "Estrategia" },
			{ title: "Táctico" },
			{ title: "Tema" },
			{ title: "Fecha" },
			{ title: "Registrar Ejecución", data: null, className: "dt-center", defaultContent: '<a href="#" id="ejecucion" class="btn btn-sm btn-success" alt="Ejecución"><span class="fa fa-book"></span></a>' },
			{ title: "Registrar Evaluación", data: null, className: "dt-center", defaultContent: '<a href="evaluacion_Coordinadora.html" id="evaluacion" class="btn btn-sm btn-success" alt="Ejecución"><span class="fa fa-book"></span></a>' }
			],
            "paging":   true,
            "info":     false,
            "columnDefs": [
			{"className": "dt-left", "targets": "_all"}, //alinear texto a la izquierda
			{"className": "idColEjec", "targets":  0},
			{ "width": "13%", "targets": 1 }//se le da ancho al td de estudiante
			//{ "width": "8%", "targets": 8 }, //se le da ancho al td de total horas
			//{ "width": "8%", "targets": 9 } //se le da ancho al td de observacion
			],
            "scrollY": "300px",
            "scrollX": true,
            "scrollCollapse": true,
            "language": {
				"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
                "sSearch": "Filtrar:",
                "zeroRecords": "Ningún resultado encontrado",
                "infoEmpty": "No hay registros disponibles",
                "Search:": "Filtrar"
            }
		});	
	}
	
	function identificarEjecutadas(data, ejecutadas){
		var isEjecutada;
		data.forEach(function(element, index) {
			isEjecutada = false;
			ejecutadas.forEach(ej =>{
				if(element[0]==ej){
					isEjecutada = true;
				}
			})
			if(isEjecutada){
				$($('#coordinadora_tabla tbody').children()[index]).addClass('rowEjecutada');
				$($('#coordinadora_tabla tbody').children()[index]).find('span').removeClass('fa-book');
				$($('#coordinadora_tabla tbody').children()[index]).find('span').addClass('fa-check');
			}
		});
	}
	
//Evento para ver detalle ejecucion//
	$(document).on('click', '#ejecucion', function() {  
			var data = table.row($(this).parents('tr')).data();
			var iconEjec = $(this).find('span').attr('class')
			idPlaneacion= data[0];
			if(data[0]!=""){
				if(iconEjec !== "fa fa-check"){
					window.location.href = "ejecucion_Coordinadora.html?idPlaneacion="+idPlaneacion+"&idIntervencion="+idIntervencion;
				}/* else{
					window.location.href = "ejecucion_Coordinadora.html?isEjecutada=1&idPlaneacion="+idPlaneacion+"&idIntervencion="+idIntervencion;
				} */
				
			}
	});






$("#btnNuevaPlaneacion").click(function () {

	// window.location.href = "planeacion_Coordinadora.html?idIntervencion="+idIntervencion; 

	window.location.href = "planeacion_Coordinadora.html?idIntervencion=" + idIntervencion + "&comportamientos=" + comportamientos + "&competencia=" + competencia + "&idComportamientos=" + idComportamientos + "&idCompetencia=" + idCompetencia + "&idEntidad=" + idEntidad;

});

