<?php
/* proceso de ingreso a cuenta */
include_once 'contenido/paginas/items/item.proceso.ingresar-a-cuenta.php';

/* proceso persistencia de cuenta */
include_once 'contenido/paginas/items/item.proceso.persistencia-cuenta.php';

/* proceso setting pushnav */
include_once 'contenido/paginas/items/item.proceso.setting-pushnav.php';

/* URL CONVERT */


/* dir certificado */
if (isset($dir[1])) {
    $rqc1 = query("SELECT id FROM emisiones_certificados WHERE certificado_id='" . $dir[1] . "' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rqc1) > 0) {
        $get[2] = $dir[1];
        $get[1] = 'certificado-view';
    }
}

/* curso individual */
if (isset($get[1])) {
    $rqc1 = query("SELECT id FROM cursos WHERE titulo_identificador='" . $get[1] . "' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rqc1) > 0) {
        if (isset($get[2])) {
            $get[3] = $get[2];
        }
        $get[2] = $get[1];
        $get[1] = 'curso';
    }
}

/* dir por departamento */
if (isset($dir[1])) {
    $rqc1 = query("SELECT id FROM departamentos WHERE titulo_identificador='" . $dir[1] . "' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rqc1) > 0) {
        $get[2] = $dir[1];
        $get[1] = 'departamento';
    }
}

/* dir por departamento show2 */
if (isset($dir[1])) {
    $rqc1 = query("SELECT titulo_identificador FROM departamentos WHERE cod LIKE '" . $dir[1] . "' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rqc1) > 0) {
        $rqc2 = mysql_fetch_array($rqc1);
        $get[2] = $rqc2['titulo_identificador'];
        $get[1] = 'cursos-por-ciudad';
    }
}

/* END - URL CONVERT */


/* exception: curso enlace corto */

if (isset($dir[1])) {

    $id_curso = (int) $dir[1];
    if ($id_curso > 0) {
        $id_curso = $id_curso + 1000;
        $rqdc1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        if (mysql_num_rows($rqdc1) > 0) {
            $rqdc2 = mysql_fetch_array($rqdc1);
            $get[1] = 'curso';
            $get[2] = $rqdc2['titulo_identificador'];
        }
    }
}
/* END exception: curso enlace corto */


if (isset($dir[1]) && ($dir[1]=='g')) {
    $id_grupo = (int) $dir[2];
    if ($id_grupo > 0) {
        $rqdc1 = query("SELECT titulo_identificador FROM cursos_agrupaciones WHERE id='$id_grupo' ORDER BY id DESC limit 1 ");
        if (mysql_num_rows($rqdc1) > 0) {
            $rqdc2 = mysql_fetch_array($rqdc1);
            $get[1] = 'grupo';
            $get[2] = $rqdc2['titulo_identificador'];
        }
    }
}


/* default tags */
$titulo_pagina = 'GEG - Google Educator Groups Bolivia';
$descripcion_pagina = 'Google Educator Groups Bolivia';
$keywords_pagina = 'cursos,capacitaciones,bolivia';
$url_pagina = str_replace('//', '/', $dominio . $_SERVER['REQUEST_URI']);
$image_pagina = 'https://gegbolivia.cursos.bo/contenido/imagenes/banners/geg-banner.PNG';

