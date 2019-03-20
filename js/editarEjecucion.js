$(function () {

  getDetallesEjecucion();
  checkLogged();
  getContactosXPlaneacion();


  $('#horaInicio').timepicker({
    uiLibrary: 'bootstrap4'
  });

  $('#horaFin').timepicker({
    uiLibrary: 'bootstrap4'
  })

  $("#datepicker").datepicker({
    uiLibrary: "bootstrap4"
  });
});

let id_plan = getParam("id_plan");
let id_zona = getParam("id_zona");

function getParam(param) {
  param = param.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + param + "=([^&#]*)");
  var results = regex.exec(location.search);
  return results === null ?
    "" :
    decodeURIComponent(results[1].replace(/\+/g, " "));
}


function getDetallesEjecucion() {
  $.ajax({
    type: "POST",
    url: "server/editarEjecucion.php",
    data: {
      getEjecucion: '',
      id_plan: id_plan
    },
    dataType: "json",
    success: function (response) {
      let detalle = response.detalle[0];
      let tipo = response.poblacion;
      let caracteristicas = response.caracteristicas;

      if (detalle.descripcion_resultado != 'null' || detalle.descripcion_resultado != null)
        $('textarea[name=descResultado]').val(detalle.descripcion_resultado);


      if (detalle.id_tipo_gestion === 2)
        $('#cardPoblacion').css('display', 'none');
      else {
        document.getElementById('tipEjecucion').className = 'col-sm-12 col-md-6 col-lg-6';
        document.getElementById('resEjecucion').className = 'showNone';
        document.getElementById('desEjecucion').className = 'col-sm-12 col-md-6 col-lg-6';
      }

      $('#tipo_gestion').html(detalle.tipo_gestion)
      $('#fecha_ejec').html(detalle.fecha);
      $('#hora_inicio').html(detalle.hora_inicio);
      $('#hora_fin').html(detalle.hora_fin);
      $('#tipoEjecucion').html(detalle.tipo_ejecucion);
      $('#resultadoEjec').html(detalle.resultado_ejecucion);
      $('#ninios').val(tipo[0].total);
      $('#jovenes').val(tipo[1].total);
      $('#adultos').val(tipo[2].total);
      $('#afro').val(caracteristicas[0].total);
      $('#indi').val(caracteristicas[1].total);
      $('#sdes').val(caracteristicas[2].total);
      $('#sdis').val(caracteristicas[3].total);
      $('#ninguno').val(caracteristicas[4].total);
    }
  });
}

function getContactosXPlaneacion() {
  $.ajax({
    type: "POST",
    url: "server/contactos.php",
    data: {
      getContactos: '',
      id_plan: id_plan
    },
    dataType: "json",
    success: function (response) {
      response.forEach(e => {
        $('#collapseContactos tbody').html(
          `<tr id="${e.id_contacto}">
            <th scope="row">${e.id_contacto}</th>
            <td>${e.nombres} ${e.apellidos}</td>
            <td>${e.correo}</td>
            <td>${e.celular}</td>
            <td>${e.cargo}</td>
            <td>
              <button class="btn btn-success" onclick="llamarEditarContacto(${e.id_contacto}, this)" role="button">Editar</button>
              <button class="btn btn-danger" onclick="removerContactoPlaneacion(${e.id_contacto})" role="button">Reemplazar</button>
            </td>
          </tr>`
        )
      });
    }
  });
}



function removerContactoDePlaneacion(contacto) {
  $.ajax({
    type: "POST",
    url: "server/contactos.php",
    data: {
      eliminar_cont: ''
    },
    dataType: "dataType",
    success: function (response) {

    }
  });
}

function llamarEditarContacto(contacto, btn) {
  btn.disabled = true;
  $.ajax({
    type: "POST",
    url: "server/contactos.php",
    data: {
      getAll: '',
      contacto
    },
    dataType: "json",
    success: function (response) {
      response = response.contact[0];
      document.getElementById('nombreContacto').value = response.nombres;
      document.getElementById('apellidoContacto').value = response.apellidos;
      document.getElementById('cedulaContacto').value = response.cedula;
      document.getElementById('TelefonoContacto').value = response.telefono;
      document.getElementById('celularContacto').value = response.celular;
      document.getElementById('correoContacto').value = response.correo;
      document.getElementById('cargoContacto').value = response.cargo;
    }
  }).done(() => {
    btn.disabled = false;
    $('#editarContactoModal').modal();
  });
}

function checkInputs() {
  let editar = document.getElementById('editarEjecucion');
  let inputs = editar.getElementsByTagName('input');

  for (let i in inputs) {
    if (inputs[i].type == 'text') {
      if (inputs[i].value != "") {
        console.log('Faltan valores por guardar');
        break;
      }
    }

    if (inputs[i].type == 'radio') {
      if (inputs[i].checked) {
        console.log('Faltan valores por guardar');
        break;
      }
    }

    if (inputs[i].type == 'number') {
      if (inputs[i].val() == 0) {
        console.log('Faltan valores por guardar');
        break;
      }
    }
  }
}

function updateEjecucion(btn) {
  let parent = btn.parentNode;
  let element = parent.querySelectorAll('input');
  let spanEl = parent.parentNode.querySelectorAll('span');
  let name = '';
  let val;

  if (element.length == 0) {
    element = parent.querySelectorAll('textarea');
  }

  if (element.length > 1) {
    for (e of element) {
      if (e.checked) {
        name = e.getAttribute('name');
        val = e.value;
        break;
      }
    }
  } else {
    name = element[0].getAttribute('name');
    val = element[0].value;
  }

  saveEdit(name.toString(), val.toString(), btn);
}

function saveEdit(name, val, btn) {
  $.ajax({
    type: "POST",
    url: "server/editarEjecucion.php",
    data: {
      id_plan: id_plan,
      name,
      val
    },
    dataType: "json",
    success: function (response) {
      if (response.error == 0) {
        getDetallesEjecucion();
        limpiarDatos();
      }
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
      $('#homeBtn').attr('href', `home.html?user=${data.rol}&id_zona=${data.zona}`);
    }
  });
}

function limpiarDatos() {
  let editar = document.getElementById('editarEjecucion');
  editar.reset();
}