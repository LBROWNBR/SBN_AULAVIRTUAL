<?php

require_once dirname(__FILE__, 3) . "/app/model/reporte_moodle_Model.php";
require_once dirname(__FILE__, 3) . "/app/model/reporte_Model.php";
require_once dirname(__FILE__, 3) . "/app/model/matricula_Model.php";
require_once dirname(__FILE__, 3) . "/app/libs/helper.php";
require_once dirname(__FILE__, 3) . "/app/libs/SimpleXLSX/SimpleXLSX.php";

$_POST['op'] = isset($_POST['op']) ? $_POST['op'] : die();

switch ($_POST['op']) {

    case 'combo_categorias':
        $rm = new reporte_moodle_Model;
        $dataCATC = $rm->getCategoriasCursos();
        $combo = [];
        foreach ($dataCATC as $data) {
            $combo[] = array(
                0 => $data['id'],
                1 => ucfirst(strtolower($data['name'])),
            );
        }
        echo json_encode($combo);
        break;

    case 'combo_cursos':
        $cm = new reporte_Model;
        $dataCURS = $cm->get_Cursos();
        $combo = [];
        foreach ($dataCURS as $data) {
            $combo[] = [
                0 => $data[0],
                1 => ucfirst(strtolower($data[1])),
            ];
        }
        array_multisort(array_column($combo, 1), SORT_ASC, $combo);
        echo json_encode($combo);
        break;

    case 'reporte_cursos_01':
        session_start();
        $cm = new reporte_Model;
        $rm = new reporte_moodle_Model;
        $cpremat = new matricula_Model;

        $mdl_IdCurso = $_POST['id_curso'];

        $dataCertificados = $cpremat->Listar_DataCertificados_Func_Serv_Certificado_By_Idcurso($mdl_IdCurso);
        $dataCURS = $rm->getCursosWithUsuariosInscritos($mdl_IdCurso);

        if (empty($dataCURS)) {
            echo false;
            exit;
        }
        $dataMR_pre = [];
        foreach ($dataCURS as $data) {
            $dataUSER = $rm->getUsuario($data['user_id']);
            $dataFIUS = $rm->getUsuarioDataField($data['user_id']);
            $tipoFuncio = '';
            foreach ($dataFIUS as $infoFU) {
                switch ($infoFU['shortname']) {
                    case 'Mat_ClasTrab':
                        $tipoFuncio = $infoFU['data'];
                        break;
                }
            }
            $dataMR_pre[] = [
                0 => $dataUSER[0]['institution'],
                1 => strtolower(html_utf8($tipoFuncio)),
            ];
        }
        $entidades = array_values(array_unique(array_column($dataCURS, 'user_entidad')));
        $dataMR = [];
        foreach ($entidades as $entidad) {
            $numFunc = 0;
            $numServ = 0;
            foreach ($dataMR_pre as $MR_pre) {
                if (in_array($entidad, $MR_pre)) {
                    switch ($MR_pre[1]) {
                        case 'funcionario público':
                            $numFunc++;
                            break;
                        case 'servidor público':
                            $numServ++;
                            break;
                    }
                }
            }
            if ($numFunc > 0) {
                $dataMR[] = array($entidad, 'funcionario público', $numFunc);
            }
            if ($numServ > 0) {
                $dataMR[] = array($entidad, 'servidor público', $numServ);
            }
        }
        $numTotalFunc = 0;
        $numTotalServ = 0;
        foreach ($dataMR as $row) {
            switch ($row[1]) {
                case 'funcionario público':
                    $numTotalFunc += $row[2];
                    break;
                case 'servidor público':
                    $numTotalServ += $row[2];
                    break;
            }
        }
        $response = [
            'rep_cursoId' => $dataCURS[0]['course_id'],
            'rep_lugar' => 'SIN LUGAR',
            'rep_cursoNombre' => $dataCURS[0]['course_name'],
            'rep_cursoIni' => $dataCURS[0]['course_ini'],
            'rep_cursoFin' => $dataCURS[0]['course_fin'],
            'rep_detalle' => $dataMR,
            'rep_numTotalFunc' => $numTotalFunc,
            'rep_numTotalServ' => $numTotalServ,
            'rep_totalCapac' => $numTotalFunc + $numTotalServ,
            'rep_detalle_certificado' => $dataCertificados            
        ];
        if ($response['rep_totalCapac'] == 0) {
            echo false;
            exit;
        }
        $_SESSION['reporte_cursos'] = $response;
        echo true;
        break;

    case 'reporte_cursos_02':
        session_start();
        $rm = new reporte_moodle_Model;
        $dataCURS = $rm->getCursosWithUsuariosInscritos($_POST['id_curso']);
        if (empty($dataCURS)) {
            echo false;
            exit;
        }
        $dataMR = [];
        foreach ($dataCURS as $data) {
            $dataUSER = $rm->getUsuario($data['user_id']);
            $dataFIUS = $rm->getUsuarioDataField($data['user_id']);
            $dataCERT = $rm->getCodigoGenerateCertificate($data['user_id'], $_POST['id_curso']);
            $tipoFuncio = '';
            foreach ($dataFIUS as $infoFU) {
                switch ($infoFU['shortname']) {
                    case 'Mat_ClasTrab':
                        $tipoFuncio = $infoFU['data'];
                        break;
                }
            }
            $dataMR[] = [
                0 => $dataUSER[0]['institution'],
                1 => strtolower(html_utf8($tipoFuncio)),
                2 => !empty($dataCERT) ? 'SI' : 'NO',
            ];
        }
        $entidades = array_values(array_unique(array_column($dataMR, 0)));
        $numFunc = 0;
        $numServ = 0;
        $numFuncCert = 0;
        $numServCert = 0;
        $numEntiCert = 0;
        foreach ($entidades as $entidad) {
            $validCert = 0;
            foreach ($dataMR as $detalle) {
                if (in_array($entidad, $detalle)) {
                    switch ($detalle[1]) {
                        case 'funcionario público':
                            if ($detalle[2] == 'SI') {
                                $validCert++;
                                $numFuncCert++;
                            }
                            $numFunc++;
                            break;
                        case 'servidor público':
                            if ($detalle[2] == 'SI') {
                                $validCert++;
                                $numServCert++;
                            }
                            $numServ++;
                            break;
                    }
                }
            }
            if ($validCert > 0) {
                $numEntiCert++;
            }
        }
        $response = [
            'rep_cursoId' => $dataCURS[0]['course_id'],
            'rep_lugar' => 'SIN LUGAR',
            'rep_cursoNombre' => $dataCURS[0]['course_name'],
            'rep_entidadAsis' => count($entidades),
            'rep_entidadCert' => $numEntiCert,
            'rep_particiAsis' => [
                0 => $numFunc,              // N° funcionarios
                1 => $numServ,              // N° Servidores
                2 => $numFunc + $numServ,   // N° Totla
            ],
            'rep_particiCert' => [
                0 => $numFuncCert,
                1 => $numServCert,
                2 => $numFuncCert + $numServCert,
            ],
        ];
        // echo json_encode($response);
        if ($response['rep_entidadAsis'] == 0) {
            echo false;
            exit;
        }
        $_SESSION['reporte_cursos'] = $response;
        echo true;
        break;

    case 'migrar_data':
        $archivo = (isset($_FILES['INSC_excel'])) ? $_FILES['INSC_excel'] : null;
        if ($archivo) {
            $name_file = basename($archivo["name"]);
            $ruta_destino_archivo = "../../Archivos/PDF/{$name_file}";
            $archivo_ok = move_uploaded_file($archivo['tmp_name'], $ruta_destino_archivo);
            if (!$archivo_ok) {
                $response = [
                    'state' => false,
                    'message' => "No se pudo cargar el archivo, intentelo nuevamente."
                ];
                echo json_encode($response);
                exit;
            }
        }

        $typeFile = explode('.', "{$name_file}");

        switch (strtolower(end($typeFile))) {
            case 'csv':
                $dataXLSX = [];
                $dataCSV = fopen("{$ruta_destino_archivo}", "r");
                while (($datos = fgetcsv($dataCSV, 1000, ",")) !== FALSE) {
                    $arrayData = [];
                    foreach ($datos as $value) {
                        $arrayData[] = $value;
                    }
                    $dataXLSX[] = $arrayData;
                }
                fclose($dataCSV);
                break;
            case 'xlsx':
                $xlsx = new SimpleXLSX("{$ruta_destino_archivo}");
                $dataXLSX = $xlsx->rows();
                break;
            default:
                $response = [
                    'state' => false,
                    'message' => "Ingrese el tipo de archivo correcto."
                ];
                unlink("{$ruta_destino_archivo}");
                echo json_encode($response);
                exit;
                break;
        }
        unlink("{$ruta_destino_archivo}");

        $fieldNames = '';
        $paramCount = '';
        foreach ($dataXLSX as $data) {
            foreach ($data as $row) {
                if (empty(trim($row))) {
                    $response = [
                        'state' => false,
                        'message' => "Todos los campos cabezera son obligatorios."
                    ];
                    echo json_encode($response);
                    exit;
                }
                $fieldNames .= "`{$row}`,";
                $paramCount .= "?,";
            }
            $fieldNames = '`Usu_ID`,`cur_ID`,' . substr($fieldNames, 0, -1);
            $paramCount = '?,?,' . substr($paramCount, 0, -1);
            break;
        }
        unset($dataXLSX[0]);

        $cm = new reporte_Model;
        $acu = 0;
        try {
            foreach ($dataXLSX as $value) {
                array_unshift($value, $_POST['ID_USER'], $_POST['INCS_cursos']);
                $state = $cm->migracion_Matricula($fieldNames, $paramCount, $value);
                $acu++;
            }
        } catch (Exception $e) {
            $response = [
                'state' => false,
                'message' => "Algo salio mal, solo se importaron los primeros {$acu} registros."
            ];
            echo json_encode($response);
            exit;
        }
        $response = [
            'state' => true,
            'message' => "Cargado correctamente, se importaron {$acu} registros."
        ];
        echo json_encode($response);
        break;
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
