//Idioma Español del Calendario
$.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '< Ant',
 nextText: 'Sig >',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);

//Activacion datepicker (Fecha)


var dateToday = new Date();
var dates = $(".datepicker").datepicker({
    defaultDate: "+1w",
    changeMonth: true,
    changeYear: true,
    numberOfMonths: 1,
    minDate: dateToday,
    onSelect: function(selectedDate) {
        var option = this.id == "from" ? "minDate" : "maxDate",
            instance = $(this).data("datepicker"),
            date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
        dates.not(this).datepicker("option", option, date);
    }
});



/* $(function (){
  $(".datepicker").datepicker({
    dateFormat: "yy-mm-dd",
    changeYear: true,
    hideIfNoPrevNext: true,

    changeMonth: true
  });
});
// Getter
var hideIfNoPrevNext = $( ".datepicker" ).datepicker( "option", "hideIfNoPrevNext" );
 
// Setter
$( ".datepicker" ).datepicker( "option", "hideIfNoPrevNext", true );

// Getter
var showAnim = $( ".datepicker" ).datepicker( "option", "showAnim" );
 
// Setter
$( ".datepicker" ).datepicker( "option", "showAnim", "fold" ); */