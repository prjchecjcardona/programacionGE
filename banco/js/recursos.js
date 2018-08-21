$(function() {
  /* FOUNDATION */
  $(document).foundation();

  /* Funciones */
  getRecursos();

  /* Click functions */
  $('.recursos-button').click(function(){
    getListaRecursos();
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
      response.forEach((element) => {
        $(`#${element.id_recurso}`).append(
          `<a id="a-download" href="${element.recurso_url}" download>
            <div class="card">  
              <i class="${element.icon}"></i>  
              <div class="card-section">
                <p class="p-card"> ${element.nombre_archivo} </p>
              </div>
            </div>  
          </a>`
        );
      });

      for(var i=1; i<8; i++){
        htmlString = $('#'+ i +'').text();
        if(htmlString.trim() == ""){
          $('#'+ i +'').html(
            '<p class="p-card">No se han cargado archivos en el modúlo</p>'
          );
        }
      }
    }
  });
}

function getListaRecursos(){
  $('#select_recursos').html("");
  $.ajax({
    type: "POST",
    url: "server/getListaRecursos.php",
    dataType: "json",
    success: function(response) {
      response.forEach((element) => {
        $('#select_recursos').append(
          `<option value="${element.id_recurso}">${element.recurso}</option>`
        );
      });

      for(var i=1; i<8; i++){
        htmlString = $('#'+ i +'').text();
        if(htmlString.trim() == ""){
          $('#'+ i +'').html(
            '<p class="p-card">No se han cargado archivos en el modúlo</p>'
          );
        }
      }
    }
  });
}
