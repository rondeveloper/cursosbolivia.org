<?php
$mensaje = '';
$nombres = '';
$apellidos = '';
$email = '';
$celular = '';
$qr_busc = '';
$sw_list = false;


if (isset($get[2]) && $get[2] == 'cuenta-google-no-encontrada') {
    $mensaje .= '<div class="alert alert-danger">
  <strong>Aviso</strong> no se encontro cuenta de usuario vinculada a la cuenta Google ingresada.
</div>';
}



/* ingreso */
$sw_ingreso_adm = false;
if (isset_post('password')) {
    if (post('password') == 'geg74a') {
        $_SESSION['adm-reg'] = 'true';
    } else {
        $mensaje .= '<div class="alert alert-danger">
  <strong>ERROR</strong> datos incorrectos.
</div>';
    }
}

if (isset($_SESSION['adm-reg']) && $_SESSION['adm-reg'] == 'true') {
    $sw_ingreso_adm = true;
}


if (isset_post('agregar-registro') && $sw_ingreso_adm) {
    $nombre = post('nombre');
    $email = post('email');
    query("INSERT INTO usuarios(nombre, nombres, apellidos, email, fecha_registro, estado) VALUES ('$nombre','$nombre','','$email',NOW(),'1')");
    $id_reg = mysql_insert_id();
    $qr_busc = " AND id='$id_reg' ";
    $sw_list = true;
    $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> registro agregado correctamente.
</div>';
}

if (isset_post('modificar-registro') && $sw_ingreso_adm) {
    $nombre = post('nombre');
    $email = post('email');
    $id_registro = post('id_registro');
    query("UPDATE usuarios SET nombre='$nombre',email='$email' WHERE id='$id_registro' ORDER BY id ASC limit 1 ");
    query("UPDATE emisiones_certificados SET receptor_de_certificado='$nombre' WHERE id_usuario='$id_registro' ORDER BY id ASC limit 5 ");
    $qr_busc = " AND id='$id_registro' ";
    $sw_list = true;
    $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> registro modificado correctamente.
</div>';
}

if (isset_post('emitir-certificado') && $sw_ingreso_adm) {
    $id_registro = post('id_registro');
    $id_certificado = post('id_certificado');

    /* id usuario */
    $rqdu1 = query("SELECT id,nombre FROM usuarios WHERE id='$id_registro' ORDER BY id ASC limit 1 ");
    $rqdu2 = mysql_fetch_array($rqdu1);
    $id_usuario = $rqdu2['id'];
    $receptor_de_certificado = $rqdu2['nombre'];

    $id_modelo_certificado = 0;
    $id_curso = 10;
    $md = 10;
    $certificado_id = 'C' . $id_registro . strtoupper(substr(md5(rand(9999, 9999999)), 15, 3));

    query("INSERT INTO emisiones_certificados(id_certificado, id_modelo_certificado, id_curso, id_usuario, md, certificado_id, receptor_de_certificado, id_administrador_emisor, fecha_emision, estado) VALUES ('$id_certificado','$id_modelo_certificado','$id_curso','$id_usuario','$md','$certificado_id','$receptor_de_certificado','0',NOW(),'1')");
    $qr_busc = " AND id='$id_registro' ";
    $sw_list = true;
    $mensaje .= '<div class="alert alert-success">
  <strong>EXITO</strong> el certificado se emitio correctamente.
</div>';
}

if (isset_post('input-buscador') && $sw_ingreso_adm) {
    $busc = post('input-buscador');
    $qr_busc = " AND ( nombre LIKE '%$busc%' OR email LIKE '%$busc%' ) ";
    $sw_list = true;
}
?>

