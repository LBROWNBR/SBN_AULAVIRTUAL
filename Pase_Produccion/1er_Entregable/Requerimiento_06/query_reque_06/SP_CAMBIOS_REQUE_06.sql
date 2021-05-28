

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
