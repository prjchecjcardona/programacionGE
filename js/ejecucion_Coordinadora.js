//Invocacion del archivo File Input Ejecucion Coordinadora
$(function(){
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
	
	idPlaneacion = $.get("idPlaneacion");
	idIntervencion = $.get("idIntervencion");
	nCumplimiento
	cargarDatosPlaneacion();
  });
 
 
 
/*Consulta el nombre de la persona que inicio sesión
* parametro: 
*/
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
				
			}
		}
          ,"json");

}


function cargarDatosPlaneacion(){ 

	$.post("php/ejecucion_Coordinadora.php",{
           accion:'cargarDatosPlaneacion',
		   idPlaneacion:idPlaneacion,
    				
         },
		 function (data) {
			if (data.error == 0){		
		
				 // $('#fechaInd').html(data.html.fecha);
				 // $('#lugarInd').html(data.html.lugar);
				 // $('#municipioInd').html(data.html.municipio);
				 // $('#comportamientoInd').html(data.html.comportamiento);
				 // $('#competenciaInd').html(data.html.competencia);
				 // $('#estrategiaInd').html(data.html.estrategia);
				 // $('#tacticoInd').html(data.html.tactico);
			}
			else{
				swal(
				  '', //titulo
				  'Debes iniciar sesion!',
				  'error'
				);
				
			}
		}
          ,"json");

}


function guardarEjecucion(){
	
	if (!validarInformacion()) {
            swal(
				  '', //titulo
				  'Debes ingresar todos los datos!',
				  'error'
				);
        }else{
            
			//detalleNivelCumplimiento
			var list = new Array();
 
			$.each($('#detalleNivelCumplimiento :selected'), function() {
				
				list.push($(this).val());
			 
			});
			
			
			//capturar los indicadores
			 var list1 = new Array();
 
            $.each($('#nCumplimiento :selected'), function() {
				
				list1.push($(this).val());
			 
			});
 
            //fin capturar los indicadores
				
			
			$.post("php/nueva_Intervencion_Coordinadora.php",{
			 accion : 'guardarEjecucion',
			 			
			 fecha : $('#textFecha').val(),
			 hora : $('#selectbasicHoraEje').val()+":"+$('#selectbasicMinEje').val(),
			 asistentes : $('#textinputAsisNum').val(),
			 detalleCumplimiento : list, 
			 nCumplimiento : list1,
			 idPlaneacion:idPlaneacion
			 
				
			 },
			  function (data) { 
							if(data.error == 1){
									
								swal(
									  '', //titulo
									  ' No se guardo la ejecución, intententalo nuevamente',
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
        $("#detalleNivelCumplimiento radio[name^=detalle]").each(function(e){
			if ($(this).val()==0 && $(this).is(":visible")){ //alert("sel"+$( this ).attr('id'));
				valido=false;
			}
        });
		//input 
		 // $("input[id^=textinput]").each(function(e){  ("input[id^=textinput][id!=id_requerido]").each(fuanction(e){
		 $("input[id^=txtFecha]").each(function(e){  
			if ($(this).val()=="" && $(this).is(":visible")){ //alert("input"+$( this ).attr('id'));
				valido=false;
			}
        });
		
        return valido;
    }


/*Dependiendo si seleccionan si cuenta con algun contacto
* parametro: 
*/
$('#radiosAlgunContacto input:radio').click(function()   {                           
	
	//si contacto 
	if ($(this).val() == 'siContacto') {  
	  
	  contacto = $(this).val();
	}
	else{
	  contacto = $(this).val();
  
	}
 
	
});

/*el detalle cumplimiento
* parametro: 
*/
$('#detalleNivelCumplimiento input:radio').click(function()   {                           
	
	

});