/* metatags */
if (isset($get[1])) {
    
    /* verification */
    if ($get[1] == 'google74133261c6a2d503') {
        echo 'google-site-verification: google74133261c6a2d503.html';
        exit;
    }

    /* matatag departamento */
    if ($get[1] == 'departamento') {
        $rqdd1 = query("SELECT * FROM departamentos WHERE titulo_identificador='" . $get[2] . "' LIMIT 1 ");
        $rqdd2 = mysql_fetch_array($rqdd1);
        $titulo_pagina = "Cursos y capacitaciones en " . $rqdd2['nombre'] . " - Bolivia";
        $descripcion_pagina = "Cursos y capacitaciones organizados en " . $rqdd2['nombre'] . " Bolivia, cursos en modalidad presencial y online.";
        $keywords_pagina = 'cursos,capacitaciones,' . $rqdd2['nombre'] . ',bolivia,seminarios,curso online';
        $image_pagina = 'https://cursos.bo/contenido/imagenes/images/banner-general-cursosbo.jpg';
    }
    
    /* matatag por ciudad (departamento) */
    /* listado-2 por departamento */
    if (isset($get[1]) && (substr($get[1], 0, 10) == 'cursos-en-')) {
        $aux_tcpc = str_replace('cursos-en-', '', $get[1]);
        $rqdd1 = query("SELECT * FROM departamentos WHERE titulo_identificador='$aux_tcpc' LIMIT 1 ");
        $rqdd2 = mysql_fetch_array($rqdd1);
        $titulo_pagina = "Cursos y capacitaciones en " . $rqdd2['nombre'] . " - Bolivia";
        $descripcion_pagina = "Cursos y capacitaciones organizados en " . $rqdd2['nombre'] . " Bolivia, cursos en modalidad presencial y online.";
        $keywords_pagina = 'cursos,capacitaciones,' . $rqdd2['nombre'] . ',bolivia,seminarios,curso online';
        $image_pagina = 'https://cursos.bo/contenido/imagenes/images/banner-general-cursosbo.jpg';
        if ($rqdd2['img_banner'] !== '') {
            $image_pagina = 'https://cursos.bo/contenido/imagenes/departamentos/' . $rqdd2['img_banner'];
        }
    }

    /* matatag departamento (second) */
    if ($get[1] == 'cursos-por-ciudad') {
        $rqdd1 = query("SELECT * FROM departamentos WHERE titulo_identificador='" . $get[2] . "' LIMIT 1 ");
        $rqdd2 = mysql_fetch_array($rqdd1);
        $titulo_pagina = "Cursos y capacitaciones en " . $rqdd2['nombre'] . " - Bolivia";
        $descripcion_pagina = "Cursos y capacitaciones organizados en " . $rqdd2['nombre'] . " Bolivia, cursos en modalidad presencial y online.";
        $keywords_pagina = 'cursos,capacitaciones,' . strtolower($rqdd2['nombre']) . ',bolivia,seminarios,curso online';
        $image_pagina = 'https://cursos.bo/contenido/imagenes/images/banner-general-cursosbo.jpg';
        if ($rqdd2['img_banner'] !== '') {
            $image_pagina = 'https://cursos.bo/contenido/imagenes/departamentos/' . $rqdd2['img_banner'];
        }
    }


    /* matatag curso */
    if ($get[1] == 'curso') {
        $rqc1 = query("SELECT titulo,imagen,contenido FROM cursos WHERE titulo_identificador='" . $get[2] . "' AND estado IN (0,1,2,3) ORDER BY FIELD(estado,1,2,0,3),id DESC limit 1 ");
        if (mysql_num_rows($rqc1) > 0) {
            $rqc2 = mysql_fetch_array($rqc1);
            $titulo_pagina = $rqc2['titulo'];
            $descripcion_pagina = substr(strip_tags($rqc2['contenido']), 0, 300) . '...';
            $image_pagina = $dominio . 'paginas/' . $rqc2['imagen'] . '.size=6.img';
            $keywords_pagina = 'curso,' . $rqc2['titulo'] . ',bolivia,certificado';
            if (!file_exists("contenido/imagenes/paginas/" . $rqc2['imagen'])) {
                $image_pagina = 'https://www.infosicoes.com/paginas/' . $rqc2['imagen'] . '.size=6.img';
            }
            //$tagmgr_head = $rqc2['pixelcode'];
            //$tagmgr_body = $rqc2['tagmgr_body'];
        }
    }
    
    /* matatag grupo */
    if ($get[1] == 'grupo') {
        $rqc1 = query("SELECT titulo,imagen,contenido FROM cursos_agrupaciones WHERE titulo_identificador='" . $get[2] . "' AND estado IN (0,1,2,3) ORDER BY FIELD(estado,1,2,0,3),id DESC limit 1 ");
        if (mysql_num_rows($rqc1) > 0) {
            $rqc2 = mysql_fetch_array($rqc1);
            $titulo_pagina = $rqc2['titulo'];
            $descripcion_pagina = substr(strip_tags($rqc2['contenido']), 0, 300) . '...';
            $image_pagina = $dominio . 'paginas/' . $rqc2['imagen'] . '.size=6.img';
            $keywords_pagina = 'curso,' . $rqc2['titulo'] . ',bolivia,certificado';
        }
    }
    
    /* meta tag cursos virtuales */
    if ($get[1] == 'cursos-virtuales') {
        $titulo_pagina = 'CURSOS VIRTUALES EN BOLIVIA';
        $descripcion_pagina = 'Cursos virtuales con emision de certificado.';
        $keywords_pagina = 'cursos virtuales,curso,virtual,bolivia';
        $image_pagina = 'https://cursos.bo/contenido/imagenes/images/banner-c-virtuales.jpg';
    }
    
    /* matatag entidades-financieras */
    if ($get[1] == 'entidades-financieras') {
        $titulo_pagina = "Entidades financieras abiertas en el periodo de cuarentena en Bolivia";
        $descripcion_pagina = "Informacion de las entidades financieras abiertas en el periodo de cuarentena en Bolivia.";
        $keywords_pagina = 'cursos,entidad financiera,cuarentena,bolivia,seminarios,curso online';
        $image_pagina = 'https://cursos.bo/contenido/imagenes/images/banner-general-cursosbo.jpg';
    }
    
}
?>
<!DOCTYPE html>
<html lang="es" class="fa-events-icons-ready js csstransitions csstransforms csstransforms3d">
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <base href="https://gegbolivia.cursos.bo/" target="_self"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="language" content="es"/>
        <meta name="robots" content="index,follow"/>
        <meta name="author" content="Cursos.BO"/>
        <meta name="category" content="General"/>
        <meta name="rating" content="General"/>
        <meta name="keywords" content="<?php echo $keywords_pagina; ?>"/>
        <meta name="title" content="<?php echo $titulo_pagina; ?>" />
        <meta name="description" content="<?php echo $descripcion_pagina; ?>"/>

        <title><?php echo $titulo_pagina; ?></title>

        <meta property="og:url" content="<?php echo $url_pagina; ?>" />
        <meta property="og:title" content="<?php echo $titulo_pagina; ?>" />
        <meta property="og:description" content="<?php echo $descripcion_pagina; ?>" />
        <meta property="og:site_name" content="Cursos.BO" />
        <meta property="og:image" content="<?php echo $image_pagina; ?>" />

        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:description" content="<?php echo $descripcion_pagina; ?>"/>
        <meta name="twitter:title" content="<?php echo $titulo_pagina; ?>"/>
        <meta name="twitter:site" content="Cursos.BO"/>
        <meta name="twitter:domain" content="https://cursos.bo"/>
        <meta name="twitter:creator" content="Cursos.BO"/>
        <link rel="image_src" href="<?php echo $image_pagina; ?>"/>


        <link href="contenido/alt/bootstrap.css" rel="stylesheet"/>
        <link href="contenido/alt/style.css" rel="stylesheet"/>
        <link href="contenido/alt/responsive.css" rel="stylesheet"/>
        <link href="contenido/alt/layout-semiboxed.css" rel="stylesheet"/>
        <link href="contenido/alt/skin-red.css" rel="stylesheet"/>
        <link href="contenido/alt/yamm.css" rel="stylesheet"/>
        <link href="contenido/alt/custom.css" rel="stylesheet"/>
        <link href="contenido/alt/jquery.sidr.css" rel="stylesheet"/>
        <link rel="shortcut icon" type="image/png" href="contenido/imagenes/images/favicon.png"/>

        <script src="contenido/alt/c5f1172444.js.descarga"></script>
        <link href="contenido/alt/c5f1172444.css" media="all" rel="stylesheet"/>

        <link href="contenido/css/data-style4.css" media="all" rel="stylesheet"/>
        
        <script src="contenido/alt/jquery.js.descarga"></script>
