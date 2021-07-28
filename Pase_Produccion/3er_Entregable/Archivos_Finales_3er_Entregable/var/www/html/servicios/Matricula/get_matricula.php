
<?php

require_once("../../servicios/conexion.php"); //Contiene funcion que conecta a la base de datos

$CBO_PROCESO = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_GET["CBO_PROCESO"], ENT_QUOTES)));
$xCurso = mysqli_real_escape_string($mysqlConeccion, (strip_tags($_GET["xCurso"], ENT_QUOTES)));
//$P_ACCION = mysqli_real_escape_string($mysqlConeccion,(strip_tags($_GET["P_ACCION"],ENT_QUOTES)));
//$sql = "call sp_get_matricula($CBO_PROCESO)";

if($xCurso !=''){
    $sql = "call sp_get_matricula_v2($CBO_PROCESO, $xCurso)";
}else{
    $sql = "call sp_get_matricula($CBO_PROCESO)";
}

//mysqli_set_charset($conexion, "utf8"); //formato de datos utf8
//$query = mysqli_query($mysqlConeccion,$sql);
$listaCargada =  mysqli_query($mysqlConeccion, $sql);
$data = "";
$row_cnt = $listaCargada->num_rows;
if (trim($row_cnt) != '0') {

    while ($row = mysqli_fetch_array($listaCargada)) {
        $matriculas[] = $row;
    }

    //$json_string = json_encode($rawdata);

    foreach ($matriculas as $datosMatricula) {
        $data .=  "<tr>";
        $data .= "<td>" . " </td>";
        $data .= " <td>";


        $marcar = "";
        if ($datosMatricula[25] != "1") {
            $data .= "<input type='checkbox' value='" . $datosMatricula[0] . "'  class='form-check-input' id='idcheckbox' name='idcheckbox' >";
        }

        $data .=  "<a onClick='fnEditarMat(" . $datosMatricula[0] . ")' title='Editar'><span class='fas fa-pencil-alt' ></span></a>";
        $data .= "<a  onClick='fnEliminarMat(" . $datosMatricula[0] . ")'  title='Eliminar'><span class='fas fa-trash-alt'></span></a> ";

        $verPDF = '';
        if (trim($datosMatricula[22]) != "" || trim($datosMatricula[22]) != null) {
            // $data .= "<a  onClick='fnVerPDF(\"" . $datosMatricula[23] . "\",\"" . $datosMatricula[22] . "\")'  title='Ver oficio'><span class='far fa-eye'></span></a> ";
            $rutaArchivo = '../..' .  $datosMatricula[23] . $datosMatricula[22];
            if (file_exists($rutaArchivo)) {
                $verPDF = "<button type='button' class='btn btn-link' onClick='fnVerPDF(\"" . $datosMatricula[23] . "\",\"" . $datosMatricula[22] . "\")'  >Ver PDF</button>";
            }
        }


        $data .= " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[0] . " </td>";
        $data .= "<td>" . $datosMatricula[1] . " </td>";
        $data .= "<td>" . $datosMatricula[2] . " </td>";
        $data .= "<td>" . $datosMatricula[3] . " </td>";
        $data .= "<td>" . $datosMatricula[4] . " </td>";
        $data .= "<td>" . $datosMatricula[5] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[6] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[7] . " </td>";
        $data .= "<td>" . $datosMatricula[8] . " </td>";
        $data .= "<td>" . $datosMatricula[9] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[10] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[11] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[12] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[13] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[14] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[15] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[16] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[17] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[18] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[19] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[20] . " </td>";
        $data .= "<td >" . $datosMatricula[21] . " </td>";
        $data .= "<td >" . $verPDF . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[22] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[23] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[27] . " </td>";
        $data .= "<td >" . $datosMatricula[26] . " </td>";
        $data .= "<td >" . $datosMatricula[29] . " </td>";
        $data .= "<td class='ocultar'>" . $datosMatricula[28] . " </td>";
        $data .=  "</tr>";
    }
} else {
    $data .=  "<tr>";
    $data .= "<td colspan='15' class='text-center'>" . "No se encontraron Registros" . " </td>";
    $data .=  "</tr>";
}


//echo $data;
//return $matriculas;




//echo $json_string;

if ($listaCargada) {
    $messages[] = $data;
} else {
    $errors[] = "Error";
}


if (isset($errors)) {

?>

            <?php
            foreach ($errors as $error) {
                echo $error;
            }
            ?>
    <?php
        }
        if (isset($messages)) {

    ?>

                <?php
                foreach ($messages as $message) {
                    echo $message;
                }
                ?>

        <?php
            }

        ?>

