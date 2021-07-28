var ACCIONNUEVO = "0";
var ACCIONEDITAR = "1";

$("#DivContenido").addClass('container');

$(document).ready(function() {
    $("#txt_DNI").prop("disabled", true)
    fntipodocumento();
    cargarValidacionesMatricula();
    fnListarDepartamentos();
    //   alert("Funciona jquery");
    $("#txf_adjuntar_oficio").change(function() {
        var filename = $(this).val().split(/[\\|/]/).pop();
        $("#txf_adjuntar_oficio_lbl").html(filename);
        $("#TXT_VAL_OFICIO").val(filename);
    });
    limpiarCombo("COD_PROV_LAB", 1);
    limpiarCombo("COD_DIST_LAB", 1);
    //$("#h1Titulocurso").html(sessionStorage.getItem("sesnombrecurso"));
    jsComboAnioCursos();
    fnListarMatriculas();
    $("#ImgCurso").attr("src", ".." + sessionStorage.getItem("sesimagen"));

    $("#TXT_ID_CURSO").val(sessionStorage.getItem("sesidcurso"));
    $("#txtNomCursomatricula").val(sessionStorage.getItem("sesnombrecurso"));
    $("#txtDescripCortaCursomatricula").val(sessionStorage.getItem("sesnombreCortocurso"));
    $("#ACCION").val(ACCIONNUEVO);

    $("#COD_DPTO_LAB").change(function() {
        limpiarCombo("COD_PROV_LAB", 1);
        fnListarProvincia($(this).val(), "0");
        limpiarCombo("COD_DIST_LAB", 1);
    });

    $("#COD_PROV_LAB").change(function() {
        limpiarCombo("COD_DIST_LAB", 1);
        fnListarDistritos($("#COD_DPTO_LAB").val(), $("#COD_PROV_LAB").val(), "0");
    });
    fnmostrardesotros();
});

var jsComboAnioCursos = () => {
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
    $("#PROF_curso").empty();
    var cboAnio = $("#PROF_Anio").val();
    $.post("../app/controller/matricula.php", {
        op: 'VerCursosByAnio',
        anio: cboAnio
    }, function(data) {
        var datos = JSON.parse(data);
        $("#PROF_curso").append("<option value=''>Seleccione</option>");
        datos.forEach(elemento => {
            var htmlok = `<option value="${elemento[0]}">${elemento[1]} (${elemento[2]})</option>`;
            $("#PROF_curso").append(htmlok);
        });
    });
}


$("#btnBuscarRUC").click(function() {
    $("#txt_RUC_ENT").val();
    ServiceRuc($("#txt_RUC_ENT").val());
});

var numdociden = 8;

var fntipodocumento = () => {
    $("#cboTipoDocIdentidad").change(function() {
        fnvaidacamtipodoc();
    });
}

var fnvaidacamtipodoc = () => {

    if ($("#cboTipoDocIdentidad").val() == "DNI") {
        $("#txt_NOMBRE").val("");
        $("#txt_APEPAT").val("");
        $("#txt_APEMAT").val("");
        /*$("#txt_NOMBRE").prop("readonly",true);
        $("#txt_APEPAT").prop("readonly",true);
        $("#txt_APEMAT").prop("readonly",true);*/
        $("#txt_DNI").prop("maxlength", "8");
        $("#txt_DNI").prop("disabled", false)
        numdociden = 8;
        //$("#spanconbuscar").show();
        var xValUsuario = sessionStorage.getItem("sesUSUARIO");
        if (xValUsuario == 'admin') { //usuario admin
            $("#txt_NOMBRE").css("background", "#eee");
            $("#txt_APEPAT").css("background", "#eee");
            $("#txt_APEMAT").css("background", "#eee");
        } else {
            $("#txt_NOMBRE").attr("style", "text-transform:uppercase;pointer-events: none;");
            $("#txt_NOMBRE").css("background", "#eee");
            $("#txt_APEPAT").attr("style", "text-transform:uppercase;pointer-events: none;");
            $("#txt_APEPAT").css("background", "#eee");
            $("#txt_APEMAT").attr("style", "text-transform:uppercase;pointer-events: none;");
            $("#txt_APEMAT").css("background", "#eee");
        }
        console.log(numdociden);
        cargarValidacionesMatricula();
    }
    if ($("#cboTipoDocIdentidad").val() == "") {
        $("#txt_DNI").prop("disabled", true)
    }
    if ($("#cboTipoDocIdentidad").val() == "CARNET EXT.") {
        /* $("#txt_NOMBRE").prop("readonly",false);
         $("#txt_APEPAT").prop("readonly",false);
         $("#txt_APEMAT").prop("readonly",false);*/
        $("#txt_DNI").prop("maxlength", "9");
        numdociden = 9;
        //$("#spanconbuscar").hide();
        $("#txt_NOMBRE").attr("style", "text-transform:uppercase; pointer-events: auto;");
        $("#txt_NOMBRE").css("background", "white");
        $("#txt_APEPAT").attr("style", "text-transform:uppercase; pointer-events: auto;");
        $("#txt_APEPAT").css("background", "white");
        $("#txt_APEMAT").attr("style", "text-transform:uppercase; pointer-events: auto;");
        $("#txt_APEMAT").css("background", "white");
        console.log(numdociden);
        $("#txt_DNI").prop("disabled", false)
        cargarValidacionesMatricula();

    }
}


