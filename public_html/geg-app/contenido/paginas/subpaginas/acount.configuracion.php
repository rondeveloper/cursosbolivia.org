<?php
/* mensaje */
$mensaje = '';

/* usuario */
$id_usuario = usuario('id');

/* verif usuario */
if (!isset_usuario()) {
    echo "<br/><br/><br/>Acceso denegado!";
    exit;
}

/* actualizar-datos */
if (isset_post('actualizar-datos')) {
    $nombre_usuario = post('nombre');
    $celular_usuario = post('celular');
    $email_usuario = post('correo');
    //query("UPDATE usuarios SET nombre='$nombre_usuario',apellidos='$apellidos_usuario',celular='$celular_usuario',email='$email_usuario' WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
    query("UPDATE usuarios SET nombre='$nombre_usuario' WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
    
    /* AUX update */
    //query("UPDATE emisiones_certificados SET receptor_de_certificado='$nombre_usuario' WHERE id_usuario='$id_usuario' ORDER BY id DESC limit 10 ");
    /* AUX END update */
    
    $mensaje = '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro se modific&oacute; correctamente.
</div>
';
}

/* data usuario */
$rqdu1 = query("SELECT * FROM usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
$rqdu2 = mysql_fetch_array($rqdu1);
$nombre_usuario = $rqdu2['nombre'];
$celular_usuario = $rqdu2['celular'];
$email_usuario = $rqdu2['email'];
if ($email_usuario == 'no-email-data') {
    $email_usuario = '';
}
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #f6f5f5;">
            <div class="col-md-2 hidden-xs">
                <?php
                include_once 'contenido/paginas/items/item.d.menu_usuario.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">
                
                <?php echo $mensaje; ?>
                
                <div class="TituloArea">
                    <h3>CONFIGURACI&Oacute;N</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        En esta secci&oacute;n puedes actualizar los datos principales de tu cuenta.
                    </p>
                    <hr>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <form action="" method="post">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <td><b>Nombre:</b></td>
                                        <td><input type="text" name="nombre" value="<?php echo $nombre_usuario; ?>" placeholder="Ingresa tu nombre completo..." class="form-control" onkeyup="this.value = this.value.toUpperCase();"/></td>
                                    </tr>
                                    <tr>
                                        <td><b>Celular:</b></td>
                                        <td><input type="number" name="celular" value="<?php echo $celular_usuario; ?>" placeholder="Ingresa tu n&uacute;mero de celular..." disabled="" class="form-control"/></td>
                                    </tr>
                                    <tr>
                                        <td><b>Correo electr&oacute;nico:</b></td>
                                        <td>
                                            <input type="email" name="correo" value="<?php echo $email_usuario; ?>" placeholder="Ingresa tu correo electr&oacute;nico..." disabled="" class="form-control"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <br/>
                                            <input type="submit" name="actualizar-datos" value="ACTUALIZAR DATOS" class="btn btn-info btn-block"/>
                                            <br/>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>



                <hr/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>

                <hr/>




            </div>

        </div>

    </section>
</div>                     



<?php

function fecha_aux($dat) {
    $meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $d1 = date("d", strtotime($dat));
    $d2 = $meses[(int) (date("m", strtotime($dat)))];
    $d3 = date("Y", strtotime($dat));
    return "$d1 de $d2 de $d3";
}
?>