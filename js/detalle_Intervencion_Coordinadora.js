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

	
	idIntervencion = $.get("id");
	cargarDetalleIntervencion(idIntervencion);
	cargarPlaneacionesPorIntrevencion(idIntervencion);
	
	
	 
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

/*Consulta el detalle de la intervención
* parametro: idIntervencion
*/
function cargarDetalleIntervencion(idIntervencion){

	$.post("php/detalle_Intervencion_Coordindora.php",{
         accion : 'cargarDetalleIntervencion',
		 idIntervencion:idIntervencion
              				
         },
          function (data) {
						if(data.error != 1){
								
								 $('#lblMunicipio').html(data.html[0].TipoIntrevencion);
								 $('#lblEntidad').html(data.html[0].TipoIntrevencion);
								 $('#lblCoportamiento').html(data.html[0].TipoIntrevencion);
								 $('#lblCompetencia').html(data.html[0].TipoIntrevencion);
								 $('#lblIndicadorChec1').html(data.html[0].IndicadorChec1);
								 $('#lblIndicadorChec2').html(data.html[0].IndicadorChec2);
								 $('#lblIndicadorChec3').html(data.html[0].IndicadorChec3);
								 $('#lblTipoIntervencion').html(data.html[0].TipoIntrevencion);
							}
							
							
						
				},"json");
}

/*Consulta las planeaciones por intervencion
* parametro: idIntervencion
*/
function cargarPlaneacionesPorIntrevencion(idIntervencion){

	$.post("php/detalle_Intervencion_Coordindora.php",{
         accion : 'cargarPlaneacionesPorIntrevencion',
		 idIntervencion:idIntervencion
              				
         },
          function (data) {
						if(data.error != 1){
								
								 cargarInformacionEnTabla(data);
							}
							
							
						
				},"json");
}

/*Carga la respuesta de la base de datos en el datatable
* parametro: data
*/
 function cargarInformacionEnTabla(data){ //alert(data);
        var table = $('#coordinadora_tabla').DataTable({
            "data": data,
            columns: [
			{ title: "Id" },
			{ title: "Fecha" },
			{ title: "Etapa" },
			{ title: "Estrategia" },
			{ title: "Táctico" },
			{ title: "Tema" },
			{ title: "Ejecución" },
			{ title: "Evaluación" },
			
			{data: null, className: "center", defaultContent: '<a href="#" id="ejecucion" class="btn btn-sm btn-success"><span class="fa fa-book"></span></a>'},
			{data: null, className: "center", defaultContent: '<a href="#" id="evaluacion" class="btn btn-sm btn-success"><span class="fa fa-pencil-square-o"></span></a>'}
			],
            "paging":   false,
            "info":     false,
            "columnDefs": [{"className": "dt-left", "targets": "_all"}, //alinear texto a la izquierda
			{"targets": [ 1 ],"visible": false,"searchable": false},
			{ "width": "13%", "targets": 1 }//se le da ancho al td de estudiante
			//{ "width": "8%", "targets": 8 }, //se le da ancho al td de total horas
			//{ "width": "8%", "targets": 9 } //se le da ancho al td de observacion
			],
            "scrollY": "300px",
            "scrollX": true,
            "scrollCollapse": true,
            "language": {
                "sSearch": "Filtrar:",
                "zeroRecords": "Ningún resultado encontrado",
                "infoEmpty": "No hay registros disponibles",
                "Search:": "Filtrar"
            }
        });
		
		
		
		
    }
	
//Evento para ver detalle ejecucion//
	$(document).on('click', '#ejecucion', function() {
			var data = table.row($(this).parents('tr')).data();
			idPlaneacion= data[0];
			if(data[0]!=""){
				
						
			}
	});
	
//Evento para ver detalle evalucion//
	$(document).on('click', '#evaluacion', function() {
			var data = table.row($(this).parents('tr')).data();
			idPlaneacion= data[0];
			if(data[0]!=""){
				
						
			}
	});
	
$("#btnNuevaPlaneacion").click(function(){                                 
		
		window.location.href = "planeacion_Coordinadora.html?id="+idIntervencion;
		
	});