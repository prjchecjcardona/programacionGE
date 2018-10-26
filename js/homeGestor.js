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
    dataType: "json"
  }).done(function(data) {
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
            <a href="#${element.municipio}" onClick="getFocalizacionesXZona(${id_zona})"><i class="fas fa-arrow-circle-right arrow"></i></a>
          </div>`
      );
    });
  });
}

function getFocalizacionesXZona(zona){
  $.ajax({
    type: "POST",
    url: "server/getFocalizaciones.php",
    data: {
      zona : zona
    },
    dataType: "json",
    success: function (response) {
      
    }
  });
}
