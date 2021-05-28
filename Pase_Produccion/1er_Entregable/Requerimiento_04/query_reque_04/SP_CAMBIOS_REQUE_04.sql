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


