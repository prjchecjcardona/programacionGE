$(function() {
  /* FOUNDATION */
  $(document).foundation();

  /* Funciones */
  getRecursos();

  /* Click functions */
  $(".recursos-button").click(function() {
    getListaRecursos();
  });

  $("input[name=archivo]").change(function() {
    getFileNames();
  });
});

function load() {
  $("body").addClass("animated fadeIn");
}

function getRecursos() {
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
  $("#select_recursos").html("");
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
  /* Tomar un array con los nombre los archivos */
  var x = document.getElementById("button-subir_recurso");
  var fileName = "";
  var files = [];

  if ("files" in x) {
    var inputLength = x.files.length;
    for (var i = 0; i < inputLength; i++) {
      files.push(x.files[i].name);
    }
  }

  var formData = {
    archivo: files, /* Sends array files through POST */
    recurso: $("select[name=recurso]").val()
  };

  $.ajax({
    type: "POST",
    url: "server/upload.php",
    data: formData,
    dataType: "json",
    encode: true
  }).done(function(data) {
    console.log(data);
  });

  event.preventDefault();
});

function getFileNames() {
  var x = document.getElementById("button-subir_recurso");
  var fileName = "";

  if ("files" in x) {
    var inputLength = x.files.length;
    for (var i = 0; i < inputLength; i++) {
      var fileInput = x.files[i].name;
      console.log(inputLength + fileInput);
    }
  }
}
