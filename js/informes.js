$(function () {
  getInforme();

  $('#estrategia').change(() => {
    getTacticos($('#estrategia').val()[0]);
  });

  $('select').multipleSelect();

  $('.chartContainer').resize(() => {
    myChart.reflow();
  });

  $('#formInformes select').change(() => {
    getInforme();
  });
});

/* $('select').change(() => {

  var filter = {
    zona: $('#zona').val(),
    municipio: $('#municipio').val(),
    comportamiento: $('#comportamiento').val(),
    estrategia: $('#estrategia').val(),
    tactico: $('#tactico').val(),
    tipo: $('#tipo').val()
  }

  getConsultaFilters(filter);
}); */

Highcharts.setOptions({
  lang: {
    downloadCSV: "Descargar CSV",
    downloadJPEG: "Descargar imagen JPEG",
    downloadPDF: "Descargar documento PDF",
    downloadPNG: "Descargar imagen PNG",
    downloadSVG: "Descargar imagen SVG",
    downloadXLS: "Descargar XLS",
    printChart: "Imprimir grafica",
    viewData: "Ver en tabla",
    openInCloud: "Abrir en nube HighCharts"
  }
});


var myChart = Highcharts.chart("chart1", {
  chart: {
    type: 'bar'
  },
  title: {
      text: 'Cobertura de competencias por zona'
  },
  xAxis: {
      categories: ['Preservacion', 'Corresponsabilidad', 'Confianza']
  },
  yAxis: {
      min: 0,
      title: {
          text: 'Zonas'
      }
  },
  legend: {
      reversed: true
  },
  plotOptions: {
      series: {
          stacking: 'normal'
      }
  }
});

/* function getInformes() {
  $.ajax({
    type: "POST",
    url: "server/getInformes.php",
    data: {
      cobZona: ""
    },
    dataType: "json",
    success: function (response) {
      response.forEach(element => {
        myChart.addSeries({
          name: element.name,
          data: element.data
        });
      });
    },
    cache: false
  })
} */

function getConsultaFilters(filter) {
  $.ajax({
    type: "POST",
    url: "server/getInformes.php",
    data: filter,
    dataType: "json",
    success: function (response) {
      var myChart = Highcharts.chart("chart1", {
        chart: {
          type: "bar",
        },
        title: {
          text: "Consolidado Cobertura"
        },
        xAxis: {
          categories: ["Participantes"]
        },
        yAxis: {
          title: {
            text: "Zona"
          }
        },

      });
    }
  });
}

function getInforme(){
  var formInformes =
  $.ajax({
    type: "POST",
    url: "server/getInformes.php",
    data: $('#formInformes').serialize(),
    dataType: "json",
    success: function (response) {
      var chart = $('#chart1').highcharts();
      var seriesLength = chart.series.length;
      for(var i = seriesLength -1; i > -1; i--) {
          chart.series[i].remove();
      }
      response.forEach(element => {
        myChart.addSeries({
          name: element.name,
          data: element.data.map(Number)
        });
      });
    }
  });
}

function getTacticos(estrat){
  $('#tactico').html('');
  $.ajax({
    type: "POST",
    url: "server/getInformes.php",
    data: {
      getTact : estrat
    },
    dataType: "json",
    success: function (response) {
      response.forEach(element => {
        $('#tactico').append(
          `<option value="${element.tactico}">${element.tactico}</option>`
        );
      });
    }
  }).done(() => {
    $('#tactico').prop('disabled', false);
    $('#tactico').multipleSelect();
  })
}