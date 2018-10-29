$(function() {
  getMunicipioXZona();
});

var id_zona = getZona("id_zona");

function getZona(param) {
  param = param.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + param + "=([^&#]*)");
  var results = regex.exec(location.search);
  return results === null
    ? ""
    : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function getMunicipioXZona() {
  $.ajax({
    type: "POST",
    url: "server/getMunicipios.php",
    data: {
      zona: id_zona
    },
    dataType: "json",
    success: function(data){
      data.forEach(element => {
        $(".municipios").append(
          ` <div>
              <div class="card">
                <div class="card-header">
                  ${element.zonas}
                </div>
                <div class="card-body">
                  <h5 class="card-title">${element.municipio}</h5>
                  <a href="#" class="btn btn-primary"><i class="fas fa-plus crear"></i> Focalizar</a>
                  <a href="#" class="btn btn-primary"><i class="fas fa-plus crear"></i> Planear</a>
                  <a href="#" class="btn btn-primary"><i class="fas fa-plus crear"></i> Ejecutar</a>
                  <a href="#" class="btn btn-primary"><i class="fas fa-plus crear"></i> Trabajo Administrativo</a>
                </div>
              </div>
              <a href="#${element.municipio}" onClick="getFocalizacionesXZona(${element.id_municipio})"><i class="fas fa-arrow-circle-right arrow"></i></a>
            </div>`
        );
      });
    },
    complete: function(){
      $('.municipios').removeClass('showNone');
      $('.municipios').addClass('fadeIn');
      $('#homeBreadCrumbs').removeClass('showNone');
      hideLoader();
    }
  })
}

function showLoader(){
  $('.center').removeClass('showNone')
}

function hideLoader(){
  $('.center').addClass('showNone');
}

function getFocalizacionesXZona(mun){
  showLoader();
  $('.municipios').addClass('showNone');
  $.ajax({
    type: "POST",
    url: "server/getFocalizaciones.php",
    data: {
      municipio : mun
    },
    dataType: "json",
    success: function(data){
      $('.focalizaciones').html('');
      data.forEach(element => {
        $(".focalizaciones").append(
          `<div>
            <a href="#"><i class="fas fa-arrow-circle-left arrow"></i></a>
            <div class="card">
              <div class="card-header">
                ${element.fecha}
              </div>
              <div class="card-body">
                <h5 class="card-title">${element.comportamientos} - ${element.competencia}</h5>
                <p>Tipo de focalización: ${element.tipo_intervencion}</p>
                <a href="#" class="btn btn-primary">Go somewhere</a>
              </div>
            </div>
            <a href="#"><i class="fas fa-arrow-circle-right arrow"></i></a>
          </div>`
        )
      });
    },
    complete: function(){
      $('.focalizaciones').removeClass('showNone');
      $('.focalizaciones').addClass('fadeIn');
      hideLoader();
    }
  })
}