function ServiceRuc(numeroruc) {

    $.get("https://www.sbn.gob.pe/informacionpublica/server-ip/public/ValidarRucSID/" + numeroruc, function(response) {
        // console.log("2");
        if (response['ddp_nombre'] == null) {
            fasch_info("ruc no existe");
        } else {
            // var arregloDatos = JSON.parse(JSON.stringify(response.result));
            // console.log(arregloDatos);
            // console.log("3");

            //arregloDatos.length;
            //  $("#txt_ENTIDAD").val();

            $("#txt_ENTIDAD").val(response['ddp_nombre'].trim());

            //  console.log(arregloDatos["RazonSocial"]);

        }
    });

}


function REGISTRA_DATOS() {
    if (!$("#frmMatricula").valid()) {
        // alert("El formulario tiene inconsistencias que deben ser corregidas.");
        return;
    }
    if ($("#txf_adjuntar_oficio").val() != "") {
        var sizeByte = $("#txf_adjuntar_oficio")[0].files[0].size;
        console.log(sizeByte);
        if ($("#txf_adjuntar_oficio")[0].files[0].type != "application/pdf") {
            //mensaje de error
            fasch_info("Debe seleccionar un archivo con formato PDF.");
            return;
        }
        if (parseInt(sizeByte) > 7340032) {
            //mensaje de error
            fasch_info("El tamaño máximo de un archivo debe ser de 7 MB.");
            return;
        }
    }

    var parametros = $("#frmMatricula").serialize();
    var URL_ACCION = "";
    if ($("#ACCION").val() == "0") {

        URL_ACCION = "../servicios/Matricula/Insert_matricula.php";
        // return URL_ACCION;

    }
    if ($("#ACCION").val() == "1") {
        URL_ACCION = "../servicios/Matricula/update_matricula.php";
        // $("#txt_DNI").attr('readonly', true);
        //return URL_ACCION;
    }
    // console.log($('#TXT_VAL_OFICIO').val());

    var paqueteDeDatos = new FormData();
    /* Todos los campos deben ser añadidos al objeto FormData. Para ello 
    usamos el método append. Los argumentos son el nombre con el que se mandará el 
    dato al script que lo reciba, y el valor del dato.
    Presta especial atención a la forma en que agregamos el contenido 
    del campo de fichero, con el nombre 'archivo'. */
    paqueteDeDatos.append('TXT_ID_CURSO', $('#TXT_ID_CURSO').prop('value'));
    paqueteDeDatos.append('TXT_ID_MAT', $('#TXT_ID_MAT').prop('value'));
    paqueteDeDatos.append('txt_NOMBRE', $('#txt_NOMBRE').prop('value'));
    paqueteDeDatos.append('txt_APEPAT', $('#txt_APEPAT').prop('value'));
    paqueteDeDatos.append('txt_APEMAT', $('#txt_APEMAT').prop('value'));
    paqueteDeDatos.append('cboTipoDocIdentidad', $('#cboTipoDocIdentidad').prop('value'));
    paqueteDeDatos.append('txt_DNI', $('#txt_DNI').prop('value'));
    paqueteDeDatos.append('txt_PROFESION', $('#txt_PROFESION').prop('value'));
    paqueteDeDatos.append('CBO_GENERO', $('#CBO_GENERO').prop('value'));
    paqueteDeDatos.append('CBO_GRADO_ACAD', $('#CBO_GRADO_ACAD').prop('value'));
    paqueteDeDatos.append('txt_CORREO', $('#txt_CORREO').prop('value'));
    paqueteDeDatos.append('txt_TELEF', $('#txt_TELEF').prop('value'));
    paqueteDeDatos.append('txt_RUC_ENT', $('#txt_RUC_ENT').prop('value'));
    paqueteDeDatos.append('txt_ENTIDAD', $('#txt_ENTIDAD').prop('value'));
    paqueteDeDatos.append('COD_DPTO_LAB', $('#COD_DPTO_LAB').prop('value'));
    paqueteDeDatos.append('COD_PROV_LAB', $('#COD_PROV_LAB').prop('value'));
    paqueteDeDatos.append('COD_DIST_LAB', $('#COD_DIST_LAB').prop('value'));
    paqueteDeDatos.append('NIVEL_GOBIERNO', $('#NIVEL_GOBIERNO').prop('value'));
    paqueteDeDatos.append('txt_area', $('#txt_area').prop('value'));
    paqueteDeDatos.append('txt_CARGO', $('#txt_CARGO').prop('value'));
    paqueteDeDatos.append('cbro_rubro', $('#cbro_rubro').prop('value')); //clasificacion
    paqueteDeDatos.append('MODALIDAD', $('#MODALIDAD').prop('value'));
    paqueteDeDatos.append('DESC_MODAL', $('#DESC_MODAL').prop('value'));
    paqueteDeDatos.append('txt_nro_of_asignado', $('#txt_nro_of_asignado').prop('value'));
    paqueteDeDatos.append('TXT_VAL_OFICIO', $('#TXT_VAL_OFICIO').prop('value'));
    paqueteDeDatos.append('txf_adjuntar_oficio', $('#txf_adjuntar_oficio')[0].files[0]);
    paqueteDeDatos.append('txt_areaopcional', $("#txt_areaopcional").val());

    $.ajax({
        url: URL_ACCION,
        type: 'POST', // Siempre que se envíen ficheros, por POST, no por GET.
        contentType: false,
        data: paqueteDeDatos, // Al atributo data se le asigna el objeto FormData.
        processData: false,
        //      dataType: 'json',
        cache: false,
        success: function(resultado) { // En caso de que todo salga bien.
            var alertMessage = "Sus datos han sido registrados con éxito. Sus datos pasarán a la etapa de evaluación y, de encontrarse apto, se le enviará a su correo electrónico la confirmación de su matrícula, así como el respectivo usuario y contraseña para que pueda acceder al curso.";
            if ($("#ACCION").val() == '0' && resultado.indexOf(alertMessage) > -1) {
                fasch_info((resultado).trim(), 'lg', '20px', 'IMPORTANTE - SBN', function() {
                    location.reload();
                });
                return;
            }
            fasch_info(resultado.trim(), 'md');
            fnListarMatriculas();
        },
        error: function() { // Si hay algún error.
            fasch_info("Algo ha fallado.");
        }
    });

};