<style>
.modal-backdrop.in {
    display: none !important;
}
</style>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="TituloArea">
                    <h3>ADMINISTRACI&Oacute;N DE REGISTROS</h3>
                </div>

                <?php echo $mensaje; ?>

                <?php
                if (!$sw_ingreso_adm) {
                    ?>
                    <div class="Titulo_texto1">
                        <p>
                            Ingrese la contrase&ntilde;a.
                        </p>
                        <br>
                    </div>
                    <div class="boxForm ajusta_form_contacto">
                        <h5>INGRESE LA CONTRASE&Ntilde;A</h5>
                        <hr/>
                        <form action="admin-reg.html" class="form-horizontal validable" id="contactform" method="post" enctype="application/x-www-form-urlencoded" autocomplete="Off">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input class="form-control required string" type="password" name="password" placeholder="Contrase&ntilde;a..." required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="ingresar-a-cuenta" class="btn btn-success btn-lg" value="INGRESAR"/>
                                </div>
                                <br>
                                <br>
                            </div>
                            <hr/>
                            <div class="form-group text-center" style="display: none;">
                                <span><b style="font-weight:bold;">&iquest; No tienes una cuenta ?</b> registrate con el siguiente enlace:</span>
                                <br/>
                                <br/>
                                <div class="col-md-12 text-center">
                                    <a href="registro.html" type="submit" class="btn btn-primary">CREAR UNA CUENTA</a>
                                </div>
                            </div>
                        </form>

                    </div>
                    <?php
                } else {
                    ?>
                    <div class="row">
                        <div class="col-md-8">
                            Bienvenido a la plataforma.
                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-success btn-block" data-toggle="modal" data-target="#myModal"> + Agregar registro</a>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <form action="" method="post" id="FORM">
                            <div class="input-group col-sm-12">
                                <span class="input-group-addon" style="cursor:pointer;" onclick='$("#FORM").submit();'><i class="fa fa-search"></i> &nbsp; Buscar: </span>
                                <input type="text" name="input-buscador" value="" class="form-control" placeholder="Nombres / Apellidos / Correo ..." required=""/>
                            </div>
                        </form>
                    </div>
                    <hr/>
                    <table class="table table-bordered table-striped">
                        <?php
                        if ($sw_list) {
                            $rqu1 = query("SELECT * FROM usuarios WHERE estado='1' $qr_busc ORDER BY id ASC limit 10 ");
                            while ($rqu2 = mysql_fetch_array($rqu1)) {
                                ?>
                                <tr>
                                    <td><?php echo $rqu2['nombre']; ?></td>
                                    <td><?php echo $rqu2['email']; ?></td>
                                    <td>
                                        <b class="btn btn-xs btn-info btn-block" data-toggle="modal" data-target="#MODAL-modificar-<?php echo $rqu2['id']; ?>">Modificar</b>
                                        <b class="btn btn-xs btn-warning btn-block" data-toggle="modal" data-target="#MODAL-cert-<?php echo $rqu2['id']; ?>">Certificados</b>


                                        <!-- Modal -->
                                        <div id="MODAL-modificar-<?php echo $rqu2['id']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">MODIFICAR REGISTRO</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" method="post">
                                                            <table class="table table-bordered table-striped">
                                                                <tr>
                                                                    <td>Nombre:</td>
                                                                    <td><input type="text" name="nombre" placeholder="Ingrese el nombre..." value="<?php echo $rqu2['nombre']; ?>" required="" class="form-control"/></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Correo:</td>
                                                                    <td><input type="email" name="email" placeholder="Ingrese el correo..." value="<?php echo $rqu2['email']; ?>" required="" class="form-control"/></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan='2' style="padding: 15px;">
                                                                        <input type="hidden" name="id_registro" value="<?php echo $rqu2['id']; ?>"/>
                                                                        <input type="submit" name="modificar-registro" value="MODIFICAR REGISTRO" class="btn btn-info btn-block"/>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>



                                        <!-- Modal -->
                                        <div id="MODAL-cert-<?php echo $rqu2['id']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">CERTIFICADOS</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-bordered table-striped">
                                                            <?php
                                                            $rquc1 = query("SELECT * FROM certificados WHERE aux_id_cert<>'0' ");
                                                            while ($rquc2 = mysql_fetch_array($rquc1)) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $rquc2['texto_qr']; ?>
                                                                    </td>
                                                                    <?php
                                                                    $rqdec1 = query("SELECT certificado_id FROM emisiones_certificados WHERE id_usuario='" . $rqu2['id'] . "' AND id_certificado='" . $rquc2['id'] . "' ORDER BY id DESC limit 1 ");
                                                                    if (mysql_num_rows($rqdec1) > 0) {
                                                                        $rqdec2 = mysql_fetch_array($rqdec1);
                                                                        ?>
                                                                        <td style="padding: 15px;">
                                                                            <i class="label label-success">EMITIDO</i>
                                                                        </td>
                                                                        <td>
                                                                            <b><?php echo $rqdec2['certificado_id']; ?></b>
                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <td>
                                                                            <i>NO EMITIDO</i>
                                                                        </td>
                                                                        <td>
                                                                            <form action="" method="post">
                                                                                <input type="hidden" name="id_registro" value="<?php echo $rqu2['id']; ?>"/>
                                                                                <input type="hidden" name="id_certificado" value="<?php echo $rquc2['id']; ?>"/>
                                                                                <input type="submit" name="emitir-certificado" value="EMITIR" class="btn btn-warning btn-block"/>
                                                                            </form>
                                                                        </td>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>



                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                    <?php
                }
                ?>


                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />


            </div>
            <div class="col-md-2">
                <style>
                    .wtt-aux{
                        padding-left: 50px;
                    }
                </style>

            </div>
        </div>
        <br>
        <br>

    </section>
</div>                     



<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">AGREGAR REGISTRO</h4>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Nombre:</td>
                            <td><input type="text" name="nombre" placeholder="Ingrese el nombre..." required="" class="form-control"/></td>
                        </tr>
                        <tr>
                            <td>Correo:</td>
                            <td><input type="email" name="email" placeholder="Ingrese el correo..." required="" class="form-control"/></td>
                        </tr>
                        <tr>
                            <td colspan='2' style="padding: 15px;">
                                <input type="submit" name="agregar-registro" value="AGREAR REGISTRO" class="btn btn-success btn-block"/>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>

    </div>
</div>

