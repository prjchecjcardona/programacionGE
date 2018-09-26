$(document).ready(function() {
  $("#datepicker").datepicker({
    uiLibrary: "bootstrap4"
  });

  $("#tableContactos").DataTable({
    scrollX: true,
    searching: false,
    lengthChange: false,
    info: false,
    paging: false,
    columnDefs: [
      {
        orderable: false,
        className: "select-checkbox",
        targets: 0
      }
    ],
    select: {
      style: "multi",
      selector: "td:first-child"
    },
    order: [[1, "asc"]]
  });

  showTab(currentTab); // Display the current tab

  //Hide modal registro de contacto
  $("#btnCancelarRegContacto").click(function() {
    $("#modalRegistrarContacto").modal("toggle");
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
    document.getElementById("planForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
  $("#tableContactos")
    .DataTable()
    .columns.adjust();
}

function validateForm() {
  // This function deals with validation of the form fields
  var x,
    y,
    i,
    valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByClassName("table");
  // Verifies if there is a table en the current tab
  if (y.length > 0) {
    var table = $("#tableContactos").DataTable();
    // Counts selected rows in table. if there are rows selected
    //then valid=true if not, no row is selected and valid=false
    var data = table.rows({ selected: true }).data();
    if (data.length > 0) {
      $(".alert").addClass("showNone");
      valid = true;
    } else {
      $(".alert").removeClass("showNone");
      $(".alert").addClass("show");
      valid = false;
    }
  } else {
    y = x[currentTab].getElementsByClassName("required");
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