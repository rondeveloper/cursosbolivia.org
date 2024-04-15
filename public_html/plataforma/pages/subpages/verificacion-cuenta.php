<?php
/* mensaje */
$mensaje = '';

/* cuenta */
$id_usuario = $get[2];


/* verificacion */
$rqvc1 = query("SELECT estado FROM cursos_usuarios WHERE id='$id_usuario' LIMIT 1 ");
if(num_rows($rqvc1)==0){
    $mensaje .= '<div class="alert alert-success">
  <strong>Error!</strong> no se encontro la cuenta.
</div>';
}

$rqvc2 = fetch($rqvc1);
if($rqvc2['estado']=='1'){
    $mensaje .= '<div class="alert alert-warning">
  <strong>Aviso!</strong> su cuenta ya fue autentificada anteriormente.
</div>';
}

if($rqvc2['estado']=='2'){
    query("UPDATE cursos_usuarios SET estado='1' WHERE id='$id_usuario' ");
    usuarioSet('id',$id_usuario);
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> el proceso de autentificaci&oacute;n se ha realizado correctamente.
</div>';
}

?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-8">
                <div class="TituloArea">
                    <h3>VERIFICACI&Oacute;N DE CUENTA</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        Bienvenido a la plataforma <?php echo $___nombre_del_sitio; ?>
                    </p>
                </div>

                <?php echo $mensaje; ?>
                
                <hr/>
                <a href="<?php echo $dominio_plataforma; ?>" class="btn btn-warning">CONTINUAR</a>

                <br />
                <br />


            </div>
            <div class="col-md-4">
                <style>
                    .wtt-aux{
                        padding-left: 50px;
                    }
                </style>
                <div style="padding: 0px 25px;margin-bottom: 100px;">
                    <h4>BENEFICIOS DE TENER UNA CUENTA</h4>
                    <hr/>
                    <div class="wtt-aux whyus_icon1"><a class="whyus_link ">Registro a los cursos publicados en <?php echo $___nombre_del_sitio; ?></a></div>
                    <div class="wtt-aux whyus_icon2"><a class="whyus_link ">Gestion de certificados de cursos</a></div>
                    <div class="wtt-aux whyus_icon3"><a class="whyus_link ">Participaci&oacute;n en foros y consultas</a></div>
                    <div class="wtt-aux whyus_icon4"><a class="whyus_link ">Acceso a material didactico</a></div>
                    <div class="wtt-aux whyus_icon5"><a class="whyus_link ">Brinda cursos con nuestra plataforma</a></div>
                    <div class="wtt-aux whyus_icon6"><a class="whyus_link ">Alianzas coorporativas</a></div>
                    <div class="wtt-aux whyus_icon7"><a class="whyus_link ">Configuraci&oacute;n de cuentas</a></div>
                    <div class="wtt-aux whyus_icon8"><a class="whyus_link ">Directorio de profesionales</a></div>
                </div>
            </div>
        </div>

    </section>
</div>                     


