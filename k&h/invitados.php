<?php 

$servername = "localhost";
$username = "jeanshmo_user";
$password = "18denoviembrede2022";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
  //die("Connection failed: " . $conn->connect_error);
}else{
    //echo "Connected successfully";
   // $db = mysqli_select_db( $conn, 'ivdxxqui_pindustrial' );
    $db = mysqli_select_db( $conn, 'inv_boda' );
    //echo "entra";
}



$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 0;

switch ($tipo) {
	// GET DE INVITADOS
		$id = isset($_POST['id']) ? $_POST['id'] : 0;
		$sql="SELECT * FROM invitados WHERE id=$id and status=1 and padre=0";
		$resultado = $conn->query($sql);
     	$fila = $resultado->fetch_assoc();
     	$id=$fila['id'];
     	$tipo=$fila['tipo'];
     	$cantidad=$fila['cantidad'];
     	$padre=$fila['padre'];
     	$confirmado=$fila['confirmado'];

     	if ($cantidad == 1) {
     		$lugar="lugar";
     	}else{
     		$lugar="lugares";
     	}

     	if ($confirmado == 1) {
     		$result = "<h3 class='font-riley' style='letter-spacing: 2px; margin-top: 2%; font-size:2.3em; '>Tu asistencia ha sido confirmada, muchas gracias.</h3><br><br><br><br><br><br>";
     	}else{

     		if ($tipo == 1) {
     			$result = "<h3 class='font-riley' style='letter-spacing: 2px; margin-top: 2%; font-size:2.3em; '>Hemos reservado {$cantidad} {$lugar} para ti,<br> te agradeceríamos confirmar tu asistencia.</h3><br><button type='button' class='btn btn-lg btn-confirmar' onclick='Confirmar({$id})' style='font-size:2em;'>Confirmar asistencia <i class='fa fa-check'></i></button><br><br><br><br><br><br>";
     		}else{



     			$nombre=$fila['nombre'];
     			$result = "<h3 class='font-riley' style='letter-spacing: 2px; margin-top: 2%; font-size:2.3em;'>Hemos reservado los siguientes lugares para ti,<br> te agradeceríamos confirmar tu asistencia.</h3><br>";

     			$result.="<div style='text-align:center; font-size:2.3em;'>
     			<table style='width:80%; color:white; margin: 0 auto; text-align:left;' class='table table-bordered'>
     					   <tr>
     					   <td style='text-align:center;'><b>¿Asistirá?</b></td>
     					   <td><b>Nombre</b></td>
     					   </tr>
     			           <tr>
     			           	 <td style='text-align:center;'><input class='form-check-input' type='checkbox' value='' id='check_{$id}'></td>
     			           	 <td>{$nombre}</td>
     			           </tr>";
     			
     			$sql2="SELECT * FROM invitados WHERE padre=$id and status=1";
				$resultado2 = $conn->query($sql2);
				while ($fila2 = $resultado2->fetch_assoc()) {

					
					$id2=$fila2['id'];
     				$nombre2=$fila2['nombre'];
     				$result.="<tr>
     							<td style='text-align:center;'><input class='form-check-input' type='checkbox' value='' id='check_{$id2}'></td>
     			           	 	<td>{$nombre2}</td>
     						  </tr>";
				}

     			$result.="</table><br><button type='button' class='btn btn-lg btn-confirmar' onclick='Confirmar2({$id})' style='font-size:1.1em;'>Confirmar asistencia <i class='fa fa-check'></i></button></div><br><br><br><br><br><br><br><br>";

     				
     		}

     	}
     	//echo $result;

     	echo json_encode(array($result));

	 
	 break;
	
	// CONFIRMAR TIPO 1
	case '2':
		$id = isset($_POST['id']) ? $_POST['id'] : 0;
		$sql="UPDATE invitados SET confirmado=1 WHERE id=$id";
		$resultado = $conn->query($sql);
		if ($resultado) {
			echo json_encode(array(1,"Confirmación realizada correctamente, muchas gracias"));
		}else{
			echo json_encode(array(0,"Error al realizar confirmación, por favor inténtelo de nuevo"));
		}
	 
	 break;

	// CONFIRMAR TIPO 2
	case '3':
		$selected = isset($_POST['selected']) ? $_POST['selected'] : 0;
		$sql="";
		for ($i=0; $i <= sizeof($selected)-1 ; $i++) { 
			$id=$selected[$i];
			$sql="UPDATE invitados SET confirmado=1 WHERE id=$id;";
			$resultado = $conn->query($sql);
		}

		echo json_encode(array(1,"Confirmación realizada correctamente, muchas gracias"));
		

	 break;

	default:
		# code...
		break;
}

?>