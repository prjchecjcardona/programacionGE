$(function () {
	traerNombre();
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
	idEjecucion = -1;
	idZona = $.get("id_zona");
	cargarIdEjecucion();
	arrayAsistentes = [];
	var table = $('#ejecucion_coordinadora_asistencia').DataTable({
		data: arrayAsistentes,
		columns: [
			{
				data: "nombres",
				title: "Nombre"
			},
			{
				data: "apellidos",
				title: "Apellidos"
			},
			{
				data: "celular",
				title: "Móvil"
			},
			{
				data: "correoelectronico",
				title: "Correo Electrónico"
			},
			{
				data: "rol",
				title: "Rol"
			},
			{
				data: "fecha_nacimiento",
				title: "Edad"
			},
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

	bindEvents();
});


function bindEvents() {

	$('body').keypress(function (event) {
		if (event.keyCode === 10 || event.keyCode === 13)
			event.preventDefault();
	});

	$('#buttonEnviar').click(function () {
		guardarAsistencia();
	})

	$('#buttonCancelar').click(function(){
		
		window.location.href = "detalle_Intervencion_Gestora.html?idIntervencion=" + idIntervencion +"&idZona="+idZona;
	})
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


function cargarIdEjecucion() {

	$.post("php/registro_Asistencia.php", {
		accion: 'cargarIdEjecucion',
		idPlaneacion: idPlaneacion
	},
		function (data) {
			if (data.error == 0) {
				idEjecucion = data.html;
				cargarAsistenciaRegistrada(idEjecucion);
			} else {
				$('.loader').hide();
				swal(
					'', //titulo
					'Debes iniciar sesion!',
					'error'
				);

			}
		}, "json");
}

//TODO finalizar
function cargarAsistenciaRegistrada(idEjecucion) {

	$.post("php/registro_Asistencia.php", {
		accion: 'cargarAsistenciaRegistrada',
		idEjecucion: idEjecucion
	},
		function (data) {
			if (data.error == 0) {
				asistentes = data.html;
				//actualizar la tabla con los registros obtenidos de BD
				if(data.html){
					var table = $('#ejecucion_coordinadora_asistencia');
					table.dataTable().fnClearTable();
					table.dataTable().fnAddData(asistentes);
				}
				$("html, body").animate({ scrollTop: 0 }, "slow");
				clearFields();
				$('.loader').hide();
			} else {
				$('.loader').hide();
				swal(
					'', //titulo
					'Debes iniciar sesion!',
					'error'
				);

			}
			$('.loader').hide();
		}, "json");
}


function validarInformacion() {
	var valido = true;


	$(".obligatorio").each(function (e) {
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


function guardarAsistencia() {

	if (validarInformacion()) {
		$('.loader').show();
	
		var datos_formulario = {
			nombres: $('#textinputNombres').val(),
			apellidos: $('#textinputApellidos').val(),
			tipo_documento: $('#selectbasicTipoDocumento').val(),
			numero_documento: $('#inputDocumento').val(),
			genero: $('input[name="radiosSexo"]:checked').val(),
			cuenta_CHEC: $('#textinputCuentaCHEC').val(),
			telefono: $('#textinputTelefonoAsis').val(),
			movil: $('#textinputMovilAsis').val(),
			direccion: $('#textinputDireccionAsis').val(),
			correo_electronico: $('#textinputCorreoAsis').val(),
			rol: $('#textinputRolAsis').val(),
			edad: $('#FechainputNacimientoAsis').val(),
			manejo_datos: $('input[name="radiosManejoDatos"]:checked').val(),
			sesiones: $('input[name="radiosSesionesForma"]:checked').val()
		};

		if(datos_formulario.cuenta_CHEC==""){
			datos_formulario.cuenta_CHEC = -1;
		}
		if(datos_formulario.edad==""){
			datos_formulario.edad = -1;
		}
	
		var url = "php/registro_Asistencia.php";
		$.post(url, {
			accion: 'guardarAsistente',
			datos: datos_formulario,
			idEjecucion: idEjecucion
		}, function (response) {
			if(response.error!=1){
				cargarAsistenciaRegistrada(idEjecucion);
			}else{
				swal(
					'', //titulo
					'Error en el registro, inténtalo de nuevo',
					'error'
				);
			}
		}, 'json');
	} else { 
		swal(
			'', //titulo
			'Debes ingresar todos los datos',
			'error'
		);
		
	}

}

function navegar_home(){
	idZona = $.get("id_zona")
	window.location.href = "home_Gestora.html?id_zona=" + idZona;
}
function clearFields() {
	$('#textinputNombres').val("");
	$('#textinputApellidos').val("");
	$('#selectbasicTipoDocumento').val("");
	$('#inputDocumento').val("");
	$('#textinputCuentaCHEC').val("");
	$('#textinputTelefonoAsis').val("");
	$('#textinputMovilAsis').val("");
	$('#textinputDireccionAsis').val("");
	$('#textinputCorreoAsis').val("");
	$('#textinputRolAsis').val("");
	$('#FechainputNacimientoAsis').val("");
}


