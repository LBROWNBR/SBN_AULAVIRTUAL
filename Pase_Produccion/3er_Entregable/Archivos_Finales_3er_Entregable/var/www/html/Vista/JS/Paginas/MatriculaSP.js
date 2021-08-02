$(document).ready(function() {
    $("#h4Subtitulos").text("LISTADO DE PARTICIPANTES");
    //comboCursos();
    jsMostrarAnio();
    listado();
});

$("#DivContenido").addClass('container');
$(".nav.nav-pills .nav-item .nav-link").removeClass('active');

var jsMostrarAnio = () => {
    $("#PROF_Anio").empty();
    $.post("../app/controller/matricula.php", {
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

var jsMostrarCursosByAnio = () => {
    $("#FILTRO_curso").empty();
    var cboAnio = $("#PROF_Anio").val();
    $.post("../app/controller/matricula.php", {
        op: 'VerCursosByAnio',
        anio: cboAnio
    }, function(data) {
        var datos = JSON.parse(data);
        $("#FILTRO_curso").append("<option value=''>Seleccione</option>");
        datos.forEach(elemento => {
            var htmlok = `<option value="${elemento[0]}">${elemento[1]} (${elemento[2]})</option>`;
            $("#FILTRO_curso").append(htmlok);
        });
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

var table;

var listado = () => {
    var vcursocbo = $('select[name="FILTRO_curso"] option:selected').text();
    var vestadocbo = $('select[name="FILTRO_proc"] option:selected').text();

    var FILTRO_anio = $('select[name="PROF_Anio"] option:selected').val();
    var FILTRO_curso = $('select[name="FILTRO_curso"] option:selected').val();
    var FILTRO_proc = $('select[name="FILTRO_proc"] option:selected').val();

    table = $("#tbl_participantes").DataTable({
        "ajax": {
            "method": "POST",
            "url": "../app/controller/matricula.php",
            "data": {
                op: 'matricula_sin_procesar',
                FILTRO_curso: FILTRO_curso,
                FILTRO_proc: FILTRO_proc
            }
        },
        "language": idioma_espanol,
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excel',
            title: 'LISTADO DE PARTICIPANTES - ' + 'Curso: ' + vcursocbo + ' Estado: ' + vestadocbo,
            messageTop: ' ',
            exportOptions: {
                columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]
            }
        }],
        "createdRow": function(row, data, dataIndex) {
            if (data[0] % 2 == 0) {
                $(row).css("background-color", "#cecfd0");
            } else {
                $(row).css("background-color", "#c5dfd7");
            }
        }
    });

}

var fnVerPDF = (ruta, nombre) => {
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

function fnProcesarInscritos() {
    var contvalida = 0;
    var Arreglo = [];
    $("#idcheckbox:checked").each(function() {
        if ($(this).val() != "") {
            Arreglo.push($(this).val());
        }
    });
    if (Arreglo.length == 0) {
        fasch_info("Tiene que marcar registros en la grilla.", 'md');
    } else {
        fasch_block(true, "Procesando registros");
        var numregistro = Arreglo.length;
        var ID_USUARIO = 1;
        $.each(Arreglo, function(index, item) {
            var cont = 1 + index;
            var ID_MATRICULA = item;
            $.ajax({
                type: "POST",
                url: "../servicios/Matricula/ins_proc_matricula.php",
                data: "ID_MATRICULA=" + ID_MATRICULA + "&ID_USUARIO=" + ID_USUARIO,
                success: function(datos) {
                    fasch_block(true, cont + " de " + numregistro + " Procesados");
                    if (cont == numregistro) {
                        contvalida = cont;
                    }
                    if (contvalida != 0) {
                        table.ajax.reload();
                        fasch_block(false);
                    }

                }
            });
        });
    }
}

$('#FILTRO_curso, #FILTRO_proc').on('change', function() {
    table.destroy();
    listado();
    //table.ajax.reload();
});