$(document).ready(function(){   
	
	traerNombre();
	cargarJornadas();
	cargarPoblacion();
	cargarEstrategias();
	$("#planeacion2").hide();
	$("#planeacion3").hide();
	
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

	
	idIntervencion = $.get("id");	
	 
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
				$('#selectbasicJornada').html(data);
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
				$('#selectbasicPoblacion').html(data);
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
				$('#selectbasicEstrategia').html(data);
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
				$('#selectbasicTactico').html(data);
			}
			
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


/*Muestra el formulario de gestion de redes
* parametro: 
*/
$('#btnGestionR').click(function()   {                           
		
	$("#planeacion2").show();
	$("#planeacion").hide();
		
});

/*Muestra el formulario de gestion educativa
* parametro: 
*/
$('#btnGestionE').click(function()   {                           
		
	$("#planeacion3").show();
	$("#planeacion2").hide();
		
});





