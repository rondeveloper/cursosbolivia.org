<?php
$sw_ipelc = false;
$id_usuario = usuario('id');
$rqdcip1 = query("SELECT * FROM cursos c INNER JOIN cursos_participantes p ON p.id_curso=c.id WHERE c.sw_ipelc='1' AND p.id_usuario='$id_usuario' ");
if(num_rows($rqdcip1)>0){
    $sw_ipelc = true;
}
?>
<ul id="nav" class="nav navbar-nav pull-left">
    <li class="active">
        <a href="<?php echo $dominio; ?>">Inicio</a>
    </li>
    <li class="">
        <a href="quienes-somos.html">Quienes Somos</a>
    </li>
    <li class="">
        <a href="cursos-virtuales.html">Cursos virtuales</a>
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Categorias
            <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <?php
            $rqc1 = query("SELECT titulo,titulo_identificador FROM cursos_categorias WHERE estado='1' AND (select count(1) from cursos where id_categoria=cursos_categorias.id and estado=1)>0  ORDER BY id ASC ");
            while ($rqc2 = fetch($rqc1)) {
                ?>
                <li><a href="categoria/<?php echo $rqc2['titulo_identificador']; ?>.html"><?php echo $rqc2['titulo']; ?></a></li>
                <?php
            }
            ?>
        </ul>
    </li>
