<ul class="nav navbar-nav pull-left hidden-xs hidden-sm">
    <li class="active" style="padding: 10px 0px;padding-right: 70px;">
        <span style="font-size: 15px;color: gray;">Google Educator Groups Bolivia</span>
    </li>
</ul>
<ul id="nav" class="nav navbar-nav pull-right">

    <?php
    if (isset_usuario()) {
        ?>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <img src="contenido/imagenes/images/user.png" style="height: 20px;"> 
                CUENTA
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu pull-right">
                <li><a href="acount.datos.html"><i class="fa fa-user"></i> MI CUENTA</a></li>
                <li><a href="acount.certificados.html"><i class="fa fa-certificate"></i> MIS CERTIFICADOS</a></li>
                <li><a href="acount.cursos.html"><i class="fa fa-laptop"></i> MIS CURSOS</a></li>
                <li><a href="acount.recomendados.html"><i class="fa fa-list"></i> CURSOS RECOMENDADOS</a></li>
                <li><a href="acount.configuracion.html"><i class="fa fa-cogs"></i> CONFIGURACI&Oacute;N</a></li>
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
                <li><a href="mi-cuenta-docente.html">MI CUENTA</a></li>
                <li><a href="contenido/paginas/procesos/logout.php">CERRAR SESI&Oacute;N</a></li>
            </ul>
        </li>
        <?php
    } else {
        ?>
        <li class="active">
            <a href="registro.html" style="background: red;
               background: red;
               border-radius: 5px;
               padding: 3px 20px;
               margin: 4px;
               border-bottom: 1px solid #FFF;">REGISTRATE</a>
        </li>
        <li class="active">
            <a href="ingreso-de-usuarios.html" style="background: red;
               background: red;
               border-radius: 5px;
               padding: 3px 20px;
               margin: 4px;
               border-bottom: 1px solid #FFF;">INGRESA</a>
        </li>
        <?php
    }
    ?>
</ul>
<ul id="nav" class="nav navbar-nav pull-right">
    <li class="">
        <a href="cursos.html">Inicio</a>
    </li>
<!--    <li class="">
        <a href="https://www.gegbolivia.org/" target="_blank">Quienes Somos</a>
    </li>-->
    <li class="">
        <a href="https://programa.gegbolivia.org/" target="_blank">Programa EDUCADOR DIGITAL</a>
    </li>
</ul>
