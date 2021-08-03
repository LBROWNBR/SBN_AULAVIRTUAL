<?php

use core\dml\table;

session_start();

$xRESPUESTA = isset($_SESSION['certificacion_curso_alumn_notas']) ? $_SESSION['certificacion_curso_alumn_notas'] : die();

$mObjCursoMoodle = (object) $xRESPUESTA->xCursoMoodle;
$mObjCursoPreMatricula = (object) $xRESPUESTA->xCursoPreMatricula;
$mObjActividades = (object) $xRESPUESTA->xActividades;
$mObjActividadesDet = (object) $xRESPUESTA->xActividades;
$datos = (object) $xRESPUESTA->xInscritos;

/*
echo "<pre>";
print_r($mObjCursoMoodle);
echo "</pre>";
*/
?>


<style>
.bd-example > .form-control + .form-control {
    margin-top: .5rem;
}
.form-control2 {
    display: block;
    width: 100%;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}


/**************************************/
/******** Estilo ChecBox ON/OF ********/
/**************************************/

.flipswitch {
  position: relative;
  background: white;
  width: 110px;
  height: 35px;
  -webkit-appearance: initial;
  border-radius: 3px;
  -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
  outline: none;
  font-size: 14px;
  font-family: Trebuchet, Arial, sans-serif;
  font-weight: bold;
  cursor: pointer;
  border: 1px solid #07589f;
}

.flipswitch:after {
  position: absolute;
  top: 5%;
  display: block;
  line-height: 30px;
  width: 45%;
  height: 88%;
  /*background: #fff;*/
  box-sizing: border-box;
  text-align: center;
  transition: all 0.3s ease-in 0s;
  color: black;
  border: #000000 1px solid;
  border-radius: 3px;
}

.flipswitch:after {
  left: 2%;
  content: "NO";
  background-color: #dadada;
  color: #000000;
}

.flipswitch:checked:after {
  left: 53%;
  content: "SI";
  background-color: #3367D6;
  color: #ffffff;
}


.inputText_Bloquear_CajaAmarillo{
  background-color: #D0D0D0; 
  color: #D90000; 
  font-weight: bold;
}

.inputText_Bloquear_CajaAmarillo:disabled {
  background-color: #D0D0D0; 
  color: #D90000; 
  font-weight: bold;
}


.inputText_DesBloquear_CajaBlanca{
  background-color: #ffffff; 
  color: #4F77A2;
  font-weight: bold;
}

</style>


<div class="row">
  <div class="col-md-12 text-right">
    <button type="button" class="btn btn-danger" onclick="btn_Registrar_Certificados()">Registrar Cerficaci&oacute;n</button>
  </div>
</div>


<div class="row">
  <div class="col-md-12">
    <br>
  </div>
</div>

