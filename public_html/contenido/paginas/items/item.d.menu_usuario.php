<style>
    .menu-usuario{
        color:#FFF;
    }
    .menu-usuario li a{
        color: #1b5b77;
    }
    .menu-usuario li{
        background: #dddddd;
        margin: 5px 0px;
        padding: 8px 2px 8px 12px;
        border-radius: 5px;
        font-size: 9pt;
    }
    .menu-usuario h4{
        background: #b3b3b3;
        color: #FFF;
        padding: 5px 10px;
        margin: 5px 0px;
        font-size: 10pt;
        text-align: center;
    }
</style>
<div class="menu-usuario">
    <h4>ADMINISTRACI&Oacute;N</h4>
    <ul class="menu-usuario">
        <li><a href="mi-cuenta.html"><i class="fa fa-user"></i> MI CUENTA</a></li>
        <li><a href="mi-cuenta-mis-cursos.html"><i class="fa fa-laptop"></i> MIS CURSOS</a></li>
        <li><a href="mi-cuenta-mis-certificados.html"><i class="fa fa-certificate"></i> MIS CERTIFICADOS</a></li>
        <?php if($sw_ipelc){ ?>
            <li><a href="mi-cuenta-documentacion.html"><i class="fa fa-file-o"></i> DOCUMENTOS IPELC</a></li>
            <li><a href="mi-cuenta.envio-certificacion-ipelc.html"><i class="fa fa-send"></i> ENV&Iacute;O DE CERTIFICADO IPELC</a></li>
        <?php } ?>
        <li><a href="mi-cuenta-tareas.html"><i class="fa fa-list-alt"></i> TAREAS</a></li>
        <li><a href="mi-cuenta-cursos-recomendados.html"><i class="fa fa-list"></i> CURSOS RECOMENDADOS</a></li>
        <li><a href="mi-cuenta-grupos-whatsapp.html"><i class="fa fa-comments"></i> WHATSAPP</a></li>
        <li><a href="mi-cuenta-fanpage-facebook.html"><i class="fa fa-bullhorn"></i> FACEBOOK</a></li>
        <li><a href="mi-cuenta-preferencias.html"><i class="fa fa-flag"></i> PREFERENCIAS</a></li>
        <li><a href="mi-cuenta-configuracion.html"><i class="fa fa-cogs"></i> CONFIGURACI&Oacute;N</a></li>
<!--        <li><a href="mi-cuenta-vincular.html"><i class="fa fa-refresh"></i> VINCULAR CUENTAS</a></li>-->
        <li><a href="contenido/paginas/procesos/logout.php"><i class="fa fa-close"></i> CERRAR SESI&Oacute;N</a></li>
    </ul>
</div>
