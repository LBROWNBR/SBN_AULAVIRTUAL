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

            $mdl_UserId = $user['user_id'];
            $mdl_DNI = $user['user_DNI'];
            $mdl_Course_shortname = $user['short_name'];

            $dataUSER = $rm->getUsuario($mdl_UserId);
            $dataFU = $rm->getUsuarioDataField($mdl_UserId);
            $dataCPremat_CursoMatricula = $cpremat->Ver_Datos_Detallado_Curso_Matricula_By_Curso_Alumno($mdl_Course_shortname, $mdl_DNI);
            $dataCPremat_Certificado = $cpremat->Listar_DataCertificados_By_Idcurso_IdUserMoodle($_POST['id_curso'], $mdl_UserId); 
            $dataCPremat_Curso = $cpremat->get_CursosByShortname($mdl_Course_shortname);

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

            $v_Mat_Materno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Materno'] : '';
            $v_Mat_Paterno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Paterno'] : '';
            $v_Mat_Nombre = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Nombre'] : '';
            $v_Mat_DNI = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_DNI'] : '';
            $v_Mat_Entidad = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Entidad'] : '';
            $v_Mat_Cargo = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Cargo'] : '';
            $v_Mat_Area = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Area'] : '';
            $v_Mat_Profesion = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Profesion'] : '';
            $v_Mat_NivelGobierno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_NivelGobierno'] : '';
            $v_Mat_Clasificacion = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Clasificacion'] : '';
            $v_Mat_ModoContrato = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_ModoContrato'] : '';
            $v_Mat_Correo = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Correo'] : '';
            $v_Mat_Celular = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Celular'] : '';
            $v_Mat_Oficio = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Oficio'] : '';
            $v_Mat_Genero = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Genero'] : '';
            $v_Mat_Grado = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Grado'] : '';
            $v_Mat_Depa = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Depa'] : '';
            $v_Mat_Prov = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Prov'] : '';
            $v_Mat_Dist = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Dist'] : '';

            if(count($dataCPremat_Certificado)>0){
                $sConCertificado = ($dataCPremat_Certificado) ? $dataCPremat_Certificado[0]['ConCertificado'] : '';
                $x_ConCertificado = ($sConCertificado== '1') ? 'SI' : 'NO';
                $x_CodigoCertificado = ($dataCPremat_Certificado) ? $dataCPremat_Certificado[0]['NroCertificado'] : '';                
            }else{
                $x_ConCertificado = '';
                $x_CodigoCertificado = '';
            }            

            if($v_Mat_Materno!=''){

                $acu++;

                $NotasAlumno = $rm->getNotasCourse_By_CourseID_UserId($mdl_UserId, $_POST['id_curso']);

                $response[] = [
                    0 => $acu,                                                                  // N°
                    1 => html_utf8(trim($v_Mat_Materno)),        // Apellido Materno
                    2 => html_utf8(trim($v_Mat_Paterno)),        // Apellido Paterno
                    3 => html_utf8(trim($v_Mat_Nombre)),         // Nombres
                    4 => html_utf8($dataUSER[0]['username']),                                   // Username
                    5 => html_utf8(trim($v_Mat_DNI)),            // DNI
                    6 => html_utf8(trim($v_Mat_Entidad)),        // Entidad
                    7 => html_utf8(trim($v_Mat_Cargo)),          // Cargo
                    8 => html_utf8(trim($v_Mat_Area)),           // Area
                    9 => html_utf8(trim($v_Mat_Profesion)),      // Profesion
                    10 => html_utf8(trim($v_Mat_NivelGobierno)), // Nivel gobierno
                    11 => html_utf8(trim($v_Mat_Clasificacion)), // Rubro
                    12 => html_utf8(trim($v_Mat_ModoContrato)),  // Modalidad
                    13 => html_utf8($descModalidad),                                            // Descripcion Modalidad
                    14 => html_utf8(trim($v_Mat_Correo)),        // Correo Electronico
                    15 => html_utf8(trim($v_Mat_Celular)),       // Telefono
                    16 => html_utf8($v_Mat_Depa),            // Departamento laboral
                    17 => html_utf8($v_Mat_Prov),                        // Provincia laboral
                    18 => html_utf8($v_Mat_Dist),                        // Distrito laboral
                    19 => html_utf8($v_Mat_Oficio),                         // Nro de oficio de inscripción
                    20 => html_utf8($v_Mat_Genero),                         // Genero
                    21 => html_utf8($v_Mat_Grado),                          // Grado academico
                    22 => html_utf8($discapacidad),                         // Discapacidad
                    23 => html_utf8($descDiscapac),                         // Descripcion discapacidad

                    24 => html_utf8(trim($dataCPremat_Curso[0]['cur_Nombre']).' ('.trim($dataCPremat_Curso[0]['course_shortname']).')'),      // Nombre del curso
                    25 => html_utf8($dataCPremat_Curso[0]['fecha_ini']),          // mat_fechaini
                    26 => html_utf8($dataCPremat_Curso[0]['fecha_fin']),          // mat_fechafin
                    27 => html_utf8($dataCPremat_Curso[0]['cur_Modalidad']),         // mat_modalidad
                    28 => html_utf8($dataCPremat_Curso[0]['cur_HoraLect']),       // mat_horalectiva                
                    29 => html_utf8($x_ConCertificado),          // ConCertificado
                    30 => html_utf8($x_CodigoCertificado),        // Nro Certificado
                    31 => $NotasAlumno
                ];
            
            }
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
        
        $listaAlumCursos = [];
        $item = 0;
        foreach ($dataCURS as $curso) {

            $dataFU = $rm->getUsuarioDataField($curso['user_id']);

            $mdl_UserId = $curso['user_id'];
            $mdl_DNI    = $curso['user_DNI'];
            $mdl_Course_shortname = $curso['short_name'];
            
            $dataCPremat_CursoMatricula = $cpremat->Ver_Datos_Detallado_Curso_Matricula_By_Curso_Alumno($mdl_Course_shortname, $mdl_DNI);
            $dataCPremat_Curso = $cpremat->get_CursosByShortname($mdl_Course_shortname); 

            $nivelGobierno = '';
            // foreach ($dataFU as $infoFU) {
            //     if ($infoFU['shortname'] == 'Mat_NivelGobierno') {
            //         $nivelGobierno = $infoFU['data'];
            //     }
            // }
            if (count($dataFU) > 0) {
                getDatosFU($dataFU);
            }


            $v_Cur_Nombre = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_Nombre'] : '';
            $v_Cur_Shortname = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['course_shortname'] : '';
            $v_Cur_Fecha_ini = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['fecha_ini'] : '';
            $v_Cur_Fecha_fin = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['fecha_fin'] : '';
            $v_Cur_NumEvent = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_NumEvent'] : '';
            $v_Cur_CodPOI = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_CodPOI'] : '';

            $v_Mat_Entidad = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Entidad'] : '';            
            $v_Mat_Depa = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Depa'] : '';
            $v_Mat_Prov = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Prov'] : '';
            $v_Mat_Dist = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Dist'] : '';
            $v_Mat_NivelGobierno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_NivelGobierno'] : '';
            $v_Mat_Materno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Materno'] : '';
            $v_Mat_Paterno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Paterno'] : '';
            $v_Mat_Nombre = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Nombre'] : '';
            $v_Mat_Grado = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Grado'] : '';
            $v_Mat_Profesion = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Profesion'] : '';
            $v_Mat_Cargo = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Cargo'] : '';
            $v_Mat_ModoContrato = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_ModoContrato'] : '';
            $v_Mat_Area = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Area'] : '';

            
            if($v_Mat_Materno !=''){
                
                $item++;

                $listaAlumCursos[] = [
                    'item'=> $item,
                    'course_NumEvent' => html_utf8($v_Cur_NumEvent),
                    'user_Depa' => html_utf8($v_Mat_Depa),
                    'user_Prov' => html_utf8($v_Mat_Prov),
                    'user_Dist' => html_utf8($v_Mat_Dist),
                    'course_ini' => html_utf8($v_Cur_Fecha_ini),
                    'course_fin' => html_utf8($v_Cur_Fecha_fin),                
                    'course_id' => $curso['course_id'],
                    'course_name' => html_utf8($v_Cur_Nombre.' ('.$v_Cur_Shortname.')'),
                    'user_entidad' => html_utf8($v_Mat_Entidad),
                    'user_nivelGob' => html_utf8($v_Mat_NivelGobierno),
                    'user_Nombres' => html_utf8(trim($v_Mat_Paterno).' '.trim($v_Mat_Materno).' '.trim($v_Mat_Nombre)),
                    'user_Grado' => html_utf8($v_Mat_Grado),
                    'user_Profesion' => html_utf8($v_Mat_Profesion),
                    'user_Cargo' => html_utf8($v_Mat_Cargo),
                    'user_Contrato' => html_utf8($v_Mat_ModoContrato),
                    'user_Area' => html_utf8($v_Mat_Area),
                    'course_POI' => html_utf8($v_Cur_CodPOI)                
                ];

            }
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
        $DataExcel['dataAlumnosInscritos'] = $listaAlumCursos;
        

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

        $acu = 0;
        $listaAlumCursos = [];
        foreach ($dataCURS as $curso) {
          

            $mdl_UserId = $curso['user_id'];
            $mdl_DNI = $curso['user_DNI'];
            $mdl_Course_shortname = $curso['short_name'];

            $dataUSER = $rm->getUsuario($curso['user_id']);
            $dataCert = $rm->getCodigoGenerateCertificate($curso['user_id'], $idCurso);
            $dataFU = $rm->getUsuarioDataField($curso['user_id']);

            $dataCPremat_CursoMatricula = $cpremat->Ver_Datos_Detallado_Curso_Matricula_By_Curso_Alumno($mdl_Course_shortname, $mdl_DNI);
            $dataCPremat_Certificado = $cpremat->Listar_DataCertificados_By_Idcurso_IdUserMoodle($_POST['id_curso'], $mdl_UserId); 
            $dataCPremat_Curso = $cpremat->get_CursosByShortname($mdl_Course_shortname);

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

            $v_Cur_Nombre = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_Nombre'] : '';
            $v_Cur_Shortname = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['course_shortname'] : '';
            $v_Cur_Fecha_ini = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['fecha_ini'] : '';
            $v_Cur_Fecha_fin = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['fecha_fin'] : '';
            $v_Cur_NumEvent = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_NumEvent'] : '';
            $v_Cur_CodPOI = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_CodPOI'] : '';
            $v_Cur_Modalidad = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_Modalidad'] : '';
            $v_Cur_HoraLect = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_HoraLect'] : '';


            $v_Mat_Materno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Materno'] : '';
            $v_Mat_Paterno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Paterno'] : '';
            $v_Mat_Nombre = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Nombre'] : '';
            $v_Mat_DNI = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_DNI'] : '';
            $v_Mat_Entidad = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Entidad'] : '';
            $v_Mat_Cargo = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Cargo'] : '';
            $v_Mat_Area = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Area'] : '';
            $v_Mat_Profesion = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Profesion'] : '';
            $v_Mat_NivelGobierno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_NivelGobierno'] : '';
            $v_Mat_Clasificacion = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Clasificacion'] : ''; //RUBRO
            $v_Mat_ModoContrato = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_ModoContrato'] : '';
            $v_Mat_Correo = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Correo'] : '';
            $v_Mat_Celular = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Celular'] : '';
            $v_Mat_Oficio = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Oficio'] : '';
            $v_Mat_Genero = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Genero'] : '';
            $v_Mat_Grado = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Grado'] : '';
			$v_Mat_Depa = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Depa'] : '';
            $v_Mat_Prov = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Prov'] : '';
            $v_Mat_Dist = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Dist'] : '';

            if(count($dataCPremat_Certificado)>0){
                $sConCertificado = ($dataCPremat_Certificado) ? $dataCPremat_Certificado[0]['ConCertificado'] : '';
                $x_ConCertificado = ($sConCertificado== '1') ? 'SI' : 'NO';
                $x_CodigoCertificado = ($dataCPremat_Certificado) ? $dataCPremat_Certificado[0]['NroCertificado'] : '';                
            }else{
                $x_ConCertificado = '';
                $x_CodigoCertificado = '';
            }   

            if($v_Mat_Materno != ''){
                
                $acu++;

                $NotasAlumno = $rm->getNotasCourse_By_CourseID_UserId($curso['user_id'], $idCurso);

                $listaAlumCursos[] = [
                    0 => $acu,                                              // N°
                    1 => html_utf8($dataUSER[0]['username']),               // Codigo
                    2 => html_utf8($v_Mat_Paterno),               // Apellido Paterno
                    3 => html_utf8($v_Mat_Materno),                         // Apellido Materno'
                    4 => html_utf8($v_Mat_Nombre),              // Nombres
                    5 => html_utf8($v_Mat_DNI),               // DNI
                    6 => html_utf8($v_Mat_Entidad), //html_utf8($dataUSER[0]['institution']),            // Entidad
                    7 => date("d/m/Y", $dataUSER[0]['timecreated']),        // Fecha registro
                    8 => html_utf8($v_Mat_NivelGobierno),                         // Nivel gobierno
                    9 => html_utf8($v_Mat_Clasificacion),                                 // Rubro
                    10 => html_utf8($v_Mat_Depa),            // Departamento_Laboral
                    11 => html_utf8($v_Mat_Prov),                        // Provincia_Laboral
                    12 => html_utf8($v_Mat_Dist),                        // Distrito_Laboral
                    13 => html_utf8($v_Mat_Genero),                                // Género
                    14 => html_utf8($v_Mat_Profesion),                                // Profesión
                    15 => html_utf8($ruc),                                  // RUC
                    16 => html_utf8($v_Mat_Area),            // Área
                    17 => html_utf8($v_Mat_Cargo),                                // Cargo
                    18 => html_utf8($v_Mat_ModoContrato),                            // Modalidad Contrato
                    19 => html_utf8($v_Mat_ModoContrato),                        // Descrip. Modalidad
                    20 => html_utf8($v_Mat_Correo),                 // Correo Electrónico
                    21 => html_utf8($v_Mat_Celular),                // Teléfono Fijo
                    22 => html_utf8($v_Mat_Oficio),                        // Nro de oficio de inscripción
                    23 => html_utf8($adjuntoArchiv),                        // Adjuntó Archivo?
                    //24 => !empty($dataCert) ? $dataCert[0]['code'] : '-',    // Codigo de certificado autogenerado
                    24 => html_utf8($v_Cur_Shortname),

                    25 => html_utf8($v_Cur_Nombre),   // Nombre del curso
                    26 => html_utf8($v_Cur_Fecha_ini),        // mat_fechaini
                    27 => html_utf8($v_Cur_Fecha_fin),        // mat_fechafin
                    28 => html_utf8($v_Cur_Modalidad),       // mat_modalidad
                    29 => html_utf8($v_Cur_HoraLect),     // mat_horalectiva
                    30 => html_utf8($v_Cur_CodPOI),          // mat_codpoi
                    31 => html_utf8($x_ConCertificado),        // ConCertificado
                    32 => html_utf8($x_CodigoCertificado),      // NroCertificado
                    33 => $NotasAlumno  // NOTAS
                ];

            }

            
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

        $mdl_Course_shortname = $dataCurso[0]['shortname'];
        $dataCPremat_Curso = $cpremat->get_CursosByShortname($mdl_Course_shortname); 

        $v_Cur_Nombre = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_Nombre'] : '';
        $v_Cur_Shortname = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['course_shortname'] : '';
        $v_Cur_Fecha_ini = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['fecha_ini'] : '';
        $v_Cur_Fecha_fin = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['fecha_fin'] : '';
        $v_Cur_NumEvent = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_NumEvent'] : '';
        $v_Cur_CodPOI = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_CodPOI'] : '';
        $v_Cur_Modalidad = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_Modalidad'] : '';
        $v_Cur_HoraLect = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_HoraLect'] : '';

        $listaAlumNotas = [];
        $acu = 0;
        foreach ($dataUCN as $alumno) {            

            $idUser = $alumno['user_id'];
            $mdl_DNI = $alumno['user_DNI'];

            $dataUSER = $rm->getUsuario($idUser);
            $dataFU = $rm->getUsuarioDataField($idUser);
            $dataCPremat_CursoMatricula = $cpremat->Ver_Datos_Detallado_Curso_Matricula_By_Curso_Alumno($mdl_Course_shortname, $mdl_DNI);
            $dataCPremat_Certificado = $cpremat->Listar_DataCertificados_By_Idcurso_IdUserMoodle($_POST['id_curso'], $idUser); 

            $apellidoMater = '';
            foreach ($dataFU as $infoFU) {
                if ($infoFU['shortname'] == 'Mat_Materno') {
                    $apellidoMater = $infoFU['data'];
                }
            }

            $v_Mat_Materno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Materno'] : '';
            $v_Mat_Paterno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Paterno'] : '';
            $v_Mat_Nombre = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Nombre'] : '';
            $v_Mat_DNI = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_DNI'] : '';
            $v_Mat_Entidad = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Entidad'] : '';

            if(count($dataCPremat_Certificado)>0){
                $sConCertificado = ($dataCPremat_Certificado) ? $dataCPremat_Certificado[0]['ConCertificado'] : '';
                $x_ConCertificado = ($sConCertificado== '1') ? 'SI' : 'NO';
                $x_CodigoCertificado = ($dataCPremat_Certificado) ? $dataCPremat_Certificado[0]['NroCertificado'] : '';                
            }else{
                $x_ConCertificado = '';
                $x_CodigoCertificado = '';
            } 

            if($v_Mat_Paterno !=''){

                $acu++;

                $nota = '';
                $estd = '';
                $notaCurso = [];
                foreach ($dataPC as $prueba) {
                    $idPrueba = $prueba['prueba_id'];
                    $dataNPR = $rm->getNotasByPruebas($idUser, $idPrueba);
                    if (isset($dataNPR[0]['prueba_nota'])) {
                        $nota = $dataNPR[0]['prueba_nota'];
                        $estd = $nota >= 12 ? 'APROBADO' : 'DESAPROBADO';
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

                $NotaPromFinal = $rm->getNotaPromFinal($idUser, $idCurso);

                $listaAlumNotas[] = [
                    0 => $acu,                                              // N°
                    1 => html_utf8($v_Mat_Paterno),               // Apellido Paterno
                    2 => html_utf8($v_Mat_Materno),                         // Apellido Materno
                    3 => html_utf8($v_Mat_Nombre),              // Nombres
                    4 => html_utf8($v_Mat_DNI),               // DNI
                    5 => html_utf8($v_Mat_Entidad),            // Entidad
                    6 => $notaCurso,
                    7 => $NotaPromFinal,
                    8 => html_utf8(trim($v_Cur_Nombre).' ('.trim($v_Cur_Shortname).')'),
                    9 => $v_Cur_Fecha_ini,
                    10 => $v_Cur_Fecha_fin,
                    11 => $v_Cur_Modalidad,
                    12 => $v_Cur_HoraLect,
                    13 => $v_Cur_CodPOI,
                    14 => $x_ConCertificado,
                    15 => $x_CodigoCertificado
                ];

            }

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
        $cpremat = new matricula_Model;
        //$dataCI = $rm->getCursosWithInscritos($_POST['REP_ALCU_filtro'], $_POST['REP_ALCU_desde'], $_POST['REP_ALCU_hasta']);
        $xCodCursoMoodle = $_POST['cboCursos_R7'];
        $dataCI = $rm->getCursosWithInscritos_ByIdCurso($xCodCursoMoodle);
        $dataPC = $rm->getForosPruebasByCursos($xCodCursoMoodle);
        $response = [];
        foreach ($dataCI as $course) {            

            $dataCURS = $rm->getCursosWithUsuariosInscritos($course['course_id']);                      

            $acu = 0;
            $listaAlumCursos = [];
            foreach ($dataCURS as $curso) {
                
                $dataUSER = $rm->getUsuario($curso['user_id']);
                $dataFU = $rm->getUsuarioDataField($curso['user_id']);

                $mdl_UserId = $curso['user_id'];
                $mdl_DNI    = $curso['user_DNI'];
                $mdl_Course_shortname = $curso['short_name'];  

                $dataCPremat_CursoMatricula = $cpremat->Ver_Datos_Detallado_Curso_Matricula_By_Curso_Alumno($mdl_Course_shortname, $mdl_DNI);
                $dataCPremat_Certificado = $cpremat->Listar_DataCertificados_By_Idcurso_IdUserMoodle($course['course_id'], $mdl_UserId); 
                $dataCPremat_Curso = $cpremat->get_CursosByShortname($mdl_Course_shortname); 
                
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

                $nota = '';
                $estd = '';
                $notaCurso = [];
                foreach ($dataPC as $prueba) {
                    $idPrueba = $prueba['prueba_id'];
                    $dataNPR = $rm->getNotasByPruebas($curso['user_id'], $idPrueba);
                    if (isset($dataNPR[0]['prueba_nota'])) {
                        $nota = $dataNPR[0]['prueba_nota'];
                        $estd = $nota >= 12 ? 'APROBADO' : 'DESAPROBADO';
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

                
                $NotaPromFinal = $rm->getNotaPromFinal($curso['user_id'], $xCodCursoMoodle);

                $v_Cur_Nombre = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_Nombre'] : '';
                $v_Cur_Shortname = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['course_shortname'] : '';
                $v_Cur_Fecha_ini = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['fecha_ini'] : '';
                $v_Cur_Fecha_fin = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['fecha_fin'] : '';
                $v_Cur_NumEvent = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_NumEvent'] : '';
                $v_Cur_CodPOI = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_CodPOI'] : '';
                $v_Cur_Modalidad = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_Modalidad'] : '';
                $v_Cur_HoraLect = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_HoraLect'] : '';

                $v_Mat_Materno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Materno'] : '';
                $v_Mat_Paterno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Paterno'] : '';
                $v_Mat_Nombre = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Nombre'] : '';
                $v_Mat_DNI = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_DNI'] : '';
                $v_Mat_Entidad = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Entidad'] : '';
                $v_Mat_Cargo = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Cargo'] : '';
                $v_Mat_Area = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Area'] : '';
                $v_Mat_Profesion = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Profesion'] : '';
                $v_Mat_NivelGobierno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_NivelGobierno'] : '';
                $v_Mat_Clasificacion = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Clasificacion'] : '';
                $v_Mat_ModoContrato = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_ModoContrato'] : '';
                $v_Mat_Correo = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Correo'] : '';
                $v_Mat_Celular = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Celular'] : '';
                $v_Mat_Oficio = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Oficio'] : '';
                $v_Mat_Genero = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Genero'] : '';
                $v_Mat_Grado = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Grado'] : '';
                $v_Mat_Depa = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Depa'] : '';
                $v_Mat_Prov = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Prov'] : '';
                $v_Mat_Dist = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Dist'] : '';

                if(count($dataCPremat_Certificado)>0){
                    $sConCertificado = ($dataCPremat_Certificado) ? $dataCPremat_Certificado[0]['ConCertificado'] : '';
                    $x_ConCertificado = ($sConCertificado== '1') ? 'SI' : 'NO';
                    $x_CodigoCertificado = ($dataCPremat_Certificado) ? $dataCPremat_Certificado[0]['NroCertificado'] : '';                
                }else{
                    $x_ConCertificado = '';
                    $x_CodigoCertificado = '';
                }  

                if($v_Mat_Paterno !=''){

                    $acu++;
                    
                    $listaAlumCursos[] = [
                        0 => html_utf8($v_Cur_Shortname),
                        1 => html_utf8($v_Cur_Nombre),
                        2 => html_utf8($v_Cur_Fecha_ini),
                        3 => html_utf8($v_Cur_Fecha_fin),
                        4 => html_utf8($acu),                                  // N°
                        5 => html_utf8($dataUSER[0]['username']),              // Codigo
                        6 => html_utf8($v_Mat_Paterno),              // Apellido Paterno
                        7 => html_utf8($v_Mat_Materno),                        // Apellido Materno'
                        8 => html_utf8($v_Mat_Nombre),             // Nombres
                        9 => html_utf8($v_Mat_DNI),              // DNI
                        10 => html_utf8($v_Mat_Entidad),          // Entidad
                        11 => date("d/m/Y", $dataUSER[0]['timecreated']),      // Fecha registro
                        12 => html_utf8($v_Mat_NivelGobierno),                       // Nivel gobierno
                        13 => html_utf8($v_Mat_Clasificacion),                               // Rubro
                        14 => html_utf8($v_Mat_Depa),           // Departamento_Laboral
                        15 => html_utf8($v_Mat_Prov),                       // Provincia_Laboral
                        16 => html_utf8($v_Mat_Dist),                       // Distrito_Laboral
                        17 => html_utf8($v_Mat_Genero),                               // Género
                        18 => html_utf8($v_Mat_Profesion),                               // Profesión
                        19 => html_utf8($ruc),                                 // RUC
                        20 => html_utf8($v_Mat_Area),           // Área
                        21 => html_utf8($v_Mat_Cargo),                               // Cargo
                        22 => html_utf8($v_Mat_ModoContrato),                           // Modalidad
                        23 => html_utf8($descModalidad),                       // Descrip. Modalidad
                        24 => html_utf8($v_Mat_Correo),                // Correo Electrónico
                        25 => html_utf8($v_Mat_Celular),               // Teléfono Fijo
                        26 => html_utf8($numOficioDesc),                       // Nro de oficio de inscripción
                        27 => html_utf8($adjuntoArchiv),                       // Adjuntó Archivo?

                        28 => html_utf8($v_Cur_HoraLect),                  
                        29 => html_utf8($v_Cur_CodPOI),                       
                        30 => html_utf8($x_ConCertificado),                     
                        31 => html_utf8($x_CodigoCertificado),      
                        
                        32 => $notaCurso,
                        33 => $NotaPromFinal,
                    ];

                }

                
            }
            
            $response[] = $listaAlumCursos;            
        }

        if (empty($response)) {
            echo false;
            exit;
        }

        $pruebas = array_values(array_unique(array_column($dataPC, 'prueba_nombre')));
        $response02 = [
            'curso' => 'Curso de prueba',
            'prueb' => $pruebas,
            'datos' => $response,
        ];

        $_SESSION['reporte_alumnos_cursos'] = $response02;
        echo true;
    break;



        case 'reporte_datos_abiertos':

            session_start();
            $rm = new reporte_moodle_Model;
            $cpremat = new matricula_Model;
    
            $idCurso = $_POST['id_curso'];
            $dataCURS = $rm->getCursosWithUsuariosInscritos($idCurso);  
    
            $acu = 0;
            $listaAlumCursos = [];
            foreach ($dataCURS as $curso) {
                $acu++;
                $dataUSER = $rm->getUsuario($curso['user_id']);

                $mdl_UserId = $curso['user_id'];
                $mdl_DNI    = $curso['user_DNI'];
                $mdl_Course_shortname = $curso['short_name'];
                
                $dataCPremat_CursoMatricula = $cpremat->Ver_Datos_Detallado_Curso_Matricula_By_Curso_Alumno($mdl_Course_shortname, $mdl_DNI);
                $dataCPremat_Certificado = $cpremat->Listar_DataCertificados_By_Idcurso_IdUserMoodle($_POST['id_curso'], $mdl_UserId); 
                $dataCPremat_Curso = $cpremat->get_CursosByShortname($mdl_Course_shortname); 

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
    
                $v_Cur_Nombre = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_Nombre'] : '';
                $v_Cur_Shortname = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['course_shortname'] : '';
                $v_Cur_Fecha_ini = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['fecha_ini'] : '';
                $v_Cur_Fecha_fin = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['fecha_fin'] : '';
                $v_Cur_NumEvent = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_NumEvent'] : '';
                $v_Cur_CodPOI = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_CodPOI'] : '';
                $v_Cur_Modalidad = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_Modalidad'] : '';
                $v_Cur_HoraLect = ($dataCPremat_Curso) ? $dataCPremat_Curso[0]['cur_HoraLect'] : '';

                $v_Mat_Materno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Materno'] : '';
                $v_Mat_Paterno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Paterno'] : '';
                $v_Mat_Nombre = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Nombre'] : '';
                $v_Mat_DNI = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_DNI'] : '';
                $v_Mat_Entidad = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Entidad'] : '';
                $v_Mat_Cargo = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Cargo'] : '';
                $v_Mat_Area = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Area'] : '';
                $v_Mat_Profesion = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Profesion'] : '';
                $v_Mat_NivelGobierno = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_NivelGobierno'] : '';
                $v_Mat_Clasificacion = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Clasificacion'] : '';
                $v_Mat_ModoContrato = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_ModoContrato'] : '';
                $v_Mat_Correo = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Correo'] : '';
                $v_Mat_Celular = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Celular'] : '';
                $v_Mat_Oficio = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Oficio'] : '';
                $v_Mat_Genero = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Genero'] : '';
                $v_Mat_Grado = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Grado'] : '';
                $v_Mat_Depa = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Depa'] : '';
                $v_Mat_Prov = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Prov'] : '';
                $v_Mat_Dist = ($dataCPremat_CursoMatricula) ? $dataCPremat_CursoMatricula[0]['Mat_Dist'] : '';

                if(count($dataCPremat_Certificado)>0){
                    $sConCertificado = ($dataCPremat_Certificado) ? $dataCPremat_Certificado[0]['ConCertificado'] : '';
                    $x_ConCertificado = ($sConCertificado== '1') ? 'SI' : 'NO';
                    $x_CodigoCertificado = ($dataCPremat_Certificado) ? $dataCPremat_Certificado[0]['NroCertificado'] : '';                
                }else{
                    $x_ConCertificado = '';
                    $x_CodigoCertificado = '';
                }  
    
                if($v_Mat_Paterno !=''){
                    $listaAlumCursos[] = [
                        0 => html_utf8($v_Cur_Modalidad),       // mat_modalidad
                        1 => html_utf8($v_Cur_Nombre.' ('. $v_Cur_Shortname.')'),   // Nombre del curso
                        2 => html_utf8($v_Cur_Fecha_ini),        // mat_fechaini
                        3 => html_utf8($v_Cur_Fecha_fin),        // mat_fechafin
                        4 => html_utf8($v_Cur_HoraLect),     // mat_horalectiva
                        5 => html_utf8($v_Mat_DNI),               // DNI
                        6 => html_utf8($v_Mat_Nombre),              // Nombres
                        7 => html_utf8($v_Mat_Paterno),               // Apellido Paterno
                        8 => html_utf8($v_Mat_Materno),                         // Apellido Materno'
                        9 => html_utf8($v_Mat_Profesion),                         // profesion
                        10 => html_utf8($v_Mat_Entidad), //html_utf8($dataUSER[0]['institution']),            // Entidad                    
                        11 => html_utf8($v_Mat_Depa),            // Departamento_Laboral
                        12 => html_utf8($v_Mat_Prov),                        // Provincia_Laboral
                        13 => html_utf8($v_Mat_Dist),                        // Distrito_Laboral
                        14 => html_utf8($v_Mat_Grado)                        // Distrito_Laboral
                    ];
                }
                
            }
    
            $response = [
                'curso' => count($dataCURS) > 0 ? $dataCURS[0]['course_name'].' ('.$dataCURS[0]['short_name'].')' : null,
                'datos' => $listaAlumCursos
            ];
            if (empty($dataCURS)) {
                echo false;
                exit;
            }
            $_SESSION['reporte_datos_abiertos'] = $response;
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
