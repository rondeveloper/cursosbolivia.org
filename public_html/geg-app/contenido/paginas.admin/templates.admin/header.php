<?php
if (isset_get('seccion')) {
    $get = explode("/", str_replace(".adm", "", get('seccion')));
    for ($cn_ge = count($get); $cn_ge > 0; $cn_ge--) {
        $get[$cn_ge] = $get[$cn_ge - 1];
    }
}
?>
<!DOCTYPE html>
<html class=" js no-touch">

    <head>
        <meta name="viewport" content="width=device-width, maximum-scale=1, user-scalable=no"/>
        <base href="<?php echo $dominio; ?>" target="_self"/>
        <title>ADMIN - CURSOS.BO</title>
        <!--        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
        <link href="contenido/imagenes/images/favicon.png" type="icon/x-image" rel="icon">
        <link rel="stylesheet" href="contenido/css/bootstrap.min_1.3.css">
        <link rel="stylesheet" href="contenido/css/plugins_1.4.css">
        <link rel="stylesheet" href="contenido/css/main_1.4.css">
        <link rel="stylesheet" href="contenido/css/estilo.css">

<!--        <script src="contenido/js/funciones.js"></script>-->

        <script src="contenido/adm/jquery.min.js"></script>
        <script src="contenido/adm/bootstrap.min_1.3.js"></script>
        <script src="contenido/adm/plugins_1.4.js"></script>
        <script src="contenido/adm/app_1.4.js"></script>

        <script>
            function renueva_sesion() {
                //alert('prueba 2');
                $.ajax({
                    url: 'contenido/paginas.admin/ajax/ajax.renueva_sesion.php',
                    data: {},
                    type: 'POST',
                    dataType: 'html',
                    success: function(data) {
                        $("#box-renovar-sesion").html(data);
                    }
                });
            }
        </script>
        <script>
            function load_page(page, urldata, postdata) {
                var html_loading = '<div style="background: #a2a3c17d;height: 250%;width: 100%;left: 0px;top: 0px;position: absolute;z-index: 10;"><div style="text-align:center;padding-top:250px;"><img src="contenido/imagenes/images/loading.gif" style="width:20%;"/></div></div>'
                $("#LOADPAGELOADING").html(html_loading);
                if (GLOBAL_sw_menumovil_extendido) {
                    menu_close();
                }
                $("html, body").animate({scrollTop: 0}, 600);
                $.ajax({
                    url: 'contenido/paginas.admin/loadpages/lp.ajax__' + page + '.php',
                    type: 'POST',
                    data: {'data': page + '/' + urldata, 'postdata': postdata},
                    success: function(data) {
                        $("#LOADPAGEBOX").html(data);
                        $("#LOADPAGELOADING").html("");
                        if(urldata===''){
                            window.history.pushState(null, '', page +'.adm');
                        }else{
                            window.history.pushState(null, '', page + '/' + urldata+'.adm');
                        }
                    }
                });
            }
        </script>
        <style>
            #page-container, #sidebar, #sidebar-alt {
                <?php
                if (isset_organizador()) {
                    ?>
                    background-color: #536f6c !important;
                    <?php
                } else {
                    ?>
                    background-color: #005a12 !important;
                    <?php
                }
                ?>
            }
            .nav.navbar-nav-custom>li {
                background: rgba(12, 29, 18, 0.7) !important;
            }
        </style>
        <style>
            .hidden-sm{
            }
            .hidden-md{
                display: none;
            }
            @media screen and (max-width: 600px) {
                .breadcrumb {
                    display: none;
                }
                .hidden-sm{
                    display: none;
                }
                .hidden-md{
                    display: block;
                }
            }
            /* estilos para movil */
            @media (max-width: 600px) {
                .panel-body{
                    padding: 10px 5px;
                }
                .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12{
                    padding: 5px 5px;
                }
                .form-control {
                    font-size: 11px;
                    padding: 5px 4px;
                    height: auto;
                }
                .input-group-addon {
                    padding: 5px 8px;
                    font-size: 11px;
                }
                body {
                    font-size: 8pt;
                }
                #page-content {
                    padding: 7px 0px;
                }
                .page-header {
                    margin: 0px;
                    padding: 2px 7px;
                }
            }
        </style>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}
 
/* Ocultamos el checkbox html */
.switch input {
  display:none;
}
 
/* Formateamos la caja del interruptor sobre la cual se deslizará la perilla de control o slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}
 
/* Pintamos la perilla de control o slider usando el selector before */
.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}
 
/* Cambiamos el color de fondo cuando el checkbox esta activado */
input:checked + .slider {
  background-color: #80e08b;
}
 
/* Deslizamos el slider a la derecha cuando el checkbox esta activado */ 
input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}
 
/* Aplicamos efecto de bordes redondeados en slider y en el fondo del slider */
.slider.round {
  border-radius: 34px;
}
 
