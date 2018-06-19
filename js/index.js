$(document).ready(function(){
    getwelcome();
    setTimeout(getWelcomeimg, 1000);
})

function getwelcome(){
    document.getElementById('welcome').setAttribute('style', 'display:flex');
    $('#welcome').addClass('animated bounceInLeft');
}

function getWelcomeimg(){
    document.getElementById('welcome_img').setAttribute('style', 'display:inline');
    $('#welcome_img').addClass('animated bounceIn');
}