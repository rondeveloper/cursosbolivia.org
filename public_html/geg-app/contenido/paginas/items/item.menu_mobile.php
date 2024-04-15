<ul>
    <li><a href="<?php echo $dominio; ?>"><i class="fa fa-home"></i> Inicio</a></li>
    <li class="">
        <a href="cursos.html">Cursos</a>
    </li>
    <li class="">
        <a href="https://www.gegbolivia.org/" target="_blank">Quienes Somos</a>
    </li>
    <li class="">
        <a href="https://programa.gegbolivia.org/" target="_blank">Programa EDUCADOR DIGITAL</a>
    </li>
    <li class="groupheader">
        <a href="#" class="disabled_menu"><i class="fa fa-group"></i> Cuenta</a>
        <span class="icon-chevron-right text-contrast headerbutton"></span>
        <ul>
            <?php
            if (!isset_usuario()) {
                ?>
                <li><a href="registro.html">Registro de usuarios</a></li>
                <li><a href="ingreso-de-usuarios.html">Ingreso de usuarios</a></li>
                <?php
            } else {
                ?>
                <li><a href="acount.datos.html"><i class="fa fa-user"></i> MI CUENTA</a></li>
                <li><a href="acount.certificados.html"><i class="fa fa-certificate"></i> MIS CERTIFICADOS</a></li>
                <li><a href="acount.cursos.html"><i class="fa fa-laptop"></i> MIS CURSOS</a></li>
                <li><a href="acount.recomendados.html"><i class="fa fa-list"></i> CURSOS RECOMENDADOS</a></li>
                <li><a href="acount.configuracion.html"><i class="fa fa-cogs"></i> CONFIGURACI&Oacute;N</a></li>
                <li><a href="contenido/paginas/procesos/logout.php"><i class="fa fa-close"></i> CERRAR SESI&Oacute;N</a></li>
                <?php
             }
            ?>
        </ul>
    </li>
</ul>