.slider.round:before {
  border-radius: 50%;
}
</style>
    </head>
    <body style="" onload="setInterval('renueva_sesion()', 70000);">
        <div id="page-container" class="sidebar-partial sidebar-visible-lg sidebar-no-animations">

            <div id="sidebar" style="z-index:950;box-shadow: grey 0px -1px 4px 1px;">
                <div class="sidebar-scroll">

                    <div class="sidebar-content">
                        <div class="row">
                            <div style="background: #31b312;">
                                <div style="padding:10px;">
                                    <img src="contenido/alt/logotipo-v3.png" alt="avatar" style='width:100%;'/>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="color:#FFF;padding: 5px;">
                            <div class="sidebar-user-name">
                                <?php
                                if (isset_organizador()) {
                                    $id_organizador = organizador('id');
                                    $rqda1 = query("SELECT titulo FROM cursos_organizadores WHERE id='$id_organizador' LIMIT 1 ");
                                    $rqda2 = mysql_fetch_array($rqda1);
                                    echo "<div style='background: #31b312;text-align: center;font-size: 9pt;'>ORGANIZADOR</div>";
                                    $nombre_usuario = $rqda2['titulo'];
                                    echo $nombre_usuario;
                                    echo "<br/>";

                                    $enlace_mi_cuenta = 'mi-cuenta-organizador.adm';
                                    $enlace_mi_cuenta_editar = 'mi-cuenta-organizador.adm';
                                } else {
                                    $id_administrador = administrador('id');
                                    $rqda1 = query("SELECT nombre FROM administradores WHERE id='$id_administrador' LIMIT 1 ");
                                    $rqda2 = mysql_fetch_array($rqda1);
                                    $nombre_usuario = $rqda2['nombre'];
                                    echo $nombre_usuario;

                                    $enlace_mi_cuenta = 'mi-cuenta.adm';
                                    $enlace_mi_cuenta_editar = 'mi-cuenta-editar.adm';
                                }
                                ?>
                            </div>
                            <div class="sidebar-user-links">
                                <a href="<?php echo $enlace_mi_cuenta; ?>" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Perfil de cuenta"><i class="fa fa-user fa-fw"></i></a>
                                <a  data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Mensajes"><i class="gi gi-envelope"></i></a>
                                <a  data-toggle="modal" class="enable-tooltip" data-placement="bottom" title="" data-original-title="Configuraci&oacute;n"><i class="gi gi-cogwheel"></i></a>
                                <a href="contenido/paginas.admin/procesos/salir.php" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Cerrar sesion"><i class="fa fa-ban fa-fw"></i></a>
                            </div>
                        </div>


                        <?php
                        include_once 'contenido/paginas.admin/items/item.menu_principal.php';
                        ?>

                        <div class="sidebar-header">
                            <span class="sidebar-header-options clearfix">
                                <a data-toggle="tooltip" title="" data-original-title="Refresh"><i class="gi gi-refresh"></i></a>
                            </span>
                        </div>

                        <div class="sidebar-section">
                            <div class="alert alert-success alert-alt">
                                <small id="box-renovar-sesion">not renew</small><br>
                                <i class="fa fa-thumbs-up fa-fw"></i> Sesion renovada
                            </div>
                        </div>
                        <!--
                        <div class="sidebar-header">
                            <span class="sidebar-header-options clearfix">
                                <a href="javascript:void(0)" data-toggle="tooltip" title="" data-original-title="Refresh"><i class="gi gi-refresh"></i></a>
                            </span>
                            <span class="sidebar-header-title">Actividad</span>
                        </div>
                        <div class="sidebar-section">
                            <div class="alert alert-success alert-alt">
                                <small>5 min atras</small><br>
                                <i class="fa fa-thumbs-up fa-fw"></i> Tu tienes una nueva Oferta
                            </div>
                            <div class="alert alert-info alert-alt">
                                <small>10 min atras</small><br>
                                <i class="fa fa-arrow-up fa-fw"></i> Actualizaci&oacute;n de Plan
                            </div>
                            <div class="alert alert-warning alert-alt">
                                <small>3 hours atras</small><br>
                                <i class="fa fa-exclamation fa-fw"></i> Corriendo en espacio<br><strong>18GB en uso</strong> 2GB left
                            </div>
                            <div class="alert alert-danger alert-alt">
                                <small>Ayer</small><br>
                                <i class="fa fa-bug fa-fw"></i> <a href="javascript:void(0)"><strong>Nuevo Participante Ingresado</strong></a>
                            </div>
                        </div>
                        -->
                    </div>
                </div>
            </div>
            <div id="main-container">
                <header class="navbar navbar-default" style="background: url(https://www.infosicoes.com/images/dashboard_header.jpg.size=6.img) center #000000;">
                    <script type="text/javascript">
                        var GLOBAL_sw_menumovil_extendido = false;
                        function menu() {
                            var doc = document.getElementById('sidebar');
                            doc.style.width = "200px";
                            doc.style.position = "absolute";
                            doc.style.top = "50px";
                            var doc2 = document.getElementById('btn_menu_top');
                            doc2.onclick = menu_close;
                            doc2.setAttribute = ("onClick", "menu_close();");
                            GLOBAL_sw_menumovil_extendido = true;
                        }
                        function menu_close() {
                            var doc = document.getElementById('sidebar');
                            doc.style.width = "0px";
                            doc.style.position = "absolute";
                            doc.style.top = "0px";
                            var doc2 = document.getElementById('btn_menu_top');
                            doc2.onclick = menu;
                            doc2.setAttribute = ("onClick", "menu();");
                            GLOBAL_sw_menumovil_extendido = false;
                        }
                    </script>
                    <ul class="nav navbar-nav-custom">
                        <li>
                            <a href="javascript:void(0)" title="MENU MOBILE" onclick="menu();" id="btn_menu_top" style="background:#1bbae1;color:#FFF;">
                                <i class="fa fa-bars fa-fw"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="nav navbar-nav-custom">
                        <div style="padding: 7px 50px;border-radius: 0px 20px 20px 0px;">
                            <strong style="color:#FFF;font-size:1.5vw;">CURSOS.BO</strong>
                            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                            <small  style="color:#FFF;font-size:1.1vw;">Bienvenid@ <?php echo $nombre_usuario; ?>!</small>
                        </div>
                    </div>

                    <ul class="nav navbar-nav-custom pull-right">

                        <li class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                            <!--<img src="contenido/adm/avatar2.jpg" alt="avatar"> <i class="fa fa-angle-down"></i>-->
                                <img src="https://www.infosicoes.com/contenido/imagenes/usuarios/admin.jpg" alt="avatar"> <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                                <li class="dropdown-header text-center">Perfil</li>
                                <!--
                                <li>
                                    <a href="">
                                        <i class="fa fa-clock-o fa-fw pull-right"></i>
                                        <span class="badge pull-right">0</span>
                                        Actualizaci&oacute;nes
                                    </a>
                                    <a href="">
                                        <i class="fa fa-envelope-o fa-fw pull-right"></i>
                                        <span class="badge pull-right">0</span>
                                        Mensajes
                                    </a>
                                    <a href=""><i class="fa fa-magnet fa-fw pull-right"></i>
                                        <span class="badge pull-right">0</span>
                                        Suscripciones
                                    </a>
                                    <a href="#//pixelcave.com/demo/proui/page_ready_faq.php"><i class="fa fa-question fa-fw pull-right"></i>
                                        <span class="badge pull-right">0</span>
                                        Planes
                                    </a>
                                </li>
                                -->
                                <li class="divider"></li>
                                <li>
                                    <a href="<?php echo $enlace_mi_cuenta; ?>">
                                        <i class="fa fa-user fa-fw pull-right"></i>
                                        Perfil
                                    </a>
                                    <a href="<?php echo $enlace_mi_cuenta_editar; ?>">
                                        <i class="fa fa-cog fa-fw pull-right"></i>
                                        Configuracion
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
<!--                                    <a href=""><i class="fa fa-lock fa-fw pull-right"></i> Bloquear Cuenta</a>-->
                                    <a href="contenido/paginas.admin/procesos/salir.php"><i class="fa fa-ban fa-fw pull-right"></i> Salir</a>
                                </li>

                                <!--                                <li class="dropdown-header text-center">Actividad</li>
                                                                <li>
                                                                    <div class="alert alert-success alert-alt">
                                                                        <small>5 min atras</small><br>
                                                                        <i class="fa fa-thumbs-up fa-fw"></i> Tu tienes una nueva Oferta
                                                                    </div>
                                                                    <div class="alert alert-info alert-alt">
                                                                        <small>10 min atras</small><br>
                                                                        <i class="fa fa-arrow-up fa-fw"></i> Actualizaci&oacute;n de Plan
                                                                    </div>
                                                                    <div class="alert alert-warning alert-alt">
                                                                        <small>3 horas atras</small><br>
                                                                        <i class="fa fa-exclamation fa-fw"></i> Corriendo en espacio<br><strong>18GB en uso</strong> 2GB left
                                                                    </div>
                                                                    <div class="alert alert-danger alert-alt">
                                                                        <small>Ayer</small><br>
                                                                        <i class="fa fa-bug fa-fw"></i> <a href="javascript:void(0)" class="alert-link">Nuevo suscriptor</a>
                                                                    </div>
                                                                </li>-->

                            </ul>
                        </li>
                    </ul>
                </header>


                <div id="page-content" style="min-height: 894px;background:#FFF;">

                    <div style="float:left;width:100%;background:#FFF;">
                        <div id="LOADPAGELOADING"></div>
                        <div class=".col-md-12" id="LOADPAGEBOX">




