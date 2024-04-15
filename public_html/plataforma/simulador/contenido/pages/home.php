<?php
/* permisos */
$sw_acceso_envio_formularios = false;
$sw_acceso_compras_menores = false;
$sw_acceso_anpe_lp = false;
$sw_acceso_subastas = false;
if(usuario('sw_acceso_envio_formularios')=='1'){
    $sw_acceso_envio_formularios = true;
}
if(usuario('sw_acceso_compras_menores')=='1'){
    $sw_acceso_compras_menores = true;
}
if(usuario('sw_acceso_anpe_lp')=='1'){
    $sw_acceso_anpe_lp = true;
}
if(usuario('sw_acceso_subastas')=='1'){
    $sw_acceso_subastas = true;
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
        let sw_mobile_activated = false;

        function handle_menu() {
            var element = document.getElementById("main-layout");
            if (sw_menu) {
                element.classList.add("aside-collapsed");
            } else {
                element.classList.remove("aside-collapsed");
            }
            sw_menu = !sw_menu;
        }
        function handle_menu_movil() {
            var element = document.getElementById("main-layout");
            if (sw_menu) {
                element.classList.add("aside-toggled");
            } else {
                element.classList.remove("aside-toggled");
            }
            sw_menu = !sw_menu;
            sw_mobile_activated = true;
        }
    </script>
    <style>
        @media only screen and (min-width: 1000px) {
            .table-responsive {
                display: inline-table;
            }
        }
    </style>
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
                                    <!---->
                                    <a _ngcontent-hmx-c4="" class="nav-link d-none d-md-block d-lg-block d-xl-block" trigger-resize="" onclick="handle_menu();"><em _ngcontent-hmx-c4="" class="fas fa-bars"></em></a>
                                    <a _ngcontent-hmx-c4="" class="nav-link sidebar-toggle d-md-none" onclick="handle_menu_movil();"><em _ngcontent-hmx-c4="" class="fas fa-bars"></em></a>
                                </li>
                                <!---->
                                <li _ngcontent-hmx-c4="" class=" nav-item"><a _ngcontent-hmx-c4="" class="nav-link"><em _ngcontent-hmx-c4="" class="icon-user"></em></a></li>
                                <li _ngcontent-hmx-c4="" class="nav-item"><a _ngcontent-hmx-c4="" class="nav-link" title="Cambiar Perfil"><em _ngcontent-hmx-c4="" class="icon-people"></em></a></li>
                                <!---->
                                <li _ngcontent-hmx-c4="" class="nav-item"><a _ngcontent-hmx-c4="" class="nav-link" title="Cambiar Rupe"><em _ngcontent-hmx-c4="" class="icon-user-following"></em></a></li>
                                <li _ngcontent-hmx-c4="" class="nav-item"><a _ngcontent-hmx-c4="" class="nav-link" title="Bloquear pantalla"><em _ngcontent-hmx-c4="" class="icon-lock"></em></a></li>
                                <li _ngcontent-hmx-c4="" class="nav-item"><a _ngcontent-hmx-c4="" class="nav-link" title="Cerrar Session" onclick="cerrar_sesion();"><em _ngcontent-hmx-c4="" class="icon-logout"></em></a></li>
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
                                            <?php
                                            $id_usuario = usuario('id_sim');
                                            $rqdus1 = query("SELECT u.nombres,u.apellidos,u.ci FROM cursos_usuarios u WHERE id='$id_usuario' limit 1 ");
                                            $rqdus2 = fetch($rqdus1);
                                            ?>
                                            <div _ngcontent-hmx-c10="" class="user-block-info">
                                                <span _ngcontent-hmx-c10="" class="user-block-status">Datos Usuario</span>
                                                <span _ngcontent-hmx-c10="" class="user-block-role">Usuario: RAQ2000011</span>
                                                <span _ngcontent-hmx-c10="" class="user-block-role">
                                                    <!----> Gestión: 2021
                                                    <!----> Perfil: 2700
                                                </span>
                                                <!---->
                                                <!---->
                                            </div>
                                            <!---->
                                            <div _ngcontent-hmx-c10="" class="user-block-info">
                                                <span _ngcontent-hmx-c10="" class="user-block-name">Datos Proveedor</span>
                                                <span _ngcontent-hmx-c10="" class="user-block-role">RUPE: 220001</span>
                                                <span _ngcontent-hmx-c10="" class="user-block-role">Tipo Documento: NIT</span>
                                                <span _ngcontent-hmx-c10="" class="user-block-role">Documento: <?php echo $rqdus2['ci'] == '' ? '100000001' : $rqdus2['ci']; ?></span>
                                                <span _ngcontent-hmx-c10="" class="user-block-role" style="white-space: normal;">Razón Social: <?php echo $rqdus2['nombres'] . ' ' . $rqdus2['apellidos']; ?></span>
                                            </div>
                                        </div>
                                    </app-userblock>
                                </li>
                                <app-menu _ngcontent-hmx-c5="" _nghost-hmx-c11="">
                                    <!---->
                                    <ul _ngcontent-hmx-c11="" class="sidebar-nav">
                                        <li _ngcontent-hmx-c11=""><a _ngcontent-hmx-c11=""><span _ngcontent-hmx-c11="" class="float-right"></span><em _ngcontent-hmx-c11="" class="icon-layers"></em><span _ngcontent-hmx-c11="" class="menu"> Menú</span></a></li>
                                    </ul>
                                    <!---->
                                    <ul _ngcontent-hmx-c11="" class="sidebar-nav" id="cont-menu">
                                        <!---->
                                        <li _ngcontent-hmx-c11="">
                                            <!---->
                                            <!----><a onclick="boton_uno();" _ngcontent-hmx-c11="" class="submenu" title="Registro Único de Proveedores del Estado"><em _ngcontent-hmx-c11="" class="icon-arrow-right-circle"></em><span _ngcontent-hmx-c11="" class="menu">Registro Único de Proveedores del Estado</span></a>
                                            <!---->
                                        </li>
                                    </ul>

                                    <br><br><br><br>
                                    <ul _ngcontent-hmx-c11="" class="sidebar-nav">
                                        <!---->
                                        <?php 
                                        if($sw_acceso_envio_formularios){ 
                                            ?>
                                            <li _ngcontent-hmx-c11="">
                                                <!---->
                                                <!----><a href="https://plataforma.cursosbolivia.org/simulador/envio-formularios" target="_blank" _ngcontent-hmx-c11="" class="submenu" title="Env&iacute;o de formularios"><em _ngcontent-hmx-c11="" class="icon-arrow-right-circle"></em><span _ngcontent-hmx-c11="" class="menu">Env&iacute;o de formularios</span></a>
                                                <!---->
                                            </li>
                                            <?php 
                                        }else{
                                            ?>
                                            <li _ngcontent-hmx-c11="">
                                                <!---->
                                                <!----><a onclick="error_alert('SU CUENTA NO ESTA HABILITADA PARA ENVIO DE FORMULARIOS');" _ngcontent-hmx-c11="" class="submenu" title="Env&iacute;o de formularios"><em _ngcontent-hmx-c11="" class="icon-arrow-right-circle"></em><span _ngcontent-hmx-c11="" class="menu">Env&iacute;o de formularios</span></a>
                                                <!---->
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </app-menu>
                            </ul>
                        </nav>
                    </div>
                </app-sidebar>
                <app-offsidebar _ngcontent-hmx-c1="" class="offsidebar" _nghost-hmx-c6=""></app-offsidebar>
                <section _ngcontent-hmx-c1="" class="section-container">
                    <div _ngcontent-hmx-c1="" class="content-wrapper" id="content-page">
                        <router-outlet _ngcontent-hmx-c1=""></router-outlet>
                        <app-home _nghost-hmx-c12=""></app-home>
                    </div>
                </section>
                <sigep-mensaje _ngcontent-hmx-c1="" _nghost-hmx-c7="">
                    <div _ngcontent-hmx-c7="">
                        <div id="modal-box" _ngcontent-hmx-c7="" aria-hidden="true" aria-labelledby="mySmallModalLabel" bsmodal="" class="modal fade" data-backdrop="”static”" data-keyboard="”false”" role="dialog" tabindex="-1">
                            <div _ngcontent-hmx-c7="" class="modal-dialog modal-lg">
                                <div _ngcontent-hmx-c7="" class="modal-content">
                                    <div _ngcontent-hmx-c7="" class="modal-header">
                                        <h4 _ngcontent-hmx-c7="" id="modal-title"><em _ngcontent-hmx-c7="" class="fa fa-exclamation-triangle " style="color:#cc0000;"></em> ERROR</h4><button onclick="close_modal();" _ngcontent-hmx-c7="" aria-label="Close" class="close" type="button"><span _ngcontent-hmx-c7="" aria-hidden="true">×</span></button>
                                        <!---->
                                    </div>
                                    <div id="cont-modal-body"></div>
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

    <div id="toast-container" class="toast-bottom-right toast-container"></div>


    <script>
        let content_page = document.getElementById("content-page");
        let sw_menu_cont = true;

        function boton_uno() {
            var element = document.getElementById("cont-menu");
            if (sw_menu_cont) {
                element.innerHTML = '<!----><li _ngcontent-add-c11=""><!----><a onclick="boton_uno();" style="white-space: pre-wrap;" _ngcontent-add-c11="" title="Registro Único de Proveedores del Estado"><span _ngcontent-add-c11="" class="float-right"></span><em _ngcontent-add-c11="" class="icon-arrow-left-circle"></em><span _ngcontent-add-c11="" class="menu">Registro Único de Proveedores del Estado</span></a><!----><!----><a  onclick="page_registro_productos();"_ngcontent-add-c11="" style="padding-left: 30px;" styclass="submenu"  title="Registro de productos"><!----><em _ngcontent-add-c11="" class="fa fa-circle"></em><span _ngcontent-add-c11="">Registro de productos</span></a></li><li _ngcontent-add-c11=""><!----><!----><a onclick="boton_dos();" _ngcontent-add-c11="" style="padding-left: 30px;" class="submenu" title="Procesos de Contratación"><em _ngcontent-add-c11="" class="icon-arrow-right-circle"></em><span _ngcontent-add-c11="" class="menu">Procesos de Contratación</span></a><!----></li>';
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
            <?php
            if($sw_acceso_subastas){
            ?>
            fetch('contenido/pages/ajax/page_subastas.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    //alert(text);
                    content_page.innerHTML = text;
                    if(sw_mobile_activated){
                        handle_menu_movil();
                    }
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
            <?php
            }else{
                ?>
                error_alert('SU CUENTA NO ESTA HABILITADA PARA SUBASTAS');
                <?php
            }
            ?>
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
                    if(sw_mobile_activated){
                        handle_menu_movil();
                    }
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>

    <script>
        function page_join_subasta(mod) {
            fetch('contenido/pages/ajax/page_join_subasta.php?mod='+mod)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                    contador_subasta(mod);
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }

        function contador_subasta(mod) {
            let cnt = 7;
            var timerID = setInterval(() => {
                //alert('yep'+cnt);
                document.getElementById("counter").innerHTML = cnt;
                cnt--;
                if (cnt < 1) {
                    clearInterval(timerID);
                    if(mod=='mod2'){
                        page_subasta_items();
                    }else{
                        //start_subasta();
                        page_subasta_total();
                    }
                }
            }, 1000);
        }
        
        function pjs_registrar_precio_subasta_total(mod) {
            open_modal('Registro de precios Unitarios');
            fetch('contenido/pages/ajax/pjs_registrar_precio_subasta_total.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function pjs_calcular_precio(valor) {
            let valor_ofertado = (valor * 2000).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            document.getElementById("id-ofert-item").innerHTML = valor_ofertado;
            document.getElementById("id-total-ofertado").innerHTML = valor_ofertado;
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
                        cnt_update_ID++;
                        if (cnt_update_ID > 150) {
                            clearInterval(timer_update_ID);
                            alert('LA SUBASTA TERMINO');
                            document.getElementById("btn_envprop").style.display = 'none';
                        }
                    }, 4000);
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }

        function page_subasta_items() {
            fetch('contenido/pages/ajax/page_join_subasta_items.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                    setTimeout(() => { pjsi_actualizar_historial(); },3000);
                    setTimeout(() => { document.getElementById("box-msj-alert").innerHTML = '<div style="background: #f05050;padding: 15px 25px;font-size: 12pt;color: #fff;border: 1px solid #cecece;border-radius: 5px;"><i class="fa fa-exclamation-circle"></i> &nbsp; Se encuentra en el tiempo extra, la subasta puede terminar en cualquier momento</div>'; },1000*60*5);
                    setTimeout(() => { document.getElementById("box-msj-alert").innerHTML = '<div style="background: #dfe2e2;padding: 15px 25px;font-size: 12pt;color: #000;border: 1px solid #cecece;border-radius: 5px;font-weight: bold;"><i class="fa fa-exclamation-circle"></i> &nbsp; La subasta ha finalizado, ya no puede registrar precios</div>';document.getElementById("btn-aux-1").style.display = 'none';document.getElementById("btn-aux-2").style.display = 'none';document.getElementById("btn-aux-3").style.display = 'none';document.getElementById("btn-aux-4").style.display = 'none'; pjsi_resultados_subasta(); },1000*60*6);
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function page_subasta_total() {
            fetch('contenido/pages/ajax/page_join_subasta_total.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                    setTimeout(() => { pjs_actualizar_historial(); },3000);
                    setTimeout(() => { document.getElementById("box-msj-alert").innerHTML = '<div style="background: #f05050;padding: 15px 25px;font-size: 12pt;color: #fff;border: 1px solid #cecece;border-radius: 5px;"><i class="fa fa-exclamation-circle"></i> &nbsp; Se encuentra en el tiempo extra, la subasta puede terminar en cualquier momento</div>'; },1000*60*5);
                    setTimeout(() => { document.getElementById("box-msj-alert").innerHTML = '<div style="background: #dfe2e2;padding: 15px 25px;font-size: 12pt;color: #000;border: 1px solid #cecece;border-radius: 5px;font-weight: bold;"><i class="fa fa-exclamation-circle"></i> &nbsp; La subasta ha finalizado, ya no puede registrar precios</div>';document.getElementById("btn-registrar-precio").style.display = 'none'; pjs_resultados_subasta(); },1000*60*6);
                })
                .catch(function(error) {
                    console.log('Request failed', error)
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
                    console.log('Request failed', error)
                });
        }
    </script>

    <script>
        function page_registro_productos() {
            fetch('contenido/pages/ajax/page_registro_productos.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                    if(sw_mobile_activated){
                        handle_menu_movil();
                    }
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>

    <script>
        function page_nuevo_bien(id_cat) {
            fetch('contenido/pages/ajax/page_nuevo_bien.php?id_cat='+id_cat)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>

    <script>
        function enviar_propuesta() {
            var cont_panel_estado_subasta = document.getElementById("panel-estado-subasta");
            const monto = parseFloat(document.getElementById("monto-propuesta").value);
            const ultimo_valor_ofertado = parseFloat(document.getElementById("ultimo_valor_ofertado").value);
            if(!(!isNaN(monto) && isFinite(monto))){
                error_alert("ERROR! - El valor ingresado no es un numero.");
            }else if(monto<0){
                error_alert("ERROR! - El valor ingresado no debe ser negativo.");
            }else if(monto==0){
                error_alert("ERROR! - El valor ingresado no debe ser cero.");
            }else if(monto==250){
                error_alert("ERROR! - El valor ingresado no debe ser igual al precio referencial.");
            }else if(monto>250){
                error_alert("ERROR! - El valor ingresado no debe ser mayor al precio referencial.");
            }else if(monto>=ultimo_valor_ofertado){
                error_alert("ERROR! - El precio total ofertado "+(monto*2000)+" debe ser menor al registrado en su anterior lance que es "+(ultimo_valor_ofertado*2000));
            }else{
                fetch('contenido/pages/ajax/enviar_propuesta.php?monto=' + monto)
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(text) {
                        cont_panel_estado_subasta.innerHTML = text;
                        close_modal();
                        //document.getElementById("monto-propuesta").value = 0;
                        pjs_actualizar_historial();
                    })
                    .catch(function(error) {
                        console.log('Request failed', error)
                    });
            }
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
                    pjs_actualizar_historial();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function pjs_actualizar_tabla(){/* not ended */
            document.getElementById("id-tabla_items").innerHTML = "<br><br>Cargando...<br><br>";
            fetch('contenido/pages/ajax/pjsi_registrar_precio.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("id-tabla_items").innerHTML = text;
                    pjsi_actualizar_historial();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function pjs_actualizar_historial() {
            var cont_panel_historial_subasta = document.getElementById("panel-historial-subsata");
            fetch('contenido/pages/ajax/pjs_actualizar_historial.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_panel_historial_subasta.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function pjs_resultados_subasta() {
            var cont_panel_historial_subasta = document.getElementById("panel-resultados-subsata");
            fetch('contenido/pages/ajax/pjs_resultados_subasta.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_panel_historial_subasta.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
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
                    console.log('Request failed', error)
                });
        }
    </script>

    <script>
        function pnb_selecionar_item() {
            open_modal('Seleccionar ítem');
            document.getElementById("modal-title").style.textAlign = 'center';

            var cont_modal_body = document.getElementById("cont-modal-body");
            cont_modal_body.innerHTML = 'Cargando...';

            fetch('contenido/pages/ajax/pnb_selecionar_item.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_modal_body.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });

        }

        function open_modal(title_modal) {
            var modal_box = document.getElementById("modal-box");
            var back_modal = document.getElementById("back-modal");
            var modal_title = document.getElementById("modal-title");

            modal_title.innerHTML = title_modal;
            modal_box.style.display = 'block';
            modal_box.classList.add("in");
            modal_box.classList.add("show");
            back_modal.style.display = 'block';
        }

        function close_modal() {
            var modal_box = document.getElementById("modal-box");
            var back_modal = document.getElementById("back-modal");

            modal_box.style.display = 'none';
            modal_box.classList.remove("in");
            modal_box.classList.remove("show");
            back_modal.style.display = 'none';

            document.getElementById("cont-modal-body").innerHTML = '';
        }
    </script>


    <script>
        function pnb_simular_busqueda() {
            var cont_modal_body = document.getElementById("cont-modal-body");
            cont_modal_body.innerHTML = 'Cargando...';

            fetch('contenido/pages/ajax/pnb_simular_busqueda.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_modal_body.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });

        }
    </script>

    <script>
        function pnb_press_select_item() {
            document.getElementById("boton-aceptar-select-item").style.display = 'block';
            document.getElementById("boton-nuevo-producto").removeAttribute('disabled');
        }
    </script>

    <script>
        function page_nuevo_producto(id_prod) {
            fetch('contenido/pages/ajax/page_nuevo_producto.php?id_prod='+id_prod)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                    if(!sw_mobile_activated){
                        handle_menu();
                    }
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function page_nuevo_producto_p2() {
            let formData = null;
            const form = document.getElementById("FORM-new-prod");
            if(form){
                formData = new FormData(form);    
            }
            fetch('contenido/pages/ajax/page_nuevo_producto_p2.php', {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function page_nuevo_producto_p3() {
            fetch('contenido/pages/ajax/page_nuevo_producto_p3.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }

        function page_nuevo_producto_p4() {
            fetch('contenido/pages/ajax/page_nuevo_producto_p4.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }

        function page_nuevo_producto_p5() {
            fetch('contenido/pages/ajax/page_nuevo_producto_p5.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }

        function pnp_activar_prod() {
            open_modal('¿Está seguro que desea activar el producto?');
            document.getElementById("modal-title").style.textAlign = 'center';

            var cont_modal_body = document.getElementById("cont-modal-body");
            cont_modal_body.innerHTML = '<div _ngcontent-ruw-c16="" class="modal-footer"><button onclick="close_modal();" _ngcontent-ruw-c16="" class="btn btn-secondary" type="button">Cancelar</button><button onclick="pnp_action_activar_prod();" _ngcontent-ruw-c16="" type="button" class="btn btn-success">Aceptar</button></div>';
        }
        function pnp_action_activar_prod() {
            close_modal();
            document.getElementById("toast-container").innerHTML = '<div onclick="page_mis_documentos();document.getElementById("toast-container").innerHTML=\'\';" class="alert alert-success"><strong><span class="fa fa-check"></span> PRODUCTO ACTIVADO CORRECTAMENTE</strong></div>';
            fetch('contenido/pages/ajax/pnp_action_activar_prod.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    console.log('actualizado');
                    setTimeout(() => {
                        document.getElementById("toast-container").innerHTML = '';
                        page_nuevo_bien(text);
                    }, 3000);
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>

    <script>
        function pnd_seleccionar_proceso() {
            open_modal('Seleccionar Proceso de Contratación');
            var cont_modal_body = document.getElementById("cont-modal-body");
            cont_modal_body.innerHTML = 'Cargando...';

            fetch('contenido/pages/ajax/pnd_seleccionar_proceso.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_modal_body.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });

        }
    </script>
    <script>
        function pnd_simular_busqueda() {
            var cont_modal_body = document.getElementById("cont-modal-body");
            cont_modal_body.innerHTML = 'Cargando...';

            fetch('contenido/pages/ajax/pnd_simular_busqueda.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_modal_body.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>

    <script>
        function pnd_press_select_proceso(modalidad) {
            let url_content = null;
            if (modalidad == 'CM') {
                url_content = 'contenido/pages/ajax/pnd_press_select_proceso__CM.php';
            } else {
                url_content = 'contenido/pages/ajax/pnd_press_select_proceso.php';
            }
            fetch(url_content)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                    close_modal();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>

<script>
        function DOCUMENTOS_COMPRAMENOR_ITEMS_press_select_proceso() {
            let url_content = 'contenido/pages/ajax/DOCUMENTOS_COMPRAMENOR_ITEMS_press_select_proceso.php';
            fetch(url_content)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                    close_modal();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function DOCUMENTOS_COMPRAMENOR_ITEMS_press_siguiente_a_registro_precios() {
            let url_content = 'contenido/pages/ajax/DOCUMENTOS_COMPRAMENOR_ITEMS_press_siguiente_a_registro_precios.php';
            fetch(url_content)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                    sw_menu = true;
                    handle_menu();
                    DOCUMENTOS_COMPRAMENOR_ITEMS_display_regsitro_precios__display();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function DOCUMENTOS_COMPRAMENOR_ITEMS_press_nuevo_item__modal() {
            open_modal('Selección de Ítems a participar');
            var cont_modal_body = document.getElementById("cont-modal-body");
            cont_modal_body.innerHTML = 'Cargando...';

            fetch('contenido/pages/ajax/DOCUMENTOS_COMPRAMENOR_ITEMS_press_nuevo_item__modal.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_modal_body.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function DOCUMENTOS_COMPRAMENOR_ITEMS_agregado_de_items__display() {
            const form = document.getElementById("FORM-modal");
            const formData = new FormData(form);
            let url_content = 'contenido/pages/ajax/DOCUMENTOS_COMPRAMENOR_ITEMS_agregado_de_items__display.php';
            fetch(url_content, {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    DOCUMENTOS_COMPRAMENOR_ITEMS_display_regsitro_precios__display();
                    close_modal();
                    console.log(text);
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function DOCUMENTOS_COMPRAMENOR_ITEMS_display_regsitro_precios__display() {
            let url_content = 'contenido/pages/ajax/DOCUMENTOS_COMPRAMENOR_ITEMS_display_regsitro_precios__display.php';
            fetch(url_content)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("display-content").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function DOCUMENTOS_COMPRAMENOR_ITEMS_editar_propuesta_economica_modal() {
            open_modal('Registro de Precios');
            var cont_modal_body = document.getElementById("cont-modal-body");
            cont_modal_body.innerHTML = 'Cargando...';

            fetch('contenido/pages/ajax/DOCUMENTOS_COMPRAMENOR_ITEMS_editar_propuesta_economica_modal.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_modal_body.innerHTML = text;
                    dropdown_btn_opciones()
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function DOCUMENTOS_COMPRAMENOR_ITEMS_guardado_de_precios__nocontent() {
            const form = document.getElementById("FORM-modal");
            const formData = new FormData(form);
            let url_content = 'contenido/pages/ajax/DOCUMENTOS_COMPRAMENOR_ITEMS_guardado_de_precios__nocontent.php';
            fetch(url_content, {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    DOCUMENTOS_COMPRAMENOR_ITEMS_display_regsitro_precios__display();
                    close_modal();
                    console.log(text);
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function DOCUMENTOS_COMPRAMENOR_ITEMS_verificar_doc() {
            open_modal('¿Está seguro que desea verificar el documento?');
            document.getElementById("cont-modal-body").innerHTML = '<div _ngcontent-yfk-c34="" class="modal-footer"><button onclick="close_modal();" _ngcontent-yfk-c34="" class="btn btn-secondary" type="button">Cancelar</button><button _ngcontent-yfk-c34="" type="button" class="btn btn-primary" onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_verificar_doc_send();">Aceptar</button></div>';
        }
        function DOCUMENTOS_COMPRAMENOR_ITEMS_verificar_doc_send() {
            close_modal();
            document.getElementById("toast-container").innerHTML = '<div onclick="page_mis_documentos();document.getElementById("toast-container").innerHTML=\'\';" class="alert alert-success"><strong><span class="fa fa-check"></span> REGISTRO VERIFICADO CORRECTAMENTE</strong></div>';
            setTimeout(() => {
                document.getElementById("toast-container").innerHTML = '';
                page_mis_documentos();
            }, 3000);
            fetch('contenido/pages/ajax/DOCUMENTOS_COMPRAMENOR_ITEMS_verificar_doc_send.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    console.log('actualizado');
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function DOCUMENTOS_COMPRAMENOR_ITEMS_elimnar_doc() {
            open_modal('¿Está seguro que desea eliminar el documento?');
            document.getElementById("cont-modal-body").innerHTML = '<div _ngcontent-yfk-c34="" class="modal-footer"><button onclick="close_modal();" _ngcontent-yfk-c34="" class="btn btn-secondary" type="button">Cancelar</button><button onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_elimnar_doc_send();" _ngcontent-yfk-c34="" type="button" class="btn btn-danger">Aceptar</button></div>';
        }
        function DOCUMENTOS_COMPRAMENOR_ITEMS_elimnar_doc_send() {
            close_modal();
            document.getElementById("toast-container").innerHTML = '<div onclick="page_mis_documentos();document.getElementById("toast-container").innerHTML=\'\';" class="alert alert-success"><strong><span class="fa fa-check"></span> REGISTRO ELIMINADO CORRECTAMENTE</strong></div>';
            setTimeout(() => {
                document.getElementById("toast-container").innerHTML = '';
                page_mis_documentos();
            }, 3000);
            fetch('contenido/pages/ajax/DOCUMENTOS_COMPRAMENOR_ITEMS_elimnar_doc_send.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    console.log('actualizado');
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function DOCUMENTOS_COMPRAMENOR_ITEMS_enviar_documento() {
            let url_cont = 'contenido/pages/ajax/DOCUMENTOS_COMPRAMENOR_ITEMS_enviar_documento.php';
            fetch(url_cont)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                    if(!sw_mobile_activated){
                        handle_menu();
                    }
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function DOCUMENTOS_COMPRAMENOR_ITEMS_enviar_documento_send() {
            open_modal('POLÍTICAS Y CONDICIONES DE USO');
            var cont_modal = '';
            cont_modal += '<style>#modal-box{overflow: auto !important;}body{overflow: hidden !important;;}</style><div _ngcontent-fmr-c25="" class="modal-body"><p _ngcontent-fmr-c25="" class="text-justify"><!----><!----><h4 _ngcontent-fmr-c25="" class="ng-star-inserted">DECLARACIÓN</h4> En mi calidad de Usuario del Registro Único de Proveedores del Estado - RUPE, acepto que es de mi entera responsabilidad la preservación y confidencialidad de las credenciales de acceso a mi cuenta y cumplir con las Políticas y Condiciones de uso del RUPE. <br _ngcontent-fmr-c25="" class="ng-star-inserted"> Y en mi calidad de proponente, para la presentación de mi propuesta, declaro y acepto conocer y cumplir la normativa y condiciones del proceso de contratación y asumo la responsabilidad del contenido, veracidad, oportunidad, efectos y los resultados que puedan generar la información registrada y los documentos digitales enviados y/o publicados a través del sistema. <!----></p><div _ngcontent-fmr-c25="" class="border-top"><br _ngcontent-fmr-c25=""><!----><div _ngcontent-fmr-c25="" class="ng-star-inserted"><span onclick="document.getElementById(\'id-pnd_g_envaction\').removeAttribute(\'disabled\');" _ngcontent-fmr-c25="" class="checkbox c-checkbox " style="display:inline"><label _ngcontent-fmr-c25=""><input _ngcontent-fmr-c25="" ng-change="seleccionar(declaracion)" type="checkbox"><span _ngcontent-fmr-c25="" class="fa fa-check"></span><b _ngcontent-fmr-c25="" class="text-primary"> ACEPTO LAS POLÍTICAS Y CONDICIONES DE USO</b></label></span></div></div></div>';
            cont_modal += '<div _ngcontent-fmr-c25="" class="modal-footer"><button onclick="DOCUMENTOS_COMPRAMENOR_ITEMS_enviar_documento_send_2();" id="id-pnd_g_envaction" _ngcontent-fmr-c25="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button><button onclick="close_modal();" _ngcontent-fmr-c25="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>';
            document.getElementById("cont-modal-body").innerHTML = cont_modal;
        }
        function DOCUMENTOS_COMPRAMENOR_ITEMS_enviar_documento_send_2() {
            close_modal();
            document.getElementById("toast-container").innerHTML = '<div onclick="page_mis_documentos();document.getElementById("toast-container").innerHTML=\'\';" class="alert alert-success"><strong><span class="fa fa-check"></span> REGISTRO ACTUALIZADO CORRECTAMENTE</strong></div>';
            setTimeout(() => {
                document.getElementById("toast-container").innerHTML = '';
                page_mis_documentos();
            }, 3000);
            fetch('contenido/pages/ajax/DOCUMENTOS_COMPRAMENOR_ITEMS_enviar_documento_send_2.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    console.log('actualizado');
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    
    


    <script>
        function page_reg_nuevo_documento_p1(modalidad) {
            let url_content = null;
            if (modalidad == 'CM') {
                url_content = 'contenido/pages/ajax/page_reg_nuevo_documento__CM_p1.php';
            } else {
                url_content = 'contenido/pages/ajax/page_reg_nuevo_documento_p1.php';
            }
            fetch(url_content)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                    if(!sw_mobile_activated){
                        handle_menu();
                    }
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function page_reg_nuevo_documento_p2() {
            fetch('contenido/pages/ajax/page_reg_nuevo_documento_p2.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function page_reg_nuevo_documento_p3() {
            fetch('contenido/pages/ajax/page_reg_nuevo_documento_p3.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function page_reg_nuevo_documento_p4() {
            fetch('contenido/pages/ajax/page_reg_nuevo_documento_p4.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>

    <script>
        function pnd_verificar_doc() {
            open_modal('¿Está seguro que desea verificar el documento?');
            document.getElementById("modal-title").style.textAlign = 'center';

            var cont_modal_body = document.getElementById("cont-modal-body");
            cont_modal_body.innerHTML = '<div _ngcontent-yfk-c34="" class="modal-footer"><button onclick="close_modal();" _ngcontent-yfk-c34="" class="btn btn-secondary" type="button">Cancelar</button><button onclick="pnd_action_verificar_doc();" _ngcontent-yfk-c34="" type="button" class="btn btn-primary">Aceptar</button></div>';
        }

        function pnd_elimnar_doc() {
            open_modal('¿Está seguro que desea eliminar el documento?');
            document.getElementById("modal-title").style.textAlign = 'center';

            var cont_modal_body = document.getElementById("cont-modal-body");
            cont_modal_body.innerHTML = '<div _ngcontent-yfk-c34="" class="modal-footer"><button onclick="close_modal();" _ngcontent-yfk-c34="" class="btn btn-secondary" type="button">Cancelar</button><button onclick="pnd_action_eliminar_doc();" _ngcontent-yfk-c34="" type="button" class="btn btn-danger">Aceptar</button></div>';
        }

        function pnd_action_verificar_doc() {
            close_modal();
            document.getElementById("toast-container").innerHTML = '<div onclick="page_mis_documentos();document.getElementById("toast-container").innerHTML=\'\';" class="alert alert-success"><strong><span class="fa fa-check"></span> REGISTRO VERIFICADO CORRECTAMENTE</strong></div>';
            setTimeout(() => {
                document.getElementById("toast-container").innerHTML = '';
                page_mis_documentos();
            }, 3000);
            fetch('contenido/pages/ajax/pnd_action_verificar_doc.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    console.log('actualizado');
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }

        function pnd_action_eliminar_doc() {
            close_modal();
            document.getElementById("toast-container").innerHTML = '<div onclick="page_mis_documentos();document.getElementById("toast-container").innerHTML=\'\';" class="alert alert-success"><strong><span class="fa fa-check"></span> REGISTRO ELIMINADO CORRECTAMENTE</strong></div>';
            setTimeout(() => {
                document.getElementById("toast-container").innerHTML = '';
                page_mis_documentos();
            }, 3000);
            fetch('contenido/pages/ajax/pnd_action_eliminar_doc.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    console.log('actualizado');
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>

    <script>
        function pnp_nueva_imagen() {
            open_modal('Imágenes');
            var cont_modal_body = document.getElementById("cont-modal-body");
            cont_modal_body.innerHTML = 'Cargando...';

            fetch('contenido/pages/ajax/pnp_nueva_imagen.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_modal_body.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>

    <script>
        function pnp_nuevo_atributo() {
            open_modal('Imágenes');
            var cont_modal_body = document.getElementById("cont-modal-body");
            cont_modal_body.innerHTML = 'Cargando...';

            fetch('contenido/pages/ajax/pnp_nuevo_atributo.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_modal_body.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>


    <script>
        function page_envio_formularios() {
            fetch('contenido/pages/ajax/page_envio_formularios.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        let sw_dropdown_prnd_CM_1 = false;

        function dropdown_prnd_CM_1() {
            var element = document.getElementById("id-sw_dropdown_prnd_CM_1");
            if (sw_dropdown_prnd_CM_1) {
                element.style.display = 'none';
            } else {
                element.style.display = 'block';
            }
            sw_dropdown_prnd_CM_1 = !sw_dropdown_prnd_CM_1;
        }
        let sw_dropdown_prnd_CM_2 = false;

        function dropdown_prnd_CM_2() {
            var element = document.getElementById("id-sw_dropdown_prnd_CM_2");
            if (sw_dropdown_prnd_CM_2) {
                element.style.display = 'none';
            } else {
                element.style.display = 'block';
            }
            sw_dropdown_prnd_CM_2 = !sw_dropdown_prnd_CM_2;
        }

        function prnd_CM_registrar_precios() {
            open_modal('Registro de Precios');
            var cont_modal_body = document.getElementById("cont-modal-body");
            cont_modal_body.innerHTML = 'Cargando...';

            fetch('contenido/pages/ajax/prnd_CM_registrar_precios.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    cont_modal_body.innerHTML = text;
                    dropdown_prnd_CM_2();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }

        function prnd_CM_calcular_monto(valor) {
            open_modal('Registro de Precios');
            let valor_ofertado = (valor * 7000).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            document.getElementById("id-prnd_CM_data-monto_total_1").innerHTML = valor_ofertado;
            document.getElementById("id-prnd_CM_data-monto_total_2").innerHTML = valor_ofertado;
        }

        function prnd_CM_registrar_precios_p2() {
            let precio_unitario_ofertado = parseFloat(document.getElementById("id-prnd_CM_monto-oferta").value);
            if(!(!isNaN(precio_unitario_ofertado) && isFinite(precio_unitario_ofertado))){
                error_alert("ERROR! - El valor ingresado no es un numero.");
            }else if(precio_unitario_ofertado<0){
                error_alert("ERROR! - El valor ingresado no debe ser negativo.");
            }else if(precio_unitario_ofertado==0){
                error_alert("ERROR! - El valor ingresado no debe ser cero.");
            }else if(precio_unitario_ofertado==6.80){
                error_alert("ERROR! - El valor ingresado no debe ser igual al precio referencial.");
            }else if(precio_unitario_ofertado>6.80){
                error_alert("ERROR! - El valor ingresado no debe ser mayor al precio referencial.");
            }else{
                let valor_ofertado = (precio_unitario_ofertado * 7000).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                fetch('contenido/pages/ajax/prnd_CM_registrar_precios_p2.php?precio_unitario_ofertado=' + precio_unitario_ofertado + '&valor_ofertado=' + valor_ofertado)
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(text) {
                        close_modal();
                        document.getElementById("id-cont_panel_precios").innerHTML = text;
                    })
                    .catch(function(error) {
                        console.log('Request failed', error)
                    });
            }
        }

        function prnd_CM_verificar_doc() {
            open_modal('¿Está seguro que desea verificar el documento?');
            document.getElementById("cont-modal-body").innerHTML = '<div _ngcontent-yfk-c34="" class="modal-footer"><button onclick="close_modal();" _ngcontent-yfk-c34="" class="btn btn-secondary" type="button">Cancelar</button><button _ngcontent-yfk-c34="" type="button" class="btn btn-primary" onclick="prnd_CM_action_verificar_doc();">Aceptar</button></div>';
        }

        function prnd_CM_elimnar_doc() {
            open_modal('¿Está seguro que desea eliminar el documento?');
            document.getElementById("cont-modal-body").innerHTML = '<div _ngcontent-yfk-c34="" class="modal-footer"><button onclick="close_modal();" _ngcontent-yfk-c34="" class="btn btn-secondary" type="button">Cancelar</button><button onclick="prnd_action_eliminar_doc(\'CM\');" _ngcontent-yfk-c34="" type="button" class="btn btn-danger">Aceptar</button></div>';
        }

        function prnd_CM_action_verificar_doc() {
            close_modal();
            document.getElementById("toast-container").innerHTML = '<div onclick="page_mis_documentos();document.getElementById("toast-container").innerHTML=\'\';" class="alert alert-success"><strong><span class="fa fa-check"></span> REGISTRO VERIFICADO CORRECTAMENTE</strong></div>';
            setTimeout(() => {
                document.getElementById("toast-container").innerHTML = '';
                page_mis_documentos();
            }, 3000);
            fetch('contenido/pages/ajax/prnd_CM_action_verificar_doc.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    console.log('actualizado');
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }

        function prnd_action_eliminar_doc(modalidad) {
            close_modal();
            document.getElementById("toast-container").innerHTML = '<div onclick="page_mis_documentos();document.getElementById("toast-container").innerHTML=\'\';" class="alert alert-success"><strong><span class="fa fa-check"></span> REGISTRO ELIMINADO CORRECTAMENTE</strong></div>';
            setTimeout(() => {
                document.getElementById("toast-container").innerHTML = '';
                page_mis_documentos();
            }, 3000);
            fetch('contenido/pages/ajax/prnd_action_eliminar_doc.php?modalidad='+modalidad)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    console.log('actualizado');
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        let sw_pmd_dropdown_1 = false;

        function pmd_dropdown_1(id) {
            var element = document.getElementById("id-sw_pmd_dropdown_1-" + id);
            if (sw_pmd_dropdown_1) {
                element.style.display = 'none';
            } else {
                element.style.display = 'block';
            }
            sw_pmd_dropdown_1 = !sw_pmd_dropdown_1;
        }
    </script>
    <script>
        function page_enviar_documento(modalidad) {
            let url_cont = '';
            if (modalidad == 'CM') {
                url_cont = 'contenido/pages/ajax/page_enviar_documento__CM.php';
            } else {
                url_cont = 'contenido/pages/ajax/page_enviar_documento.php';
            }
            fetch(url_cont)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    content_page.innerHTML = text;
                    if(!sw_mobile_activated){
                        handle_menu();
                    }
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }

        function action_page_enviar_documento() {
            open_modal('POLÍTICAS Y CONDICIONES DE USO');
            var cont_modal = '';
            cont_modal += '<style>#modal-box{overflow: auto !important;}body{overflow: hidden !important;;}</style><div _ngcontent-fmr-c25="" class="modal-body"><p _ngcontent-fmr-c25="" class="text-justify"><!----><!----><h4 _ngcontent-fmr-c25="" class="ng-star-inserted">DECLARACIÓN</h4> En mi calidad de Usuario del Registro Único de Proveedores del Estado - RUPE, acepto que es de mi entera responsabilidad la preservación y confidencialidad de las credenciales de acceso a mi cuenta y cumplir con las Políticas y Condiciones de uso del RUPE. <br _ngcontent-fmr-c25="" class="ng-star-inserted"> Y en mi calidad de proponente, para la presentación de mi propuesta, declaro y acepto conocer y cumplir la normativa y condiciones del proceso de contratación y asumo la responsabilidad del contenido, veracidad, oportunidad, efectos y los resultados que puedan generar la información registrada y los documentos digitales enviados y/o publicados a través del sistema. <!----></p><div _ngcontent-fmr-c25="" class="border-top"><br _ngcontent-fmr-c25=""><!----><div _ngcontent-fmr-c25="" class="ng-star-inserted"><span onclick="document.getElementById(\'id-pnd_g_envaction\').removeAttribute(\'disabled\');" _ngcontent-fmr-c25="" class="checkbox c-checkbox " style="display:inline"><label _ngcontent-fmr-c25=""><input _ngcontent-fmr-c25="" ng-change="seleccionar(declaracion)" type="checkbox"><span _ngcontent-fmr-c25="" class="fa fa-check"></span><b _ngcontent-fmr-c25="" class="text-primary"> ACEPTO LAS POLÍTICAS Y CONDICIONES DE USO</b></label></span></div></div></div>';
            cont_modal += '<div _ngcontent-fmr-c25="" class="modal-footer"><button onclick="prnd_action_enviar_doc();" id="id-pnd_g_envaction" _ngcontent-fmr-c25="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button><button onclick="close_modal();" _ngcontent-fmr-c25="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>';
            document.getElementById("cont-modal-body").innerHTML = cont_modal;
        }

        function action_page_enviar_documento__CM() {
            open_modal('Condiciones Generales de Uso del Sistema para la Presentación de Ofertas Económicas como Potenciales Proveedores');
            var cont_modal = '';
            cont_modal += '<style>#modal-box{overflow: auto !important;}body{overflow: hidden !important;;}</style><div _ngcontent-dvn-c25="" class="modal-body"><p _ngcontent-dvn-c25="" class="text-justify"><!----><b _ngcontent-dvn-c25="">1.	Presentación de Ofertas</b><br _ngcontent-dvn-c25=""> Con la presentación de mi propuesta económica declaro mediante el presente documento que he revisado en detalle la oferta del proveedor preseleccionado y que conozco las especificaciones técnicas, cantidades, precio y demás condiciones de los bienes, obras o servicios generales, que fueron ofertados por el proveedor preseleccionado y publicados por la entidad contratante. <br _ngcontent-dvn-c25=""> En este marco, mediante la aceptación de este documento manifiesto mi completo consentimiento para adherirme a la oferta del proveedor preseleccionado publicada en el SICOES y declaro que en caso de ser adjudicado y contratado, se dará fiel cumplimiento a dicha oferta, so pena de la aplicación de las siguientes sanciones: <br _ngcontent-dvn-c25="">a)	En caso de ser adjudicado y desistir de la formalización de la contratación, no podré participar en procesos de contratación hasta un (1) año después de la fecha del desistimiento, de conformidad con lo previsto en el inciso i) del Artículo 43 del Decreto Supremo N° 0181, de 28 de junio de 2009, Normas Básicas del Sistema de Administración de Bienes y Servicios. <br _ngcontent-dvn-c25="">b)	En caso de formalizar la contratación mediante contrato y resolver el mismo, por causales atribuibles al proveedor, no podré participar en procesos de contratación durante tres (3) años después de la fecha de la resolución. En caso de formalizar la contratación mediante orden de compra o servicio y declarar el incumplimiento de las mismas, por causales atribuibles al proveedor, no podré participar durante un (1) años después de la fecha de incumplimiento. Ambos casos se efectuarán de conformidad con lo previsto en el inciso j) del Artículo 43 del Decreto Supremo N° 0181, de 28 de junio de 2009, Normas Básicas del Sistema de Administración de Bienes y Servicios. <br _ngcontent-dvn-c25=""><br _ngcontent-dvn-c25=""><b _ngcontent-dvn-c25="">2.	Obligaciones de los Potenciales Proveedores</b><br _ngcontent-dvn-c25=""> Asimismo declaro y acepto las siguientes obligaciones: <br _ngcontent-dvn-c25="">a)	Cumplir estrictamente la normativa de la Ley N° 1178, de Administración y Control Gubernamentales, lo establecido en las NB-SABS, Decreto Supremo N° 0181 y en el Decreto Supremo N° 4308; <br _ngcontent-dvn-c25="">b)	No tener conflicto de intereses para el presente proceso de contratación; <br _ngcontent-dvn-c25="">c)	No estar alcanzado por las causales de impedimento, establecidas en el Artículo 43 de las NB-SABS, para participar en el proceso de contratación. <br _ngcontent-dvn-c25="">d)	Respetar el desempeño de los servidores públicos asignados, por la entidad pública al proceso de contratación y no incurrir en relacionamiento que no sea a través de medio escrito. <br _ngcontent-dvn-c25="">e)	Denunciar, posibles actos de corrupción en el presente proceso de contratación, en el marco de lo dispuesto por la Ley N° 974 de Unidades de Transparencia. <br _ngcontent-dvn-c25="">f)	Conocer a detalle la oferta del proveedor preseleccionado, aceptando sin reservas todas las estipulaciones en dichos documentos; <br _ngcontent-dvn-c25="">g)	Adherirme a las especificaciones técnicas, cantidades y demás condiciones de los bienes, obras o servicios generales, que fueron ofertados por el proveedor preseleccionado; <br _ngcontent-dvn-c25="">h)	En caso de ser adjudicado, formalizar la contratación de conformidad con las condiciones de la oferta técnica del proveedor preseleccionado y conforme al precio que he ofertado; <br _ngcontent-dvn-c25=""><br _ngcontent-dvn-c25=""><b _ngcontent-dvn-c25=""> 3.	Reconocimiento y Validez Jurídica de las actuaciones realizadas</b><br _ngcontent-dvn-c25=""> La validez, exigibilidad y fuerza probatoria de la adhesión que realizan los potenciales proveedores respecto a la oferta del proveedor preseleccionado, así como de las ofertas de menores precios registradas y remitidas mediante el RUPE como sistema integrante del SICOES, se enmarcan en lo dispuesto por el Decreto Supremo N° 3548, de 2 de mayo de 2018, por lo que todas las operaciones realizadas mediante el presente módulo, son de estricto cumplimiento de los potenciales proveedores que participen de un proceso de contratación. <!----></p><div _ngcontent-dvn-c25="" class="border-top"><br _ngcontent-dvn-c25=""><!----><div _ngcontent-dvn-c25=""><span onclick="document.getElementById(\'id-pmd_ed_envaction\').removeAttribute(\'disabled\');" _ngcontent-dvn-c25="" class="checkbox c-checkbox " style="display:inline"><label _ngcontent-dvn-c25=""><input _ngcontent-dvn-c25="" ng-change="seleccionar(declaracion)" type="checkbox"><span _ngcontent-dvn-c25="" class="fa fa-check"></span><b _ngcontent-dvn-c25="" class="text-primary"> ACEPTO LAS CONDICIONES GENERALES DE USO</b></label></span></div></div></div>';
            cont_modal += '<div _ngcontent-dvn-c25="" class="modal-footer"><button onclick="ped_CM_action_enviar_doc();" id="id-pmd_ed_envaction" _ngcontent-dvn-c25="" class="btn btn-primary btn-sm" type="submit" disabled="">Aceptar</button><button onclick="close_modal();" _ngcontent-dvn-c25="" class="btn btn-secondary btn-sm" type="button">Cancelar</button></div>';
            document.getElementById("cont-modal-body").innerHTML = cont_modal;
        }

        function prnd_action_enviar_doc() {
            close_modal();
            document.getElementById("toast-container").innerHTML = '<div onclick="page_mis_documentos();document.getElementById("toast-container").innerHTML=\'\';" class="alert alert-success"><strong><span class="fa fa-check"></span> REGISTRO ACTUALIZADO CORRECTAMENTE</strong></div>';
            setTimeout(() => {
                document.getElementById("toast-container").innerHTML = '';
                page_mis_documentos();
            }, 3000);
            fetch('contenido/pages/ajax/prnd_action_enviar_doc.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    console.log('actualizado');
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }

        function ped_CM_action_enviar_doc() {
            close_modal();
            document.getElementById("toast-container").innerHTML = '<div onclick="page_mis_documentos();document.getElementById("toast-container").innerHTML=\'\';" class="alert alert-success"><strong><span class="fa fa-check"></span> REGISTRO ACTUALIZADO CORRECTAMENTE</strong></div>';
            setTimeout(() => {
                document.getElementById("toast-container").innerHTML = '';
                page_mis_documentos();
            }, 3000);
            fetch('contenido/pages/ajax/ped_CM_action_enviar_doc.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    console.log('actualizado');
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function prnd_nuevo_doc_leg_ad() {
            open_modal('Documento Adjunto');
            fetch('contenido/pages/ajax/prnd_nuevo_doc_leg_ad.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }

        function prnd_proptec_nuevo_item() {
            open_modal('Selección de Ítems a participar');
            fetch('contenido/pages/ajax/prnd_proptec_nuevo_item.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        let sw_prnd_rp_dropdown_1 = false;

        function prnd_rp_dropdown_1() {
            var element = document.getElementById("id-sw_prnd_rp_dropdown_1");
            if (sw_prnd_rp_dropdown_1) {
                element.style.display = 'none';
            } else {
                element.style.display = 'block';
            }
            sw_prnd_rp_dropdown_1 = !sw_prnd_rp_dropdown_1;
        }

        function prnd_editar_prop_eco() {
            open_modal('Registro de Precios');
            fetch('contenido/pages/ajax/prnd_editar_prop_eco.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                    prnd_rp_dropdown_1();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }

        function prnd_cal_prop_eco(valor, cant, pib) {
            let valor_ofertado = (valor * cant).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            document.getElementById("val-prnd_cal_prop_eco-" + pib).innerHTML = valor_ofertado;
        }
    </script>

    <script>
        function prnd_enviar_file_formulario() {
            const form = document.getElementById("id-FORM_envia_file");
            const formData = new FormData(form);
            fetch('contenido/pages/ajax/prnd_enviar_file_formulario.php', {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
                    document.getElementById("id-prnd_table-doc_leg_ad").innerHTML = text;
                    close_modal();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function prnd_eliminar_archivo(id_arch) {
            if(confirm('DESEA ELIMINAR EL ARCHIVO ?')){
                fetch('contenido/pages/ajax/prnd_enviar_file_formulario.php?id_arch_delete='+id_arch)
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(text) {
                        document.getElementById("id-prnd_table-doc_leg_ad").innerHTML = text;
                    })
                    .catch(function(error) {
                        console.log('Request failed', error)
                    });
            }
        }
    </script>

    <script>
        function prnd_proctec_add_nuevo_item() {
            const form = document.getElementById("FORM-prnd_selec_items");
            const formData = new FormData(form);
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/prnd_proctec_add_nuevo_item.php', {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("id-prnd_table-items").innerHTML = text;
                    close_modal();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        let sw_dropdown_prnd_item = false;
        function dropdown_prnd_item(id_item) {
            var element = document.getElementById("id-dropdown_prnd_item-"+id_item);
            if (sw_dropdown_prnd_item) {
                element.style.display = 'none';
            } else {
                element.style.display = 'block';
            }
            sw_dropdown_prnd_item = !sw_dropdown_prnd_item;
        }
        function prnd_items_doctec(id_item) {
            open_modal('Documentos Adjuntos');
            fetch('contenido/pages/ajax/prnd_items_doctec.php?id_item='+id_item)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function prnd_items_doctec_addoc(id_item) {
            open_modal('Documentos Adjuntos');
            fetch('contenido/pages/ajax/prnd_items_doctec.php?id_item='+id_item+'&addoc=true')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                    dropdown_prnd_item(id_item);
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function prnd_items_doctec_addoc_send_file() {
            const form = document.getElementById("id-FORM_envia_file");
            const formData = new FormData(form);
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/prnd_items_doctec.php', {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                    prnd_items_doctec_updatetable();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function prnd_items_doctec_updatetable() {
            document.getElementById("id-prnd_table-items").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/prnd_items_doctec_updatetable.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("id-prnd_table-items").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function prnd_editar_prop_eco_p2(){
            const form = document.getElementById("id-FORM_prnd_editar_prop_eco_p2");
            const formData = new FormData(form);
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/prnd_editar_prop_eco_p2.php', {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("pnrd-propuestas-economicas").innerHTML = text;
                    close_modal();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function validacion__prnd_editar_prop_eco_p2(json_validaciones){
            const validaciones = JSON.parse(json_validaciones);
            let sw_valido = true;
            let precio_unitario_ofertado = 0;
            let precio_referencial_item = 0;
            for (var i=0; i< validaciones.length; i++){
                precio_unitario_ofertado = parseFloat(document.getElementById(validaciones[i].input_id).value);
                precio_referencial_item = parseFloat(validaciones[i].precio_referencial);
                if(!(!isNaN(precio_unitario_ofertado) && isFinite(precio_unitario_ofertado))){
                    error_alert("ERROR! - El valor ingresado no es un numero. \n ITEM: "+validaciones[i].desc_item);
                    sw_valido = false;
                }else if(precio_unitario_ofertado<0){
                    error_alert("ERROR! - El valor ingresado no debe ser negativo. \n ITEM: "+validaciones[i].desc_item);
                    sw_valido = false;
                }else if(precio_unitario_ofertado==0){
                    error_alert("ERROR! - El valor ingresado no debe ser cero. \n ITEM: "+validaciones[i].desc_item);
                    sw_valido = false;
                }else if(precio_unitario_ofertado==precio_referencial_item){
                    error_alert("ERROR! - El valor ingresado no debe ser igual al precio referencial. \n ITEM: "+validaciones[i].desc_item);
                    sw_valido = false;
                }else if(precio_unitario_ofertado>precio_referencial_item){
                    error_alert("ERROR! - El valor ingresado no debe ser mayor al precio referencial. \n ITEM: "+validaciones[i].desc_item);
                    sw_valido = false;
                }
            }
            if(sw_valido){
                prnd_editar_prop_eco_p2();
            }
        }
    </script>
    <script>
        function prnd_margpref_new(){
            open_modal('M&aacute;rgenes de Preferencia');
            fetch('contenido/pages/ajax/prnd_margpref_new.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function prnd_margpref_addnew(){
            const form = document.getElementById("FORM-prnd_margpref_addnew");
            const formData = new FormData(form);
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/prnd_margpref_addnew.php', {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("id-prnd_tabla_margpref").innerHTML = text;
                    close_modal();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function prnd_itemmargpref_new(id_item){
            open_modal('M&aacute;rgenes de Preferencia');
            fetch('contenido/pages/ajax/prnd_itemmargpref_new.php?id_item='+id_item)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                    dropdown_prnd_item(id_item);
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function prnd_itemmargpref_addnew(){
            const form = document.getElementById("FORM-prnd_itemmargpref_addnew");
            const formData = new FormData(form);
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/prnd_itemmargpref_addnew.php', {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("id-prnd_tabla_itemmargpref").innerHTML = text;
                    close_modal();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function prnd_reg_plazoentrega(){
            open_modal('Registro de Plazos');
            fetch('contenido/pages/ajax/prnd_reg_plazoentrega.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                    dropdown_prnd_item('a-9');
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function prnd_reg_plazoentrega_p2(){
            const form = document.getElementById("id-prnd_reg_plazoentrega_p2");
            const formData = new FormData(form);
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/prnd_reg_plazoentrega_p2.php', {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("prnd-plazosentrega").innerHTML = text;
                    close_modal();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('img-to-show').setAttribute('src',e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function pnp_add_nueva_imagen(){
            const form = document.getElementById("FORM-prod-img");
            const formData = new FormData(form);
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/pnp_add_nueva_imagen.php', {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("id-pnp_tabla_imgs").innerHTML = text;
                    close_modal();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function pnp_add_nuevo_atributo(){
            const form = document.getElementById("FORM-nuevo-atributo");
            const formData = new FormData(form);
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/pnp_add_nuevo_atributo.php', {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("id-pnp_tabla_atrib").innerHTML = text;
                    close_modal();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function pnp_selec_atributo(){
            open_modal('Buscar Atributos');
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/pnp_selec_atributo.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function pnp_selec_atributo_ok(atributo){
            open_modal('Atributo');
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/pnp_nuevo_atributo.php?atributo='+atributo)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function pnp_nuevo_precio(){
            open_modal('Precio por Ubicaci&oacute;n');
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/pnp_nuevo_precio.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function pnp_selec_ubicacion(){
            open_modal('Ubicaciones Geograficas');
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/pnp_selec_ubicacion.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function pnp_selec_ubicacion_ok(ubicacion){
            open_modal('Precio por Ubicaci&oacute;n');
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/pnp_nuevo_precio.php?ubicacion='+ubicacion)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }

        function pnp_nuevo_precio_descuento(){
            document.getElementById("id-pnp_nuevo_precio_minimodal").style.display='block';
        }
        function pnp_add_nuevo_descuento(){
            const cantidad_desde = document.getElementById("id-input-cantidad_desde").value;
            const cantidad_hasta = document.getElementById("id-input-cantidad_hasta").value;
            const precio_descuento = document.getElementById("id-input-precio_descuento").value;
            fetch('contenido/pages/ajax/pnp_add_nuevo_descuento.php?cantidad_desde='+cantidad_desde+'&cantidad_hasta='+cantidad_hasta+'&precio_descuento='+precio_descuento)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("id-pnp-tabla-descuentos").innerHTML = text;

                    document.getElementById("id-input-cantidad_desde").value = '';
                    document.getElementById("id-input-cantidad_hasta").value = '';
                    document.getElementById("id-input-precio_descuento").value = '';
                    document.getElementById('id-pnp_nuevo_precio_minimodal-btn-aceptar').setAttribute('disabled','disabled');
                    document.getElementById("id-pnp_nuevo_precio_minimodal").style.display='none';
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });        
        }
        function pnp_add_nuevo_precio(){
            const form = document.getElementById("FORM-nuevo-precio");
            const formData = new FormData(form);
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/pnp_add_nuevo_precio.php', {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("id-pnp_tabla_precios").innerHTML = text;
                    close_modal();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function pnp_nuevo_adjunto(){
            open_modal('Registro de Enlace');
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/pnp_nuevo_adjunto.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function pnp_selec_arch_enlace(dato){
            if(dato=='Archivo'){
                document.getElementById("id-frm-adjuntos").innerHTML = '<div _ngcontent-cyf-c35="" class="col-lg-12 col-md-12 col-sm-12 col-12"><div _ngcontent-cyf-c35="" class="col-12"><!----><!----><label _ngcontent-cyf-c35="" class="control-obligatorios control-obligatorios">Nombre Archivo:</label></div><div _ngcontent-cyf-c35="" class="col-lg-9 col-md-9 col-sm-9 col-12 form-group margin-bottom-5"><form _ngcontent-cyf-c35="" novalidate="" class="ng-pristine ng-valid ng-touched"><!----><input name="enlace" _ngcontent-cyf-c35="" class="form-control input-sm ng-touched ng-pristine ng-valid" formcontrolname="maxlen" maxlength="49" type="text"><!----><!----><!----></form><!----></div></div>  <div _ngcontent-cyf-c35="" class="col-lg-12 col-md-12 col-sm-12 col-12"><div _ngcontent-cyf-c35="" class="col-12"></div><div _ngcontent-cyf-c35="" class="col-lg-9 col-md-9 col-sm-9 col-12 form-group margin-bottom-5"><!----><label _ngcontent-cyf-c35="" class="file-upload" for="file2" style="display:inline;"><button _ngcontent-cyf-c35="" class="btn btn-sm btn-secondary"><i _ngcontent-cyf-c35="" class="fa fa-paperclip text-primary"></i> Adjuntar</button><input _ngcontent-cyf-c35="" ng2fileselect="" type="file"></label></div></div>';
            }else if(dato==''){
                document.getElementById("id-frm-adjuntos").innerHTML = '';
            }else{
                document.getElementById("id-frm-adjuntos").innerHTML = '<div _ngcontent-cyf-c35="" class="col-lg-12 col-md-12 col-sm-12 col-12"><div _ngcontent-cyf-c35="" class="col-12"><!----><label _ngcontent-cyf-c35="" class="control-obligatorios control-obligatorios">Enlace:</label><!----></div><div _ngcontent-cyf-c35="" class="col-lg-9 col-md-9 col-sm-9 col-12 form-group margin-bottom-5"><form _ngcontent-cyf-c35="" novalidate="" class="ng-pristine ng-valid ng-touched"><!----><!----><!----><input name="enlace" _ngcontent-cyf-c35="" class="form-control input-sm ng-untouched ng-pristine ng-valid" formcontrolname="url" maxlength="299" placeholder="http://" type="url"><!----></form><!----></div></div>';
            }
        }
        function pnp_add_nuevo_adjunto(){
            const form = document.getElementById("FORM-nuevo-adjunto");
            const formData = new FormData(form);
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/pnp_add_nuevo_adjunto.php', {
                    method: "POST",
                    body: formData,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("id-pnp_tabla_adjuntos").innerHTML = text;
                    close_modal();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function pjsi_calcular_precio(num_item,valor,cantidad) {
            let valor_ofertado = (valor * cantidad).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            document.getElementById("id-ofert-item-"+num_item).innerHTML = valor_ofertado;
            let total_ofertado = 0;
            total_ofertado += parseInt(document.getElementById("id-input-item-1").value*3200);
            total_ofertado += parseInt(document.getElementById("id-input-item-2").value*3500);
            total_ofertado += parseInt(document.getElementById("id-input-item-3").value*1000);
            total_ofertado += parseInt(document.getElementById("id-input-item-4").value*270);
            const send_ofertado = (total_ofertado * 1).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            document.getElementById("id-total-ofertado").innerHTML = send_ofertado;
        }
        function pjsi_registrar_precio(num_item){
            const valor_ofertado = parseFloat(document.getElementById("id-input-item-"+num_item).value);
            const data = { valor_ofertado: valor_ofertado, num_item: num_item };
            if( 
                (num_item=='1' && valor_ofertado>=120) || 
                (num_item=='2' && valor_ofertado>=170) || 
                (num_item=='3' && valor_ofertado>=40) || 
                (num_item=='4' && valor_ofertado>=20)
            ){
                error_alert('ERROR - El precio ofertado debe ser menor al precio referencial.');
            }else if( valor_ofertado<=0 ){
                error_alert('ERROR - El precio ofertado no debe ser negativo o cero.');
            }else if( !(!isNaN(valor_ofertado) && isFinite(valor_ofertado)) ){
                error_alert('ERROR - El precio ofertado debe no es un numero.');
            }else{
                const formData = new FormData();
                formData.append('valor_ofertado', valor_ofertado);
                formData.append('num_item', num_item);
                document.getElementById("id-tabla_items").innerHTML = "<br><br>Cargando...<br><br>";
                fetch('contenido/pages/ajax/pjsi_registrar_precio.php', {
                        method: "POST",
                        body: formData,
                    })
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(text) {
                        document.getElementById("id-tabla_items").innerHTML = text;
                        pjsi_actualizar_historial();
                    })
                    .catch(function(error) {
                        console.log('Request failed', error)
                    });
            }
        }
        function pjsi_actualizar_tabla(){
            document.getElementById("id-tabla_items").innerHTML = "<br><br>Cargando...<br><br>";
            fetch('contenido/pages/ajax/pjsi_registrar_precio.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("id-tabla_items").innerHTML = text;
                    pjsi_actualizar_historial();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function pjsi_actualizar_historial(){
            document.getElementById("id-tabla_hitorial").innerHTML = "<br><br>Cargando...<br><br>";
            fetch('contenido/pages/ajax/pjsi_actualizar_historial.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("id-tabla_hitorial").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
        function pjsi_resultados_subasta(){
            document.getElementById("id-tabla_resultados").innerHTML = "<br><br>Cargando...<br><br>";
            fetch('contenido/pages/ajax/pjsi_resultados_subasta.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("id-tabla_resultados").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function cerrar_sesion(){
            fetch('contenido/pages/ajax/cerrar_sesion.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    location.reload();
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });
        }
    </script>
    <script>
        function selec_tab(num){
            for (let index = 1; index <=4; index++) {
                document.getElementById("tab-cont"+index).classList.remove('active');
                document.getElementById("cont"+index).classList.remove('show');
                document.getElementById("cont"+index).classList.remove('active');
                if(index==num){
                    document.getElementById("tab-cont"+index).classList.add('active');
                    document.getElementById("cont"+index).classList.add('show');
                    document.getElementById("cont"+index).classList.add('active');
                }
            }
        }
    </script>
    <script>
        function reiniciar_subasta(dat){
            if(confirm(' DESEA REINICIAR LA SUBASTA ? \n Se eliminaran todas las ofertas enviadas para esta subasta.')){
                fetch('contenido/pages/ajax/reiniciar_subasta.php?dat='+dat)
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(text) {
                        page_subastas();
                    })
                    .catch(function(error) {
                        console.log('Request failed', error)
                    });
            }
        }
    </script>

    <script>
        function error_alert(texto){
            document.getElementById("texto-error").innerHTML=texto;
            document.getElementById("modal-error").style.display='block';
        }
        function close_modal_error(){
            document.getElementById("modal-error").style.display='none';
        }
    </script>

    <script>
        function pjs_info_propuesta(id_prop){
            open_modal('Documento 25271.2 - Todos los Items: LOTE/PAQUETE');
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/pjs_info_propuesta.php?id_prop='+id_prop)
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });               
        }
        function pjs_info_cronograma(){
            open_modal('Cronograma del Proceso');
            document.getElementById("cont-modal-body").innerHTML = '<h3>Cargando...</h3>';
            fetch('contenido/pages/ajax/pjs_info_cronograma.php')
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    document.getElementById("cont-modal-body").innerHTML = text;
                })
                .catch(function(error) {
                    console.log('Request failed', error)
                });               
        }
    </script>


    <bs-modal-backdrop class="modal-backdrop fade in show" id="back-modal" style="display: none;"></bs-modal-backdrop>


    <div id="modal-error" style="display: none;" onclick="close_modal_error();">
        <bs-modal-backdrop class="modal-backdrop fade in show" onclick="close_modal_error();" style="z-index: 10000;"></bs-modal-backdrop>
        <div class="">
            <div class="" style="background: white;width: 500px;z-index: 100000;position: fixed;top: 20%;left: 50%;margin-left: -250px;border-radius: 5px;padding: 20px;">
                <div class="swal-icon swal-icon--error" style="width: 80px;height: 80px;border-width: 4px;border-style: solid;border-radius: 50%;padding: 0;position: relative;box-sizing: content-box;margin: 20px auto;border-color: #f27474;-webkit-animation: animateErrorIcon .5s;animation: animateErrorIcon .5s;">
                    <div class="swal-icon--error__x-mark">
                        <span style="position: absolute;height: 5px;width: 47px;background-color: #f27474;display: block;top: 37px;border-radius: 2px;-webkit-transform: rotate(45deg);transform: rotate(45deg);left: 17px;"></span>
                        <span style="position: absolute;height: 5px;width: 47px;background-color: #f27474;display: block;top: 37px;border-radius: 2px;-webkit-transform: rotate(-45deg);transform: rotate(-45deg);right: 16px;"></span>
                    </div>
                </div>
                <div style="color: rgba(0,0,0,.65);font-weight: 600;text-transform: none;position: relative;display: block;padding: 13px 16px;font-size: 27px;line-height: normal;text-align: center;margin-bottom: 0;">¡ ERROR !</div>
                <div id="texto-error" style="font-size: 16px;position: relative;float: none;line-height: normal;vertical-align: top;text-align: left;display: inline-block;margin: 0;padding: 0 10px;font-weight: 400;color: rgba(0,0,0,.64);max-width: calc(100% - 20px);overflow-wrap: break-word;box-sizing: border-box;">ERROR! - El precio total ofertado 184000 debe ser menor al registrado en su anterior lance que es 200000</div>
                <div style="text-align: right;padding-top: 13px;margin-top: 13px;padding: 13px 16px;border-radius: inherit;border-top-left-radius: 0;border-top-right-radius: 0;">
                    <div class="swal-button-container">
                        <button style="background-color: #7cd1f9;color: #fff;border: none;box-shadow: none;border-radius: 5px;font-weight: 600;font-size: 14px;padding: 10px 24px;margin: 0;cursor: pointer;">OK</button>
                        <div class="swal-button__loader">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>