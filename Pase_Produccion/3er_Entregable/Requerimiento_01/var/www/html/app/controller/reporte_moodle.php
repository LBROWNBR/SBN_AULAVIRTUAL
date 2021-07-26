<?php

require_once dirname(__FILE__, 3) . "/app/model/reporte_moodle_Model.php";
require_once dirname(__FILE__, 3) . "/app/model/matricula_Model.php";
require_once dirname(__FILE__, 3) . "/app/libs/helper.php";

$_POST['op'] = isset($_POST['op']) ? $_POST['op'] : die();

switch ($_POST['op']) {

    case 'combo_anios':
        $rm = new reporte_moodle_Model;
        $dataANIOS = $rm->getAniosCurso();
        $combo = [];
        foreach ($dataANIOS as $data) {
            $combo[] = [
                0 => $data['course_anio'],
                1 => $data['course_anio']
            ];
        }
        array_multisort(array_column($combo, 1), SORT_DESC, $combo);
        echo json_encode($combo);
        break;

    case 'combo_cursos_by_anio':
            $anio = $_POST['cboanio'];
            $rm = new reporte_moodle_Model;
            $dataCURS = $rm->getCourseByAnio($anio);
            $combo = [];
            foreach ($dataCURS as $data) {
                $combo[] = [
                    0 => $data['id'],
                    1 => ucfirst(strtoupper($data['fullname'])).' ('.strtoupper($data['shortname']).')',
                ];
            }
            array_multisort(array_column($combo, 1), SORT_ASC, $combo);
            echo json_encode($combo);
            break;
        
    case 'combo_cursos':
        $rm = new reporte_moodle_Model;
        $dataCURS = $rm->getCourse();
        $combo = [];
        foreach ($dataCURS as $data) {
            $combo[] = [
                0 => $data['id'],
                1 => ucfirst(strtoupper($data['fullname'])),
            ];
        }
        array_multisort(array_column($combo, 1), SORT_ASC, $combo);
        echo json_encode($combo);
        break;

    case 'reporte_usuarios':
        session_start();
        $rm = new reporte_moodle_Model;
        $cpremat = new matricula_Model;
        $response = [];

        //$dataUM = $rm->getUsuariosInscritos();
        $dataUM = $rm->getCursosWithUsuariosInscritos($_POST['id_curso']);      
        $dataActividad = $rm->getActividades_By_CourseID($_POST['id_curso']);   
        $dataCurso = $rm->getCourse_By_ID($_POST['id_curso']);   

        
        
        $acu = 0;
        foreach ($dataUM as $user) {
            $id = $user['user_id'];
            $dataUSER = $rm->getUsuario($id);
            $dataFU = $rm->getUsuarioDataField($id);
            $dataPREMATRICULAALUMNO = $cpremat->Listar_DataCertificados_By_Idcurso_IdUserMoodle($_POST['id_curso'], $id); 
            $apellidoMater = '';
            $nivelGobierno = '';
            $rubro = '';
            $provinLaboral = '';
            $distriLaboral = '';
            $gener = '';
            $profe = '';
            $ruc = '';
            $cargo = '';
            $modalidad = '';
            $descModalidad = '';
            $numOficioDesc = '';
            $adjuntoArchiv = 'NO';
            $gradoAcademic = '';
            $discapacidad = '';
            $descDiscapac = '';
            if (count($dataFU) > 0) {
                getDatosFU($dataFU);
            }
            
            $acu++;

            $xNombreCurso_Moodle = $dataCurso[0]['fullname'].' ('.$dataCurso[0]['shortname'].')';

            $x_Mat_Entidad = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_entidad'] : '';
            $x_Mat_Cargo = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_cargo'] : '';
            $x_Mat_Area = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_area_lab'] : '';
            $x_Mat_Profesion = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_profesion'] : '';
            $x_Mat_NivelGobierno = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_nivelgrado'] : '';
            $x_Mat_Rubro = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_rubro'] : '';
            $x_Mat_ModoContrato = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_tipocontrato'] : '';            

            $x_mdl_cur_fullname = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_cur_fullname'] : '';
            $x_mdl_cur_shortname = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_cur_shortname'] : '';
            $x_mat_fechaini = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_fechaini'] : '';
            $x_mat_fechafin = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_fechafin'] : '';
            $x_mat_modalidad = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_modalidad'] : '';
            $x_mat_horalectiva = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_horalectiva'] : '';
            $x_ConCertificado = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['ConCertificado'] : '';
            $x_NroCertificado = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['NroCertificado'] : '';
            $ConCertificado = ($x_ConCertificado== '1') ? 'SI' : 'NO';

            $NotasAlumno = $rm->getNotasCourse_By_CourseID_UserId($id, $_POST['id_curso']);

            $response[] = [
                0 => $acu,                                              // N°
                1 => html_utf8($dataUSER[0]['lastname']),               // Apellido Materno
                2 => html_utf8($apellidoMater),                         // Apellido Paterno
                3 => html_utf8($dataUSER[0]['firstname']),              // Nombres
                4 => html_utf8($dataUSER[0]['username']),               // Username
                5 => html_utf8($dataUSER[0]['username']),               // DNI
                6 => html_utf8($x_Mat_Entidad), //html_utf8($dataUSER[0]['institution']),            // Entidad
                7 => html_utf8($x_Mat_Cargo),                                 // Cargo
                8 => html_utf8($x_Mat_Area),             // Area
                9 => html_utf8($x_Mat_Profesion),                                 // Profesion
                10 => html_utf8($x_Mat_NivelGobierno),                        // Nivel gobierno
                11 => html_utf8($x_Mat_Rubro),                                // Rubro
                12 => html_utf8($x_Mat_ModoContrato),                            // Modalidad
                13 => html_utf8($descModalidad),                        // Descripcion Modalidad
                14 => html_utf8($dataUSER[0]['email']),                 // Correo Electronico
                15 => html_utf8($dataUSER[0]['phone2']),                // Telefono
                16 => html_utf8($dataUSER[0]['department']),            // Departamento laboral
                17 => html_utf8($provinLaboral),                        // Provincia laboral
                18 => html_utf8($distriLaboral),                        // Distrito laboral
                19 => html_utf8($numOficioDesc),                        // Nro de oficio de inscripción
                20 => html_utf8($gener),                                // Genero
                21 => html_utf8($gradoAcademic),                        // Grado academico
                22 => html_utf8($discapacidad),                         // Discapacidad
                23 => html_utf8($descDiscapac),                         // Descripcion discapacidad

                24 => html_utf8($xNombreCurso_Moodle),      // Nombre del curso
                25 => html_utf8($x_mat_fechaini),          // mat_fechaini
                26 => html_utf8($x_mat_fechafin),          // mat_fechafin
                27 => html_utf8($x_mat_modalidad),         // mat_modalidad
                28 => html_utf8($x_mat_horalectiva),       // mat_horalectiva                
                29 => html_utf8($ConCertificado),          // ConCertificado
                30 => html_utf8($x_NroCertificado),        // Nro Certificado
                31 => $NotasAlumno
            ];
        }
        // echo json_encode($response);
        if (count($response) == 0) {
            echo false;
            exit;
        }
        $_SESSION['reporte_alumnos'] = $response;
        $_SESSION['reporte_actividades'] = $dataActividad;
        echo true;
        break;

    case 'reporte_entidades_inscritas':
        session_start();
        $rm = new reporte_moodle_Model;
        $cpremat = new matricula_Model;

        $idCurso = $_POST['id_curso'];
        $dataCURS = $rm->getCursosWithUsuariosInscritos($idCurso);
        $dataPREMATRICULAALUMNOCERT = $cpremat->Listar_DataCertificados_By_Idcurso($idCurso); 

        $listaAlumCursos = [];
        foreach ($dataCURS as $curso) {
            $dataFU = $rm->getUsuarioDataField($curso['user_id']);            
            $nivelGobierno = '';
            // foreach ($dataFU as $infoFU) {
            //     if ($infoFU['shortname'] == 'Mat_NivelGobierno') {
            //         $nivelGobierno = $infoFU['data'];
            //     }
            // }
            if (count($dataFU) > 0) {
                getDatosFU($dataFU);
            }
            $listaAlumCursos[] = [
                'course_id' => $curso['course_id'],
                'course_name' => html_utf8($curso['course_name'].' ('.$curso['short_name'].')'),
                'course_ini' => html_utf8($curso['course_ini']),
                'course_fin' => html_utf8($curso['course_fin']),
                'user_entidad' => html_utf8($curso['user_entidad']),
                'user_nivelGob' => html_utf8($nivelGobierno)                
            ];
        }
        $instituciones = array_values(array_unique(array_column($listaAlumCursos, 'user_entidad')));
        $DataExcel = [];
        $response = [];
        foreach ($instituciones as $institution) {
            $numLoc = 0;
            $numReg = 0;
            $numNac = 0;
            $numOtr = 0;
            $header = [];
            foreach ($listaAlumCursos as $data) {
                $data = (array) $data;
                if (in_array($institution, $data)) {
                    $nivelGob = strtoupper($data['user_nivelGob']);
                    switch ($nivelGob) {
                        case 'LOCAL':
                            $numLoc++;
                            break;
                        case 'REGIONAL':
                            $numReg++;
                            break;
                        case 'NACIONAL':
                            $numNac++;
                            break;
                        default:
                            $numOtr++;
                            break;
                    }
                    unset($data['user_nivelGob']);
                    $header = $data;
                }
            }
            $serv = [];
            if ($numLoc > 0)
                $serv['local'] = $numLoc;
            if ($numReg > 0)
                $serv['regional'] = $numReg;
            if ($numNac > 0)
                $serv['nacional'] = $numNac;
            if ($numOtr > 0)
                $serv['otros'] = $numOtr;
            $header['servidores'] = $serv;
            $response[] =  $header;
        }

        
        $DataExcel['dataEntidades'] = $response;
        $DataExcel['dataCertificacion'] = $dataPREMATRICULAALUMNOCERT;
        

        if (empty($response)) {
            echo false;
            exit;
        }
        $_SESSION['reporte_ent_inscritas'] = $DataExcel;
        echo true;
        break;

    case 'reporte_alumnos_inscritos':
        session_start();
        $rm = new reporte_moodle_Model;
        $cpremat = new matricula_Model;

        $idCurso = $_POST['id_curso'];
        $dataCURS = $rm->getCursosWithUsuariosInscritos($idCurso);
        $dataActividad = $rm->getActividades_By_CourseID($idCurso);   
        $dataCurso = $rm->getCourse_By_ID($idCurso);   

        $acu = 0;
        $listaAlumCursos = [];
        foreach ($dataCURS as $curso) {
            $acu++;
            $dataUSER = $rm->getUsuario($curso['user_id']);
            $dataCert = $rm->getCodigoGenerateCertificate($curso['user_id'], $idCurso);
            $dataFU = $rm->getUsuarioDataField($curso['user_id']);
            $dataPREMATRICULAALUMNO = $cpremat->Listar_DataCertificados_By_Idcurso_IdUserMoodle($_POST['id_curso'], $curso['user_id']); 
            $apellidoMater = '';
            $nivelGobierno = '';
            $rubro = '';
            $provinLaboral = '';
            $distriLaboral = '';
            $gener = '';
            $profe = '';
            $ruc = '';
            $cargo = '';
            $modalidad = '';
            $descModalidad = '';
            $numOficioDesc = '';
            $adjuntoArchiv = 'NO';
            $gradoAcademic = '';
            $discapacidad = '';
            $descDiscapac = '';
            if (count($dataFU) > 0) {
                getDatosFU($dataFU);
            }


            $xNombreCurso_Moodle = $dataCurso[0]['fullname'].' ('.$dataCurso[0]['shortname'].')';

            $x_Mat_Entidad = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_entidad'] : '';
            $x_mdl_depa_lab = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_depa_lab'] : '';
            $x_mdl_prov_lab = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_prov_lab'] : '';
            $x_mdl_dist_lab = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_dist_lab'] : '';
            $x_Mat_Cargo = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_cargo'] : '';
            $x_Mat_Area = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_area_lab'] : '';
            $x_Mat_Profesion = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_profesion'] : '';
            $x_Mat_NivelGobierno = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_nivelgrado'] : '';
            $x_Mat_Rubro = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_rubro'] : '';
            $x_Mat_ModoContrato = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_tipocontrato'] : '';            

            $x_mdl_cur_fullname = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_cur_fullname'] : '';
            $x_mdl_cur_shortname = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_cur_shortname'] : '';
            $x_mat_fechaini = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_fechaini'] : '';
            $x_mat_fechafin = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_fechafin'] : '';
            $x_mat_modalidad = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_modalidad'] : '';
            $x_mat_horalectiva = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_horalectiva'] : '';
            $x_mat_codpoi = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_codpoi'] : '';
            $x_ConCertificado = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['ConCertificado'] : '';
            $x_NroCertificado = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['NroCertificado'] : '';
            $ConCertificado = ($x_ConCertificado== '1') ? 'SI' : 'NO';

            $NotasAlumno = $rm->getNotasCourse_By_CourseID_UserId($curso['user_id'], $idCurso);

            $listaAlumCursos[] = [
                0 => $acu,                                              // N°
                1 => html_utf8($dataUSER[0]['username']),               // Codigo
                2 => html_utf8($dataUSER[0]['lastname']),               // Apellido Paterno
                3 => html_utf8($apellidoMater),                         // Apellido Materno'
                4 => html_utf8($dataUSER[0]['firstname']),              // Nombres
                5 => html_utf8($dataUSER[0]['username']),               // DNI
                6 => html_utf8($x_Mat_Entidad), //html_utf8($dataUSER[0]['institution']),            // Entidad
                7 => date("d/m/Y", $dataUSER[0]['timecreated']),        // Fecha registro
                8 => html_utf8($x_Mat_NivelGobierno),                         // Nivel gobierno
                9 => html_utf8($x_Mat_Rubro),                                 // Rubro
                10 => html_utf8($x_mdl_depa_lab),            // Departamento_Laboral
                11 => html_utf8($x_mdl_prov_lab),                        // Provincia_Laboral
                12 => html_utf8($x_mdl_dist_lab),                        // Distrito_Laboral
                13 => html_utf8($gener),                                // Género
                14 => html_utf8($profe),                                // Profesión
                15 => html_utf8($ruc),                                  // RUC
                16 => html_utf8($dataUSER[0]['department']),            // Área
                17 => html_utf8($x_Mat_Cargo),                                // Cargo
                18 => html_utf8($modalidad),                            // Modalidad
                19 => html_utf8($x_mat_modalidad),                        // Descrip. Modalidad
                20 => html_utf8($dataUSER[0]['email']),                 // Correo Electrónico
                21 => html_utf8($dataUSER[0]['phone2']),                // Teléfono Fijo
                22 => html_utf8($numOficioDesc),                        // Nro de oficio de inscripción
                23 => html_utf8($adjuntoArchiv),                        // Adjuntó Archivo?
                //24 => !empty($dataCert) ? $dataCert[0]['code'] : '-',    // Codigo de certificado autogenerado
                24 => !empty($dataCurso) ? $dataCurso[0]['shortname'] : '-',    // NOMBRE CORTO DEL CURSO

                25 => html_utf8($xNombreCurso_Moodle),   // Nombre del curso
                26 => html_utf8($x_mat_fechaini),        // mat_fechaini
                27 => html_utf8($x_mat_fechafin),        // mat_fechafin
                28 => html_utf8($x_mat_modalidad),       // mat_modalidad
                29 => html_utf8($x_mat_horalectiva),     // mat_horalectiva
                30 => html_utf8($x_mat_codpoi),          // mat_codpoi
                31 => html_utf8($ConCertificado),        // ConCertificado
                32 => html_utf8($x_NroCertificado),      // NroCertificado
                33 => $NotasAlumno  // NOTAS
            ];
        }

        $response = [
            'curso' => count($dataCURS) > 0 ? $dataCURS[0]['course_name'].' ('.$dataCURS[0]['short_name'].')' : null,
            'datos' => $listaAlumCursos
        ];
        if (empty($dataCURS)) {
            echo false;
            exit;
        }
        $_SESSION['reporte_alu_inscritos'] = $response;
        $_SESSION['reporte_actividades'] = $dataActividad;
        echo true;
        break;

    case 'reporte_curso_notas':
        session_start();
        $rm = new reporte_moodle_Model;
        $cpremat = new matricula_Model;
        $idCurso = $_POST['id_curso'];
        $dataUCN = $rm->getUsuariosWithNota($idCurso);
        $dataPC = $rm->getForosPruebasByCursos($idCurso); //$rm->getPruebasByCursos($idCurso)
        $dataCurso = $rm->getCourse_By_ID($idCurso);   
        $listaAlumNotas = [];
        $acu = 0;
        foreach ($dataUCN as $alumno) {
            $acu++;
            $idUser = $alumno['user_id'];
            $dataUSER = $rm->getUsuario($idUser);
            $dataFU = $rm->getUsuarioDataField($idUser);
            $apellidoMater = '';
            foreach ($dataFU as $infoFU) {
                if ($infoFU['shortname'] == 'Mat_Materno') {
                    $apellidoMater = $infoFU['data'];
                }
            }
            $nota = '';
            $estd = '';
            $notaCurso = [];
            foreach ($dataPC as $prueba) {
                $idPrueba = $prueba['prueba_id'];
                $dataNPR = $rm->getNotasByPruebas($idUser, $idPrueba);
                if (isset($dataNPR[0]['prueba_nota'])) {
                    $nota = $dataNPR[0]['prueba_nota'];
                    $estd = $nota >= 11 ? 'APROBADO' : 'DESAPROBADO';
                } else {
                    $nota = '-';
                    $estd = 'SIN NOTA';
                }
                $notaCurso[] = [
                    'fech' => $prueba['prueba_fecha'],
                    'nota' => $nota == '-' ? $nota : number_format(round($nota, 2), 2),
                    'estd' => $estd,
                ];
            }

            $xNombreCurso_Moodle = $dataCurso[0]['fullname'].' ('.$dataCurso[0]['shortname'].')';

            $NotaPromFinal = $rm->getNotaPromFinal($idUser, $idCurso);

            $dataPREMATRICULAALUMNO = $cpremat->Listar_DataCertificados_By_Idcurso_IdUserMoodle($idCurso, $idUser); 

            $x_mdl_cur_fullname = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_cur_fullname'] : '';
            $x_mdl_cur_shortname = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mdl_cur_shortname'] : '';
            $x_mat_fechaini = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_fechaini'] : '';
            $x_mat_fechafin = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_fechafin'] : '';
            $x_mat_modalidad = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_modalidad'] : '';
            $x_mat_horalectiva = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_horalectiva'] : '';
            $x_mat_codpoi = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['mat_codpoi'] : '';
            $x_ConCertificado = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['ConCertificado'] : '';
            $x_NroCertificado = ($dataPREMATRICULAALUMNO) ? $dataPREMATRICULAALUMNO[0]['NroCertificado'] : '';
			$ConCertificado = ($x_ConCertificado== '1') ? 'SI' : 'NO';

            $listaAlumNotas[] = [
                0 => $acu,                                              // N°
                1 => html_utf8($dataUSER[0]['lastname']),               // Apellido Paterno
                2 => html_utf8($apellidoMater),                         // Apellido Materno
                3 => html_utf8($dataUSER[0]['firstname']),              // Nombres
                4 => html_utf8($dataUSER[0]['username']),               // DNI
                5 => html_utf8($dataUSER[0]['institution']),            // Entidad
                6 => $notaCurso,
                7 => $NotaPromFinal,
                8 => $xNombreCurso_Moodle,
                9 => $x_mat_fechaini,
                10 => $x_mat_fechafin,
                11 => $x_mat_modalidad,
                12 => $x_mat_horalectiva,
                13 => $x_mat_codpoi,
                14 => $ConCertificado,
                15 => $x_NroCertificado
            ];
        }
        // echo json_encode($listaAlumNotas);
        // die();
        $pruebas = array_values(array_unique(array_column($dataPC, 'prueba_nombre')));
        $response = [
            'curso' => 'Curso de prueba',
            'prueb' => $pruebas,
            'datos' => $listaAlumNotas,
        ];
        if (count($listaAlumNotas) == 0) {
            echo false;
            exit;
        }
        // echo json_encode($listaAlumNotas);
        $_SESSION['reporte_alumn_notas'] = $response;
        echo true;
        break;

    case 'reporte_alumnos_cursos':
        session_start();
        $rm = new reporte_moodle_Model;
        $dataCI = $rm->getCursosWithInscritos($_POST['REP_ALCU_filtro'], $_POST['REP_ALCU_desde'], $_POST['REP_ALCU_hasta']);
        $response = [];
        foreach ($dataCI as $course) {
            $dataCURS = $rm->getCursosWithUsuariosInscritos($course['course_id']);
            $acu = 0;
            $listaAlumCursos = [];
            foreach ($dataCURS as $curso) {
                $acu++;
                $dataUSER = $rm->getUsuario($curso['user_id']);
                $dataFU = $rm->getUsuarioDataField($curso['user_id']);
                $apellidoMater = '';
                $nivelGobierno = '';
                $rubro = '';
                $provinLaboral = '';
                $distriLaboral = '';
                $gener = '';
                $profe = '';
                $ruc = '';
                $cargo = '';
                $modalidad = '';
                $descModalidad = '';
                $numOficioDesc = '';
                $adjuntoArchiv = 'NO';
                $gradoAcademic = '';
                $discapacidad = '';
                $descDiscapac = '';
                if (count($dataFU) > 0) {
                    getDatosFU($dataFU);
                }
                $listaAlumCursos[] = [
                    0 => html_utf8($course['course_cod']),
                    1 => html_utf8($course['course_name']),
                    2 => html_utf8($course['course_ini']),
                    3 => html_utf8($course['course_fin']),
                    4 => html_utf8($acu),                                  // N°
                    5 => html_utf8($dataUSER[0]['username']),              // Codigo
                    6 => html_utf8($dataUSER[0]['lastname']),              // Apellido Paterno
                    7 => html_utf8($apellidoMater),                        // Apellido Materno'
                    8 => html_utf8($dataUSER[0]['firstname']),             // Nombres
                    9 => html_utf8($dataUSER[0]['username']),              // DNI
                    10 => html_utf8($dataUSER[0]['institution']),          // Entidad
                    11 => date("d/m/Y", $dataUSER[0]['timecreated']),      // Fecha registro
                    12 => html_utf8($nivelGobierno),                       // Nivel gobierno
                    13 => html_utf8($rubro),                               // Rubro
                    14 => html_utf8($dataUSER[0]['department']),           // Departamento_Laboral
                    15 => html_utf8($provinLaboral),                       // Provincia_Laboral
                    16 => html_utf8($distriLaboral),                       // Distrito_Laboral
                    17 => html_utf8($gener),                               // Género
                    18 => html_utf8($profe),                               // Profesión
                    19 => html_utf8($ruc),                                 // RUC
                    20 => html_utf8($dataUSER[0]['department']),           // Área
                    21 => html_utf8($cargo),                               // Cargo
                    22 => html_utf8($modalidad),                           // Modalidad
                    23 => html_utf8($descModalidad),                       // Descrip. Modalidad
                    24 => html_utf8($dataUSER[0]['email']),                // Correo Electrónico
                    25 => html_utf8($dataUSER[0]['phone2']),               // Teléfono Fijo
                    26 => html_utf8($numOficioDesc),                       // Nro de oficio de inscripción
                    27 => html_utf8($adjuntoArchiv),                       // Adjuntó Archivo?
                ];
            }
            $response[] = $listaAlumCursos;
        }

        if (empty($response)) {
            echo false;
            exit;
        }
        $_SESSION['reporte_alumnos_cursos'] = $response;
        echo true;
        break;
}