function fnListarMatriculas() {

    var xAnio = $("#PROF_Anio").val();
    var xCurso = $("#PROF_curso").val();

    //$.get("../servicios/Matricula/get_matricula.php?CBO_PROCESO=" + $("#CBO_PROCESO").val(), function(response) {
    $.get("../servicios/Matricula/get_matricula.php?CBO_PROCESO=" + $("#CBO_PROCESO").val(), { xAnio: xAnio, xCurso: xCurso }, function(response) {

        console.log("=======================");
        console.log(response);
        console.log("=======================");
        //   var tablaDatos = $("#divTablamatricula");
        var filaHTML = "";
        filaHTML += "<table class='table table-striped' id='tblMatricula' name='tblMatricula'>";
        filaHTML += " <thead>";
        filaHTML += " <th class='table-primary'>FILA</th>";
        filaHTML += " <th class='table-primary'>ACCION</th>";
        filaHTML += " <th class='ocultar'>ID</th>";
        filaHTML += " <th class='table-primary'>NOMBRE</th>";
        filaHTML += " <th class='table-primary'>APE PATERNO</th>";
        filaHTML += " <th class='table-primary'>APE MATERNO</th>";
        filaHTML += " <th class='table-primary'>DNI</th>";
        filaHTML += " <th class='table-primary'>PROFESION</th>";
        filaHTML += " <th class='ocultar'>GENERO</th>";
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
        filaHTML += " <th class='ocultar'>Otra Modalidad</th>";
        filaHTML += " <th class='table-primary'>NRO. OFICIO</th>";
        filaHTML += " <th class='table-primary'>DOCUMENTO</th>";
        filaHTML += " <th class='ocultar'>Nom. Archivo</th>";
        filaHTML += " <th class='ocultar'>Ruta Archivo</th>";
        filaHTML += " <th class='ocultar'>Area Opcional</th>";
        filaHTML += " <th class='table-primary'>NOM. CURSO</th>";
        filaHTML += " <th class='table-primary'>NOM. CORTO CURSO</th>";
        filaHTML += " <th class='ocultar'>TIPO DE DOC.</th>";
        filaHTML += "</thead>";
        filaHTML += "<tbody id='tbMatricula' name='tbMatricula'>";
        filaHTML += response;
        filaHTML += "</tbody>";
        filaHTML += "</table>";

        /* $("#tblMatricula").DataTable().destroy();
           //  tablaDatos.empty();
        $("#tbMatricula").html(response);*/
        $("#divTablamatricula").html(filaHTML);

        fnPintarTabla("tblMatricula");

    });
};
/*
function fnListarMatriculasXdni(){

  $.get("../servicios/Matricula/sel_matriculaXdni.php?TXT_DNI_MOD="+$("#TXT_DNI_MOD").val(), function (response) {
 //   var tablaDatos = $("#divTablamatricula");
    $("#tblMatricula").DataTable().destroy();
      //  tablaDatos.empty();
   $("#tbMatricula").html(response);
   fnPintarTabla("tblMatricula");
});
};*/


function exportarexcel() {
    var CBO_PROCESO = $("#CBO_PROCESO").val();
    window.location.href = "../servicios/Generar_excel.php?tipo=" + CBO_PROCESO;

};

