var formMantImgCrono = "<div id='divMantImagen' name='divMantImagen'><form id='frmImagen' name='frmImagen'><label class='btn btn-secondary' for='txf_adjImgenCrono'><input type='file'  id='txf_adjImgenCrono' name='txf_adjImgenCrono' style='display:none;' />Adjuntar Imagen</label><label id='lblNombArchivo' name='lblNombArchivo'></label></form><button class='btn btn-info' id='btnImagenCrono' name='btnImagenCrono' onclick='fnGrabarImgenCronog()'>Grabar imagen</button></div>";

$("#DivContenido").addClass('container');

$(document).ready(function() {
    fnLLENAR_CboMes();
    fnListarAnios_CboMes_cboAnio();
    fnListarAnios_cboAnioImagen();
    fnMostrarImg();
});

function fnLLENAR_CboMes() {
    var MesActual = (new Date).getMonth() + 1;
    var myObjMeses = {
        "mes": [
            { "codMes": "01", "nomMes": "Enero" },
            { "codMes": "02", "nomMes": "Febrero" },
            { "codMes": "03", "nomMes": "Marzo" },
            { "codMes": "04", "nomMes": "Abril" },
            { "codMes": "05", "nomMes": "Mayo" },
            { "codMes": "06", "nomMes": "Junio" },
            { "codMes": "07", "nomMes": "Julio" },
            { "codMes": "08", "nomMes": "Agosto" },
            { "codMes": "09", "nomMes": "Septiembre" },
            { "codMes": "10", "nomMes": "Octubre" },
            { "codMes": "11", "nomMes": "Noviembre" },
            { "codMes": "12", "nomMes": "Diciembre" }
        ]
    };

    $("#cboMes").empty();
    myObjMeses.mes.forEach(elemento => {
        var selectedMes = (MesActual == elemento.codMes) ? 'selected="selected"' : '';
        var htmlok = `<option value="${elemento.codMes}" ${selectedMes}>${elemento.nomMes}</option>`;
        $("#cboMes").append(htmlok);
    });
}


function fnListarAnios_CboMes_cboAnio() {
    var anioActual_01 = (new Date).getFullYear();
    $("#cboAnio").empty();
    $.post("../app/controller/matricula.php", {
        op: 'AniosCursos',
    }, function(data) {
        var datos = JSON.parse(data);
        $("#cboAnio").append("<option value=''>Seleccione</option>");
        datos.forEach(elemento => {
            var selectedAnio = (anioActual_01 == elemento[0]) ? 'selected="selected"' : '';
            var htmlok = `<option value="${elemento[0]}" ${selectedAnio}>${elemento[1]}</option>`;
            $("#cboAnio").append(htmlok);
        });
        fnListacronograma();
    });
}

function fnListarAnios_cboAnioImagen() {
    var anioActual = (new Date).getFullYear();
    $("#cboAnioImagen").empty();
    $.post("../app/controller/matricula.php", {
        op: 'AniosCursos',
    }, function(data) {
        var datos = JSON.parse(data);
        $("#cboAnioImagen").append("<option value=''>Seleccione</option>");
        datos.forEach(elemento => {
            var selectedAnio = (anioActual == elemento[0]) ? 'selected="selected"' : '';
            var htmlok = `<option value="${elemento[0]}" ${selectedAnio}>${elemento[1]}</option>`;
            $("#cboAnioImagen").append(htmlok);
        });
        fnListaImagencronograma();
    });
}


$("#cboAnioImagen").change(function() {
    fnListaImagencronograma();
});

function fnMostrarImg() {
    $("#imgCoronograma").show();
    $("#divListaCronograma").hide();
    $("#divComboImagen").show();
    $("#divcomboLista").hide();
}

function fnesconderImg() {
    $("#imgCoronograma").hide();
    $("#divListaCronograma").show();
    $("#divComboImagen").hide();
    $("#divcomboLista").show();
}

$("#cboMes").change(function() {
    fnbuscacronograma($("#cboMes").val(), $("#cboAnio").val())

});

$("#cboAnio").change(function() {
    fnbuscacronograma($("#cboMes").val(), $("#cboAnio").val())

});

function fnListacronograma() {
    var f = new Date();
    var mes = f.getMonth() + 1;
    var anio = f.getFullYear();
    //document.write(f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());
    fnbuscacronograma(mes, anio);
}


