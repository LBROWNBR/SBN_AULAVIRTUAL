<?php
require_once dirname(__FILE__, 3) . "/app/db/Conexion.php";
require_once dirname(__FILE__, 3) . "/app/config.php";

class reporte_moodle_Model
{

    public function __construct()
    {
        $this->db = new Conexion(DB_NAME_MOODLE);
    }

    public function getUsuariosInscritos()
    {
        $sql = "SELECT 
        U.`id` 
        FROM mdl_user U 
        INNER JOIN mdl_role_assignments RA ON RA.`userid` = U.`id` 
        INNER JOIN mdl_role R ON R.`id` = RA.`roleid` 
        WHERE R.`id` = '5' GROUP BY U.`id`";
        return $this->db->query($sql);
    }

    public function getUsuario($idUser)
    {
        $sql = "SELECT * FROM `mdl_user` WHERE `id` = ?";
        $param = array($idUser);
        return $this->db->query($sql, $param);
    }

    public function getUsuarioDataField($idUser)
    {
        $sql = "SELECT 
        U.`id`,
        UIF.`shortname`,
        UID.`data`
        FROM `mdl_user_info_data` UID
        INNER JOIN `mdl_user_info_field` UIF ON UIF.`id` = UID.`fieldid`
        INNER JOIN `mdl_user` U ON U.`id` = UID.`userid` WHERE U.`id` = ?";
        $param = array($idUser);
        return $this->db->query($sql, $param);
    }

    public function getAniosCurso()
    {
        $sql = "SELECT FROM_UNIXTIME(C.`startdate`, '%Y') AS 'course_anio' FROM mdl_course C GROUP BY FROM_UNIXTIME(C.`startdate`, '%Y') ORDER BY 1 DESC";
        return $this->db->query($sql);
    }

    public function getCourseByAnio($anio)
    {
        $sql = "SELECT * FROM mdl_course WHERE FROM_UNIXTIME(`startdate`, '%Y') = ?";
        $param = array($anio);
        return $this->db->query($sql, $param);
    }
    
    public function getCourse()
    {
        $sql = "SELECT * FROM mdl_course";
        return $this->db->query($sql);
    }

    public function getCategoriasCursos()
    {
        $sql = 'SELECT `id`,`name` FROM `mdl_course_categories`';
        return (object) $this->db->query($sql);
    }

    public function getCursosWithUsuariosInscritos($idCurso)
    {
        $sql = "SELECT
        U.`id` AS 'user_id',
        C.`id` AS 'course_id',
        C.`fullname` AS 'course_name',
        C.`shortname` AS 'short_name',
        FROM_UNIXTIME(C.`startdate`, '%d/%m/%Y') AS 'course_ini',
        FROM_UNIXTIME(C.`enddate`, '%d/%m/%Y') AS 'course_fin',
        U.`institution` AS 'user_entidad'
        FROM mdl_user U
        INNER JOIN mdl_role_assignments RA ON RA.`userid` = U.`id`
        INNER JOIN mdl_role R ON R.`id` = RA.`roleid`
        INNER JOIN mdl_user_enrolments UE ON UE.`userid` = U.`id`
        INNER JOIN mdl_enrol ER ON ER.`id` = UE.`enrolid`
        INNER JOIN mdl_course C ON C.`id` = ER.`courseid`
        WHERE R.`id` = '5' AND C.`id` = ? GROUP BY U.`id`, C.`id`";
        $param = array($idCurso);
        return $this->db->query($sql, $param);
    }

    public function getUsuariosWithNota($idCurso)
    {
        $sql = "SELECT
        U.`id` AS 'user_id'
        FROM mdl_course C
        INNER JOIN mdl_grade_items GI ON GI.`courseid` = C.`id`
        INNER JOIN mdl_grade_grades GG ON GG.`itemid` = GI.`id`
        INNER JOIN mdl_user U ON U.`id` = GG.`userid`
        INNER JOIN mdl_role_assignments RA ON RA.`userid` = U.`id`
        INNER JOIN mdl_role R ON R.`id` = RA.`roleid`
        WHERE R.`id` = '5' AND C.`id` = ?
        GROUP BY U.`id` ";
        $param = array($idCurso);
        return $this->db->query($sql, $param);
    }

    public function getPruebasByCursos($idCurso)
    {
        $sql = "SELECT
        GI.`id` AS 'prueba_id',
        GI.`itemname` AS 'prueba_nombre',
        FROM_UNIXTIME(GI.`timecreated`, '%d/%m/%Y') AS 'prueba_fecha'
        FROM mdl_course C
        INNER JOIN mdl_grade_items GI ON GI.`courseid` = C.`id`
        WHERE GI.`itemtype` = 'mod' AND GI.`itemmodule` =  'quiz' AND  C.`id` = ?";
        $param = array($idCurso);
        return $this->db->query($sql, $param);
    }

    public function getForosPruebasByCursos($idCurso)
    {
        $sql = "SELECT
        GI.`id` AS 'prueba_id',
        GI.`itemname` AS 'prueba_nombre',
        FROM_UNIXTIME(GI.`timecreated`, '%d/%m/%Y') AS 'prueba_fecha'
        FROM mdl_course C
        INNER JOIN mdl_grade_items GI ON GI.`courseid` = C.`id`
        WHERE GI.`itemtype` = 'mod' AND GI.`itemmodule` in ('forum','quiz') AND  C.`id` = ?";
        $param = array($idCurso);
        return $this->db->query($sql, $param);
    }

