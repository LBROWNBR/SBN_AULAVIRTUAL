<?php
require_once dirname(__FILE__, 3) . "/app/db/Conexion.php";

class matricula_Model
{
    public function __construct()
    {
        $this->db = new Conexion;
    }

    //bartoloricsi@gmail.com
    //Listar Anio del Curso
    public function get_AniosCurso()
    {
        $sql = 'SELECT year(cur_FechaInicio) as anio FROM tbl_curso WHERE Estado = 1 GROUP BY year(cur_FechaInicio)';
        return $this->db->query($sql);
    }

    //bartoloricsi@gmail.com
    //Listar Cursos x Anio
    public function get_CursosByAnio($anio)
    {
        $sql = 'SELECT * FROM tbl_curso WHERE Estado = 1 AND year(cur_FechaInicio) = ? ';
        return $this->db->query($sql, array($anio));
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
}
