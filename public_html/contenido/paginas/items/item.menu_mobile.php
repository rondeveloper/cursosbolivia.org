<ul>
    <li><a href="<?php echo $dominio; ?>"><i class="fa fa-home"></i> Inicio</a></li>
    <li>
        <a href="quienes-somos.html"><i class="fa fa-tv"></i> Quienes Somos</a>
    </li>
    <li>
        <a href="cursos-virtuales.html"><i class="fa fa-street-view"></i> Cursos virtuales</a>
    </li>
    <li class="groupheader">
        <a href="#" class="disabled_menu"><i class="fa fa-list"></i> Categorias</a>
        <span class="icon-chevron-right text-contrast headerbutton"></span>
        <ul>
            <?php
            $rqc1 = query("SELECT titulo,titulo_identificador FROM cursos_categorias WHERE estado='1' AND (select count(1) from cursos where id_categoria=cursos_categorias.id and estado=1)>0 ORDER BY id ASC ");
            while ($rqc2 = fetch($rqc1)) {
                ?>
                <li><a href="categoria/<?php echo $rqc2['titulo_identificador']; ?>.html"><?php echo $rqc2['titulo']; ?></a></li>
                <?php
            }
            ?>
        </ul>
    </li>
<!--    <li class="groupheader">
        <a href="#" class="disabled_menu"><i class="fa fa-map-o"></i> Departamentos</a>
        <span class="icon-chevron-right text-contrast headerbutton"></span>
        <ul>
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
        <a href="comunicados.html"><i class="fa fa-map-o"></i> Comunicados</a>
    </li>
    <?php
    if ((isset_usuario() || isset_docente()) && false) {
        ?>
        <li class="">
            <a href="foros.html"><i class="fa fa-map-o"></i> Foros</a>
        </li>
        <?php
    }
    ?>
    <li>
        <a href="contacto.html"><i class="fa fa-comments"></i> Cont&aacute;cto</a>
    </li>
    <li class="groupheader">
        <a href="#" class="disabled_menu"><i class="fa fa-group"></i> Cuenta</a>
        <span class="icon-chevron-right text-contrast headerbutton"></span>
        <ul>
            <?php
            if (isset_usuario()) {
                ?>
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
                <?php
            } elseif (isset_docente()) {
                ?>
                <li><a href="acount-docente.dashboard.html"><i class="fa fa-dashboard"></i> DASHBOARD</a></li>
                <li><a href="acount-docente.datos.html"><i class="fa fa-user"></i> MI CUENTA</a></li>
                <li><a href="acount-docente.cursos.html"><i class="fa fa-laptop"></i> MIS CURSOS</a></li>
                <li><a href="contenido/paginas/procesos/logout.php"><i class="fa fa-close"></i> CERRAR SESI&Oacute;N</a></li>
                <?php
            } else {
                ?>
                <li><a href="<?php echo $dominio_plataforma; ?>registro-de-usuarios.html">Registro de usuarios</a></li>
                <li><a href="<?php echo $dominio_plataforma; ?>">Ingreso de usuarios</a></li>
                <?php
            }
            ?>
        </ul>
    </li>
</ul>