    public function getNotasByPruebas($idUser, $idPrueba)
    {
        $sql = "SELECT 
        U.`id` AS 'user_id',
        GG.`finalgrade` AS 'prueba_nota'
        FROM mdl_user U
        INNER JOIN `mdl_grade_grades` GG ON GG.`userid` = U.`id`
        WHERE U.`id` = ? AND  GG.`itemid` = ?";
        $param = array($idUser, $idPrueba);
        return $this->db->query($sql, $param);
    }

    public function getNotaPromFinal($userid, $courseid)
    {
        $sql = "SELECT 
        ROUND(IFNULL(notas.finalgrade,0),0) AS PROM_FINAL
        FROM mdl_grade_grades notas
        INNER JOIN mdl_grade_items ACT ON (ACT.id = notas.itemid)
        WHERE ACT.itemtype = 'course'
        AND  userid = ? 
        AND ACT.courseid = ?";
        $param = array($userid, $courseid);
        return $this->db->query($sql, $param);
    }

    public function getCursosWithInscritos($tipoFiltro, $fechaIni, $fechaFin)
    {
        $tipoFiltro = ($tipoFiltro == 'Fecha de inicio') ? 'startdate' : 'enddate';
        $sql = "SELECT
        C.`id` AS 'course_id',
        C.`shortname` AS 'course_cod',
        C.`fullname` AS 'course_name',
        FROM_UNIXTIME(C.`startdate`, '%d/%m/%Y') AS 'course_ini',
        FROM_UNIXTIME(C.`enddate`, '%d/%m/%Y') AS 'course_fin'
        FROM mdl_user U
        INNER JOIN mdl_role_assignments RA ON RA.`userid` = U.`id`
        INNER JOIN mdl_role R ON R.`id` = RA.`roleid`
        INNER JOIN mdl_user_enrolments UE ON UE.`userid` = U.`id`
        INNER JOIN mdl_enrol ER ON ER.`id` = UE.`enrolid`
        INNER JOIN mdl_course C ON C.`id` = ER.`courseid`
        WHERE R.`id` = '5' AND FROM_UNIXTIME(C.`{$tipoFiltro}`, '%Y-%m-%d') BETWEEN ? AND ?  GROUP BY C.`id`";
        $param = array($fechaIni, $fechaFin);
        return $this->db->query($sql, $param);
    }

    public function getCodigoGenerateCertificate($idUser, $idCurso)
    {
        $sql = "SELECT 
        CTI.`userid`,
        C.`id`,
        CTI.`code`,
        FROM_UNIXTIME(CTI.`timecreated`, '%d/%m/%Y') AS 'fecha_certi'
        FROM `mdl_course` C 
        INNER JOIN `mdl_customcert` CT ON CT.`course` = C.`id`
        INNER JOIN `mdl_customcert_issues` CTI ON CTI.`customcertid` = CT.`id`
        WHERE CTI.`userid` = ? AND C.`id` = ?";
        $param = array($idUser, $idCurso);
        return $this->db->query($sql, $param);
    }


    //=====================================================================

    public function getCourse_By_ID($idCurso)
    {
        $sql = "SELECT C.* FROM mdl_course C WHERE C.`id` = ?";
        $param = array($idCurso);
        return $this->db->query($sql, $param);
    }

    public function getDetalladoCourse_By_ID($idCurso)
    {
        $sql = "
            SELECT 
            C.*, 
            FROM_UNIXTIME(C.`startdate`, '%Y') AS 'course_anio',
            FROM_UNIXTIME(C.`startdate`, '%d/%m/%Y') AS 'course_ini',
            FROM_UNIXTIME(C.`enddate`, '%d/%m/%Y') AS 'course_fin',
            CC.name as nom_categoria 
            FROM mdl_course C, mdl_course_categories CC
            WHERE CC.id = C.category
            AND C.`id` = ?
        ";
        $param = array($idCurso);
        return $this->db->query($sql, $param);
    }


    public function getActividades_By_CourseID($idCurso)
    {
        $sql = "
            SELECT 
            id, 
            courseid, 
            categoryid, 
            IFNULL(itemname,'') as itemname, 
            itemtype, 
            itemmodule, 
            calculation,
            (CASE WHEN IFNULL(itemtype,'') = 'course' THEN 'PROM. FINAL' ELSE IFNULL(itemname,'') END) AS NOMACTIVIDAD
            FROM mdl_grade_items 
            WHERE courseid = ?
        ";
        $param = array($idCurso);
        return $this->db->query($sql, $param);
    }



    public function getNotasCourse_By_CourseID_UserId($idUser, $idCurso)
    {
        $sql = "
            SELECT 
            ACT.id,
            (CASE WHEN ACT.itemtype = 'course' THEN 'PROM. FINAL' ELSE ACT.itemname END) AS ACTIVIDAD,
            ROUND(IFNULL(notas.finalgrade,0),0) AS NOTA 
            FROM mdl_grade_grades notas
            INNER JOIN mdl_grade_items ACT ON (ACT.id = notas.itemid)
            WHERE userid = ? 
            AND ACT.courseid = ?
            order by ACT.id
        ";
        $param = array($idUser, $idCurso);
        return $this->db->query($sql, $param);
    }

}
