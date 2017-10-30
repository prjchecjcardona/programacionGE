
function getMain(pag){
  switch (pag) {
    case "criterios":
      return getCriterios();
      break;
    case "generalidades":
      return getGeneralidades();
      break;
    case "herramientas":
      return getHerramientas();
      break;
    case "glosario":
      return getGlosario();
      break;
      case "recurso":
      return getRecurso();
      break;
    default:
      return getInicio();
  }
}

function getInicio() {
  return `
    <div class="cover-div"></div>
    <div class="welcome-container">
      <h1>Bienvenido al Banco de Herramientas</h1>
    </div>

  `;
}

function getGeneralidades() {
  return `
  <div id="presentacion" class="gen-block">    
    <div class="text-title-container">
     <h2 class="gen-title" id="title-presentacion">Presentación</h2>
     <div class="gen-img-container">
      <img id="girl-gen" src="bh/img/Señorita-01.png" alt="">
     </div>
      <div class="floating-text">
        <p>
          La Gestión Educativa surge como la necesidad de aportar a la construcción de territorios sostenibles con el fortalecimiento de la cultura ciudadana a partir de practicas sustentables en los clientes del servicio de energía, que en conjunto faciliten su participación y compromiso de los mismos en escenarios de integración social y ambiental.
        </p>
        <p>
          Es así como la Gestión Educativa buscar movilizar el ámbito social a través de estrategias educativas que permitan la formación ciudadana desde diferentes aspectos que conllevan a la concientización de los clientes sobre la importancia de hacer un uso responsable del servicio con el fin de garantizar su disponibilidad en el tiempo; fomentar una cultura de pago que favorezca el cumplimiento de derechos y deberes de los clientes, la comunidad y la empresa desde una mirada de respeto, legalidad y responsabilidad, lo que conlleva a gozar y disfrutar de un servicio de energía como aporte a la calidad de vida desde una mirada positiva, en tanto este posibilita la comodidad del ser humano y aporta a su bienestar integral, así mismo se fortalece la cultura de confianza que busca una interacción entre la empresa y los clientes con una comunicación más fluida a través de canales alternativos de comunicación, cuyo uso para consultas y procedimientos asociados al servicio de energía se realicen con confianza y seguridad, lo cual a su vez favorece la sostenibilidad ambiental en el territorio.
        </p>
        <p>
          En este sentido, la Gestión Educativa permite identificar prácticas cotidianas que realizan los grupos de interés y a partir de una orientación reflexiva, guiada desde una postura pedagógica, orientar acciones colectivas que conlleven a la construcción de nuevas prácticas derivadas del Aprendizaje Experiencial.
        </p>
        <p>Descargar Ficha Tecnica <a href="bh/ficheros/FichaTecnicaWeb.pdf" target="_blank">aqui</a></p>
        </p>
      </div>     
    </div>
  </div>


  `;
}

