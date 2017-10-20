<?php

session_start();

if(isset($_POST["accion"]))
{		
 	if($_POST["accion"]=="traerZona")
	{
		traerZona();
	}
	if($_POST["accion"]=="traerMunicipios")
	{
		traerMunicipios();
	}
	if($_POST["accion"]=="traerIntervencion")
	{
		traerIntervencion();
	}
	if($_POST["accion"]=="guardarDetalleIntervencion")
	{
		guardarDetalleIntervencion();
	}
	if($_POST["accion"]=="traerDetalleIntervencion")
	{
		traerDetalleIntervencion();
	}
}
//include "conexion.php";
if(isset($_POST["button1id"]))//cancelar
{
	header("Location: ../nueva_Intervencion_Gestora.html");
}
if(isset($_POST["button2id"]))//guardar
{
	guardarIntervencion();
}

function traerZona()
{
	$Zona="";
	if(isset($_SESSION["Gestora"]))
	{
		include "conexion.php";
		if($con)
	 	{			
			$Id_Identificacion = $_SESSION["Gestora"];
			$Id_PersonasZona = $_SESSION["IdPersonasPorZona"];
			$sql = "SELECT zonas, id_zona FROM zonas z INNER JOIN personas_por_zona pz ON z.id_zona = pz.zonas_Id_zona AND pz.personas_numeroidentificacion = '".$Id_Identificacion."' AND id_personas_por_zonacol = '".$Id_PersonasZona."'";
		
			$result = $con->query($sql);
			// Parse returned data, and displays them
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  	$Zona = $row["Zonas"];			  	
			}
			echo $Zona;
		}
	}
}
function traerMunicipios()
{
	include "conexion.php";
	if(isset($_SESSION["Id_Zona"]))
	{		
		$id_zona=$_SESSION["Id_Zona"];		
		if( $con ) {
	    	$sql = "SELECT * FROM municipios where id_zona = '".$id_zona."'";
	  		$result = $con->query($sql);

			  // Parse returned data, and displays them
			  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			       echo '<option value="'.$row['id_municipio'].'">'.$row['municipio'].'</option>';
			  }
			  $con = null;
		}
	}
}
function guardarIntervencion()
{
	include "conexion.php";
	$fecha_intervencion = date('Y-m-d');
	//$data = array('error'=>0, 'mensaje'=>'');

		//codigo para guardar

	    //header("Location: ../nueva_Intervencion_Coordinadora.html");
		 		
	/*$Id_Zona = $_POST["selectbasicZona"];
	$Id_Municipio = $_POST["selectbasicMunicipio"];
	$Id_Territorio = $_POST["radiosMunicipio"];
	$Id_Comuna= $_POST["selectbasicComuna"];
	if(isset($_POST["selectbasicBarrio"]))
		$Id_Barrio = $_POST["selectbasicBarrio"];
	if(isset($_POST["selectbasicVereda"]))
		$Id_Vereda = $_POST["selectbasicVereda"];*/
	//$Id_Entidad = $_POST["textinputNombreEntidad"];
	$Id_TipoIntervencion = $_POST["selectbasicTipoInvervencion"];
	$Id_Comportamiento= $_POST["selectbasicComportamiento"];
	//$Cantidad_Indicador = $_POST["cant_indicador"];
	$id_entidad = $_POST["id_Entidades"];	
	/*$id_indicadores = $_POST["indicadores"];	
	for($i=0;$i<=count($id_indicadores);$i++)
	{
		$id_indi = $id_indicadores[$i];		
		echo $in_indi;
		echo "<br>";
	}*/
	if($id_entidad==0)//no encontró una entidad en la base de datos, se recogen los datos de direccion y telefono
	{
		//$Id_Zona = $_POST["selectbasicZona"];
		//$Id_Municipio = $_POST["selectbasicMunicipio"];
		//$Id_Territorio = $_POST["radiosMunicipio"];
		//$Id_Comuna= $_POST["selectbasicComuna"];
		$Id_Barrio=0;
		$Id_Vereda=0;
		if(isset($_POST["selectbasicBarrio"]))
			$Id_Barrio = $_POST["selectbasicBarrio"];
		if(isset($_POST["selectbasicVereda"]))
			$Id_Vereda = $_POST["selectbasicVereda"];
		$Nombre_Entidad = $_POST["textinputNombreEntidad"];
		$telefono = $_POST["textinputTelefono"];
		$direccion = $_POST["textinputDireccion"];
		$Id_TipoEntidad = $_POST["selectbasicTipoEntidad"];
		//$Nodo = $_POST["textinputNodo"];
		$Nodo="";
		$sql = "SELECT max(Id_Entidad) as ultimo_reg FROM entidades";
		$result = $con->query($sql);
		$ultimo_registro_Entidad=0;
		if($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$ultimo_registro = $row["ultimo_reg"];		
			if($ultimo_registro_Entidad=="")
			{
				$ultimo_registro_Entidad=1;
			}
		}
		else
		{
			
			$ultimo_registro_Entidad++;
		}
		$sql="INSERT INTO  entidades values('".$ultimo_registro_Entidad."','".$Nombre_Entidad."',".$Id_Barrio."','".$Id_Vereda."','".$direccion."','".$telefono."','".$Id_TipoEntidad."','".$Nodo."')";	       
	 	$results = $con->query( $sql );	 	
		if($results)
		{
			//echo "guarda entidad";
			$id_entidad = $ultimo_registro_Entidad;
		}		
	}
	$id_Personas_por_Zona =$_SESSION["IdPersonasPorZona"];
	$ultimo_registro_intervencion=0;
	$sql = "SELECT max(id_intervenciones) as ultimo_reg FROM intervenciones";
	$result = $con->query($sql);
	if($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$ultimo_registro = $row["ultimo_reg"];		
		if($ultimo_registro_intervencion=="")
		{
			$ultimo_registro_intervencion=1;
		}
	}
	else
	{
		
		$ultimo_registro_intervencion++;
	}	
	$operador = 1;// falta traer el operador del formulario
	$sql="INSERT INTO  intervenciones (id_intervenciones, entidades_id_Entidad, oOperadores_id_Operadores, personas_por_zona_id_personas_por_zonacol,tipo_intervencion_id_tipo_intervencion,fecha_intervencion)
        values('".$ultimo_registro_intervencion."','".$id_entidad."','".$operador."','".$id_Personas_por_Zona."','".$Id_TipoIntervencion."', '".$fecha_intervencion."')";       
        //echo $sql;
 	$results = $con->query( $sql ); 	
	if($results)
	{
		//$data = array('error'=>0, 'mensaje'=>'Guardo');
		$Cantidad_Indicador = $_POST["cant_indicador"];
		for($i=1;$i<=$Cantidad_Indicador;$i++)
		{
			if(isset($_POST["Indicador".$i]))
			{
				$id_indi = $_POST["Indicador".$i];	
				$sql="INSERT INTO  indicadores_chec_por_intervenciones (indicadores_chec_id_indicadores_chec, intervenciones_id_intervenciones)
	        values('".$id_indi."','".$ultimo_registro_intervencion."')";       
	        //echo $sql;
		 			$results = $con->query( $sql ); 		 			
				if($results)
				{		
					?>
					<script type="text/javascript">
						alert("Se guardo exitosamente");
					</script>
					<?php
					header("Location: ../Home_Gestora.html");
				}		
				else
				{
					$data = array('error'=>1, 'mensaje'=>'no Guardo');
				}
			}
		}
		
		/*case "Asistencia":
			$Id_TipoDocumento = $_POST["selectbasicTipoDocumento"];
			$Numero_Documento = $_POST["textinputDocumento"];
			$Nombres = $_POST["textinputNombres"];
			$Apellidos= $_POST["textinputApellidos"];
			$Sexo = $_POST["radiosSexo"];
			$CuentaChec = $_POST["textinputCuentaCHEC"];
			$Telefono = $_POST["textinputTelefonoAsis"];
			$Celular = $_POST["textinputMovilAsis"];
			$Direccion = $_POST["textinputDireccionAsis"];
			$CorreoElectronico = $_POST["textinputCorreoAsis"];
			$Rol= $_POST["textinputRolAsis"];
			$FechaNacimiento = $_POST["FechainputNacimientoAsis"];
			$ManejoDeDatos = $_POST["radiosManejoDatos"];
			$SesionesFormacion = $_POST["radiosSesionesForma"];
			?>
			<script>alert("Datos alamcenados");</script>
			<?php
		break;*/
	}
	//echo json_encode($data);
}

