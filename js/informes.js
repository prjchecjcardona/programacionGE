$(function () {
  getMunicipiosCobertura();
  getZonasCobertura();

  $('.chartContainer').resize(() => {
    myChart.reflow();
    myChart2.reflow();
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
    type: "bar",
    events: {
      load: getInformes
    }
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
  }
});

var myChart2 = Highcharts.chart("chart2", {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie',
    events: {
      load: getCobEstrategia
    }
  },
  title: {
    text: "Cobertura de participantes por estrategia"
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.y} - {point.percentage:.1f}%</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: false
      },
      showInLegend: true
    }
  }
});

var myChart3 = Highcharts.chart("chart3", {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie',
    events: {
      load: getCobMunicipio('Occidente', 3)
    }
  },
  title: {
    text: "Cobertura de participantes por municipio en Zona Occidente"
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.y} - {point.percentage:.1f}%</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: false
      },
      showInLegend: true
    }
  }
});

var myChart4 = Highcharts.chart("chart4", {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie',
    events: {
      load: getCobMunicipio('Centro', 4)
    }
  },
  title: {
    text: "Cobertura de participantes por municipio en Zona Centro"
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.y} - {point.percentage:.1f}%</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: false
      },
      showInLegend: true
    }
  }
});

var myChart5 = Highcharts.chart("chart5", {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie',
    events: {
      load: getCobMunicipio('Noroccidente', 5)
    }
  },
  title: {
    text: "Cobertura de participantes por municipio en Zona Noroccidente"
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.y} - {point.percentage:.1f}%</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: false
      },
      showInLegend: true
    }
  }
});

var myChart6 = Highcharts.chart("chart6", {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie',
    events: {
      load: getCobMunicipio('Suroccidente', 6)
    }
  },
  title: {
    text: "Cobertura de participantes por municipio en Zona Suroccidente"
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.y} - {point.percentage:.1f}%</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: false
      },
      showInLegend: true
    }
  }
});

var myChart7 = Highcharts.chart("chart7", {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie',
    events: {
      load: getCobMunicipio('Oriente', 7)
    }
  },
  title: {
    text: "Cobertura de participantes por municipio en Zona Oriente"
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.y} - {point.percentage:.1f}%</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: false
      },
      showInLegend: true
    }
  }
});

var myChart8 = Highcharts.chart('chart8', {
  chart: {
      plotBackgroundColor: null,
      plotBorderWidth: 0,
      plotShadow: false,
      events: {
        load: getCobComportamientos
      }
  },
  title: {
      text: 'Cobertura<br>por<br>competencia',
      align: 'center',
      verticalAlign: 'middle',
      y: 40
  },
  tooltip: {
      pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
  },
  plotOptions: {
      pie: {
          dataLabels: {
              enabled: false,
          },
          startAngle: -90,
          endAngle: 90,
          center: ['50%', '75%'],
          size: '110%',
          showInLegend: true
      }
  }
});

var myChart9 = Highcharts.chart("chart9", {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie',
    events: {
      load: getCobAct
    }
  },
  title: {
    text: "Cobertura de participantes por tipo de actividad"
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.y} - {point.percentage:.1f}%</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: false
      },
      showInLegend: true
    }
  }
});


/* var myChart3 = Highcharts.chart("chart3", {
  chart: {
    type: "bar",
    events: {
      load: getInformes
    }
  },
  title: {
    text: "Consolidado Cobertura"
  },
  xAxis: {
    categories: ["Participantes"]
  },
  yAxis: {
    title: {
      text: "Participantes por zona"
    }
  }
}); */

function getInformes() {
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
}

function getCobAct(){
  $.ajax({
    type: "POST",
    url: "server/getInformes.php",
    data: {
      cobAct: ""
    },
    dataType: "json",
    success: function (response) {
      myChart9.addSeries({
        name: 'Participantes',
        colorByPoint: true,
        data: response
      });
    },
    cache: false
  })
}

function getCobMunicipio(zona, chart){
  $.ajax({
    type: "POST",
    url: "server/getInformes.php",
    data: {
      cobMun: zona
    },
    dataType: "json",
    success: function (response) {
      if(chart == 4){
        myChart4.addSeries({
          name: 'Participantes',
          colorByPoint: true,
          data: response
        });
      }

      if(chart == 5){
        myChart5.addSeries({
          name: 'Participantes',
          colorByPoint: true,
          data: response
        });
      }

      if(chart == 3){
        myChart3.addSeries({
          name: 'Participantes',
          colorByPoint: true,
          data: response
        });
      }

      if(chart == 6){
        myChart6.addSeries({
          name: 'Participantes',
          colorByPoint: true,
          data: response
        });
      }

      if(chart == 7){
        myChart7.addSeries({
          name: 'Participantes',
          colorByPoint: true,
          data: response
        });
      }
    },
    cache: false
  })
}

function getCobComportamientos(){
  $.ajax({
    type: "POST",
    url: "server/getInformes.php",
    data: {
      cobComp: ""
    },
    dataType: "json",
    success: function (response) {
      myChart8.addSeries({
        type: 'pie',
        name: 'Participantes',
        innerSize: '50%',
        data: response
      });
    },
    cache: false
  })
}

function getCobEstrategia() {
  $.ajax({
    type: "POST",
    url: "server/getInformes.php",
    data: {
      cobEstrategia: ""
    },
    dataType: "json",
    success: function (response) {
      myChart2.addSeries({
        name: 'Participantes',
        colorByPoint: true,
        data: response
      });
    },
    cache: false
  })
}

function getMunicipiosCobertura() {
  $.ajax({
    type: "POST",
    url: "server/getMunicipios.php",
    data: {
      zona: "all"
    },
    dataType: "json",
    success: function (response) {
      response.forEach(element => {
        $('#municipio').append(
          `<option value="${element.id_municipio}">${element.municipio}</option>`
        )
      });
    }
  }).done(() => {
    $('select').multipleSelect();
  });
}

function getZonasCobertura() {
  $.ajax({
    type: "POST",
    url: "server/getZonas.php",
    data: '',
    dataType: "json",
    success: function (response) {
      response.forEach(element => {
        $('#zona').append(
          `<option value="${element.zonas}">${element.zonas}</option>`
        )
      });
    }
  }).done(() => {
    $('select').multipleSelect();
  });
}

function getEstrategiasCobertura() {
  $.ajax({
    type: "POST",
    url: "server/getInformes.php",
    data: '',
    dataType: "json",
    success: function (response) {
      response.forEach(element => {
        $('#zona').append(
          `<option value="${element.id_estrategia}">${element.nombre_estrategia}</option>`
        )
      });
    }
  }).done(() => {
    $('select').multipleSelect();
  });
}

function getTacticosCobertura(estrategia) {
  $('#zona').prop("disabled", true);
  $.ajax({
    type: "POST",
    url: "server/getInformes.php",
    data: {
      estrategia: estrategia
    },
    dataType: "json",
    success: function (response) {
      response.forEach(element => {
        $('#zona').append(
          `<option value="${element.id_tactico}">${element.nombre_tactico}</option>`
        )
      });
    }
  }).done(() => {
    $('select').multipleSelect();
    $('#zona').prop("disabled", false);
  });
}

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

function openNav() {
  document.getElementById("mySidenav").style.width = "450px";
  document.getElementsByClassName("main")[0].style.marginLeft = "450px";
  setTimeout(() => {
    $('.chartContainer').resize();
  }, 800);
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  document.getElementsByClassName("main")[0].style.marginLeft = "0";
  setTimeout(() => {
    $('.chartContainer').resize();
  }, 800);
}