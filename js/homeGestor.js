$(function() {
  checkLogged();
  getMunicipioXZona();

  $('#logOut a').click(function(){
    $('#logOut').submit();
  });
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
                  <a href="registrarFocalizacionG.html?id_zona=${element.id_zona}&id_mun=${element.id_municipio}" class="btn btn-primary"><i class="fas fa-plus crear"></i> Focalizar</a>
                  <a href="#" class="btn btn-primary"><i class="fas fa-plus crear"></i> Trabajo Administrativo</a>
                </div>
              </div>
              <a href="#${element.municipio}" onClick="getFocalizacionesXZona(${element.id_municipio})"><i class="fas fa-arrow-circle-right arrow"></i></a>
            </div>`
        );
      });
    },
    complete: function(){
      $('.municipios').fadeIn();
      $('.municipios').removeClass('showNone');
      $('#homeBreadCrumbs').removeClass('showNone');
      $('#loaderList').fadeOut();
    }
  })
}

function getFocalizacionesXZona(mun){
  $('#loaderList').fadeIn();
  $('.municipios').fadeOut();
  $.ajax({
    type: "POST",
    url: "server/getFocalizaciones.php",
    data: {
      municipio : mun
    },
    dataType: "json",
    success: function(data){
      $('.focalizaciones').html(
        `<a href="#" id="returnBtn" class="btn btn-success" onClick="returnMunicipio()"><i class="fas fa-arrow-circle-left arrow"></i></a>`
      );
      data.forEach(element => {
        $(".focalizaciones").append(
          `<div>
            <div class="card">
              <div class="card-header">
                ${element.fecha}
              </div>
              <div class="card-body">
                <h5 class="card-title">${element.comportamientos} - ${element.competencia}</h5>
                <p>Tipo de focalización: ${element.tipo_focalizacion}</p>
                <a href="#" class="btn btn-primary"><i class="fas fa-plus crear"></i> Planear</a>
              </div>
            </div>
            <a href="registrarPlaneacionG.html?id_zona" onclick="getPlaneacionesXFocalizacion(${element.id_focalizacion})"><i class="fas fa-arrow-circle-right arrow"></i></a>
          </div>`
        );
      });
    },
    complete: function(){
      $('.focalizaciones').fadeIn();
      $('.focalizaciones').removeClass('showNone');
      $('#loaderList').fadeOut();
    }
  })
}

function getPlaneacionesXFocalizacion(foc){
  $.ajax({
    type: "POST",
    url: "server/getPlaneaciones.php",
    data: {
      foc : foc
    },
    dataType: "json",
    success: function (data) {

    }
  });
}

function returnMunicipio(){
  $('.focalizaciones').fadeOut();
  $('.municipios').fadeIn();
}

function checkLogged(){

  $.ajax({
    type: "POST",
    url: "server/checkLog.php",
    data: {
      zona : id_zona
    },
    dataType: "json"
  }).done(function(data){
    if(data.error){
      swal({
        type: 'info',
        title: 'Usuario',
        text: data.message,
      }).then(function(){
        window.location.href = "iniciarSesion.html";
      });
    }else{
      $('#userName').html(
        `Hola ${data}`
      );
    }
  });
}
