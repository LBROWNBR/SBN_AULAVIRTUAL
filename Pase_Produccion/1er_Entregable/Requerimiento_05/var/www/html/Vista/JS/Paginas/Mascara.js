var v_codcha = "";

$(document).ready(function() {
    validate_Login();
    fnListarCursosdiv();
    fnListarCursosCarousel();
    // $("#h4Subtitulos").html("INICIO" + "</br>");
    fncodcapcha();
    $("#btnlogin").prop("disabled", true);
    var yearNow = new Date().getFullYear();
    $('#page-anio-now').text(yearNow);
});

$(".nav.nav-pills .nav-item .nav-link").click(function(e) {
    e.preventDefault();
    var menu = $(this).text();
    switch (menu) {
        case 'AULA VIRTUAL':
            window.open(this['href'], '_blank')
            break;
        default:
            $(".nav.nav-pills .nav-item .nav-link").removeClass('active');
            $(this).addClass('active');
            break;
    }
    // console.log(menu);
});

$('.navbar-nav .nav-item .nav-link').click(function(e) {
    $(".navbar-nav .nav-item .nav-link").removeClass('active');
    $(this).addClass('active');
});

$('.navbar-nav>li>a').on('click', function() {
    $('.navbar-collapse').collapse('hide');
});

var init = () => {
    var mover = false;
    $("body").mousemove(function() {
        mover = true;
    });

    setInterval(function() {
        if (!mover) {
            CesarSession();
        } else {
            mover = false;
        }
    }, 600000);

}

var validate_Login = () => {
    $.post("../app/controller/login.php", {
        op: 'validate_login',
    }, function(data) {
        var datos = JSON.parse(data);
        if (datos['session'] == true) {
            SBN_SESSION = datos['session'];
            SBN_USER = datos['data']['user'];
            SBN_ROLE = datos['data']['role'];
            fnValidarUsuarios();
            init();
        } else {
            SBN_SESSION = false;
            SBN_USER = null;
            SBN_ROLE = null;
            fnValidarUsuarios();
        }
    });
}

var SBN_SESSION = false;
var SBN_USER = null;
var SBN_ROLE = null;

function fnValidarUsuarios() {
    var btnlogins = "";

    if (SBN_SESSION == false) {
        btnlogins = "<button id='btnInicarsession' name='btnInicarsession' style='height:40px'class='btn btn-black' onclick='ShowModalLogin()'><span style='font-size: 17px'> Iniciar session</span></button>";
        $("#divLogin").html(btnlogins);
        $(".CSSSESSION").hide();
    } else {
        btnlogins += "<div class='btn-group' role='group'>";
        btnlogins += "<button id='btnCerrarsession' type='button' class='btn btn-secondary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
        btnlogins += SBN_USER;
        btnlogins += "</button>";
        btnlogins += "<div class='dropdown-menu' aria-labelledby='btnCerrarsession'>";
        $(".CSSSESSION").show();
        if (SBN_ROLE == 1) {
            btnlogins += "<a class='dropdown-item' onclick='fnMantUsuario()'><i class='fas fa-users'></i> Usuarios</a>";
        }
        var rutaVistaReporte = 'fnhrefpagina("../Vista/ViewReportes.html")';
        var rutaVistaMatricuSP = 'fnhrefpagina("../Vista/ViewMatriculaSP.html")';
        btnlogins += "<a class='dropdown-item' onclick='fnMantProfesor()'><i class='fas fa-chalkboard-teacher'></i> Docentes</a>";
        btnlogins += "<a class='dropdown-item' onclick='" + rutaVistaMatricuSP + "'><i class='fas fa-address-book'></i>&nbsp; Participantes</a>";
        btnlogins += "<a class='dropdown-item' onclick='" + rutaVistaReporte + "'><i class='fas fa-file-alt'></i>&nbsp; Reportes</a>";
        btnlogins += "<a class='dropdown-item' onclick='changePassword()'><i class='fas fa-unlock'></i>&nbsp; Cambiar contraseña</a>";
        btnlogins += "<a class='dropdown-item' onclick='CesarSession()'><i class='fas fa-sign-out-alt'></i>&nbsp; Cerrar session</a>";
        btnlogins += "</div>";
        btnlogins += "</div>";
        $("#divLogin").html(btnlogins);
    }
}

var fnMantProfesor = () => {
    fnhrefpagina("../Vista/ViewProfesores.html");

}

function fnMantUsuario() {
    if (SBN_ROLE == 1) {
        fnhrefpagina("../Vista/ViewUsuarios.html");
    }
}


function CesarSession() {
    $.post("../app/controller/login.php", {
        op: 'cerrar_session',
    }, function(data) {
        var datos = JSON.parse(data);
        if (datos['session'] == false) {
            SBN_SESSION = false;
            SBN_USER = null;
            SBN_ROLE = null;
            // fnValidarUsuarios();
            location.reload();
        }
    });
}


/*
function fnListarCursosdiv() {
    $.get("../servicios/Mascara/Lista_cursos.php", function (response) {
        $("#divListaCursos").html(response);
    });
};
*/

