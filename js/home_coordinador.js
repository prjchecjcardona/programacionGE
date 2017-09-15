$(function(){
 

})



function initMap() {
    var uluru = {lat: 5.067774, lng: -75.517053};
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 9,
      center: uluru
    });
    var marker = new google.maps.Marker({
      position: uluru,
      map: map
    });
  }



  //5.423532, -75.703456