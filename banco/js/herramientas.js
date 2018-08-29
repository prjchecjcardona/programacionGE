$(function() {
  $(document).foundation();
  getCompetencias();
  getZonas();
  getIndicadores();
  getFicheros();



  /* ONCLICKS */
  $(".a_download").click(function() {
    var url = window.location.hash.substr(1);
    getPDF(url);
  });
});

function load() {
  $("body").addClass("animated fadeIn");
}

$(".sf").change(function() {
  var competencia = $("#sfichero_competencia").val();
  var tema = $("#sfichero_tema").val();
  var zona = $("#sfichero_zona").val();
  var indicador = $("#sfichero_indicador").val();

  getFicheros(competencia, tema, zona, indicador);
});

$(".filtros_competencia").change(function() {
  var competencia = $(this).val();
  getTemas(competencia);
});

/* Llama funcion por cada cambio en el filtro */
$(".fp").change(function() {
  var competencia = $("#competencia").val();
  var tema = $("#tema").val();
  var zona = $("#zona").val();
  var indicador = $("#indicador").val();

  getFicheros(competencia, tema, zona, indicador);
});

function getPDF(src) {
  $("#pdf").html(
    `<embed src="${src}" type="application/pdf" width="90%" height="90%">`
  );
}

function getCompetencias() {
  $(".filtros_competencia").html('<option value="0" >No filtro</option>');
  $.ajax({
    type: "POST",
    url: "server/getCompetencias.php",
    data: "",
    dataType: "json",
    success: function(response) {
      response.forEach(element => {
        var comp = element.competencia;
        var first = comp.charAt();
        $(".filtros_competencia").append(
          `<option value="${element.id_competencia}"> ${first} - ${
            element.competencia
          } </option>`
        );
      });
    }
  });
}

function getZonas() {
  $(".filtros_zona").html('<option value="0" >No filtro</option>');
  $.ajax({
    type: "POST",
    url: "server/getZonas.php",
    data: "",
    dataType: "json",
    success: function(response) {
      response.forEach(element => {
        $(".filtros_zona").append(
          `<option value="${element.id_zona}"> Z${element.id_zona} - ${
            element.zonas
          } </option>`
        );
      });
    }
  });
}

function getTemas(competencia) {
  $(".filtros_tema").html('<option value="0" >No filtro</option>');
  $.ajax({
    type: "POST",
    url: "server/getTemas.php",
    data: {
      competencia: competencia
    },
    dataType: "json",
    success: function(response) {
      response.forEach(element => {
        $(".filtros_tema").append(
          `<option value="${element.id_temas}"> ${element.id_temas} - ${
            element.temas
          } </option>`
        );
      });
    }
  });
}

function getIndicadores() {
  $(".filtros_indicador").html('<option value="0" >No filtro</option>');
  $.ajax({
    type: "POST",
    url: "server/getIndicadores.php",
    data: "",
    dataType: "json",
    success: function(response) {
      response.forEach(element => {
        var ind = element.indicador;
        var first = ind.charAt();
        $(".filtros_indicador").append(
          `<option value="${element.id_indicador}"> ${first} - ${
            element.indicador
          } </option>`
        );
      });
    }
  });
}

function getFicheros(competencia, tema, zona, indicador) {
  $("#area-ficheros").html(
    `<img id="codificacion-img" src="img/codificacion.PNG" alt="">`
  );
  $("#pdf").html("");
  $.ajax({
    type: "POST",
    url: "server/getFicheros.php",
    data: {
      competencia: competencia,
      tema: tema,
      zona: zona,
      indicador: indicador
    },
    dataType: "json",
    success: function(response) {
      response.forEach(element => {
        $("#area-ficheros").append(
          `<a class="a_download" href="#${element.fichero_url}">${
            element.codigo
          }</a>`
        );
      });

      fichas();
      htmlString = $("#area-ficheros").text();
      if (htmlString.trim() == "") {
        $("#area-ficheros").html(
          '<p class="p-card">No hay ficheros para este filtro</p>'
        );
        $("#pdf").html('<p class="p-card">No hay pdfs</p>');
      }
    }
  });
}

function getFicheroCodigo(competencia, tema, zona, indicador) {
  $.ajax({
    type: "POST",
    url: "server/getFicheros.php",
    data: {
      competencia: competencia,
      tema: tema,
      zona: zona,
      indicador: indicador
    },
    dataType: "json",
    success: function(response) {
      response.forEach(element => {
        $("#area-ficheros").append(
          `<a class="a_download" href="#${element.fichero_url}">${
            element.codigo
          }</a>`
        );
      });

      fichas();
      htmlString = $("#area-ficheros").text();
      if (htmlString.trim() == "") {
        $("#area-ficheros").html(
          '<p class="p-card">No hay ficheros para este filtro</p>'
        );
        $("#pdf").html('<p class="p-card">No hay pdfs</p>');
      }
    }
  });
}

function fichas() {
  $(".a_download").click(function() {
    if ($(".a_download").hasClass("selected")) {
      $(".a_download").removeClass("selected");
      $(this).addClass("selected");
    } else {
      $(this).addClass("selected");
    }
    var url = window.location.hash.substr(1);
    getPDF(url);
  });
}