function fnBuscaDni() {
    var MATR_dni = $("#txt_DNI").val();
    $.post("../app/controller/matricula.php", {
        op: 'buscar_dni',
        dni: MATR_dni
    }, function(response) {
        var arregloDatos = JSON.parse(response);
        if (arregloDatos['error'] == true) {
            // fnlimpiarMatricula();
            // fasch_info("dni no encontrado");
            // $("#txt_DNI").val(MATR_dni);
            serviceDNI(MATR_dni);
        } else {
            var dato = arregloDatos['data'];
            if (dato.length == 4) {
                $("#txt_NOMBRE").val(dato[1]);
                $("#txt_APEPAT").val(dato[2]);
                $("#txt_APEMAT").val(dato[3]);
            } else {
                var inputForm = [
                    'txt_DNI', 'txt_NOMBRE', 'txt_APEPAT', 'txt_APEMAT', 'txt_PROFESION', 'txt_TELEF', 'txt_CORREO', 'CBO_GENERO', 'CBO_GRADO_ACAD',
                    'txt_ENTIDAD', 'txt_RUC_ENT', 'COD_DPTO_LAB', 'COD_PROV_LAB', 'COD_DIST_LAB', 'NIVEL_GOBIERNO', 'txt_area', 'txt_CARGO',
                    'cbro_rubro', 'MODALIDAD', 'txt_nro_of_asignado',
                ];

                var xValUsuario = sessionStorage.getItem("sesUSUARIO");

                if (xValUsuario == 'admin') { //usuario admin
                    $('#' + 'txt_DNI').css("background", "#eee");
                    $('#' + 'txt_NOMBRE').css("background", "#eee");
                    $('#' + 'txt_APEPAT').css("background", "#eee");
                    $('#' + 'txt_APEMAT').css("background", "#eee");
                    $('#' + 'txt_PROFESION').css("background", "#eee");
                    $('#' + 'txt_TELEF').css("background", "#eee");
                    $('#' + 'txt_CORREO').css("background", "#eee");
                    $('#' + 'CBO_GENERO').css("background", "#eee");
                    $('#' + 'CBO_GRADO_ACAD').css("background", "#eee");
                } else { //usuario normal
                    $('#' + 'txt_DNI').attr("style", "pointer-events: none;");
                    $('#' + 'txt_DNI').css("background", "#eee");
                    $('#' + 'txt_NOMBRE').attr("style", "pointer-events: none;");
                    $('#' + 'txt_NOMBRE').css("background", "#eee");
                    $('#' + 'txt_APEPAT').attr("style", "pointer-events: none;");
                    $('#' + 'txt_APEPAT').css("background", "#eee");
                    $('#' + 'txt_APEMAT').attr("style", "pointer-events: none;");
                    $('#' + 'txt_APEMAT').css("background", "#eee");
                    $('#' + 'txt_PROFESION').attr("style", "pointer-events: none;");
                    $('#' + 'txt_PROFESION').css("background", "#eee");
                    $('#' + 'txt_TELEF').attr("style", "pointer-events: none;");
                    $('#' + 'txt_TELEF').css("background", "#eee");
                    $('#' + 'txt_CORREO').attr("style", "pointer-events: none;");
                    $('#' + 'txt_CORREO').css("background", "#eee");
                    $('#' + 'CBO_GENERO').attr("style", "pointer-events: none;");
                    $('#' + 'CBO_GENERO').css("background", "#eee");
                    $('#' + 'CBO_GRADO_ACAD').attr("style", "pointer-events: none;");
                    $('#' + 'CBO_GRADO_ACAD').css("background", "#eee");
                }

                for (var i = 0; i < dato.length; i++) {
                    $('#' + inputForm[i]).val(dato[i]);
                    /*$('#' + inputForm[i]).attr("style", "pointer-events: none;");
                    $('#' + inputForm[i]).css("background", "#eee");*/
                }
                var TableTr14 = $('#div_form_curso table tbody tr')[17];
                $(TableTr14).addClass('d-none');
                combo_Provinci(dato[11], dato[12]);
                fnListarDistritos(dato[11], dato[12], dato[13]);
            }
            $("#frmMatricula").data('validator').resetForm();

        }

    });
};

var serviceDNI = (numeroDNI) => {
    $.get("https://www.sbn.gob.pe/informacionpublica/server-ip/public/ValidarDniSID/" + numeroDNI, function(response) {
        if (response['data']['deResultado'] != 'Consulta realizada correctamente') {
            fasch_info("DNI no encontrado");
        } else {
            var datos = response['data']['datosPersona'];
            // var datos = JSON.parse(JSON.stringify(response.result));
            $("#PROF_dni").val(numeroDNI);
            $("#txt_NOMBRE").val(datos['prenombres']);
            $("#txt_APEPAT").val(datos['apPrimer']);
            $("#txt_APEMAT").val(datos['apSegundo']);
            $("#frmMatricula").data('validator').resetForm();
        }
    });
}

function fnEliminarMat(Mat_ID) {
    // alert(Mat_ID);

    fasch_confirm(MSGEliminar, function(rpta) {
        if (rpta) {
            $.ajax({
                type: "POST",
                url: "../servicios/Matricula/del_matricula.php",
                data: "Mat_ID=" + Mat_ID,
                success: function(datos) {
                    //  $("#errorphp").html(datos);
                    fnListarMatriculas();
                }
            });
        }
    });
};


