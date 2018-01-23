//Invocacion del archivo File Input Ejecucion Coordinadora
$(function () {
	traerNombre();
	initFileInput();
	cargarTipoCedula();

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

	idPlaneacion = $.get("idPlaneacion");
	idIntervencion = $.get("idIntervencion");
	isEjecutada = $.get("isEjecutada");
	nCumplimiento = "";
	cargarDatosPlaneacion();

	var table = $('#ejecucion_coordinadora_asistencia').DataTable({
		data: arrayAsistentes,
		columns: [{
				data: "numero_documento",
				title: "Documento"
			},
			{
				data: "nombres",
				title: "Nombre"
			},
			{
				data: "apellidos",
				title: "Apellidos"
			},
			{
				data: "movil",
				title: "Móvil"
			}
		],
		"language": {
			"sProcessing": "Procesando...",
			"sLengthMenu": "Mostrar _MENU_ registros",
			"sZeroRecords": "No se encontraron resultados",
			"sEmptyTable": "Ningún dato disponible en esta tabla",
			"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
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
		scrollY: 200,
	});

	var dateToday = new Date();
	var dates = $("#textFecha").datepicker({
		defaultDate: "+1w",
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		numberOfMonths: 1
	});


	var dateToday = new Date();
	var dates = $("#FechainputNacimientoAsis").datepicker({
		defaultDate: "+1w",
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		numberOfMonths: 1,
		yearRange: '1900:2018'
	});



	bindEvents();
});


function bindEvents() {
	/*Dependiendo si seleccionan si cuenta con algun contacto
	 * parametro: 
	 */
	$('#radiosAlgunContacto input:radio').click(function () {

		//si contacto 
		if ($(this).val() == 'siContacto') {

			contacto = $(this).val();
		} else {
			contacto = $(this).val();

		}


	});

	/*el detalle cumplimiento
	 * parametro: 
	 */
	$('#detalleNivelCumplimiento input:radio').click(function () {


	});


	$('#btnnueva_asistencia_coordinadora').click(function () {
		$("#ex5").modal({
			fadeDuration: 500,
			fadeDelay: 0.50,
			escapeClose: false,
			clickClose: false,
			showClose: false
		});
	})

	$('#buttonEnviar').click(function () {
		guardarAsistencia();
	})
}

//Invocacion del archivo File Input para ejecucion Coordinadora
function initFileInput() {
	$('#file_fotograficas').fileinput({
		language: 'es',
		'theme': 'fa',
		uploadUrl: '#',
		allowedFileExtensions: ['jpg', 'png', 'gif', 'pdf', 'doc', 'docx',
			'xlsx', 'xls', 'ppt', 'pptx', 'mp4', 'avi', 'mov', 'mpeg4'
		]
	});
	$('#file_asistencias').fileinput({
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

			}
		}, "json");

}


function cargarDatosPlaneacion() {
	//TODO PENDIENTE LLAMADO A BACKEND DE YA EJECUTADAS
	if (isEjecutada == 1) {
		$.post("php/ejecucion_Coordinadora.php", {
				accion: 'cargarDatosPlaneacion',
				idPlaneacion: idPlaneacion,
				isEjecutada: true
			},
			function (data) {
				if (data.error == 0) {

					$('#fechaInd').html(data.html.fecha);
					$('#lugarInd').html(data.html.lugar);
					$('#municipioInd').html(data.html.municipio);
					$('#comportamientoInd').html(data.html.comportamiento);
					$('#competenciaInd').html(data.html.competencia);
					$('#estrategiaInd').html(data.html.estrategia);
					$('#tacticoInd').html(data.html.tactico);
					$('#indicadoresInd').html(data.html.indicador);

					//datos de la ejecucion registrada
					let valor = data.html.datosejec.horafinalizacion.split(':');
					$('#textFecha').html(dta.html.datosEjec.fecha);
					$('#selectbasicHoraEje').val(valor[0]);
					$('#selectbasicHoraEje').val(valor[0]);

				} else {
					swal(
						'', //titulo
						'Debes iniciar sesion!',
						'error'
					);

				}
				$('.loader').hide();
			}, "json");

	} else {
		$.post("php/ejecucion_Coordinadora.php", {
				accion: 'cargarDatosPlaneacion',
				idPlaneacion: idPlaneacion,
				isEjecutada: false
			},
			function (data) {
				if (data.error == 0) {

					$('#fechaInd').html(data.html.fecha);
					$('#lugarInd').html(data.html.lugar);
					$('#municipioInd').html(data.html.municipio);
					$('#comportamientoInd').html(data.html.comportamiento);
					$('#competenciaInd').html(data.html.competencia);
					$('#estrategiaInd').html(data.html.estrategia);
					$('#tacticoInd').html(data.html.tactico);
					$('#indicadoresInd').html(data.html.indicador);
				} else {
					swal(
						'', //titulo
						'Debes iniciar sesion!',
						'error'
					);

				}
				$('.loader').hide();
			}, "json");
	}


}

