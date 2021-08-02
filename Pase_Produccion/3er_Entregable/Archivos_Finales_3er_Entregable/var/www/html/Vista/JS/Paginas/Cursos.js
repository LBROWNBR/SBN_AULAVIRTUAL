var ACCIONNUEVO = "0";
var ACCIONEDITAR = "1";
var ID_CURSO_MODAL = 0;
var NOMB_CURSO = "";

$(document).ready(function() {
    fnListarCursos();
    $("#txf_adjuntar_imagen").change(function() {
        var filename = $(this).val().split(/[\\|/]/).pop();
        $("#txf_adjuntar_imagen_lbl").html(filename);
        $("#TXT_VAL_IMAGEN").val(filename);

    });
    $("#ACCION_CUR").val(ACCIONNUEVO);
    $("#ID_USUARIO").val(1);
    $('#TXT_FECHA_INICIO').datepicker().datepicker("setDate", 'today');
    $('#TXT_FECHA_FIN').datepicker().datepicker("setDate", 'today');
    cargarValidacionesCursos();
    loadComboCategCurs();
    $("#CBO_PROCESO_CUR").change(function() {
        fnListarMatriculasXcurso(ID_CURSO_MODAL)
    });

    CKEDITOR.replace('TXA_DESCRIPCION', {
        height: 200
    });
});

$("#DivContenido").addClass('container');

var loadComboCategCurs = () => {
    $.post("../app/controller/reporte.php", {
        op: 'combo_categorias'
    }, function(data) {
        if (data) {
            // var datos = [[0, 'Prueba'], [1, 'Miscelania']];
            var datos = JSON.parse(data);
            var combo = '';
            for (var i = 0; i < datos.length; i++) {
                combo += '<option value="' + datos[i][0] + '">' + datos[i][1] + '</option>';
            }
            $("#TXT_CURSO_CATEGORIA").html(combo);
        }
    });
}

function fnListarMatriculasXcurso(VAL_ID_CURSO, VAL_NOMB_CURSO) {
    $('#DivMdlListmatriculados').modal('show');
    ID_CURSO_MODAL = VAL_ID_CURSO;
    NOMB_CURSO = VAL_NOMB_CURSO;
    $("#H5titulocurso").html(NOMB_CURSO);
    $.get("../servicios/Cursos/sel_matriculaXcurso.php?CBO_PROCESO=" + $("#CBO_PROCESO_CUR").val() + "&VAL_ID_CURSO=" + VAL_ID_CURSO, function(response) {
        //   var tablaDatos = $("#divTablamatricula");
        var filaHTML = "";
        filaHTML += "<table class='table table-striped' id='tblMatricula' name='tblMatricula'>";
        filaHTML += " <thead>";
        filaHTML += " <th class='table-primary'>FILA</th>";
        filaHTML += " <th class='ocultar'>ID</th>";
        filaHTML += " <th class='table-primary'>NOMBRE</th>";
        filaHTML += " <th class='table-primary'>APE PATERNO</th>";
        filaHTML += " <th class='table-primary'>APE MATERNO</th>";
        filaHTML += " <th class='table-primary'>DNI</th>";
        filaHTML += " <th class='table-primary'>PROFESION</th>";
        filaHTML += " <th class='table-primary'>GENERO</th>";
        filaHTML += " <th class='ocultar'>GRADO</th>";
        filaHTML += " <th class='table-primary'>CORREO</th>";
        filaHTML += " <th class='table-primary'>CELULAR</th>";
        filaHTML += " <th class='ocultar'> RUC</th>";
        filaHTML += " <th class='ocultar'>Nom. Entidiad</th>";
        filaHTML += " <th class='ocultar'>Departamento</th>";
        filaHTML += " <th class='ocultar'>Provincia</th>";
        filaHTML += " <th class='ocultar'>Distrito</th>";
        filaHTML += " <th class='ocultar'>Niv. Gobierno</th>";
        filaHTML += " <th class='ocultar'>Area</th>";
        filaHTML += " <th class='ocultar'>Cargo</th>";
        filaHTML += " <th class='ocultar'>Clasificacion</th>";
        filaHTML += " <th class='ocultar'>Modalidad</th>";
        filaHTML += " <th class='ocultar'>otra Modalidad</th>";
        filaHTML += " <th class='table-primary'>NRO. OFICIO</th>";
        filaHTML += " <th class='table-primary'>DOCUMENTO</th>";
        filaHTML += " <th class='ocultar'>Nom. Archivo</th>";
        filaHTML += " <th class='ocultar'>Ruta Archivo</th>";
        filaHTML += " <th class='ocultar'>NOM. CURSO</th>";
        filaHTML += "</thead>";
        filaHTML += "<tbody id='tbMatricula' name='tbMatricula'>";
        filaHTML += response;
        filaHTML += "</tbody>";
        filaHTML += "</table>";

        /* $("#tblMatricula").DataTable().destroy();
           //  tablaDatos.empty();
        $("#tbMatricula").html(response);*/
        $("#divTablamatriculacurso").html(filaHTML);

        fnPintarTabla("tblMatricula");

    });
};

