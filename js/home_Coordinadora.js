$(document).ready(function(){   
	
	traerNombre();	
	interevensionesPorZona();
	 
});


function initMap() {
    var uluru = {lat: 5.067774, lng: -75.517053};
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 9,
      center: uluru
    });
    var marker = new google.maps.Marker({
      position: uluru,
      map: map
    });
  }

function traerNombre(){ 

	$.post("php/CapturaVariableSession.php",{
           accion:'traerNombre'
		   
              				
         },
		 function (data) {
			if (data !=	""){		
				$('#Nombre').html(data);
			}
			else{
				swal(
				  '', //titulo
				  'Debes iniciar sesion!',
				  'error'
				);
				window.location.href = "welcome_Coordinadora.html";
			}
		}
          ,"json");

}

function interevensionesPorZona()
{
  
  $.post("php/home_Coordinadora.php",{
         accion : 'interevensionesPorZona'
              				
         },
          function (data) {
						if(data.error != 1){
								
								 $('#interversionesPorZona').html(data.html);
							}
							// else{
								// mostrarPopUpError(data.error);
							// }
							
						
				},"json");
}

function mostrarDetalleIntervencion(idIntervencion)
{
	window.location.href = "detalle_Intervencion_Coordinadora.html?id="+idIntervencion;
}