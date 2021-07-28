$(document).ready(function() {
    $("#h4Subtitulos").text("CERTIFICADOS");
    combo_anio_moodle();
});

$("#DivContenido").addClass('container');
$(".nav.nav-pills .nav-item .nav-link").removeClass('active');


var combo_anio_moodle = () => {
    $("#CERT_Anio").empty();
    $.post("../app/controller/reporte_moodle.php", {
        op: 'combo_anios'
    }, function(data) {
        var datos = JSON.parse(data);
        $("#CERT_Anio").append("<option value='' selected>Seleccione</option>");
        datos.forEach(element => {
            var html = `<option value="${element[0]}">${element[1]}</option>`;
            $("#CERT_Anio").append(html);
        });
    });
}

var combo_curso_by_anio_moodle = () => {
    var anio = $("#CERT_Anio").val();
    if (anio == '') {
        SwalError('Oops...', 'Sellecione Año!');
    } else {
        $("#CERT_Cursos").empty();
        $.post("../app/controller/reporte_moodle.php", {
            op: 'combo_cursos_by_anio',
            cboanio: anio
        }, function(data) {
            var datos = JSON.parse(data);
            $("#CERT_Cursos").append("<option value='' selected>Seleccione</option>");
            datos.forEach(element => {
                var html = `<option value="${element[0]}">${element[1]}</option>`;
                $("#CERT_Cursos").append(html);
            });
        });
    }
}



function Buscar_AlumnosCertificacion() {

    var mdl_Anio = $("#CERT_Anio").val();
    var mdl_IdCurso = $("#CERT_Cursos").val();

    if (mdl_Anio == '') {
        SwalError('Oops...', 'Sellecione Año!');
        return false;
    } else if (mdl_IdCurso == '') {
        SwalError('Oops...', 'Sellecione Curso!');
        return false;
    } else {

        $("#div_resultadoCertificacion").html('Cargando...');
        $.post("../app/controller/certificacion_moodle.php", {
            op: 'Buscar_Alumnos_Certificacion_By_Curso',
            mdl_Anio: mdl_Anio,
            mdl_IdCurso: mdl_IdCurso,
        }, function(data) {
            console.log("respuesta->" + data);
            $.get("../servicios/ReporteCursos/certificacion_cursos_lista.php", function(response) {
                $("#div_resultadoCertificacion").html(response);
            });
        });

    }

}




function FormRegAlumnosCertificacion() {

    var mdl_Anio = $("#CERT_Anio").val();
    var mdl_IdCurso = $("#CERT_Cursos").val();

    if (mdl_Anio == '') {
        SwalError('Oops...', 'Sellecione Año!');
        return false;
    } else if (mdl_IdCurso == '') {
        SwalError('Oops...', 'Sellecione Curso!');
        return false;
    } else {

        $.post("../app/controller/certificacion_moodle.php", {
            op: 'Extraer_Lista_Alumnos_Moodle_Detallado',
            mdl_Anio: mdl_Anio,
            mdl_IdCurso: mdl_IdCurso,
        }, function(data) {

            console.log(data);
            if (data == true) {
                $('#ModalForm_Certificacion').modal({ backdrop: 'static', keyboard: false });
                $("#ModalBody_Certificacion").html('Cargando...');
                $.get("../servicios/ReporteCursos/certificacion_cursos_form.php", function(response) {
                    $("#ModalBody_Certificacion").html(response);
                    formatoTablaFormulario();
                });
            } else {
                $("#ModalBody_Certificacion").html('Sin Registros');
                SwalError('Oops...', 'No existen practicas realizadas en este curso!');
            }

        });

    }

}


function ValidarCheckedCertificado(fila) {

    var checkSelectCertificado = $("input[name='checkSelectCertificado_" + fila + "']:checked").val();
    console.log(checkSelectCertificado);

    if (!(typeof checkSelectCertificado === 'undefined')) { //NO
        $("#txt_NroCertificado_" + fila).prop('disabled', false);
        $("#txt_NroCertificado_" + fila).css({ 'background-color': '#ffd78b' });
        $("#txt_NroCertificado_" + fila).val('');
    } else { //ACTIVO
        $("#txt_NroCertificado_" + fila).prop('disabled', true);
        $("#txt_NroCertificado_" + fila).css({ 'background-color': '#c0c8d0' });
        $("#txt_NroCertificado_" + fila).val('');
    }

}


