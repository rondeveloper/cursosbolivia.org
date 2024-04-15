<?php

/* mensaje */
$mensaje = "";

/* depurar-correos */
if (isset_post('depurar-correos')) {
    $busc = array(' ', ',', ';', '\r\n', '\r', '\n');
    $correos = str_replace($busc, '___separator__', trim(post('correos')));
    $arr_correos = explode('___separator__', $correos);
    $id_administrador = administrador('id');
    $cnt = 0;
    foreach ($arr_correos as $correo) {
        if (trim($correo) != '') {
            $cnt++;
            $sw_founded = false;

            /* busc en participantes */
            $rqbdp1 = query("SELECT id FROM cursos_participantes WHERE correo LIKE '$correo' ");
            while ($rqbdp2 = fetch($rqbdp1)) {
                $id_participante = $rqbdp2['id'];
                query("INSERT INTO correos_depurados(correo, ref, id_ref, id_administrador, fecha) VALUES ('$correo','1','$id_participante','$id_administrador',NOW())");
                $sw_founded = true;
                query("UPDATE cursos_participantes SET sw_notif='0' WHERE id='$id_participante' ");
            }

            /* busc en usuario */
            $rqbdu1 = query("SELECT id FROM cursos_usuarios WHERE email LIKE '$correo' ");
            while ($rqbdu2 = fetch($rqbdu1)) {
                $id_usuario = $rqbdu2['id'];
                query("INSERT INTO correos_depurados(correo, ref, id_ref, id_administrador, fecha) VALUES ('$correo','2','$id_usuario','$id_administrador',NOW())");
                $sw_founded = true;
                query("UPDATE cursos_usuarios SET sw_notif='0' WHERE id='$id_usuario' ");
            }

            if (!$sw_founded) {
                query("INSERT INTO correos_depurados(correo, ref, id_ref, id_administrador, fecha) VALUES ('$correo','0','0','$id_administrador',NOW())");
                $mensaje .= $correo . ' <b><i>NO ENCONTRADO</i></b><br/>';
            } else {
                $mensaje .= $correo . ' <b><i>DEPURADO</i></b><br/>';
            }
        }
    }
    $mensaje = '<div class="alert alert-success">
  <strong>EXITO</strong> ' . $cnt . ' correos depurados.
</div>' . $mensaje . '<hr>';
}
?>


<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li class="active">DEPURACION DE CORREOS</li>
        </ul>
        <h3 class="page-header">
            <i class="fa fa-indent"></i> DEPURACION DE CORREOS <i class="fa fa-info-circle animated bounceInDown show-info"></i>
        </h3>
    </div>
</div>

