<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once dirname(__FILE__, 3) . "/app/model/profesor_model.php";

$_POST['op'] = isset($_POST['op']) ? $_POST['op'] : die();

switch ($_POST['op']) {

    case 'AniosCursos':
        $cm = new profesor_model;
        $dataANIOCURS = $cm->get_AniosCurso();
        $combo = [];
        foreach ($dataANIOCURS as $data) {
            $combo[] = [
                0 => $data['anio'],
                1 => ucfirst(mb_strtolower($data['anio']))
            ];
        }
        array_multisort(array_column($combo, 1), SORT_ASC, $combo);
        echo json_encode($combo);        
        break;


    case 'VerCursosByAnio':
        $cm = new profesor_model;
        $anio = !empty($_POST['anio']) ? $_POST['anio'] : 0;
        $dataANIOCURS = $cm->get_CursosByAnio($anio);
        $combo = [];
        foreach ($dataANIOCURS as $data) {
            $combo[] = [
                0 => $data['cur_ID'],
                1 => ucfirst(mb_strtolower($data['cur_Nombre']))
            ];
        }
        array_multisort(array_column($combo, 1), SORT_ASC, $combo);
        echo json_encode($combo);        
        break;
        
    case 'cursos':
        $cm = new profesor_model;
        $dataCURS = $cm->get_Cursos();
        $combo = [];
        foreach ($dataCURS as $data) {
            $combo[] = [
                0 => $data['cur_ID'],
                1 => ucfirst(mb_strtolower($data['cur_Nombre'])),
            ];
        }
        array_multisort(array_column($combo, 1), SORT_ASC, $combo);
        echo json_encode($combo);
        break;

    case 'listado':
        $cm = new profesor_model;
        $idCurso = !empty($_POST['FILTRO_curso']) ? $_POST['FILTRO_curso'] : 0;
        $dataPROF = $cm->get_Profesores($idCurso);
        $acu = 0;
        $response = [];
        foreach ($dataPROF as $data) {
            $acu++;

            $datos = "";
            $datos .= $data['dni'] . "||";
            $datos .= $data['nombre'] . "||";
            $datos .= $data['apellido_paterno'] . "||";
            $datos .= $data['apellido_materno'] . "||";
            $datos .= $data['email'] . "||";
            $datos .= $data['celular'] . "||";
            $datos .= $data['usuario'] . "||";
            $datos .= $data['curso_id'] . "||";
            $datos .= $data['tipo_documento'];
            $datos = '"' . $datos . '"';

            $editar = "<a onclick='fnEditarProfesor({$data['id']}, $datos)' title='Eliminar'><span class='fas fa-pencil-alt' aria-hidden='true'></span> ";
            $elimin = "<a onclick='fnEliminarProfesor({$data['id']})' title='Eliminar'><span class='fas fa-trash-alt' aria-hidden='true'></span></a>";

            $response[] = [
                0 => $acu,
                1 => $editar . $elimin,
                2 => $data['tipo_documento'] . ' / ' . $data['dni'],
                3 => $data['nombre'],
                4 => $data['apellido_paterno'],
                5 => $data['email'],
                6 => $data['usuario'],
                7 => ucwords(strtolower($data['cur_Nombre'])),
            ];
        }
        $data['data'] = $response;
        echo json_encode($data);
        break;

    case 'actualizar':
        $cm = new profesor_model;
        $values = [
            0 => $_POST['PROF_dni'],
            1 => ucwords($_POST['PROF_nombres']),
            2 => ucwords($_POST['PROF_apePaterno']),
            3 => ucwords($_POST['PROF_apeMaterno']),
            4 => strtolower($_POST['PROF_email']),
            5 => $_POST['PROF_celular'],
            6 => $_POST['PROF_id'],
        ];
        $ress = $cm->update($values);
        $message = explode(':', $ress['@message']);
        $response = [
            'error' => $message[0] != 'FAILED' ? false : true,
            'data' => $message[1],
        ];
        echo json_encode($response);
        break;

    case 'registrar':
        $cm = new profesor_model;
        $values = [
            0 => $_POST['PROF_tipoDoc'],
            1 => $_POST['PROF_dni'],
            2 => ucwords($_POST['PROF_nombres']),
            3 => ucwords($_POST['PROF_apePaterno']),
            4 => ucwords($_POST['PROF_apeMaterno']),
            5 => strtolower($_POST['PROF_email']),
            6 => $_POST['PROF_celular'],
            7 => $_POST['PROF_usuario'],
            8 => $_POST['PROF_curso'],
        ];
        $response = $cm->create($values);

        $message = explode(':', $response['@message']);

        if ($message[0] != 'FAILED') {

            require_once '../libs/PHPMailer/autoload.php';
            require_once '../libs/helper.php';

            $dataMAPR = $cm->get_MatriculaProc($response['@idMatrProc']);

            $nombres = utf8_decode("Sr.(a) " . ucwords($dataMAPR[0]['Mat_nombres']));
            $user = $_POST['PROF_usuario'];
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

                $mail->setFrom(MAIL_USER, 'Aula Virtual SBN');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = utf8_decode('Aula Virtual SBN - Confirmación de inscripción');
                
                /*
                $messageEmail = '';
                $messageEmail .= "<p>Bienvenido {$nombres}, usted ha sido inscrito como docente en el curso de ".utf8_decode("{$cursoNameLarge} ({$cursoNameSmall})")." de nuestra aula virtual de la SBN.</p>";
                $messageEmail .= '<p>Su cuenta de ingreso es:</p>';
                $messageEmail .= '<ul>';
                $messageEmail .= "<li>Nombre de usuario : <b>{$user}</b></li>";
                $messageEmail .= "<li>" . utf8_decode("Contraseña") . " : <b>{$pass}</b></li>";
                $messageEmail .= '</ul>';
                $messageEmail .= utf8_decode('Ya puedes iniciar sesión en <a href="https://aulavirtual.sbn.gob.pe/sdnc/login/index.php">https://aulavirtual.sbn.gob.pe/sdnc/login/index.php</a>');
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
				$messageEmail .= "<div style='padding:20px; text-align: center; color: #1f5278;'><h1>¡Bienvenido(a) Sr(a) Docente!</h1></div>";
				$messageEmail .= "</td>";
				$messageEmail .= "</tr>";
				$messageEmail .= "<tr>";
				$messageEmail .= "<td colspan='4'>";
				$messageEmail .= "<div style='padding: 0px 25px 0px 25px; text-align: justify; font-size:19px; color: #1f5278;'><p>Estimado(a) ".$nombres.", usted ha sido inscrito como docente en el curso de <strong>".$cursoNameLarge."</strong> <strong>(".$cursoNameSmall.")</strong> de nuestra aula virtual de la SBN.</p></div>";
				$messageEmail .= "</td>";
				$messageEmail .= "<tr>";
				$messageEmail .= "<td colspan='4'>";
				$messageEmail .= "<div style='padding: 0px 25px 20px 25px; text-align: justify; font-size:19px; color: #1f5278;'><p>Su cuenta de ingreso es:</p></div>";
				$messageEmail .= "</td>";
				$messageEmail .= "<tr>";
				$messageEmail .= "<td colspan='4'>";
				$messageEmail .= "<table>";
				$messageEmail .= "<tr>";
				$messageEmail .= '<td><div style="background-color:#184E74; margin: 0 30px 0 80px; padding:10px 50px 10px 50px; text-align:left;border-radius:50px;color:#ffffff;">USUARIO: '.$user.'</div></td>';
				$messageEmail .= "</tr>";
				$messageEmail .= "<tr>";
				$messageEmail .= '<td><br></td>';
				$messageEmail .= "</tr>";
				$messageEmail .= "<tr>";
				$messageEmail .= '<td><div style="background-color:#184E74; margin: 0 30px 0 80px; padding:10px 50px 10px 50px; text-align:left;border-radius:50px;color:#ffffff;">CONTRASE&Ntilde;A: '.$pass.'</div></td>';
				$messageEmail .= "</tr>";
				$messageEmail .= "</table>";
				$messageEmail .= "</td>";
				$messageEmail .= "</tr>";
				$messageEmail .= "<tr>";
				$messageEmail .= "<td colspan='4'>";
				$messageEmail .= "<div style='padding:25px; text-align: justify; font-size:19px; color: #1f5278;'><p>Ya puedes iniciar sesión en: <a href='https://aulavirtual.sbn.gob.pe/sdnc/login/iniciar-sesion.php'>https://aulavirtual.sbn.gob.pe/sdnc/login/iniciar-sesion.php</a></p></div>";
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
                $message =  "Hubo un error al enviar el correo. ERROR : " . $e->getMessage();
                echo json_encode(['error' => true, 'data' => $message]);
                exit;
            }
        }
        $response = [
            'error' => $message[0] != 'FAILED' ? false : true,
            'data' => $message[1],
        ];
        echo json_encode($response);
        break;

    case 'eliminar':
        $cm = new profesor_model;
        echo $cm->eliminar_Profesor($_POST['PROF_id']) ? true : false;
        break;

    case 'buscar_dni':
        $cm = new profesor_model;
        $tipoDoc = $_POST['tipo_doc'];
        $numeDoc = $_POST['dni'];
        $dataPROF = $cm->get_Profesor($tipoDoc, $numeDoc);
        $response = [];
        if (!empty($dataPROF)) {
            $error = false;
            $datos = array(
                0 => $dataPROF[0]['dni'],
                1 => $dataPROF[0]['nombre'],
                2 => $dataPROF[0]['apellido_paterno'],
                3 => $dataPROF[0]['apellido_materno'],
                4 => $dataPROF[0]['email'],
                5 => $dataPROF[0]['celular'],
                6 => $dataPROF[0]['usuario'],
            );
        } else {
            $error = true;
            $datos = array();
            // $url = "https://www.facturacionelectronica.us/facturacion/controller/ws_consulta_rucdni_v2.php?documento=DNI&usuario=10415898890&password=webmaster2017&nro_documento=" . $dni;
            // $url = 'http://bct.test-api.sbn.gob.pe/pide/ValidarDni/' . $dni;
            // $json = file_get_contents($url);
            // $datosReniec = json_decode($json, true);
            // $error = $datosReniec['success'] == 'False' ? true : false;
            // $datos = $datosReniec;
            // if ($error != true) {
            //     $datosReniec = $datosReniec['result'];
            //     $datos = array(
            //         0 => $dni,
            //         1 => ucwords($datosReniec['Nombre']),
            //         2 => ucwords($datosReniec['Paterno']),
            //         3 => ucwords($datosReniec['Materno']),
            //     );
            // }
            // error_reporting(E_ALL ^ E_NOTICE);
            // require_once '../libs/reniec/simple_html_dom.php';
            // $consulta = file_get_html('https://eldni.com/buscar-por-dni?dni=' . $dni);
            // $datosnombres = array();
            // foreach ($consulta->find('td') as $header) {
            //    $datosnombres[] = $header->plaintext;
            // }
            // $error = $datosnombres[2] == null ? true : false;
            // $datos = array(
            //   0 => $dni,
            //   1 => ucwords(strtolower($datosnombres[0])),
            //   2 => ucwords(strtolower($datosnombres[1])),
            //    3 => ucwords(strtolower($datosnombres[2])),
            // );
        }
        $response = [
            'error' => $error,
            'data' => $datos,
        ];
        echo json_encode($response);
        break;
}
