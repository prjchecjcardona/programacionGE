$(document).ready(function () {
	$("#Rural").show();
	$("#Urbano").hide();
	traerNombre();
	nuevaentidad = "";




	/*Dependiendo si seleccionan si cuenta con algun contacto
	 * parametro: 
	 */
	$('#UrbanoRural input:radio').click(function () {
		ubicacion = $(this).val();
		//rural
		if ($(this).val() == '1') {
			$("#Rural").show();
			$("#Urbano").hide();

			//se llama a la funcion que llena el combo de veredas
			cargarComunas_VeredaPorIdMunicipio($(this).val());
		} else {
			$("#Rural").hide();
			$("#Urbano").show();
			cargarComunas_VeredaPorIdMunicipio($(this).val());

		}


	});

	$('#btnNuevaEntidad').click(function () {
		$("#ex1").modal({
			fadeDuration: 500,
			fadeDelay: 0.50,
			escapeClose: false,
			clickClose: false,
			showClose: false
		});
		$('.imagenGestora').removeClass('imagenGestora')
	})



	$('#btnNuevaVereda').click(function () {
		$("#ex2").modal({
			fadeDuration: 500,
			fadeDelay: 0.50,
			escapeClose: false,
			clickClose: false,
			showClose: false
		});
		$('.imagenGestora').removeClass('imagenGestora')

	})

	$('#btnNuevoBarrio').click(function () {
		$("#ex3").modal({
			fadeDuration: 500,
			fadeDelay: 0.50,
			escapeClose: false,
			clickClose: false,
			showClose: false
		});
		$('.imagenGestora').removeClass('imagenGestora')
	})

	$('#btnNuevaComuna').click(function () {
		$("#ex4").modal({
			fadeDuration: 500,
			fadeDelay: 0.50,
			escapeClose: false,
			clickClose: false,
			showClose: false
		});
		$('.imagenGestora').removeClass('imagenGestora')
	})

	$('.regresarImgGestora').click(function () {
		$('.regresoImgGestora').addClass('imagenGestora')
	})

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


	idZona = $.get("idZona");
	initFileInput();
	cargarZonasPorId(idZona);
	cargarPorMunicipiosPorIdZona(idZona);
	cargarTipoIntervencion();
	cargarTipoEntidad();
	cargarComportamientos();




});

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

function cargarZonasPorId(idZona) {

	$.post("php/nueva_Intervencion_Coordinadora.php", {
			accion: 'cargarZonasPorId',
			idZona: idZona
		},
		function (data) {
			if (data.error != 1) {

				$('#nombreZona').html(data.html);
			}
			// else{
			// mostrarPopUpError(data.error);
			// }


		}, "json");
}

function cargarPorMunicipiosPorIdZona(idZona) {

	$.post("php/nueva_Intervencion_Coordinadora.php", {
			accion: 'cargarMunicipiosPorIdZona',
			idZona: idZona
		},
		function (data) {
			if (data.error != 1) {

				$('#selectbasicMunicipio').html(data.html);
			}
			// else{
			// mostrarPopUpError(data.error);
			// }


		}, "json");
}

$("#selectbasicMunicipio").change(function () {

	$('#UrbanoRural input:radio').trigger('click');

});

function cargarComunas_VeredaPorIdMunicipio(ubicacion) {

	$.post("php/nueva_Intervencion_Coordinadora.php", {
			accion: 'cargarComunasPorIdMunicipio',
			idMunicipio: $('#selectbasicMunicipio').val(),
			ubicacion: ubicacion
		},
		function (data) {
			if (data.error != 1) {

				if (ubicacion == 2) {
					$('#selectbasicComuna').html(data.html);
				} else {
					$('#selectbasicVereda').html(data.html);
				}
			}
		}, "json");

}

$("#selectbasicVereda").change(function () {

	$.post("php/nueva_Intervencion_Coordinadora.php", {
			accion: 'cargarEntidadPorVereda',
			idVereda: $('#selectbasicVereda').val()
		},
		function (data) {
			if (data.error != 1) {

				$('#selectbasicEntidad').html(data.html);
			}



		}, "json");

});

$("#selectbasicComuna").change(function () {

	$.post("php/nueva_Intervencion_Coordinadora.php", {
			accion: 'cargarBarriosPorComuna',
			idComuna: $('#selectbasicComuna').val()
		},
		function (data) {
			if (data.error != 1) {

				if (data.error != 1) {

					$('#selectbasicBarrio').html(data.html);
				}


			}



		}, "json");

});

