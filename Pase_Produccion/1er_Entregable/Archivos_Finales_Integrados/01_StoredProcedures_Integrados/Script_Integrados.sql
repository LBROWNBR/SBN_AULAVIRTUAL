

use dbmatricula;


alter table tbl_curso add cur_HoraLect varchar(10);
alter table tbl_curso add cur_Modalidad varchar(90);
alter table tbl_curso add cur_NumEvent varchar(90);
alter table tbl_curso add cur_CodPOI varchar(90);
alter table tbl_curso add cur_Publicar varchar(2);


-- DROP PROCEDURE IF EXISTS sp_ins_curso_V2;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ins_curso_V2`(
`p_cur_Nombre` VARCHAR(200), 
`p_course_shortname` VARCHAR(255), 
`p_cur_descripcion` LONGTEXT, 
`p_cur_FechaInicio` DATE, 
`p_cur_FechaFin` DATE,
`p_cur_HoraInicio` TIME, 
`p_cur_HoraFin` TIME, 
`p_cur_Lugar` VARCHAR(100), 
`p_cur_PersonaDirigida` VARCHAR(100), 
`p_cur_NombreImagen` VARCHAR(250), 
`p_cur_RutaImagen` VARCHAR(100), 
`p_cur_Estado` VARCHAR(1), 
`p_Usu_ID` INT(11), 
`p_Cate_ID` INT(11), 
`p_cur_HoraLect` VARCHAR(10), 
`p_cur_Modalidad` VARCHAR(90), 
`p_cur_NumEvent` VARCHAR(90), 
`p_cur_CodPOI` VARCHAR(90),
`p_cur_Publicar` VARCHAR(2),
 OUT `msg_salida` VARCHAR(500)
)
BEGIN
DECLARE V_CONTADOR INT ; 
DECLARE v_cur_FechaInicioEpoch INT(11);
DECLARE v_cur_FechaFinEpoch INT(11);
SELECT UNIX_TIMESTAMP(DATE_FORMAT(CONCAT(p_cur_FechaInicio,' ',p_cur_HoraInicio),'%y-%m-%d %h-%m-%s')),
UNIX_TIMESTAMP(DATE_FORMAT(CONCAT(p_cur_FechaFin,' ',p_cur_HoraFin),'%y-%m-%d %h-%m-%s'))
INTO v_cur_FechaInicioEpoch ,v_cur_FechaFinEpoch;
SELECT COUNT(*) INTO V_CONTADOR FROM tbl_curso WHERE course_shortname = p_course_shortname AND Estado = '1' ;
IF (V_CONTADOR = '0') THEN
INSERT INTO tbl_curso (
cur_Nombre  ,course_shortname,cur_descripcion,cur_FechaInicio ,
cur_FechaFin ,cur_HoraInicio ,
cur_HoraFin ,cur_Lugar  ,
cur_PersonaDirigida  ,
cur_NombreImagen ,
cur_RutaImagen,
cur_Estado,Usu_ID,cur_idnumber,
cur_FechaInicioEpoch,cur_FechaFinEpoch, cur_Categoria,
cur_HoraLect, cur_Modalidad, cur_NumEvent, cur_CodPOI, cur_Publicar
) VALUES (
p_cur_Nombre  , p_course_shortname,p_cur_descripcion, p_cur_FechaInicio,
p_cur_FechaFin ,p_cur_HoraInicio ,
p_cur_HoraFin ,p_cur_Lugar  ,
p_cur_PersonaDirigida  ,
p_cur_NombreImagen ,p_cur_RutaImagen,
p_cur_Estado,p_Usu_ID,p_course_shortname,
v_cur_FechaInicioEpoch,v_cur_FechaFinEpoch, p_Cate_ID,
p_cur_HoraLect, p_cur_Modalidad, p_cur_NumEvent, p_cur_CodPOI, p_cur_Publicar
);
SELECT 'Grabado con exito' INTO msg_salida;
ELSE
SELECT 'El nombre corto no se puede repetir' INTO msg_salida;
END IF;
END ;;
DELIMITER ;







-- DROP PROCEDURE IF EXISTS sp_upd_curso_V2;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_upd_curso_V2`(
IN `p_cur_ID` INT(11), 
IN `p_cur_Nombre` VARCHAR(200), 
IN `p_cur_descripcion` LONGTEXT, 
IN `p_cur_FechaInicio` DATE, 
IN `p_cur_FechaFin` DATE, 
IN `p_cur_HoraInicio` TIME, 
IN `p_cur_HoraFin` TIME, 
IN `p_cur_Lugar` VARCHAR(100), 
IN `p_cur_PersonaDirigida` VARCHAR(35), 
IN `p_cur_NombreImagen` VARCHAR(250), 
IN `p_cur_RutaImagen` VARCHAR(100), 
IN `p_cur_Estado` VARCHAR(1), 
IN `p_cur_HoraLect` VARCHAR(10), 
IN `p_cur_Modalidad` VARCHAR(90), 
IN `p_cur_NumEvent` VARCHAR(90), 
IN `p_cur_CodPOI` VARCHAR(90), 
IN `p_cur_Publicar` VARCHAR(2),
`p_Cate_ID` INT(11)
)
BEGIN
DECLARE v_cur_FechaInicioEpoch INT(11);
DECLARE v_cur_FechaFinEpoch INT(11);
SELECT UNIX_TIMESTAMP(DATE_FORMAT(CONCAT(p_cur_FechaInicio,' ',p_cur_HoraInicio),'%y-%m-%d %h-%m-%s')),
UNIX_TIMESTAMP(DATE_FORMAT(CONCAT(p_cur_FechaFin,' ',p_cur_HoraFin),'%y-%m-%d %h-%m-%s'))
INTO v_cur_FechaInicioEpoch ,v_cur_FechaFinEpoch;
UPDATE tbl_curso SET
cur_Nombre = p_cur_Nombre, 
cur_descripcion = p_cur_descripcion, 
cur_FechaInicio = p_cur_FechaInicio,
cur_FechaFin = p_cur_FechaFin,
cur_HoraInicio = p_cur_HoraInicio,
cur_HoraFin = p_cur_HoraFin,
cur_Lugar = p_cur_Lugar,
cur_PersonaDirigida = p_cur_PersonaDirigida ,
cur_NombreImagen = p_cur_NombreImagen ,
cur_RutaImagen = p_cur_RutaImagen,
cur_Estado = p_cur_Estado,
cur_FechaInicioEpoch = v_cur_FechaInicioEpoch,
cur_FechaFinEpoch = v_cur_FechaFinEpoch,

