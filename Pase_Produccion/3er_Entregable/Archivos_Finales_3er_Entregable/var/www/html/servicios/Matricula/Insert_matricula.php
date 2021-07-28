<?php
//var_dump($_POST);
//var_dump($_FILES);

if (empty($_POST['txt_NOMBRE'])) {
	$errors[] = "Ingresa el nombre.";
}
if (empty($_POST['txt_RUC_ENT'])) {
	$errors[] = "Ingresa el RUC.";
}
if (empty($_POST['txt_DNI'])) {
	$errors[] = "Ingresa el DNI.";
} elseif (!empty($_POST['txt_NOMBRE'])) {
	require_once("../../servicios/conexion.php"); //Contiene funcion que conecta a la base de datos

	// escaping, additionally removing everything that could be (html/javascript-) code
	$TXT_ID_CURSO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_ID_CURSO"], ENT_QUOTES)));
	$txt_NOMBRE = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["txt_NOMBRE"], ENT_QUOTES)));
	$txt_APEPAT = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["txt_APEPAT"], ENT_QUOTES)));
	$txt_APEMAT = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["txt_APEMAT"], ENT_QUOTES)));
	$cboTipoDocIdentidad = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["cboTipoDocIdentidad"], ENT_QUOTES)));
	$txt_DNI = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["txt_DNI"], ENT_QUOTES)));
	$txt_PROFESION = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["txt_PROFESION"], ENT_QUOTES)));
	$CBO_GENERO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["CBO_GENERO"], ENT_QUOTES)));
	$CBO_GRADO_ACAD = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["CBO_GRADO_ACAD"], ENT_QUOTES)));
	$txt_CORREO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["txt_CORREO"], ENT_QUOTES)));
	$txt_TELEF = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["txt_TELEF"], ENT_QUOTES)));
	$txt_RUC_ENT = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["txt_RUC_ENT"], ENT_QUOTES)));
	$txt_ENTIDAD = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["txt_ENTIDAD"], ENT_QUOTES)));
	$COD_DPTO_LAB = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["COD_DPTO_LAB"], ENT_QUOTES)));
	$COD_PROV_LAB = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["COD_PROV_LAB"], ENT_QUOTES)));
	$COD_DIST_LAB = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["COD_DIST_LAB"], ENT_QUOTES)));
	$NIVEL_GOBIERNO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["NIVEL_GOBIERNO"], ENT_QUOTES)));
	$txt_area = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["txt_area"], ENT_QUOTES)));
	$txt_areaopcional = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["txt_areaopcional"], ENT_QUOTES)));
	$txt_CARGO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["txt_CARGO"], ENT_QUOTES)));
	$cbro_rubro = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["cbro_rubro"], ENT_QUOTES))); //clasificacion
	$MODALIDAD = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["MODALIDAD"], ENT_QUOTES)));
	$DESC_MODAL = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["DESC_MODAL"], ENT_QUOTES)));
	$V_RUTA = mysqli_real_escape_string($mysqlConeccion, (strip_tags("/Archivos/PDF/", ENT_QUOTES)));
	$txt_nro_of_asignado = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["txt_nro_of_asignado"], ENT_QUOTES)));
	$TXT_VAL_OFICIO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["TXT_VAL_OFICIO"], ENT_QUOTES)));
	// $txf_adjuntar_oficio = mysqli_real_escape_string($mysqlConeccion,(strip_tags($_FILES["txf_adjuntar_oficio"],ENT_QUOTES)));
	$DESC_MODAL = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["DESC_MODAL"], ENT_QUOTES)));
	//	$stock = intval($_POST["stock"]);
	//	$price = floatval($_POST["price"]);
	$txf_adjuntar_oficio = (isset($_FILES['txf_adjuntar_oficio'])) ? $_FILES['txf_adjuntar_oficio'] : null;
	
	$xLimiteMatriculaGral = (isset($_POST['TXT_LIMITE_MATRICULADOS'])) ? $_POST['txf_adjuntar_oficio'] : '2';
	

	$codarchivo = "";
	$archrepe = 1;
	if ($txf_adjuntar_oficio) {
		$codarchivo = date('dmyhisv') . $txf_adjuntar_oficio['name'];
		$files = glob('../..' . $V_RUTA . '*'); //obtenemos todos los nombres de los ficheros
		foreach ($files as $file) {

			$insarch = "../.." . $V_RUTA . $codarchivo;
			//$data .=$file." ---- " .$insarch."---";
			if ($file == $insarch) {
				$archrepe = 0;
				//	$data .=$archrepe;
				break;
			}
			// unlink($file); //elimino el fichero
		}
		if ($archrepe != 0) {
			
			//$ruta_destino_archivo = $_SERVER['DOCUMENT_ROOT'] . "/Archivos/PDF/{$txf_adjuntar_oficio['name']}";
			//$archivo_ok = move_uploaded_file($txf_adjuntar_oficio['tmp_name'], $ruta_destino_archivo);

			require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config.php';
			$connection = ssh2_connect(SFTP_HOST, SFTP_PORT);
			if (ssh2_auth_password($connection, SFTP_USER, SFTP_PASS)) {
				$rutaUpdoad = $_SERVER['DOCUMENT_ROOT'] . '/Archivos/PDF/';
				ssh2_scp_send($connection, $txf_adjuntar_oficio['tmp_name'], $rutaUpdoad . $codarchivo, 0644);
			}
		}
	}
	$data = "";

	// REGISTER data into database
	if ($archrepe != 0) {
		$xLimiteMatricula = $xLimiteMatriculaGral; //Default es 2
		//$sql = "call sp_ins_matricula('$TXT_ID_CURSO', '$txt_NOMBRE', '$txt_APEPAT', '$txt_APEMAT','$cboTipoDocIdentidad', '$txt_DNI', '$txt_PROFESION', '$CBO_GENERO', '$CBO_GRADO_ACAD', '$txt_CORREO', '$txt_TELEF', '$txt_RUC_ENT', '$txt_ENTIDAD', '$COD_DPTO_LAB','$COD_PROV_LAB','$COD_DIST_LAB', '$NIVEL_GOBIERNO', '$txt_area','$txt_areaopcional', '$txt_CARGO', '$cbro_rubro', '$MODALIDAD', '$DESC_MODAL', '$txt_nro_of_asignado', '$codarchivo', '$V_RUTA',@message);";
		//$sql = "call sp_ins_matricula_v2('$TXT_ID_CURSO', '$txt_NOMBRE', '$txt_APEPAT', '$txt_APEMAT','$cboTipoDocIdentidad', '$txt_DNI', '$txt_PROFESION', '$CBO_GENERO', '$CBO_GRADO_ACAD', '$txt_CORREO', '$txt_TELEF', '$txt_RUC_ENT', '$txt_ENTIDAD', '$COD_DPTO_LAB','$COD_PROV_LAB','$COD_DIST_LAB', '$NIVEL_GOBIERNO', '$txt_area','$txt_areaopcional', '$txt_CARGO', '$cbro_rubro', '$MODALIDAD', '$DESC_MODAL', '$txt_nro_of_asignado', '$codarchivo', '$V_RUTA',@message);";		
		$sql = "call sp_ins_matricula_v3('$TXT_ID_CURSO', '$txt_NOMBRE', '$txt_APEPAT', '$txt_APEMAT','$cboTipoDocIdentidad', '$txt_DNI', '$txt_PROFESION', '$CBO_GENERO', '$CBO_GRADO_ACAD', '$txt_CORREO', '$txt_TELEF', '$txt_RUC_ENT', '$txt_ENTIDAD', '$COD_DPTO_LAB','$COD_PROV_LAB','$COD_DIST_LAB', '$NIVEL_GOBIERNO', '$txt_area','$txt_areaopcional', '$txt_CARGO', '$cbro_rubro', '$MODALIDAD', '$DESC_MODAL', '$txt_nro_of_asignado', '$codarchivo', '$V_RUTA', '$xLimiteMatricula', @message);";		
		$query = mysqli_query($mysqlConeccion, $sql);

		// if product has been added successfully

	}


	if ($archrepe != 0) {

		$rpta = mysqli_query($mysqlConeccion, "select @message;");

		// 	$msgArray = mysqli_fetch_assoc($rpta)
		while ($row = mysqli_fetch_array($rpta)) {
			$matriculas[] = $row;
		}
		foreach ($matriculas as $datosMatricula) {
			$data = $datosMatricula[0];
		}
	}
	// echo $rpta;
	// die();
	if ($archrepe == 0) {
		$messages[] = "Nombre de archivo repetido";
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