$("#selectbasicBarrio").change(function () {

	$.post("php/nueva_Intervencion_Coordinadora.php", {
			accion: 'cargarEntidadesPorBarrio',
			idBarrio: $('#selectbasicBarrio').val()
		},
		function (data) {
			if (data.error != 1) {

				$('#selectbasicEntidad').html(data.html);
			}



		}, "json");

});


function cargarTipoIntervencion() {

	$.post("php/nueva_Intervencion_Coordinadora.php", {
			accion: 'cargarTipoIntervencion'

		},
		function (data) {
			if (data.error != 1) {

				$('#selectbasicTipoInvervencion').html(data.html);

			}
			// else{
			// mostrarPopUpError(data.error);
			// }


		}, "json");
}

function cargarTipoEntidad() {

	$.post("php/nueva_Intervencion_Coordinadora.php", {
			accion: 'cargarTipoEntidad'

		},
		function (data) {
			if (data.error != 1) {

				$('#selectbasicTipoEntidad').html(data.html);

			}

		}, "json");
}

function cargarComportamientos() {

	$.post("php/nueva_Intervencion_Coordinadora.php", {
			accion: 'cargarComportamientos'

		},
		function (data) {
			if (data.error != 1) {

				$('#selectbasicComportamiento').html(data.html);

			}
			// else{
			// mostrarPopUpError(data.error);
			// }


		}, "json");
}


$("#selectbasicComportamiento").change(function () {

	$.post("php/nueva_Intervencion_Coordinadora.php", {
			accion: 'cargarIndicadoresChec',
			idIndicador: $('#selectbasicComportamiento').val()
		},
		function (data) {
			if (data.error != 1) {

				$('#selectbasicIndicadores').html(data.html);

			}
			// else{
			// mostrarPopUpError(data.error);
			// }


		}, "json");

});

function guardarIntervencion() {

	if (!validarInformacion()) {


		swal(
			'', //titulo
			'Debes ingresar todos los datos!',
			'error'
		);
	} else {

		$('.regresoImgGestora').addClass('imagenGestora')


		//capturar los indicadores
		var list = new Array();

		$.each($('#selectbasicIndicadores :selected'), function () {

			list.push($(this).val());

		});

		//fin capturar los indicadores



		$.post("php/nueva_Intervencion_Coordinadora.php", {
				accion: 'guardarIntervencion',
				idZona: idZona,
				idEntidad: $('#selectbasicEntidad').val(),
				idTipoIntervencion: $('#selectbasicTipoInvervencion').val(),
				indicadores: list
			},
			function (data) {
				if (data.error == 1) {

					swal(
						'', //titulo
						' No se guardo la intervención, intententalo nuevamente',
						'error'
					);

				} else {
					swal(
						'', //titulo
						'Guardado Correctamente',
						'success'
					).then(function () {
						window.location.href = "detalle_Intervencion_Gestora.html?idIntervencion=" + data.idIntervencion;
					});
				}
			}, "json");
	}
}

function validarInformacion() {
	var valido = true;
	//select
	$("select[id^=selectbasic]").each(function (e) {
		if ($(this).val() == 0 && $(this).is(":visible")) { //alert("sel"+$( this ).attr('id'));
			valido = false;
		}
	});
	//input 
	// $("input[id^=textinput]").each(function(e){  ("input[id^=textinput][id!=id_requerido]").each(fuanction(e){
	$("input[id^=textinput]").each(function (e) {
		if ($(this).val() == "" && $(this).is(":visible")) { //alert("input"+$( this ).attr('id'));
			valido = false;
		}
	});

	return valido;
}

$("#buttonCancelar").click(function () {

	window.location.href = "home_Gestora.html?id_zona="+idZona;

});

//Invocacion del archivo File Input para nueva intervencion coordinadora
function initFileInput() {
	$('#upload_files_input').fileinput({
		language: 'es',
		'theme': 'fa',
		uploadUrl: '#',
		allowedFileExtensions: ['jpg', 'png', 'gif', 'pdf', 'doc', 'docx',
			'xlsx', 'xls', 'ppt', 'pptx', 'mp4', 'avi', 'mov', 'mpeg4'
		]
	});
}

