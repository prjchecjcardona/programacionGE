$(function() {});

var uploadEF = new FileUploadWithPreview("evidenciasFotograficas");
var uploadAsis = new FileUploadWithPreview("evidenciasAsistencia");

var evidencias = function getArray() {
  var evidencias_fotograficas = [];
  var asistencias = [];

  /* Ciclo de evidencias fotograficas */
  for (let i = 0; i < uploadEF.cachedFileArray.length; i++) {
    element = uploadEF.cachedFileArray[i];
    evidencias_fotograficas.push(element);
  }

  /* Ciclo de asistencias */
  for (let i = 0; i < uploadAsis.cachedFileArray.length; i++) {
    element = uploadAsis.cachedFileArray[i];
    asistencias.push(element);
  }

  return {
    ef : evidencias_fotograficas,
    asis : asistencias
  }
}

function uploadAsistencias() {
  var evidencia = new evidencias();

  var formData = new FormData();
  formData.append('ef', evidencia.ef);
  formData.append('asis', evidencia.asis);
  $.ajax({
    type: "POST",
    url: "server/uploadRegistros.php",
    data: formData,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function(response) {}
  });
}
