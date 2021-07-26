$(document).ready(function() {
    //combo_curso_moodle();
    llenarComboAnio_moodle();
    validateForms();
    $("#h4Subtitulos").text("REPORTES");
});

$("#DivContenido").addClass('container');
$(".nav.nav-pills .nav-item .nav-link").removeClass('active');
$(".nav.nav-pills .nav-item .nav-link:last").addClass('active');

/*
var combo_curso_moodle = () => {
    $.post("../app/controller/reporte_moodle.php", {
        op: 'combo_cursos'
    }, function(data) {
        var datos = JSON.parse(data);
        datos.forEach(element => {
            var html = `<option value="${element[0]}">${element[1]}</option>`;
            $(".combo_cursos_moodle").append(html);
        });
    });
}
*/

var llenarComboAnio_moodle = () => {
    $.post("../app/controller/reporte_moodle.php", {
        op: 'combo_anios'
    }, function(data) {
        var datos = JSON.parse(data);
        $(".combo_anios_moodle").append("<option value='' selected>Seleccione</option>");
        datos.forEach(element => {
            var html = `<option value="${element[0]}">${element[1]}</option>`;
            $(".combo_anios_moodle").append(html);
        });
    });
}

var llenarComboCursoByAnio_moodle = (cboAnio, cboCurso) => {
    var anio = $("#" + cboAnio + "").val();
    if (anio == '') {
        SwalError('Oops...', 'Sellecione Año!');
    } else {
        $("#" + cboCurso + "").empty();
        $.post("../app/controller/reporte_moodle.php", {
            op: 'combo_cursos_by_anio',
            cboanio: anio
        }, function(data) {
            var datos = JSON.parse(data);
            $("#" + cboCurso + "").append("<option value='' selected>Seleccione</option>");
            datos.forEach(element => {
                var html = `<option value="${element[0]}">${element[1]}</option>`;
                $("#" + cboCurso + "").append(html);
            });
        });
    }
}

//============================================
//REPORTE 01 - ENTIDADES
//============================================

var reporteExcel_Entidades = () => {

    if (!validOnlyRequired('cboAnio_R1')) {
        return;
    }
    if (!validOnlyRequired('cboCursos_R1')) {
        return;
    }
    $.post("../app/controller/reporte.php", {
        op: 'reporte_cursos_01',
        id_curso: $("#cboCursos_R1").val()
    }, function(data) {
        if (data == true) {
            window.location.href = '../servicios/ReporteCursos/cursos_excel_01.php';
        } else {
            SwalError('Oops...', 'No se encontraron inscritos en este curso!');
        }
    });
}

//============================================
//REPORTE 02 - CUADRO DE RESULTADOS
//============================================

var reporteExcel_CuadroResultados = () => {
    if (!validOnlyRequired('cboAnio_R2')) {
        return;
    }
    if (!validOnlyRequired('cboCursos_R2')) {
        return;
    }
    $.post("../app/controller/reporte.php", {
        op: 'reporte_cursos_02',
        id_curso: $("#cboCursos_R2").val()
    }, function(data) {
        if (data == true) {
            window.location.href = '../servicios/ReporteCursos/cursos_excel_02.php';
        } else {
            SwalError('Oops...', 'Aun no hay participantes en este curso!');
        }
    });
}


//============================================
//REPORTE 03 - ALUMNOS 
//============================================

var reporteExcel_Alumnos = () => {
    if (!validOnlyRequired('cboAnio_R3')) {
        return;
    }
    if (!validOnlyRequired('cboCursos_R3')) {
        return;
    }
    $.post("../app/controller/reporte_moodle.php", {
        op: 'reporte_usuarios',
        id_curso: $("#cboCursos_R3").val()
    }, function(data) {
        if (data == true) {
            window.location.href = '../servicios/ReporteCursos/alumnos_excel.php';
        } else {
            SwalError('Oops...', 'No existen alumnos registradoss!');
        }
    });
}

//============================================
//REPORTE 04 - ENTIDADES INSCRITOS 
//============================================

var reporteExcel_EntidadesIncritos = () => {
    if (!validOnlyRequired('cboAnio_R4')) {
        return;
    }
    if (!validOnlyRequired('cboCursos_R4')) {
        return;
    }
    $.post("../app/controller/reporte_moodle.php", {
        op: 'reporte_entidades_inscritas',
        id_curso: $("#cboCursos_R4").val(),
    }, function(data) {
        if (data == true) {
            window.location.href = '../servicios/ReporteCursos/cursos_entidad_excel.php';
        } else {
            SwalError('Oops...', 'No existen entidades inscritas en este curso!');
        }
    });
}

//============================================
//REPORTE 05 - ALUMNOS INSCRITOS 
//============================================

