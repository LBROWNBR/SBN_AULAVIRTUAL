var URLactual = window.location;
var ID_USUARIO = "";

var USER_NAME = "";

var PASSWORD = "";

var USU_ROL = "";

var ID_USUARIOLOGEO = "";


function limpiarCombo(control, opcionCero, texto) {
    var combo = $("#" + control);
    combo.empty();
    // Valores por defecto
    opcionCero = typeof opcionCero !== 'undefined' ? opcionCero : 0;
    texto = typeof texto !== 'undefined' ? texto : "Seleccione";
    // Opción Cero
    if (opcionCero == 1) {
        if (texto)
            combo.html("<option value='0'>" + texto + "</option>");
    }
}

/*Paginacion de las tablas*/

function fnPintarTabla(idTabla) {

    var tablaDatos = $('#' + idTabla + '').DataTable({
        "lengthChange": true,
        "pageLength": 10,
        "lengthMenu": [10, 20, 30, 40],
        "paging": true,
        "ordering": true,
        "info": true,
        "searching": true,
        "compact": true,
        "order": [],
        "columnDefs": [{
            "targets": 'no-sort',
            "orderable": false,
        }],
        "language": idioma_espanol,
        "bDestroy": true,
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'csv', 'copy', 'print']
    });

    tablaDatos.on('order.dt search.dt', function() {
        tablaDatos.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    $('#' + idTabla + ' tbody').on('click', 'tr', function() {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            tablaDatos.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
}



function formatofecha(fecha) {
    V_fecha;
    V_fecha = fecha.replase('/', '-');
    return V_fecha;

}

/*
Si opcion es TRUE bloquea la pantalla, caso contrario la desbloquea.
El mensaje es opcional.
*/
function fasch_block(opcion, mensaje) {
    if (opcion) {
        if (typeof(mensaje) == "undefined" || mensaje == null) {
            mensaje = "Procesando...";
        }
        var atributos = {
            message: '<h3>' + mensaje + '</h3>',
            css: { backgroundColor: '#4a69bd', color: '#fff' }
        }
        $.blockUI(atributos);
    } else {
        $.unblockUI();
    }
}

// mensajes de alert

function fasch_info(message, size = 'sm', sizeMessage = '16px', title = 'SBN - INFO', funcion = false) {

    bootbox.alert({
        size: size,
        title: title,
        message: `<p style='color:#163E64 !important; font-size: ${sizeMessage} !important'>${message}</p>`,
        buttons: {
            ok: {
                label: 'Aceptar'
            }
        },
        callback: funcion
    });

}


///mensaje de eliminacion


function fasch_confirm(mensaje, funcion) {
    bootbox.confirm({
        title: "SBN - CONFIRMAR",
        message: mensaje,
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> NO',
                className: 'btn-danger'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> SI',
                className: 'btn-success'
            }
        },
        callback: funcion
    });
}
//mesajes de confirmacion

var MSGEliminar = "¿Está seguro de eliminar el registro?";

// Datatable español

const idioma_espanol = {
    "lengthMenu": "Agrupar _MENU_ filas por página",
    "zeroRecords": "No existen registros",
    "info": "Página _PAGE_ de _PAGES_",
    "infoEmpty": "No hay registros disponibles.",
    "infoFiltered": "(filtered from _MAX_ total records)",
    "search": "Filtrar:",
    "paginate": {
        "first": "Primero",
        "last": "Ultimo",
        "next": "Siguiente",
        "previous": "Anterior"
    }
}

//validacion fecha

$.validator.addMethod('checkDateFormat', function(value, element) {
    return this.optional(element) || /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(value);
}, "El formato de fecha es dd/mm/yyyy.");

/*Messajes de validacion */

MSG_DATO_OBLIGATORIO = "Campo obligatorio";
MSG_FORMATO_EMAIL = "Ingrese correo valido";
MSG_SOLO_NUMEROS = "Solo números";
MSG_SOLO_NUMEROS_ENTEROS = "Solo números enteros";

$(document).ready(function() {
    jQuery.extend(jQuery.validator.messages, {
        required: "Este campo es obligatorio.",
        remote: "Por favor, rellena este campo.",
        email: "Por favor, escribe una dirección de correo válida",
        url: "Por favor, escribe una URL válida.",
        date: "Por favor, escribe una fecha válida.",
        dateISO: "Por favor, escribe una fecha (ISO) válida.",
        number: "Por favor, escribe un número entero válido.",
        digits: "Por favor, escribe sólo dígitos.",
        creditcard: "Por favor, escribe un número de tarjeta válido.",
        equalTo: "Por favor, escribe el mismo valor de nuevo.",
        accept: "Por favor, escribe un valor con una extensión aceptada.",
        maxlength: jQuery.validator.format("Por favor, no escribas más de {0} caracteres."),
        minlength: jQuery.validator.format("Por favor, no escribas menos de {0} caracteres."),
        rangelength: jQuery.validator.format("Por favor, escribe un valor entre {0} y {1} caracteres."),
        range: jQuery.validator.format("Por favor, escribe un valor entre {0} y {1}."),
        max: jQuery.validator.format("Por favor, escribe un valor menor o igual a {0}."),
        min: jQuery.validator.format("Por favor, escribe un valor mayor o igual a {0}.")
    });
});