function getDatosFU($dataFU = [])
{
    foreach ($dataFU as $infoFU) {
        switch ($infoFU['shortname']) {
            case 'Mat_Materno':
                global $apellidoMater;
                $apellidoMater = $infoFU['data'];
                break;
            case 'Mat_NivelGobierno':
                global $nivelGobierno;
                $nivelGobierno = $infoFU['data'];
                break;
            case 'Mat_ClasTrab':   // Rubro
                global $rubro;
                $rubro = $infoFU['data'];
                break;
            case 'Mat_ProvLab':
                global $provinLaboral;
                $provinLaboral = $infoFU['data'];
                break;
            case 'Mat_DistLab':
                global $distriLaboral;
                $distriLaboral = $infoFU['data'];
                break;
            case 'Mat_GenSex':
                global $gener;
                $gener = $infoFU['data'];
                break;
            case 'Mat_ProfCar':
                global $profe;
                $profe = $infoFU['data'];
                break;
            case 'Mat_RUC':
                global $ruc;
                $ruc = $infoFU['data'];
                break;
            case 'Mat_Cargo':
                global $cargo;
                $cargo = $infoFU['data'];
                break;
            case 'Mat_ModCont':
                global $modalidad;
                $modalidad = $infoFU['data'];
                break;
            case 'Mat_DescMod':
                global $descModalidad;
                $descModalidad = $infoFU['data'];
                break;
            case 'Mat_Oficio':
                global $numOficioDesc;
                $numOficioDesc = $infoFU['data'];
                break;
            case 'Mat_NombreArchivo':
                global $adjuntoArchiv;
                $adjuntoArchiv = empty(trim($infoFU['data'])) ? 'NO' : 'SI';
                break;
            case 'Mat_GradAca':
                global $gradoAcademic;
                $gradoAcademic = $infoFU['data'];
                break;
            case 'Mat_Discap':
                global $discapacidad;
                $discapacidad = $infoFU['data'];
                break;
            case 'Mat_DescDisc':
                global $descDiscapac;
                $descDiscapac = $infoFU['data'];
                break;
        }
    }
}

function html_utf8($rb)
{
    ## Sustituyo caracteres en la cadena final
    $rb = str_replace("Ã¡", "&aacute;", $rb);
    $rb = str_replace("Ã©", "&eacute;", $rb);
    $rb = str_replace("Â®", "&reg;", $rb);
    $rb = str_replace("Ã­", "&iacute;", $rb);
    $rb = str_replace("ï¿½", "&iacute;", $rb);
    $rb = str_replace("Ã³", "&oacute;", $rb);
    $rb = str_replace("Ãº", "&uacute;", $rb);
    $rb = str_replace("n~", "&ntilde;", $rb);
    $rb = str_replace("Âº", "&ordm;", $rb);
    $rb = str_replace("Âª", "&ordf;", $rb);
    $rb = str_replace("ÃƒÂ¡", "&aacute;", $rb);
    $rb = str_replace("Ã±", "&ntilde;", $rb);
    $rb = str_replace("Ã‘", "&Ntilde;", $rb);
    $rb = str_replace("ÃƒÂ±", "&ntilde;", $rb);
    $rb = str_replace("n~", "&ntilde;", $rb);
    $rb = str_replace("Ãš", "&Uacute;", $rb);
    return html_entity_decode($rb);
}
