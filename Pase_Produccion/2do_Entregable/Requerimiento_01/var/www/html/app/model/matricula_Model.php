<?php
require_once dirname(__FILE__, 3) . "/app/db/Conexion.php";

class matricula_Model
{
    public function __construct()
    {
        $this->db = new Conexion;
    }

    //Listar Anio del Curso
    public function get_AniosCurso()
    {
        $sql = 'SELECT year(cur_FechaInicio) as anio FROM tbl_curso WHERE Estado = 1 GROUP BY year(cur_FechaInicio)';
        return $this->db->query($sql);
    }

    //Listar Cursos x Anio
    public function get_CursosByAnio($anio)
    {
        $sql = 'SELECT * FROM tbl_curso WHERE Estado = 1 AND year(cur_FechaInicio) = ? ';
        return $this->db->query($sql, array($anio));
    }

    //Listar Cursos x Nombre
    public function get_CursosByNombreCurso($nombCurso)
    {
        $sql = 'SELECT * FROM tbl_curso WHERE trim(cur_Nombre) = ? ';
        return $this->db->query($sql, array($nombCurso));
    }

    //Listar Cursos x DescripcionCorta
    public function get_CursosByShortname($abrevNombCurso)
    {
        $sql = 'SELECT * FROM tbl_curso WHERE trim(course_shortname) = ? ';
        return $this->db->query($sql, array($abrevNombCurso));
    }

    public function get_Provincia($codDep)
    {
        $sql = 'CALL `sp_get_provincia`(?)';
        $param = array($codDep);
        return $this->db->query($sql, $param);
    }

    public function get_Matricula($dni)
    {
        $sql = 'CALL `sp_sel_matriculaXdni`(?)';
        $param = array($dni);
        return $this->db->query($sql, $param);
    }

    public function get_Matricula_SP($idCurso, $estadoProc)
    {
        $sql = 'CALL sp_get_matricula_participantes(?,?)';
        $param = array($idCurso, $estadoProc);
        return $this->db->query($sql, $param);
    }

    public function get_Curso($idCurso)
    {
        $sql = 'CALL `sp_sel_cursosXid`(?)';
        $param = array($idCurso);
        return $this->db->query($sql, $param);
    }

    public function get_MatriculaProc($campo, $valor)
    {
        // $sql = "SELECT Mat_ID_proc, user_username, Mat_Correo, Mat_Password FROM `tbl_matricula_proc` WHERE {$campo} = ? LIMIT 1";
        // $param = array($valor);
        $sql = 'CALL `sp_sel_matriculaprocXcampo`(?,?)';
        $param = array($campo, $valor);
        return $this->db->query($sql, $param);
    }

    public function restablecer_Pass($email, $pass)
    {
        $sql = "CALL `sp_upd_matriculaprocPass`(?,?)";
        $param = array($email, $pass);
        return $this->db->query($sql, $param);
    }


    //Insertar
    public function Insert_DataCertificados($arrayCampos = [])
    {  
        $sql = 'INSERT INTO tbl_certificados (Usu_ID, anio, mdl_cur_id, mdl_cur_fullname, mdl_cur_shortname, mat_fechaini, mat_fechafin, mat_horalectiva, mat_modalidad, mat_nroevento, mat_codpoi, mdl_apepat, mdl_apemat, mdl_nombres, mdl_dni, mdl_entidad, mdl_nivelgrado, mdl_rubro, mdl_depa_lab, mdl_prov_lab, mdl_dist_lab, mdl_gradoacademico, mdl_profesion, mdl_cargo, mdl_tipocontrato, mdl_area_lab, ConCertificado, NroCertificado, NotaPractica, NotaForo, ExamenFinal, PromedioFinal, id_estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        return $this->db->query($sql, $arrayCampos);
    }

    //Total de Registros
    public function TotReg_DataCertificados($anio, $idcurso)
    {  
        $sql = 'SELECT COUNT(*) AS TOTREG FROM tbl_certificados WHERE anio = ? and mdl_cur_id = ? ';
        $param = array($anio, $idcurso);
        return $this->db->query($sql, $param);
    }

    //Lista Registros Certificaciones x Anio y Idcurso
    public function Listar_DataCertificados($anio, $idcurso)
    {  
        $sql = 'SELECT * FROM tbl_certificados WHERE anio = ? and mdl_cur_id = ? ';
        $param = array($anio, $idcurso);
        return $this->db->query($sql, $param);
    }

    //Lista Registros Certificaciones x IdCurso
    public function Listar_DataCertificados_By_Idcurso($idcurso)
    {  
        $sql = 'SELECT * FROM tbl_certificados WHERE mdl_cur_id = ? ';
        $param = array($idcurso);
        return $this->db->query($sql, $param);
    }
    
}
