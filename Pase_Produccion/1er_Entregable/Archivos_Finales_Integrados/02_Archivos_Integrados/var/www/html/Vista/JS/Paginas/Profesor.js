$(document).ready(function() {
    $("#h4Subtitulos").text("DOCENTES");
    //comboCursos();
    jsComboAnioCursos();
    jsComboFiltroAnioCursos();
    listado();
    validateForms();
});

$("#DivContenido").addClass('container');
$(".nav.nav-pills .nav-item .nav-link").removeClass('active');

//function Listar Anios
var jsComboAnioCursos = () => {
    $("#PROF_Anio").empty();
    $.post("../app/controller/profesor.php", {
        op: 'AniosCursos',
    }, function(data) {
        var datos = JSON.parse(data);
        $("#PROF_Anio").append("<option value=''>Seleccione</option>");
        datos.forEach(elemento => {
            var htmlok = `<option value="${elemento[0]}">${elemento[1]}</option>`;
            $("#PROF_Anio").append(htmlok);
        });
    });
}

//function Listar Anios
var jsComboFiltroAnioCursos = () => {
    $("#FILTRO_AnioCurso").empty();
    $.post("../app/controller/profesor.php", {
        op: 'AniosCursos',
    }, function(data) {
        var datos = JSON.parse(data);
        $("#FILTRO_AnioCurso").append("<option value=''>Seleccione</option>");
        datos.forEach(elemento => {
            var htmlok = `<option value="${elemento[0]}">${elemento[1]}</option>`;
            $("#FILTRO_AnioCurso").append(htmlok);
        });
    });
}

//function Listar Cursos x Anio
var jsMostrarCursosByAnio = () => {
    $("#PROF_curso").empty();
    var cboAnio = $("#PROF_Anio").val();
    $.post("../app/controller/profesor.php", {
        op: 'VerCursosByAnio',
        anio: cboAnio
    }, function(data) {
        var datos = JSON.parse(data);
        $("#PROF_curso").append("<option value=''>Seleccione</option>");
        datos.forEach(elemento => {
            var htmlok = `<option value="${elemento[0]}">${elemento[1]}</option>`;
            $("#PROF_curso").append(htmlok);
        });
    });
}

//function Listar Cursos x Anio
var jsFiltroMostrarCursosByAnio = () => {
    $("#FILTRO_curso").empty();
    var cboAnio = $("#FILTRO_AnioCurso").val();
    $.post("../app/controller/profesor.php", {
        op: 'VerCursosByAnio',
        anio: cboAnio
    }, function(data) {
        var datos = JSON.parse(data);
        $("#FILTRO_curso").append("<option value=''>Seleccione</option>");
        datos.forEach(elemento => {
            var htmlok = `<option value="${elemento[0]}">${elemento[1]}</option>`;
            $("#FILTRO_curso").append(htmlok);
        });
        table.ajax.reload();
    });
}

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

var action = 'registrar';

$("#formProfesor").on("submit", function(e) {
    e.preventDefault();
    if (!$(this).valid()) {
        return;
    }
    if (action == 'registrar')
        fasch_block(true, "Procesando registros");
    $.ajax({
        type: "POST",
        url: "../app/controller/profesor.php",
        data: $(this).serialize() + '&op=' + action,
        success: function(response) {
            var datos = JSON.parse(response);
            if (action == 'actualizar') {
                fasch_info(datos['data'], 'md');
                if (datos['error'] == false) {
                    clearFormProfesor();
                    table.ajax.reload();
                }
                return;
            }
            fasch_info(datos['data'], 'lg');
            if (datos['error'] == false) {
                clearFormProfesor();
                table.ajax.reload();
            }
            fasch_block(false);
        }
    });
});

var table;

var listado = () => {
    table = $("#tbl_profesor").DataTable({
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'csv', 'copy', 'print'],
        "ajax": {
            "method": "POST",
            "url": "../app/controller/profesor.php",
            "data": {
                op: 'listado',
                FILTRO_curso: function() {
                    return $('#FILTRO_curso').val()
                },
            }
        },
        "language": idioma_espanol,
    });
}

