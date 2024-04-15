<?php
require_once "contenido/librerias/classes/class.codigo-control-v7.php";

use clases\CodigoControlV7;

/* mensaje */

$mensaje = '';

/* data */
$ref = $get[2];
$id_ref = $get[3];
$hash = $get[4];

/* verificacion */
if (md5(md5('auteditco104511'.$id_ref)) != $hash) {
    echo "<script>alert('DENEGADO');location.href='$dominio';</script>";
    exit;
}


if($ref=='1'){
    /* participante */
    $rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_ref' ORDER BY id DESC limit 1 ");
    $participante = fetch($rqdp1);
    $nombre = $participante['nombres'] . ' ' . $participante['apellidos'];
    $correo = $participante['correo'];
}else{
    /* usuarios */
    $rqdp1 = query("SELECT * FROM cursos_usuarios WHERE id='$id_ref' ORDER BY id DESC limit 1 ");
    $participante = fetch($rqdp1);
    $nombre = $participante['nombres'] . ' ' . $participante['apellidos'];
    $correo = $participante['email'];
}


/* corregir-correo */
if (isset_post('corregir-correo')) {

    $correo = post('correo');
    $sw_corregido = false;

    if($ref=='1'){
        query("UPDATE cursos_participantes SET correo='$correo',sw_notif='1' WHERE id='$id_ref' ORDER BY id DESC limit 1 ");
        logcursos('Edicion de correo por enlace de AUTO-CORRECCCION', 'partipante-edicion', 'participante', $id_ref);
    }else{
        query("UPDATE cursos_usuarios SET email='$correo',sw_notif='1' WHERE id='$id_ref' ORDER BY id DESC limit 1 ");
        logcursos('Edicion de correo por enlace de AUTO-CORRECCCION', 'usuario-edicion', 'usuario', $id_ref);
    }

    $sw_corregido = true;

    $mensaje .= '<br><div class="alert alert-success">
        <strong>EXITO</strong> registro actualizado exitosamente.
    </div>';
}

?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <?php echo $mensaje; ?>
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">

                <div class="TituloArea">
                    <h3>CORRECCI&Oacute;N DE CORREO ELECTR&Oacute;NICO</h3>
                </div>

                <div class="Titulo_texto1">
                    <?php if (!$sw_corregido) { ?>
                        <div style="background: #f3fbff;padding: 20px;font-size: 12pt;text-align: justify;border: 1px solid #e0e0e0;">
                            Hola <?php echo $nombre; ?>
                            <br>
                            <br>
                            Desde aqu&iacute; puedes corregir tu correo electr&oacute;nico y asi recibir informaci&oacute;n importante:
                            <br>
                            <br>

                            <div style="text-align:center;padding: 20px 0px;">
                                <form action="" method="post">
                                    <table class="table table-bordered" style="background: #FFF;">
                                        <tr>
                                            <td style="padding: 15px;"><b>Nombre:</b></td>
                                            <td style="padding: 15px;"><?php echo $nombre; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 15px;"><b>Correo electr&oacute;nico:</b></td>
                                            <td><input type="email" value="<?php echo $correo; ?>" class="form-control" name="correo" placeholder="Ingresa tu correo electr&oacute;nico.." required=""/></td>
                                        </tr>
                                    </table>
                                    <br>
                                    <input type="submit" name="corregir-correo" value="ACTUALIZAR CORREO" class="btn btn-success" style="width: auto;border-radius: 5px;padding: 10px 20px;">
                                </form>
                            </div>
                        </div>
                    <?php 
                } else { 
                    ?>
                        <div style="background: #ffefd3;padding: 20px;font-size: 12pt;text-align: justify;">
                            EL CORREO FUE MODIFICADO CORRECTAMENTE
                        </div>
                    <?php } ?>
                </div>
                <hr />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
            </div>
        </div>
    </section>
</div>