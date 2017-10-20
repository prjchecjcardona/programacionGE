$(document).ready(function(){   
  mostrarIntervencion();
  mostrarDetalleIntervencion();
  cargarDetalleIntervencion();
	var data;
   $.ajax({
      data: {"accion": 'traerZona'},
      type: 'post',
      datatype: 'json',
      url: 'php/Gestoras.php',
      success: function(data){
          //datos = data;              
          //var zona = JSON.parse(data);
          $('#NombreZona').text(data);
      }
  });
  
  if($('#selectbasicMunicipio option:selected')){
     var selValue = $('#selectbasicMunicipio').val();     

     if(!selValue)//se llena el select de municipios
     {            
        $.ajax({
          data: {"accion": 'traerMunicipios'},
          type: 'post',          
          url: 'php/Gestoras.php',
          success: function(resp){              
           $('#selectbasicMunicipio').html(resp) 
           }
        });
     }
  }



});

function mostrarIntervencion()
{
     $.ajax({
      data: {"accion": 'traerIntervencion'},
      type: 'post',
      datatype: 'json',
      url: 'php/Gestoras.php',
      success: function(data){
          //datos = data;              
          //var zona = JSON.parse(data);
          $('#1').text(data);
      }
  });
}

function mostrarDetalleIntervencion()
{
    //para saber a que link se le dio click
    $('#Intervenciones').click(function(e){
     idIntervencion = e.target.id;
     //alert(idIntervencion);

         $.ajax({
          data: {"accion": 'guardarDetalleIntervencion', 'idIntervencion' : idIntervencion },
          type: 'post',
          //datatype: 'json',
          url: 'php/Gestoras.php',
          success: function(data){
              //datos = data;              
              //var zona = JSON.parse(data);
              //$('#Intervenciones').html(data);
          }
      });
    
    }); 
}
function cargarDetalleIntervencion()
{
    //alert(idIntervencion);
       $.ajax({
        data: {"accion": 'traerDetalleIntervencion'},//,"idIntervencion": idIntervencion},
        type: 'post',
        datatype: 'json',
        url: 'php/Gestoras.php',
        success: function(data){ //,detalle){
          //alert(data);
           resp = JSON.parse(data);
           //$.parseJSON(data);
          // resp1= JSON.parse(detalle);
            //alert(resp.length); 
            Municipio = resp["Municipio"];
            Tipo_Intervencion = resp["Tipo_Intervencion"];
            NombreEntidad = resp["NombreEntidad"];
            Comportamientos = resp["Comportamientos"];
            Competencia = resp["Competencia"];
            cantidad = resp["cantidad"];
           
                 //$('#textinputTelefono').html(telefono)
                 //alert(Municipio) ;
             $( "#mostrarMunicipio" ).append("<label for=''>"+Municipio+"</label>");
            //$('#mostrarMunicipio').val(Municipio);
            //$('#mostrarTipoIntervencion').val(Tipo_Intervencion);
            $( "#mostrarTipoIntervencion" ).append("<label for=''>"+Tipo_Intervencion+"</label>");
            $( "#mostrarEntidad" ).append("<label for=''>"+NombreEntidad+"</label>");
            $( "#mostrarComportamiento" ).append("<label for=''>"+Comportamientos+"</label>");
            $( "#mostrarCompetencia" ).append("<label for=''>"+Competencia+"</label>");
            for(i=0;i<cantidad;i++)
            {
              Indicadores = resp["Indicadores"+i];             
             $( "#mostrarIndicadores" ).append("<li><label class='mr-sm-2'>"+Indicadores+"</label></li>");
            }
        }
    });
  //});
}

/*
$("#button2id").click(function(){
    alert("Entro a guardar");
    var i=0;
    //$('.checkbox').find(':checkbox').each(function(){
      /* var indicador = [];
      $("input[id^='Indicador']:checked").each(function(){
          var indicadores = this;
          indicador[i] = indicadores.value;
          //alert(indicadores.value);
          i++;
        });   */ 
      
     //indicador.toString(); 
     //indicador.serialize();
     //console.log(indicador);
    //document.formularioGestora.action="php/Gestoras.php?accion='guardarIntervencion'&indicadores='"+indicador+'';
    /*document.formularioGestora.action="php/Gestoras.php?accion='guardarIntervencion'&indicadores='"+indicador+'';
    document.formularioGestora.submit();*/
    /*
    $.post("php/Gestoras.php",
        {
          //FALTA ENVIAR EL RESTO DE LAS VARIABLES DEL FORMULARIO
         accion    : 'guardarIntervencion'

          
        },
        function(data){
          if(data.error == 1)
          {
            alert("se presento un error");
          }
          else
          { 
            alert("guardado");
          }          
        }
      );
      */
   /* var url = "php/Gestoras.php";
     var parametros = {
          "accion" : 'guardarIntervencion'
      }; // El script a dónde se realizará la petición.
    $.ajax({
           type: "POST",
           url: url,
           data: $("#formularioGestora").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
            alert(data);
               //$("#respuesta").html(data); // Mostrar la respuestas del script PHP.
               if(data.error == 1)
                {
                  alert("se presento un error");
                }
                else
                {
                  alert("guardado");
                }   
           }
         });*/

    //return false; // Evitar ejecutar el submit del formulario.
 //});