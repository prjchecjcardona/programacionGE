$(function () {
    $('#exampleRadio2').prop('checked', true)
    initElements();
    getInitialData();
});


function traerNombre(){ 
    
        $.post("php/CapturaVariableSession.php",{
               accion:'traerNombre'
               
                                  
             },
             function (data) {
                if (data !=	""){		
                    $('#Nombre').html(data);
                }
                else{
                    swal(
                      '', //titulo
                      'Debes iniciar sesion!',
                      'error'
                    );
                    window.location.href = "welcome_Coordinadora.html";
                }
            }
              ,"json");
    
    }
function initElements(){
    traerNombre();

    $('#multiple-mes').multiselect({
        enableFiltering: true,
        filterPlaceholder: 'Buscar',
        includeSelectAllOption: true,
        selectAllText: 'Seleccionar Todos',
        buttonText: function(options, select) {
            return 'Seleccione Opción'; 
        },
        onChange: function(option, checked, select) {
            filterByMonth($(option).val());
        }
    });
    
    $('#multiple-anio').multiselect({
        enableFiltering: true,
        filterPlaceholder: 'Buscar',
        includeSelectAllOption: true,
        selectAllText: 'Seleccionar Todos',
        buttonText: function(options, select) {
            return 'Seleccione Opción';
        }
    });
    
    $('#multiple-municipio').multiselect({
        enableFiltering: true,
        filterPlaceholder: 'Buscar',
        includeSelectAllOption: true,
        selectAllText: 'Seleccionar Todos',
        buttonText: function(options, select) {
            return 'Seleccione Opción';
        }
    });
    $('#multiple-zona').multiselect({
        enableFiltering: true,
        filterPlaceholder: 'Buscar',
        includeSelectAllOption: true,
        selectAllText: 'Seleccionar Todos',
        buttonText: function(options, select) {
            return 'Seleccione Opción';
        }
    });
    $('#multiple-entidad').multiselect({
        enableFiltering: true,
        filterPlaceholder: 'Buscar',
        includeSelectAllOption: true,
        selectAllText: 'Seleccionar Todos',
        buttonText: function(options, select) {
            return 'Seleccione Opción';
        }
    });
    $('#multiple-comportamiento').multiselect({
        enableFiltering: true,
        filterPlaceholder: 'Buscar',
        includeSelectAllOption: true,
        selectAllText: 'Seleccionar Todos',
        buttonText: function(options, select) {
            return 'Seleccione Opción';
        }
    });
    $('#multiple-estrategia').multiselect({
        enableFiltering: true,
        filterPlaceholder: 'Buscar',
        includeSelectAllOption: true,
        selectAllText: 'Seleccionar Todos',
        buttonText: function(options, select) {
            return 'Seleccione Opción';
        }
    });
    $('#multiple-tactico').multiselect({
        enableFiltering: true,
        filterPlaceholder: 'Buscar',
        includeSelectAllOption: true,
        selectAllText: 'Seleccionar Todos',
        buttonText: function(options, select) {
            return 'Seleccione Opción';
        }
    });
    
    
    Highcharts.setOptions({
        lang: {
            months: [
                'Janvier', 'Février', 'Mars', 'Avril',
                'Mai', 'Juin', 'Juillet', 'Août',
                'Septembre', 'Octobre', 'Novembre', 'Décembre'
            ],
            weekdays: [
                'Dimanche', 'Lundi', 'Mardi', 'Mercredi',
                'Jeudi', 'Vendredi', 'Samedi'
            ],
    
            shortMonths: [
                'Ene', 'Feb', 'Mar', 'Abr',
                'May', 'Jun', 'Jul', 'Ago',
                'Sep', 'Oct', 'Nov', 'Dic'
            ],
    
            downloadPNG: "Descargar en Formato PNG",
            downloadPDF: "Descargar en Formato PDF",
            downloadSVG: "Descargar en Formato SVG",
            downloadJPEG: "Descargar en formato JPEG",
            printChart: "Imprimir Tablero",
    
    
    
        }
    });
    
   
    
 
    var MomentoIII = Highcharts.chart('pie_coordinadora', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Porcentaje de cobertura según Estrategia, Septiembre 2017'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Porcentaje',
            colorByPoint: true,
            data: [{
                name: 'PRESERVACIÓN',
                y: 44
            }, {
                name: 'RESPETO',
                y: 21
            }, {
                name: 'CUIDADO',
                y: 18
            }, {
                name: 'CONFIANZA',
                y: 16
            }]
        }]
    });
    
    var MomentoIV = Highcharts.chart('lines_coordinadora', {
    
        title: {
            text: 'Solar Employment Growth by Sector, 2010-2016'
        },
    
        subtitle: {
            text: 'Source: thesolarfoundation.com'
        },
    
        yAxis: {
            title: {
                text: 'Number of Employees'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
    
        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                pointStart: 2010
            }
        },
    
        series: [{
            name: 'Installation',
            data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
        }, {
            name: 'Manufacturing',
            data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
        }, {
            name: 'Sales & Distribution',
            data: [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387]
        }, {
            name: 'Project Development',
            data: [null, null, 7988, 12169, 15112, 22452, 34400, 34227]
        }, {
            name: 'Other',
            data: [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111]
        }],
    
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    
    });
    
   /*  var MomentoV = Highcharts.chart('lines2_coordinadora', {
    
        title: {
            text: 'Solar Employment Growth by Sector, 2010-2016'
        },
    
        subtitle: {
            text: 'Source: thesolarfoundation.com'
        },
    
        yAxis: {
            title: {
                text: 'Number of Employees'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
    
        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                pointStart: 2010
            }
        },
    
        series: [{
            name: 'Installation',
            data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
        }, {
            name: 'Manufacturing',
            data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
        }, {
            name: 'Sales & Distribution',
            data: [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387]
        }, {
            name: 'Project Development',
            data: [null, null, 7988, 12169, 15112, 22452, 34400, 34227]
        }, {
            name: 'Other',
            data: [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111]
        }],
    
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    
    }); */
    
    /* var table = $('#gestionRedes').DataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
    
        },
    
    });
    
    var table1 = $('#coberturaGeneral').DataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
    
        },
    
    });
    
    var table2 = $('#competenciaCiudadanaComportamientoDeseable').DataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
    
        },
    
    }); */

}

