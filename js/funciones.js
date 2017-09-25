$(document).ready(function(){       
   $.ajax({
        url: 'php/ConsultasDB.php?consulta=1',
        success: function(resp){
         $('#selectbasicZona').html(resp) 
         }
    });
   $.ajax({
        url: 'php/ConsultasDB.php?consulta=7',
        success: function(resp){
         $('#selectbasicTipoEntidad').html(resp) 
         }
    });
  $.ajax({
      url: 'php/ConsultasDB.php?consulta=8',
      success: function(resp){
       $('#selectbasicTipoInvervencion').html(resp) 
       }
  });
   $.ajax({
      url: 'php/ConsultasDB.php?consulta=9',
      success: function(resp){
       $('#selectbasicComportamiento').html(resp) 
       }
  });
   $.ajax({
      url: 'php/ConsultasDB.php?consulta=11',
      success: function(resp){
       $('#selectbasicTipoDocumento').html(resp) 
       }
  });
   $.ajax({
      url: 'php/ConsultasDB.php?consulta=12',
      success: function(resp){
       $('#selectbasicJornada').html(resp) 
       }
  });
   $.ajax({
      url: 'php/ConsultasDB.php?consulta=13',
      success: function(resp){
       $('#selectbasicPoblacion').html(resp) 
       }
  });
    $.ajax({
      url: 'php/ConsultasDB.php?consulta=14',
      success: function(resp){
       $('#selectbasicEstrategia').html(resp) 
       }
  });
});

function buscarEntidad1() { 
    //Al escribr dentro del input con id="service"
    $('#textinputNombreEntidad').keypress(function(){
        //Obtenemos el value del input
        var service = $(this).val(); 
         var selector = document.getElementById('selectbasicBarrio');
        var barrio = selector[selector.selectedIndex].value;
         var selector = document.getElementById('selectbasicVereda');
        var vereda = selector[selector.selectedIndex].value;
        //if(document.getElementById("selectbasicBarrio").disabled == false)
        if(barrio=="")
        {
          barrio=0;
        }
         if(vereda=="")
        {
          vereda=0;
        }       
        var dataString = 'service='+service;
        var consulta='6';
        //Le pasamos el valor del input al ajax
        $.ajax({
            type: "GET",
            url: "php/ConsultasDB.php?consulta="+consulta+"&barrio="+barrio+"&vereda="+vereda+"",
            data: dataString,
            success: function(data) {
                //Escribimos las sugerencias que nos manda la consulta
                $('#suggestions').fadeIn(1000).html(data);
                //Al hacer click en algua de las sugerencias
                $('.suggest-element').live('click', function(){
                    //Obtenemos la id unica de la sugerencia pulsada
                    var id = $(this).attr('id');
                    //Editamos el valor del input con data de la sugerencia pulsada
                    $('#textinputNombreEntidad').val($('#'+id).attr('data'));
                    //Hacemos desaparecer el resto de sugerencias
                    $('#suggestions').fadeOut(1000);
                });              
            }
        });
    });    
}

  


function recargarMunicipios(zona)
{
   //esperando la carga...
   $('#selectbasicMunicipio').html('<option value="">Cargando...aguarde</option>'); 
   //realizo la call via jquery ajax
   var parametros = {
                "Id_Zona" : zona,
                "consulta" : '2'
        };
   $.ajax({    
        url: 'php/ConsultasDB.php',
        data: parametros,
        type:  'get',
        success: function(resp){
         $('#selectbasicMunicipio').html(resp) 
         }
    });
}


function recargarComunas(municipio)
{
   //esperando la carga...
   $('#selectbasicComuna').html('<option value="">Cargando...aguarde</option>'); 
   //realizo la call via jquery ajax
   var parametros = {
                "Id_Municipio" : municipio,
                "consulta" : '3'
        };
   $.ajax({    
        url: 'php/ConsultasDB.php',
        data: parametros,
        type:  'get',
        success: function(resp){
         $('#selectbasicComuna').html(resp) 
         }
    });


   $('#selectbasicVereda').html('<option value="">Cargando...aguarde</option>'); 
   //realizo la call via jquery ajax
   var parametros = {
                "Id_Municipio" : municipio,
                "consulta" : '4'
        };
   $.ajax({    
        url: 'php/ConsultasDB.php',
        data: parametros,
        type:  'get',
        success: function(resp){
         $('#selectbasicVereda').html(resp) 
         }
    });
}

function recargarBarrios(comuna)
{
   //esperando la carga...
   $('#selectbasicBarrio').html('<option value="">Cargando...aguarde</option>'); 
   //realizo la call via jquery ajax
   var parametros = {
                "Id_Comuna" : comuna,
                "consulta" : '5'
        };
   $.ajax({    
        url: 'php/ConsultasDB.php',
        data: parametros,
        type:  'get',
        success: function(resp){
         $('#selectbasicBarrio').html(resp) 
         }
    });
}

