<?php
require_once dirname(__FILE__, 3) . "/app/db/Conexion.php";

class reporte_Model
{
    public function __construct()
    {
        $this->db = new Conexion;
    }

    public function get_Cursos()
    {
        $sql = 'CALL `sp_get_Cursos`';
        return $this->db->query($sql);
    }

    /* public function listado_reporte_01($id)
    {
        $sql = "CALL `sp_sel_ReporCursos01`(?)";
        $param = array($id);
        return $this->db->query($sql, $param);
    }

    public function get_Cursos_by_id($id)
    {
        $sql = "CALL `sp_sel_cursosXid`(?)";
        $param = array($id);
        return $this->db->query($sql, $param);
    }

    public function listado_reporte_02($id)
    {
        $sql = "CALL `sp_sel_ReporCursos02`(?)";
        $param = array($id);
        return $this->db->query($sql, $param);
    } */

    public function migracion_Matricula($fieldNames, $paramCount, $paramValue)
    {
        $sql = "INSERT INTO `tbl_matricula` ($fieldNames) VALUES ($paramCount)";
        return $this->db->query($sql, $paramValue);
    }
}