function fnEditarMat(id) {
    var vdepartame = "";
    var vprovincia = "";
    var vdistrito = "";

    var tableHtml = $('#tblMatricula');

    tableHtml.find('tbody > tr').each(function() {
        //alert(id);
        if ($(this).find("td").eq(2).html() == id) {

            //    var objDetPresupuesto = new Object();
            $("#ACCION").val(ACCIONEDITAR);
            //  fnListarProvincia($(this).find("td").eq(14).html().trim());
            //  fnListarDistritos($(this).find("td").eq(14).html().trim(),$(this).find("td").eq(15).html().trim());

            $("#TXT_ID_MAT").val($(this).find("td").eq(2).html().trim());

            $("#txt_DNI").val($(this).find("td").eq(6).html().trim());
            $("#txt_PROFESION").val($(this).find("td").eq(7).html().trim());
            $("#CBO_GENERO").val($(this).find("td").eq(8).html().trim());
            //       $("#CBO_GENERO option[value='"+$(this).find("td").eq(7).html()+"']").attr("selected", true);
            //      alert("."+$(this).find("td").eq(7).html()+".");
            $("#CBO_GRADO_ACAD").val($(this).find("td").eq(9).html().trim());
            $("#txt_CORREO").val($(this).find("td").eq(10).html().trim());
            $("#txt_TELEF").val($(this).find("td").eq(11).html().trim());
            $("#txt_RUC_ENT").val($(this).find("td").eq(12).html().trim());
            $("#txt_ENTIDAD").val($(this).find("td").eq(13).html().trim());
            $("#COD_DPTO_LAB").val($(this).find("td").eq(14).html().trim());
            //fnListarProvincia($(this).find("td").eq(14).html().trim(),$(this).find("td").eq(15).html().trim());
            //console.log($(this).find("td").eq(15).html().trim()+"prov");

            //alert(vprovincia);
            vdepartame = $(this).find("td").eq(14).html().trim();
            vprovincia = $(this).find("td").eq(15).html().trim();
            vdistrito = $(this).find("td").eq(16).html().trim();

            combo_Provinci(vdepartame, vprovincia);
            //  combo_Distrito(vprovincia, vdistrito);

            // console.log($("#txt_NOMBRE").val() + "---ape" + $("#txt_APEMAT").val());
            //return false;

            $("#COD_PROV_LAB").val($(this).find("td").eq(15).html().trim());
            $("#COD_DIST_LAB").val($(this).find("td").eq(16).html().trim());
            // console.log($("#COD_PROV_LAB").val() + "-");
            $("#NIVEL_GOBIERNO").val($(this).find("td").eq(17).html().trim());
            $("#txt_area").val($(this).find("td").eq(18).html().trim());

            // console.log($(this).find("td").eq(18).html().trim());

            $("#txt_CARGO").val($(this).find("td").eq(19).html().trim());
            $("#cbro_rubro").val($(this).find("td").eq(20).html().trim());
            $("#MODALIDAD").val($(this).find("td").eq(21).html().trim());
            $("#DESC_MODAL").val($(this).find("td").eq(22).html().trim());
            $("#txt_nro_of_asignado").val($(this).find("td").eq(23).html());

            $("#TXT_VAL_OFICIO").val($(this).find("td").eq(25).html().trim());

            $("#txt_areaopcional").val($(this).find("td").eq(28).html().trim());

            $("#cboTipoDocIdentidad").val($(this).find("td").eq(29).html().trim());
            //     $("#COD_PROV_LAB option[value='01']").attr("selected", true);
            //     $("#COD_DIST_LAB option[value='02']").attr("selected", true);

            nivel_gobierno_change($('#NIVEL_GOBIERNO').val());
            fnvaidacamtipodoc();

            $('#NIVELGOB_ENTIDAD').val($('#txt_ENTIDAD').val());

            $("#txt_NOMBRE").val($(this).find("td").eq(3).html().trim());
            $("#txt_APEPAT").val($(this).find("td").eq(4).html().trim());
            $("#txt_APEMAT").val($(this).find("td").eq(5).html().trim());
            var nombre_archivo = $(this).find("td").eq(25).html().trim();
            var ruta_archivo = $(this).find("td").eq(26).html().trim();
            //$("#txf_adjuntar_oficio_lbl").html($(this).find("td").eq(24).html().trim());
            $("#txf_adjuntar_oficio_lbl").html("");
            var string = "";
            // alert("--"+nombre_archivo+"--"+ruta_archivo+"--");
            if (nombre_archivo != "" && ruta_archivo != "") {

                //string +="<a onclick=\"fnDescargarArchivo('" +ruta_archivo+ "','"+nombre_archivo+"')\">";
                string += "<a href='" + ruta_archivo + nombre_archivo + "' download='" + nombre_archivo + "'>";
                string += "<span class='glyphicon glyphicon-cloud-download'>";

                string += nombre_archivo;
                string += "</span>";
                string += "</a>";

                $("#txf_adjuntar_oficio_lbl").html(string);
            }

            $("#TXT_VAL_RUTA_ARCH").val($(this).find("td").eq(26).html().trim());

            $("#txt_DNI").attr('readonly', true);

            //  alert($(this).find("td").eq(24).html().trim());
        }

    });
    fnListarDistritos(vdepartame, vprovincia, vdistrito);
    fnmostrardesotros();

    //$("#COD_PROV_LAB option[value='01']").attr("selected", true);
    //fnselecubigeo(vprovincia,vdistrito);
};