function getHerramientas() {
  return `
  <div class="row">
    <div class="col-xs-4 filters-col">
      <h3>Filtros de búsqueda</h3>
      <div class="filter-container">
        <div class="form-group">
          <label for="comp" class="ontrol-label">Competencia - Comportamiento deseable</label>
          <select class="form-control" id="comp">
            <option value="1">Todos</option>
            <option value="2">Confianza - Uso de Canales vanguardistas</option>
            <option value="3">Respeto - Cultura de pago</option>
            <option value="4">Preservación - Uso responsable de energía</option>
            <option value="5">Cuidado - Disfrute del servicio de energía como aporte a a la calidad de vida</option>
          </select>
        </div>

        <div class="form-group">
          <label for="poblacion" class="ontrol-label">Población objetivo</label>
          <select class="form-control" id="poblacion">
            <option value="1">Todos</option>
            <option value="2">Niños</option>
            <option value="3">Jóvenes</option>
            <option value="4">Adultos</option>
          </select>
        </div>

        <div class="form-group">
          <label for="tema" class="ontrol-label">Tema</label>
          <select class="form-control" id="tema">
            <option value="1" data-tag="1">Todos</option>
            <option value="2" data-tag="4">Ahorro de energía</option>
            <option value="3" data-tag="3">Alumbrado Público</option>
            <option value="4" data-tag="5">Calidad de vida a través del servicio de energía eléctrica</option>
            <option value="5" data-tag="5">Ciudadania</option>
            <option value="6" data-tag="2">Consultas chat y líneas telefónicas</option>
            <option value="7" data-tag="5">Cuidado de lo público</option>
            <option value="8" data-tag="3">Cultura de la legalidad</option>
            <option value="9" data-tag="3">Cultura de la legalidad y marco regulatorio</option>
            <option value="10" data-tag="4">Esquema tarifario y cálculo de consumo</option>
            <option value="11" data-tag="3">Estrategias ecológicas</option>
            <option value="12" data-tag="2">Gobierno en linea</option>
            <option value="13" data-tag="3">Interpretación de la factura</option>
            <option value="14" data-tag="4">Prevencion de riesgo eléctrico</option>
            <option value="15" data-tag="2">Servicio en línea</option>
            <option value="16" data-tag="5">Territorios sostenibles</option>
          </select>
        </div>
        <span><a href="javascript:void(0);" onClick="recargar();">Limpiar Filtro</a></span> 
      </div>
    </div>
    <div class="col-xs-8 ficheros-area">
      <div class="fich-title">
        <h3>Ficheros encontrados</h3>
      </div>      
      <div class="fich-found">
      <div class="file-group" id="first">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="1">
            Servicio en línea
          </span>
        </div>

        <div class="file-group" id="first">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="2">
            Consultas chat y lineas telefonica
          </span>
        </div>

        <div class="file-group" id="first">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="3">
            Gobierno en línea
          </span>
        </div>

        <div class="file-group" id="second">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="4">
            Alumbrado Público
          </span>
        </div>

        <div class="file-group" id="second">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="5">
            Cultura de la legalidad
          </span>
        </div>

        <div class="file-group" id="second">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="6">
            Cultura de la legalidad y marco regulatorio
          </span>
        </div>

        <div class="file-group" id="second">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="7">
            Interpretación de la factura
          </span>
        </div>

        <div class="file-group" id="third">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="8">
            Ahorro de energía
          </span>
        </div>    

         <div class="file-group" id="third">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="9">
            Esquema tarifario y cálculo de consumo
          </span>
        </div>

        <div class="file-group" id="third">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="10">
            Estrategias ecológicas
          </span>
        </div>

        <div class="file-group" id="third">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="11">
            Prevencion de riesgo eléctrico
          </span>
        </div>

        <div class="file-group" id="fourth">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="12">
            Cuidado de lo público
          </span>
        </div>

         <div class="file-group" id="fourth">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="13">
            Calidad de vida a través del servicio de energía eléctrica
          </span>
        </div>

        <div class="file-group" id="fourth">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="14">
            Ciudadania
          </span>
        </div>

         <div class="file-group" id="fourth">
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="file-text" id="15">
            Territorios sostenibles
          </span>
        </div>
      </div>
    </div>
  </div>
  `;

}
 