var fnEliminarProfesor = (id) => {
    fasch_confirm(MSGEliminar, function(rpta) {
        if (rpta) {
            $.post("../app/controller/profesor.php", {
                op: 'eliminar',
                PROF_id: id,
            }, function(data) {
                table.ajax.reload();
            });
        }
    });
}

var fnEditarProfesor = (id, detalle) => {
    var datos = detalle.split('||');
    $("#PROF_dni").val(datos[0]);
    $("#PROF_nombres").val(datos[1]);
    $("#PROF_apePaterno").val(datos[2]);
    $("#PROF_apeMaterno").val(datos[3]);
    $("#PROF_email").val(datos[4]);
    $("#PROF_celular").val(datos[5]);
    $("#PROF_usuario").val(datos[6]);
    $("#PROF_curso").val(datos[7]);
    $("#PROF_tipoDoc").val(datos[8]);
    fnChangePROFTipoDoc(datos[8]);
    var inputForm = ['PROF_tipoDoc', 'PROF_dni', 'PROF_curso'];
    for (var i = 0; i < inputForm.length; i++) {
        $('#' + inputForm[i]).attr("style", "pointer-events: none;");
        $('#' + inputForm[i]).css("background", "#eee");
    }
    $("#PROF_dni + div button").attr("disabled", true);
    $("#formProfesor div:last button[type=submit]").text('Actualizar');
    $("#PROF_id").val(id);
    action = 'actualizar';
}

var clearFormProfesor = () => {
    $("#PROF_dni").focus();
    $("#PROF_dni").attr('maxlength', 8);
    $("#formProfesor").data('validator').resetForm();
    $("#formProfesor")[0].reset();
    var inputFormA = ['PROF_tipoDoc', 'PROF_dni', 'PROF_curso', 'PROF_email', 'PROF_celular'];
    inputFormA.forEach(element => {
        $('#' + element).attr("style", "pointer-events: auto;");
        $('#' + element).css("background", "white");
    });
    var inputFormB = ['PROF_nombres', 'PROF_apePaterno', 'PROF_apeMaterno'];
    inputFormB.forEach(element => {
        $('#' + element).attr('readonly', true);
    });
    $("#PROF_dni + div button").attr("disabled", false);
    $("#formProfesor div:last button[type=submit]").text('Registrar');
    $("#PROF_id").val(null);
    action = 'registrar';
}

$("#PROF_nombres, #PROF_apePaterno, #PROF_apeMaterno").on("change", function() {
    var usuario = '';
    var nombres = $("#PROF_nombres").val().trim();
    var apePate = $("#PROF_apePaterno").val().trim();
    var apeMate = $("#PROF_apeMaterno").val().trim();
    if (nombres != '' && apePate != '' && apeMate != '') {
        usuario = nombres.substring(0, 1) + apePate + apeMate.substring(0, 1);
    }
    $("#PROF_usuario").val(usuario.toLowerCase());
});

$("#PROF_dni + div button").click(function(e) {
    e.preventDefault();
    $.post("../app/controller/profesor.php", {
        op: 'buscar_dni',
        dni: $("#PROF_dni").val(),
        tipo_doc: $("#PROF_tipoDoc").val(),
    }, function(data) {
        var datos = JSON.parse(data);
        if (datos['error'] == false) {
            datos = datos['data'];
            var inputForm = ['PROF_tipoDoc', 'PROF_dni'];
            $("#PROF_dni").val(datos[0]);
            $("#PROF_nombres").val(datos[1]);
            $("#PROF_apePaterno").val(datos[2]);
            $("#PROF_apeMaterno").val(datos[3]);
            if (datos.length != 4) {
                inputForm.push('PROF_email');
                inputForm.push('PROF_celular');
                $("#PROF_email").val(datos[4]);
                $("#PROF_celular").val(datos[5]);
                $("#PROF_usuario").val(datos[6]);
            } else {
                var usuario = datos[1].substring(0, 1) + datos[2] + datos[3].substring(0, 1);
                $("#PROF_usuario").val(usuario.toLowerCase());
            }
            inputForm.forEach(element => {
                $('#' + element).css({
                    "background": "#eee",
                    "pointer-events": "none"
                });
            });
            $("#formProfesor").data('validator').resetForm();
            $("#PROF_dni").blur();
        } else {
            var dni = $("#PROF_dni").val();
            var tipo_doc = $("#PROF_tipoDoc").val();
            if (tipo_doc == 'DNI') {
                serviceDNI(dni);
            } else {
                var inputFormA = ['PROF_tipoDoc', 'PROF_dni'];
                inputFormA.forEach(element => {
                    $('#' + element).css({
                        "background": "#eee",
                        "pointer-events": "none"
                    });
                });
                var inputFormB = ['PROF_nombres', 'PROF_apePaterno', 'PROF_apeMaterno'];
                inputFormB.forEach(element => {
                    $('#' + element).attr('readonly', false);
                });
            }

        }
    });
});

