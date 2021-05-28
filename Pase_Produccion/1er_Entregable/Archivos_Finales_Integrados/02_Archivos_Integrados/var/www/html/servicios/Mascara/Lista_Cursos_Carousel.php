
<?php

require_once ("../../servicios/conexion.php");//Contiene funcion que conecta a la base de datos

//$P_ACCION = mysqli_real_escape_string($mysqlConeccion,(strip_tags($_GET["P_ACCION"],ENT_QUOTES)));
//$sql="call sp_sel_Cursos_estado";

$sql="call sp_sel_Cursos_estado_carrusel";

//mysqli_set_charset($conexion, "utf8"); //formato de datos utf8
//$query = mysqli_query($mysqlConeccion,$sql);
$listaCargada =  mysqli_query($mysqlConeccion,$sql);
$data ="";
$row_cnt = $listaCargada->num_rows;
if(trim($row_cnt) !='0'){
    
while ($row = mysqli_fetch_array($listaCargada))
{
    $Cursos[] = $row;

}

//$json_string = json_encode($rawdata);
$contador=0;

foreach($Cursos as $datosCursos)
 {
 $contador+=1;
 if($contador == 1){
 	$data .="<div class='carousel-item active'>";
    $data .="<a href='JavaScript:fnDetalleCurso(".$datosCursos[0].",\"".$datosCursos[1]."\",\"".$datosCursos[7].$datosCursos[6]."\");'>";
	$data .="<img class='d-block img-course-carrusel' src='..".$datosCursos[7].$datosCursos[6]."' alt='".$datosCursos[1]."'> ";
	//
    
	$data .="</a>";
	$data .="</div>";

 }else{
 	$data .="<div class='carousel-item '>";
$data .="<a href='JavaScript:fnDetalleCurso(".$datosCursos[0].",\"".$datosCursos[1]."\",\"".$datosCursos[7].$datosCursos[6]."\");'>";
	$data .="<img class='d-block img-course-carrusel' src='..".$datosCursos[7].$datosCursos[6]."' alt='".$datosCursos[1]."'> ";
	

	$data .="</a>";
	$data .="</div>";
 }

}

}else{
    $data .="<div  class='col-md-3 item text-center'>";
    $data .= "No se encontraron cursos"; 
    $data .="</div>";
}


if ($listaCargada) {
    $messages[] =$data;
} else {
    $errors[] = "Error";
}


if (isset($errors)){
			
    ?>

            <?php
                foreach ($errors as $error) {
                        echo $error;
                    }
                ?>
    <?php
    }
    if (isset($messages)){
        
        ?>

                <?php
                    foreach ($messages as $message) {
                            echo $message;
                        }
                    ?>

        <?php
    }

?>