<div class="card text-dark" style="border-color: #a4a3a3 !important;">
    <h5 class="card-header text-white" style="background-color: #e53935;">DATOS DEL CURSO</h5>
    <div class="card-body">

        <div class="row">

            <div class="col-md-1">
                <div class="form-group">
                  <label style="font-weight: bold;">A&ntilde;o</label>
                  <input type="text" class="form-control" id="txtAnio" style="background-color: #c0c8d0; color: #0c1217;" value="<?=$mObjCursoMoodle->course_anio?>" disabled>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group">
                  <label style="font-weight: bold;">Nombre Completo del Curso</label>
                  <input type="text" class="form-control" id="txtCursoDescLarga" style="background-color: #c0c8d0; color: #0c1217;" value="<?=$mObjCursoMoodle->fullname?>" disabled>
                  <input type="hidden" class="form-control" id="txtCodCursoMoodle" style="width: 100px;" value="<?=$mObjCursoMoodle->id?>" disabled>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                  <label style="font-weight: bold;">Nombre Corto del Curso</label>
                  <input type="text" class="form-control" id="txtCursoDescCorta" style="background-color: #c0c8d0; color: #0c1217;" value="<?=$mObjCursoMoodle->shortname?>" disabled>
                </div>
            </div>           

        </div>



        <div class="row">

            <div class="col-md-2">
                <div class="form-group">
                  <label style="font-weight: bold;">Categoría Del Curso</label>
                  <input type="hidden" class="form-control" id="txtCodCategoria_Moodle" value="<?=$mObjCursoMoodle->category?>" disabled>
                  <input type="text" class="form-control" id="txtDescripCategoria_Moodle" style="background-color: #c0c8d0; color: #0c1217;" value="<?=$mObjCursoMoodle->nom_categoria?>" disabled>
                </div>
            </div>   

            <div class="col-md-2">
                <div class="form-group">
                  <label style="font-weight: bold;">Fecha Inicio</label>
                  <input type="text" class="form-control" id="txtCursoFechaIni" style="background-color: #c0c8d0; color: #0c1217;" value="<?=$mObjCursoMoodle->course_ini?>" disabled>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                  <label style="font-weight: bold;">Fecha Fin</label>
                  <input type="text" class="form-control" id="txtCursoFechaFin" style="background-color: #c0c8d0; color: #0c1217;" value="<?=$mObjCursoMoodle->course_fin?>" disabled>
                </div>
            </div> 
            
            <div class="col-md-1">
                <div class="form-group">
                  <label style="font-weight: bold;">Hora Lectiva</label>
                  <input type="text" class="form-control" id="txtHoraLectiva" style="background-color: #c0c8d0; color: #0c1217;" value="<?=$mObjCursoPreMatricula->cur_HoraLect?>" disabled>
                </div>
            </div> 

            <div class="col-md-1">
                <div class="form-group">
                  <label style="font-weight: bold;">Modalidad</label>
                  <input type="text" class="form-control" id="txtModalidad" style="background-color: #c0c8d0; color: #0c1217;" value="<?=$mObjCursoPreMatricula->cur_Modalidad?>" disabled>
                </div>
            </div> 

            <div class="col-md-2">
                <div class="form-group">
                  <label style="font-weight: bold;">Nro Evento</label>
                  <input type="text" class="form-control" id="txtNroEvento" style="background-color: #c0c8d0; color: #0c1217;" value="<?=$mObjCursoPreMatricula->cur_NumEvent?>" disabled>
                </div>
            </div> 

            <div class="col-md-2">
                <div class="form-group">
                  <label style="font-weight: bold;">Cod. POI</label>
                  <input type="text" class="form-control" id="txtCodigoPOI" style="background-color: #c0c8d0; color: #0c1217;" value="<?=$mObjCursoPreMatricula->cur_CodPOI?>" disabled>
                </div>
            </div> 

        </div>


    </div>
</div>

<style>

  th {
      background-color: #4163b5;
      color: #ffffff;
  }

  .table-striped>tbody>tr:nth-child(odd)>td,
  .table-striped>tbody>tr:nth-child(odd)>th {
    background-color: #b8daff;
    color: #000;
  }

  .table-striped>tbody>tr:nth-child(even)>td,
  .table-striped>tbody>tr:nth-child(even)>th {
    background-color: #d6d8db;
    color: #000;
  }


/*

  border-color: #7abaff;
  background-color: #b8daff;
  border: 1px solid #dee2e6;
  ->#b8daff
  ->#b8daff

  border-color: #b3b7bb;
  background-color: #d6d8db;
  border: 1px solid #dee2e6;
  ->#b3b7bb
  ->#d6d8db

*/

</style>

