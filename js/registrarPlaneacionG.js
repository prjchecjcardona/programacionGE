$(document).ready(function () {
  /* Functions */
  checkLogged();

  executeAll();
  getCompetencias();
  checkGestionInstitucional();
  showTab(currentTab); // Display the current tab
  $("#vereda").hide();
  $("#comunaObarrio").hide();
  $("#subirSolicitud").hide();
  determineRadio();
  checkSolicitudEducativa();

  $("#datepicker").datepicker({
    locale: "es-es",
    uiLibrary: "bootstrap4",
    format: "dd-mm-yyyy"
  });

  $("#tableContactos").DataTable({
    scrollX: true,
    searching: false,
    lengthChange: false,
    info: false,
    paging: true,
    ajax: {
      url: `server/getContactos.php`,
      type: "POST",
      data: {
        mun: id_mun
      }
    },
    columns: [{
        data: "id_contacto"
      },
      {
        data: "nombre"
      },
      {
        data: "telefono"
      },
      {
        data: "cargo"
      },
      {
        data: "nombre_entidad"
      }
    ],
    sProcessing: "Procesando...",
    sLengthMenu: "Mostrar _MENU_ registros",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
    sInfoPostFix: "",
    sSearch: "Buscar:",
    sUrl: "",
    sInfoThousands: ",",
    sLoadingRecords: "Cargando...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "Siguiente",
      sPrevious: "Anterior"
    },
    oAria: {
      sSortAscending: ": Activar para ordenar la columna de manera ascendente",
      sSortDescending: ": Activar para ordenar la columna de manera descendente"
    },
    columnDefs: [{
      targets: 0,
      render: function (data, type, row, meta) {
        return `<input type="checkbox" id="contacto" name="contacto" value="${data}">`;
      }
    }]
  });

  if (
    $("select").change(function () {
      if ($(this).hasClass("invalid")) {
        $(this).removeClass("invalid");
      }
    })
  );

  $("input[name=esCercania]").change(function () {
    getTacticos();
  });

  $("input[name=solicitudEducativa]").change(function () {
    checkSolicitudEducativa();
  });

  $('input[name="ubicMunicipio"]').change(function () {
    determineRadio();
  });

  //Hide modal registro de contacto
  $("#btnCancelarRegContacto").click(function () {
    $("#modalRegistrarContacto").modal("toggle");
  });

  $("#btnCancelarRegEntidad").click(function () {
    $("#modalRegistrarEntidad").modal("toggle");
  });

  $("#btnCrearContacto").click(function (e) {
    e.preventDefault();
    insertContacto();
  });

  $("#btnCrearEntidad").click(function (e) {
    e.preventDefault();
    insertEntidad();
  });

  $("#selectEstrategia").change(function () {
    getTacticos();
  });

  $("#selectTema").change(function () {
    id_tema = $("#selectTema").val();
    getSubtemasList(id_tema);
  });

  $('#cancelar').click(() => {
    swal({
      type: "warning",
      title: "Vas a cancelar",
      text: "¿Seguro?",
      showCancelButton: true,
      cancelButtonText: 'No',
      cancelButtonColor: '#d33',
      confirmButtonColor: '#28a745',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {
        window.location.href = $('#homeBtn').attr('href');
      }
    })
  });

  $(
    "#formCrearContacto input, #planForm input, #formRegistrarEntidad input"
  ).focusout(function () {
    $(this).val(
      $(this)
      .val()
      .toUpperCase()
    );
  });
});

/* PARAMETERS */
id_zona = getParam("id_zona");
id_mun = getParam("id_mun");
id_foc = getParam("id_foc");
id_comport = getParam("comport");

function getParam(param) {
  param = param.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + param + "=([^&#]*)");
  var results = regex.exec(location.search);
  return results === null ?
    "" :
    decodeURIComponent(results[1].replace(/\+/g, " "));
}