function fnVerPDFcurso(ruta, nombre) {
    var Archivo = ruta + nombre;


    $.post("../app/controller/matricula.php", {
        op: 'pdf',
        archivo: Archivo,
    }, function(data) {
        if (data == true) {
            window.open("../servicios/pdf.php", "_blank");
        }
    });

}

function fnGrabarCursos() {

    if (!$("#frmCursos").valid()) {
        // alert("El formulario tiene inconsistencias que deben ser corregidas.");
        return;
    }
    if ($("#txf_adjuntar_imagen").val() != "") {
        var sizeByte = $("#txf_adjuntar_imagen")[0].files[0].size;
        var tipoarchivo = $("#txf_adjuntar_imagen")[0].files[0].type;
        // console.log(tipoarchivo);
        // console.log(sizeByte);
        if (tipoarchivo != "image/jpeg" && tipoarchivo != "image/png" && tipoarchivo != "image/gif") {
            //mensaje de error
            fasch_info("Debe seleccionar un archivo con formato de imagen.");
            return;
        }
        if (parseInt(sizeByte) > 7340032) {
            //mensaje de error
            fasch_info("El tamaño máximo de un archivo debe ser de 7 MB.");
            return;
        }
    }
    var parametros = $("#frmCursos").serialize();
    var URL_ACCION = "";
    //if ($("#ACCION_USU").val() =="0"){
    var arregloformulario = new FormData();

    CKEDITOR.instances['TXA_DESCRIPCION'].updateElement();

    arregloformulario.append('ACCION_CUR', $('#ACCION_CUR').prop('value'));
    arregloformulario.append('TXT_CURSO_CATEGORIA', $('#TXT_CURSO_CATEGORIA').prop('value'));
    arregloformulario.append('ID_CURSO', $('#ID_CURSO').prop('value'));
    arregloformulario.append('TXT_NOMBRE', $('#TXT_NOMBRE').prop('value'));
    arregloformulario.append('TXT_SHORT_NOMBRE', $('#TXT_SHORT_NOMBRE').prop('value'));
    arregloformulario.append('TXA_DESCRIPCION', $('#TXA_DESCRIPCION').prop('value'));
    arregloformulario.append('TXT_FECHA_INICIO', $('#TXT_FECHA_INICIO').prop('value'));
    arregloformulario.append('TXT_FECHA_FIN', $('#TXT_FECHA_FIN').prop('value'));
    arregloformulario.append('TXT_HORA_INICIO', $('#TXT_HORA_INICIO').prop('value'));
    arregloformulario.append('TXT_HORA_FIN', $('#TXT_HORA_FIN').prop('value'));
    arregloformulario.append('TXT_LUGAR', $('#TXT_LUGAR').prop('value'));
    arregloformulario.append('TXT_PUBLICO_DIRIGINDO', $('#TXT_PUBLICO_DIRIGINDO').prop('value'));
    arregloformulario.append('CBO_ESTADO', $('#CBO_ESTADO').prop('value'));
    arregloformulario.append('TXT_VAL_IMAGEN', $('#TXT_VAL_IMAGEN').prop('value'));
    arregloformulario.append('ID_USUARIO', $('#ID_USUARIO').prop('value'));

    arregloformulario.append('TXT_HORALECTIVA', $('#TXT_HORALECTIVA').prop('value'));
    arregloformulario.append('TXT_MODALIDAD', $('#TXT_MODALIDAD').prop('value'));
    arregloformulario.append('TXT_NUMEVENTO', $('#TXT_NUMEVENTO').prop('value'));
    arregloformulario.append('TXT_CODPOI', $('#TXT_CODPOI').prop('value'));
    arregloformulario.append('CBO_PUBLICAR', $('#CBO_PUBLICAR').prop('value'));

    arregloformulario.append('txf_adjuntar_imagen', $('#txf_adjuntar_imagen')[0].files[0]);


    URL_ACCION = "../servicios/Cursos/insert_cursos.php";

    // } if($("#ACCION_USU").val() =="1"){
    //   URL_ACCION="../servicios/Usuarios/update_Usuarios.php";

    // }
    //alert(parametros);

    $.ajax({
        type: "POST",
        url: URL_ACCION,
        contentType: false,
        data: arregloformulario,
        processData: false,
        cache: false,
        success: function(resultado) {
            fasch_info((resultado).trim());
            //     $("#errorphp").html(datos);
            fnListarCursos();
            fnhrefpagina('../Vista/ViewCursos.html');
            fnMostrarSubTitulo('CURSOS');
            /*
            if ((resultado).trim() == "actualizado") {
                fnlimpiacurso();
            }
            */
        }

    });

}

