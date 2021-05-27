
<?php

require_once("../../servicios/conexion.php"); //Contiene funcion que conecta a la base de datos

//$P_ACCION = mysqli_real_escape_string($mysqlConeccion,(strip_tags($_GET["P_ACCION"],ENT_QUOTES)));
//$sql = "call sp_get_Cursos";
$sql = "call sp_get_Cursos_V2";
//mysqli_set_charset($conexion, "utf8"); //formato de datos utf8
//$query = mysqli_query($mysqlConeccion,$sql);
$listaCargada =  mysqli_query($mysqlConeccion, $sql);
$data = "";
$row_cnt = $listaCargada->num_rows;
if (trim($row_cnt) != '0') {

    while ($row = mysqli_fetch_array($listaCargada)) {
        $Usuarios[] = $row;
    }

    //$json_string = json_encode($rawdata);

    foreach ($Usuarios as $datosUsuarios) {
        $data .=  "<tr>";
        $data .= "<td>" . " </td>";
        $data .= " <td>";
        $data .=  "<a onClick='fnEditarCursos(" . $datosUsuarios[0] . ")' title='Editar'><i class='fas fa-pencil-alt'></i></a>";
        $data .= "<a  onClick='fnEliminarCurso(" . $datosUsuarios[0] . ")'  title='Eliminar'><i class='fas fa-trash-alt'></i></a> ";
        $data .= "<a  onClick='fnListarMatriculasXcurso(\"". $datosUsuarios[0]."\",\"".$datosUsuarios[1]. "\")'  title='Matriculados'><i class='fas fa-check'></i></a> ";
        $data .= " </td>";
        $data .= "<td class='ocultar'>" . $datosUsuarios[0] . " </td>";
        $data .= "<td>" . $datosUsuarios[1] . " </td>";
        $data .= "<td>" . $datosUsuarios[12] . " </td>";
        $data .= "<td>" . $datosUsuarios[2] . " </td>";
        $data .= "<td>" . $datosUsuarios[3] . " </td>";
        $data .= "<td>" . $datosUsuarios[4] . " </td>";
        $data .= "<td>" . $datosUsuarios[5] . " </td>";
        $data .= "<td>" . $datosUsuarios[6] . " </td>";
        $data .= "<td>" . $datosUsuarios[7] . " </td>";
        $data .= "<td>" . $datosUsuarios[15] . " </td>";
        $data .= "<td>" . $datosUsuarios[16] . " </td>";
        $data .= "<td>" . $datosUsuarios[17] . " </td>";
        $data .= "<td>" . $datosUsuarios[18] . " </td>";
        $data .= "<td class='ocultar'>" . $datosUsuarios[8] . " </td>";
        $data .= "<td class='ocultar'>" . $datosUsuarios[9] . " </td>";
        $data .= "<td class='ocultar'>" . $datosUsuarios[10] . " </td>";        
        $data .= "<td class='ocultar'>" . $datosUsuarios[13] . " </td>";
        $data .= "<td class='ocultar'>" . $datosUsuarios[14] . " </td>";
        $data .= "<td>" . $datosUsuarios[19] . " </td>";
        $data .=  "</tr>";
    }
    
} else {
    $data .=  "<tr>";
    $data .= "<td colspan='12'  class='text-center'>" . "No se encontraron registros" . " </td>";
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

