$(document).ready(function() {
  /* Functions */
  getTotal("#tipoPoblacion input[type=number]", true);
  getTotal("#caracteristicasPoblacion input[type=number]", false);
  showTab(currentTab); // Display the current tab

  $("#datepicker").datepicker({
    uiLibrary: "bootstrap4"
  });

  if (
    $("select").change(function() {
      if ($(this).hasClass("invalid")) {
        $(this).removeClass("invalid");
      }
    })
  );

  $("#tableCaracteristica").DataTable({
    scrollX: true,
    searching: false,
    lengthChange: false,
    info: false,
    paging: false
  });

  //Hide modal registro de contacto
  $("#cerrarDetalleModal").click(function() {
    $("#detalleEjecModal").modal("toggle");
  });

  $("#tipoPoblacion")
    .find($("input[type=number]"))
    .change(() => {
      var element = "#tipoPoblacion input[type=number]";
      var index = true;
      getTotal(element, index);
    });

  $("#caracteristicasPoblacion")
    .find($("input[type=number]"))
    .change(() => {
      var element = "#caracteristicasPoblacion input[type=number]";
      var index = false;
      getTotal(element, index);
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
  y = x[currentTab].getElementsByClassName("required");
  k = x[currentTab].getElementsByClassName("totales");
  //Verifies if spans have equal value
  if (parseInt(k[0].innerHTML) != parseInt(k[1].innerHTML)) {
    valid = false;

    swal({ 
      icon: "error",
      title: "Los totales no concuerdan"
    });
  }

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

function getTotal(data, index) {
  var index = index;
  let tipoInputObject = $(data);
  let tipoInputArray = tipoInputObject.get();
  let total = 0;

  tipoInputArray.forEach(element => {
    total += parseInt(element.value);
  });

  if (index) {
    $("#tipoTotal").html(`${total}`);
  } else {
    $("#caractTotal").html(`${total}`);
  }
}
