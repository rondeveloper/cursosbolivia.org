<?php
/* proceso de ingreso a cuenta */
include_once 'contenido/paginas/items/item.proceso.ingresar-a-cuenta.php';

/* proceso persistencia de cuenta */
include_once 'contenido/paginas/items/item.proceso.persistencia-cuenta.php';

/* proceso setting pushnav */
include_once 'contenido/paginas/items/item.proceso.setting-pushnav.php';

/* datos de configuracion */
$img_logotipo_principal = $__CONFIG_MANAGER->getImg('img_logotipo_principal');
$img_banner_principal = $__CONFIG_MANAGER->getImg('img_banner_principal');
$img_banner_cvirtuales = $__CONFIG_MANAGER->getImg('img_banner_cvirtuales');

/* URL CONVERT */

/* noticia */
if (isset($get[1])) {
    $rqc1 = query("SELECT id FROM publicaciones WHERE titulo_identificador='" . $get[1] . "' ORDER BY id DESC limit 1 ");
    if (num_rows($rqc1) > 0) {
        if (isset($get[2])) {
            $get[3] = $get[2];
        }
        $get[2] = $get[1];
        $get[1] = 'noticia';
    }
}

/* curso individual */
if (isset($get[1])) {
    $rqc1 = query("SELECT id FROM cursos WHERE titulo_identificador='" . $get[1] . "' ORDER BY id DESC limit 1 ");
    if (num_rows($rqc1) > 0) {
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
    if (num_rows($rqc1) > 0) {
        $get[2] = $dir[1];
        $get[1] = 'departamento';
    }
}

/* dir por departamento show2 */
if (isset($dir[1])) {
    $rqc1 = query("SELECT titulo_identificador FROM departamentos WHERE cod LIKE '" . $dir[1] . "' ORDER BY id DESC limit 1 ");
    if (num_rows($rqc1) > 0) {
        $rqc2 = fetch($rqc1);
        $get[2] = $rqc2['titulo_identificador'];
        $get[1] = 'cursos-por-ciudad';
    }
}

/* dir registro a curso */
if (isset($dir[2]) && $dir[1]=='R') {
    if((int)$dir[2]==0){
        $rqc1 = query("SELECT r.id_curso FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON r.id_enlace=e.id WHERE e.enlace='" . $dir[2] . "' ORDER BY r.id DESC limit 1 ");
        if (num_rows($rqc1) > 0) {
            $rqc2 = fetch($rqc1);
            $rqcdci1 = query("SELECT titulo_identificador FROM cursos WHERE id='" . $rqc2['id_curso'] . "' ORDER BY id DESC limit 1 ");
            if (num_rows($rqcdci1) > 0) {
                $rqcdci2 = fetch($rqcdci1);
                $get[2] = $rqcdci2['titulo_identificador'];
                $get[1] = 'registro-curso';
            }
        }
    }else{
        $rqc1 = query("SELECT titulo_identificador FROM cursos WHERE id='" . (int)$dir[2] . "' ORDER BY id DESC limit 1 ");
        if (num_rows($rqc1) > 0) {
            $rqc2 = fetch($rqc1);
            $get[2] = $rqc2['titulo_identificador'];
            $get[1] = 'registro-curso';
        }
    }
}

/* dir referidos */
/*
if (isset($dir[2]) && $dir[1]=='r') {
    $get[1] = 'r';
    $id_referido = $dir[2];
    $rqdrfcm1 = query("SELECT c.titulo_identificador,r.ip_registro FROM cursos c INNER JOIN recomendaciones r ON r.id_curso=c.id INNER JOIN recomendaciones_referidos rf ON rf.id_recomendacion=r.id WHERE rf.id='$id_referido' ORDER BY rf.id DESC LIMIT 1 ");
    $rqdrfcm2 = fetch($rqdrfcm1);
    $ref_ip_registro = $rqdrfcm2['ip_registro'];
    $get[2] = $rqdrfcm2['titulo_identificador'];
    $get[1] = 'curso';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
        $ip_actual = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_actual = $_SERVER['REMOTE_ADDR'];
    }
    if($ref_ip_registro!=$ip_actual){
        query("UPDATE recomendaciones_referidos SET ip_ingreso='$ip_actual',estado='2' WHERE id='$id_referido' ORDER BY id DESC limit 1 ");
    }
}
*/
/* END - URL CONVERT */


/* exception: curso enlace corto */
if (isset($dir[1])) {
    $id_curso = (int) $dir[1];
    if ($id_curso > 0) {
        $id_curso = $id_curso + $___num_reduccion_id_curso;
        $rqdc1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        if (num_rows($rqdc1) > 0) {
            $rqdc2 = fetch($rqdc1);
            $get[1] = 'curso';
            $get[2] = $rqdc2['titulo_identificador'];
        }
    }
}
/* END exception: curso enlace corto */

/* dir enlace a curso */
if (isset($dir[1])) {
    $rqc1 = query("SELECT r.id_curso FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON r.id_enlace=e.id WHERE e.enlace='" . $dir[1] . "' ORDER BY r.id DESC limit 1 ");
    if (num_rows($rqc1) > 0) {
        $rqc2 = fetch($rqc1);
        $rqcdci1 = query("SELECT titulo_identificador FROM cursos WHERE id='" . $rqc2['id_curso'] . "' ORDER BY id DESC limit 1 ");
        if (num_rows($rqcdci1) > 0) {
            $rqcdci2 = fetch($rqcdci1);
            $get[2] = $rqcdci2['titulo_identificador'];
            $get[1] = 'curso';
        }
    }
}

/* blog */
if (isset($dir[1])) {
    $tit_ident_blog = $dir[1];
    $rqdb1 = query("SELECT id FROM blog WHERE titulo_identificador='$tit_ident_blog' ORDER BY id DESC limit 1 ");
    if (num_rows($rqdb1) > 0) {
        $get[1] = 'blog';
        $get[2] = $tit_ident_blog;
    }
}
/* END blog */

/*
if (isset($dir[1]) && ($dir[1]=='g')) {
    $id_grupo = (int) $dir[2];
    if ($id_grupo > 0) {
        $rqdc1 = query("SELECT titulo_identificador FROM cursos_agrupaciones WHERE id='$id_grupo' ORDER BY id DESC limit 1 ");
        if (num_rows($rqdc1) > 0) {
            $rqdc2 = fetch($rqdc1);
            $get[1] = 'grupo';
            $get[2] = $rqdc2['titulo_identificador'];
        }
    }
}
*/

/* certificados enlace directo */
if (isset($dir[1]) && ($dir[1]=='C')) {
    $certificado_id = $dir[2];
    header("Location: ".$dominio_www."contenido/paginas/procesos/pdfs/certificado-digital-3.php?id_certificado=".$certificado_id."&download=true");
    exit;
}

/* factura enlace directo */
if (isset($dir[1]) && ($dir[1]=='F')) {
    $id_factura = $dir[2];
    header("Location: ".$dominio_www."contenido/paginas/procesos/pdfs/factura-1.php?id_factura=".$id_factura."&download=true");
    exit;
}

/* cupon enlace directo */
if (isset($dir[1]) && ($dir[1]=='CPN')) {
    $cod_cupon = $dir[2];
    $rqdcpn1 = query("SELECT id_cupon,id_participante FROM cursos_emisiones_cupones_infosicoes WHERE codigo='$cod_cupon' ORDER BY id DESC limit 1 ");
    if(num_rows($rqdcpn1)>0){
        $rqdcpn2 = fetch($rqdcpn1);
        $id_cupon = $rqdcpn2['id_cupon'];
        $id_participante = $rqdcpn2['id_participante'];
        header("Location: ".$dominio_www."contenido/paginas/procesos/pdfs/cupon-infosicoes.php?id_cupon=".$id_cupon."&id_participante=".$id_participante."&download=true");
        exit;
    }
}

/* ficharegistro enlace directo */
if (isset($dir[1]) && ($dir[1]=='FR')) {
    $id_proceso_registro = $dir[2];
    $url_redirect = $dominio.encrypt('registro-participantes-curso/' . $id_proceso_registro . '/pdf').'.impresion';
    $rqdcpn1 = query("SELECT id FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
    if(num_rows($rqdcpn1)>0){
        header("Location: ".$url_redirect);
        exit;
    }
}


/* default tags */
$titulo_pagina = $___nombre_del_sitio.' - Cursos y capacitaciones en Bolivia';
$descripcion_pagina = 'Cursos y capacitaciones en Bolivia';
$keywords_pagina = 'cursos,capacitaciones,bolivia';
$url_pagina = str_replace($dominio.'/', $dominio, $dominio . $_SERVER['REQUEST_URI']);
$image_pagina = $img_banner_principal;

/* metatags */
if (isset($get[1])) {

    /* matatag departamento */
    if ($get[1] == 'departamento') {
        $rqdd1 = query("SELECT * FROM departamentos WHERE titulo_identificador='" . $get[2] . "' LIMIT 1 ");
        $rqdd2 = fetch($rqdd1);
        $titulo_pagina = "Cursos y capacitaciones en " . $rqdd2['nombre'] . " - Bolivia";
        $descripcion_pagina = "Cursos y capacitaciones organizados en " . $rqdd2['nombre'] . " Bolivia, cursos en modalidad presencial y online.";
        $keywords_pagina = 'cursos,capacitaciones,' . $rqdd2['nombre'] . ',bolivia,seminarios,curso online';
        $image_pagina = $img_banner_principal;
    }
    
    /* matatag por ciudad (departamento) */
    /* listado-2 por departamento */
    if (isset($get[1]) && (substr($get[1], 0, 10) == 'cursos-en-')) {
        $aux_tcpc = str_replace('cursos-en-', '', $get[1]);
        $rqdd1 = query("SELECT * FROM departamentos WHERE titulo_identificador='$aux_tcpc' LIMIT 1 ");
        $rqdd2 = fetch($rqdd1);
        $titulo_pagina = "Cursos y capacitaciones en " . $rqdd2['nombre'] . " - Bolivia";
        $descripcion_pagina = "Cursos y capacitaciones organizados en " . $rqdd2['nombre'] . " Bolivia, cursos en modalidad presencial y online.";
        $keywords_pagina = 'cursos,capacitaciones,' . $rqdd2['nombre'] . ',bolivia,seminarios,curso online';
        $image_pagina = $img_banner_principal;
        if ($rqdd2['img_banner'] !== '') {
            $image_pagina = $dominio.'contenido/imagenes/departamentos/' . $rqdd2['img_banner'];
        }
    }

    /* matatag departamento (second) */
    if ($get[1] == 'cursos-por-ciudad') {
        $rqdd1 = query("SELECT * FROM departamentos WHERE titulo_identificador='" . $get[2] . "' LIMIT 1 ");
        $rqdd2 = fetch($rqdd1);
        $titulo_pagina = "Cursos y capacitaciones en " . $rqdd2['nombre'] . " - Bolivia";
        $descripcion_pagina = "Cursos y capacitaciones organizados en " . $rqdd2['nombre'] . " Bolivia, cursos en modalidad presencial y online.";
        $keywords_pagina = 'cursos,capacitaciones,' . strtolower($rqdd2['nombre']) . ',bolivia,seminarios,curso online';
        $image_pagina = $img_banner_principal;
        if ($rqdd2['img_banner'] !== '') {
            $image_pagina = $dominio.'contenido/imagenes/departamentos/' . $rqdd2['img_banner'];
        }
    }


    /* matatag curso */
    if ($get[1] == 'curso') {
        $rqc1 = query("SELECT titulo,imagen,contenido FROM cursos WHERE titulo_identificador='" . $get[2] . "' AND estado IN (0,1,2,3) ORDER BY FIELD(estado,1,2,0,3),id DESC limit 1 ");
        if (num_rows($rqc1) > 0) {
            $rqc2 = fetch($rqc1);
            $titulo_pagina = $rqc2['titulo'];
            $descripcion_pagina = str_replace('[NOMBRE-CURSO]',$rqc2['titulo'],substr(strip_tags($rqc2['contenido']), 0, 300)) . '...';
            $image_pagina = $dominio . 'contenido/imagenes/paginas/' . $rqc2['imagen'];
            $keywords_pagina = 'curso,' . $rqc2['titulo'] . ',bolivia,certificado';
        }
    }
    
    /* matatag grupo */
    if ($get[1] == 'grupo') {
        $rqc1 = query("SELECT titulo,imagen,contenido FROM cursos_agrupaciones WHERE titulo_identificador='" . $get[2] . "' AND estado IN (0,1,2,3) ORDER BY FIELD(estado,1,2,0,3),id DESC limit 1 ");
        if (num_rows($rqc1) > 0) {
            $rqc2 = fetch($rqc1);
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
        $image_pagina = $img_banner_cvirtuales;
    }
    
    /* matatag entidades-financieras */
    if ($get[1] == 'entidades-financieras') {
        $titulo_pagina = "Entidades financieras abiertas en el periodo de cuarentena en Bolivia";
        $descripcion_pagina = "Informacion de las entidades financieras abiertas en el periodo de cuarentena en Bolivia.";
        $keywords_pagina = 'cursos,entidad financiera,cuarentena,bolivia,seminarios,curso online';
        $image_pagina = $img_banner_principal;
    }

    /* matatag categoria empresarial */
    if (isset($get[2]) && ($get[1].'-'.$get[2] == 'categoria-empresarial')) {
        $titulo_pagina = "Curso SICOES proponentes";
        $image_pagina = $dominio.'contenido/imagenes/banners/banner-sicoes-rectangulo.jpg';
    }
    
}
?>
<!DOCTYPE html>
<html lang="es" class="fa-events-icons-ready js csstransitions csstransforms csstransforms3d">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <base href="<?php echo $dominio; ?>" target="_self"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="language" content="es"/>
        <meta name="robots" content="index,follow"/>
        <meta name="author" content="<?php echo $___nombre_del_sitio; ?>"/>
        <meta name="category" content="General"/>
        <meta name="rating" content="General"/>
        <meta name="keywords" content="<?php echo $keywords_pagina; ?>"/>
        <meta name="title" content="<?php echo $titulo_pagina; ?>" />
        <meta name="description" content="<?php echo $descripcion_pagina; ?>"/>

        <title><?php echo $titulo_pagina; ?></title>

        <meta property="og:url" content="<?php echo $url_pagina; ?>" />
        <meta property="og:title" content="<?php echo $titulo_pagina; ?>" />
        <meta property="og:description" content="<?php echo $descripcion_pagina; ?>" />
        <meta property="og:site_name" content="<?php echo $___nombre_del_sitio; ?>" />
        <meta property="og:image" content="<?php echo $image_pagina; ?>" />

        <meta name="twitter:card" content="summary"/>
        <meta name="twitter:description" content="<?php echo $descripcion_pagina; ?>"/>
        <meta name="twitter:title" content="<?php echo $titulo_pagina; ?>"/>
        <meta name="twitter:site" content="<?php echo $___nombre_del_sitio; ?>"/>
        <meta name="twitter:domain" content="<?php echo $dominio; ?>"/>
        <meta name="twitter:creator" content="<?php echo $___nombre_del_sitio; ?>"/>
        <link rel="image_src" href="<?php echo $image_pagina; ?>"/>


        <link href="<?php echo $dominio_www; ?>contenido/alt/bootstrap.css" rel="stylesheet"/>
        <link href="<?php echo $dominio_www; ?>contenido/alt/style.css" rel="stylesheet"/>
        <link href="<?php echo $dominio_www; ?>contenido/alt/responsive.v2.css" rel="stylesheet"/>
        <link href="<?php echo $dominio_www; ?>contenido/alt/layout-semiboxed.css" rel="stylesheet"/>
        <link href="<?php echo $dominio_www; ?>contenido/alt/skin-red.css" rel="stylesheet"/>
        <link href="<?php echo $dominio_www; ?>contenido/alt/yamm.css" rel="stylesheet"/>
        <link href="<?php echo $dominio_www; ?>contenido/alt/custom.css" rel="stylesheet"/>
        <link href="<?php echo $dominio_www; ?>contenido/alt/jquery.sidr.css" rel="stylesheet"/>
        <link rel="shortcut icon" type="image/png" href="<?php echo $dominio_www; ?>contenido/alt/favicon.png"/>

        <script src="<?php echo $dominio_www; ?>contenido/alt/c5f1172444.js.descarga"></script>
        <link href="<?php echo $dominio_www; ?>contenido/alt/c5f1172444.css" media="all" rel="stylesheet"/>

        <link href="<?php echo $dominio_www; ?>contenido/css/data-style9.css" media="all" rel="stylesheet"/>
        <link href="<?php echo $dominio_www; ?>contenido/css/individual.css" media="all" rel="stylesheet"/>

        <?php
        /* pixel_facebook */
        $rq1 = query("SELECT pixel FROM facebook_pixels WHERE sw_current='1' LIMIT 1 ");
        $rq2 = fetch($rq1);
        $pixel_facebook = $rq2['pixel'];
        echo $pixel_facebook;
        ?>
        
        <!--        newt scripts-->
        <script src="<?php echo $dominio_www; ?>contenido/alt/jquery.js.descarga"></script>
        <script src="<?php echo $dominio_www; ?>contenido/alt/bootstrap.js.descarga"></script>
        <script src="<?php echo $dominio_www; ?>contenido/alt/jquery.sidr.js.descarga"></script>
        <script src="<?php echo $dominio_www; ?>contenido/alt/plugins.js.descarga"></script>
        <script src="<?php echo $dominio_www; ?>contenido/alt/common.js"></script>
        <script src="<?php echo $dominio_www; ?>contenido/alt/validable.js.descarga"></script>
        <script src="<?php echo $dominio_www; ?>contenido/alt/footer.js.descarga"></script>
        <script src="<?php echo $dominio_www; ?>contenido/alt/index.js.descarga"></script>
        <script src="<?php echo $dominio_www; ?>contenido/alt/sidr.js.descarga"></script>
        
    </head>
    <body class="on">
        <div class="wrapbox">
            <div style='height:10px;'>&nbsp;</div>
            <nav class="navbar yamm navbar-fixed-top wowmenu tiny" role="navigation">
                <div class="container">
                    <div class="navbar-header">
                        <a class="navbar-brand logo-nav" href="<?php echo $dominio; ?>">
                            <img src="<?php echo $img_logotipo_principal; ?>" style="height:40px;" alt="logo">
                        </a>
                    </div>
                    <?php include_once 'contenido/paginas/items/item.menu_principal.php'; ?>
                </div>	
            </nav>
            <div id="mobile-header" class="hidden-md hidden-lg">
                <a id="simple-menu" class="menu-button" href="<?php echo $dominio; ?>#sidr">Toggle menu</a>
            </div>
            <div id="sidr" class="hidden-md hidden-lg sidr left" style="transition: left 0.1s ease-in-out;">
                <a class="sidr_close pull-right" href="<?php echo $dominio; ?>#" style="padding:5px;"><i class="icon-remove-sign text-contrast"></i></a>
                <?php include_once 'contenido/paginas/items/item.menu_mobile.php'; ?>
            </div>
