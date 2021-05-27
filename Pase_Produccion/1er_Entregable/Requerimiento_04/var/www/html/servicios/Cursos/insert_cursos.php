
<?php

if (empty($_POST['TXT_NOMBRE'])) {
	$errors[] = "Ingresa el nombre.";
} elseif (!empty($_POST['TXT_NOMBRE'])) {
	require_once("../../servicios/conexion.php"); //Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code

	$ACCION_CUR = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["ACCION_CUR"], ENT_QUOTES)));
	$TXT_CURSO_CATEGORIA = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_CURSO_CATEGORIA"], ENT_QUOTES)));
	$ID_CURSO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["ID_CURSO"], ENT_QUOTES)));
	$TXT_NOMBRE = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_NOMBRE"], ENT_QUOTES)));
	$TXT_SHORT_NOMBRE = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_SHORT_NOMBRE"], ENT_QUOTES)));
	// $TXA_DESCRIPCION = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXA_DESCRIPCION"], ENT_QUOTES)));
	$TXA_DESCRIPCION = $_POST["TXA_DESCRIPCION"];
	$TXT_FECHA_INICIO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_FECHA_INICIO"], ENT_QUOTES)));
	$TXT_FECHA_FIN = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_FECHA_FIN"], ENT_QUOTES)));
	$TXT_HORA_INICIO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_HORA_INICIO"], ENT_QUOTES)));
	$TXT_HORA_FIN = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_HORA_FIN"], ENT_QUOTES)));
	$TXT_LUGAR = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_LUGAR"], ENT_QUOTES)));
	$TXT_PUBLICO_DIRIGINDO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_PUBLICO_DIRIGINDO"], ENT_QUOTES)));
	$CBO_ESTADO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["CBO_ESTADO"], ENT_QUOTES)));
	$TXT_VAL_IMAGEN = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_VAL_IMAGEN"], ENT_QUOTES)));
	$TXT_VAL_IMAGEN = empty(trim($TXT_VAL_IMAGEN)) ? 'default.jpg' : $TXT_VAL_IMAGEN;
	$ID_USUARIO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["ID_USUARIO"], ENT_QUOTES)));
	$V_RUTA = mysqli_real_escape_string($mysqlConeccion, (strip_tags("/Archivos/IMAGEN/", ENT_QUOTES)));

	$TXT_HORALECTIVA = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_HORALECTIVA"], ENT_QUOTES)));
	$TXT_MODALIDAD = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_MODALIDAD"], ENT_QUOTES)));
	$TXT_NUMEVENTO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_NUMEVENTO"], ENT_QUOTES)));
	$TXT_CODPOI = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_CODPOI"], ENT_QUOTES)));
	$CBO_PUBLICAR = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["CBO_PUBLICAR"], ENT_QUOTES)));

	$archivo = (isset($_FILES['txf_adjuntar_imagen'])) ? $_FILES['txf_adjuntar_imagen'] : null;

	/* if ($archivo) {
      $ruta_destino_archivo = $_SERVER['DOCUMENT_ROOT'] ."/Archivos/IMAGEN/{$archivo['name']}";
      $archivo_ok = move_uploaded_file($archivo['tmp_name'], $ruta_destino_archivo);
   }*/
	$archrepe = 1;

	$nombreArchivo = '';
	// if ($archivo) {
	// 	$files = glob('../..' . $V_RUTA . '*'); //obtenemos todos los nombres de los ficheros
	// 	foreach ($files as $file) {

	// 		$insarch = "../.." . $V_RUTA . $archivo['name'];
	// 		//$data .=$file." ---- " .$insarch."---";
	// 		if ($file == $insarch) {
	// 			$archrepe = 0;
	// 			//	$data .=$archrepe;
	// 			break;
	// 		}
	// 		// unlink($file); //elimino el fichero
	// 	}

	// 	if ($archrepe != 0) {
	// 		$archrepe = 1;
	// 		// $data .= $archrepe;
	// 		// $ruta_destino_archivo = $_SERVER['DOCUMENT_ROOT'] . "/Archivos/IMAGEN/{$archivo['name']}";
	// 		// $archivo_ok = move_uploaded_file($archivo['tmp_name'], $ruta_destino_archivo);

	// 		require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config.php';
	// 		$connection = ssh2_connect(SFTP_HOST, SFTP_PORT);
	// 		if (ssh2_auth_password($connection, SFTP_USER, SFTP_PASS)) {
	// 			$nombreArchivo = date('dmyhisv') . $archivo['name'];
	// 			$rutaUpdoad = $_SERVER['DOCUMENT_ROOT'] . '/Archivos/IMAGEN/';
	// 			ssh2_scp_send($connection, $archivo['tmp_name'], $rutaUpdoad . $nombreArchivo, 0644);
	// 		}
	// 	}
	// }


	$data = "";
	$sql = "";

	if ($archrepe != 0) {

		if ($_POST["ACCION_CUR"] == "0") {

			if ($archivo) {

				$nombreArchivo = round(microtime(true) * 1000) . '-' . $archivo['name'];

				//bartoloricsi@gmail.com
				$ruta_destino_archivo = $_SERVER['DOCUMENT_ROOT'] . "/Archivos/IMAGEN/{$nombreArchivo}";
				move_uploaded_file($archivo['tmp_name'], $ruta_destino_archivo);
				
				/*
				require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config.php';
				$connection = ssh2_connect(SFTP_HOST, SFTP_PORT);
				if (ssh2_auth_password($connection, SFTP_USER, SFTP_PASS)) {
					$rutaUpdoad = $_SERVER['DOCUMENT_ROOT'] . '/Archivos/IMAGEN/';
					ssh2_scp_send($connection, $archivo['tmp_name'], $rutaUpdoad . $nombreArchivo, 0644);
				}
				*/
				
			} else {
				$nombreArchivo = $TXT_VAL_IMAGEN;
			}

			// REGISTER data into database
			//$sql = "call sp_ins_curso('$TXT_NOMBRE','$TXT_SHORT_NOMBRE','$TXA_DESCRIPCION',STR_TO_DATE('$TXT_FECHA_INICIO','%d/%m/%Y'),STR_TO_DATE('$TXT_FECHA_FIN','%d/%m/%Y'),'$TXT_HORA_INICIO','$TXT_HORA_FIN','$TXT_LUGAR','$TXT_PUBLICO_DIRIGINDO','$nombreArchivo','$V_RUTA','$CBO_ESTADO','$ID_USUARIO','$TXT_CURSO_CATEGORIA',@message);";
			$sql = "call sp_ins_curso_V2('$TXT_NOMBRE','$TXT_SHORT_NOMBRE','$TXA_DESCRIPCION',STR_TO_DATE('$TXT_FECHA_INICIO','%d/%m/%Y'),STR_TO_DATE('$TXT_FECHA_FIN','%d/%m/%Y'),'$TXT_HORA_INICIO','$TXT_HORA_FIN','$TXT_LUGAR','$TXT_PUBLICO_DIRIGINDO','$nombreArchivo','$V_RUTA','$CBO_ESTADO','$ID_USUARIO','$TXT_CURSO_CATEGORIA','$TXT_HORALECTIVA','$TXT_MODALIDAD','$TXT_NUMEVENTO','$TXT_CODPOI', '$CBO_PUBLICAR', @message);";
		}
		if ($_POST["ACCION_CUR"] == "1") {

			if ($archivo) {

				$nombreArchivo = round(microtime(true) * 1000) . '-' . $archivo['name'];

				// $ruta_destino_archivo = $_SERVER['DOCUMENT_ROOT'] . "/Archivos/IMAGEN/{$nombreArchivo}";
				// move_uploaded_file($archivo['tmp_name'], $ruta_destino_archivo);
				require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config.php';
				$connection = ssh2_connect(SFTP_HOST, SFTP_PORT);
				if (ssh2_auth_password($connection, SFTP_USER, SFTP_PASS)) {
					$rutaUpdoad = $_SERVER['DOCUMENT_ROOT'] . '/Archivos/IMAGEN/';
					ssh2_scp_send($connection, $archivo['tmp_name'], $rutaUpdoad . $nombreArchivo, 0644);
				}
			} else {
				$nombreArchivo = $TXT_VAL_IMAGEN;
			}

			//$sql = "call sp_upd_curso('$ID_CURSO','$TXT_NOMBRE','$TXA_DESCRIPCION',STR_TO_DATE('$TXT_FECHA_INICIO','%d/%m/%Y'),STR_TO_DATE('$TXT_FECHA_FIN','%d/%m/%Y'),'$TXT_HORA_INICIO','$TXT_HORA_FIN','$TXT_LUGAR','$TXT_PUBLICO_DIRIGINDO','$nombreArchivo','$V_RUTA','$CBO_ESTADO', '$TXT_CURSO_CATEGORIA');";
			$sql = "call sp_upd_curso_V2('$ID_CURSO','$TXT_NOMBRE','$TXA_DESCRIPCION',STR_TO_DATE('$TXT_FECHA_INICIO','%d/%m/%Y'),STR_TO_DATE('$TXT_FECHA_FIN','%d/%m/%Y'),'$TXT_HORA_INICIO','$TXT_HORA_FIN','$TXT_LUGAR','$TXT_PUBLICO_DIRIGINDO','$nombreArchivo','$V_RUTA','$CBO_ESTADO','$TXT_HORALECTIVA','$TXT_MODALIDAD','$TXT_NUMEVENTO','$TXT_CODPOI', '$CBO_PUBLICAR', '$TXT_CURSO_CATEGORIA');";
		}



		$query = mysqli_query($mysqlConeccion, $sql);
	}


	if ($archrepe == 0) {
		$data = "Nombre de archivo repetido";
	}
	if ($archrepe != 0) {
		if ($_POST["ACCION_CUR"] == "0") {

			// REGISTER data into database
			$rpta = mysqli_query($mysqlConeccion, "select @message;");
			// 	$msgArray = mysqli_fetch_assoc($rpta)
			while ($row = mysqli_fetch_array($rpta)) {
				$matriculas[] = $row;
			}
			foreach ($matriculas as $datosMatricula) {
				$data .= $datosMatricula[0];
			}
		}
		if ($_POST["ACCION_CUR"] == "1") {
			$data = "actualizado";
		}
	}


	// if product has been added successfully
	if ($archrepe == 0) {
		$messages[] = $data;
	}
	if ($archrepe != 0) {
		if ($query) {
			$messages[] = $data;
		} else {
			$errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo.";
		}
	}
} else {
	$errors[] = "desconocido.";
}
if (isset($errors)) {

?>

					<?php
					foreach ($errors as $error) {
						echo $error;
					}
					?>
			<?php
		}
		if (isset($messages)) {

			?>
	
						<?php
						foreach ($messages as $message) {
							echo $message;
						}
						?>

				<?php
			}
				?>




