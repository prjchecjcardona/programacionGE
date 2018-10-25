$(function() {
  getMunicipioXZona();
});

var id_zona = getZona('id_zona');

function getZona(param){
  param = param.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
  var regex = new RegExp('[\\?&]' + param + '=([^&#]*)');
  var results = regex.exec(location.search);
  return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

function getMunicipioXZona(){
  $.ajax({
    type: "POST",
    url: "server/getMunicipios.php",
    data: {
      zona : id_zona
    },
    dataType: "json"
  }).done(function(data){
    console.log(data);
  });
}
