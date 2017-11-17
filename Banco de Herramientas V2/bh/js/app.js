var selectedPage = "inicio";


function loadComponents() {
  $('#navbar').html(getNavbar());
  $('#col-izq').html(getColIzq());
  $('#main').html(getMain(selectedPage));
}

function setHoverIcons() {
  $('#inicio').hover(
    function (){
      $('#inicio img').attr('src', 'bh/img/Inicio-01.png');
    },
    function (){
      $('#inicio img').attr('src', 'bh/img/InicioGris-01.png');
    }
  )
  $('#criterios').hover(
    function (){
      $('#criterios img').attr('src', 'bh/img/CriteriosConceptualesVerdes-01.png');
    },
    function (){
      $('#criterios img').attr('src', 'bh/img/CriteriosConceptualesGris-01.png');
    }
  )
  $('#generalidades').hover(
    function (){
      $('#generalidades img').attr('src', 'bh/img/GeneralidadesVerde-01.png');
    },
    function (){
      $('#generalidades img').attr('src', 'bh/img/GeneralidadesGris-01.png');
    }
  )
  $('#herramientas').hover(
    function (){
      $('#herramientas img').attr('src', 'bh/img/HerramientasDidácticasVerde-01.png');
    },
    function (){
      $('#herramientas img').attr('src', 'bh/img/HerramientasDidácticasGris-01.png');
    }
  )
  $('#glosario').hover(
    function (){
      $('#glosario img').attr('src', 'bh/img/GlosarioVerde-01.png');
    },
    function (){
      $('#glosario img').attr('src', 'bh/img/GlosarioGris-01.png');
    }
  )
  $('#recurso').hover(
    function (){
      $('#recurso img').attr('src', 'bh/img/GlosarioVerde-01.png');
    },
    function (){
      $('#recurso img').attr('src', 'bh/img/GlosarioGris-01.png');
    }
  )
}

function getHeight() {
  var h = window.innerHeight;
  h = h - 80;
  $('#col-izq, #main').css({
    "height": h
  })
}

function getSelectedPage() {
  var selection = {
    "color": "#00782B",
    "background-color": "#EAEAEA"
  };
  var unSelected = {
    "color": "#A1A1A5",
    "background-color": "#fff"
  }
  
  setHoverIcons();
  $('#main').html(getMain(selectedPage)).show('fade', 100, function(){
    switch (selectedPage) {
      case "criterios":
      criteriosSelected(selection, unSelected);
      break;
      case "generalidades":
      generalidadesSelected(selection, unSelected);
      break;
      case "herramientas":
      herramientasSelected(selection, unSelected);
      break;
      case "glosario":
      glosarioSelected(selection, unSelected);
      break;
      case "recurso":
      recursoSelected(selection, unSelected);
      break;
      default:
      inicioSelected(selection, unSelected);
    }

  });
}


function assignEvents(){
  $('#inicio').click(function(){
    selectedPage = "inicio";
    $('#main').hide('fade', 100, function() {
      $(this).empty();
      getSelectedPage();
    });
  })
  $('#criterios').click(function(){
    selectedPage = "criterios";
    $('#main').hide('fade', 100, function() {
      $(this).empty();
      getSelectedPage();
    });
  })
  $('#generalidades').click(function(){
    selectedPage = "generalidades";
    $('#main').hide('fade', 100, function() {
      $(this).empty();
      getSelectedPage();
    });
  })
  $('#herramientas').click(function(){
    selectedPage = "herramientas";
    $('#main').hide('fade', 100, function() {
      $(this).empty();
      getSelectedPage();
    });
  })
  $('#glosario').click(function(){
    selectedPage = "glosario";
    $('#main').hide('fade', 100, function() {
      $(this).empty();
      getSelectedPage();
    });
  })
  $('#recurso').click(function(){
    selectedPage = "recurso";
    $('#main').hide('fade', 100, function() {
      $(this).empty();       
      getSelectedPage();
    });
  })
}


