use dbmatricula;

--DROP PROCEDURE IF EXISTS sp_sel_Cursos_estado_v2;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_sel_Cursos_estado_v2`()
BEGIN
select cur_ID,cur_Nombre  , DATE_FORMAT(cur_FechaInicio, '%d/%m/%Y') ,
DATE_FORMAT(cur_FechaFin, '%d/%m/%Y') ,cur_Lugar  ,
cur_PersonaDirigida  ,
cur_NombreImagen ,
cur_RutaImagen,
cur_Estado,
case when cur_Estado = 1 then 'ACTIVO' else 'INACTIVO' end as cur_Desc_Estado
from tbl_curso where cur_Estado = '1' 
order by cur_FechaInicio asc;
END ;;
DELIMITER ;


--DROP PROCEDURE IF EXISTS sp_sel_Cursos_estado_admin;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_sel_Cursos_estado_admin`()
BEGIN
select cur_ID,cur_Nombre  , DATE_FORMAT(cur_FechaInicio, '%d/%m/%Y') ,
DATE_FORMAT(cur_FechaFin, '%d/%m/%Y') ,cur_Lugar  ,
cur_PersonaDirigida  ,
cur_NombreImagen ,
cur_RutaImagen,
cur_Estado,
case when cur_Estado = 1 then 'ACTIVO' else 'INACTIVO' end as cur_Desc_Estado
from tbl_curso where cur_Estado in ('1', '0') 
order by cur_FechaInicio asc;
END ;;
DELIMITER ;


--DROP PROCEDURE IF EXISTS sp_sel_Cursos_estado_carrusel;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_sel_Cursos_estado_carrusel`()
BEGIN
select cur_ID,cur_Nombre  , DATE_FORMAT(cur_FechaInicio, '%d/%m/%Y') ,
DATE_FORMAT(cur_FechaFin, '%d/%m/%Y') ,cur_Lugar  ,
cur_PersonaDirigida  ,
cur_NombreImagen ,
cur_RutaImagen,
cur_Estado,
case when cur_Estado = 1 then 'ACTIVO' else 'INACTIVO' end as cur_Desc_Estado
from tbl_curso where cur_Estado = '1' and cur_Publicar = 'SI'
order by cur_FechaInicio asc;
END ;;
DELIMITER ;




--DROP PROCEDURE IF EXISTS sp_get_matricula_v2;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_matricula_v2`(
`p_estado_proc` VARCHAR(1),
`p_cur_ID` int(11)
)
BEGIN
	SELECT mat.Mat_ID,Mat_Nombre, mat.Mat_Paterno, mat.Mat_Materno, 
	 mat.Mat_DNI, mat.Mat_Profesion, mat.Mat_Genero, mat.Mat_Grado, mat.Mat_Correo, mat.Mat_Celular, mat.Mat_RUC,
	 mat.Mat_Entidad, SUBSTRING(mat.ubi_codigo,-6,2) AS Mat_Departamento, SUBSTRING(mat.ubi_codigo,-4,2) AS Mat_Provincia, 
	SUBSTRING(mat.ubi_codigo,-2,2) AS Mat_Distrito, mat.Mat_NivelGobierno, mat.Mat_Area, 
	 mat.Mat_Cargo, Mat_Clasificacion, mat.Mat_ModoContrato, mat.Mat_ContracOtros, mat.Mat_Oficio, 
	 mat.Mat_NombreArchivo, mat.Mat_RutaArchivo,mat.Mat_Estado,mat.Mat_Estado_proc,cur.cur_Nombre,mat.Mat_AreaOpcional,mat.Mat_TipoDocIdent
	 FROM tbl_matricula AS mat 
	 INNER JOIN tbl_curso AS cur ON mat.cur_ID =cur.cur_ID 
	 WHERE mat.Mat_Estado_proc = p_estado_proc
	 AND mat.Estado = '1' 
	 AND mat.cur_ID = p_cur_ID
	 ORDER BY mat.Mat_Nombre;
    END ;;
DELIMITER ;