function fnListarCursosdiv() {

    $.post("../app/controller/login.php", {
        op: 'validate_login',
    }, function(data) {
        var datos = JSON.parse(data);
        if (datos['session'] == true) {
            SBN_SESSION = datos['session'];
            SBN_USER = datos['data']['user'];
            SBN_ROLE = datos['data']['role'];

            if (SBN_ROLE == 1) {
                $.get("../servicios/Mascara/Lista_cursos.php", { rol: 'admin', }, function(response) {
                    $("#divListaCursos").html(response);
                });
            } else {
                $.get("../servicios/Mascara/Lista_cursos.php", { rol: '', }, function(response) {
                    $("#divListaCursos").html(response);
                });
            }

        } else {
            $.get("../servicios/Mascara/Lista_cursos.php", { rol: '', }, function(response) {
                $("#divListaCursos").html(response);
            });
        }

    });

};

function fnListarCursosCarousel() {

    $.get("../servicios/Mascara/Lista_Cursos_Carousel.php", function(response) {
        $("#divcarouselimgs").html(response);
        //alert(response);
    });
};

function fnMostrarSubTitulo(SubTitulo) {

    $("#h4Subtitulos").html(SubTitulo + "</br>" + "</br>");

}



function ShowModalLogin() {
    $("#MdlLogeo").modal("show");
};



function fnDetalleCurso(idcurso, nombrecurso, imagen) {

    sessionStorage.setItem("sesidcurso", idcurso);
    sessionStorage.setItem("sesnombrecurso", nombrecurso);
    sessionStorage.setItem("sesimagen", imagen);

    $.get("../servicios/Mascara/get_DetalleCurso.php?idcurso=" + idcurso, function(response) {

        var arreglo = JSON.parse(response);
        var fila1 = arreglo["0"]
            /*  console.log(arreglo);
              console.log(arreglo.cur_Nombre);
              console.log(arreglo["0"]);*/
        $('#MdlDesCurso').modal('show');

        // $("#lblTituloCursomdl").html(fila1.cur_Nombre);
        $("#lblDescripcioncurso").html(fila1.cur_descripcion);
        // $("#stgFechainicio").html(fila1.cur_FechaInicio);
        // $("#stgFechaDuracion").html(fila1.cur_FechaInicio + " - " + fila1.cur_FechaFin);

        // $("#imgMdlDetalleCurso").attr("src", ".." + sessionStorage.getItem("sesimagen"));
    });


}



function hrefmatricula() {
    // console.log("Inicio");

    $('#MdlDesCurso').modal('hide');
    /* sessionStorage.setItem("sesidcurso", idcurso);
     sessionStorage.setItem("sesnombrecurso", nombrecurso);
     sessionStorage.setItem("sesimagen", imagen);*/

    if (SBN_USER == null) {
        fnhrefpagina("../Vista/ViewMatricula.html");
        $("#DivContenido").prop("class", "col-md-12");

    } else {

        fnhrefpagina("../Vista/ViewMantMatricula.html");
        $("#DivContenido").prop("class", "col-md-12");
    }

};


function fnhrefpagina(ruta) {

    $("#DivContenido").prop("class", "container");
    if (ruta == "../Vista/ViewCronograma.html" || ruta == "../Vista/ViewMatricula.html" || ruta == "../Vista/contactenos.html" || ruta == "../Vista/ViewPreguntasFrec.html") {
        fnpintarpaginas(ruta);
    } else {

        // alert(sessionStorage.getItem("SES_USER_NAME"));
        if (SBN_SESSION == false) {
            //	paginaurl ="../Vista/login.html";
            ShowModalLogin();
        }
        if (SBN_SESSION == true) {
            if (SBN_ROLE == 0 || SBN_ROLE == 1) {
                fnpintarpaginas(ruta);
            }

        }
    }


}

function fnpintarpaginas(paginaurl) {
    var DivContenido = $("#DivContenido");
    $.get(paginaurl, function(response) {
        DivContenido.html(response);
    });
}


function fnrefrescar() {
    location.reload();
}


function cargarValidacionesCursos() {

    $.validator.setDefaults({
        debug: true,
        success: "valid"
    });

    $("#frmCursos").validate({
        rules: {
            txt_username: {
                required: true,
                email: true
            },
            txt_password: {
                required: true,
                email: true
            },
        },
        messages: {
            txt_username: {
                required: MSG_DATO_OBLIGATORIO
            },
            txt_password: {
                required: MSG_DATO_OBLIGATORIO
            },

        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });
}

function fncodcapcha() {

    var caracteres = "abcdefghijkmnpqrtuvwxyzABCDEFGHJKMNPQRTUVWXYZ2346789";

    var codcapcha = "";

    for (i = 0; i < 3; i++) codcapcha += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
    v_codcha = codcapcha;
    //  return codcapcha;
    var htmlcapha = "<a onclick='fncodcapcha()'><i class='fas fa-sync'></i></a> <b><i> <s>" + codcapcha + "</s></i></b>";
    $("#divcodCapcha").html(htmlcapha);

}


function fnvalcapcha() {
    //disabled="true"
    if ($("#txtcapcha").val() == v_codcha) {

        $("#btnlogin").prop("disabled", false);

    } else {
        $("#btnlogin").prop("disabled", true);
    }

}