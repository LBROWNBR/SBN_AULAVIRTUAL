<?php

use core\dml\table;

session_start();

$dataCertificaciones = isset($_SESSION['Lista_certificacion_BY_Curso_Anio']) ? $_SESSION['Lista_certificacion_BY_Curso_Anio'] : die();
$dataListaActividades = isset($_SESSION['Lista_Actividades_BY_Curso']) ? $_SESSION['Lista_Actividades_BY_Curso'] : die();
$mObjActividades = (object) $dataListaActividades;
/*
    echo "<pre>";
    print_r($dataListaActividades);
    echo "</pre>";
*/

?>


<div class="col-md-12 col-lg-12">
    <div class="card">

        <div class="card-body">

        <?php
        if(count($dataCertificaciones) == 0){
        ?>
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-primary" onclick="FormRegAlumnosCertificacion()"><i class="far fa-file"></i> Nuevo</button>
                </div>
            </div>
        <?php 
        }else{

            $wCodCurso = $dataCertificaciones[0]['mdl_cur_id'];
        ?>
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-danger" onclick="LimpiarCertificacion('<?=$wCodCurso?>')"> Limpiar Lista de Certificados</button>
                </div>
            </div>
        <?php 
        }
        ?>


            <div style="overflow-x : scroll; overflow-y: scroll; height: 500px;">
                <table class="table table-striped table-bordered text-nowrap tablaEstilo" id="Table_ListaCertificadoCurso">
                
                    <thead>
                        <tr>
                            <th style="width: 5%">Item</th>
                            <th style="width: 65%">Curso Nombre Largo</th>
                            <th style="width: 65%">Curso Nombre Corto</th>
                            <th style="width: 65%">Ap. Paterno</th>
                            <th style="width: 65%">Ap. Materno</th>
                            <th style="width: 65%">Ap. Nombres</th>
                            <th style="width: 10%">DNI</th>
                            <th style="width: 10%">Entidad</th>
                            <th style="width: 10%">Hora Lectiva</th>

                            <?php                                
                              foreach ($mObjActividades as $indice01 => $regAct):
                                $Nom_columna = $mObjActividades->$indice01['NOMACTIVIDAD'];
                            ?>
                               <th style="text-align: center;"> <?=$Nom_columna?> </th>
                            <?php
                              endforeach;
                            ?>

                            <th style="width: 10%">¿Tiene <br>Certificado?</th>                            
                            <th style="width: 10%">Código<br> Certificado</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                   

                        if(count($dataCertificaciones)>0) {

                            $w=0;
                            foreach ($dataCertificaciones as $reg):

                                $w++;
                                $filaColor = ($w % 2 == 0) ? 'class="table-secondary"' : 'class="table-primary"';
                                $DescripCertificado = ($reg['ConCertificado']==1) ? 'SI' : 'NO';
                                
                    ?>
                        <tr <?=$filaColor?>>
                            <td><?=$w;?></td>                          
                            <td><?=$reg['mdl_cur_fullname'];?></td>
                            <td><?=$reg['mdl_cur_shortname'];?></td>
                            <td><?=$reg['mdl_apepat'];?></td>
                            <td><?=$reg['mdl_apemat'];?></td>
                            <td><?=$reg['mdl_nombres'];?></td>
                            <td><?=$reg['mdl_dni'];?></td>
                            <td><?=$reg['mdl_entidad'];?></td>
                            <td><?=$reg['mat_horalectiva'];?></td>

                            <?php

                              $ArrayNotasDet = $reg['mdl_NOTAS'];

                              foreach ($mObjActividades as $xIndice => $regActividad):
                                
                                if($ArrayNotasDet){
                                    if(isset($ArrayNotasDet[$xIndice]['NOTA'])){
                                      echo '<td style="text-align: center;">'.$ArrayNotasDet[$xIndice]['NOTA'].'</td>';
                                    }else{
                                      echo '<td style="text-align: center;">0</td>';
                                    }                                    
                                }else{
                                  echo '<td style="text-align: center;">0</td>';
                                }

                              endforeach;
                              
                            ?>

                            <td><?=$DescripCertificado;?></td>
                            <td><?=$reg['NroCertificado'];?></td>                            
                        </tr>   

                    <?php 
                            endforeach;
                        } 

                    ?>               
                    </tbody>

                </table>

            </div>

         
        </div>
    </div>
</div>

</div>

<?php
unset($_SESSION['Lista_Actividades_BY_Curso']);
unset($_SESSION['Lista_certificacion_BY_Curso_Anio']);
?>
<script>
    var tablaCurso = $("#Table_ListaCertificadoCurso").DataTable({
            "pageLength": 10000,
            "bDestroy": true,
            "lengthMenu": [10, 20, 30, 40],
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": true,
            "columnDefs": [{
                "targets": 'no-sort',
                "orderable": false,
            }],
            "language": idioma_espanol,
            dom: 'Bfrtip',
            buttons: ['excel', 'pdf', 'csv', 'copy', 'print']
        });
</script>