function traerIntervencion()
{
	include "conexion.php";
	$zonas_por_intervencion=array();

	$id_Personas_por_Zona = $_SESSION["IdPersonasPorZona"];
	$intervencion_por_zona= "SELECT i.id_intervenciones, i.fecha_intervencion, ti.tipo_intervencion, m.municipio from intervenciones i inner join entidades e on i.entidades_id_entidad = e.id_entidad inner join tipo_intervencion ti on i.Tipo_intervencion_id_tipo_intervencion = ti.id_tipo_intervencion left join barrios b on e.id_barrio = b.id_barrio left join veredas v on v.id_veredas = e.id_veredas left join comunas c on c.id_comuna = b.id_comuna left join municipios m on m.id_municipio = c.id_municipio or v.id_municipio = m.id_municipio where i.personas_por_zona_id_Personas_por_Zonacol = '".$id_Personas_por_Zona."'";
	$resultados_zona = $con->query($intervencion_por_zona);
	$contador=0;
	while($row = $resultados_zona->fetch(PDO::FETCH_ASSOC)) {

	     $intervencion[$contador]["id_Intervenciones"] =  $row["id_Intervenciones"];
	     $intervencion[$contador]["Fecha_Intervencion"] =  $row["Fecha_Intervencion"];
	     $intervencion[$contador]["Tipo_Intervencion"] =  $row["Tipo_Intervencion"];
	     $intervencion[$contador]["Municipio"] =  $row["Municipio"];
	     $contador++;
	  }
	 $cantidad_intervenciones_por_zona = $contador;
	$intervenciones_por_comportamiento = "SELECT c.comportamientos from indicadores_chec_por_intervenciones ici inner join intervenciones i on
	ici.intervenciones_id_intervenciones = i.id_intervenciones inner join indicadores_chec ic on ici.indicadores_chec_id_indicadores_chec = ic.id_indicadores_chec
	inner join comportamientos c on ic.comportamientos_id_comportamientos = c.id_comportamientos where i.personas_por_zona_id_personas_por_zonacol = '".$id_Personas_por_Zona."' group by c.Comportamientos";

	  	$resultados_comportamiento = $con->query($intervenciones_por_comportamiento);
	  	$contador=0;
	  // Parse returned data, and displays them
	  while($row = $resultados_comportamiento->fetch(PDO::FETCH_ASSOC)) {
	  		$intervencion[$contador]["Comportamientos"] =  $row["Comportamientos"];
	     $contador++;
	     
	  }
		if($cantidad_intervenciones_por_zona == $contador)//si la cantidad de las intervenciones son las mismas, se guarda la cantidad en una variable para el ciclo
		{
			$cantidad_intervenciones = $cantidad_intervenciones_por_zona;
		}
	  //PENDIENTE HACER UN FOR
		for($cont=0;$cont<$cantidad_intervenciones;$cont++)
		{
	  		$fecha_intervencion = str_replace("-", "/", $intervencion[$cont]["Fecha_Intervencion"]);

	  		/*echo "<a id=".$intervencion[$cont]["id_Intervenciones"]." href='detalle_Intervencion_Gestora.html' class='list-group-item active'>".$intervencion[$cont]["Municipio"]." - ".$fecha_intervencion." - ".$intervencion[$cont]["Tipo_Intervencion"]." - ".$intervencion[$cont]["Comportamientos"]."</a>";*/
	  		echo "".$intervencion[$cont]["Municipio"]." - ".$fecha_intervencion." - ".$intervencion[$cont]["Tipo_Intervencion"]." - ".$intervencion[$cont]["Comportamientos"]."";
	  	}
}