function buscarEntidad() {  
   var selector = document.getElementById('selectbasicBarrio');
        var barrio = selector[selector.selectedIndex].value;
         var selector = document.getElementById('selectbasicVereda');
        var vereda = selector[selector.selectedIndex].value;
        //if(document.getElementById("selectbasicBarrio").disabled == false)
        if(barrio=="")
        {
          barrio=0;
        }
         if(vereda=="")
        {
          vereda=0;
        }
         var parametros = {
                  "selectbasicBarrio" : barrio,
                  "selectbasicVereda" : vereda,
                  "consulta" : '6'              
          };
        $('#txtCountry').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "php/ConsultasDB.php",
          data: {query:query,parametros},
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
            result($.map(data, function (item) {
              return item;
                        }));
                    }
                });
            }
        });
}
/*SI
function buscarEntidad() {  
   var selector = document.getElementById('selectbasicBarrio');
        var barrio = selector[selector.selectedIndex].value;
         var selector = document.getElementById('selectbasicVereda');
        var vereda = selector[selector.selectedIndex].value;
        //if(document.getElementById("selectbasicBarrio").disabled == false)
        if(barrio=="")
        {
          barrio=0;
        }
         if(vereda=="")
        {
          vereda=0;
        }
         var parametros = {
                  "selectbasicBarrio" : barrio,
                  "selectbasicVereda" : vereda,
                  "consulta" : '6'              
          };
  var min_length = 0; // min caracters to display the autocomplete
  var keyword = $('#textinputNombreEntidad').val();  
  if (keyword.length >= min_length) {
    $.ajax({
      url: 'php/ConsultasDB.php',
      type: 'GET',
      data: {keyword:keyword, parametros},
      dataType: "json",
      //success:function(resp){        
        //$('#textinputNombreEntidad').html(resp);
        success: function (data) {
            result($.map(data, function (item) {
              return item;
            }));
      }
    });
  } 
}*/
/*NO SIRVE
function buscarEntidad() { 
   var selector = document.getElementById('selectbasicBarrio');
        var barrio = selector[selector.selectedIndex].value;
         var selector = document.getElementById('selectbasicVereda');
        var vereda = selector[selector.selectedIndex].value;
        //if(document.getElementById("selectbasicBarrio").disabled == false)
        if(barrio=="")
        {
          barrio=0;
        }
         if(vereda=="")
        {
          vereda=0;
        }
         var parametros = {
                  "selectbasicBarrio" : barrio,
                  "selectbasicVereda" : vereda,
                  "consulta" : '6'              
          };
        
        $("#textinputNombreEntidad").autocomplete({  

            source: function(request, response) {              
                $.ajax({
                    url: 'php/ConsultasDB.php',
                    dataType: "json", 
                    type: 'GET',
                    data: {request,parametros}, 
                    success: function (data) {  
                          if(data)                                    
                          {
                            alert("ok");
                          }
                            response(data);                        
                          
                      }
                 });      
             }, 
               
         });  
   }    
*/
//si funciona
/*
function buscarEntidad() { 
   var selector = document.getElementById('selectbasicBarrio');
        var barrio = selector[selector.selectedIndex].value;
         var selector = document.getElementById('selectbasicVereda');
        var vereda = selector[selector.selectedIndex].value;
        //if(document.getElementById("selectbasicBarrio").disabled == false)
        if(barrio=="")
        {
          barrio=0;
        }
         if(vereda=="")
        {
          vereda=0;
        }
         var parametros = {
                  "selectbasicBarrio" : barrio,
                  "selectbasicVereda" : vereda,
                  "consulta" : '6'              
          };
$('#textinputNombreEntidad').typeahead({
      source:  function (query, process) {
        return $.get('php/ConsultasDB.php?consulta=6', { query: query, selectbasicBarrio:barrio, selectbasicVereda:vereda }, function (data) {
            console.log(data);
            data = $.parseJSON(data);
              return process(data);
          });
      }
  });
}*/

/*
  <select class="itemName form-control" style="width:500px" name="itemName"></select>
</div>*/
/*
function buscarEntidad()
{
      $('.itemName').select2({
        placeholder: 'Select an item',
        ajax: {
          url: '/ajaxpro.php',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });*/
    

