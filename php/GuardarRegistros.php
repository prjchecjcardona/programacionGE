<?php

$tipoformulario = $_POST["TipoFormulario"];
if (isset($_POST['accion']) and $_POST['accion'] == 'guardarIntervencion')
{
	$data = array('error'=>0, 'mensaje'=>'');

	//codigo para guardar

    header("Location: ../nueva_Intervencion_Coordinadora.html");

	 "IntervencionCoordinadora" ;		
			$Id_Zona = $_POST["selectbasicZona"];
			$Id_Municipio = $_POST["selectbasicMunicipio"];
			$Id_Territorio = $_POST["radiosMunicipio"];
			$Id_Comuna= $_POST["selectbasicComuna"];
			if(isset($_POST["selectbasicBarrio"]))
				$Id_Barrio = $_POST["selectbasicBarrio"];
			if(isset($_POST["selectbasicVereda"]))
				$Id_Vereda = $_POST["selectbasicVereda"];
			$Id_Entidad = $_POST["textinputNombreEntidad"];
			$Telefono = $_POST["textinputTelefono"];
			$Direccion = $_POST["textinputDireccion"];
			$Id_TipoIntervencion = $_POST["selectbasicTipoInvervencion"];
			$Id_Comportamiento= $_POST["selectbasicComportamiento"];
			$Id_TipoIntervencion = $_POST["selectbasicTipoInvervencion"];
			$Cantidad_Indicador = $_POST["cant_indicador"];
			$id_entidad = $_POST["id_Entidades"];
			for($i=1;$i<=$Cantidad_Indicador;$i++){
				if(isset($_POST["Indicador".$i])){
					$id_indicador = $_POST["Indicador".$i];
				}	
			}
			if($id_entidad==0)//no encontró una entidad en la base de datos, se recogen los datos de direccion y telefono
			{
				$telefono = $_POST["textinputTelefono"];
				$direccion = $_POST["textinputDireccion"];
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
	echo json_encode($data);

	
}
?>