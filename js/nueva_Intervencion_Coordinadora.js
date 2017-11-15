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
		});
	})

	$('#btnNuevaVereda').click(function () {
		$("#ex2").modal({
			fadeDuration: 500,
			fadeDelay: 0.50,
		});
	})

	$('#btnNuevoBarrio').click(function () {
		$("#ex3").modal({
			fadeDuration: 500,
			fadeDelay: 0.50,
		});
	})

	$('#btnNuevaComuna').click(function () {
		$("#ex4").modal({
			fadeDuration: 500,
			fadeDelay: 0.50,
		});
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

	cargarZonasPorId(idZona);
	cargarPorMunicipiosPorIdZona(idZona);
	cargarTipoIntervencion();
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
				window.location.href = "welcome_Coordinadora.html";
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
				$('#selectbasicTipoEntidad').html(data.tipo);
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
				$('#selectbasicTipoEntidad').html(data.tipo);
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


		//capturar los indicadores
		var list = new Array();

		$.each($('#selectbasicIndicadores :selected'), function () {

			list.push($(this).val());

		});

		//fin capturar los indicadores

		//se va a guardar la nueva entidad
		if (nuevaentidad == 1) {
			direccion = $('#textinputDireccion').val();
			telefono = $('#textinputTelefono').val();
			nombreEntidad = $('#textinputEntidadNueva').val();
			idTipoEntidad = $('#selectbasicTipoEntidadNueva').val();

		} else {

			direccion = "";
			telefono = "";
			nombreEntidad = $('#selectbasicEntidad :selected').text(),
				idTipoEntidad = $('#selectbasicTipoEntidad').val();
		}



		$.post("php/nueva_Intervencion_Coordinadora.php", {
				accion: 'guararIntervencion',
				idZona: idZona,
				idEntidad: $('#selectbasicEntidad').val(),
				idTipoIntervencion: $('#selectbasicTipoInvervencion').val(),
				indicadores: list,
				nombreEntidad: $('#selectbasicEntidad :selected').text(),
				idBarrio: $('#selectbasicBarrio').val(),
				idVereda: $('#selectbasicVereda').val(),
				idTipoEntidad: $('#selectbasicTipoEntidad').val(),
				nuevaentidad: nuevaentidad,
				direccion: direccion,
				telefono: direccion


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
					);

					window.location.href = "detalle_Intervencion_Coordinadora.html?idIntervencion=" + data.idIntervencion;
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

	window.location.href = "home_Coordinadora.html";

});

//Invocacion del archivo File Input para nueva intervencion coordinadora
// $(function(){
<<<<<<< HEAD
// $('#nueva_intervencion_coord').fileinput({
// language: 'es',
// 'theme': 'fa',
// uploadUrl: '#',
// allowedFileExtensions: ['jpg', 'png', 'gif', 'pdf', 'doc', 'docx',
// 'xlsx', 'xls', 'ppt', 'pptx', 'mp4', 'avi', 'mov', 'mpeg4']
// });
// })
=======
    // $('#nueva_intervencion_coord').fileinput({
          // language: 'es',
          // 'theme': 'fa',
          // uploadUrl: '#',
          // allowedFileExtensions: ['jpg', 'png', 'gif', 'pdf', 'doc', 'docx',
          // 'xlsx', 'xls', 'ppt', 'pptx', 'mp4', 'avi', 'mov', 'mpeg4']
      // });
  // })

>>>>>>> bad924883dfb9fe1b5e1bc3a937fa1902f80aead