/*NO SIRVIO
    function buscarEntidad() {  
   var selector = document.getElementById('selectbasicBarrio');
        var barrio = selector[selector.selectedIndex].value;
         var selector = document.getElementById('selectbasicVereda');
        var vereda = selector[selector.selectedIndex].value;
        //if(document.getElementById("selectbasicBarrio").disabled == false)
        if(barrio=="")
        {
          barrio=0;
        }
         if(vereda=="")
        {
          vereda=0;
        }
         var parametros = {
                  "selectbasicBarrio" : barrio,
                  "selectbasicVereda" : vereda,
                  "consulta" : '6'              
        };                  
        $('.itemName').select2({

        placeholder: 'Select an item',
        ajax: {
          url: 'php/ConsultasDB.php',
          dataType: 'json',
          data: parametros,
          delay: 250,
          processResults: function (resp) {
            return {
              results: resp
            };
          },
          cache: true
        }
      });

/*
  var min_length = 0; // min caracters to display the autocomplete
  var keyword = $('#textinputNombreEntidad').val();  
  if (keyword.length >= min_length) {
    $.ajax({
      url: 'php/ConsultasDB.php',
      type: 'GET',
      data: {keyword:keyword, parametros},
      dataType: "json",
      //success:function(resp){        
        //$('#textinputNombreEntidad').html(resp);
        success: function (data) {
            result($.map(data, function (item) {
              return item;
            }));
      }
    });
  }
}*/

/*
NO SIRVE
function buscarEntidad()
{
  $('#textinputNombreEntidad').bootcomplete({
      source: function (query, result) {
      var selector = document.getElementById('selectbasicBarrio');
      var barrio = selector[selector.selectedIndex].value;
       var selector = document.getElementById('selectbasicVereda');
      var vereda = selector[selector.selectedIndex].value;
      if(document.getElementById("selectbasicBarrio").disabled == true)
      {
        alert("barrio habilitado");
      }
      else
      {
         alert("barrio deshabilitado");
      }
      var parametros = {
                "selectbasicBarrio" : barrio,
                "selectbasicVereda" : vereda,
                "consulta" : '6',
                "query": + query
        };
         $.ajax({
                    url: "php/ConsultasDB.php",
                    minLength : 1,
                    data: parametros,            
                    dataType: "json",
                    type: "GET",
                    success: function (data) {
            result($.map(data, function (item) {
              return item;
            }));
          }
        });
      }
  });
}*/
        //minLength : 1,
       
       // success: function (data) {
        //result($.map(data, function (item) {
          //return item;
        /*formParams: {
            'selectbasicBarrio' : $('#selectbasicBarrio'),
            'selectbasicVereda' : $('#selectbasicVereda')*/
        

/*
$('#txtCountry').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "server.php",
          data: 'query=' + query,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
            result($.map(data, function (item) {
              return item;
                        }));
                    }
                });
            }
        });..<div class="row">
              <div class="col-md-12 text-center">
                  <input onkeyup="buscarEntidad()" class="typeahead form-control" id="textinputNombreEntidad" style="margin:0px auto;width:300px;" type="text">
              </div>
            </div>*/


//}

function validarRadio(valor)
{
  if (valor == '1') {//rural
       document.getElementById("selectbasicVereda").disabled=false;
       document.getElementById("selectbasicBarrio").disabled=true;
  } 
  else {//urbano
        document.getElementById("selectbasicBarrio").disabled=false;
        document.getElementById("selectbasicVereda").disabled=true;
  }
}


  
function recargarIndicadores(comportamiento)
{
   //esperando la carga...
   var parametros = {
                "Id_Comportamiento" : comportamiento,
                "consulta" : '10'
        };
   $.ajax({    
        url: 'php/ConsultasDB.php',
        data: parametros,
        type:  'get',
        success: function(resp){
         $('.comportamiento').html(resp)
         }
    });
}

function recargarTactico(estrategia)
{
   //esperando la carga...
   $('#selectbasicTactico').html('<option value="">Cargando...aguarde</option>'); 
   //realizo la call via jquery ajax
   var parametros = {
                "Id_Estrategia" : estrategia,
                "consulta" : '15'
        };
   $.ajax({    
        url: 'php/ConsultasDB.php',
        data: parametros,
        type:  'get',
        success: function(resp){
         $('#selectbasicTactico').html(resp) 
         }
    });
}


function cargarFormulario()
{
  var selector = document.getElementById('selectbasicBarrio');
  var barrio = selector[selector.selectedIndex].value;
   var selector = document.getElementById('selectbasicVereda');
  var vereda = selector[selector.selectedIndex].value;
  if(vereda=="")
    vereda=0;
 
  window.open("php/NuevaAsistencia.php?barrio="+barrio+"&vereda="+vereda, "ventana" , "width=640,height=480,scrollbars=NO,menubar=NO,resizable=NO,titlebar=NO,status=NO") ;
 
}
//window.open('php/NuevaAsistencia.php?barrio=&vereda','ventana','width=640,height=480,scrollbars=NO, menubar=NO,resizable=NO,titlebar=NO,status=NO'); return false"