function getInitialData(){
    categories = [];
    cobertura = [];
    $.post('php/dashboard_coordinadora.php',{
        type: 'general gestion redes'
    }, function(response){
        data= JSON.parse(response);
        data.response.forEach(function(element) {
           categories.push(element.mes); 
           cobertura.push(parseInt(element.sum));
        }, this);
        
        createLineGraph(categories, cobertura, 'bar_coordinadora', 'bar', 'Actores Sociales Clave', 'Número de actores sociales clave', 'número')
    })
}

function createLineGraph(categories, cobertura, selector, type, title, name, yAxis){
    var MomentoI = Highcharts.chart(selector, {
        title: {
            text: title
        },  
        xAxis: {
            categories: categories
        },
        yAxis: {
            title: {
                text: yAxis
            }
        },
        series: [{
            name: name,
            data: cobertura
        }]
    });
}

function filterByMonth(month){
    $('input[type=radio]:checked').prop('checked', false)
    categories = [];
    cobertura = [];
    tipo_entidad = [];
    $.post('php/dashboard_coordinadora.php',{
        type: 'mes gestion redes',
        mes: month,
        anio: '2017'
    }, function(response){
        data= JSON.parse(response);
        data.response.forEach(function(element) {
           categories.push(element.municipio); 
           cobertura.push(parseInt(element.sum));
           tipo_entidad.push(element.tipo_entidad);
        }, this);
        tipo_entidad_ref = ['Pública', 'Privada', 'Organización de base comunitaria'] 
        datos =[];
        bar_graph = [];
        tipo_entidad_ref.forEach(function(ent, i) {
            datos = [];
            categories.forEach(function(element, index) {
                
                if (tipo_entidad[index] == ent) {
                   datos[index]=cobertura[index]; 
                }else{
                    datos[index]=0; 
                }
            }, this);
            bar_graph[i] = {name: ent, data: datos};
        }, this);
        
        
        var MomentoI = Highcharts.chart('bar_coordinadora', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Actores Sociales en el mes '+month
            },
            xAxis: {
                categories: categories
            },
            series: bar_graph
        });
    })
}

