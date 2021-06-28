<?php

require_once dirname(__FILE__, 3) . "/app/model/reporte_moodle_Model.php";
require_once dirname(__FILE__, 3) . "/app/model/matricula_Model.php";
require_once dirname(__FILE__, 3) . "/app/libs/helper.php";

$_POST['op'] = isset($_POST['op']) ? $_POST['op'] : die();

switch ($_POST['op']) {

    case 'Buscar_Alumnos_Certificacion_By_Curso':
       
        $mdl_Anio = $_POST['mdl_Anio'];
        $mdl_IdCurso = $_POST['mdl_IdCurso'];

        session_start();
        $cpremat = new matricula_Model;

        $dataCertificados = $cpremat->Listar_DataCertificados($mdl_Anio, $mdl_IdCurso);    
        $_SESSION['Lista_certificacion_BY_Curso_Anio'] = $dataCertificados;
        echo true;
        
        break;

    case 'Extraer_Lista_Alumnos_Moodle_Detallado':
       
        $mdl_Anio = $_POST['mdl_Anio'];
        $idCurso = $_POST['mdl_IdCurso'];

        session_start();
        $rm = new reporte_moodle_Model;
        $cpremat = new matricula_Model;

        $dataCURS = $rm->getCursosWithUsuariosInscritos($idCurso);
        $xNombreCurso = count($dataCURS) > 0 ? $dataCURS[0]['course_name'] : null;
        $xNombreCortaCurso = count($dataCURS) > 0 ? $dataCURS[0]['short_name'] : null;

        $dataPreMatCURSO = $cpremat->get_CursosByShortname($xNombreCortaCurso);
        $xPreMatCurs_FechaIni = count($dataPreMatCURSO) > 0 ? $dataPreMatCURSO[0]['cur_FechaInicio'] : null;
        $xPreMatCurs_FechaFin = count($dataPreMatCURSO) > 0 ? $dataPreMatCURSO[0]['cur_FechaFin'] : null;
        $xPreMatCurs_IdCategoria = count($dataPreMatCURSO) > 0 ? $dataPreMatCURSO[0]['cur_Categoria'] : null;
        $xPreMatCurs_HoraLect = count($dataPreMatCURSO) > 0 ? $dataPreMatCURSO[0]['cur_HoraLect'] : null;
        $xPreMatCurs_Modalidad = count($dataPreMatCURSO) > 0 ? $dataPreMatCURSO[0]['cur_Modalidad'] : null;
        $xPreMatCurs_NumEvent = count($dataPreMatCURSO) > 0 ? $dataPreMatCURSO[0]['cur_NumEvent'] : null;
        $xPreMatCurs_CodPOI = count($dataPreMatCURSO) > 0 ? $dataPreMatCURSO[0]['cur_CodPOI'] : null;       

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
            $gradoAcademic = '';
            $profe = '';
            $cargo = '';
            $modalidad = '';
            
            if (count($dataFU) > 0) {
                getDatosFU($dataFU);
            }
            $listaAlumCursos[] = [
                'Item' => $acu,                                             
                'Anio' => html_utf8($mdl_Anio),     
                'IdCurso' => html_utf8($idCurso),                           
                'CursoNomLargo' => html_utf8(trim($xNombreCurso)),          
                'CursoNomCorto' => html_utf8(trim($xNombreCortaCurso)),     
                'CursoFechaIni' => html_utf8(trim($xPreMatCurs_FechaIni)),
                'CursoFechaFin' => html_utf8(trim($xPreMatCurs_FechaFin)),
                'CursoIdCategoria' => html_utf8(trim($xPreMatCurs_IdCategoria)),
                'CursoHoraLect' => html_utf8(trim($xPreMatCurs_HoraLect)),
                'CursoModalidad' => html_utf8(trim($xPreMatCurs_Modalidad)),
                'CursoNumEvent' => html_utf8(trim($xPreMatCurs_NumEvent)),
                'CursoCodPOI' => html_utf8(trim($xPreMatCurs_CodPOI)),

                'ApePatAlumno' => html_utf8(trim($dataUSER[0]['lastname'])),     
                'ApeMatAlumno' => html_utf8(trim($apellidoMater)),                
                'NombresAlumno' => html_utf8(trim($dataUSER[0]['firstname'])),     
                'DNIAlumno' => html_utf8(trim($dataUSER[0]['username'])),          
                'EntidadAlumno' => html_utf8(trim($dataUSER[0]['institution'])),  
                'NivelGobAlumno' => html_utf8(trim($nivelGobierno)),            
                'RubroAlumno' => html_utf8(trim($rubro)),                          
                'DepaAlumno' => html_utf8(trim($dataUSER[0]['department'])),       
                'ProvAlumno' => html_utf8(trim($provinLaboral)),                   
                'DistAlumno' => html_utf8(trim($distriLaboral)),        
                'GradoAcademicoAlumno' => html_utf8(trim($gradoAcademic)),  
                'ProfesionAlumno' => html_utf8(trim($profe)), 
                'CargoAlumno' => html_utf8(trim($cargo)),  
                'TipoContratoAlumno' => html_utf8(trim($modalidad)), 
                'AreaLaboraAlumno' => html_utf8(trim($dataUSER[0]['department'])),
                'mdl_user_id' => html_utf8(trim($curso['user_id']))
            ];
        }

        if (empty($dataCURS)) {
            echo false;
            exit;
        }
        $_SESSION['certificacion_curso_alumn_notas'] = $listaAlumCursos;
        echo true;
        break;

    
    case 'Registrar_Datos_Certificacion':
       
        $new_ItemsANIO = $_POST['new_ItemsANIO'];
        $new_ItemsIDCURSO = $_POST['new_ItemsIDCURSO'];
        $new_ItemsNOMLARGOCURSO = $_POST['new_ItemsNOMLARGOCURSO'];
        $new_ItemsNOMCORTOCURSO = $_POST['new_ItemsNOMCORTOCURSO'];
        $new_ItemsFECHAINI = $_POST['new_ItemsFECHAINI'];
        $new_ItemsFECHAFIN = $_POST['new_ItemsFECHAFIN'];
        $new_ItemsHORALECTIVA = $_POST['new_ItemsHORALECTIVA'];
        $new_ItemsMODALIDAD = $_POST['new_ItemsMODALIDAD'];
        $new_ItemsNUMEVENTO = $_POST['new_ItemsNUMEVENTO'];
        $new_ItemsCODPOI = $_POST['new_ItemsCODPOI'];
        $new_ItemsAPEPAT = $_POST['new_ItemsAPEPAT'];
        $new_ItemsAPEMAT = $_POST['new_ItemsAPEMAT'];
        $new_ItemsNOMBRES = $_POST['new_ItemsNOMBRES'];
        $new_ItemsDNI = $_POST['new_ItemsDNI'];
        $new_ItemsENTIDAD = $_POST['new_ItemsENTIDAD'];
        $new_ItemsNIVELGOBIERNO = $_POST['new_ItemsNIVELGOBIERNO'];
        $new_ItemsRUBRO = $_POST['new_ItemsRUBRO'];
        $new_ItemsDEPA = $_POST['new_ItemsDEPA'];
        $new_ItemsPROV = $_POST['new_ItemsPROV'];
        $new_ItemsDIST = $_POST['new_ItemsDIST'];
        $new_ItemsGRADOACADEMICO = $_POST['new_ItemsGRADOACADEMICO'];
        $new_ItemsPROFESION = $_POST['new_ItemsPROFESION'];
        $new_ItemsCARGO = $_POST['new_ItemsCARGO'];
        $new_ItemsTIPOCONTRATO = $_POST['new_ItemsTIPOCONTRATO'];
        $new_ItemsAREALABORA = $_POST['new_ItemsAREALABORA'];
        $new_ItemsCONCERTIFICADO = $_POST['new_ItemsCONCERTIFICADO'];
        $new_ItemsNROCERTIFICADO = $_POST['new_ItemsNROCERTIFICADO'];

        $new_ItemsNOTAPRACTICA = $_POST['new_ItemsNOTAPRACTICA'];
        $new_ItemsNOTAFORO = $_POST['new_ItemsNOTAFORO'];
        $new_ItemsEXAMFINAL = $_POST['new_ItemsEXAMFINAL'];
        $new_ItemsPROMFINAL = $_POST['new_ItemsPROMFINAL'];
        $new_ItemsMDLUSERID = $_POST['new_ItemsMDLUSERID'];
        

        $mdl_IdCurso = $_POST['mdl_IdCurso'];
        $mdl_Anio = $_POST['mdl_Anio'];        

        $Data_ItemsANIO    = isset( $new_ItemsANIO ) ? explode(',',$new_ItemsANIO) : '';
        $Data_ItemsIDCURSO    = isset( $new_ItemsIDCURSO ) ? explode(',',$new_ItemsIDCURSO) : '';
        $Data_ItemsNOMLARGOCURSO    = isset( $new_ItemsNOMLARGOCURSO ) ? explode(',',$new_ItemsNOMLARGOCURSO) : '';
        $Data_ItemsNOMCORTOCURSO    = isset( $new_ItemsNOMCORTOCURSO ) ? explode(',',$new_ItemsNOMCORTOCURSO) : '';
        $Data_ItemsFECHAINI    = isset( $new_ItemsFECHAINI ) ? explode(',',$new_ItemsFECHAINI) : '';
        $Data_ItemsFECHAFIN    = isset( $new_ItemsFECHAFIN ) ? explode(',',$new_ItemsFECHAFIN) : '';
        $Data_ItemsHORALECTIVA    = isset( $new_ItemsHORALECTIVA ) ? explode(',',$new_ItemsHORALECTIVA) : '';
        $Data_ItemsMODALIDAD    = isset( $new_ItemsMODALIDAD ) ? explode(',',$new_ItemsMODALIDAD) : '';
        $Data_ItemsNUMEVENTO    = isset( $new_ItemsNUMEVENTO ) ? explode(',',$new_ItemsNUMEVENTO) : '';
        $Data_ItemsCODPOI    = isset( $new_ItemsCODPOI ) ? explode(',',$new_ItemsCODPOI) : '';
        $Data_ItemsAPEPAT    = isset( $new_ItemsAPEPAT ) ? explode(',',$new_ItemsAPEPAT) : '';
        $Data_ItemsAPEMAT    = isset( $new_ItemsAPEMAT ) ? explode(',',$new_ItemsAPEMAT) : '';
        $Data_ItemsNOMBRES    = isset( $new_ItemsNOMBRES ) ? explode(',',$new_ItemsNOMBRES) : '';
        $Data_ItemsDNI    = isset( $new_ItemsDNI ) ? explode(',',$new_ItemsDNI) : '';
        $Data_ItemsENTIDAD    = isset( $new_ItemsENTIDAD ) ? explode(',',$new_ItemsENTIDAD) : '';
        $Data_ItemsNIVELGOBIERNO    = isset( $new_ItemsNIVELGOBIERNO ) ? explode(',',$new_ItemsNIVELGOBIERNO) : '';
        $Data_ItemsRUBRO    = isset( $new_ItemsRUBRO ) ? explode(',',$new_ItemsRUBRO) : '';
        $Data_ItemsDEPA    = isset( $new_ItemsDEPA ) ? explode(',',$new_ItemsDEPA) : '';
        $Data_ItemsPROV    = isset( $new_ItemsPROV ) ? explode(',',$new_ItemsPROV) : '';
        $Data_ItemsDIST    = isset( $new_ItemsDIST ) ? explode(',',$new_ItemsDIST) : '';
        $Data_ItemsGRADOACADEMICO    = isset( $new_ItemsGRADOACADEMICO ) ? explode(',',$new_ItemsGRADOACADEMICO) : '';
        $Data_ItemsPROFESION   = isset( $new_ItemsPROFESION ) ? explode(',',$new_ItemsPROFESION) : '';
        $Data_ItemsCARGO   = isset( $new_ItemsCARGO ) ? explode(',',$new_ItemsCARGO) : '';
        $Data_ItemsTIPOCONTRATO   = isset( $new_ItemsTIPOCONTRATO ) ? explode(',',$new_ItemsTIPOCONTRATO) : '';
        $Data_ItemsAREALABORA   = isset( $new_ItemsAREALABORA ) ? explode(',',$new_ItemsAREALABORA) : '';
        $Data_ItemsCONCERTIFICADO   = isset( $new_ItemsCONCERTIFICADO ) ? explode(',',$new_ItemsCONCERTIFICADO) : '';
        $Data_ItemsNROCERTIFICADO   = isset( $new_ItemsNROCERTIFICADO ) ? explode(',',$new_ItemsNROCERTIFICADO) : '';

        $Data_ItemsNOTAPRACTICA   = isset( $new_ItemsNOTAPRACTICA ) ? explode(',',$new_ItemsNOTAPRACTICA) : '';
        $Data_ItemsNOTAFORO   = isset( $new_ItemsNOTAFORO ) ? explode(',',$new_ItemsNOTAFORO) : '';
        $Data_ItemsEXAMFINAL   = isset( $new_ItemsEXAMFINAL ) ? explode(',',$new_ItemsEXAMFINAL) : '';
        $Data_ItemsPROMFINAL   = isset( $new_ItemsPROMFINAL ) ? explode(',',$new_ItemsPROMFINAL) : '';
        $Data_ItemsMDLUSERID   = isset( $new_ItemsMDLUSERID ) ? explode(',',$new_ItemsMDLUSERID) : '';

        $cpremat = new matricula_Model;

        for ( $i = 0; $i < count($Data_ItemsANIO); $i++){

            $arrayCampos   = [
                0=>'2',
                1=>$Data_ItemsANIO[$i],
                2=>$Data_ItemsIDCURSO[$i],
                3=>$Data_ItemsNOMLARGOCURSO[$i],
                4=>$Data_ItemsNOMCORTOCURSO[$i],
                5=>$Data_ItemsFECHAINI[$i],
                6=>$Data_ItemsFECHAFIN[$i],
                7=>$Data_ItemsHORALECTIVA[$i],
                8=>$Data_ItemsMODALIDAD[$i],
                9=>$Data_ItemsNUMEVENTO[$i],
                10=>$Data_ItemsCODPOI[$i],
                11=>$Data_ItemsAPEPAT[$i],
                12=>$Data_ItemsAPEMAT[$i],
                13=>$Data_ItemsNOMBRES[$i],
                14=>$Data_ItemsDNI[$i],
                15=>$Data_ItemsENTIDAD[$i],
                16=>$Data_ItemsNIVELGOBIERNO[$i],
                17=>$Data_ItemsRUBRO[$i],
                18=>$Data_ItemsDEPA[$i],
                19=>$Data_ItemsPROV[$i],
                20=>$Data_ItemsDIST[$i],
                21=>$Data_ItemsGRADOACADEMICO[$i],
                22=>$Data_ItemsPROFESION[$i],
                23=>$Data_ItemsCARGO[$i],
                24=>$Data_ItemsTIPOCONTRATO[$i],
                25=>$Data_ItemsAREALABORA[$i],
                26=>$Data_ItemsCONCERTIFICADO[$i],
                27=>$Data_ItemsNROCERTIFICADO[$i],
                28=>($Data_ItemsNOTAPRACTICA[$i] == '') ? '0' : $Data_ItemsNOTAPRACTICA[$i],
                29=>($Data_ItemsNOTAFORO[$i] == '') ? '0' : $Data_ItemsNOTAFORO[$i],
                30=>($Data_ItemsEXAMFINAL[$i] == '') ? '0' : $Data_ItemsEXAMFINAL[$i],
                31=>($Data_ItemsPROMFINAL[$i] == '') ? '0' : $Data_ItemsPROMFINAL[$i],
                32=>($Data_ItemsMDLUSERID[$i] == '') ? '0' : $Data_ItemsMDLUSERID[$i],
                33=>'1'
            ];    

            $rpta = $cpremat->Insert_DataCertificados($arrayCampos);
        }


        $objetoTotal = $cpremat->TotReg_DataCertificados($mdl_Anio, $mdl_IdCurso);
        $totalRegistro = $objetoTotal[0]['TOTREG'];

        if($totalRegistro>0){
            echo true;
        }else{
            echo false;
        }

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