var combo_Provinci = (COD_DEP, COD_PROV) => {
    $.post("../app/controller/matricula.php", {
        op: 'combo_Provincia',
        COD_DEP: COD_DEP,
    }, function(data) {
        var datos = JSON.parse(data);
        var combo = '';
        for (var i = 0; i < datos.length; i++) {
            if (COD_PROV == datos[i][0]) {
                combo += '<option value="' + datos[i][0] + '" selected>' + datos[i][1] + '</option>';
            } else {
                combo += '<option value="' + datos[i][0] + '">' + datos[i][1] + '</option>';
            }

        }
        $("#COD_PROV_LAB").html(combo);
    });
}



function fnselecubigeo(prov, dist) {
    $("#COD_PROV_LAB").val(prov);
    $("#COD_DIST_LAB").val(dist);
}


function fnDescargarArchivo(ruta, nombre) {

    window.location.href = "../servicios/Matricula/DescargarArchivo.php?TXT_VAL_RUTA_ARCH=" + ruta + "&TXT_VAL_OFICIO=" + nombre;

};


function fnVerPDF(ruta, nombre) {
    var Archivo = ruta + nombre;

    // window.open("../servicios/Matricula/GetViewpdfs.php?Archivo=" + Archivo, "_blank");

    // window.location.href = "../servicios/Matricula/GetViewpdfs.php?Archivo="+Archivo;
    /* $.ajax({
     type: "GET",
     url: "../servicios/Matricula/GetViewpdfs.php",
     data: "Archivo="+Archivo,
     success: function(datos){
   //  $("#errorphp").html(datos);
     window.location.href ="../servicios/Matricula/GetViewpdfs.php";
   }
 });*/

    $.post("../app/controller/matricula.php", {
        op: 'pdf',
        archivo: Archivo,
    }, function(data) {
        if (data == true) {
            window.open("../servicios/pdf.php", "_blank");
        }
    });

}


function fnListarDepartamentos() {

    $.get("../servicios/Ubigeo/get_departamentos.php", function(response) {
        //   var tablaDatos = $("#divTablamatricula");
        //  tablaDatos.empty();
        //  console.log(response);
        //  debugger;
        $("#COD_DPTO_LAB").html(response);

    });
};

/*$("#COD_DPTO_LAB").onchange(
  fnListarProvincia();
  );*/


function fnListarProvincia(departamento, provincia) {
    //debugger;
    // console.log(departamento);
    $.get("../servicios/Ubigeo/get_provincias.php?COD_DPTO_LAB=" + departamento, function(response) {
        //alert(response);
        $("#COD_PROV_LAB").html(response);
        if (departamento == "0") {
            $("#PROVINCIA").val("0");
        } else {
            $("#PROVINCIA").val(provincia);
        }

    });

};

function fnListarDistritos(departamento, provincia, distrito) {
    // console.log(departamento + " ---- " + provincia);

    //debugger;
    $.get("../servicios/Ubigeo/get_distritos.php?COD_DPTO_LAB=" + departamento + "&COD_PROV_LAB=" + provincia, function(response) {

        $("#COD_DIST_LAB").html(response);
        if (provincia == "0") {
            $("#COD_DIST_LAB").val("0");
        } else {
            $("#COD_DIST_LAB").val(distrito);
        }
    });

};

function fnProcesarInscritos() {
    var contvalida = 0;
    var Arreglo = [];
    $("#idcheckbox:checked").each(function() {

        if ($(this).val() != "") {
            Arreglo.push($(this).val());
        }
    });
    console.log(Arreglo.length);
    if (Arreglo.length == 0) {
        fasch_info("Tiene que marcar registros en la grilla.");
    } else {
        fasch_block(true, "Procesando registros");
        var numregistro = Arreglo.length;
        var ID_USUARIO = 1;
        // console.log(Arreglo.length);
        // console.log(Arreglo);
        $.each(Arreglo, function(index, item) {
            var cont = 1 + index;
            var ID_MATRICULA = item;
            // console.log(ID_MATRICULA + ID_USUARIO);
            // console.log(cont);
            $.ajax({
                type: "POST",
                url: "../servicios/Matricula/ins_proc_matricula.php",
                data: "ID_MATRICULA=" + ID_MATRICULA + "&ID_USUARIO=" + ID_USUARIO,
                success: function(datos) {
                    console.log(datos);
                    console.log(cont);

                    //alert(cont+" de "+numregistro+ " Procesados");
                    fasch_block(true, cont + " de " + numregistro + " Procesados");
                    if (cont == numregistro) {

                        contvalida = cont;
                        // return contvalida;
                    }
                    if (contvalida != 0) {
                        console.log("DESBLOQUEA");
                        fnListarMatriculas();
                        fasch_block(false);
                    }

                }
            });

            /* $.post("../servicios/Matricula/ins_proc_matricula.php?ID_MATRICULA=2&ID_USUARIO=2", function (objRpta) {
   
           console.log(objRpta);

       });*/
        });
        // console.log(index+"Procesados");
    }
}



function fnmostrardesotros() {

    if ($("#MODALIDAD").val() == "Otros") {
        $("#desc_modal").show();
    } else {
        $("#desc_modal").hide();
    }

}



