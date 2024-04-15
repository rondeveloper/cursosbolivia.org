<?php
/* mensaje */
$mensaje = '';

/* token registrado */
$sw_token_registered = false;
$token = '';
if (isset($_COOKIE['token_nav'])) {
    $sw_token_registered = true;
    $token = $_COOKIE['token_nav'];
}


$rqdct1 = query("SELECT * FROM cursos_suscnav WHERE token='$token' LIMIT 1 ");
$rqdct2 = fetch($rqdct1);
$id_tokensusc = $rqdct2['id'];

if (isset_post('actualizar-departamento')) {
    $id_departamento = post('id_departamento');
    query("UPDATE cursos_suscnav SET id_departamento='$id_departamento' WHERE id='$id_tokensusc' LIMIT 1 ");
    $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> registro modificado exitosamente.
</div>';
}
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row" style="min-height:700px;">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="TituloArea">
                    <h3>NOTIFICACIONES</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        En esta secci&oacute;n puedes configurar y revisar el estado de notificaci&oacute;nes.
                    </p>
                </div>

                <?php echo $mensaje; ?>

                <br/>

                <div class="boxForm ajusta_form_contacto" style="background: #f7f7f7;border: 1px solid #5bc0de;box-shadow: 1px 1px 3px #d0d0d0;">
                    <?php
                    if ($sw_token_registered) {
                        $rqdct1 = query("SELECT * FROM cursos_suscnav WHERE token='$token' LIMIT 1 ");
                        $rqdct2 = fetch($rqdct1);
                        ?>
                        <table class="table table-striped table-bordered" style="background: white;">
                            <tr>
                                <td>Estado:</td>
                                <td>
                                    <?php
                                    switch ($rqdct2['estado']) {
                                        case '0':
                                            echo "<b class='text-danger'>DES-ACTIVADO</b>";
                                            break;
                                        case '1':
                                            echo "<b class='text-success'>ACTIVADO</b>";
                                            break;
                                        case '2':
                                            echo "<b class='text-info'>DES-HABILITADO</b>";
                                            break;
                                        case '3':
                                            echo "<b class='text-warning'>SOBRE ASIGNADO</b>";
                                            break;
                                        default:
                                            echo "<b class='text-default'>SIN DATO</b>";
                                            break;
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>ID:</td>
                                <td>
                                    <?php echo $id_tokensusc; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Registro:</td>
                                <td>
                                    <?php
                                    echo date("d / M / Y", strtotime($rqdct2['fecha_registro']));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Asociado a:</td>
                                <td>
                                    <?php
                                    if ($rqdct2['id_usuario'] == '0') {
                                        echo "Sin cuenta asociada";
                                    } else {
                                        $rqdu1 = query("SELECT nombres,apellidos FROM cursos_usuarios WHERE id='" . $rqdct2['id_usuario'] . "' LIMIT 1 ");
                                        $rqdu2 = fetch($rqdu1);
                                        echo trim($rqdu2['nombres'] . ' ' . $rqdu2['apellidos']);
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>

                        <hr/>

                        <form action="" method="post">
                            <table class="table table-striped table-bordered" style="background: white;">                           
                                <tr>
                                    <td colspan="2">
                                        Se reciben notificaciones del departamento de:
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-control" name="id_departamento">
                                            <option value="0">Todos los departamentos</option>
                                            <?php
                                            $rqdd1 = query("SELECT nombre,id FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                                            while ($rqdd2 = fetch($rqdd1)) {
                                                $txt_selec = '';
                                                if ($rqdct2['id_departamento'] == $rqdd2['id']) {
                                                    $txt_selec = ' selected="selected" ';
                                                }
                                                ?>
                                                <option value="<?php echo $rqdd2['id']; ?>" <?php echo $txt_selec; ?>><?php echo $rqdd2['nombre']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-success btn-block" name="actualizar-departamento" value="ACTUALIZAR"/>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <?php
                    } else {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <b>LAS NOTIFICACIONES NO ESTAN ACTIVADAS</b>
                                <br/>
                                <br/>
                                <p>
                                    Para poder recibir notificaciones primero debes permitir al navegador recibir notificaciones de <b><?php echo $___nombre_del_sitio; ?></b>, este proceso se produce al ingreso inicial 
                                    a la p&aacute;gina, en caso de no haber habilitado el premiso en ese momento, se debe habilitar el permiso manualmente como se muestra en la siguiente imagen:
                                </p>
                                <b>Navegador Chrome:</b>
                                <br/>
                                <div class="text-center">
                                    <img src="contenido/imagenes/images/enable-notification-2.jpg" style="max-width:100%;"/>
                                </div>
                                <br/>
                                <b>Navegador Firefox:</b>
                                <br/>
                                <div class="text-center">
                                    <img src="contenido/imagenes/images/enable-notification-1.jpg" style="max-width:100%;"/>
                                </div>
                                <br/>
                                <p>
                                    Luego de habilitar el permiso debes hacer click en el siguiente boton y actualizar la p&aacute;gina. Posteriormente se te notificar&aacute; inmediatamente cuando 
                                    se programe una fecha para el curso que estas buscando.
                                </p>
                                <br/>
                                <br/>
                                <b class="btn btn-info btn-xs btn-block" onclick="location.reload();">Activar notificaciones</b>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <?php
                if ($sw_token_registered) {
                    ?>
                    <div style="">
                        <hr/>
                        <h4>NOTIFICACIONES RECIBIDAS</h4>
                        <table class="table table-striped table-bordered" style="background: white;">
                            <tr>
                                <th>
                                    FECHA
                                </th>
                                <th>
                                    NOTIFICACI&Oacute;N
                                </th>
                            </tr>
                            <?php
                            $rqdn1 = query("SELECT c.titulo,n.fecha_envio FROM cursos_rel_notifsuscpush n INNER JOIN cursos c ON n.id_curso=c.id WHERE n.id_tokensusc='" . $rqdct2['id'] . "' ORDER BY n.id DESC ");
                            while ($rqdn2 = fetch($rqdn1)) {
                                ?>
                                <tr>
                                    <td>
                                        <?php
                                        if ($rqdn2['fecha_envio'] !== '0000-00-00') {
                                            echo date("d / M / Y", strtotime($rqdn2['fecha_envio']));
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $rqdn2['titulo']; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                    <?php
                }
                ?>

                <br />
                <br />

            </div>
            <div class="col-md-2">
                <style>
                    .wtt-aux{
                        padding-left: 50px;
                    }
                </style>
                <div class="">
                </div>
            </div>
        </div>

    </section>
</div>                     
