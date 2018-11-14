$(document).ready(function() {
  /* Functions */
  checkLogged();
  getTipoGestion();
  getDetallePlaneacion();
  getTotal("#tipoPoblacion input[type=number]", true);
  getTotal("#caracteristicasPoblacion input[type=number]", false);
  showTab(currentTab); // Display the current tab
  document.getElementById("noEjecucion").checked = false;
  calcularDuracion();

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

  // When time input changes
  $("input[type=time]").change(function() {
    calcularDuracion();
  });

  //When window resizes
  $(window)
    .resize(resize)
    .trigger("resize");
});

var id_zona = getParam("id_zona");
let id_plan = getParam("id_plan");
let id_foc = getParam("id_foc");

function getParam(param) {
  param = param.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + param + "=([^&#]*)");
  var results = regex.exec(location.search);
  return results === null
    ? ""
    : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function getTipoGestion() {
  $.ajax({
    type: "POST",
    url: "server/getTipoGestion.php",
    data: {
      tipo_gestion : id_foc
    },
    dataType: "json",
    success: function(response) {

      if(response[0].id_tipo_gestion != 1){
        $('#detalleCardContentGF').addClass('showNone');
        $('#colPoblacion').addClass('showNone');
      }else{
        $('#colActaReunion').addClass('showNone');
      }
    }
  });
}

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
    insertEjecucion();
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

    if (endHour > startHour) {
      totalMinutes = Math.abs(endMinute - startMinute);
      if (totalMinutes == 0) {
        totalHours = Math.abs(endHour - startHour);
      } else {
        totalMinutes = Math.abs(totalMinutes - 60);
        totalHours = Math.abs(endHour - 1);
        totalHours = Math.abs(totalHours - startHour);
      }
    } else if (endHour <= startHour) {
      totalMinutes = Math.abs(endMinute - startMinute);
      totalHours = Math.abs(endHour - startHour);
    }

    $("#duracionTotal").val(`${totalHours} horas ${totalMinutes} minutos`);
  }
}

var isChecked = false; // Sets that radio is unchecked for condition

function getChecked() {
  var radio = document.getElementById("noEjecucion");
  var alert = document.getElementById("divNoEjecucionDad");

  if (!isChecked) {
    radio.checked = true;
    isChecked = true;
    alert.classList.add("danger"); // Adds danger indicating that radio is active
    $("#modalNoEjecucion").modal({
      keyboard: false,
      backdrop: "static"
    }); //
  } else {
    radio.checked = false;
    isChecked = false;
    alert.classList.remove("danger");
  }
}

// When window resizes change class
function resize() {
  if ($(window).width() <= 1171) {
    $("#colCreateEjec")
      .removeClass("col-lg-8")
      .addClass("col-lg-12");
  } else {
    if ($("#colCreateEjec").hasClass("col-lg-12")) {
      $("#colCreateEjec")
        .removeClass("col-lg-12")
        .addClass("col-lg-8");
    }
  }
}

function checkLogged() {
  $.ajax({
    type: "POST",
    url: "server/checkLog.php",
    data: {
      zona: id_zona
    },
    dataType: "json"
  }).done(function(data) {
    if (data.error) {
      swal({
        type: "info",
        title: "Usuario",
        text: data.message
      }).then(function() {
        window.location.href = "iniciarSesion.html";
      });
    } else {
      $("#userName").html(`Hola ${data}`);
    }
  });
}

function insertEjecucion(){
  $(".loader").fadeIn();
  $.ajax({
    type: "POST",
    url: "server/insertEjecucion.php",
    data: `${$('#ejecForm').serialize()}&id_plan=${id_plan}`,
    dataType: "json",
    success: function (response) {
      swal({
        type: "success",
        title: response
      }).then(function() {
        $(".loader").fadeOut();
      });
    }
  });
}

function getDetallePlaneacion(){
  $.ajax({
    type: "POST",
    url: "server/getPlaneaciones.php",
    data: {
      detallePlaneacion : id_plan
    },
    dataType: "json",
    success: function (response) {
      console.log(response);
    }
  });
}