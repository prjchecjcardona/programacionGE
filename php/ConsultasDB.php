<?php
 include('conexion.php');
$TipoConsulta=$_GET["consulta"];
//if($TipoConsulta=='1') 
switch ($TipoConsulta) {
	case '1':
		# code...
			if( $con )
		 	{
		    	$sql = "SELECT * FROM zonas";
		  		$result = $con->query($sql);

			  // Parse returned data, and displays them
			  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			       echo '<option value="'.$row['Id_Zona'].'">'.$row['Zonas'].'</option>';
			  }

			  $conn = null;        // Disconnect
			}
		break;
	
	//if ($TipoConsulta=='2') 
	case '2':

		$id_zona=$_GET["Id_Zona"];
		if( $con ) {
	    	$sql = "SELECT * FROM municipios where Id_Zona = '".$id_zona."'";
	  		$result = $con->query($sql);

		  // Parse returned data, and displays them
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		       echo '<option value="'.$row['Id_Municipio'].'">'.$row['Municipio'].'</option>';
		  }
		  $conn = null;        // Disconnect
		}
		break;
	case '3':

		$id_municipio=$_GET["Id_Municipio"];
		if( $con ) {
			//consulta de las comunas segun el municipio
	    	$sql = "SELECT * FROM comunas where Id_Municipio = '".$id_municipio."'";
	  		$result = $con->query($sql);

		  // Parse returned data, and displays them
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		       echo '<option value="'.$row['Id_Comuna'].'">'.$row['Comuna'].'</option>';
		  }
		  //consulta de las veredas segun el municipio
		  $sql = "SELECT * FROM veredas where Id_Municipio = '".$id_municipio."'";
	  		$result = $con->query($sql);

		  // Parse returned data, and displays them
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		       echo '<option value="'.$row['Id_Comuna'].'">'.$row['Comuna'].'</option>';
		  }
		  $conn = null;        // Disconnect
		}
	 	break;
	 case '4':

		$id_municipio=$_GET["Id_Municipio"];
		if( $con ) {
	    	$sql = "SELECT * FROM veredas where Id_Municipio = '".$id_municipio."'";
	  		$result = $con->query($sql);

		  // Parse returned data, and displays them
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		       echo '<option value="'.$row['Id_Veredas'].'">'.$row['Vereda'].'</option>';
		  }
		  $conn = null;        // Disconnect
		}
		break;
	case '5':

		$id_comuna=$_GET["Id_Comuna"];
		if( $con ) {
	    	$sql = "SELECT * FROM barrios where Id_Comuna = '".$id_comuna."'";
	  		$result = $con->query($sql);

		  // Parse returned data, and displays them
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		       echo '<option value="'.$row['Id_Barrio'].'">'.$row['Barrio'].'</option>';
		  }
		  $conn = null;        // Disconnect
		}
		break;
	case '6':

		$id_barrio=$_GET["barrio"];
		$id_vereda=$_GET["vereda"];		
		//$nombrentidad=$_GET['keyword'];
		//strval($_POST['query']);
		$nombrentidad=$_GET['service'];	
		//$nombrentidad=$_GET["query"];		
		if( $con ) {			
	    	/*$sql = "SELECT NombreEntidad FROM entidades where Id_Barrio = '".$id_barrio."' and Id_Veredas = '".$id_vereda."' and NombreEntidad like '%".$nombrentidad."%'";
	  		$result = $con->query($sql);
	  		$json = [];
		  // Parse returned data, and displays them
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		        $json[] = $row['NombreEntidad'];
		  		//echo  $row['NombreEntidad'];
		  }*/



		 /* $sql = $con->prepare('SELECT * FROM entidades WHERE Id_Barrio = ? AND  Id_Veredas = ? AND NombreEntidad LIKE "?%"');		  
		  $data = array($id_barrio,$id_vereda,$nombrentidad);
//$query->execute(array($nombrentidad));
		  $sql->execute($data);
		  $json = [];
while ($str=$sql->fetch(PDO::FETCH_ASSOC))
{
    //echo $str['NombreEntidad'];
    echo "no";
    $json[] = $str['NombreEntidad'];
}
 echo json_encode($json);*/


//$nombrentidad="Institu";
$entidad = "'%$nombrentidad%'";
 //$gsent = $con->prepare('SELECT * FROM entidades WHERE Id_Barrio=:barrio AND Id_Veredas=:vereda AND NombreEntidad LIKE :entidad');
//$gsent = $con->prepare('SELECT NombreEntidad FROM entidades WHERE Id_Barrio=3 AND Id_Veredas=0 AND NombreEntidad LIKE %Instituci%');
$gsent = $con->prepare('SELECT Id_Entidad,NombreEntidad FROM entidades WHERE Id_Barrio='.$id_barrio.' AND Id_Veredas='.$id_vereda.' AND NombreEntidad LIKE '.$entidad.'');
 //$gsent = $con->prepare('SELECT * FROM entidades WHERE NombreEntidad LIKE :entidad');
//$gsent->bindParam(':barrio', $id_barrio,PDO::PARAM_INT);
//$gsent->bindParam(':vereda', $id_vereda,PDO::PARAM_INT);
//$gsent->bindParam(':entidad',$entidad,PDO::PARAM_STR);
$gsent->execute();
//$json = [];

while ($str=$gsent->fetch(PDO::FETCH_ASSOC))
{
    //echo $str['NombreEntidad'];    
   //$array = array();
   echo '<div class="suggest-element"><a data="'.$str['NombreEntidad'].'" id="service'.$str['Id_Entidad'].'">'.$str['NombreEntidad'].'</a></div>';
        
        //$json[] = $str['NombreEntidad'];// $str['NombreEntidad'];
}
 //echo json_encode($array);




//$sql = $pdo->prepare("select id,name,biography from authors where id= ?");
//$data = array($_POST['id']);
//$sql->execute($data);
//$str=$sql->fetch(PDO::FETCH_ASSOC);
		 // echo json_encode($json);
		}
		break;

	case '7':
	# code...
		if( $con )
	 	{
	    	$sql = "SELECT * FROM tipoentidad";
	  		$result = $con->query($sql);

		  // Parse returned data, and displays them
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		       echo '<option value="'.$row['Id_TipoEntidad'].'">'.$row['TipoEntidad'].'</option>';
		  }

		  $conn = null;        // Disconnect
		}
	break;
	case '8':
	# code...
		if( $con )
	 	{
	    	$sql = "SELECT * FROM tipo_intervencion";
	  		$result = $con->query($sql);

		  // Parse returned data, and displays them
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		       echo '<option value="'.$row['id_Tipo_intervencion'].'">'.$row['Tipo_intervencion'].'</option>';
		  }

		  $conn = null;        // Disconnect
		}
	break;
	case '9':
	# code...
		if( $con )
	 	{
	    	$sql = "SELECT * FROM comportamientos";
	  		$result = $con->query($sql);

		  // Parse returned data, and displays them
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		       echo '<option value="'.$row['Id_Comportamientos'].'">'.$row['Comportamientos'].'</option>';
		  }

		  $conn = null;        // Disconnect
		}
	break;
	case '10':
	# code...
		if( $con )
	 	{
	 		$id_Comportamiento=$_GET["Id_Comportamiento"];
	    	$sql = "SELECT * FROM indicadores_chec where Comportamientos_Id_Comportamientos = '".$id_Comportamiento."'";
	  		$result = $con->query($sql);

		  // Parse returned data, and displays them
	  		$cont=0;
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$cont++;
				 echo '<div class="checkbox">
                  <label class="grisTexto"><input type="checkbox" name="Indicador'.$cont.'" value="'.$row['id_Indicadores_CHEC'].'">'.$row['Indicador'].'</label>
                </div>';
		  }
		  echo '<input type="hidden" name="cant_indicador" value="'.$cont.'">';
		  $conn = null;        // Disconnect
		}
	break;
	case '11':
	# code...
		if( $con ) {
	    	$sql = "SELECT * FROM tipo_documento";
		  		$result = $con->query($sql);

			  // Parse returned data, and displays them
			  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			       echo '<option value="'.$row['id_Tipo_Documento'].'">'.$row['Tipo_Documento'].'</option>';
		  }
		  $conn = null;        // Disconnect
		}
		break;
	case '12':
	# code...
		if( $con ) {
	    	$sql = "SELECT * FROM jornada";
		  		$result = $con->query($sql);

			  // Parse returned data, and displays them
			  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			       echo '<option value="'.$row['Id_Jornada'].'">'.$row['Jornada'].'</option>';
		  }
		  $conn = null;        // Disconnect
		}
		break;
	case '13':
	# code...
		if( $con ) {
	    	$sql = "SELECT * FROM tipopoblacion";
		  		$result = $con->query($sql);

			  // Parse returned data, and displays them
			  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			       echo '<option value="'.$row['Id_TipoPoblacion'].'">'.$row['TipoPoblacion'].'</option>';
		  }
		  $conn = null;        // Disconnect
		}
		break;
	case '14':
	# code...
		if( $con ) {
	    	$sql = "SELECT * FROM estrategias";
		  		$result = $con->query($sql);

			  // Parse returned data, and displays them
			  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			       echo '<option value="'.$row['Id_Estrategia'].'">'.$row['NombreEstrategia'].'</option>';
		  }
		  $conn = null;        // Disconnect
		}
		break;
	case '15':

		$id_estrategia=$_GET["Id_Estrategia"];
		if( $con ) {
	    	$sql = "SELECT * FROM tactico where Id_Estrategia = '".$id_estrategia."'";
	  		$result = $con->query($sql);

		  // Parse returned data, and displays them
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		       echo '<option value="'.$row['Id_Tactico'].'">'.$row['NombreTactico'].'</option>';
		  }
		  $conn = null;        // Disconnect
		}
		break;
	default:
		# code...
		break;
}

?>