let uploadSolicitud = new FileUploadWithPreview.default("solicitudEdu");

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
    insertPlaneacion();
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
    // Counts selected rows in table. if there are rows selected
    var contactList = $("input[name=contacto]:checked").length;
    if (contactList > 0) {
      if (!$(".alert").hasClass("showNone")) {
        $(".alert").addClass("showNone");
      }
      valid = true;
    } else {
      if ($(".alert").hasClass("showNone")) {
        $(".alert").removeClass("showNone");
      }
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

function determineRadio() {
  var vereda = $("#vereda");
  var comunaObarrio = $("#comunaObarrio");
  var radioVar = $('input[name="ubicMunicipio"]:checked');
  if (radioVar.val() != undefined) {
    if (radioVar.val() == "rural") {
      if (vereda.is(":hidden") && comunaObarrio.is(":visible")) {
        vereda.toggle();
        comunaObarrio.toggle();
      } else {
        vereda.toggle();
      }
    } else if (comunaObarrio.is(":hidden") && vereda.is(":visible")) {
      vereda.toggle();
      comunaObarrio.toggle();
    } else {
      comunaObarrio.toggle();
    }
  }
}

function checkSolicitudEducativa() {
  let solicitudRadio = $("input[name=solicitudEducativa]:checked");

  if (solicitudRadio.val() == "true") {
    $("#subirSolicitud").toggle();
    
  } else {
    if ($("#subirSolicitud").is(":visible")) {
      $("#subirSolicitud").toggle();
    }
  }
}

function executeAll() {
  /* Define array of objects with values to replace */
  var ajaxJson = [{
      select: "selectEntidad",
      url: "getEntidades.php",
      data: {
        mun: id_mun
      }
    },
    {
      select: "selectIndicador",
      url: "getIndicadoresGE.php"
    },
    {
      select: "entidadContacto",
      url: "getEntidades.php",
      data: {
        mun: id_mun
      }
    },
    {
      select: "selectVereda",
      url: "getVeredas.php",
      data: {
        mun: id_mun
      }
    },
    {
      select: "selectBarrio",
      url: "getBarrios.php",
      data: {
        mun: id_mun
      }
    },
    {
      select: "selectEstrategia",
      url: "getEstrategias.php"
    },
    {
      select: "selectTema",
      url: "getTemas.php",
      data: {
        comportamiento: id_comport
      }
    }
  ];

  ajaxJson.forEach(element => {
    primaryAjax(element.url, element.select, element.data);
  });
}

function primaryAjax(url, tag, data) {
  $(`#${tag}`).html('<option value="" disabled>Seleccione</option>');
  $.ajax({
    type: "POST",
    url: `server/${url}`,
    data: data,
    dataType: "json"
  }).done(function (data) {
    data.forEach(element => {
      var elementArray = Object.values(element);

      if (elementArray.length == 1) {
        $(`#${tag}`).append(
          `<option value="${elementArray[0]}">${elementArray[0]}</option>`
        );

        if ($(`#${tag}`).prop('disabled')) {
          $(`#${tag}`).prop('disabled', false);
        }
      } else {
        $(`#${tag}`).append(
          `<option value="${elementArray[0]}">${elementArray[1]}</option>`
        );

        if ($(`#${tag}`).prop('disabled')) {
          $(`#${tag}`).prop('disabled', false);
        }
      }
    });
  });
}

function getSubtemasList(id_tema) {
  $("#divSubtemas").html("");
  $.ajax({
    type: "POST",
    url: "server/getSubtemas.php",
    data: {
      tema: id_tema
    },
    dataType: "json",
    success: function (response) {
      response.forEach(element => {

        arraySubtemas = element.subtemas.split("&");

        $("#divSubtemas").append(
          `<div class="checkbox-card">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" name="subtema" class="custom-control-input" id="customCheck${
                element.id_subtema
              }" value="${element.id_subtema}">
              <label class="custom-control-label" for="customCheck${
                element.id_subtema
              }">
                <ul id="${element.id_subtema}">

                </ul>
              </label>
            </div>
          </div>`
        );

        arraySubtemas.forEach(el => {
          $(`label ul#${element.id_subtema}`).append(`<li>${el}</li>`);
        });
      });

      $("input[name=subtema]").change(function () {
        getIndicadoresGEXSubtema();
        getGuiasPlaneacion();
      });
    }
  });
}

function getIndicadoresGEXSubtema() {
  subtemasLength = $("input[name=subtema]:checked").length;
  subtemasArray = [];
  for (i = 0; i < subtemasLength; i++) {
    subtemasArray.push($("input[name=subtema]:checked")[i].value);
  }

  $.ajax({
    type: "POST",
    url: "server/getIndicadoresGE.php",
    data: {
      subtema: subtemasArray
    },
    dataType: "json",
    success: function (response) {
      $("#indicadores .card-title ul").html("");
      response.forEach(element => {
        $("#indicadores .card-title ul").append(
          `<li id="${element.id_indicador}">${element.nombre_indicador}</li>`
        );
      });
    }
  });
}

function getGuiasPlaneacion() {
  subtemasLength = $("input[name=subtema]:checked").length;
  subtemasArray = [];
  for (i = 0; i < subtemasLength; i++) {
    subtemasArray.push($("input[name=subtema]:checked")[i].value);
  }

  $.ajax({
    type: "POST",
    url: "server/getGuias.php",
    data: {
      subtema: subtemasArray
    },
    dataType: "json",
    success: function (response) {
      $("#guias .card-title ul").html("");
      response.forEach(element => {
        $("#guias .card-title ul").append(
          `<li><a href="${element.fichero_url}" id="${
            element.id_guia
          }" target="_blank">${element.nombre}</a></li>`
        );
      });
    }
  });
}

function insertContacto() {
  var requiredElements = document.getElementById("formCrearContacto");
  var x = requiredElements.getElementsByClassName("required");
  var valid = true;

  for (i = 0; i < x.length; i++) {
    if (x[i].value == "") {
      $(`#${x[i].id}`).addClass("is-invalid");
      valid = false;
    } else {
      if ($(`#${x[i].id}`).hasClass("is-invalid")) {
        $(`#${x[i].id}`).removeClass("is-invalid");
      }
    }
  }

  if (valid) {
    $("#btnCrearContacto").prop("disabled", true);
    formContacto = $("#formCrearContacto").serialize();
    $.ajax({
      type: "POST",
      url: "server/insertContactos.php",
      data: formContacto,
      dataType: "json",
      success: function (response) {
        if (response.error == 0) {
          swal({
            type: "success",
            title: response.message
          }).then(function () {
            $("#modalRegistrarContacto").modal("toggle");
            $("#btnCrearContacto").prop("disabled", false);
            document.getElementById("formCrearContacto").reset();
            reloadContactos();
          });
        } else {
          swal({
            type: "error",
            title: response.message
          });
        }
      }
    });
  }
}

function insertEntidad() {
  let type = "success";
  formEntidad = $("#formRegistrarEntidad").serialize();
  $.ajax({
    type: "POST",
    url: "server/insertEntidad.php",
    data: formEntidad + `&municipio=${id_mun}`,
    dataType: "json",
    success: function (response) {
      if (response.error !== 0) {
        type = "error";
      }
      swal({
        type: type,
        title: response.message
      }).then(function () {
        $("#modalRegistrarEntidad").modal("toggle");
        $("#btnCrearEntidad").prop("disabled", false);
        document.getElementById("formRegistrarEntidad").reset();

        primaryAjax("getEntidades.php", "selectEntidad", {
          mun: id_mun
        });

        primaryAjax("getEntidades.php", "entidadContacto", {
          mun: id_mun
        });
      });
    }
  });
}

function insertPlaneacion() {
  $(".loader").fadeIn();
  let formPlan = document.getElementById("planForm");
  let form = new FormData(formPlan);
  form.append("id_foc", id_foc);

  if ($("input[name=solicitudEducativa]").val() == "true") {
    let file = uploadSolicitud.cachedFileArray[0];
    form.append("solicitud", file);
  }

  $.ajax({
    type: "POST",
    url: "server/insertPlaneacion.php",
    data: form,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.error == 1) {
        if (!response.error_message) {
          response.error_message = "";
          callback = window.location.reload();
        } else {
          callback = insertXPlaneacion();
        }
        swal({
          type: "error",
          title: response.message,
          text: response.message_error
        }).then(() => {
          callback;
        });
      } else {
        insertXPlaneacion();
      }
    }
  });
}