/*
function fnListarCursos() {
    $("#tbCursos").html("");
    $.get("../servicios/Cursos/get_Cursos.php", function(response) {
        // var tablaDatos = $("#divTablaCursos");
        // $("#tblCursos").DataTable().destroy();
        //       tablaDatos.empty();
        var filaHTML = "";
        filaHTML += "<table class='table table-striped' id='tblCursos' name='tblCursos'>";
        filaHTML += "<thead>";
        filaHTML += "<th class='table-primary'>FILA</th>";
        filaHTML += "<th class='table-primary'>ACCION</th>";
        filaHTML += "<th class='ocultar'>ID</th>";
        filaHTML += "<th class='table-primary'>NOMBRE</th>";
        filaHTML += "<th class='table-primary'>FECHA INCIO</th>";
        filaHTML += "<th class='table-primary'>FECHA FIN</th>";
        filaHTML += "<th class='table-primary'>HORA INICIO</th>";
        filaHTML += "<th class='table-primary'>HORA FIN</th>";
        filaHTML += "<th class='table-primary'>LUGAR</th>";
        filaHTML += "<th class='table-primary'>PUBLICO DIRIGIDO</th>";
        filaHTML += "<th class='ocultar'>ESTADO</th>";
        filaHTML += "<th class='ocultar'>NOMBRE IMAGEN</th>";
        filaHTML += "<th class='ocultar'>RUTA IMAGEN</th>";
        filaHTML += "<th class='ocultar'>NOMBRE CORTO</th>";
        filaHTML += "<th class='ocultar'>CATEGORIAS</th>";
        filaHTML += "<th class='ocultar'>DESCRIPCIÓN</th>";
        filaHTML += "</thead>";
        filaHTML += "<tbody id='tbCursos' name='tbCursos'>";
        filaHTML += response;
        filaHTML += "</tbody>";
        filaHTML += "</table>";
        $("#divTablaCursos").html(filaHTML);
        fnPintarTabla("tblCursos");

    });
};
*/


