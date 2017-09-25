<?php
$barrio=0;
$vereda=0;
if(isset($_GET["barrio"]))
	$barrio = $_GET["barrio"];
if(isset($_GET["vereda"]) && $_GET["vereda"]!="")
{	
	$vereda = $_GET["vereda"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Language" content="en-us">
    <title>PHP MySQL Typeahead Autocomplete</title>
    <meta charset="utf-8">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="//netsh.pp.ua/upwork-demo/1/js/typeahead.js"></script>
    <script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
   <style>
.suggest-element{
margin-left:5px;
margin-top:5px;
width:350px;
cursor:pointer;
}
#suggestions {
width:350px;
height:150px;
overflow: auto;
}
</style>
<script type="text/javascript">
$(document).ready(function() {    
    //Al escribr dentro del input con id="service"
    $('#service').keypress(function(){
        //Obtenemos el value del input
        var service = $(this).val();        
        var dataString = 'service='+service;
        var barrio = <?php echo $barrio; ?>;
		var vereda = <?php echo $vereda; ?>;
		var consulta='6';
        //Le pasamos el valor del input al ajax
        $.ajax({
            type: "GET",
            url: "ConsultasDB.php?consulta="+consulta+"&barrio="+barrio+"&vereda="+vereda+"",
            data: dataString,
            success: function(data) {
                //Escribimos las sugerencias que nos manda la consulta
                $('#suggestions').fadeIn(1000).html(data);
                //Al hacer click en algua de las sugerencias
                $('.suggest-element').live('click', function(){
                    //Obtenemos la id unica de la sugerencia pulsada
                    var id = $(this).attr('id');
                    //Editamos el valor del input con data de la sugerencia pulsada
                    $('#service').val($('#'+id).attr('data'));
                    //Hacemos desaparecer el resto de sugerencias
                    $('#suggestions').fadeOut(1000);
                });              
            }
        });
    });              
});    
</script>
</head>

<body>
    <div class="content">

       <form>
   <input type="text" size="50" id="service" name="service" />
   <div id="suggestions"></div>
</form>
    </div>
</body>

</html>
 

<?php
/* 
}



 




<html lang="en">
<head>
    <title>Bootstrap Typeahead with Ajax Example</title>  
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>  
</head>
<body>

<div class="row">
	<div class="col-md-12 text-center">
		<br/>
		<h1>Search Dynamic Autocomplete using Bootstrap Typeahead JS</h1>	
			<input class="typeahead form-control" style="margin:0px auto;width:300px;" type="text">
	</div>
</div>

<script type="text/javascript">
var barrio = <?php echo $barrio; ?>;
var vereda = <?php echo $vereda; ?>;
	$('input.typeahead').typeahead({
	    source:  function (query, process) {
        return $.get('ConsultasDB.php', { query: query, barrio: barrio, vereda: vereda, consulta:'6' }, function (data) {
        		//console.log(data);
        		//data = $.parseJSON(data);
	            //return process(data);
	            //return process(data.search_results);
	             if (data.success) {

          			response($.map(dtdata.data, function(item) {

		            
          			}));
          		}
	        });
	    }
	});



      url: '@Url.Content("~/Employee/SearchEmployee")/',
      type: 'POST',
      contentType: 'application/json',
      dataType: "json",
      data: JSON.stringify({
          employerId: 1,
          searchStr: me.val()
      }),
      success: function(data) {
        if (data.success) {

          response($.map(data.data, function(item) {

            return {
              label: "(" + item.EmployeeNumber + ") " + 
                           item.FirstName + " " + 
                           item.MothersLast + ", " + 
                           item.FathersLast,
              employeeId: item.EmployeeId
            }
          }));

</script>
</body>
</html>



*/

/*
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
$barrio=0;
$vereda=0;
if(isset($_GET["barrio"]))
	$barrio = $_GET["barrio"];
if(isset($_GET["vereda"]) && $_GET["vereda"]!="")
{	
	$vereda = $_GET["vereda"];
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/bootcomplete.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.bootcomplete.js"></script>

<input id="example2" type="text" name="chained" placeholder="Enter Query" class="form-control">
                
<script type="text/javascript">

var barrio = <?php echo $barrio; ?>;
var vereda = <?php echo $vereda; ?>;
    $('#example2').bootcomplete({
        url:'php/consultarDB.php',
        minLength : 1,
        formParams: {
            'consulta':'6',
            'barrio':barrio,
            'vereda':vereda
        }
    });
</script>


</body>
</html>
*/
?>