<?php echo $mensaje; ?>

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    DEPURACION DE CORREOS
                    <span class="pull-right">
                        <a class="panel-minimize"><i class="fa fa-info"></i></a>
                    </span>
                </h3>
            </div>
            <form action="" method="post">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nombre">Correos</label> *
                                        <textarea name="correos" class="form-control" placeholder="Correos separados por comas, espacios o saltos de linea" required="" style="height: 200px;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <input type="submit" name="depurar-correos" class="btn btn-success btn-sm btn-block" value="DEPURAR" />
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <hr>

        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">
                    CORREOS DEPURADOS
                    <span class="pull-right">
                        <a class="panel-minimize"><i class="fa fa-list"></i></a>
                    </span>
                </h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>EDITAR</th>
                        <th>CORREO</th>
                        <th>AUTO-CORRECCI&Oacute;N</th>
                        <th>REFERENCIA</th>
                        <th>ID</th>
                        <th>NOMBRE</th>
                        <th>CELULAR</th>
                        <th>FECHA</th>
                        <th>ADMIN</th>
                    </tr>
                    <?php
                    $rqdcd1 = query("SELECT c.*,(a.nombre)dr_adminsitrador FROM correos_depurados c INNER JOIN administradores a ON c.id_administrador=a.id ORDER BY c.id DESC limit 150 ");
                    while ($rqdcd2 = fetch($rqdcd1)) {
                        if ($rqdcd2['ref'] == '1') {
                            $rqdpr1 = query("SELECT nombres,apellidos,celular FROM cursos_participantes WHERE id='" . $rqdcd2['id_ref'] . "' ORDER BY id DESC limit 1 ");
                            $rqdpr2 = fetch($rqdpr1);
                            $nombre = $rqdpr2['nombres'] . ' ' . $rqdpr2['apellidos'];
                            $celular = $rqdpr2['celular'];
                        } elseif ($rqdcd2['ref'] == '2') {
                            $rqdus1 = query("SELECT nombres,apellidos,celular FROM cursos_usuarios WHERE id='" . $rqdcd2['id_ref'] . "' ORDER BY id DESC limit 1 ");
                            $rqdus2 = fetch($rqdus1);
                            $nombre = $rqdus2['nombres'] . ' ' . $rqdus2['apellidos'];
                            $celular = $rqdus2['celular'];
                        } else {
                            $nombre = '...';
                            $celular = '...';
                        }
                    ?>
                        <tr>
                            <td>
                                <?php
                                if ($rqdcd2['ref'] == '1' || $rqdcd2['ref'] == '2') {
                                ?>
                                    <b class="btn btn-success btn-xs" onclick="editar_correo('<?php echo $rqdcd2['ref']; ?>','<?php echo $rqdcd2['id_ref']; ?>');">EDITAR</b>
                                <?php
                                }
                                ?>
                            </td>
                            <td><?php echo $rqdcd2['correo']; ?></td>
                            <td>
                                <?php
                                if (strlen($celular)==8) {
                                    $enlace_autocorreccion = $dominio.'aut-mail/'.$rqdcd2['ref'].'/'.$rqdcd2['id_ref'].'/'.md5(md5('auteditco104511'.$rqdcd2['id_ref'])).'.html';
                                    $txt_wap_autfact = "Hola ".trim($nombre)."__ __Hemos detectado que el correo *".$rqdcd2['correo']."* con el que te registraste a ".$___nombre_del_sitio." esta incorrecto, por lo que no podemos enviarte información importante.__Te invitamos a corregir tu correo en el siguiente enlace:__ __".$enlace_autocorreccion;
                                ?>
                                    Enlace de auto-correcci&oacute;n: 
                                    &nbsp;&nbsp;&nbsp; 
                                    <b style="font-size: 15pt;color: #327532;">
                                    &nbsp;&nbsp;&nbsp; 
                                    <a href="https://api.whatsapp.com/send?phone=591<?php echo $celular; ?>&text=<?php echo str_replace(' ','%20',str_replace('__','%0A',$txt_wap_autfact)); ?>" target="_blank"><img src="<?php echo $dominio_www; ?>contenido/imagenes/wapicons/wap-init-0.jpg" style="height: 25px;border-radius: 20%;cursor:pointer;position: absolute;"></a>
                                    <br>
                                    <input type="text" class="form-control" value="<?php echo $enlace_autocorreccion; ?>"/>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($rqdcd2['ref'] == '1') {
                                    echo '<b class="label label-info">PARTICIPANTE</b>';
                                } elseif ($rqdcd2['ref'] == '2') {
                                    echo '<b class="label label-primary">USUARIO</b>';
                                } else {
                                    echo '<b class="label label-default">NO ENCONTRADO</b>';
                                }
                                ?>
                            </td>
                            <td><?php echo $rqdcd2['id_ref']; ?></td>
                            <td><?php echo $nombre; ?></td>
                            <td>
                                <?php echo $celular; ?>
                                &nbsp;
                                <?php
                                if (($rqdcd2['ref'] == '1' || $rqdcd2['ref'] == '2') && strlen($celular)==8) {
                                ?>
                                    <a href="https://api.whatsapp.com/send?phone=591<?php echo $celular; ?>&text=" target="_blank"><img src="<?php echo $dominio_www; ?>contenido/imagenes/wapicons/wap-init-0.jpg" style="height: 25px;border-radius: 20%;cursor:pointer;"></a>
                                <?php
                                }
                                ?>
                            </td>
                            <td><?php echo date("d/m/Y H:i", strtotime($rqdcd2['fecha'])); ?></td>
                            <td><?php echo $rqdcd2['dr_adminsitrador']; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>

    </div>
</div>


<!-- editar_correo -->
<script>
    function editar_correo(ref,id_ref) {
        $("#TITLE-modgeneral").html('EDITAR CORREO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.depurar-correos.editar_correo.php',
            data: {ref: ref, id_ref: id_ref},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>