$("#PROF_tipoDoc").change(function(e) {
    e.preventDefault();
    fnChangePROFTipoDoc(this.value);
});

var fnChangePROFTipoDoc = (tipoDoc) => {
    var inputFormB = ['PROF_nombres', 'PROF_apePaterno', 'PROF_apeMaterno'];
    switch (tipoDoc) {
        case 'DNI':
            var dni = $("#PROF_dni").val();
            var dni = dni.substring(0, dni.length - 1);
            $("#PROF_dni").val(dni);
            $("#PROF_dni").attr('maxlength', 8);
            inputFormB.forEach(element => {
                $('#' + element).attr('readonly', true);
            });
            break;
        case 'CDE':
            $("#PROF_dni").attr('maxlength', 9);
            inputFormB.forEach(element => {
                $('#' + element).attr('readonly', false);
            });
            break;
    }
}

var serviceDNI = (numeroDNI) => {
    $.get("https://www.sbn.gob.pe/informacionpublica/server-ip/public/ValidarDniSID/" + numeroDNI, function(response) {
        // console.log(response['data']); 
        if (response['data']['deResultado'] != "Consulta realizada correctamente") {
            fasch_info("DNI no encontrado");
        } else {
            var datos = response['data']['datosPersona'];
            // var datos = JSON.parse(JSON.stringify(response.result));
            $("#PROF_dni").val(numeroDNI);
            $("#PROF_nombres").val(datos['prenombres']);
            $("#PROF_apePaterno").val(datos['apPrimer']);
            $("#PROF_apeMaterno").val(datos['apSegundo']);
            var usuario = datos['prenombres'].substring(0, 1) + datos['apPrimer'] + datos['apSegundo'].substring(0, 1);
            $("#PROF_usuario").val(usuario.toLowerCase());
            $('#PROF_dni').css({
                "background": "#eee",
                "pointer-events": "none"
            });
            $("#formProfesor").data('validator').resetForm();
            $("#PROF_dni").blur();
        }
    });
}

$("#FILTRO_curso").change(function() {
    table.ajax.reload();
});

/*  VALIDACIONES DE FORMULARIOS */

var validateForms = () => {
    $.validator.setDefaults({
        debug: true,
        success: "valid"
    });

    $("#formProfesor").validate({
        rules: {
            PROF_tipoDoc: { required: true, maxlength: 5 },
            PROF_dni: { required: true, number: true },
            PROF_nombres: { required: true, maxlength: 50 },
            PROF_apePaterno: { required: true, maxlength: 50 },
            PROF_apeMaterno: { required: true, maxlength: 50 },
            PROF_email: { required: true, email: true, maxlength: 60 },
            PROF_celular: { required: true, number: true, maxlength: 9 },
            PROF_usuario: { required: true, maxlength: 60 },
            PROF_curso: { required: true, number: true, maxlength: 20 },
        },
        errorPlacement: function(error, element) {
            if (element[0]['id'] == 'PROF_dni') {
                error.insertAfter($("#PROF_dni").parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
}