$(document).ready(function(){   
	
	traerNombre();
	cargarJornadas();
	cargarPoblacion();
	cargarEstrategias();
	cargarEtapas();
	$("#planeacion2").hide();
	$("#planeacion3").hide();
	idEtapa ="";
	idPlaneacion="";
	
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
	comportamientos = $.get("comportamientos");
	competencia = $.get("competencia");
	idComportamientos = $.get("idComportamientos");
	idCompetencia = $.get("idCompetencia");
	idEntidad = $.get("idEntidad");
	
	$("#spanComportamiento,#spanComportamientog").html(comportamientos);
	$("#spanCompetencia, #spanCompetenciag").html(competencia);
	 
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
				window.location.href = "welcome_Coordinadora.html";
			}
		}
          ,"json");

}

/*Consulta las jornadas
* parametro: 
*/
function cargarJornadas(){
	
	$.post("php/planeacion_Coordinadora.php",{
           accion:'cargarJornadas'
		   
              				
         },
		 function (data) {
			if (data !=	""){ 		
				$('#selectbasicJornada').html(data.html);
			}
			
		}
          ,"json");
}

/*Consulta las jornadas
* parametro: 
*/
function cargarPoblacion(){
	
	$.post("php/planeacion_Coordinadora.php",{
           accion:'cargarPoblacion'
		   
              				
         },
		 function (data) {
			if (data !=	""){ 		
				$('#selectbasicPoblacion').html(data.html);
			}
			
		}
          ,"json");
}

/*Consulta las estrategias
* parametro: 
*/
function cargarEstrategias(){
	
	$.post("php/planeacion_Coordinadora.php",{
           accion:'cargarEstrategias'
		   
              				
         },
		 function (data) {
			if (data !=	""){ 		
				$('#selectbasicEstrategia').html(data.html);
			}
			
		}
          ,"json");
}

/*Dependiendo de la estrategia seleccionada se llena el tactico
* parametro: 
*/
$("#selectbasicEstrategia").change(function(){                                 
		
		var idEstrategia = $('#selectbasicEstrategia').val();
		if (idEstrategia != 0){
			cargarTacticos(idEstrategia);
		}
		else{
			
		}
		
});

/*Consulta las tacticos
* parametro: 
*/
function cargarTacticos(idEstrategia){
	
	$.post("php/planeacion_Coordinadora.php",{
           accion:'cargarTacticos',
		   idEstrategia:idEstrategia
		   
              				
         },
		 function (data) {
			if (data !=	""){ 		
				$('#selectbasicTactico').html(data.html);
			}
			
		}
          ,"json");
}

/*Consulta las etapas
* parametro: 
*/
function cargarEtapas(){ 
	
	$.post("php/planeacion_Coordinadora.php",{
           accion:'cargarEtapas'
		   
              				
         },
		 function (data) {
			if (data !=	""){ 		
				$('#etapas').html(data.html);
			}
			$('#btnGestion_1').hide(); //se oculta reconocimiento
		}
          ,"json");
}

/*Dependiendo si seleccionan si cuenta con algun contacto
* parametro: 
*/
$('#radiosContacto input:radio').click(function()   {                           
		
		if ($(this).val() === '1') { 
		  $("#preguntaContacto").show();
		}
		else{
			$("#preguntaContacto").hide();
		}
     
		
});


$('#buttonGuardarPlaneacion').click(function()   { 
	
	if (!validarInformacion()) {
            swal(
				  '', //titulo
				  'Debes ingresar todos los datos!',
				  'error'
				);
        }else{
            
 
            //fin capturar los indicadores
			if($('#textinputNombreContacto').val() != "" && $('#textinputCargoContacto').val() != "" && $('#textinputTelefonoContacto').val() != "" && $('#textinputCorreoContacto').val() != ""){
				nombreContacto : $('#textinputNombreContacto').val(); 
				cargoContacto : $('#textinputCargoContacto').val();
				telefonoContacto : $('#textinputTelefonoContacto').val();
				CorreoContacto : $('#textinputCorreoContacto').val();
			}
			else{
				nombreContacto ="";
				cargoContacto ="";
				telefonoContacto ="";
				correoContacto ="";
			}
			
			
			
			$.post("php/planeacion_Coordinadora.php",{
			 accion : 'guararPlaneacion',
			 nombreContacto :nombreContacto,
			 cargoContacto :cargoContacto,
			 telefonoContacto :telefonoContacto,
			 correoContacto :correoContacto,
			 fecha:$('#Fechainput').val(),
			 lugar:$('#textinputLugarEncuentro').val(),
			 jornada:$('#selectbasicJornada').val(),
			 comunidad:$('#comunidad input:radio[name=radiosComunidadEspecial]:checked').val(),
			 poblacion:$('#selectbasicPoblacion').val(),
			 observaciones:$('textarea[id="textareaObservaciones"]').val(),
			 idIntervencion:idIntervencion,
			 idEtapa:idEtapa,
			 idEntidad:idEntidad
				
			 },
			  function (data) { 
							if(data.error == 1){
									
								swal(
									  '', //titulo
									  ' No se guardo la planeación, intententalo nuevamente',
									  'error'
									);	 
									 
							}
							else{
								// swal(
									  // '', //titulo
									  // 'Guardado Correctamente',
									  // 'success'
									// );
									
									idPlaneacion = data.idPlaneacion;
									//gestion de redes
									if(idEtapa ==2){
										guardarGestionRedes();
										
									}//gestion educativa
									else if(idEtapa ==3){
										guardarGestionEducativa();
									}
									// window.location.href = "detalle_Intervencion_Coordinadora.html?idIntervencion="+data.idIntervencion;
							}
								
								
							
				},"json");
        }
});