var reporteExcel_AlumnosIncritos = () => {
    if (!validOnlyRequired('cboAnio_R5')) {
        return;
    }
    if (!validOnlyRequired('cboCursos_R5')) {
        return;
    }
    $.post("../app/controller/reporte_moodle.php", {
        op: 'reporte_alumnos_inscritos',
        id_curso: $("#cboCursos_R5").val(),
    }, function(data) {
        if (data == true) {
            window.location.href = '../servicios/ReporteCursos/cursos_alumno_excel.php';
        } else {
            SwalError('Oops...', 'No existen alumnos inscritos en este curso!');
        }
    });
}

//============================================
//REPORTE 06 - NOTAS
//============================================

var reporteExcel_Notas = () => {
    if (!validOnlyRequired('cboAnio_R6')) {
        return;
    }
    if (!validOnlyRequired('cboCursos_R6')) {
        return;
    }
    $.post("../app/controller/reporte_moodle.php", {
        op: 'reporte_curso_notas',
        id_curso: $("#cboCursos_R6").val(),
    }, function(data) {
        if (data == true) {
            window.location.href = '../servicios/ReporteCursos/cursos_notas_excel.php';
        } else {
            SwalError('Oops...', 'No existen practicas realizadas en este curso!');
        }
    });
}

//============================================
//REPORTE 07 - Alumnos que llevaron cursos
//============================================

$("#formAlumnosCursos").on("submit", function(e) {
    e.preventDefault();
    if (!$(this).valid()) {
        return;
    }
    var dataForm = $(this).serialize();
    dataForm += '&op=reporte_alumnos_cursos';
    $.ajax({
        type: "POST",
        url: "../app/controller/reporte_moodle.php",
        data: dataForm,
        success: function(response) {
            if (response == true) {
                window.location.href = '../servicios/ReporteCursos/cursos_alumcurs_excel.php';
            } else {
                SwalError('Oops...', 'No se encontraron registros entre estas fechas!');
            }
        }
    });

});

// $("#INSC_load").on("submit", function (e) {
//     e.preventDefault();
//     if (!$(this).valid()) {
//         return;
//     }
//     var datos = new FormData(this);
//     datos.append('op', 'migrar_data');
//     datos.append('ID_USER', sessionStorage.getItem("SES_ID_USUARIO"));
//     $.ajax({
//         type: "POST",
//         url: "../app/controller/reporte.php",
//         data: datos,
//         contentType: false,
//         cache: false,
//         processData: false,
//         success: function (response) {
//             var datos = JSON.parse(response);
//             if (datos['state']) {
//                 Swal.fire(
//                     'Exitoso!',
//                     datos['message'],
//                     'success'
//                 );
//             } else {
//                 Swal.fire(
//                     'Error!',
//                     datos['message'],
//                     'error'
//                 );
//             }
//             $("input[name=INSC_excel]").val('');
//         }
//     });
// });

/* MOODLE */


var comboCursos = () => {
    $.post("../app/controller/profesor.php", {
        op: 'cursos',
    }, function(data) {
        var datos = JSON.parse(data);
        datos.forEach(element => {
            var html = `<option value="${element[0]}">${element[1]}</option>`;
            $(".cursos").append(html);
        });
    });
}


/* ---------------- VALIDACIONES DE FORMULARIOS ---------------- */

var validateForms = () => {
    $.validator.setDefaults({
        debug: true,
        success: "valid"
    });

    $("#formAlumnosCursos").validate({
        rules: {
            REP_ALCU_filtro: {
                required: true,
            },
            REP_ALCU_desde: {
                required: true,
                date: true
            },
            REP_ALCU_hasta: {
                required: true,
                date: true
            },
        },
        messages: {
            REP_ALCU_filtro: {
                required: MSG_DATO_OBLIGATORIO
            },
            REP_ALCU_desde: {
                required: MSG_DATO_OBLIGATORIO,
                date: 'Por favor, escribe una fecha válida.'
            },
            REP_ALCU_hasta: {
                required: MSG_DATO_OBLIGATORIO,
                date: 'Por favor, escribe una fecha válida.'
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });

    $("#INSC_load").validate({
        rules: {
            INCS_cursos: {
                required: true
            },
            INSC_excel: {
                required: true
            },
        },
        messages: {
            INCS_cursos: {
                required: MSG_DATO_OBLIGATORIO
            },
            INSC_excel: {
                required: MSG_DATO_OBLIGATORIO
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });
}

var validOnlyRequired = (id) => {
    var response = true;
    $('#' + id + '-error').empty();
    if ($('#' + id).val() == null || $('#' + id).val() == '') {
        $('<label id="' + id + '-error" class="error" for="' + id + '">Campo obligatorio</label>').insertAfter('#' + id);
        response = false;
    }
    return response;
}

var SwalError = (title_, text_) => {
    return Swal.fire({
        icon: 'error',
        title: title_,
        text: text_,
    });
}