function insertXPlaneacion() {
  $.ajax({
    type: "POST",
    url: "server/insertXPlaneacion.php",
    data: "",
    dataType: "json",
    success: function (response) {
      id_plan = response[0].max;

      /* GET SELECTED CONTACTS */
      contactos = $("input[name=contacto]:checked").serializeArray();

      /* GET SELECTED SUBTEMAS */
      subtemas = $("input[name=subtema]").serializeArray();

      /* GET SELECTED TACTICOS */
      tacticos = $("#selectTactico").serializeArray();

      /* GET SELECTED COMPORTAMIENTOS */
      comportamientos = $('#selectCompetencia').val();

      $.ajax({
        type: "POST",
        url: "server/insertXPlaneacion.php",
        data: {
          tacticos: tacticos,
          subtemas: subtemas,
          contactos: contactos,
          comportamientos: comportamientos,
          id_plan: id_plan
        },
        dataType: "json",
        success: function (response) {
          if (response.error == 0) {
            swal({
              type: "success",
              title: response.message
            }).then(function () {
              $(".loader").fadeOut();
              window.location.href = $("#homeBtn").attr("href");
            });
          } else {
            swal({
              type: "error",
              title: response.message
            }).then(function () {
              $(".loader").fadeOut();
              location.reload();
            });
          }
        }
      });
    }
  });
}