function guardarNuevaComuna() {
	let nombreComuna = $('#textinputComuna').val();
	let municipio = $('#selectbasicMunicipio').val();
	let url = "php/nueva_Intervencion_Coordinadora.php";
	if (nombreComuna !== "") {
		$.post(url, {
				accion: 'guardarNuevaComuna',
				municipio: municipio,
				comuna: nombreComuna
			},
			function (data) {
				if (data.error == 1) {
					swal(
						'', //titulo
						' No se guardo la comuna, intententalo nuevamente',
						'error'
					);
				} else {
					swal(
						'', //titulo
						'Guardado Correctamente',
						'success'
					).then(function () {
						$.modal.close();
						let ubicacion = $('#UrbanoRural input:radio:checked').val();
						cargarComunas_VeredaPorIdMunicipio(ubicacion);
					});
				}
			}, "json");
	}
}


function guardarNuevoBarrio() {
	let nombreBarrio = $('#textinputBarrio').val();
	let latitud = $('#textinputBarrioLan').val();
	let longitud = $('#textinputBarrioLon').val();
	let comuna = $('#selectbasicComuna').val();
	let url = "php/nueva_Intervencion_Coordinadora.php";
	if (nombreBarrio != "" && latitud != "" && longitud != "") {
		$.post(url, {
				accion: 'guardarNuevoBarrio',
				barrio: nombreBarrio,
				comuna: comuna,
				latitud: latitud,
				longitud: longitud
			},
			function (data) {
				if (data.error == 1) {
					swal(
						'', //titulo
						' No se guardo la comuna, intententalo nuevamente',
						'error'
					);
				} else {
					swal(
						'', //titulo
						'Guardado Correctamente',
						'success'
					).then(function () {
						$.modal.close();
						$("#selectbasicComuna").trigger("change");
					});
				}
			}, "json");
	} else {
		swal(
			'Error',
			'Debes diligenciar todos los campos',
			'error'
		)
	}
}

function guardarNuevaVereda() {
	let nombreVereda = $('#textinputVereda').val();
	let latitud = $('#textinputVeredaLan').val();
	let longitud = $('#textinputVeredaLon').val();
	let municipio = $('#selectbasicMunicipio').val();
	let url = "php/nueva_Intervencion_Coordinadora.php";
	if (nombreVereda != "" && latitud != "" && longitud != "") {
		$.post(url, {
				accion: 'guardarNuevaVereda',
				municipio: municipio,
				vereda: nombreVereda,
				latitud: latitud,
				longitud: longitud
			},
			function (data) {
				if (data.error == 1) {
					swal(
						'', //titulo
						' No se guardo la comuna, intententalo nuevamente',
						'error'
					);
				} else {
					swal(
						'', //titulo
						'Guardado Correctamente',
						'success'
					).then(function () {
						$.modal.close();
						let ubicacion = $('#UrbanoRural input:radio:checked').val();
						cargarComunas_VeredaPorIdMunicipio(ubicacion);
					});
				}
			}, "json");
	} else {
		swal(
			'Error',
			'Debes diligenciar todos los campos',
			'error'
		)
	}
}

//TODO implementar guardar nueva entidad
function guardarNuevaEntidad() {
	let nombreEntidad = $('#textinputEntidadNueva').val();
	let direccion = $('#textinputDireccionEntidad').val();
	let telefono = $('#textinputTelefonoEntidad').val();
	let tipo_entidad = $('#selectbasicTipoEntidad').val();
	let barrio = $('#selectbasicBarrio').val();
	let vereda = $('#selectbasicVereda').val();
	let nodo = $('#text_inputNodoEntidad').val();
	let ubicacion = $('#UrbanoRural input:radio:checked').val();
	let url = "php/nueva_Intervencion_Coordinadora.php";
	if (nombreEntidad != "" && direccion != "" && telefono != "" && tipo_entidad != "" && nodo != "") {
		$.post(url, {
				accion: 'guardarNuevaEntidad',
				nombreEntidad: nombreEntidad,
				direccion: direccion,
				telefono: telefono,
				tipo_entidad: tipo_entidad,
				barrio: barrio,
				vereda: vereda,
				nodo: nodo,
				ubicacion: ubicacion
			},
			function (data) {
				if (data.error == 1) {
					swal(
						'', //titulo
						' No se guardo la entidad, intententalo nuevamente',
						'error'
					);
				} else {
					swal(
						'', //titulo
						'Guardado Correctamente',
						'success'
					).then(function () {
						$.modal.close();
						let ubicacion = $('#UrbanoRural input:radio:checked').val();
						if (ubicacion == 1) {
							$('#selectbasicVereda').trigger('change');
						} else {
							$('#selectbasicBarrio').trigger('change');
						}
					});
				}
			}, "json");
	} else {
		swal(
			'Error',
			'Debes diligenciar todos los campos',
			'error'
		)
	}
}