function guardarEjecucion() {

	if (!validarInformacion()) {
		swal(
			'', //titulo
			'Debes ingresar todos los datos!',
			'error'
		);
	} else {

		//detalleNivelCumplimiento
		var list = new Array();

		$.each($('#detalleNivelCumplimiento :checked'), function () {

			list.push($(this).val());

		});

		//fin capturar detalle nivel cumplimiento


		//Verificar si el registro de ejecución no tiene asistentes.
		if(arrayAsistentes.length==0){
			arrayAsistentes = ["1"];
		}



		$.post("php/ejecucion_Coordinadora.php", {
				accion: 'guardarEjecucion',
				fecha: $('#textFecha').val(),
				hora: $('#selectbasicHoraEje').val() + ":" + $('#selectbasicMinEje').val(),
				asistentes: $('#textinputAsisNum').val(),
				detalleCumplimiento: list,
				nCumplimiento: $('input:radio[name=nCumplimiento]:checked').val(),
				observaciones: $('#textareaObservaciones').val(),
				idPlaneacion: idPlaneacion,
				arrayAsistentes: arrayAsistentes
			},
			function (data) {
				if (data.error == 1) {

					swal(
						'', //titulo
						' No se guardo la ejecución, intententalo nuevamente',
						'error'
					);

				} else {
					swal(
						'', //titulo
						'Guardado Correctamente',
						'success'
					).then(function(){
						window.location.href = "detalle_Intervencion_Coordinadora.html?idIntervencion=" + idIntervencion;
					});

				}

			}, "json");
	}
}

function validarInformacion() {
	var valido = true;
	//radio
	cont = 0;
	$("#detalleNivelCumplimiento input:radio[name^=detalle_]:checked").each(function (e) {

		cont++;
	});
	if (cont == 4) {

	} else {
		valido = false;
	}

	$("input[id^=text]").each(function (e) {
		if ($(this).val() == "" && $(this).is(":visible")) { //alert("input"+$( this ).attr('id'));
			valido = false;
		}
	});

	return valido;
}


function cargarTipoCedula() {
	var url = "php/ejecucion_Coordinadora.php";
	$.post(url, {
		accion: 'cargarTipoCedula'
	}, function (data) {
		var arrayData = JSON.parse(data);
		arrayData.response.forEach(element => {
			$('#selectbasicTipoDocumento').append('<option value="' + element.id_tipo_documento + '">' + element.tipo_documento + '</option>');
		});
	});
}

var arrayAsistentes = [];

function guardarAsistencia() {

	if ($('#selectbasicTipoDocumento').val() == "" || $('#textinputDocumento').val() == "" ||
		$('#textinputNombres').val() == "" || $('#textinputApellidos').val() == "" ||
		$('#textinputRolAsis').val() == "" ||
		$('#FechainputNacimientoAsis').val() == "") {
		swal(
			'Error', //titulo
			'Debes diligenciar todos los campos',
			'error'
		);
	} else {
		var datos_formulario = {
			tipo_documento: $('#selectbasicTipoDocumento').val(),
			numero_documento: $('#textinputDocumento').val(),
			nombres: $('#textinputNombres').val(),
			apellidos: $('#textinputApellidos').val(),
			genero: $('input[name="radiosSexo"]:checked').val(),
			cuenta_CHEC: $('#textinputCuentaCHEC').val(),
			telefono: $('#textinputTelefonoAsis').val(),
			movil: $('#textinputMovilAsis').val(),
			direccion: $('#textinputDireccionAsis').val(),
			correo_electronico: $('#textinputCorreoAsis').val(),
			rol: $('#textinputRolAsis').val(),
			fecha_asistencia: $('#FechainputNacimientoAsis').val(),
			manejo_datos: $('input[name="radiosManejoDatos"]:checked').val(),
			sesiones: $('input[name="radiosSesionesForma"]:checked').val()
		};

		arrayAsistentes.push(datos_formulario);

		var table = $('#ejecucion_coordinadora_asistencia');
		table.dataTable().fnClearTable();
		table.dataTable().fnAddData(arrayAsistentes);

		swal({
			position: 'top-right',
			type: 'success',
			title: 'Información guardada',
			showConfirmButton: false,
			timer: 1500
		})


		$.modal.close();
		$('#selectbasicTipoDocumento').val("1");
		$('#textinputDocumento').val("");
		$('#textinputNombres').val("");
		$('#textinputApellidos').val("");
		$('#textinputCuentaCHEC').val("");
		$('#textinputTelefonoAsis').val("");
		$('#textinputMovilAsis').val("");
		$('#textinputDireccionAsis').val("");
		$('#textinputCorreoAsis').val("");
		$('#textinputRolAsis').val("");
		$('#FechainputNacimientoAsis').val("");


	}

}

/*Dependiendo si seleccionan si cuenta con algun contacto
 * parametro: 
 */
$('#radiosContacto input:radio').click(function () {

	if ($(this).val() === '1') {
		$("#preguntaContacto").show();
	} else {
		$("#preguntaContacto").hide();
	}


});