function guardarDetalleIntervencion()
{
	if(isset($_POST["idIntervencion"]))
	{
		$_SESSION["idIntervencion"] = $_POST["idIntervencion"];
		echo $_SESSION["idIntervencion"];
	}
}
function traerDetalleIntervencion()
{
	include "conexion.php";

	$detalle=array();
	if(isset($_SESSION["IdPersonasPorZona"]) && isset($_SESSION["idIntervencion"]))
	{
		$id_Personas_por_Zona=$_SESSION["IdPersonasPorZona"];
		$idIntervencion=$_SESSION["idIntervencion"];
	//if(isset($_POST["idIntervencion"]))
	//{
		//$_SESSION["idIntervencion"] = $_POST["idIntervencion"];
		//$idIntervencion=$_POST["idIntervencion"];
		$detalleIntervencion= "SELECT i.id_Intervenciones, i.Fecha_Intervencion, ti.Tipo_Intervencion, m.Municipio from intervenciones i inner join entidades e on i.Entidades_Id_Entidad = e.Id_Entidad inner join tipo_intervencion ti on i.Tipo_intervencion_id_Tipo_intervencion = ti.id_Tipo_intervencion left join barrios b on e.Id_Barrio = b.Id_Barrio left join veredas v on v.id_Veredas = e.Id_Veredas left join comunas c on c.Id_Comuna = b.Id_Comuna left join municipios m on m.Id_Municipio = c.Id_Municipio or v.Id_Municipio = m.Id_Municipio where i.id_Intervenciones= ".$idIntervencion." and i.Personas_por_Zona_id_Personas_por_Zonacol = '".$id_Personas_por_Zona."'";

		$detalleIntervencion= "SELECT 
			i.id_intervenciones, 
			e.nombreentidad, 
			i.fecha_intervencion, 
			ti.tipo_intervencion, 
			m.municipio, 
			comp.comportamientos,
			compe.competencia
			from intervenciones i inner join entidades e on i.entidades_id_entidad = e.id_entidad 
			inner join tipo_intervencion ti on 
			i.tipo_intervencion_id_tipo_intervencion = ti.id_tipo_intervencion 
			left join barrios b on e.id_barrio = b.id_barrio left join veredas v 
			on v.id_veredas = e.id_veredas left join comunas c 
			on c.id_comuna = b.id_comuna 
			left join municipios m 
			on m.id_municipio = c.id_municipio or v.id_municipio = m.id_municipio 

			inner join indicadores_chec_por_intervenciones ici on
			ici.intervenciones_id_intervenciones = i.id_intervenciones
			inner join indicadores_chec ic on ici.indicadores_chec_id_indicadores_chec = ic.id_indicadores_chec
			inner join comportamientos comp on ic.comportamientos_id_comportamientos = comp.id_comportamientos

			inner join competencias_por_comportamiento cpc on
			comp.id_comportamientos = cpc.comportamientos_id_comportamientos
			inner join competencias compe on
			cpc.competencias_id_competencia = compe.id_competencia

			where i.id_intervenciones= '".$idIntervencion."'
			and i.personas_por_zona_id_personas_por_zonacol = '".$id_Personas_por_Zona."'
			group by i.id_intervenciones, e.nombreEntidad, i.fecha_intervencion, ti.tipo_intervencion, m.municipio,comp.comportamientos,compe.competencia";

		
		$resultados_detalle = $con->query($detalleIntervencion);
		while($row = $resultados_detalle->fetch(PDO::FETCH_ASSOC)) {
		  		$detalle["Municipio"] =  $row["municipio"];
		  		$detalle["NombreEntidad"] =  $row["nombreentidad"];
		  		$detalle["Tipo_Intervencion"] =  $row["tipo_intervencion"];
		  		$detalle["Comportamientos"] =  $row["comportamientos"];
		  		$detalle["Competencia"] =  $row["competencia"];		     
		  }

		  $detalleIndicadores= "SELECT indicador from indicadores_chec_por_intervenciones ici inner join intervenciones i on
	ici.intervenciones_id_intervenciones = i.id_intervenciones inner join indicadores_chec ic on ici.indicadores_chec_id_indicadores_chec = ic.id_indicadores_chec
	inner join comportamientos c on ic.comportamientos_id_comportamientos = c.id_comportamientos where i.id_intervenciones= '".$idIntervencion."' and i.personas_por_zona_id_personas_por_zonacol = '".$id_Personas_por_Zona."'";
	$resultados_detalle1 = $con->query($detalleIndicadores);
	$i=0;
		while($row = $resultados_detalle1->fetch(PDO::FETCH_ASSOC)) {
		  		$detalle["Indicadores".$i] =  $row["indicador"];	     
		  		$i++;
		  } 
		  $detalle["cantidad"] = $i;

	  echo json_encode($detalle);//,$detalle_indicadores);
	}
		  //echo $_POST["idIntervencion"];
	//}
}

	
//}
?>