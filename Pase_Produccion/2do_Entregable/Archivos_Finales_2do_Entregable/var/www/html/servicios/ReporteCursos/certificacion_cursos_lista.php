<?php

use core\dml\table;

session_start();

$dataCertificaciones = isset($_SESSION['Lista_certificacion_BY_Curso_Anio']) ? $_SESSION['Lista_certificacion_BY_Curso_Anio'] : die();

/*
    echo "<pre>";
    print_r($dataCertificaciones);
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
        }
        ?>


            <div style="overflow-x : scroll; overflow-y: scroll; height: 500px;">
                <table class="table table-striped table-bordered text-nowrap tablaEstilo" id="Table_ListaCertificadoCurso">
                
                    <thead>
                        <tr>
                            <th style="width: 5%">Item</th>
                            <th style="width: 5%">A&ntilde;o</th>
                            <th style="width: 5%">ID Curso</th>
                            <th style="width: 35%">Curso Nombre Largo</th>
                            <th style="width: 10%">Curso Nombre Corto</th>
                            <th style="width: 5%">Fecha Inicio</th>
                            <th style="width: 5%">Fecha Fin</th>
                            <th style="width: 10%">Hora Lectiva</th>
                            <th style="width: 10%">Modalidad</th>
                            <th style="width: 10%">Nro. Evento</th>
                            <th style="width: 10%">Cod. POI</th>
                            <th style="width: 10%">Id User Moodle</th>
                            <th style="width: 10%">Ap. Paterno</th>
                            <th style="width: 10%">Ap. Materno</th>
                            <th style="width: 10%">Nombres</th>
                            <th style="width: 10%">DNI</th>
                            <th style="width: 10%">Entidad</th>
                            <th style="width: 10%">Nivel Gobierno</th>
                            <th style="width: 10%">Rubro</th>
                            <th style="width: 10%">Departamento Laboral</th>
                            <th style="width: 10%">Provincia Laboral</th>
                            <th style="width: 10%">Distrito Laboral</th>
                            <th style="width: 10%">Grado Académico</th>
                            <th style="width: 10%">Profesión</th>
                            <th style="width: 10%">Cargo</th>
                            <th style="width: 10%">Tipo Contrato</th>
                            <th style="width: 10%">Area Labora</th>
                            <th style="width: 10%">¿Tiene Certificado?</th>
                            <th style="width: 10%">Nro Certificado</th>                            
                            <th style="width: 10%">Nota Práctica</th>
                            <th style="width: 10%">Nota Foro</th>
                            <th style="width: 10%">Examen Final</th>
                            <th style="width: 10%">Promedio Final</th>
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
                            <td><?=$reg['anio'];?></td>
                            <td><?=$reg['mdl_cur_id'];?></td>
                            <td><?=$reg['mdl_cur_fullname'];?></td>
                            <td><?=$reg['mdl_cur_shortname'];?></td>
                            <td><?=$reg['mat_fechaini'];?></td>
                            <td><?=$reg['mat_fechafin'];?></td>
                            <td><?=$reg['mat_horalectiva'];?></td>
                            <td><?=$reg['mat_modalidad'];?></td>
                            <td><?=$reg['mat_nroevento'];?></td>
                            <td><?=$reg['mat_codpoi'];?></td>
                            <td><?=$reg['mdl_user_id'];?></td>                            
                            <td><?=$reg['mdl_apepat'];?></td>
                            <td><?=$reg['mdl_apemat'];?></td>
                            <td><?=$reg['mdl_nombres'];?></td>
                            <td><?=$reg['mdl_dni'];?></td>
                            <td><?=$reg['mdl_entidad'];?></td>
                            <td><?=$reg['mdl_nivelgrado'];?></td>
                            <td><?=$reg['mdl_rubro'];?></td>
                            <td><?=$reg['mdl_depa_lab'];?></td>
                            <td><?=$reg['mdl_prov_lab'];?></td>
                            <td><?=$reg['mdl_dist_lab'];?></td>
                            <td><?=$reg['mdl_gradoacademico'];?></td>
                            <td><?=$reg['mdl_profesion'];?></td>
                            <td><?=$reg['mdl_cargo'];?></td>
                            <td><?=$reg['mdl_tipocontrato'];?></td>
                            <td><?=$reg['mdl_area_lab'];?></td>
                            <td><?=$DescripCertificado;?></td>
                            <td><?=$reg['NroCertificado'];?></td>
                            <td><?=$reg['NotaPractica'];?></td>
                            <td><?=$reg['NotaForo'];?></td>
                            <td><?=$reg['ExamenFinal'];?></td>
                            <td><?=$reg['PromedioFinal'];?></td>                            
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