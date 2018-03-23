$(document).ready(function () {

	traerNombre();
	intervencionesPorZona();

	
});

function getUbicaciones() {
	$.post("php/home_Coordinadora.php", {
		accion: 'getUbicaciones'
	},
	function (data) {
		if (data.error != 1) {
			iniciarMapa(data.html);
		} else {
			map = new google.maps.Map(document.getElementById('map'), {
				zoom: 10,
				center: new google.maps.LatLng(5.067774, -75.517053),
				mapTypeId: 'roadmap'
			});
			swal(
				'Error', //titulo
				data.mensaje,
				'error'
			);
		}
	}, "json");
}

//Inicializacion del mapa con punto de Referencia.
var map;

function iniciarMapa(data) {
	map = new google.maps.Map(document.getElementById('map'), {
		zoom: 10,
		center: new google.maps.LatLng(5.067774, -75.517053),
		mapTypeId: 'roadmap'
	});

	//Inicializacion de variables para los iconos del mapa.
	var iconBase = 'img/icons/';
	var features = Array();
	data.forEach((element, index) => {
		features.push({
			position: new google.maps.LatLng(element.latitud, element.longitud),
			type: 'gestor'+index
		})
	});

	// Creación de marcadores en el mapa.
	features.forEach(function (feature) {
		var marker = new google.maps.Marker({
			position: feature.position,
			icon: iconBase+'003-girl-6.png',
			map: map,
			draggable: false
		});
	});

	$("#btnInformes").click(function () {

		window.location.href = "dashboard_Coordinadora.html";

	});

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

function intervencionesPorZona() {

	$.post("php/home_Coordinadora.php", {
			accion: 'intervencionesPorZona'

		},
		function (data) {
			if (data.error != 1) {

				$('#intervercionesPorZona').html(data.html);
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
	window.location.href = "detalle_Intervencion_Coordinadora.html?idIntervencion=" + idIntervencion;
}

function agregarIntervencion(idZona) {
	window.location.href = "nueva_Intervencion_Coordinadora.html?idZona=" + idZona;
}