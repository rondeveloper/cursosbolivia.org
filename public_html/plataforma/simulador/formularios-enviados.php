<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* content get */
$get_ = explode('/', str_replace('.html', '', get('seccion')));
for ($cn_ge = count($get_); $cn_ge > 0; $cn_ge--) {
    if ($get_[$cn_ge - 1] != '') {
        $get[$cn_ge] = $get_[$cn_ge - 1];
    }
}

?>
<!DOCTYPE html>
<!-- saved from url=(0014)about:internet -->
<html class=" bgpositionshorthand bgpositionxy bgrepeatround bgrepeatspace bgsizecover borderradius cssanimations csscalc csstransforms supports csstransforms3d preserve3d csstransitions no-flexboxtweener fontface svg svgasimg svgclippaths svgfilters svgforeignobject inlinesvg smil localstorage sessionstorage websqldatabase multiplebgs">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>SIGEP - Mefp</title>
    <!--<base href="./">-->
    <base href=".">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="icon" type="image/x-icon" href="https://sigep.sigma.gob.bo/rsseguridad/favicon.ico">
    <link rel="stylesheet" href="contenido/alt/styles.css">
    <style type="text/css">
        /* Chart.js */
        @-webkit-keyframes chartjs-render-animation {
            from {
                opacity: 0.99
            }

            to {
                opacity: 1
            }
        }

        @keyframes chartjs-render-animation {
            from {
                opacity: 0.99
            }

            to {
                opacity: 1
            }
        }

        .chartjs-render-monitor {
            -webkit-animation: chartjs-render-animation 0.001s;
            animation: chartjs-render-animation 0.001s;
        }
    </style>
    <style></style>
    <script charset="utf-8" src="contenido/alt/7-es2015.js.descarga"></script>
    <style type="text/css">
        .jqstooltip {
            position: absolute;
            left: 0px;
            top: 0px;
            visibility: hidden;
            background: rgb(0, 0, 0) transparent;
            background-color: rgba(0, 0, 0, 0.6);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
            color: white;
            font: 10px arial, san serif;
            text-align: left;
            white-space: nowrap;
            padding: 5px;
            border: 1px solid white;
            box-sizing: content-box;
            z-index: 10000;
        }

        .jqsfield {
            color: white;
            font: 10px arial, san serif;
            text-align: left;
        }
    </style>
    <style>
        .card[_ngcontent-hmx-c2] .table[_ngcontent-hmx-c2],
        .panel[_ngcontent-hmx-c2] .table[_ngcontent-hmx-c2] {
            border-top: 1px solid #eee !important
        }

        .padre[_ngcontent-hmx-c2] {
            text-align: center !important
        }

        .hijo[_ngcontent-hmx-c2] {
            padding: 10px !important;
            margin: 10px !important;
            display: inline-block !important
        }

        .modal-content[_ngcontent-hmx-c2] {
            top: 100px
        }

        .modal-lg[_ngcontent-hmx-c2] {
            max-width: 72% !important
        }

        .form-inline[_ngcontent-hmx-c2] label[_ngcontent-hmx-c2] {
            font-weight: 700
        }
    </style>
    <style>
        .card[_ngcontent-hmx-c3] .table[_ngcontent-hmx-c3],
        .panel[_ngcontent-hmx-c3] .table[_ngcontent-hmx-c3] {
            border-top: 1px solid #eee !important
        }

        .padre[_ngcontent-hmx-c3] {
            text-align: center !important
        }

        .hijo[_ngcontent-hmx-c3] {
            padding: 10px !important;
            margin: 10px !important;
            display: inline-block !important
        }

        .modal-lg[_ngcontent-hmx-c3] {
            max-width: 72% !important
        }
    </style>
    <style type="text/css" id="appthemes">
        body,
        .wrapper .section-container {
            background-color: #f5f7fa
        }

        .wrapper .aside-container {
            background-color: #3a3f51
        }

        .topnavbar {
            background-color: #1797be;
            background-image: -webkit-gradient(linear, left top, right top, from(#1797be), to(#23b7e5));
            background-image: linear-gradient(to right, #1797be 0%, #23b7e5 100%);
            background-repeat: repeat-x
        }

        @media(min-width: 992px) {

            .topnavbar .navbar-nav>.nav-item.show>.nav-link,
            .topnavbar .navbar-nav>.nav-item.show>.nav-link:hover,
            .topnavbar .navbar-nav>.nav-item.show>.nav-link:focus {
                box-shadow: 0 -3px 0 #1381a3 inset
            }
        }

        .topnavbar .navbar-nav>.nav-item>.navbar-text {
            color: #fff
        }

        .topnavbar .navbar-nav>.nav-item>.nav-link,
        .topnavbar .navbar-nav>.nav-item.show>.nav-link {
            color: #fff
        }

        .topnavbar .navbar-nav>.nav-item>.nav-link:hover,
        .topnavbar .navbar-nav>.nav-item>.nav-link:focus,
        .topnavbar .navbar-nav>.nav-item.show>.nav-link:hover,
        .topnavbar .navbar-nav>.nav-item.show>.nav-link:focus {
            color: #0c4f63
        }

        .topnavbar .dropdown-item.active,
        .topnavbar .dropdown-item:active {
            background-color: #1797be
        }

        .sidebar {
            background-color: #3a3f51
        }

        .sidebar .nav-heading {
            color: #919da8
        }

        .sidebar-nav>li>a,
        .sidebar-nav>li>.nav-item {
            color: #e1e2e3
        }

        .sidebar-nav>li>a:focus,
        .sidebar-nav>li>a:hover,
        .sidebar-nav>li>.nav-item:focus,
        .sidebar-nav>li>.nav-item:hover {
            color: #1797be
        }

        .sidebar-nav>li>a>em,
        .sidebar-nav>li>.nav-item>em {
            color: inherits
        }

        .sidebar-nav>li.active,
        .sidebar-nav>li.active>a,
        .sidebar-nav>li.active>.nav-item,
        .sidebar-nav>li.active .sidebar-nav,
        .sidebar-nav>li.open,
        .sidebar-nav>li.open>a,
        .sidebar-nav>li.open>.nav-item,
        .sidebar-nav>li.open .sidebar-nav {
            background-color: #383d4e;
            color: #1797be
        }

        .sidebar-nav>li.active>.nav-item>em,
        .sidebar-nav>li.active>a>em,
        .sidebar-nav>li.open>.nav-item>em,
        .sidebar-nav>li.open>a>em {
            color: #1797be
        }

        .sidebar-nav>li.active {
            border-left-color: #1797be
        }

        .sidebar-subnav {
            background-color: #3a3f51
        }

        .sidebar-subnav>.sidebar-subnav-header {
            color: #e1e2e3
        }

        .sidebar-subnav>li>a,
        .sidebar-subnav>li>.nav-item {
            color: #e1e2e3
        }

        .sidebar-subnav>li>a:focus,
        .sidebar-subnav>li>a:hover,
        .sidebar-subnav>li>.nav-item:focus,
        .sidebar-subnav>li>.nav-item:hover {
            color: #1797be
        }

        .sidebar-subnav>li.active>a,
        .sidebar-subnav>li.active>.nav-item {
            color: #1797be
        }

        .sidebar-subnav>li.active>a:after,
        .sidebar-subnav>li.active>.nav-item:after {
            border-color: #1797be;
            background-color: #1797be
        }

        .offsidebar {
            border-left: 1px solid greyscale(#cccccc);
            background-color: #fff;
            color: #656565
        }
    </style>
    <style>
        .modal-wrapper[_ngcontent-hmx-c7] {
            display: -webkit-box !important;
            display: flex !important;
            -webkit-box-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            align-items: center
        }

        .modal-content[_ngcontent-hmx-c7] {
            top: 1px
        }

        .modal-content-msg[_ngcontent-hmx-c7] {
            margin: 0;
            padding: 0 0 0 5
        }

        .boldcito[_ngcontent-hmx-c7] {
            font-weight: 700
        }

        .nodo-error[_ngcontent-hmx-c7] {
            position: absolute;
            bottom: 9%;
            left: 2%
        }

        @media(max-width:360px) {
            .nodo-error[_ngcontent-hmx-c7] {
                bottom: 7%;
                left: 3%
            }
        }
    </style>
    <style>
        a.submenu[_ngcontent-hmx-c11] {
            margin: 0 0 0 10px
        }

        a[_ngcontent-hmx-c11] {
            white-space: normal !important
        }
    </style>
    <style>
        .home-container[_ngcontent-hmx-c12] {
            margin: 0 auto;
            max-width: 960px
        }

        .home-container[_ngcontent-hmx-c12] .home-logo[_ngcontent-hmx-c12] {
            width: 240px
        }

        .home-container[_ngcontent-hmx-c12] .home-text[_ngcontent-hmx-c12] {
            text-align: center
        }

        @media(min-width:992px) {
            .home-container[_ngcontent-hmx-c12] .home-text[_ngcontent-hmx-c12] {
                text-align: left
            }
        }

        .home-container[_ngcontent-hmx-c12] .home-text-big[_ngcontent-hmx-c12] {
            font-size: 3.9375rem
        }
    </style>
    <style>
        .pointer[_ngcontent-hmx-c9] {
            cursor: pointer
        }

        .bloqueoDD[_ngcontent-hmx-c9] {
            display: block
        }

        .bloqueoDN[_ngcontent-hmx-c9] {
            display: none
        }

        .pnotbell[_ngcontent-hmx-c9] {
            width: 180px;
            word-wrap: break-word
        }
    </style>

    <script>
        let sw_menu = true;

        function handle_menu() {
            var element = document.getElementById("main-layout");
            if (sw_menu) {
                element.classList.add("aside-collapsed");
            } else {
                element.classList.remove("aside-collapsed");
            }
            sw_menu = !sw_menu;
        }
    </script>
</head>

<body>
    <app-root _nghost-hmx-c0="" ng-version="9.0.0" class="layout-fixed" id="main-layout">
        <router-outlet _ngcontent-hmx-c0=""></router-outlet>
        <app-layout _nghost-hmx-c1="">
            <div _ngcontent-hmx-c1="" class="wrapper">
                <div _ngcontent-hmx-c1="" id="sigep-modals"></div>
                <sigep-cambiaperfil _ngcontent-hmx-c1="" _nghost-hmx-c2="">
                    <!---->
                </sigep-cambiaperfil>
                <sigep-cambiarupe _ngcontent-hmx-c1="" _nghost-hmx-c3="">
                    <!---->
                </sigep-cambiarupe>
                <app-header _ngcontent-hmx-c1="" class="topnavbar-wrapper" _nghost-hmx-c4="">
                    <nav _ngcontent-hmx-c4="" class="navbar topnavbar" role="navigation">
                        <div _ngcontent-hmx-c4="" class="vdd navbar-header"><a _ngcontent-hmx-c4="" class="navbar-brand" href="">
                                <div _ngcontent-hmx-c4="" class="brand-logo"><img _ngcontent-hmx-c4="" alt="App Logo" class="img-responsive" src="contenido/alt/logo.png"></div>
                                <div _ngcontent-hmx-c4="" class="brand-logo-collapsed"><img _ngcontent-hmx-c4="" alt="App Logo" class="img-responsive" src="contenido/alt/logo-single.png"></div>
                            </a></div>
                        <div _ngcontent-hmx-c4="" class="vdd buttons-operation d-flex mr-auto">
                            <ul _ngcontent-hmx-c4="" class="nav navbar-nav flex-row mr-auto">
                                <li _ngcontent-hmx-c4="" class="nav-item">
                                    <!----><a _ngcontent-hmx-c4="" class="nav-link d-none d-md-block d-lg-block d-xl-block" trigger-resize="" onclick="handle_menu();"><em _ngcontent-hmx-c4="" class="fas fa-bars"></em></a><a _ngcontent-hmx-c4="" class="nav-link sidebar-toggle d-md-none"><em _ngcontent-hmx-c4="" class="fas fa-bars"></em></a>
                                </li>
                                <!---->
                                <li _ngcontent-hmx-c4="" class=" nav-item"><a _ngcontent-hmx-c4="" class="nav-link"><em _ngcontent-hmx-c4="" class="icon-user"></em></a></li>
                                <li _ngcontent-hmx-c4="" class="nav-item"><a _ngcontent-hmx-c4="" class="nav-link" title="Cambiar Perfil"><em _ngcontent-hmx-c4="" class="icon-people"></em></a></li>
                                <!---->
                                <li _ngcontent-hmx-c4="" class="nav-item"><a _ngcontent-hmx-c4="" class="nav-link" title="Cambiar Rupe"><em _ngcontent-hmx-c4="" class="icon-user-following"></em></a></li>
                                <li _ngcontent-hmx-c4="" class="nav-item"><a _ngcontent-hmx-c4="" class="nav-link" title="Bloquear pantalla"><em _ngcontent-hmx-c4="" class="icon-lock"></em></a></li>
                                <li _ngcontent-hmx-c4="" class="nav-item"><a _ngcontent-hmx-c4="" class="nav-link" title="Cerrar Session"><em _ngcontent-hmx-c4="" class="icon-logout"></em></a></li>
                                <!---->
                            </ul>
                        </div>
                        <div _ngcontent-hmx-c4="" class="vdd buttons-complement ml-auto">
                            <!---->
                            <scnot-bell-fragment _ngcontent-hmx-c4="" _nghost-hmx-c9="">
                                <ul _ngcontent-hmx-c9="" class="nav navbar-nav ml-auto">
                                    <li _ngcontent-hmx-c9="" class="nav-item ml-auto dropdown dropdown-list" dropdown=""><a _ngcontent-hmx-c9="" class="nav-link dropdown-toggle dropdown-toggle-nocaret" dropdowntoggle="" aria-haspopup="true"><em _ngcontent-hmx-c9="" class="icon-bell"></em>
                                            <!---->
                                        </a>
                                        <!---->
                                    </li>
                                </ul>
                            </scnot-bell-fragment>
                        </div>
                    </nav>
                </app-header>
                <app-sidebar _ngcontent-hmx-c1="" class="aside-container" _nghost-hmx-c5="">
                    <div _ngcontent-hmx-c5="" class="aside-inner">
                        <nav _ngcontent-hmx-c5="" class="sidebar" sidebar-anyclick-close="">
                            <ul _ngcontent-hmx-c5="" class="sidebar-nav">
                                <li _ngcontent-hmx-c5="" class="has-user-block">
                                    <app-userblock _ngcontent-hmx-c5="" _nghost-hmx-c10="">
                                        <!---->
                                        <div _ngcontent-hmx-c10="" class="item user-block">
                                            <div _ngcontent-hmx-c10="" class="user-block-info"><span _ngcontent-hmx-c10="" class="user-block-status">Datos Usuario</span><span _ngcontent-hmx-c10="" class="user-block-role">Usuario: RAQ204432300</span><span _ngcontent-hmx-c10="" class="user-block-role">
                                                    <!----> Gestión: 2021
                                                    <!----> Perfil: 233
                                                </span>
                                                <!---->
                                                <!---->
                                            </div>
                                            <!---->
                                            <div _ngcontent-hmx-c10="" class="user-block-info"><span _ngcontent-hmx-c10="" class="user-block-name">Datos Proveedor</span><span _ngcontent-hmx-c10="" class="user-block-role">RUPE: 229523</span><span _ngcontent-hmx-c10="" class="user-block-role">Tipo Documento: NIT</span><span _ngcontent-hmx-c10="" class="user-block-role">Documento: 2044323014</span><span _ngcontent-hmx-c10="" class="user-block-role" style="white-space: normal;">Razón Social: ALIAGA QUENTA RODOLFO REYNALDO</span></div>
                                        </div>
                                    </app-userblock>
                                </li>
                                <app-menu _ngcontent-hmx-c5="" _nghost-hmx-c11="">
                                    <!---->

                                </app-menu>
                            </ul>
                        </nav>
                    </div>
                </app-sidebar>
                <app-offsidebar _ngcontent-hmx-c1="" class="offsidebar" _nghost-hmx-c6=""></app-offsidebar>
                <section _ngcontent-hmx-c1="" class="section-container">
                    <div _ngcontent-hmx-c1="" class="content-wrapper" id="content-page">














                        <router-outlet _ngcontent-jbq-c1=""></router-outlet>



                        <br>

                        <div _ngcontent-jbq-c12="" class="row">
                            <div _ngcontent-jbq-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
                                <div _ngcontent-jbq-c12="" class="card card-default">
                                    <div _ngcontent-jbq-c12="" class="card-header">
                                        <div style="border-bottom: 1px solid #d2d2d2;padding: 10px;font-size: 17px;margin-bottom: 20px;">FORMULARIOS ENVIADOS</div>
                                    </div>
                                    <div _ngcontent-jbq-c12="" class="card-body">

                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th>#</th>
                                                <th>USUARIO</th>
                                                <th>Formulario A1</th>
                                                <th>Formulario A2B</th>
                                                <th>Formulario B1</th>
                                                <th>Formulario C1</th>
                                                <th>Formulario C2</th>
                                            </tr>
                                            <?php
                                            $cnt = 1;
                                            $rqde1 = query("SELECT u.nombres,u.apellidos,u.id FROM cursos_usuarios u WHERE u.id IN (select id_usuario from simulador_docs where DATE(fecha_upload)=CURDATE()) ORDER BY u.id ASC ");
                                            while ($rqde2 = fetch($rqde1)) {

                                                $url_form_1 = '';
                                                $fecha_form_1 = '';
                                                $url_form_2 = '';
                                                $fecha_form_2 = '';
                                                $url_form_3 = '';
                                                $fecha_form_3 = '';
                                                $url_form_4 = '';
                                                $fecha_form_4 = '';
                                                $url_form_5 = '';
                                                $fecha_form_5 = '';


                                                $rqdefr1 = query("SELECT d.codigo,d.nombre,d.fecha_upload FROM simulador_docs d WHERE d.id_usuario='" . $rqde2['id'] . "' AND DATE(fecha_upload)=CURDATE() ORDER BY d.id ASC ");
                                                while ($rqdefr2 = fetch($rqdefr1)) {
                                                    $url_img = $dominio_www . 'contenido/imagenes/doc-usuarios/' . $rqdefr2['nombre'];
                                                    switch ($rqdefr2['codigo']) {
                                                        case 'form-1':
                                                            $url_form_1 = $url_img;
                                                            $fecha_form_1 = date("d/m/Y H:i", strtotime($rqdefr2['fecha_upload']));
                                                            break;
                                                        case 'form-2':
                                                            $url_form_2 = $url_img;
                                                            $fecha_form_2 = date("d/m/Y H:i", strtotime($rqdefr2['fecha_upload']));
                                                            break;
                                                        case 'form-3':
                                                            $url_form_3 = $url_img;
                                                            $fecha_form_3 = date("d/m/Y H:i", strtotime($rqdefr2['fecha_upload']));
                                                            break;
                                                        case 'form-4':
                                                            $url_form_4 = $url_img;
                                                            $fecha_form_4 = date("d/m/Y H:i", strtotime($rqdefr2['fecha_upload']));
                                                            break;
                                                        case 'form-5':
                                                            $url_form_5 = $url_img;
                                                            $fecha_form_5 = date("d/m/Y H:i", strtotime($rqdefr2['fecha_upload']));
                                                            break;
                                                    }
                                                }


                                                $url_img = $dominio_www . 'contenido/imagenes/doc-usuarios/' . $rqde2['nombre'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $cnt++; ?></td>
                                                    <td><?php echo $rqde2['nombres'] . ' ' . $rqde2['apellidos']; ?></td>
                                                    <td><?php if($url_form_1!=''){ ?><a href="<?php echo $url_form_1; ?>" target="_blank">VISUALIZAR</a><?php }else{ echo 'Sin archivo'; } ?></td>
                                                    <td><?php if($url_form_2!=''){ ?><a href="<?php echo $url_form_2; ?>" target="_blank">VISUALIZAR</a><?php }else{ echo 'Sin archivo'; } ?></td>
                                                    <td><?php if($url_form_3!=''){ ?><a href="<?php echo $url_form_3; ?>" target="_blank">VISUALIZAR</a><?php }else{ echo 'Sin archivo'; } ?></td>
                                                    <td><?php if($url_form_4!=''){ ?><a href="<?php echo $url_form_4; ?>" target="_blank">VISUALIZAR</a><?php }else{ echo 'Sin archivo'; } ?></td>
                                                    <td><?php if($url_form_5!=''){ ?><a href="<?php echo $url_form_5; ?>" target="_blank">VISUALIZAR</a><?php }else{ echo 'Sin archivo'; } ?></td>
                                                   
                                                </tr>
                                            <?php
                                            }

                                            ?>
                                        </table>


                                    </div>
                                </div>
                            </div>
                        </div>
                        </procesos-subasta-list-screen>









                    </div>
                </section>
                <sigep-mensaje _ngcontent-hmx-c1="" _nghost-hmx-c7="">
                    <div _ngcontent-hmx-c7="">
                        <div _ngcontent-hmx-c7="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade" data-backdrop="”static”" data-keyboard="”false”" role="dialog" tabindex="-1">
                            <div _ngcontent-hmx-c7="" class="modal-dialog modal-lg">
                                <div _ngcontent-hmx-c7="" class="modal-content">
                                    <div _ngcontent-hmx-c7="" class="modal-header">
                                        <h4 _ngcontent-hmx-c7=""><em _ngcontent-hmx-c7="" class="fa fa-exclamation-triangle " style="color:#cc0000;"></em> ERROR</h4><button _ngcontent-hmx-c7="" aria-label="Close" class="close" type="button"><span _ngcontent-hmx-c7="" aria-hidden="true">×</span></button>
                                        <!---->
                                    </div>
                                    <!---->
                                    <div _ngcontent-hmx-c7="" class="modal-footer"><button _ngcontent-hmx-c7="" class="btn test/insbtn-primary" data-dismiss="modal" type="button">Cerrar</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </sigep-mensaje>
                <footer _ngcontent-hmx-c1="" app-footer="" class="footer-container" _nghost-hmx-c8=""><span _ngcontent-hmx-c8="">© 2021 - SIGEP &nbsp;&nbsp;&nbsp; <b>(SITIO WEB NO OFICIAL / SITIO WEB SIMULADOR EXCLUSIVO DE CURSOSBOLIVIA.ORG)</b></span></footer>
            </div>
        </app-layout>
    </app-root>
    <div class="preloader-hidden">
        <div class="preloader-progress">
            <div class="preloader-progress-bar" style="width: 100%;"></div>
        </div>
    </div>
    <script src="contenido/alt/runtime-es2015.js.descarga" type="module"></script>
    <script src="contenido/alt/runtime-es5.js.descarga" nomodule="" defer=""></script>
    <script src="contenido/alt/polyfills-es5.js.descarga" nomodule="" defer=""></script>
    <script src="contenido/alt/polyfills-es2015.js.descarga" type="module"></script>
    <script src="contenido/alt/scripts.js.descarga" defer=""></script>
    <script src="contenido/alt/vendor-es2015.js.descarga" type="module"></script>
    <script src="contenido/alt/vendor-es5.js.descarga" nomodule="" defer=""></script>
    <script src="contenido/alt/main-es2015.js.descarga" type="module"></script>
    <script src="contenido/alt/main-es5.js.descarga" nomodule="" defer=""></script>

    <script>
        let content_page = document.getElementById("content-page");
        let sw_menu_cont = true;

        function boton_uno() {
            var element = document.getElementById("cont-menu");
            if (sw_menu_cont) {
                element.innerHTML = '<!----><li _ngcontent-add-c11=""><!----><a onclick="boton_uno();" style="white-space: pre-wrap;" _ngcontent-add-c11="" title="Registro Único de Proveedores del Estado"><span _ngcontent-add-c11="" class="float-right"></span><em _ngcontent-add-c11="" class="icon-arrow-left-circle"></em><span _ngcontent-add-c11="" class="menu">Registro Único de Proveedores del Estado</span></a><!----><!----><a _ngcontent-add-c11="" style="padding-left: 30px;" styclass="submenu"  title="Registro de productos"><!----><em _ngcontent-add-c11="" class="fa fa-circle"></em><span _ngcontent-add-c11="">Registro de productos</span></a></li><li _ngcontent-add-c11=""><!----><!----><a onclick="boton_dos();" _ngcontent-add-c11="" style="padding-left: 30px;" class="submenu" title="Procesos de Contratación"><em _ngcontent-add-c11="" class="icon-arrow-right-circle"></em><span _ngcontent-add-c11="" class="menu">Procesos de Contratación</span></a><!----></li>';
            } else {
                element.innerHTML = '<!----><li _ngcontent-add-c11=""><!----><!----><a onclick="boton_uno();" _ngcontent-add-c11="" class="submenu" title="Registro Único de Proveedores del Estado"><em _ngcontent-add-c11="" class="icon-arrow-right-circle"></em><span _ngcontent-add-c11="" class="menu">Registro Único de Proveedores del Estado</span></a><!----></li>';
            }
            sw_menu_cont = !sw_menu_cont;
        }
        let sw_menu_cont_2 = true;

        function boton_dos() {
            var element = document.getElementById("cont-menu");
            if (sw_menu_cont_2) {
                element.innerHTML = '<!----><li _ngcontent-add-c11=""><!----><a onclick="boton_uno();" _ngcontent-add-c11="" title="Procesos de Contratación"><span _ngcontent-add-c11="" class="float-right"></span><em _ngcontent-add-c11="" class="icon-arrow-left-circle"></em><span _ngcontent-add-c11="" class="menu">Procesos de Contratación</span></a><!----><!----><a _ngcontent-add-c11="" onclick="page_mis_documentos();" class="submenu" style="padding-left:30px;" title="Mis Documentos"><!----><em _ngcontent-add-c11="" class="fa fa-circle"></em><span _ngcontent-add-c11="">Mis Documentos</span></a></li><li _ngcontent-add-c11=""><!----><!----><!----><a _ngcontent-add-c11="" class="submenu" onclick="page_subastas();" style="padding-left:30px;" title="Subastas Electrónicas"><!----><em _ngcontent-add-c11="" class="fa fa-circle"></em><span _ngcontent-add-c11="">Subastas Electrónicas</span></a></li>';
            } else {
                element.innerHTML = '<!----><li _ngcontent-add-c11=""><!----><!----><a onclick="boton_uno();" _ngcontent-add-c11="" class="submenu" title="Registro Único de Proveedores del Estado"><em _ngcontent-add-c11="" class="icon-arrow-right-circle"></em><span _ngcontent-add-c11="" class="menu">Registro Único de Proveedores del Estado</span></a><!----></li>';
            }
            sw_menu_cont_2 = !sw_menu_cont_2;
        }

        function page_subastas() {
            fetch('contenido/pages/ajax/page_subastas.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    //alert(text);
                    content_page.innerHTML = text;
                })
                .catch(function(error) {
                    log('Request failed', error)
                });
        }
        let sw_dropdown_1 = false;

        function dropdown_btn_opciones() {
            var element = document.getElementById("dropdown-autoclose1");
            if (sw_dropdown_1) {
                element.style.display = 'none';
            } else {
                element.style.display = 'block';
            }
            sw_dropdown_1 = !sw_dropdown_1;
        }
    </script>

    <script>
        function page_mis_documentos() {
            fetch('contenido/pages/ajax/page_mis_documentos.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                })
                .catch(function(error) {
                    log('Request failed', error)
                });
        }
    </script>

    <script>
        function page_join_subasta() {
            fetch('contenido/pages/ajax/page_join_subasta.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                    contador_subasta();
                })
                .catch(function(error) {
                    log('Request failed', error)
                });
        }

        function contador_subasta() {
            let cnt = 7;
            var timerID = setInterval(() => {
                //alert('yep'+cnt);
                document.getElementById("counter").innerHTML = cnt;
                cnt--;
                if (cnt < 1) {
                    clearInterval(timerID);
                    start_subasta();
                }
            }, 1000);
        }

        function start_subasta() {
            var cont_subasta = document.getElementById("cont-subasta");
            fetch('contenido/pages/ajax/start_subasta.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_subasta.innerHTML = text;
                    let cnt_update_ID = 0;
                    var timer_update_ID = setInterval(() => {
                        actualizar_estado_subasta();
                        cnt++;
                        if (cnt > 150) {
                            clearInterval(timer_update_ID);
                            alert('LA SUBASTA TERMINO');
                            document.getElementById("btn_envprop").style.display = 'none';
                        }
                    }, 2000);
                })
                .catch(function(error) {
                    log('Request failed', error)
                });
        }
    </script>

    <script>
        function page_nuevo_documento() {
            fetch('contenido/pages/ajax/page_nuevo_documento.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                })
                .catch(function(error) {
                    log('Request failed', error)
                });
        }
    </script>

    <script>
        function enviar_propuesta() {
            var cont_panel_estado_subasta = document.getElementById("panel-estado-subasta");
            const monto = document.getElementById("monto-propuesta").value;
            fetch('contenido/pages/ajax/enviar_propuesta.php?monto=' + monto)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_panel_estado_subasta.innerHTML = text;
                    document.getElementById("monto-propuesta").value = 0;
                })
                .catch(function(error) {
                    log('Request failed', error)
                });
        }
    </script>

    <script>
        function actualizar_estado_subasta() {
            var cont_panel_estado_subasta = document.getElementById("panel-estado-subasta");
            fetch('contenido/pages/ajax/enviar_propuesta.php?monto=0')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_panel_estado_subasta.innerHTML = text;
                })
                .catch(function(error) {
                    log('Request failed', error)
                });
        }
    </script>

    <script>
        function busqueda() {
            let content_table = document.getElementById("tablaValues");
            const busc = document.getElementById("input-busc").value;
            fetch('contenido/pages/ajax/busqueda.php?busc=' + busc)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_table.innerHTML = text;
                })
                .catch(function(error) {
                    log('Request failed', error)
                });
        }
    </script>


</body>

</html>