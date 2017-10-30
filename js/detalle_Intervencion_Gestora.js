$(document).ready(function(){  

	traerNombre();
	/*Extrae los parametros que llegan en la url
* parametro: 
*/
	$.get = function(key)   {  
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
	
	
	cargarDetalleIntervencion(idIntervencion);	
	 
});

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

function cargarDetalleIntervencion(idIntervencion){ 
	
	$.post("php/nueva_Intervencion_Coordinadora.php",{
         accion : 'cargarZonasPorId',
         idZona : idZona   				
         },
          function (data) {
						if(data.error != 1){ 
								
								 $('#nombreZona').html(data.html);
							}
							// else{
								// mostrarPopUpError(data.error);
							// }
							
						
				},"json");
}
