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
        $rm = new reporte_moodle_Model;
        $cpremat = new matricula_Model;

        $dataCertificados = $cpremat->Listar_DataCertificados($mdl_Anio, $mdl_IdCurso);   
        $dataActividad = $rm->getActividades_By_CourseID($mdl_IdCurso); 

        $listaAlumCertificados = [];
        foreach ($dataCertificados as $AlumCertificados) {

            $NotasAlumno = $rm->getNotasCourse_By_CourseID_UserId($AlumCertificados['mdl_user_id'], $mdl_IdCurso);

            $listaAlumCertificados[] = [
                'CertID' => html_utf8(trim($AlumCertificados['CertID'])),
                'anio' => html_utf8(trim($AlumCertificados['anio'])),
                'mdl_cur_id' => html_utf8(trim($AlumCertificados['mdl_cur_id'])),
                'mdl_cur_fullname' => html_utf8(trim($AlumCertificados['mdl_cur_fullname'])),
                'mdl_cur_shortname' => html_utf8(trim($AlumCertificados['mdl_cur_shortname'])),
                'mdl_category_id' => html_utf8(trim($AlumCertificados['mdl_category_id'])),
                'mdl_nom_categoria' => html_utf8(trim($AlumCertificados['mdl_nom_categoria'])),
                'mat_fechaini' => html_utf8(trim($AlumCertificados['mat_fechaini'])),
                'mat_fechafin' => html_utf8(trim($AlumCertificados['mat_fechafin'])),
                'mat_horalectiva' => html_utf8(trim($AlumCertificados['mat_horalectiva'])),
                'mat_modalidad' => html_utf8(trim($AlumCertificados['mat_modalidad'])),
                'mat_nroevento' => html_utf8(trim($AlumCertificados['mat_nroevento'])),
                'mat_codpoi' => html_utf8(trim($AlumCertificados['mat_codpoi'])),
                'mdl_user_id' => html_utf8(trim($AlumCertificados['mdl_user_id'])),
                'mdl_apepat' => html_utf8(trim($AlumCertificados['mdl_apepat'])),
                'mdl_apemat' => html_utf8(trim($AlumCertificados['mdl_apemat'])),
                'mdl_nombres' => html_utf8(trim($AlumCertificados['mdl_nombres'])),
                'mdl_dni' => html_utf8(trim($AlumCertificados['mdl_dni'])),
                'mdl_entidad' => html_utf8(trim($AlumCertificados['mdl_entidad'])),
                'mdl_nivelgrado' => html_utf8(trim($AlumCertificados['mdl_nivelgrado'])),
                'mdl_rubro' => html_utf8(trim($AlumCertificados['mdl_rubro'])),
                'mdl_depa_lab' => html_utf8(trim($AlumCertificados['mdl_depa_lab'])),
                'mdl_prov_lab' => html_utf8(trim($AlumCertificados['mdl_prov_lab'])),
                'mdl_dist_lab' => html_utf8(trim($AlumCertificados['mdl_dist_lab'])),
                'mdl_gradoacademico' => html_utf8(trim($AlumCertificados['mdl_gradoacademico'])),
                'mdl_profesion' => html_utf8(trim($AlumCertificados['mdl_profesion'])),
                'mdl_cargo' => html_utf8(trim($AlumCertificados['mdl_cargo'])),
                'mdl_tipocontrato' => html_utf8(trim($AlumCertificados['mdl_tipocontrato'])),
                'mdl_area_lab' => html_utf8(trim($AlumCertificados['mdl_area_lab'])),
                'ConCertificado' => html_utf8(trim($AlumCertificados['ConCertificado'])),
                'NroCertificado' => html_utf8(trim($AlumCertificados['NroCertificado'])),
                'id_estado' => html_utf8(trim($AlumCertificados['id_estado'])),
                'mdl_NOTAS' => $NotasAlumno
            ];
        }        

        $_SESSION['Lista_certificacion_BY_Curso_Anio'] = $listaAlumCertificados;
        $_SESSION['Lista_Actividades_BY_Curso'] = $dataActividad;
        echo true;
        
        break;

    case 'Extraer_Lista_Alumnos_Moodle_Detallado':
       
        $mdl_Anio = $_POST['mdl_Anio'];
        $idCurso = $_POST['mdl_IdCurso'];

        session_start();
        $rm = new reporte_moodle_Model;
        $cpremat = new matricula_Model;

        $objCurseMoodle = $rm->getDetalladoCourse_By_ID($idCurso)[0];
        $xNombreCortaCurso = count($objCurseMoodle) > 0 ? $objCurseMoodle['shortname'] : null;

        $objCursePreMatricula = $cpremat->get_CursosByShortname($xNombreCortaCurso)[0];

        $dataCURS = $rm->getCursosWithUsuariosInscritos($idCurso);
        $dataActividad = $rm->getActividades_By_CourseID($idCurso);

        $acu = 0;
        $listaAlumCursos = [];
        foreach ($dataCURS as $curso) {
            
            $dataUSER = $rm->getUsuario($curso['user_id']);
            $dataFU = $rm->getUsuarioDataField($curso['user_id']);
           
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

            $nomCursoCorta = $objCursePreMatricula['course_shortname'];
            $dniAlumno = html_utf8(trim($dataUSER[0]['username']));

            $arrayparametros   = [
                0=>$nomCursoCorta,
                1=>$dniAlumno
            ]; 
            $DatosAlumnoMatriculado = $cpremat->Ver_Datos_Matricula_By_Curso_Alumno($arrayparametros);

            $AlumnoApePat = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_Paterno'] : 'VACIO';
            $AlumnoApeMat = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_Materno'] : 'VACIO';
            $AlumnoNombres = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_Nombre'] : 'VACIO';
            $AlumnoNomEntidad = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_Entidad'] : 'VACIO';
            $AlumnoNivelGobierno = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_NivelGobierno'] : 'VACIO';
            $AlumnoClasificacion = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_Clasificacion'] : 'VACIO';
            $AlumnoDepartamento = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_Ciudad'] : 'VACIO';
            $AlumnoProvincia = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_Provincia'] : 'VACIO';
            $AlumnoDistrito = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_Distrito'] : 'VACIO';
            $AlumnoGradoAcadem = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_Grado'] : 'VACIO';
            $AlumnoProfesion = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_Profesion'] : 'VACIO';
            $AlumnoCargo = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_Cargo'] : 'VACIO';
            $AlumnoModoContrato = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_ModoContrato'] : 'VACIO';
            $AlumnoArea = ($DatosAlumnoMatriculado) ? $DatosAlumnoMatriculado[0]['Mat_Area'] : 'VACIO';

            $NotasAlumno = $rm->getNotasCourse_By_CourseID_UserId($curso['user_id'], $idCurso);
            

            if($AlumnoApePat != 'VACIO'){
                $acu++;
                $listaAlumCursos[] = [
                    'Item' => $acu,
                    //'ApePatAlumno' => html_utf8(trim($dataUSER[0]['lastname'])),     
                    //'NombresAlumno' => html_utf8(trim($dataUSER[0]['firstname'])),   
                    'ApePatAlumno' => html_utf8(trim($AlumnoApePat)),     
                    'ApeMatAlumno' => html_utf8(trim($AlumnoApeMat)),                 
                    'NombresAlumno' => html_utf8(trim($AlumnoNombres)),       
                    'DNIAlumno' => $dniAlumno,          
                    'EntidadAlumno' => $AlumnoNomEntidad,  //html_utf8(trim($dataUSER[0]['institution'])),  
                    'NivelGobAlumno' => $AlumnoNivelGobierno, //html_utf8(trim($nivelGobierno)),            
                    'RubroAlumno' => $AlumnoClasificacion, //html_utf8(trim($rubro)),                          
                    'DepaAlumno' => $AlumnoDepartamento, //html_utf8(trim($dataUSER[0]['department'])),       
                    'ProvAlumno' => $AlumnoProvincia, //html_utf8(trim($provinLaboral)),                   
                    'DistAlumno' => $AlumnoDistrito, //html_utf8(trim($distriLaboral)),        
                    'GradoAcademicoAlumno' => $AlumnoGradoAcadem, //html_utf8(trim($gradoAcademic)),  
                    'ProfesionAlumno' => $AlumnoProfesion, //html_utf8(trim($profe)), 
                    'CargoAlumno' => $AlumnoCargo, //html_utf8(trim($cargo)),  
                    'TipoContratoAlumno' => $AlumnoModoContrato, //html_utf8(trim($modalidad)), 
                    'AreaLaboraAlumno' => $AlumnoArea, //html_utf8(trim($dataUSER[0]['department'])),
                    'mdl_user_id' => html_utf8(trim($curso['user_id'])),
                    'mdl_NOTAS' => $NotasAlumno
                ];

            }
        }

        if (empty($dataCURS)) {
            echo false;
            exit;
        }

        $respuesta = (object)[
            'xCursoMoodle' => $objCurseMoodle,
            'xCursoPreMatricula' => $objCursePreMatricula,
            'xActividades' => $dataActividad,
            'xInscritos' => $listaAlumCursos
        ];

        $_SESSION['certificacion_curso_alumn_notas'] = $respuesta;
        echo true;
        break;

    
    case 'Registrar_Datos_Certificacion':
      
        $mdl_Anio = $_POST['mdl_Anio'];
        $mdl_IdCurso = $_POST['mdl_IdCurso'];
        $txtCursoDescLarga = $_POST['txtCursoDescLarga'];
        $txtCursoDescCorta = $_POST['txtCursoDescCorta'];
        $txtCodCategoria_Moodle = $_POST['txtCodCategoria_Moodle'];
        $txtDescripCategoria_Moodle = $_POST['txtDescripCategoria_Moodle'];
        $txtCursoFechaIni = $_POST['txtCursoFechaIni'];
        $txtCursoFechaFin = $_POST['txtCursoFechaFin'];
        $txtHoraLectiva = $_POST['txtHoraLectiva'];
        $txtModalidad = $_POST['txtModalidad'];
        $txtNroEvento = $_POST['txtNroEvento'];
        $txtCodigoPOI = $_POST['txtCodigoPOI'];
        $new_ItemsMDLUSERID = $_POST['new_ItemsMDLUSERID'];
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
        $xIdestado = '1';      

        $Data_ItemsMDLUSERID   = isset( $new_ItemsMDLUSERID ) ? explode(',',$new_ItemsMDLUSERID) : '';
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

        $cpremat = new matricula_Model;

        for ( $i = 0; $i < count($Data_ItemsMDLUSERID); $i++){

            $arrayCampos   = [
                0=>$mdl_Anio,
                1=>$mdl_IdCurso,
                2=>$txtCursoDescLarga,
                3=>$txtCursoDescCorta,
                4=>$txtCodCategoria_Moodle,
                5=>$txtDescripCategoria_Moodle,
                6=>$txtCursoFechaIni,
                7=>$txtCursoFechaFin,
                8=>$txtHoraLectiva,
                9=>$txtModalidad,
                10=>$txtNroEvento,
                11=>$txtCodigoPOI,
                12=>$Data_ItemsMDLUSERID[$i],
                13=>$Data_ItemsAPEPAT[$i],
                14=>$Data_ItemsAPEMAT[$i],
                15=>$Data_ItemsNOMBRES[$i],
                16=>$Data_ItemsDNI[$i],
                17=>$Data_ItemsENTIDAD[$i],
                18=>$Data_ItemsNIVELGOBIERNO[$i],
                19=>$Data_ItemsRUBRO[$i],
                20=>$Data_ItemsDEPA[$i],
                21=>$Data_ItemsPROV[$i],
                22=>$Data_ItemsDIST[$i],
                23=>$Data_ItemsGRADOACADEMICO[$i],
                24=>$Data_ItemsPROFESION[$i],
                25=>$Data_ItemsCARGO[$i],
                26=>$Data_ItemsTIPOCONTRATO[$i],
                27=>$Data_ItemsAREALABORA[$i],
                28=>$Data_ItemsCONCERTIFICADO[$i],
                29=>$Data_ItemsNROCERTIFICADO[$i],                
                30=>$xIdestado    
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

    
    case 'Limpiar_Registros_Certificacion':
      
        $xCodigoCursoMdle = $_POST['xCodigoCursoMdle'];
        $cpremat = new matricula_Model;
        $rpta = $cpremat->Limpiar_Registros_DataCertificados($xCodigoCursoMdle);
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