function inicioSelected(selection, unSelected) {
  $(".lista-opciones a").css(unSelected);
  $("#inicio").css(selection);
  $("#inicio").unbind('mouseenter mouseleave');
  $('#criterios img').attr('src', 'bh/img/CriteriosConceptualesGris-01.png');
  $('#generalidades img').attr('src', 'bh/img/GeneralidadesGris-01.png');
  $('#herramientas img').attr('src', 'bh/img/HerramientasDidácticasGris-01.png');
  $('#glosario img').attr('src', 'bh/img/GlosarioGris-01.png');
  $('#recurso img').attr('src', 'bh/img/GlosarioGris-01.png');  
  $('#main .welcome-container h1').show('slide', 2000);
}
function glosarioSelected(selection, unSelected) {
  $(".lista-opciones a").css(unSelected);
  $("#glosario").css(selection);
  $("#glosario").unbind('mouseenter mouseleave');
  $('#criterios img').attr('src', 'bh/img/CriteriosConceptualesGris-01.png');
  $('#generalidades img').attr('src', 'bh/img/GeneralidadesGris-01.png');
  $('#herramientas img').attr('src', 'bh/img/HerramientasDidácticasGris-01.png');
  $('#recurso img').attr('src', 'bh/img/GlosarioGris-01.png');
  $('#inicio img').attr('src', 'bh/img/InicioGris-01.png');
}
function herramientasSelected(selection, unSelected) {
  $(".lista-opciones a").css(unSelected);
  $("#herramientas").css(selection);
  $("#herramientas").unbind('mouseenter mouseleave');
  $('#criterios img').attr('src', 'bh/img/CriteriosConceptualesGris-01.png');
  $('#generalidades img').attr('src', 'bh/img/GeneralidadesGris-01.png');
  $('#glosario img').attr('src', 'bh/img/GlosarioGris-01.png');
  $('#recurso img').attr('src', 'bh/img/GlosarioGris-01.png');
  $('#inicio img').attr('src', 'bh/img/InicioGris-01.png');
 /* $('#comp').change(function(){
    $('.firstHide').hide('fade');
  })
  $('#tema').change(function(){
    $('.secondHide').hide('fade');
  })
  $('#openFile').click(function(){
    $('.ficheros-area')
      .empty()
      .html(`
        <embed src="bh/ficheros/2-Respeto-Cultura_de pago-Interpretacion_de_la_factura.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">
        `)
  })*/
  $('#comp').on('change', function() {
    var selected = $(this).val();
     if(selected=='2')
     {
           $('div#first').show();
           $('div#second').hide("fast");
           $('div#third').hide("fast");
           $('div#fourth').hide("fast");
     }
     else
     {
        if(selected=='3')
        {
           $('div#second').show();
            $('div#first').hide("fast");
            $('div#third').hide("fast");
            $('div#fourth').hide("fast");
        }
        else
        {
            if(selected=='4')
            {
               $('div#third').show();
               $('div#second').hide("fast");
               $('div#first').hide("fast");
               $('div#fourth').hide("fast");
            }
            else
             {
                if(selected=='5')
                {
                   $('div#fourth').show();
                    $('div#first').hide("fast");
                    $('div#third').hide("fast");
                    $('div#second').hide("fast");
                }
              }
        }
      }
    $("#tema option").each(function(item){
      console.log(selected) ;  
      var element =  $(this) ; 
      console.log(element.data("tag")) ; 
      if ((element.data("tag") != selected) && selected !='1'){
        element.hide() ; 
      }else{
        element.show();       
      }
    }) ; 
    
    $("#tema").val($("#tema option:visible:first").val());
    
   }) ;

$('span#1').on('click', function(){
       $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/2-Confianza-Servicios_en_linea.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#2').on('click', function(){
     $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/3-Confianza-Consultas_chat_y_lineas_telefonicas.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#3').on('click', function(){
       $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/1-Confianza-Gobierno_en_linea.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#4').on('click', function(){
     $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/1-Respeto-Alumbrado_publico.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#5').on('click', function(){
       $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/3-Respeto-Cultura_de_la_legalidad.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#6').on('click', function(){
     $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/4-Respeto-Cultura_de_la_legalidad_y_marco_regulatorio.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#7').on('click', function(){
       $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/2-Respeto-Interpretacion_de_la_factura.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#8').on('click', function(){
     $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/1-Preservacion-Ahorro_de_energia.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#9').on('click', function(){
     $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/4-Preservacion-Esquema_tarifario_y_calculo_de_consumo.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#10').on('click', function(){
     $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/2-Preservacion-Estrategias_ecologicas.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#11').on('click', function(){
     $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/3-Preservacion-Prevencion_de_riesgo_electrico.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#12').on('click', function(){
     $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/1-Cuidado-Cuidado_de_lo_publico.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#13').on('click', function(){
     $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/2-Cuidado-Calidad_de_vida_a_traves_del_servicio_de_energia.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#14').on('click', function(){
     $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/3-Cuidado-Ciudadania.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
$('span#15').on('click', function(){
     $('.ficheros-area')
              .empty()
              .html('<embed src="bh/ficheros/4-Cuidado-Territorios_sostenibles.pdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">')
});
        
}

function generalidadesSelected(selection, unSelected) {
  $(".lista-opciones a").css(unSelected);
  $("#generalidades").css(selection);
  $("#generalidades").unbind('mouseenter mouseleave');
  $('#criterios img').attr('src', 'bh/img/CriteriosConceptualesGris-01.png');
  $('#herramientas img').attr('src', 'bh/img/HerramientasDidácticasGris-01.png');
  $('#glosario img').attr('src', 'bh/img/GlosarioGris-01.png');
  $('#recurso img').attr('src', 'bh/img/GlosarioGris-01.png');
  $('#inicio img').attr('src', 'bh/img/InicioGris-01.png');
}
function criteriosSelected(selection, unSelected) {
  $(".lista-opciones a").css(unSelected);
  $("#criterios").css(selection);
  $("#criterios").unbind('mouseenter mouseleave');
  $('#generalidades img').attr('src', 'bh/img/GeneralidadesGris-01.png');
  $('#herramientas img').attr('src', 'bh/img/HerramientasDidácticasGris-01.png');
  $('#glosario img').attr('src', 'bh/img/GlosarioGris-01.png');
  $('#recurso img').attr('src', 'bh/img/GlosarioGris-01.png');
  $('#inicio img').attr('src', 'bh/img/InicioGris-01.png');
}
function recursoSelected(selection, unSelected) {
  $(".lista-opciones a").css(unSelected);
  $("#recurso").css(selection);
  $("#recurso").unbind('mouseenter mouseleave');
  $('#criterios img').attr('src', 'bh/img/CriteriosConceptualesGris-01.png');
  $('#generalidades img').attr('src', 'bh/img/GeneralidadesGris-01.png');
  $('#herramientas img').attr('src', 'bh/img/HerramientasDidácticasGris-01.png');
  $('#glosario img').attr('src', 'bh/img/GlosarioGris-01.png');
  $('#inicio img').attr('src', 'bh/img/InicioGris-01.png');
  //$('#openFile').click(function(){ 
  $('#myList a').on('click', function (e) {
    e.preventDefault()
    $(this).tab('show')
  })
}


function recargar() {  
  //herramientasSelected(selection, unSelected);
  selectedPage = "herramientas";
    $('#main').hide('fade', 100, function() {
      $(this).empty();
      getSelectedPage();
    });
  
}


$(function(){


  loadComponents();
  setHoverIcons();
  getSelectedPage();
  assignEvents();
  getHeight();
})
