
<?php

require_once("../../servicios/conexion.php"); //Contiene funcion que conecta a la base de datos

//$P_ACCION = mysqli_real_escape_string($mysqlConeccion,(strip_tags($_GET["P_ACCION"],ENT_QUOTES)));
//$sql = "call sp_sel_Cursos_estado";

if(isset($_GET['rol'])) {
    if($_GET['rol'] == 'admin'){
        $sql = "call sp_sel_Cursos_estado_admin";
    }else{
        $sql = "call sp_sel_Cursos_estado_v2";
    }    
}else{
    $sql = "call sp_sel_Cursos_estado_v2";
}

//mysqli_set_charset($conexion, "utf8"); //formato de datos utf8
//$query = mysqli_query($mysqlConeccion,$sql);
$listaCargada =  mysqli_query($mysqlConeccion, $sql);
$data = "";
$html = "";
$row_cnt = $listaCargada->num_rows;
if (trim($row_cnt) != '0') {

    while ($row = mysqli_fetch_array($listaCargada)) {
        $Cursos[] = $row;
    }

    //$json_string = json_encode($rawdata);

    foreach ($Cursos as $datosCursos) {

        $namesCourse = '"' . $datosCursos[1] . '"';
        $routeCourse = '"' . $datosCursos[7] . $datosCursos[6] . '"';

        /*
        $data .= "<div class='col-md-4 col-lg-4 col-xl-4'>";
        $data .= "<div class='card mb-4'>";
        $data .= "<a href='JavaScript:fnDetalleCurso({$datosCursos[0]}, {$namesCourse}, {$routeCourse});'>";
        $data .= "<img src='..{$datosCursos[7]}{$datosCursos[6]}' class='card-img-top img-course-card' alt='{$datosCursos[1]} '>";
        $data .= "</a>";
        $data .= "<div class='card-body'>";
        $data .= "Fecha de inicio: {$datosCursos[2]}</br>";
        $data .= "Fecha de fin: {$datosCursos[3]}";
        $data .= "</div>";
        $data .= "</div>";
        $data .= "</div>";
        */

        $data .= "<div class='col-md-4 col-lg-4 col-xl-4'>";
        $data .= "<div class='card mb-4'>";
        $data .= "<a href='JavaScript:fnDetalleCurso({$datosCursos[0]}, {$namesCourse}, {$routeCourse});'>";
        $data .= "<img src='..{$datosCursos[7]}{$datosCursos[6]}' class='card-img-top img-course-card' alt='{$datosCursos[1]} '>";
        $data .= "</a>";
        $data .= "<div class='card-body'>";
        $data .= "<b>Curso: {$datosCursos[1]}</b></br>";
        $data .= "Fecha de inicio: {$datosCursos[2]}</br>";
        $data .= "Fecha de fin: {$datosCursos[3]}</br>";
        $data .= "Estado: {$datosCursos[9]}</br></br>";
        $data .= "<center><a href='JavaScript:fnDetalleCurso({$datosCursos[0]}, {$namesCourse}, {$routeCourse});' class='btn btn-warning'>M&aacute;s Informaci&oacute;n</a></center>";
        $data .= "</div>";
        $data .= "</div>";
        $data .= "</div>";

        // $data .= "<div class='col-md-3 item'>";
        // $data .= "<a href='JavaScript:fnDetalleCurso(" . $datosCursos[0] . ",\"" . $datosCursos[1] . "\",\"" . $datosCursos[7] . $datosCursos[6] . "\");'>";
        // $data .= "<figure>";
        // $data .= "<img src='.." . $datosCursos[7] . $datosCursos[6] . "' class='img-responsive' alt='" . $datosCursos[1] . "' style='height: 150px; width: 240px;'>";
        // $data .= "</figure>";
        // $data .= "</a>";
        // $data .= "<div class='card card-task'>";
        // $data .= "<div class='card-body'>";
        // $data .= "<a href='JavaScript:fnDetalleCurso(" . $datosCursos[0] . ",\"" . $datosCursos[1] . "\",\"" . $datosCursos[7] . $datosCursos[6] . "\");'>";
        // $data .= "<div >";
        // $data .= "<div></br> Fecha de inicio: " . $datosCursos[2] . "</br>Fecha de fin: " . $datosCursos[3] . "  </div>";
        // $data .= "</div>";
        // $data .= "</a>";
        // $data .= "</div>";
        // $data .= "</div>";
        // $data .= "</div>";
    }
} else {
    $data .= "<div  class='col-md-3 item text-center'>";
    $data .= "No se encontraron cursos";
    $data .= "</div>";
}


if ($listaCargada) {
    $messages[] = "<div class='row'>{$data}</div>";
} else {
    $errors[] = "Error";
}


if (isset($errors)) {
    foreach ($errors as $error) {
        echo $error;
    }
}
if (isset($messages)) {
    foreach ($messages as $message) {
        echo $message;
    }
}
