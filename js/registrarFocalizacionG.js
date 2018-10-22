$(document).ready(function() {
  /* Functions */
  executeAll();
  determineTipoGestion();
  showTab(currentTab); // Display the current tab
  checkLogged();

  if (
    $("select").change(function() {
      if ($(this).hasClass("invalid")) {
        $(this).removeClass("invalid");
      }
    })
  );

  $("input[name=tipoGestion]").change(function() {
    determineTipoGestion();
  });
});

var currentTab = 0; // Current tab is set to be the first tab (0)

function showTab(n) {
  // This function will display the specified tab of the form ...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  // ... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == x.length - 1) {
    document.getElementById("nextBtn").innerHTML = "Guardar";
  } else {
    document.getElementById("nextBtn").innerHTML = "Siguiente";
  }
  // ... and run a function that displays the correct step indicator:
  fixStepIndicator(n);
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form... :
  if (currentTab >= x.length) {
    //...the form gets submitted:
    document.getElementById("intForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x,
    y,
    i,
    valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("select");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].parentElement.parentElement.style.display != "none") {
      if (y[i].value == "") {
        // add an "invalid" class to the field:
        y[i].className += " invalid";
        // and set the current valid status to false:
        valid = false;
      }
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i,
    x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class to the current step:
  x[n].className += " active";
}

function determineTipoGestion() {
  var radioTipoGestion = $("input[name=tipoGestion]:checked");

  if (parseInt(radioTipoGestion.val()) == 2) {
    console.log(radioTipoGestion.val());
    $("#selectComportamiento").prop("disabled", true);
    $("#selectTipoInt").prop("disabled", true);
  } else {
    $("#selectComportamiento").prop("disabled", false);
    $("#selectTipoInt").prop("disabled", false);
  }
}

/* function that creates array of objects that needs request from db
select=name of html tag, data= name of query that will be in POST parameter
 */
function executeAll() {
  /* Define array of objects with values to replace */
  var ajaxJson = [
    {
      select: "selectMunicipio",
      url: "server/getMunicipios.php"
    },
    {
      select: "selectComportamiento",
      url: "server/getComportamientos.php"
    }
  ];

  ajaxJson.forEach(element => {
    primaryAjax(element.select, element.url);
  });
}

function primaryAjax(tag, url) {
  $.ajax({
    type: "POST",
    url: url,
    data: "",
    dataType: "json"
  }).done(function(data) {
    data.forEach(element => {
      var elementArray = Object.values(element); // Converts objects to array

      if (tag == "selectMunicipio") {
        // Object from selectMunicipio has only 2 keys
        $(`#${tag}`).append(
          `<option value="${elementArray[0]}">${elementArray[1]}</option>`
        );
      } else {
        // This object has more than 2, for that is required to add another element
        $(`#${tag}`).append(
          `<option value="${elementArray[0]}">${elementArray[1]} - ${
            elementArray[2]
          }</option>`
        );
      }
    });
  });
}

function checkLogged(){
  $.ajax({
    type: "POST",
    url: "server/checkLog.php",
    data: "",
    dataType: "json"
  }).done(function(data){
    if(data.error){
      swal({
        type: 'info',
        title: 'Usuario',
        text: data.message,
      }).then(function(){
        window.location.href = "iniciarSesion.html";
      });
    }
  });
}