function fnbuscacronograma(mes, anio) {
    /*$("#cboMes").val();
    $("#cboAnio").val()*/

    console.log(mes + "--" + anio);
    $.get("../servicios/Cronograma/get_cronograma.php", "cboMes=" + mes + "&cboAnio=" + anio, function(response) {

        //  alert(response);
        $("#divListaCronograma").html("");
        var arregloDatos = JSON.parse(response);
        var fechaencabes = "";
        console.log(arregloDatos.length);
        if (arregloDatos.length == 0) {
            var filaHTML = "";
            filaHTML += "<div> <div class='centrartext'><b>" + "No existen cursos en este mes" + "</b></div>";
            $("#divListaCronograma").append(filaHTML);
        } else {

            $.each(arregloDatos, function(idx, item) {
                var filaHTML = "";
                if (fechaencabes != item.FECHA) {
                    filaHTML += " <div class='centrartext'><b>" + item.FECHA + "</b></div>";
                }

                //	filaHTML +="<div class='card' >";
                //
                filaHTML += "<div class='card-header' id='heading" + item.cur_ID + "'>";

                filaHTML += "<h2 class='mb-0'>";

                filaHTML += "<button class='btn btn-link collapsed' type='button' data-toggle='collapse' data-target='#collapse" + item.cur_ID + "' aria-expanded='false' aria-controls='collapse" + item.cur_ID + "'>";
                filaHTML += item.cur_Nombre + " (" + item.cur_Lugar + ")";
                filaHTML += "</button>";

                filaHTML += "</h2>";
                filaHTML += "</div>";
                //

                //
                filaHTML += "<div id='collapse" + item.cur_ID + "' class='collapse' aria-labelledby='heading" + item.cur_ID + "' data-parent='#divListaCronograma'>";
                //          	
                filaHTML += "<div class='card-body' style=''>";

                filaHTML += "Nombre del curso: " + item.cur_Nombre + "</br>";

                filaHTML += "Fecha de inicio: " + item.cur_FechaInicio + "</br>";

                filaHTML += "Fecha fin: " + item.cur_FechaFin + "</br>";

                filaHTML += "Descripción: " + "</br>" + item.cur_descripcion + "</br>";


                //		filaHTML += "Fecha fin: "+item.cur_FechaFin +"</br>";

                //		filaHTML +="</div>";

                filaHTML += "</div>";
                //

                filaHTML += "</div>";
                if (fechaencabes != item.FECHA) {
                    filaHTML += "</div>";
                }

                fechaencabes = item.FECHA;
                $("#divListaCronograma").append(filaHTML);
            });
        }
    });

}


function fnGrabarImgenCronog() {

    var archivoform = new FormData();
    archivoform.append('cboAnioImagen', $('#cboAnioImagen').prop('value'));
    archivoform.append('txf_adjImgenCrono', $('#txf_adjImgenCrono')[0].files[0]);

    if ($("#txf_adjImgenCrono").val() != "") {
        var sizeByte = $("#txf_adjImgenCrono")[0].files[0].size;
        var tipoarchivo = $("#txf_adjImgenCrono")[0].files[0].type;
        console.log(tipoarchivo);
        console.log(sizeByte);
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
    $.ajax({
        url: "../servicios/Cronograma/Mant_ImagenCrono.php",
        type: 'POST', // Siempre que se envíen ficheros, por POST, no por GET.
        contentType: false,
        data: archivoform, // Al atributo data se le asigna el objeto FormData.
        processData: false,
        //      dataType: 'json',
        cache: false,
        success: function(resultado) { // En caso de que todo salga bien.
            fasch_info("Grabado");
            fnListaImagencronograma();
        },
        error: function() { // Si hay algún error.
            fasch_info("Algo ha fallado.");
        }
    });

}

function fnListaImagencronograma() {
    $("#imgCoronograma").html("");
    //alert($("#cboAnioImagen").val());
    if (SBN_USER == null) {
        //    paginaurl ="../Vista/login.html";
        $("#imgCoronograma").append("");
    } else {

        $("#imgCoronograma").append(formMantImgCrono);
        $("#btnImagenCrono").hide();
        $("#txf_adjImgenCrono").change(function() {
            var filename = $(this).val().split(/[\\|/]/).pop();
            $("#lblNombArchivo").html(filename);
            //  $("#TXT_VAL_OFICIO").val(filename);
            $("#btnImagenCrono").show();
        });
    }



    //return;
    $.get("../servicios/Cronograma/get_imagenCrono.php?cboAnioImagen=" + $("#cboAnioImagen").val(), function(response) {
        //$("#imgCoronograma").html("");  

        $("#imgCoronograma").append(response);

    });

}