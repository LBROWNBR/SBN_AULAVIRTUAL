<?php
require_once dirname(__FILE__, 3) . "/app/db/Conexion.php";

class profesor_model
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

    public function get_Cursos()
    {
        $sql = 'CALL `sp_get_Cursos`()';
        return $this->db->query($sql);
    }

    public function create($values)
    {
        $sql = 'CALL `sp_ins_profesor`(?,?,?,?,?,?,?,?,?, @message, @idMatrProc)';
        // $sql = "CALL `sp_ins_profesor`('74200128','Luis Javier','Rojas','Blanco','rs.tksk.2015@gmail.com','986382450','2', @message)";
        $this->db->query($sql, $values);
        return $this->db->query('SELECT @message, @idMatrProc');
    }

    public function update($values)
    {
        $sql = 'CALL `sp_Upd_Profesor`(?,?,?,?,?,?,?, @message)';
        $this->db->query($sql, $values);
        return $this->db->query('SELECT @message');
    }

    public function get_MatriculaProc($id)
    {
        $sql = 'CALL `sp_sel_matriculaprocXidproc`(?)';
        return $this->db->query($sql, array($id));
    }

    public function get_Profesores($idCurso)
    {
        $sql = 'CALL `sp_sel_ProfesorXidcurso`(?)';
        return $this->db->query($sql, array($idCurso));
    }

    public function eliminar_Profesor($idProf)
    {
        $sql = 'CALL `sp_del_profesor`(?)';
        return $this->db->query($sql, array($idProf));
    }

    public function get_Profesor($tipoDoc, $numeDoc)
    {
        $sql = 'CALL `sp_sel_profesorXdniYcde`(?,?)';
        return $this->db->query($sql, array($tipoDoc, $numeDoc));
    }
}