<script src="contenido/alt/bootstrap.js.descarga"></script>
<script src="contenido/alt/jquery.sidr.js.descarga"></script>
<script src="contenido/alt/plugins.js.descarga"></script>
<script src="contenido/alt/common.js"></script>
<script src="contenido/alt/validable.js.descarga"></script>
<script src="contenido/alt/footer.js.descarga"></script>
<script src="contenido/alt/index.js.descarga"></script>
<script src="contenido/alt/sidr.js.descarga"></script>


        <?php
        /* pixel_facebook */
        $rq1 = query("SELECT pixel_facebook FROM cursos_webdata LIMIT 1 ");
        $rq2 = mysql_fetch_array($rq1);
        $pixel_facebook = $rq2['pixel_facebook'];
        echo $pixel_facebook;
        ?>
        <style>
            .wowmenu.tiny {
                background: rgb(255, 255, 255) !important;
            }
            .navbar-nav>li>a {
                color: #8c8c8c;
            }
            .navbar-nav>li.active>a, .navbar-nav>li.active>a:hover, .navbar-nav>li.active>a:focus {
                background-color: #d6d8e3;
                border-bottom: 7px solid #a9a9a9;
            }
            .copyright {
                background-color: #FFF;
                color: #989898;
            }
            #TextoConsultasPie {
                background-color: #3369e8;
                border-top: 1px solid #ff3c3c;
            }
            .footer {
                color: #227b94;
                background-color: #dfe0ea;
            }
            .copyright ul.footermenu li a {
                color: #878787;
                height: 50px;
            }
            .rojo {
                background-color: #d5a82c;
            }
            .titulo-second-f1-a {
                background: #e84c45;
            }
            .bx-img-curso-f1 {
                border-top: 1px solid #b0b1bd;
                border-left: 1px solid #b0b1bd;
                border-right: 1px solid #b0b1bd;
            }
            .TituloArea h3:after {
                padding-bottom: 0px;
            }
        </style>
        <?php if(isset($get[1]) && $get[1]=='registro'){ ?>
        <meta name="google-signin-scope" content="profile email">
        <meta name="google-signin-client_id" content="521942249199-p8m7lposn5urnl8k2l67k08sh3jgqdjd.apps.googleusercontent.com">
        <script src="https://apis.google.com/js/platform.js" async defer></script>
        <?php } ?>
        <style>
