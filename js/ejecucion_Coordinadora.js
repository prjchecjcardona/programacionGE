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
	nCumplimiento="";
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
		
				 $('#fechaInd').html(data.html.fecha);
				 $('#lugarInd').html(data.html.lugar);
				 $('#municipioInd').html(data.html.municipio);
				 $('#comportamientoInd').html(data.html.comportamiento);
				 $('#competenciaInd').html(data.html.competencia);
				 $('#estrategiaInd').html(data.html.estrategia);
				 $('#tacticoInd').html(data.html.tactico);
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
        }else{ alert("entro");
            
			//detalleNivelCumplimiento
			var list = new Array();
 
			$.each($('#detalleNivelCumplimiento :checked'), function() {
				
				list.push($(this).val());
			 
			});
			
            //fin capturar los indicadores
				
			
			$.post("php/ejecucion_Coordinadora.php",{
			 accion : 'guardarEjecucion',
			 fecha : $('#textFecha').val(),
			 hora : $('#selectbasicHoraEje').val()+":"+$('#selectbasicMinEje').val(),
			 asistentes : $('#textinputAsisNum').val(),
			 detalleCumplimiento : list, 
			 nCumplimiento : $('input:radio[name=nCumplimiento]:checked').val(),
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
									
									window.location.href = "detalle_Intervencion_Coordinadora.html?idIntervencion="+idIntervencion;
							}
								
								
							
				},"json");
        }
}

function validarInformacion(){ 
        var valido=true;
		//radio
		cont=0;
        $("#detalleNivelCumplimiento input:radio[name^=detalle_]:checked").each(function(e){
			 // alert("radio"+$( this ).attr('id'));
				
			cont++;
        });
		if (cont == 4 )
		{
			
		}
		else
		{
			valido=false;
		}
		
		//input 
		 // $("input[id^=textinput]").each(function(e){  ("input[id^=textinput][id!=id_requerido]").each(fuanction(e){
		 $("input[id^=text]").each(function(e){  
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

