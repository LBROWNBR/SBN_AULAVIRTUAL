<?php

use core\dml\table;

session_start();

$datos = isset($_SESSION['certificacion_curso_alumn_notas']) ? $_SESSION['certificacion_curso_alumn_notas'] : die();

/*
    echo "<pre>";
    print_r($datos);
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

<div class="col-md-12 col-lg-12">
    <div class="card">

        <div class="card-body">


            <div style="overflow-x : scroll; overflow-y: scroll; height: 650px;">
                <table class="table table-striped table-bordered text-nowrap tablaEstilo" id="Table_FormCertificadoCurso">
                
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
                            <th style="width: 15%">Cod. POI</th>
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

                        if($datos) {

                            $w=0;
                            foreach ($datos as $reg):

                                $w++;
                                $filaColor = ($w % 2 == 0) ? 'class="table-secondary"' : 'class="table-primary"';

                                $NombreAlumno = $reg['ApePatAlumno'].' '.$reg['ApeMatAlumno'].' '.$reg['NombresAlumno'];

                                list($IniAnio,$IniMes,$IniDia)=explode("-",$reg['CursoFechaIni']);
                                $xFechaINI = $IniDia.'-'.$IniMes.'-'.$IniAnio;
                                list($FinAnio,$FinMes,$FinDia)=explode("-",$reg['CursoFechaFin']);
                                $xFechaFIN = $FinDia.'-'.$FinMes.'-'.$FinAnio;
                                
                    ?>
                        <tr <?=$filaColor?> title="<?=$NombreAlumno?>" >
                            <td><?=$reg['Item'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_Anio_<?=$w;?>" value="<?=$reg['Anio'];?>"><?=$reg['Anio'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_IdCurso_<?=$w;?>" value="<?=$reg['IdCurso'];?>"><?=$reg['IdCurso'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_CursoNomLargo_<?=$w;?>" value="<?=$reg['CursoNomLargo'];?>"><?=$reg['CursoNomLargo'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_CursoNomCorto_<?=$w;?>" value="<?=$reg['CursoNomCorto'];?>"><?=$reg['CursoNomCorto'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_CursoFechaIni_<?=$w;?>" value="<?=$xFechaINI;?>"><?=$xFechaINI;?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_CursoFechaFin_<?=$w;?>" value="<?=$xFechaFIN;?>"><?=$xFechaFIN;?></td>
                            <td><input type="text" class="form-control2" id="txtCert_CursoHoraLect_<?=$w;?>" value="<?=$reg['CursoHoraLect'];?>"></td>
                            <td><input type="text" class="form-control2" id="txtCert_CursoModalidad_<?=$w;?>" value="<?=$reg['CursoModalidad'];?>"></td>
                            <td><input type="text" class="form-control2" id="txtCert_CursoNumEvent_<?=$w;?>" value="<?=$reg['CursoNumEvent'];?>"></td>
                            <td><input type="text" class="form-control2" id="txtCert_CursoCodPOI_<?=$w;?>" value="<?=$reg['CursoCodPOI'];?>"></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_ApePatAlumno_<?=$w;?>" value="<?=$reg['ApePatAlumno'];?>"><?=$reg['ApePatAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_ApeMatAlumno_<?=$w;?>" value="<?=$reg['ApeMatAlumno'];?>"><?=$reg['ApeMatAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_NombresAlumno_<?=$w;?>" value="<?=$reg['NombresAlumno'];?>"><?=$reg['NombresAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_DNIAlumno_<?=$w;?>" value="<?=$reg['DNIAlumno'];?>"><?=$reg['DNIAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_EntidadAlumno_<?=$w;?>" value="<?=$reg['EntidadAlumno'];?>"><?=$reg['EntidadAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_NivelGobAlumno_<?=$w;?>" value="<?=$reg['NivelGobAlumno'];?>"><?=$reg['NivelGobAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_RubroAlumno_<?=$w;?>" value="<?=$reg['RubroAlumno'];?>"><?=$reg['RubroAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_DepaAlumno_<?=$w;?>" value="<?=$reg['DepaAlumno'];?>"><?=$reg['DepaAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_ProvAlumno_<?=$w;?>" value="<?=$reg['ProvAlumno'];?>"><?=$reg['ProvAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_DistAlumno_<?=$w;?>" value="<?=$reg['DistAlumno'];?>"><?=$reg['DistAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_GradoAcademicoAlumno_<?=$w;?>" value="<?=$reg['GradoAcademicoAlumno'];?>"><?=$reg['GradoAcademicoAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_ProfesionAlumno_<?=$w;?>" value="<?=$reg['ProfesionAlumno'];?>"><?=$reg['ProfesionAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_CargoAlumno_<?=$w;?>" value="<?=$reg['CargoAlumno'];?>"><?=$reg['CargoAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_TipoContratoAlumno_<?=$w;?>" value="<?=$reg['TipoContratoAlumno'];?>"><?=$reg['TipoContratoAlumno'];?></td>
                            <td><input type="hidden" class="form-control2" id="txtCert_AreaLaboraAlumno_<?=$w;?>" value="<?=$reg['AreaLaboraAlumno'];?>"><?=$reg['AreaLaboraAlumno'];?></td>
                            <td>
                                <div class="input-group">                      
                                    <span class="input-group-addon" style="color: #333; font-weight: bold;">
                                    <input class="flipswitch" type="checkbox" id="checkSelectCertificado_<?=$w;?>" name="checkSelectCertificado_<?=$w;?>" onclick="ValidarCheckedCertificado(<?=$w;?>)">
                                    </span>
                                </div> 
                            </td>
                            <td><input type="text" class="form-control2" id="txt_NroCertificado_<?=$w;?>" value="" disabled="disabled" style="background-color: #e1e7e8; font-weight: bold;"></div> 
                            <td><input type="text" class="form-control2" style="font-weight: bold; width: 100px;" maxlength="3" id="txt_NotaPractica_<?=$w;?>" value=""></div> 
                            <td><input type="text" class="form-control2" style="font-weight: bold; width: 100px;" maxlength="3" id="txt_NotaForo_<?=$w;?>" value=""></div> 
                            <td><input type="text" class="form-control2" style="font-weight: bold; width: 100px;" maxlength="3" id="txt_ExamenFinal_<?=$w;?>" value=""></div> 
                            <td><input type="text" class="form-control2" style="font-weight: bold; width: 100px;" maxlength="3" id="txt_PromedioFinal_<?=$w;?>" value=""></div> 
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