<div class="col-md-12 col-lg-12">
    <div class="card">

        <div class="card-body">


            <div style="overflow-x : scroll; overflow-y: scroll; height: 650px;">
                <table class="table table-striped table-bordered text-nowrap tablaEstilo" id="Table_FormCertificadoCurso">
                
                    <thead>
                        <tr>
                            <th style="text-align: center;">Item</th>
                            <th style="text-align: center;">Ap. Paterno</th>
                            <th style="text-align: center;">Ap. Materno</th>
                            <th style="text-align: center;">Nombres</th>
                            <th style="text-align: center;">DNI</th>
                            <th style="text-align: left;">Horas Lectivas</th>
                            
                            <?php                                
                              foreach ($mObjActividades as $indice01 => $regAct):
                                $Nom_columna = $mObjActividades->$indice01['NOMACTIVIDAD'];
                            ?>
                               <th style="text-align: center;"> <?=$Nom_columna?> </th>
                            <?php
                              endforeach;
                            ?>

                            <th style="text-align: center;">¿Tiene Certificado?</th>
                            <th style="text-align: center;">C&oacute;digo Certificado</th>                            
                            <th style="text-align: left;">Entidad</th>
                            
                        </tr>
                    </thead>

                    <tbody>

                    <?php

                        if($datos) {                          

                            $w=0;
                            foreach ($datos as $reg):                              

                                $w++;
                                $filaColor = ($w % 2 == 0) ? 'class="table-secondary"' : 'class="table-primary"';                                
                                $NombreAlumno = $reg['ApePatAlumno'].' '.$reg['ApeMatAlumno'].' '.$reg['NombresAlumno'];
                                
                    ?>
                        <tr title="<?=$NombreAlumno?>" >
                            <td style="text-align: center;">
                              
                              <input type="hidden" class="form-control2" id="txt_mdlUserID_<?=$w;?>" value="<?=$reg['mdl_user_id'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_ApePatAlumno_<?=$w;?>" value="<?=$reg['ApePatAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_ApeMatAlumno_<?=$w;?>" value="<?=$reg['ApeMatAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_NombresAlumno_<?=$w;?>" value="<?=$reg['NombresAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_DNIAlumno_<?=$w;?>" value="<?=$reg['DNIAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_EntidadAlumno_<?=$w;?>" value="<?=$reg['EntidadAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_NivelGobAlumno_<?=$w;?>" value="<?=$reg['NivelGobAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_RubroAlumno_<?=$w;?>" value="<?=$reg['RubroAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_DepaAlumno_<?=$w;?>" value="<?=$reg['DepaAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_ProvAlumno_<?=$w;?>" value="<?=$reg['ProvAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_DistAlumno_<?=$w;?>" value="<?=$reg['DistAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_GradoAcademicoAlumno_<?=$w;?>" value="<?=$reg['GradoAcademicoAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_ProfesionAlumno_<?=$w;?>" value="<?=$reg['ProfesionAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_CargoAlumno_<?=$w;?>" value="<?=$reg['CargoAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_TipoContratoAlumno_<?=$w;?>" value="<?=$reg['TipoContratoAlumno'];?>">
                              <input type="hidden" class="form-control2" id="txtCert_AreaLaboraAlumno_<?=$w;?>" value="<?=$reg['AreaLaboraAlumno'];?>">

                              <?=$reg['Item'];?>
                            </td>
                            <td style="text-align: left;">                              
                              <?=$reg['ApePatAlumno'];?>
                            </td>
                            <td style="text-align: left;">
                              <?=$reg['ApeMatAlumno'];?>
                            </td>
                            <td style="text-align: left;">
                              <?=$reg['NombresAlumno'];?>
                            </td>
                            <td style="text-align: center;">                              
                              <?=$reg['DNIAlumno'];?>
                            </td>
                            <td style="text-align: center;">
                              <?=$mObjCursoPreMatricula->cur_HoraLect?>
                            </td>                            
                            
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

                            <td style="text-align: center;">                   
                              <span class="input-group-addon" style="color: #333; font-weight: bold;">
                                <input class="flipswitch" type="checkbox" id="checkSelectCertificado_<?=$w;?>" name="checkSelectCertificado_<?=$w;?>" onclick="ValidarCheckedCertificado(<?=$w;?>)">
                              </span>
                            </td>
                            <td style="text-align: center;">
                              <input type="text" class="form-control2" id="txt_NroCertificado_<?=$w;?>" value="" disabled="disabled" style="background-color: #c0c8d0; font-weight: bold;" maxlength="15"></div> 
                            </td>                            
                            <td style="text-align: left;">                              
                              <?=$reg['EntidadAlumno'];?>
                            </td>
                        </tr>   
                        

                    <?php 

                            endforeach;

                        } 
                    ?>               
                    </tbody>

                </table>
                <input type="hidden" value="<?=$w;?>" id="txt_total_registros">

            </div>

         
        </div>
    </div>
</div>

</div>

<?php
unset($_SESSION['certificacion_curso_alumn_notas']);
?>

<script>
  formatoTablaFormulario();
</script>