function btn_Registrar_Certificados() {

    var xTotReg = $("#txt_total_registros").val();

    var mdl_Anio = $("#CERT_Anio").val();
    var mdl_IdCurso = $("#CERT_Cursos").val();
    var txtCursoDescLarga = $("#txtCursoDescLarga").val();
    var txtCursoDescCorta = $("#txtCursoDescCorta").val();
    var txtCodCategoria_Moodle = $("#txtCodCategoria_Moodle").val();
    var txtDescripCategoria_Moodle = $("#txtDescripCategoria_Moodle").val();
    var txtCursoFechaIni = $("#txtCursoFechaIni").val();
    var txtCursoFechaFin = $("#txtCursoFechaFin").val();
    var txtHoraLectiva = $("#txtHoraLectiva").val();
    var txtModalidad = $("#txtModalidad").val();
    var txtNroEvento = $("#txtNroEvento").val();
    var txtCodigoPOI = $("#txtCodigoPOI").val();

    if (xTotReg > 0) {

        var ItemsMDLUSERID = new Array();
        var ItemsAPEPAT = new Array();
        var ItemsAPEMAT = new Array();
        var ItemsNOMBRES = new Array();
        var ItemsDNI = new Array();
        var ItemsENTIDAD = new Array();
        var ItemsNIVELGOBIERNO = new Array();
        var ItemsRUBRO = new Array();
        var ItemsDEPA = new Array();
        var ItemsPROV = new Array();
        var ItemsDIST = new Array();
        var ItemsGRADOACADEMICO = new Array();
        var ItemsPROFESION = new Array();
        var ItemsCARGO = new Array();
        var ItemsTIPOCONTRATO = new Array();
        var ItemsAREALABORA = new Array();
        var ItemsCONCERTIFICADO = new Array();
        var ItemsNROCERTIFICADO = new Array();

        var Acum_ItemsMDLUSERID = '';
        var Acum_ItemsAPEPAT = '';
        var Acum_ItemsAPEMAT = '';
        var Acum_ItemsNOMBRES = '';
        var Acum_ItemsDNI = '';
        var Acum_ItemsENTIDAD = '';
        var Acum_ItemsNIVELGOBIERNO = '';
        var Acum_ItemsRUBRO = '';
        var Acum_ItemsDEPA = '';
        var Acum_ItemsPROV = '';
        var Acum_ItemsDIST = '';
        var Acum_ItemsGRADOACADEMICO = '';
        var Acum_ItemsPROFESION = '';
        var Acum_ItemsCARGO = '';
        var Acum_ItemsTIPOCONTRATO = '';
        var Acum_ItemsAREALABORA = '';
        var Acum_ItemsCONCERTIFICADO = '';
        var Acum_ItemsNROCERTIFICADO = '';


        for (k = 1; k <= xTotReg; k++) {

            ItemsMDLUSERID[k] = $("#txt_mdlUserID_" + k).val();
            ItemsAPEPAT[k] = $("#txtCert_ApePatAlumno_" + k).val();
            ItemsAPEMAT[k] = $("#txtCert_ApeMatAlumno_" + k).val();
            ItemsNOMBRES[k] = $("#txtCert_NombresAlumno_" + k).val();
            ItemsDNI[k] = $("#txtCert_DNIAlumno_" + k).val();
            ItemsENTIDAD[k] = $("#txtCert_EntidadAlumno_" + k).val();
            ItemsNIVELGOBIERNO[k] = $("#txtCert_NivelGobAlumno_" + k).val();
            ItemsRUBRO[k] = $("#txtCert_RubroAlumno_" + k).val();
            ItemsDEPA[k] = $("#txtCert_DepaAlumno_" + k).val();
            ItemsPROV[k] = $("#txtCert_ProvAlumno_" + k).val();
            ItemsDIST[k] = $("#txtCert_DistAlumno_" + k).val();
            ItemsGRADOACADEMICO[k] = $("#txtCert_GradoAcademicoAlumno_" + k).val();
            ItemsPROFESION[k] = $("#txtCert_ProfesionAlumno_" + k).val();
            ItemsCARGO[k] = $("#txtCert_CargoAlumno_" + k).val();
            ItemsTIPOCONTRATO[k] = $("#txtCert_TipoContratoAlumno_" + k).val();
            ItemsAREALABORA[k] = $("#txtCert_AreaLaboraAlumno_" + k).val();

            if ($("input[name=checkSelectCertificado_" + k + "]:checkbox").is(":checked")) {
                ItemsCONCERTIFICADO[k] = '1';
            } else {
                ItemsCONCERTIFICADO[k] = '0';
            }
            ItemsNROCERTIFICADO[k] = $("#txt_NroCertificado_" + k).val();

            Acum_ItemsMDLUSERID = Acum_ItemsMDLUSERID + ',' + ItemsMDLUSERID[k];
            Acum_ItemsAPEPAT = Acum_ItemsAPEPAT + ',' + ItemsAPEPAT[k];
            Acum_ItemsAPEMAT = Acum_ItemsAPEMAT + ',' + ItemsAPEMAT[k];
            Acum_ItemsNOMBRES = Acum_ItemsNOMBRES + ',' + ItemsNOMBRES[k];
            Acum_ItemsDNI = Acum_ItemsDNI + ',' + ItemsDNI[k];
            Acum_ItemsENTIDAD = Acum_ItemsENTIDAD + ',' + ItemsENTIDAD[k];
            Acum_ItemsNIVELGOBIERNO = Acum_ItemsNIVELGOBIERNO + ',' + ItemsNIVELGOBIERNO[k];
            Acum_ItemsRUBRO = Acum_ItemsRUBRO + ',' + ItemsRUBRO[k];
            Acum_ItemsDEPA = Acum_ItemsDEPA + ',' + ItemsDEPA[k];
            Acum_ItemsPROV = Acum_ItemsPROV + ',' + ItemsPROV[k];
            Acum_ItemsDIST = Acum_ItemsDIST + ',' + ItemsDIST[k];
            Acum_ItemsGRADOACADEMICO = Acum_ItemsGRADOACADEMICO + ',' + ItemsGRADOACADEMICO[k];
            Acum_ItemsPROFESION = Acum_ItemsPROFESION + ',' + ItemsPROFESION[k];
            Acum_ItemsCARGO = Acum_ItemsCARGO + ',' + ItemsCARGO[k];
            Acum_ItemsTIPOCONTRATO = Acum_ItemsTIPOCONTRATO + ',' + ItemsTIPOCONTRATO[k];
            Acum_ItemsAREALABORA = Acum_ItemsAREALABORA + ',' + ItemsAREALABORA[k];
            Acum_ItemsCONCERTIFICADO = Acum_ItemsCONCERTIFICADO + ',' + ItemsCONCERTIFICADO[k];
            Acum_ItemsNROCERTIFICADO = Acum_ItemsNROCERTIFICADO + ',' + ItemsNROCERTIFICADO[k];

        }

        var new_ItemsMDLUSERID = String(Acum_ItemsMDLUSERID).substring(1, String(Acum_ItemsMDLUSERID).length);
        var new_ItemsAPEPAT = String(Acum_ItemsAPEPAT).substring(1, String(Acum_ItemsAPEPAT).length);
        var new_ItemsAPEMAT = String(Acum_ItemsAPEMAT).substring(1, String(Acum_ItemsAPEMAT).length);
        var new_ItemsNOMBRES = String(Acum_ItemsNOMBRES).substring(1, String(Acum_ItemsNOMBRES).length);
        var new_ItemsDNI = String(Acum_ItemsDNI).substring(1, String(Acum_ItemsDNI).length);
        var new_ItemsENTIDAD = String(Acum_ItemsENTIDAD).substring(1, String(Acum_ItemsENTIDAD).length);
        var new_ItemsNIVELGOBIERNO = String(Acum_ItemsNIVELGOBIERNO).substring(1, String(Acum_ItemsNIVELGOBIERNO).length);
        var new_ItemsRUBRO = String(Acum_ItemsRUBRO).substring(1, String(Acum_ItemsRUBRO).length);
        var new_ItemsDEPA = String(Acum_ItemsDEPA).substring(1, String(Acum_ItemsDEPA).length);
        var new_ItemsPROV = String(Acum_ItemsPROV).substring(1, String(Acum_ItemsPROV).length);
        var new_ItemsDIST = String(Acum_ItemsDIST).substring(1, String(Acum_ItemsDIST).length);
        var new_ItemsGRADOACADEMICO = String(Acum_ItemsGRADOACADEMICO).substring(1, String(Acum_ItemsGRADOACADEMICO).length);
        var new_ItemsPROFESION = String(Acum_ItemsPROFESION).substring(1, String(Acum_ItemsPROFESION).length);
        var new_ItemsCARGO = String(Acum_ItemsCARGO).substring(1, String(Acum_ItemsCARGO).length);
        var new_ItemsTIPOCONTRATO = String(Acum_ItemsTIPOCONTRATO).substring(1, String(Acum_ItemsTIPOCONTRATO).length);
        var new_ItemsAREALABORA = String(Acum_ItemsAREALABORA).substring(1, String(Acum_ItemsAREALABORA).length);
        var new_ItemsCONCERTIFICADO = String(Acum_ItemsCONCERTIFICADO).substring(1, String(Acum_ItemsCONCERTIFICADO).length);
        var new_ItemsNROCERTIFICADO = String(Acum_ItemsNROCERTIFICADO).substring(1, String(Acum_ItemsNROCERTIFICADO).length);


        $.post("../app/controller/certificacion_moodle.php", {
            op: 'Registrar_Datos_Certificacion',
            mdl_Anio: mdl_Anio,
            mdl_IdCurso: mdl_IdCurso,
            txtCursoDescLarga: txtCursoDescLarga,
            txtCursoDescCorta: txtCursoDescCorta,
            txtCodCategoria_Moodle: txtCodCategoria_Moodle,
            txtDescripCategoria_Moodle: txtDescripCategoria_Moodle,
            txtCursoFechaIni: txtCursoFechaIni,
            txtCursoFechaFin: txtCursoFechaFin,
            txtHoraLectiva: txtHoraLectiva,
            txtModalidad: txtModalidad,
            txtNroEvento: txtNroEvento,
            txtCodigoPOI: txtCodigoPOI,
            new_ItemsMDLUSERID: new_ItemsMDLUSERID,
            new_ItemsAPEPAT: new_ItemsAPEPAT,
            new_ItemsAPEMAT: new_ItemsAPEMAT,
            new_ItemsNOMBRES: new_ItemsNOMBRES,
            new_ItemsDNI: new_ItemsDNI,
            new_ItemsENTIDAD: new_ItemsENTIDAD,
            new_ItemsNIVELGOBIERNO: new_ItemsNIVELGOBIERNO,
            new_ItemsRUBRO: new_ItemsRUBRO,
            new_ItemsDEPA: new_ItemsDEPA,
            new_ItemsPROV: new_ItemsPROV,
            new_ItemsDIST: new_ItemsDIST,
            new_ItemsGRADOACADEMICO: new_ItemsGRADOACADEMICO,
            new_ItemsPROFESION: new_ItemsPROFESION,
            new_ItemsCARGO: new_ItemsCARGO,
            new_ItemsTIPOCONTRATO: new_ItemsTIPOCONTRATO,
            new_ItemsAREALABORA: new_ItemsAREALABORA,
            new_ItemsCONCERTIFICADO: new_ItemsCONCERTIFICADO,
            new_ItemsNROCERTIFICADO: new_ItemsNROCERTIFICADO
        }, function(data) {
            if (data == true) {
                Buscar_AlumnosCertificacion();
                $("#ModalForm_Certificacion").modal('hide');
                SwalOK('Felicitaciones', 'Se registró correctamente las certificados.');
            } else {
                SwalError('Oops...', 'No se pudo registrar los certificados');
            }
        });

    } else {
        SwalError('Oops...', 'No existen alumnos matriculados!');
    }

}

function LimpiarCertificacion(xCodigoCursoMdle) {

    if (confirm('Confirma limpiar los registro del certificado')) {
        $.post("../app/controller/certificacion_moodle.php", {
            op: 'Limpiar_Registros_Certificacion',
            xCodigoCursoMdle: xCodigoCursoMdle
        }, function(data) {
            if (data == true) {
                Buscar_AlumnosCertificacion();
                SwalOK('¡Exito!', 'Se elimino registros correctamente.');
            } else {
                SwalError('Error!', 'No se pudo eliminar registros');
            }
        });
    }

}


var SwalError = (title_, text_) => {
    return Swal.fire({
        icon: 'error',
        title: title_,
        text: text_,
    });
}

var SwalOK = (title_, text_) => {
    return Swal.fire({
        icon: 'success',
        title: title_,
        text: text_,
    });
}


function formatoTablaFormulario() {

    var tablaCurso = $("#Table_FormCertificadoCurso").DataTable({
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
        dom: 'frtip',
        "order": [
            [1, "asc"],
            [2, "asc"],
            [3, "asc"]
        ]
    });

}