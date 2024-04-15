<?php
$sw_ipelc = false;
$id_usuario = usuario('id');
$rqdcip1 = query("SELECT * FROM cursos c INNER JOIN cursos_participantes p ON p.id_curso=c.id WHERE c.sw_ipelc='1' AND p.id_usuario='$id_usuario' ");
if(num_rows($rqdcip1)>0){
    $sw_ipelc = true;
}
?>
<ul id="nav" class="nav navbar-nav pull-left">
    <!-- <li class="active">
        <a href="<?php echo $dominio; ?>">Inicio</a>
    </li> -->
</ul>
<ul id="nav" class="nav navbar-nav pull-right">
    <?php
    if (isset_usuario()) {
        $id_usuario = usuario('id');
        $sw_examen_2t = false;
        $rqdvest1 = query("SELECT id FROM segundos_turnos WHERE id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
        if(num_rows($rqdvest1)>0){
            $sw_examen_2t = true;
        }
        $sw_doc_compromiso_finalizacion = false;
        $rqdvcfn1 = query("SELECT id FROM compromisos_finalizacion WHERE id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
        if(num_rows($rqdvcfn1)>0){
            $sw_doc_compromiso_finalizacion = true;
        }
        ?>
        <li class="dropdown active">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <img src="<?php echo $dominio_www; ?>contenido/imagenes/images/user.png" style="height: 20px;"> 
                CUENTA
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu pull-right">
                <li><a href="mi-cuenta.html"><i class="fa fa-user"></i> MI CUENTA</a></li>
                <li><a href="mi-cuenta.mis-cursos.html"><i class="fa fa-laptop"></i> MIS CURSOS</a></li>
                <?php if($sw_examen_2t){ ?> 
                    <li><a href="mi-cuenta.examen-segundo-turno.html"><i class="fa fa-copy"></i> EXAMEN DE 2DO TURNO</a></li>
                <?php } ?>
                <?php if($sw_doc_compromiso_finalizacion){ ?>
                    <li><a href="mi-cuenta.documentos.html"><i class="fa fa-copy"></i> DOCUMENTOS</a></li>
                <?php } ?>
                <li><a href="mi-cuenta.mis-certificados.html"><i class="fa fa-certificate"></i> MIS CERTIFICADOS</a></li>
                <?php if($sw_ipelc){ ?>
                    <li><a href="mi-cuenta.documentacion.html"><i class="fa fa-file-o"></i> SUBIR DOCUMENTOS IPELC</a></li>
                    <li><a href="mi-cuenta.envio-certificacion-ipelc.html"><i class="fa fa-send"></i> ENV&Iacute;O DE CERTIFICACI&Oacute;N IPELC</a></li>
                    <li><a href="mi-cuenta.envio-sugerencia-queja-reclamo.html"><i class="fa fa-flag"></i> SUGERENCIAS, QUEJAS Y RECLAMOS </a></li>
                <?php } ?>
                <li><a href="mi-cuenta.tareas.html"><i class="fa fa-list-alt"></i> TAREAS</a></li>
                <li><a href="mi-cuenta.cursos-recomendados.html"><i class="fa fa-list"></i> CURSOS RECOMENDADOS</a></li>
                <li><a href="mi-cuenta.grupos-whatsapp.html"><i class="fa fa-comments"></i> WHATSAPP</a></li>
                <li><a href="mi-cuenta.fanpage-facebook.html"><i class="fa fa-bullhorn"></i> FACEBOOK</a></li>
                <li><a href="mi-cuenta.preferencias.html"><i class="fa fa-flag"></i> PREFERENCIAS</a></li>
                <li><a href="mi-cuenta.configuracion.html"><i class="fa fa-cogs"></i> CONFIGURACI&Oacute;N</a></li>
                <li><a href="mi-cuenta.vincular.html"><i class="fa fa-refresh"></i> VINCULAR CUENTAS</a></li>
                <li><a onclick="cerrar_sesion();" style="cursor:pointer;"><i class="fa fa-close"></i> CERRAR SESI&Oacute;N</a></li>
            </ul>
        </li>
        <?php
    } elseif (isset_docente()) {
        ?>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <img src="<?php echo $dominio_www; ?>contenido/imagenes/images/user.png" style="height: 20px;"> 
                CUENTA DOCENTE
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu pull-right">
                <li><a href="acount-docente.dashboard.html"><i class="fa fa-dashboard"></i> DASHBOARD</a></li>
                <li><a href="acount-docente.datos.html"><i class="fa fa-user"></i> MI CUENTA</a></li>
                <li><a href="acount-docente.cursos.html"><i class="fa fa-laptop"></i> MIS CURSOS</a></li>
                <li><a onclick="cerrar_sesion();" style="cursor:pointer;"><i class="fa fa-close"></i> CERRAR SESI&Oacute;N</a></li>
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
