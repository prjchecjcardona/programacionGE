$(function() {
  /* FOUNDATION */
  $(document).foundation();

  /* Funciones */
  getRecursos();

  /* Click functions */
  $(".recursos-button").click(function() {
    getListaRecursos();
  });
});

function load() {
  $("body").addClass("animated fadeIn");
}

function getRecursos() {
  $('.tabs-panel').html("");
  $.ajax({
    type: "POST",
    url: "server/getRecursos.php",
    dataType: "json",
    success: function(response) {
      response.forEach(element => {
        $(`#${element.id_recurso}`).append(
          `<div class="card-test">
            <div class="header">
              ${element.nombre_archivo}
            </div>
            <div class="content">
              <img class="img-recursos" src="img/${element.icon}">
              <div class="botones-pdf">
              <a href="${element.recurso_url}" target="_blank">
                <button class="hollow button recursos-button">Ver</button>
              </a>
              <a href="${element.recurso_url}" download>
                <button class="hollow button recursos-button">Descargar</button>
              </a>
            </div>
          </div>
        </div>`
        );
      });

      for (var i = 1; i < 8; i++) {
        htmlString = $("#" + i + "").text();
        if (htmlString.trim() == "") {
          $("#" + i + "").html(
            '<p class="p-card">No se han cargado archivos en el modúlo</p>'
          );
        }
      }
    }
  });
}

function getListaRecursos() {
  $.ajax({
    type: "POST",
    url: "server/getListaRecursos.php",
    dataType: "json",
    success: function(response) {
      response.forEach(element => {
        $("#select_recursos").append(
          `<option value="${element.id_recurso}">${element.recurso}</option>`
        );
      });

      for (var i = 1; i < 8; i++) {
        htmlString = $("#" + i + "").text();
        if (htmlString.trim() == "") {
          $("#" + i + "").html(
            '<p class="p-card">No se han cargado archivos en el modúlo</p>'
          );
        }
      }
    }
  });
}

$("#form-recursos").submit(function(event) {
  event.preventDefault();
  $.ajax({
    type: "POST",
    url: "server/upload.php",
    data: new FormData(this),
    dataType: "json",
    encode: true,
    contentType: false,
    processData: false
  }).done(function(data) {
    if(data.success){
      swal({
        title: "Listo!",
        text: data.message,
        icon: "success",
        button: "Ok",
      }).then(()=>{
        getRecursos();
      });
    }else{
      swal({
        title: "Oh-oh!",
        text: data.message,
        icon: "error",
        button: "Ok",
      }).then(()=>{
        getRecursos();
      });
    }
  });
});