@media (max-width: 750px){
    .TituloArea h3 {
        margin-top: 35px;
        margin-bottom: -10px;
        padding: 20px 0px !important;
    }
}
.container {
    width: 100% !important;
}
.wrapsemibox {
    box-shadow: 0px 0px 0px transparent !important;
    border-bottom: 0px !important;
}
</style>
    </head>
    <body class="on" style="background: #FFF;">

        <div id="domain" style="display: none;">https://gegbolivia.cursos.bo</div>

        <div class="wrapbox">

            <div style='height:10px;'>&nbsp;</div>

            <nav class="navbar yamm navbar-fixed-top wowmenu tiny" role="navigation" style="display: none;">
                <div class="container">
                    <div class="navbar-header">
                        <a class="navbar-brand logo-nav" href="https://gegbolivia.cursos.bo/"><img src="contenido/imagenes/images/logotipo.PNG" style="height:40px;" alt="logo">
                        </a>            

                    </div>
                    <?php
                    include_once 'contenido/paginas/items/item.menu_principal.php';
                    ?>
                </div>	
            </nav>
            <div id="mobile-header" class="hidden-md hidden-lg" style="display: none;">
                <a id="simple-menu" class="menu-button" href="https://gegbolivia.cursos.bo/#sidr">Toggle menu</a>
            </div>

            <div id="sidr" class="hidden-md hidden-lg sidr left" style="transition: left 0.1s ease-in-out;">
                <a class="sidr_close pull-right" href="https://gegbolivia.cursos.bo/#" style="padding:5px;"><i class="icon-remove-sign text-contrast"></i></a>
                    <?php
                    include_once 'contenido/paginas/items/item.menu_mobile.php';
                    ?>
            </div>
