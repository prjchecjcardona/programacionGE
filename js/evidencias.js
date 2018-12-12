$(function() {});

var uploadEF = new FileUploadWithPreview("evidenciasFotograficas");
var uploadAsis = new FileUploadWithPreview("evidenciasAsistencia");

function uploadAsistencias() {
  var form = new FormData();

  /* Ciclo de evidencias fotograficas */
  for (i = 0; i < uploadEF.cachedFileArray.length; i++) {
    element = uploadEF.cachedFileArray[i];
    console.log(element);
    form.append('evidencias[]', element);
  }

  /* Ciclo de asistencias */
  for (i = 0; i < uploadAsis.cachedFileArray.length; i++) {
    element = uploadAsis.cachedFileArray[i];
    console.log(element);
    form.append('asistencias[]', element);
  }

  $.ajax({
    type: "POST",
    url: "server/uploadRegistros.php",
    data: form,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function(response) {}
  });
}

function validarRegistros(){
  
}