cur_HoraLect = p_cur_HoraLect,
cur_Modalidad = p_cur_Modalidad,
cur_NumEvent = p_cur_NumEvent,
cur_CodPOI = p_cur_CodPOI,
cur_Publicar = p_cur_Publicar,

cur_Categoria = p_Cate_ID
WHERE cur_ID = p_cur_ID;
END ;;
DELIMITER ;





-- DROP PROCEDURE IF EXISTS sp_get_Cursos_V2;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_Cursos_V2`()
BEGIN
SELECT cur_ID,cur_Nombre , DATE_FORMAT(cur_FechaInicio, '%d/%m/%Y') ,
DATE_FORMAT(cur_FechaFin, '%d/%m/%Y') ,DATE_FORMAT(cur_HoraInicio, '%H:%i') ,
DATE_FORMAT(cur_HoraFin, '%H:%i')  ,cur_Lugar  ,
cur_PersonaDirigida  ,
cur_Estado,
cur_NombreImagen ,
cur_RutaImagen,
Usu_ID,course_shortname,
cur_Categoria,
cur_descripcion,
cur_HoraLect, 
cur_Modalidad, 
cur_NumEvent, 
cur_CodPOI,
cur_Publicar
FROM tbl_curso WHERE Estado = '1' ;
END ;;
DELIMITER ;




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






--DROP PROCEDURE IF EXISTS sp_ins_matricula_v2;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ins_matricula_v2`(IN `p_cur_ID` INT(11), IN `p_Mat_Nombre` VARCHAR(50), IN `p_Mat_Paterno` VARCHAR(50), IN `p_Mat_Materno` VARCHAR(50), IN `p_Mat_TipoDocIdent` VARCHAR(40), IN `p_Mat_DNI` varchar(9), IN `p_Mat_Profesion` VARCHAR(50), IN `p_Mat_Genero` VARCHAR(1), IN `p_Mat_GradoAcade` VARCHAR(25), IN `p_Mat_Corroe` VARCHAR(100), IN `p_Mat_Celular` INT(9), IN `p_Mat_Ruc` VARCHAR(11), IN `p_Mat_Entidad` VARCHAR(100), IN `p_Mat_Departamento` VARCHAR(3), IN `p_Mat_Provincia` VARCHAR(3), IN `p_Mat_Distrito` VARCHAR(3), IN `p_Mat_NivGobierno` VARCHAR(25), IN `p_Mat_Area` VARCHAR(100), IN `p_Mat_AreaOpcional` VARCHAR(100), IN `p_Mat_Cargo` VARCHAR(100), IN `p_Mat_Clasificacion` VARCHAR(25), IN `p_Mat_ModContrac` VARCHAR(15), IN `p_Mat_ContracOtros` VARCHAR(20), IN `p_Mat_NroOficio` VARCHAR(100), IN `p_Mat_NomArchivo` VARCHAR(200), IN `p_Mat_RutaArchivo` VARCHAR(50), OUT `msg_salida` VARCHAR(500))
BEGIN
        DECLARE V_CONTADOR INT ;
        DECLARE V_CONTADOR_CORREO INT ;
        DECLARE V_COUNT_PROF_email INT;
		
		DECLARE V_NOMBRE_CURSO VARCHAR(200);
		DECLARE V_ANIO_CURSO INT;
		DECLARE V_TOT_CURSO_NOM_ANIO INT;
		DECLARE V_TOT_INSCRITO_CURSO_NOM_ANIO INT;
		DECLARE V_TOT_MAX_INSCRITO INT;

        SELECT COUNT(*) INTO V_CONTADOR FROM tbl_matricula WHERE Mat_DNI = p_Mat_DNI AND cur_ID = p_cur_ID;
        SELECT COUNT(*) INTO V_CONTADOR_CORREO FROM tbl_matricula WHERE Mat_Correo = p_Mat_Corroe AND Mat_DNI != p_Mat_DNI;
        SELECT COUNT(*) INTO V_COUNT_PROF_email FROM `tbl_profesor` WHERE `email` = p_Mat_Corroe;

		SELECT trim(cur_Nombre) INTO V_NOMBRE_CURSO FROM tbl_curso WHERE cur_ID = p_cur_ID;
        SELECT YEAR(cur_FechaInicio) INTO V_ANIO_CURSO FROM tbl_curso WHERE cur_ID = p_cur_ID;
		
		SELECT count(*) INTO V_TOT_CURSO_NOM_ANIO FROM tbl_curso WHERE Estado = 1 AND YEAR(cur_FechaInicio) = V_ANIO_CURSO AND cur_Nombre = V_NOMBRE_CURSO;
		SELECT count(*) INTO V_TOT_INSCRITO_CURSO_NOM_ANIO FROM tbl_matricula mat, tbl_curso c WHERE mat.cur_ID = c.cur_ID AND mat.Mat_DNI = p_Mat_DNI AND YEAR(c.cur_FechaInicio) = V_ANIO_CURSO AND c.cur_Nombre = V_NOMBRE_CURSO;
		
		
        IF (V_CONTADOR = 0 AND V_CONTADOR_CORREO = 0 AND V_COUNT_PROF_email = 0 AND V_TOT_INSCRITO_CURSO_NOM_ANIO < 3) THEN
                INSERT INTO `dbmatricula`.`tbl_matricula` (
                cur_ID,Mat_Nombre, Mat_Paterno, Mat_Materno, Mat_TipoDocIdent,
                 Mat_DNI, Mat_Profesion, Mat_Genero, Mat_Grado, Mat_Correo, Mat_Celular, Mat_RUC,
                 Mat_Entidad, ubi_codigo, Mat_NivelGobierno, Mat_Area, Mat_AreaOpcional,
                 Mat_Cargo, Mat_Clasificacion, Mat_ModoContrato, Mat_ContracOtros, Mat_Oficio,
                 Mat_NombreArchivo, Mat_RutaArchivo,Mat_Password,Mat_idnumber,Mat_username)
                VALUES (p_cur_ID,p_Mat_Nombre,p_Mat_Paterno, p_Mat_Materno,p_Mat_TipoDocIdent,p_Mat_DNI,p_Mat_Profesion ,p_Mat_Genero ,p_Mat_GradoAcade ,
                p_Mat_Corroe,p_Mat_Celular,p_Mat_Ruc ,p_Mat_Entidad , CONCAT(p_Mat_Departamento ,p_Mat_Provincia ,p_Mat_Distrito)  ,
                p_Mat_NivGobierno ,p_Mat_Area  ,p_Mat_AreaOpcional ,p_Mat_Cargo  ,p_Mat_Clasificacion  ,p_Mat_ModContrac  ,p_Mat_ContracOtros  ,
                p_Mat_NroOficio  ,p_Mat_NomArchivo  ,p_Mat_RutaArchivo,p_Mat_DNI,p_Mat_DNI,p_Mat_DNI);
                SELECT 'Sus datos han sido registrados con éxito. Sus datos pasarán a la etapa de evaluación y, de encontrarse apto, se le enviará a su correo electrónico la confirmación de su matrícula, así como el respectivo usuario y contraseña para que pueda acceder al curso.' INTO msg_salida;
        END IF;

        IF (V_CONTADOR != 0 )THEN
                SELECT 'Ud. ya fue matriculado en este curso' INTO msg_salida;
        END IF;
        IF (V_CONTADOR_CORREO != 0 OR V_COUNT_PROF_email != 0)THEN
                SELECT 'Ya existe un usuario registrado con este correo' INTO msg_salida;
        END IF;
		IF (V_TOT_INSCRITO_CURSO_NOM_ANIO >=3 )THEN
			SELECT CONCAT('Ud. ya fue matriculado ',V_TOT_INSCRITO_CURSO_NOM_ANIO,' veces', ' en el curso ',V_NOMBRE_CURSO,' durante el año', V_ANIO_CURSO) INTO msg_salida;
        END IF;
    END ;;
DELIMITER ;




