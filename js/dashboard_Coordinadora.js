$(document).ready(function(){
    $('#multiple-mes').multiselect();
    $('#multiple-anio').multiselect();
    $('#multiple-comportamiento').multiselect();
    $('#multiple-estrategia').multiselect();
    $('#multiple-tactico').multiselect();
 
});

$(function () { 
    var myChart = Highcharts.chart('container', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Fruit Consumption'
        },
        xAxis: {
            categories: ['Apples', 'Bananas', 'Oranges']
        },
        yAxis: {
            title: {
                text: 'Fruit eaten'
            }
        },
        series: [{
            name: 'Jane',
            data: [1, 0, 4]
        }, {
            name: 'John',
            data: [5, 7, 3]
        }]
    });
});
