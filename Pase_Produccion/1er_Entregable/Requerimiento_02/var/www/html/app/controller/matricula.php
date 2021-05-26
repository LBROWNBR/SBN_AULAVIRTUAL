<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once dirname(__FILE__, 3) . "/app/model/matricula_Model.php";
require_once dirname(__FILE__, 3) . "/app/libs/helper.php";


$_POST['op'] = isset($_POST['op']) ? $_POST['op'] : die();

switch ($_POST['op']) {

    case 'combo_Provincia':
        $cm = new matricula_Model;
        $dataPRO = $cm->get_Provincia($_POST['COD_DEP']);
        $combo = [];
        foreach ($dataPRO as $data) {
            $combo[] = [
                0 => $data[1],
                1 => $data[3],
            ];
        }
        echo json_encode($combo);
        break;

    case 'buscar_dni':
        $cm = new matricula_Model;
        $dni = $_POST['dni'];
        $dataMATR = $cm->get_Matricula($dni);
        if (!empty($dataMATR)) {
            $error = false;
            $datos = array(
                0 => $dataMATR[0]['Mat_DNI'],                   // DNI
                1 => $dataMATR[0]['Mat_Nombre'],                // Nombres
                2 => $dataMATR[0]['Mat_Paterno'],               // Apellido Paterno
                3 => $dataMATR[0]['Mat_Materno'],               // Apellido Materno
                4 => $dataMATR[0]['Mat_Profesion'],             // Profesion
                5 => $dataMATR[0]['Mat_Celular'],               // Celular
                6 => $dataMATR[0]['Mat_Correo'],                // Email
                7 => $dataMATR[0]['Mat_Genero'],                // Genero
                8 => $dataMATR[0]['Mat_Grado'],                 // Grado Academico
                9 => $dataMATR[0]['Mat_Entidad'],               // Nombre de la Entidad
                10 => $dataMATR[0]['Mat_RUC'],                  // Ruc de la Entidad
                11 => $dataMATR[0]['Mat_Departamento'],         // Departamento
                12 => $dataMATR[0]['Mat_Provincia'],            // Provincia
                13 => $dataMATR[0]['Mat_Distrito'],             // Distrito
                14 => $dataMATR[0]['Mat_NivelGobierno'],        // Nivel de Gobierno
                15 => $dataMATR[0]['Mat_Area'],                 // Area
                16 => $dataMATR[0]['Mat_Cargo'],                // Cargo
                17 => $dataMATR[0]['Mat_Clasificacion'],        // Clasificacion
                18 => $dataMATR[0]['Mat_ModoContrato'],         // Modalidad Contractural
                19 => $dataMATR[0]['Mat_Oficio'],               // N° Oficio
            );
        } else {
            $error = true;
            $datos = array();
            // $url = "https://www.facturacionelectronica.us/facturacion/controller/ws_consulta_rucdni_v2.php?documento=DNI&usuario=10415898890&password=webmaster2017&nro_documento=" . $dni;
            // $json = file_get_contents($url);
            // $datosReniec = json_decode($json, true);
            // $error = $datosReniec['success'] == 'False' ? true : false;
            // if ($error != true) {
            //     $datosReniec = $datosReniec['result'];
            //     $datos = array(
            //         0 => $dni,
            //         1 => $datosReniec['Nombre'],
            //         2 => $datosReniec['Paterno'],
            //         3 => $datosReniec['Materno'],
            //     );
            // }
            // error_reporting(E_ALL ^ E_NOTICE);
            // require_once '../libs/reniec/simple_html_dom.php';
            // $consulta = file_get_html($url);
            // $datosnombres = array();
            // foreach ($consulta->find('td') as $header) {
            //     $datosnombres[] = $header->plaintext;
            // }
            // $error = $datosnombres[2] == null ? true : false;
            // $datos = array(
            //     0 => $dni,
            //     1 => $datosnombres[0],
            //     2 => $datosnombres[1],
            //     3 => $datosnombres[2],
            // );
        }
        $response = [
            'error' => $error,
            'data' => $datos,
        ];

        echo json_encode($response);
        break;

    case 'pdf':
        session_start();
        $_SESSION['archivo_matricula']  = $_POST['archivo'];
        echo !empty($_SESSION['archivo_matricula']) ? true : false;
        break;

    case 'matricula_sin_procesar':
        $cm = new matricula_Model;
        $idCurso = !empty($_POST['FILTRO_curso']) ? $_POST['FILTRO_curso'] : 0;
        $estadoProc = $_POST['FILTRO_proc'];
        $dataMATR = $cm->get_Matricula_SP($idCurso, $estadoProc);
        $acu = 0;
        $response = [];
        foreach ($dataMATR as $data) {
            $acu++;
            $verPDF = '';
            if (!empty(trim($data['Mat_NombreArchivo'])) && file_exists('../..' . $data['Mat_RutaArchivo'] . $data['Mat_NombreArchivo'])) {
                $rutaNombre = '"' . $data['Mat_RutaArchivo'] . '"';
                $archNombre = '"' . $data['Mat_NombreArchivo'] . '"';
                $verPDF = "<button type='button' class='btn btn-link' onClick='fnVerPDF($rutaNombre, $archNombre)'>Ver PDF</button>";
            }
            $response[] = [
                0 => $acu,
                // 1 => "<input type='checkbox' value='" . $data['Mat_ID'] . "'  class='form-check-input' id='idcheckbox' name='idcheckbox'>",
				
                1 => $verPDF,
				2 => strtoupper($data['course_shortname']),
				3 => strtoupper($data['cur_Nombre']),
				4 => strtoupper($data['Mat_TipoDocIdent']),
                5 => strtoupper($data['Mat_DNI']),
                6 => strtoupper($data['Mat_Nombre']),
                7 => strtoupper($data['Mat_Paterno'].' '.$data['Mat_Materno']),
                8 => strtoupper($data['Mat_Correo']),
                9=> strtoupper($data['Mat_Celular']),
                10=> strtoupper($data['Mat_Oficio']),
                11 => strtoupper($data['Mat_Profesion']),
        		12 => strtoupper($data['Mat_Grado']),
				13 => strtoupper($data['Mat_RUC']),
				14 => strtoupper($data['Mat_Entidad']),
				15 => strtoupper($data['Mat_Departamento']),
				16 => strtoupper($data['Mat_Provincia']),
				17 => strtoupper($data['Mat_Distrito']),
				18 => strtoupper($data['Mat_NivelGobierno']),
				19 => strtoupper($data['Mat_Area']),
				20 => strtoupper($data['Mat_Cargo']),
				21 => strtoupper($data['Mat_Clasificacion']),
				22 => strtoupper($data['Mat_ModoContrato']),
				23 => strtoupper($data['Mat_ContracOtros']),
				24 => strtoupper($data['Mat_AreaOpcional']),
                                25 => strtoupper($data['fecha_create']),
                                26 => strtoupper($data['Mat_Fecha_hora']),
			];
        }
        $data['data'] = $response;
        echo json_encode($data);
        break;

    case 'change_password':
        $cm = new matricula_Model;
        $accion = isset($_POST['MATR_action']) ? $_POST['MATR_action'] : die();
        $campo = $accion == 'usuario' ? 'user_username' : 'Mat_Correo';
        $valor = $accion == 'usuario' ? $_POST['MATR_usuario'] : $_POST['MATR_email'];
        $campoAlias = $accion == 'usuario' ? 'usuario' : 'email';
        $dataMATR = $cm->get_MatriculaProc($campo, $valor);
        // echo json_encode($dataMATR);
        // exit;
        $response = [
            'error' => false,
            'data' => 'Si ha suministrado un nombre de usuario o dirección correctos, se le ha enviado un email con sus nuevos datos de ingreso.'
        ];
        if (!empty($dataMATR)) {
            require_once dirname(__FILE__, 3) . "/app/libs/PHPMailer/autoload.php";

            $user = $dataMATR[0]['user_username'];
            $email = $dataMATR[0]['Mat_Correo'];
            $pass = rand(1111, 9999);

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

                $mail->setFrom(MAIL_USER, utf8_decode('Aula Virtual SBN - Restablecer contraseña'));
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = utf8_decode('Aula Virtual SBN - Restablecer contraseña');

                /*
                $messageEmail = '';
                $messageEmail .= "<p>Su cuenta de acceso al aula virtual SBN ha sido restablecida satisfactoriamente.</p>";
                $messageEmail .= '<p>Nuevos datos de ingreso:</p>';
                $messageEmail .= '<ul>';
                $messageEmail .= "<li>Usuario : <b>{$user}</b></li>";
                $messageEmail .= "<li>" . utf8_decode("Contraseña") . " : <b>{$pass}</b></li>";
                $messageEmail .= '</ul>';
                $messageEmail .= utf8_decode('Usted puede iniciar desde : <a href="https://aulavirtual.sbn.gob.pe/sdnc/login/index.php">https://aulavirtual.sbn.gob.pe/sdnc/login/index.php</a>');
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
                $messageEmail .= "<div style='padding:20px; text-align: center; color: #1f5278;'><h1>RESTABLECER CONTRASE&Ntilde;A</h1></div>";
                $messageEmail .= "</td>";
                $messageEmail .= "</tr>";
                $messageEmail .= "<tr>";
                $messageEmail .= "<td colspan='4'>";
                $messageEmail .= "<div style='padding: 0px 25px 0px 25px; text-align: justify; font-size:19px; color: #1f5278;'><p>Su cuenta de acceso al aula virtual SBN ha sido restablecida satisfactoriamente.</p></div>";
                $messageEmail .= "</td>";
                $messageEmail .= "<tr>";
                $messageEmail .= "<td colspan='4'>";
                $messageEmail .= "<div style='padding: 0px 25px 20px 25px; text-align: justify; font-size:19px; color: #1f5278;'><p>Nuevos datos de ingreso:</p></div>";
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
                $messageEmail .= "<div style='padding:25px; text-align: justify; font-size:19px; color: #1f5278;'><p>Usted puede iniciar sesión desde: <a href='https://aulavirtual.sbn.gob.pe/sdnc/login/iniciar-sesion.php'>https://aulavirtual.sbn.gob.pe/sdnc/login/iniciar-sesion.php</a></p></div>";
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

                $mail->Body    = $messageEmail;
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if ($mail->send()) {
                    $cm->restablecer_Pass($email, $pass);
                    $response = [
                        'error' => false,
                        'data' => 'Si ha suministrado un nombre de usuario o dirección correctos, se le ha enviado un email con sus nuevos datos de ingreso.'
                    ];
                };
            } catch (Exception $e) {
                $response = [
                    'error' => true,
                    'data' => 'Hubo un error al enviar el correo. ERROR : ' . $e->getMessage()
                ];
            }
        } else {
            sleep(4);
            $IPCliente = getRealIP();
            $response = [
                'error' => false,
                'data' => "El {$campoAlias} <b>{$valor}</b> no se encuentra registrado.",
            ];
        }
        echo json_encode($response);
        break;
}