function fnlimpiarMatricula() {

    $("#ACCION").val(ACCIONNUEVO);
    $("#TXT_ID_MAT").val("");
    $("#txt_NOMBRE").val("");
    $("#txt_APEPAT").val("");
    $("#txt_APEMAT").val("");
    $("#txt_DNI").val("");
    $("#txt_PROFESION").val("");
    $("#CBO_GENERO").val("");
    limpiarCombo("COD_PROV_LAB", 1);
    limpiarCombo("COD_DIST_LAB", 1);
    $("#CBO_GRADO_ACAD").val("");
    $("#txt_CORREO").val("");
    $("#txt_TELEF").val("");
    $("#txt_RUC_ENT").val("");
    $("#txt_ENTIDAD").val("");
    $("#COD_DPTO_LAB").val("0");
    //   $("#COD_PROV_LAB").val("0");
    //    $("#COD_DIST_LAB").val("0");
    $("#NIVEL_GOBIERNO").val("");
    $("#txt_area").val("");
    $("#txt_CARGO").val("");
    $("#cbro_rubro").val("");
    $("#MODALIDAD").val("");
    $("#DESC_MODAL").val("");
    $("#txt_nro_of_asignado").val("");
    $("#TXT_VAL_OFICIO").val("");
    $("#txf_adjuntar_oficio_lbl").html("");
    $("#txf_adjuntar_oficio").val("");
    $("#txt_areaopcional").val("");

    //  fntipodocumento();
    var inputForm = [
        'txt_DNI', 'txt_PROFESION', 'txt_TELEF', 'txt_CORREO', 'CBO_GENERO', 'CBO_GRADO_ACAD',
        'txt_RUC_ENT', 'COD_DPTO_LAB', 'COD_PROV_LAB', 'COD_DIST_LAB', 'NIVEL_GOBIERNO', 'txt_area', 'txt_CARGO',
        'cbro_rubro', 'MODALIDAD', 'txt_nro_of_asignado',
    ];
    for (var i = 0; i < inputForm.length; i++) {
        $('#' + inputForm[i]).attr("style", "pointer-events: auto;");
        $('#' + inputForm[i]).css("background", "white");
    }

    nivel_gobierno_change($('#NIVEL_GOBIERNO').val());

    $("#txt_DNI").attr('readonly', false);

    var TableTr14 = $('#div_form_curso table tbody tr')[17];
    $(TableTr14).removeClass('d-none');

    fnmostrardesotros();

    $("#frmMatricula").data('validator').resetForm();
};

$("#CBO_PROCESO").change(function(e) {
    e.preventDefault();
    fnListarMatriculas();
});


$('#NIVEL_GOBIERNO').change(function(e) {
    e.preventDefault();
    $('#txt_RUC_ENT').val('0');
    nivel_gobierno_change(this.value);
    $('#txt_ENTIDAD').val(null);
    $('#NIVELGOB_ENTIDAD').val(null);
});

var nivel_gobierno_change = (nivelGobierno) => {
    switch (nivelGobierno) {
        case 'Regional':
            $('#txt_RUC_ENT').css({ "background": "#eee", 'pointer-events': 'none' });
            $('#btnBuscarRUC').attr('disabled', true);
            $('#NG_open').attr('disabled', false);
            $('#message_NG').removeClass('d-none');
            break;
        default:
            $('#txt_RUC_ENT').css({ "background": "white", 'pointer-events': 'auto' });
            $('#btnBuscarRUC').attr('disabled', false);
            $('#NG_open').attr('disabled', true);
            $('#message_NG').addClass('d-none');
            break;
    }
}

$('#btnSelectEntidad').click(function(e) {
    e.preventDefault();
    var NG_entidad = $('#NIVELGOB_ENTIDAD').val();
    $('#txt_ENTIDAD').val(NG_entidad);
    entidadesRegionales.forEach(element => {
        if (element[1] == NG_entidad) {
            $('#txt_RUC_ENT').val(element[0]);
        }
    });
    $('#modalEntidades').modal('hide');
});

const entidadesRegionales = [
    ['20530688390', 'GOBIERNO REGIONAL DE LIMA'],
    ['20479569861', 'GOBIERNO REGIONAL DE AMAZONAS'],
    ['20530689019', 'GOBIERNO REGIONAL DE ANCASH'],
    ['20527141762', 'GOBIERNO REGIONAL DE APURIMAC'],
    ['20498390570', 'GOBIERNO REGIONAL DE AREQUIPA'],
    ['20452393493', 'GOBIERNO REGIONAL DE AYACUCHO'],
    ['20453744168', 'GOBIERNO REGIONAL DE CAJAMARCA'],
    ['20505703554', 'GOBIERNO REGIONAL DE CALLAO'],
    ['20527147612', 'GOBIERNO REGIONAL DE CUSCO'],
    ['20486020882', 'GOBIERNO REGIONAL DE HUANCAVELICA'],
    ['20489250731', 'GOBIERNO REGIONAL DE HUÁNUCO'],
    ['20452393817', 'GOBIERNO REGIONAL DE ICA'],
    ['20486021692', 'GOBIERNO REGIONAL DE JUNÍN'],
    ['20440374248', 'GOBIERNO REGIONAL DE LA LIBERTAD'],
    ['20479569780', 'GOBIERNO REGIONAL DE LAMBAYEQUE'],
    ['20493196902', 'GOBIERNO REGIONAL DE LORETO'],
    ['20527143200', 'GOBIERNO REGIONAL DE MADRE DE DIOS'],
    ['20519752604', 'GOBIERNO REGIONAL DE MOQUEGUA'],
    ['20489252270', 'GOBIERNO REGIONAL DE PASCO'],
    ['20484004421', 'GOBIERNO REGIONAL DE PIURA'],
    ['20406325815', 'GOBIERNO REGIONAL DE PUNO'],
    ['20531375808', 'GOBIERNO REGIONAL DE SAN MARTÍN'],
    ['20519752515', 'GOBIERNO REGIONAL DE TACNA'],
    ['20484003883', 'GOBIERNO REGIONAL DE TUMBES'],
    ['20393066386', 'GOBIERNO REGIONAL DE UCAYALI'],
];

