-- DROP PROCEDURE IF EXISTS sp_get_matricula;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_matricula`(`p_estado_proc` VARCHAR(1))
BEGIN
	SELECT mat.Mat_ID,Mat_Nombre, mat.Mat_Paterno, mat.Mat_Materno, 
	 mat.Mat_DNI, mat.Mat_Profesion, mat.Mat_Genero, mat.Mat_Grado, mat.Mat_Correo, mat.Mat_Celular, mat.Mat_RUC,
	 mat.Mat_Entidad, SUBSTRING(mat.ubi_codigo,-6,2) AS Mat_Departamento, SUBSTRING(mat.ubi_codigo,-4,2) AS Mat_Provincia, 
	SUBSTRING(mat.ubi_codigo,-2,2) AS Mat_Distrito, mat.Mat_NivelGobierno, mat.Mat_Area, 
	 mat.Mat_Cargo, Mat_Clasificacion, mat.Mat_ModoContrato, mat.Mat_ContracOtros, mat.Mat_Oficio, 
	 mat.Mat_NombreArchivo, mat.Mat_RutaArchivo,mat.Mat_Estado,mat.Mat_Estado_proc,cur.cur_Nombre,mat.Mat_AreaOpcional,mat.Mat_TipoDocIdent, cur.course_shortname
	 FROM tbl_matricula AS mat INNER JOIN tbl_curso AS cur ON mat.cur_ID =cur.cur_ID WHERE mat.Mat_Estado_proc = p_estado_proc
	 AND mat.Estado = '1' ORDER BY mat.Mat_Nombre;
    END ;;
DELIMITER ;




-- DROP PROCEDURE IF EXISTS sp_get_matricula_v2;
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
	 mat.Mat_NombreArchivo, mat.Mat_RutaArchivo,mat.Mat_Estado,mat.Mat_Estado_proc,cur.cur_Nombre,mat.Mat_AreaOpcional,mat.Mat_TipoDocIdent, cur.course_shortname
	 FROM tbl_matricula AS mat 
	 INNER JOIN tbl_curso AS cur ON mat.cur_ID =cur.cur_ID 
	 WHERE mat.Mat_Estado_proc = p_estado_proc
	 AND mat.Estado = '1' 
	 AND mat.cur_ID = p_cur_ID
	 ORDER BY mat.Mat_Nombre;
    END ;;
DELIMITER ;


-- DROP TABLE IF EXISTS `tbl_certificados`;
CREATE TABLE `tbl_certificados` (
  `CertID` int(11) NOT NULL AUTO_INCREMENT,
  `anio` int(11) NOT NULL,
  `mdl_cur_id` int(11) NOT NULL,  
  `mdl_cur_fullname` varchar(200) NOT NULL DEFAULT '',
  `mdl_cur_shortname` varchar(255) NOT NULL DEFAULT '',
  `mdl_category_id` int(11) NOT NULL,  
  `mdl_nom_categoria` varchar(255) NOT NULL DEFAULT '',  
  `mat_fechaini` varchar(15) DEFAULT NULL,
  `mat_fechafin` varchar(15) DEFAULT NULL,
  `mat_horalectiva` varchar(20) DEFAULT NULL,
  `mat_modalidad` varchar(20) DEFAULT NULL,
  `mat_nroevento` varchar(20) DEFAULT NULL,
  `mat_codpoi` varchar(20) DEFAULT NULL,
  `mdl_user_id` int(11) DEFAULT '0',
  `mdl_apepat` varchar(200) NOT NULL DEFAULT '',
  `mdl_apemat` varchar(200) NOT NULL DEFAULT '',
  `mdl_nombres` varchar(200) NOT NULL DEFAULT '',
  `mdl_dni` varchar(15) NOT NULL DEFAULT '',
  `mdl_entidad` varchar(300) NOT NULL DEFAULT '',
  `mdl_nivelgrado` varchar(300) NOT NULL DEFAULT '',
  `mdl_rubro` varchar(300) NOT NULL DEFAULT '',
  `mdl_depa_lab` varchar(300) NOT NULL DEFAULT '',
  `mdl_prov_lab` varchar(300) NOT NULL DEFAULT '',
  `mdl_dist_lab` varchar(300) NOT NULL DEFAULT '',
  `mdl_gradoacademico` varchar(300) NOT NULL DEFAULT '',
  `mdl_profesion` varchar(300) NOT NULL DEFAULT '',
  `mdl_cargo` varchar(300) NOT NULL DEFAULT '',
  `mdl_tipocontrato` varchar(300) NOT NULL DEFAULT '',
  `mdl_area_lab` varchar(300) NOT NULL DEFAULT '',  
  `ConCertificado` char(1) DEFAULT '0',  
  `NroCertificado` varchar(15) DEFAULT NULL,  
  `id_estado` varchar(1) DEFAULT '1',
  PRIMARY KEY (`CertID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



-- DROP PROCEDURE IF EXISTS sp_sel_Usuarioxemail;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_sel_Usuarioxemail`(IN `USER_email` VARCHAR(100))
BEGIN
	SELECT * FROM `tbl_usuario` WHERE Usu_Email = USER_email;
    END ;;
DELIMITER ;