function getCriterios() {
 /* return `
  <header class="header">
    <nav class="top_menu">
        <ul>
            <li><a href="bh/ficheros/CompetenciaCiudadana.pdf" target="_blank"">Competencia Ciudadana</a></li>
            <li><a href="bh/ficheros/AprendizajeExperiencial.pdf" target="_blank">Aprendizaje Experiencial</a></li>
        </ul>
    </nav>
  </header>

  <div id="presentacion" class="gen-block">
    <div class="text-title-container">
    <h2>Grupos de Interés</h2>
      <div class="floating-text">
        <h4>Nuestros grupos de interés</h4>
        <p>CHEC reconoce como partes interesadas o grupos de interés a las personas o grupos de personas que generan impactos en la organización
        o se ven impactados por las diferentes decisiones, actividades, productos o servicios que brinda como empresa prestadora del servicio
         público de energía.<a href="bh/ficheros/GruposDeInteresChec.pdf" target="_blank">(Ver PDF)</a></p>
      </div>
    <h2>Cultura Ciudadana</h2>
      <div class="floating-text">
          <p>La cultura ciudadana la construimos entre todos a través de las prácticas cotidianas que favorecen el bienestar común. 
          La ciudadanía otorga características especiales a los sujetos como actores activos, con derechos y deberes con ellos mismos, 
          con los demás y con el entorno.  En este sentido, desde la Gestión Educativa, se busca aportar al fortalecimiento de competencias 
          ciudadanas con las cuales las personas lleven a la práctica conocimientos que posibiliten el bienestar de todos los ciudadanos, clientes del 
          servicio de energía eléctrica <a href="bh/ficheros/CulturaCiudadana.pdf" target="_blank">(Ver PDF)</a></p>
      </div>
    <h2>Comportamientos Deseables</h2>
      <div class="floating-text">
          <p>Los comportamientos deseables son las actitudes adecuadas para vivir en sociedad, fomentando el sentido de pertenencia de los ciudadanos 
          por los bienes públicos al hacer un uso responsable de la energía, fortaleciendo la cultura de pago, disfrutando del servicio de energía 
          y reconociendo su aporte a la calidad de vida de los clientes y haciendo uso de los canales vanguardistas para interactuar con la empresa 
          CHEC <a href="bh/ficheros/CulturaCiudadana.pdf" target="_blank">(Ver PDF)</a></p>

    </div>
  </div>`;*/
return `
 <div id="presentacion" class="gen-block">
    <div class="text-title-container">
      <div id="accordion" role="tablist">
      <div class="card">
        <div class="card-header" role="tab" id="headingOne">
          <h5 class="mb-0">
            <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              Grupos de Interés
            </a>
          </h5>
        </div>

        <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
          <div class="card-body">
            <p>CHEC reconoce como partes interesadas o grupos de interés a las personas o grupos de personas que generan impactos en la organización
            o se ven impactados por las diferentes decisiones, actividades, productos o servicios que brinda como empresa prestadora del servicio
             público de energía.<a href="bh/ficheros/GruposDeInteresChec.pdf" target="_blank">(Ver PDF)</a></p>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header" role="tab" id="headingTwo">
          <h5 class="mb-0">
            <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              Competencia Ciudadana
            </a>
          </h5>
        </div>
        <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
          <div class="card-body">
          <p><a href="bh/ficheros/CompetenciaCiudadana.pdf" target="_blank">(Ver PDF)</a></p>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header" role="tab" id="headingThree">
          <h5 class="mb-0">
            <a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              Cultura Ciudadana
            </a>
          </h5>
      </div>
      <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
        <div class="card-body">
          <p>La cultura ciudadana la construimos entre todos a través de las prácticas cotidianas que favorecen el bienestar común. 
          La ciudadanía otorga características especiales a los sujetos como actores activos, con derechos y deberes con ellos mismos, 
          con los demás y con el entorno.  En este sentido, desde la Gestión Educativa, se busca aportar al fortalecimiento de competencias 
          ciudadanas con las cuales las personas lleven a la práctica conocimientos que posibiliten el bienestar de todos los ciudadanos, clientes del 
          servicio de energía eléctrica <a href="bh/ficheros/CulturaCiudadana.pdf" target="_blank">(Ver PDF)</a></p>
        </div>
      </div>
      <div class="card">
        <div class="card-header" role="tab" id="headingFour">
          <h5 class="mb-0">
            <a class="collapsed" data-toggle="collapse" href="#collapseFour" aria-expanded="false" aria-controls="collapseThree">
              Comportamientos Deseables
            </a>
          </h5>
      </div>
      <div id="collapseFour" class="collapse" role="tabpanel" aria-labelledby="headingFour" data-parent="#accordion">
        <div class="card-body">
          <p>Los comportamientos deseables son las actitudes adecuadas para vivir en sociedad, fomentando el sentido de pertenencia de los ciudadanos 
          por los bienes públicos al hacer un uso responsable de la energía, fortaleciendo la cultura de pago, disfrutando del servicio de energía 
          y reconociendo su aporte a la calidad de vida de los clientes y haciendo uso de los canales vanguardistas para interactuar con la empresa 
          CHEC <a href="bh/ficheros/ComportamientosDesables.pdf" target="_blank">(Ver PDF)</a></p>
        </div>
      </div>

      <div class="card">
        <div class="card-header" role="tab" id="headingFive">
          <h5 class="mb-0">
            <a class="collapsed" data-toggle="collapse" href="#collapseFive" aria-expanded="false" aria-controls="collapseThree">
              Aprendizaje Experiencial
            </a>
          </h5>
      </div>
      <div id="collapseFive" class="collapse" role="tabpanel" aria-labelledby="headingFive" data-parent="#accordion">
        <div class="card-body">
          <p><a href="bh/ficheros/AprendizajeExperiencial.pdf" target="_blank">(Ver PDF)</a></p>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>`;

}

function getGlosario() {
  return ``;
}

function getRecurso() {
  return `
  <div class="ficheros-area" id="ficheros-area">
     <div class="fich-found">
      </div>
  </div>`;
}