function cargarValidacionesMatricula() {

    $.validator.setDefaults({
        debug: true,
        success: "valid"
    });

    $("#frmMatricula").validate({
        rules: {
            txt_NOMBRE: {
                required: true
            },
            txt_CORREO: {
                required: true,
                email: true
            },
            txt_APEPAT: {
                required: true
            },
            txt_APEMAT: {
                required: true
            },
            txt_DNI: {
                required: true,
                number: true,
                digits: true,
                minlength: numdociden
            },
            txt_TELEF: {
                required: true,
                number: true,
                digits: true
            },
            txt_PROFESION: {
                required: true
            },
            CBO_GENERO: {
                required: true
            },
            CBO_GRADO_ACAD: {
                required: true
            },
            txt_ENTIDAD: {
                required: true
            },
            txt_RUC_ENT: {
                required: true,
                number: true,
                digits: true
            },
            COD_DPTO_LAB: {
                required: true
            },
            COD_PROV_LAB: {
                required: true
            },
            COD_DIST_LAB: {
                required: true
            },
            NIVEL_GOBIERNO: {
                required: true
            },
            txt_area: {
                required: true
            },
            txt_CARGO: {
                required: true
            },
            cbro_rubro: {
                required: true
            },
            MODALIDAD: {
                required: true
            },
            txt_nro_of_asignado: {
                required: true
            } //,
            //   txt_CARGO: { required: true },
        },
        messages: {
            txt_NOMBRE: {
                required: MSG_DATO_OBLIGATORIO
            },
            txt_CORREO: {
                required: MSG_DATO_OBLIGATORIO,
                email: MSG_FORMATO_EMAIL
            },
            txt_APEPAT: {
                required: MSG_DATO_OBLIGATORIO
            },
            txt_APEMAT: {
                required: MSG_DATO_OBLIGATORIO
            },
            txt_DNI: {
                required: MSG_DATO_OBLIGATORIO,
                number: MSG_SOLO_NUMEROS,
                digits: MSG_SOLO_NUMEROS_ENTEROS
            },
            txt_TELEF: {
                required: MSG_DATO_OBLIGATORIO,
                number: MSG_SOLO_NUMEROS,
                digits: MSG_SOLO_NUMEROS_ENTEROS
            },
            txt_PROFESION: {
                required: MSG_DATO_OBLIGATORIO
            },
            CBO_GENERO: {
                required: MSG_DATO_OBLIGATORIO
            },
            CBO_GRADO_ACAD: {
                required: MSG_DATO_OBLIGATORIO
            },
            txt_ENTIDAD: {
                required: MSG_DATO_OBLIGATORIO
            },
            txt_RUC_ENT: {
                required: MSG_DATO_OBLIGATORIO,
                number: MSG_SOLO_NUMEROS,
                digits: MSG_SOLO_NUMEROS_ENTEROS
            },
            COD_DPTO_LAB: {
                required: MSG_DATO_OBLIGATORIO
            },
            COD_PROV_LAB: {
                required: MSG_DATO_OBLIGATORIO
            },
            COD_DIST_LAB: {
                required: MSG_DATO_OBLIGATORIO
            },
            NIVEL_GOBIERNO: {
                required: MSG_DATO_OBLIGATORIO
            },
            txt_area: {
                required: MSG_DATO_OBLIGATORIO
            },
            txt_CARGO: {
                required: MSG_DATO_OBLIGATORIO
            },
            cbro_rubro: {
                required: MSG_DATO_OBLIGATORIO
            },
            MODALIDAD: {
                required: MSG_DATO_OBLIGATORIO
            },
            txt_nro_of_asignado: {
                required: MSG_DATO_OBLIGATORIO
            } //,
            //  txt_CARGO: { required: MSG_DATO_OBLIGATORIO },
        },
        errorPlacement: function(error, element) {
            switch (element[0]['id']) {
                case 'txt_DNI':
                    error.insertAfter($("#txt_DNI").parent());
                    break;
                case 'txt_RUC_ENT':
                    error.insertAfter($("#txt_RUC_ENT").parent());
                    break;
                case 'txt_ENTIDAD':
                    error.insertAfter($("#txt_ENTIDAD").parent());
                    break;

                default:
                    error.insertAfter(element);
                    break;
            }
        }
    });
}