function fnListarCursos() {

    $('#tblCursos tbody').empty();
    $.get("../servicios/Cursos/get_Cursos.php", function(htmlTags) {
        //console.log(htmlTags);
        //$('#tblCursos tbody').append(htmlTags);
        $('#tblCursos > tbody:last-child').append(htmlTags);

        var tablaCurso = $("#tblCursos").DataTable({
            "pageLength": 10,
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
        tablaCurso.DataTable().ajax.reload();
    });
}

/*
function fnEditarCursos(id) {

    var tableHtml = $('#tblCursos');

    tableHtml.find('tbody > tr').each(function() {
        //   alert(id);
        if ($(this).find("td").eq(2).html() == id) {

            $("#ACCION_USU").val(ACCIONEDITAR);
            $("#ID_CURSO").val($(this).find("td").eq(2).html().trim());
            $("#TXT_NOMBRE").val($(this).find("td").eq(3).html().trim());
            $("#TXT_FECHA_INICIO").val($(this).find("td").eq(4).html().trim());
            $("#TXT_FECHA_FIN").val($(this).find("td").eq(5).html().trim());
            $("#TXT_HORA_INICIO").val($(this).find("td").eq(6).html().trim());
            $("#TXT_HORA_FIN").val($(this).find("td").eq(7).html().trim());
            $("#TXT_LUGAR").val($(this).find("td").eq(8).html().trim());
            $("#TXT_PUBLICO_DIRIGINDO").val($(this).find("td").eq(9).html().trim());
            $("#CBO_ESTADO").val($(this).find("td").eq(10).html().trim());
            $("#TXT_VAL_IMAGEN").val($(this).find("td").eq(11).html().trim());
            $("#txf_adjuntar_imagen_lbl").html($(this).find("td").eq(11).html().trim());
            $("#TXT_SHORT_NOMBRE").val($(this).find("td").eq(13).html().trim());
            $("#TXT_CURSO_CATEGORIA option:selected").removeAttr("selected");
            CKEDITOR.instances['TXA_DESCRIPCION'].destroy();
            $("#TXA_DESCRIPCION").val($(this).find("td").eq(15).html().trim());
            CKEDITOR.replace('TXA_DESCRIPCION', {
                height: 200
            });
            var categoria = $(this).find("td").eq(14).html().trim();
            $("#TXT_CURSO_CATEGORIA").val(categoria);
            $("#TXT_SHORT_NOMBRE").prop("disabled", true);
            $("#ACCION_CUR").val(ACCIONEDITAR);
        }
    });
};
*/


function fnEditarCursos(id) {
    $('#tblCursos').find('tbody > tr').each(function() {
        //   alert(id);
        if ($(this).find("td").eq(2).html() == id) {
            $("#ACCION_USU").val(ACCIONEDITAR);
            $("#ID_CURSO").val($(this).find("td").eq(2).html().trim());
            $("#TXT_NOMBRE").val($(this).find("td").eq(3).html().trim());
            $("#TXT_SHORT_NOMBRE").val($(this).find("td").eq(4).html().trim());
            $("#TXT_FECHA_INICIO").val($(this).find("td").eq(5).html().trim());
            $("#TXT_FECHA_FIN").val($(this).find("td").eq(6).html().trim());
            $("#TXT_HORA_INICIO").val($(this).find("td").eq(7).html().trim());
            $("#TXT_HORA_FIN").val($(this).find("td").eq(8).html().trim());
            $("#TXT_LUGAR").val($(this).find("td").eq(9).html().trim());
            $("#TXT_PUBLICO_DIRIGINDO").val($(this).find("td").eq(10).html().trim());
            $("#TXT_HORALECTIVA").val($(this).find("td").eq(11).html().trim());
            $("#TXT_MODALIDAD").val($(this).find("td").eq(12).html().trim());
            $("#TXT_NUMEVENTO").val($(this).find("td").eq(13).html().trim());
            $("#TXT_CODPOI").val($(this).find("td").eq(14).html().trim());
            $("#CBO_ESTADO").val($(this).find("td").eq(15).html().trim());
            $("#TXT_VAL_IMAGEN").val($(this).find("td").eq(16).html().trim());
            $("#txf_adjuntar_imagen_lbl").html($(this).find("td").eq(16).html().trim());
            $("#TXT_CURSO_CATEGORIA option:selected").removeAttr("selected");
            CKEDITOR.instances['TXA_DESCRIPCION'].destroy();
            $("#TXA_DESCRIPCION").val($(this).find("td").eq(19).html().trim());
            CKEDITOR.replace('TXA_DESCRIPCION', {
                height: 200
            });
            var categoria = $(this).find("td").eq(18).html().trim();
            $("#TXT_CURSO_CATEGORIA").val(categoria);
            var publicado = $(this).find("td").eq(20).html().trim();
            $("#CBO_PUBLICAR").val(publicado);
            $("#TXT_SHORT_NOMBRE").prop("disabled", true);
            $("#ACCION_CUR").val(ACCIONEDITAR);
        }
    });
};

function fnEliminarCurso(id) {
    //console.log(id);
    fasch_confirm(MSGEliminar, function(rpta) {
        if (rpta) {

            $.ajax({
                type: "POST",
                url: "../servicios/Cursos/del_cursos.php",
                data: "cur_ID=" + id,
                success: function(datos) {
                    $("#errorphp").html(datos);
                    fnListarCursos();
                }
            });
        }
    });

};


function fnlimpiacurso() {

    $("#TXT_HORALECTIVA").val("");
    $("#TXT_MODALIDAD").val("");
    $("#TXT_NUMEVENTO").val("");
    $("#TXT_CODPOI").val("");
    $("#CBO_PUBLICAR").val("NO");

    $("#ID_CURSO").val("");
    $("#TXT_NOMBRE").val("");
    $("#TXT_SHORT_NOMBRE").val("");
    $("#TXT_FECHA_INICIO").val("");
    $("#TXT_FECHA_FIN").val("");
    $("#TXT_HORA_INICIO").val("");
    $("#TXT_HORA_FIN").val("");
    $("#TXT_LUGAR").val("");
    $("#TXT_PUBLICO_DIRIGINDO").val("");
    $("#CBO_ESTADO").val("1");
    $("#TXT_VAL_IMAGEN").val("");
    $("#txf_adjuntar_imagen_lbl").html("");
    $("#ACCION_CUR").val(ACCIONNUEVO);
    $("#TXT_VAL_IMAGEN").val("");
    $("#txf_adjuntar_imagen").val("");
    $("#TXT_SHORT_NOMBRE").prop("disabled", false);
    CKEDITOR.instances['TXA_DESCRIPCION'].destroy();
    $("#TXA_DESCRIPCION").val("");
    CKEDITOR.replace('TXA_DESCRIPCION', {
        height: 200
    });
}


function cargarValidacionesCursos() {

    $.validator.setDefaults({
        debug: true,
        success: "valid"
    });

    $("#frmCursos").validate({
        rules: {
            TXT_NOMBRE: {
                required: true
            },
            TXT_SHORT_NOMBRE: {
                required: true
            },
            TXT_FECHA_INICIO: {
                required: true,
                checkDateFormat: true
            },
            TXT_FECHA_FIN: {
                required: true,
                checkDateFormat: true
            },
            TXT_HORA_INICIO: {
                required: true
            },
            TXT_HORA_FIN: {
                required: true
            },
            TXT_LUGAR: {
                required: true
            },
            TXT_PUBLICO_DIRIGINDO: {
                required: true
            },
            CBO_ESTADO: {
                required: true
            },
            TXT_CURSO_CATEGORIA: {
                required: true
            }
        },
        messages: {
            TXT_NOMBRE: {
                required: MSG_DATO_OBLIGATORIO
            },
            TXT_SHORT_NOMBRE: {
                required: MSG_DATO_OBLIGATORIO
            },
            TXT_FECHA_INICIO: {
                required: MSG_DATO_OBLIGATORIO
            },
            TXT_FECHA_FIN: {
                required: MSG_DATO_OBLIGATORIO
            },
            TXT_HORA_INICIO: {
                required: MSG_DATO_OBLIGATORIO
            },
            TXT_HORA_FIN: {
                required: MSG_DATO_OBLIGATORIO
            },
            TXT_LUGAR: {
                required: MSG_DATO_OBLIGATORIO
            },
            TXT_PUBLICO_DIRIGINDO: {
                required: MSG_DATO_OBLIGATORIO
            },
            CBO_ESTADO: {
                required: MSG_DATO_OBLIGATORIO
            },
            TXT_CURSO_CATEGORIA: {
                required: MSG_DATO_OBLIGATORIO
            },
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });
}