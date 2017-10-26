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
	cargarComportamientos();
	
	
	
	 
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

function cargarComportamientos(){

	$.post("php/nueva_Intervencion_Coordinadora.php",{
         accion : 'cargarComportamientos'
         		
         },
          function (data) {
						if(data.error != 1){
								
								 $('#selectbasicComportamiento').html(data.html);
								 
							}
							// else{
								// mostrarPopUpError(data.error);
							// }
							
						
				},"json");
}


$( "#selectbasicComportamiento" ).change(function() { 
	
	$.post("php/nueva_Intervencion_Coordinadora.php",{
         accion : 'cargarIndicadoresChec',
         idIndicador : $('#selectbasicComportamiento').val()  				
         },
          function (data) {
						if(data.error != 1){
								
								 $('#selectbasicIndicadores').html(data.html);
								 
							}
							// else{
								// mostrarPopUpError(data.error);
							// }
							
						
				},"json");
	
});

function guardarIntervencion(){
	
	if (!validarInformacion()) {
            swal(
				  '', //titulo
				  'Debes ingresar todos los datos!',
				  'error'
				);
        }else{
            
			
			//capturar los indicadores
			 var list = new Array();
 
            $.each($('#selectbasicIndicadores :selected'), function() {
				
				list.push($(this).val());
			 
			});
 
            // alert(list);
			
			//fin capturar los indicadores
			
			
			$.post("php/nueva_Intervencion_Coordinadora.php",{
			 accion : 'guararIntervencion',
			 idZona : idZona,  				
			 idEntidad : $('#selectbasicEntidad').val(),
			 idTipoIntervencion : $('#selectbasicTipoInvervencion').val(),
			 indicadores:list,
			 // idEntidad : $('#selectbasicEntidad').val(),
			 nombreEntidad : $('#selectbasicEntidad :selected').text(),
			 idBarrio : $('#selectbasicBarrio').val(), //o vereda
			 direccion : $('#textinputDireccion').val(), 
			 telefono : $('#textinputTelefono').val(), 
			 idTipoEntidad : $('#selectbasicTipoEntidad').val()
				
			 },
			  function (data) { 
							if(data.error == 1){
									
								swal(
									  '', //titulo
									  ' No se guardo la intervención, intententalo nuevamente',
									  'error'
									);	 
									 
							}
							else{
								swal(
									  '', //titulo
									  'Guardado Correctamente',
									  'success'
									);
									
									window.location.href = "detalle_Intervencion_Coordinadora.html?idIntervencion="+data.idIntervencion;
							}
								
								
							
				},"json");
        }
}

function validarInformacion(){
        var valido=true;
		//select
        $("select[id^=selectbasic]").each(function(e){
			if ($(this).val()==0){
				valido=false;
			}
        });
		//input
		 $("input[id^=textinput]").each(function(e){
			if ($(this).val()==""){
				valido=false;
			}
        });
		
        return valido;
    }
	
$( "#buttonCancelar" ).click(function() { 
	
	alert();
	window.location.href = "home_Coordinadora.html";
	
});