function checkLogged() {
  $.ajax({
    type: "POST",
    url: "server/checkLog.php",
    data: {
      zona: id_zona
    },
    dataType: "json"
  }).done(function (data) {
    if (data.error) {
      swal({
        type: "info",
        title: "Usuario",
        text: data.message
      }).then(function () {
        window.location.href = "iniciarSesion.html";
      });
    } else {
      $("#homeBtn").attr(
        "href",
        `home.html?user=${data.rol}&id_zona=${data.zona}`
      );
    }
  });
}

function reloadContactos() {
  setTimeout(() => {
    $("#tableContactos")
      .DataTable()
      .ajax.reload();
  }, 1000);
}

function checkGestionInstitucional() {
  $.ajax({
    type: "POST",
    url: "server/getFocalizaciones.php",
    data: {
      check_gestion: id_foc
    },
    dataType: "json",
    success: function (response) {
      if (response[0].id_tipo_gestion == 2) {
        var temas = document.getElementById('selectTema').parentElement
        var competencias = document.getElementById('selectCompetencia').parentElement
        var indicadores = document.getElementById('indicadores').parentElement
        temas.classList.add('showNone');
        competencias.classList.remove('showNone');
        indicadores.classList.add('showNone')
      }
    }
  });
}

function getCompetencias() {
  $.ajax({
    type: "POST",
    url: "server/getComportamientos.php",
    data: "",
    dataType: "json",
    success: function (response) {
      response.forEach(element => {
        $('#selectCompetencia').append(
          `<option value="${element.id_comportamientos}"> ${element.comportamientos} </option>`
        )
      });
    }
  });
}

function getTacticos() { 
  estrategia = $("#selectEstrategia").val();
  cercania = $("input[name=esCercania]:checked").val();
  primaryAjax("getTacticos.php", "selectTactico", {
    estrategia: estrategia,
    cercania: cercania
  });
}