<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (empty($_POST['ID_USUARIO'])) {
	$errors[] = "Ingresa el nombre." . $_POST['ID_USUARIO'];
} elseif (!empty($_POST['ID_USUARIO'])) {

	require_once("../../servicios/conexion.php");

	$ID_MATRICULA = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["ID_MATRICULA"], ENT_QUOTES)));
	$ID_USUARIO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_POST["ID_USUARIO"], ENT_QUOTES)));

	$sql = "call sp_ins_matricula_proc('$ID_MATRICULA', '$ID_USUARIO')";
	$query = mysqli_query($mysqlConeccion, $sql);

	if ($query) {

		$send = true;

		if ($send) {
			require_once '../../app/libs/PHPMailer/autoload.php';
			require_once '../../app/db/Conexion.php';
			require_once '../../app/libs/helper.php';

			$db = new Conexion;
			$dataMAPR = $db->query('CALL `sp_sel_matriculaprocXid`(?)', array($_POST["ID_MATRICULA"]));

			$nombres = ucwords($dataMAPR[0]['Mat_nombres']);
			$user = $dataMAPR[0]['Mat_DNI'];
			$pass = $dataMAPR[0]['Mat_Password'];
			$email = $dataMAPR[0]['Mat_Correo'];
			$cursoNameLarge = $dataMAPR[0]['cur_Nombre'];
			$cursoNameSmall = $dataMAPR[0]['course_shortname'];

			$mail = new PHPMailer(true);

			try {
				$mail->SMTPDebug = 0;
				$mail->isSMTP();
				$mail->Host       = MAIL_HOST;
				$mail->SMTPAuth   = true;
				$mail->Username   = MAIL_USER;
				$mail->Password   = MAIL_PASS;
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
				$mail->Port       = MAIL_PORT;
				$mail->CharSet = 'UTF-8';
				$mail->setFrom(MAIL_USER, 'Aula Virtual SBN');
				$mail->addAddress($email);

				$mail->isHTML(true);
				$mail->Subject = utf8_decode('BIENVENIDO AL ' . $cursoNameLarge);
				
				/*
				$messageEmail = '';
				$messageEmail .= "<div style='font-size: 14px'>";
				$messageEmail .= '<div style="text-align: center"><img width="150" src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQATnmZ7KaFQ4-tgbm9o1lh5ePPOWvE5CjMM0UbmUIAg1qLlJ-v" alt="Logo"></div>';
				$messageEmail .= "<h1 style='text-align: center; margin-top: 0;'>¡Bienvenido(a)!</h1>";
				$messageEmail .= "Estimado(a) {$nombres},";
				$messageEmail .= "<p>Reciba un grato saludo. El motivo del presente es darle la más cordial bienvenida y confirmarle que usted ha sido inscrito(a) en {$cursoNameLarge} ({$cursoNameSmall}). En ese sentido, a partir de la recepción de la presente comunicación, podrá ingresar al Aula Virtual en la dirección <a href='https://aulavirtual.sbn.gob.pe/sdnc/login/iniciar-sesion.php'>https://aulavirtual.sbn.gob.pe/sdnc/login/iniciar-sesion.php</a> con su usuario y contraseña:</p>";
				$messageEmail .= "<p>";
				$messageEmail .= "<span><b>USUARIO : </b> {$user}<span><br>";
				$messageEmail .= "<span><b>CONTRASEÑA : </b> {$pass}<span>";
				$messageEmail .= "</p>";
				$messageEmail .= "En los próximos días, le enviaremos un mensaje con las indicaciones para el inicio y desarrollo del curso.";
				$messageEmail .= "</div>";
				*/

				$messageEmail  = "<html><body>";  
				$messageEmail .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";				   
				$messageEmail .= "<tr><td>";				
				$messageEmail .= "<br>";				
				$messageEmail .= "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='max-width:60%; background-color:#fff; font-family:Verdana, Geneva, sans-serif;'>";				
				$messageEmail .= "<thead>";
				$messageEmail .= "<tr>";
				$messageEmail .= "<th colspan='4'>";
				$messageEmail .= '<div style="text-align: center; padding: 30px;"><img width="450" src="https://www.sbn.gob.pe/Repositorio/Portal/logoweb_350x50.png" alt="Logo"></div>';
				$messageEmail .= "</th>";
				$messageEmail .= "</tr>";
				$messageEmail .= "</thead>";
				$messageEmail .= "<tbody>";				
				$messageEmail .= "<tr>";
				$messageEmail .= "<td colspan='4'>";
				$messageEmail .= '<div style="text-align: center; padding: 15px; background-color:#E53935; font-weight: bold; color:#fff; font-size:20px;">Plataforma Virtual de la SBN</div>';
				$messageEmail .= "</td>";
				$messageEmail .= "</tr>";				
				$messageEmail .= "<tr>";
				$messageEmail .= "<td colspan='4'>";
				$messageEmail .= "<div style='padding:20px; text-align: center; color: #1f5278;'><h1>¡Bienvenido(a)!</h1></div>";
				$messageEmail .= "</td>";
				$messageEmail .= "</tr>";				
				$messageEmail .= "<tr>";
				$messageEmail .= "<td colspan='4'>";
				$messageEmail .= "<div style='padding-left:25px; color: #1f5278; font-size:20px;'><p>Estimado(a) usuario: ".$nombres.",</p></div>";
				$messageEmail .= "<hr />";
				$messageEmail .= "</td>";
				$messageEmail .= "</tr>";				
				$messageEmail .= "<tr>";
				$messageEmail .= "<td colspan='4'>";
				$messageEmail .= "<div style='padding:25px; text-align: justify; font-size:19px; color: #1f5278;'><p>Reciba un grato saludo. El motivo del presente es darle la más cordial bienvenida y confirmarle que usted ha sido inscrito(a) en <strong>".$cursoNameLarge."</strong> <strong>(".$cursoNameSmall.")</strong>. En ese sentido, a partir de la recepción de la presente comunicación, podrá ingresar al Aula Virtual en la dirección <a href='https://aulavirtual.sbn.gob.pe/sdnc/login/iniciar-sesion.php'>https://aulavirtual.sbn.gob.pe/sdnc/login/iniciar-sesion.php</a> con su usuario y contraseña:</p></div>";
				$messageEmail .= "</td>";
				$messageEmail .= "</tr>";	
				$messageEmail .= "<tr>";
				$messageEmail .= "<td colspan='4'>";				
				$messageEmail .= "<table>";				
				$messageEmail .= "<tr>";
				$messageEmail .= '<td><div style="background-color:#184E74; margin: 0 30px 0 30px; padding:10px 30px 10px 30px; text-align:left;border-radius:50px;color:#ffffff;">USUARIO: '.$user.'</div></td>';
				$messageEmail .= "</tr>";				
				$messageEmail .= "<tr>";
				$messageEmail .= '<td><br></td>';
				$messageEmail .= "</tr>";				
				$messageEmail .= "<tr>";
				$messageEmail .= '<td><div style="background-color:#184E74; margin: 0 30px 0 30px; padding:10px 30px 10px 30px; text-align:left;border-radius:50px;color:#ffffff;">CONTRASE&Ntilde;A: '.$pass.'</div></td>';
				$messageEmail .= "</tr>";				
				$messageEmail .= "</table>";				
				$messageEmail .= "</td>";
				$messageEmail .= "</tr>";				
				$messageEmail .= "<tr>";
				$messageEmail .= "<td colspan='4'>";
				$messageEmail .= "<div style='padding:25px; text-align: justify; font-size:19px; color: #1f5278;'><p>En los próximos días, le enviaremos un mensaje con las indicaciones para el inicio y desarrollo del curso.</p></div>";
				$messageEmail .= "</td>";
				$messageEmail .= "</tr>";				
				$messageEmail .= "<tr>";
				$messageEmail .= "<td colspan='4'>";
				$messageEmail .= "<div style='padding: 0 0 0 25px; text-align: justify; font-size:19px; color: #1f5278;'><p>Saludos cordiales,</p></div>";
				$messageEmail .= "</td>";
				$messageEmail .= "</tr>";				
				$messageEmail .= "<tr>";
				$messageEmail .= "<td colspan='4'>";
				$messageEmail .= "<div style='padding: 0 0 0 25px; text-align: justify; font-size:19px; color: #1f5278;'><p>Subdirección de Normas y Capacitación<br>Calle Chinchon 890, San Isidro<br>Teléfono: 317-4400<br></p></div>";
				$messageEmail .= "</td>";
				$messageEmail .= "</tr>";				
				$messageEmail .= "<tr>";
				$messageEmail .= "<td colspan='4'>";
				$messageEmail .= '<div style="text-align: center; padding: 15px; background-color:#E53935; font-weight: bold; color:#fff; font-size:20px;">&nbsp;</div>';
				$messageEmail .= "</td>";
				$messageEmail .= "</tr>";				
				$messageEmail .= "</tbody>";    
				$messageEmail .= "</table>";
				$messageEmail .= "<br></td></tr>";
				$messageEmail .= "</table>";   
				$messageEmail .= "</body></html>";

				$mail->Body    = utf8_decode($messageEmail);
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
				$mail->send();
			} catch (Exception $e) {
				echo  "Hubo un error al enviar el correo.";
				exit;
			}
		}
		$messages[] =  'Guardado con éxito.';
	} else {
		$errors[] = "Lo sentimos, el registro falló. Por favor, regrese y vuelva a intentarlo." . $sql;
	}
} else {
	$errors[] = "desconocido.";
}

if (isset($errors)) {
	foreach ($errors as $error) {
		echo $error;
	}
}
if (isset($messages)) {

	foreach ($messages as $message) {
		echo $message;
	}
}