function validarInformacion(){
        var valido=true;
		//select
        $("select[id^=selectbasic]").each(function(e){
			if ($(this).val()==0 && $(this).is(":visible")){ //alert("sel"+$( this ).attr('id'));
				valido=false;
			}
        });
		//input 
		 // $("input[id^=textinput]").each(function(e){  ("input[id^=textinput][id!=id_requerido]").each(fuanction(e){
		 $("input[id^=textinput]").each(function(e){  
			if ($(this).val()=="" && $(this).is(":visible")){ //alert("input"+$( this ).attr('id'));
				valido=false;
			}
        });
		
        return valido;
    }
	
$( "#buttonCancelar" ).click(function() { 
	
	window.location.href = "home_Coordinadora.html";
	
});

function seleccionarEtapa(idEtapa){
	
	idEtapa=idEtapa;
	
	consultarTemas();
	consultarIndicadoresGE();
	
	if(idEtapa ==1){
		
	} //gestion de redes
	else if(idEtapa ==2){
		$("#planeacion2").show();
		$("#planeacion3").hide();
		
		
	} //gestion educativa
	else if(idEtapa ==3){
		$("#planeacion3").show();
	$("#planeacion2").hide();
	}
	
}

/*guarda gestion educativa
* parametro: 
*/
function guardarGestionRedes(){ 

	//capturar indicadores
	var list = new Array();
	$("#indicadoresre input:checkbox:checked").each(function() {
        // alert("El checkbox con valor " + $(this).val() + " está seleccionado");
		list.push($(this).val());
		}
	);
	
	//capturar los indicadores
			 // var list = new Array();
 
            // $.each($('#selectbasicIndicadores :selected'), function() {
				
				// list.push($(this).val());
			 
			// });
	
	$.post("php/planeacion_Coordinadora.php",{
           accion:'guardarGestionRedes',
		   idPlaneacion:idPlaneacion,
           idTema:$("#selectbasicTemare").val(),
		   indicadores:list
		   
		   
         },
		 function (data) {
			if (data.error != 1){ 		
				swal(
					  '', //titulo
					  'Guardado Correctamente',
					  'success'
					);
					
					window.location.href = "detalle_Intervencion_Coordinadora.html?idIntervencion="+idIntervencion;
			}
			else{
				swal(
					  '', //titulo
					  'No se guardo la planeación, intentelo nuevamente',
					  'error'
					);
			}
			
		}
          ,"json");
}

/*Guarda gestion educativa
* parametro: 
*/
function guardarGestionEducativa(){ 
	
	$.post("php/planeacion_Coordinadora.php",{
           accion:'guardarGestionEducativa',
		   idPlaneacion:idPlaneacion,
		   idTema:$("#selectbasicTemare").val(),
		   indicadores:list,
		   tactico:$("#selectbasicTactico").val()
              			
         },
		 function (data) {
			if (data.error != 1){ 		
				swal(
					  '', //titulo
					  'Guardado Correctamente',
					  'success'
					);
					
					window.location.href = "detalle_Intervencion_Coordinadora.html?idIntervencion="+idIntervencion;
			}
			else{
				swal(
					  '', //titulo
					  'No se guardo la planeación, intentelo nuevamente',
					  'error'
					);
			}
		}
          ,"json");
}

/*consulta los temas
* parametro: 
*/
function consultarTemas(){ 
	
	$.post("php/planeacion_Coordinadora.php",{
           accion:'consultarTemas',
		   idComportamientos : idComportamientos,
		   idCompetencia : idCompetencia,				
         },
		 function (data) {
			if (data.error != 1){ 		
				$('#selectbasicTema,#selectbasicTemare').html(data.html);
			}
			
		}
          ,"json");
}


/*consulta los indicadoresge
* parametro: 
*/
function consultarIndicadoresGE(){ 
	
	$.post("php/planeacion_Coordinadora.php",{
           accion:'consultarIndicadoresGE'
		   			
         },
		 function (data) {
			if (data.error != 1){ 		
				$('#indicadoresge,#indicadoresre').html(data.html);
			}
			
		}
          ,"json");
}

$( "#buttonCancelar" ).click(function() { 
	
	window.location.href = "detalle_Intervencion_Coordinadora.html?idIntervencion="+idIntervencion;
	
});

/*Muestra el formulario de gestion de redes
* parametro: 
*/
// $('#btnGestionR').click(function()   {                           
		
	// $("#planeacion2").show();
	// $("#planeacion").hide();
		
// });

/*Muestra el formulario de gestion educativa
* parametro: 
*/
// $('#btnGestionE').click(function()   {                           
		
	// $("#planeacion3").show();
	// $("#planeacion2").hide();
		
// });
	





