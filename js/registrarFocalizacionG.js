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

  //Prevent from typing negative numbers
  $("input[type=number]").keydown(function(e) {
    if (
      !(
        (e.keyCode > 95 && e.keyCode < 106) ||
        (e.keyCode > 47 && e.keyCode < 58) ||
        e.keyCode == 8
      )
    ) {
      return false;
    }
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

    $("input[type=time]").change(function(){
      calcularDuracion();
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
  if (k.length != 0) {
    if (parseInt(k[0].innerHTML) != parseInt(k[1].innerHTML)) {
      valid = false;
      swal({
        icon: "error",
        title: "Los totales no concuerdan"
      });
    }
    if (parseInt($("input[name=ninguno]").val()) < 0) {
      valid = false;
      swal({
        icon: "error",
        title: "No puede haber un campo negativo"
      });
    }
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
  let tipoInputObject = $(data);
  let tipoInputArray = tipoInputObject.get();
  var totalTipo = 0;
  var totalCaract = 0;
  let x = parseInt($("#tipoTotal").html());

  tipoInputArray.forEach(element => {
    if (index) {
      totalTipo += parseInt(element.value);
    } else if (!index) {
      if (element.name != "ninguno") {
        x = x - parseInt(element.value);
        if (x < 0) {
          x = 0;
        }
        $("input[name=ninguno]").val(`${x}`);
      }
      totalCaract += parseInt(element.value);
    }
  });

  if (index) {
    $("#tipoTotal").html(`${totalTipo}`);
    $("input[name=ninguno]").val(
      `${parseInt($("input[name=ninguno]").val()) +
        (parseInt($("#tipoTotal").html()) -
          parseInt($("#caractTotal").html()))}`
    );
    $("#caractTotal").html(`${totalTipo}`);
  }

  if (!index) {
    $("#caractTotal").html(`${totalCaract}`);
  }
}

function calcularDuracion() {
  var timeInputs = $("input[type=time]").get();

  if (timeInputs[0].value != "" && timeInputs[1].value != "") {
    var inicio = $("input[name=horaInicio]").val();
    var fin = $("input[name=horaFin]").val();

    var timeStart = new Date("01/01/2007 " + inicio);
    var timeEnd = new Date("01/01/2007 " + fin);
    var startHour = timeStart.getHours();
    var endHour = timeEnd.getHours();
    var startMinute = timeStart.getMinutes();
    var endMinute = timeEnd.getMinutes();

    if(endHour > startHour){
      totalMinutes = Math.abs((endMinute - startMinute));
      totalMinutes = Math.abs(totalMinutes - 60);
      totalHours = Math.abs((endHour - 1));
      totalHours = Math.abs((totalHours - startHour));

    }else if(endHour <= startHour){
      totalMinutes = Math.abs(endMinute - startMinute);
      totalHours = Math.abs(endHour - startHour);
    }

    $('#duracionTotal').val(
      `${totalHours} horas ${totalMinutes} minutos`
    );
  }
}