<!--    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Departamentos
            <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <?php
            $rqc1 = query("SELECT nombre,titulo_identificador FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
            while ($rqc2 = fetch($rqc1)) {
                ?>
                <li>
                    <a href="<?php echo $rqc2['titulo_identificador']; ?>/" title="Cursos y capacitaciones en <?php echo $rqc2['nombre']; ?> - Bolivia">
                        <?php echo $rqc2['nombre']; ?>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>
    </li>-->
    <li class="">
        <a href="comunicados.html">Comunicados</a>
    </li>
    <?php
    if ((isset_usuario() || isset_docente()) && false) {
        ?>
        <li class="">
            <a href="foros.html">Foros</a>
        </li>
        <?php
    }
    ?>
    <li class="">
        <a href="contacto.html">Cont&aacute;cto</a>
    </li>
</ul>
<ul id="nav" class="nav navbar-nav pull-right">
    <li title="Notificaciones">
        <a class="dropdown-toggle" data-toggle="dropdown" ><i class="fa fa-bell"></i></a>
        <ul class="dropdown-menu">
            <?php
            $token_n_aux = '';
            $sw_notificacion_activada = false;
            if (isset($_COOKIE['token_nav'])) {
                $token_n_aux = $_COOKIE['token_nav'];
                $rqdl1 = query("SELECT estado FROM cursos_suscnav WHERE token='$token_n_aux' ORDER BY id DESC limit 1 ");
                if (num_rows($rqdl1) > 0) {
                    $rqdl2 = fetch($rqdl1);
                    if ($rqdl2['estado'] == '1') {
                        $sw_notificacion_activada = true;
                    }
                }
            }
            ?>
            <li id="ajaxbox-notification-state">
                <?php
                if ($sw_notificacion_activada) {
                    ?>
                    <a ><i class="fa fa-check"></i> NOTIFICACIONES ACTIVADAS</a>
                    <?php
                } else {
                    ?>
                    <a ><i class="fa fa-close"></i> NOTIFICACIONES DES-ACTIVADAS</a>
                    <?php
                }
                ?>
            </li>
            <li id="ajaxbox-notification-msm">
                <?php
                if (!$sw_notificacion_activada) {
                    ?>
                    <a style="cursor: pointer;" onclick="cambia_estado_notificaciones('<?php echo $token_n_aux; ?>', '1');"><i class="fa fa-refresh"></i> Activar notificaciones</a>
                    <?php
                } else {
                    ?>
                    <a style="cursor: pointer;" onclick="cambia_estado_notificaciones('<?php echo $token_n_aux; ?>', '0');"><i class="fa fa-refresh"></i> Desactivar notificaciones</a>
                    <?php
                }
                ?>
            </li>
            <li><a href="notificaciones.html"><i class="fa fa-list"></i> Notificaciones recibidas</a></li>
        </ul>
    </li>

    <?php
    if (isset_usuario()) {
        /*
          $id_usuario = usuario('id');
          $rqdu1 = query("SELECT imagen FROM cursos_usuarios WHERE id='$id_usuario' ");
          $rqdu2 = fetch($rqdu1);
          $url_imagen = 'contenido/imagenes/images/user.png';
          if(file_exists('contenido/imagenes/usuarios/'.$rqdu2['imagen'])){
          $url_imagen = 'usuarios/'.$rqdu2['imagen'].'.size=2.img';
          }
         */
        ?>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <img src="contenido/imagenes/images/user.png" style="height: 20px;"> 
                CUENTA
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu pull-right">
                <li><a href="mi-cuenta.html"><i class="fa fa-user"></i> MI CUENTA</a></li>
                <li><a href="mi-cuenta-mis-cursos.html"><i class="fa fa-laptop"></i> MIS CURSOS</a></li>
                <li><a href="mi-cuenta-mis-certificados.html"><i class="fa fa-certificate"></i> MIS CERTIFICADOS</a></li>
                <?php if($sw_ipelc){ ?>
                <li><a href="mi-cuenta-documentacion.html"><i class="fa fa-file-o"></i> SUBIR DOCUMENTOS IPELC</a></li>
                <li><a href="mi-cuenta.envio-certificacion-ipelc.html"><i class="fa fa-send"></i> ENV&Iacute;O DE CERTIFICACI&Oacute;N IPELC</a></li>
                <?php } ?>
                <li><a href="mi-cuenta-tareas.html"><i class="fa fa-list-alt"></i> TAREAS</a></li>
                <li><a href="mi-cuenta-cursos-recomendados.html"><i class="fa fa-list"></i> CURSOS RECOMENDADOS</a></li>
                <li><a href="mi-cuenta-grupos-whatsapp.html"><i class="fa fa-comments"></i> WHATSAPP</a></li>
                <li><a href="mi-cuenta-fanpage-facebook.html"><i class="fa fa-bullhorn"></i> FACEBOOK</a></li>
                <li><a href="mi-cuenta-preferencias.html"><i class="fa fa-flag"></i> PREFERENCIAS</a></li>
                <li><a href="mi-cuenta-configuracion.html"><i class="fa fa-cogs"></i> CONFIGURACI&Oacute;N</a></li>
                <li><a href="mi-cuenta-vincular.html"><i class="fa fa-refresh"></i> VINCULAR CUENTAS</a></li>
                <li><a href="contenido/paginas/procesos/logout.php"><i class="fa fa-close"></i> CERRAR SESI&Oacute;N</a></li>
            </ul>
        </li>
        <?php
    } elseif (isset_docente()) {
        ?>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <img src="contenido/imagenes/images/user.png" style="height: 20px;"> 
                CUENTA DOCENTE
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu pull-right">
                <li><a href="acount-docente.dashboard.html"><i class="fa fa-dashboard"></i> DASHBOARD</a></li>
                <li><a href="acount-docente.datos.html"><i class="fa fa-user"></i> MI CUENTA</a></li>
                <li><a href="acount-docente.cursos.html"><i class="fa fa-laptop"></i> MIS CURSOS</a></li>
                <li><a href="contenido/paginas/procesos/logout.php"><i class="fa fa-close"></i> CERRAR SESI&Oacute;N</a></li>
            </ul>
        </li>
        <?php
    } else {
        ?>
        <li class="active">
            <a href="<?php echo $dominio_plataforma; ?>registro-de-usuarios.html" class="top-boton-user">REGISTRATE</a>
        </li>
        <li class="active">
            <a href="<?php echo $dominio_plataforma; ?>" class="top-boton-user">INGRESA</a>
        </li>
        <?php
    }
    ?>
</ul>
