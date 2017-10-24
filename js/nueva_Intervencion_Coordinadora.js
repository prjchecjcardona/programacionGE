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

	
	idZona = $.get("idZona");
	
	cargarZonasPorId(idZona);
	cargarPorMunicipiosPorIdZona(idZona);
	cargarTipoIntervencion();
	
	
	
	 
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

function cargarZonasPorId(idZona){ 
	
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

function cargarPorMunicipiosPorIdZona(idZona){ 
	
	$.post("php/nueva_Intervencion_Coordinadora.php",{
         accion : 'cargarMunicipiosPorIdZona',
         idZona : idZona   				
         },
          function (data) {
						if(data.error != 1){
								
								 $('#selectbasicMunicipio').html(data.html);
							}
							// else{
								// mostrarPopUpError(data.error);
							// }
							
						
				},"json");
}

$( "#selectbasicMunicipio" ).change(function() { 
	
	$.post("php/nueva_Intervencion_Coordinadora.php",{
         accion : 'cargarComunasPorIdMunicipio',
         idMunicipio : $('#selectbasicMunicipio').val()  				
         },
          function (data) {
						if(data.error != 1){
								
								 $('#selectbasicComuna').html(data.html);
							}
							// else{
								// mostrarPopUpError(data.error);
							// }
							
						
				},"json");
	
});

$( "#selectbasicComuna" ).change(function() { 
	
	$.post("php/nueva_Intervencion_Coordinadora.php",{
         accion : 'cargarBarriosPorComuna',
         idComuna : $('#selectbasicComuna').val()  				
         },
          function (data) {
						if(data.error != 1){
								
									if(data.html == ""){ 
										cargarEntidadPorVereda();
										$('#selectbasicBarrio').html("");
									}
									else{ 
										$('#selectbasicBarrio').html(data.html);
									}
									
							}
							// else{
								// mostrarPopUpError(data.error);
							// }
							
						
				},"json");
	
});

$( "#selectbasicBarrio" ).change(function() { 
	
	$.post("php/nueva_Intervencion_Coordinadora.php",{
         accion : 'cargarEntidadesPorBarrio',
         idBarrio : $('#selectbasicBarrio').val()  				
         },
          function (data) {
						if(data.error != 1){
								
								 $('#selectbasicEntidad').html(data.html);
								 $('#selectbasicTipoEntidad').html(data.tipo);
							}
							// else{
								// mostrarPopUpError(data.error);
							// }
							
						
				},"json");
	
});

function cargarEntidadPorVereda(){

	$.post("php/nueva_Intervencion_Coordinadora.php",{
         accion : 'cargarEntidadPorVereda',
         idComuna : $('#selectbasicComuna').val()  			
         },
          function (data) {
						if(data.error != 1){
								
								 $('#selectbasicEntidad').html(data.html);
								 $('#selectbasicTipoEntidad').html(data.tipo);
							}
							// else{
								// mostrarPopUpError(data.error);
							// }
							
						
				},"json");
}

function cargarTipoIntervencion(){

	$.post("php/nueva_Intervencion_Coordinadora.php",{
         accion : 'cargarTipoIntervencion'
         		
         },
          function (data) {
						if(data.error != 1){
								
								 $('#selectbasicTipoInvervencion').html(data.html);
								 
							}
							// else{
								// mostrarPopUpError(data.error);
							// }
							
						
				},"json");
}



