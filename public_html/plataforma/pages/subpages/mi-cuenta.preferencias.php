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

/* suscribir */
if (isset_post('suscribir')) {
    $id_categoria = post('categoria');
    query("INSERT INTO cursos_rel_usu_cat (id_categoria,id_usuario) VALUES ('$id_categoria','$id_usuario')");
    $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> el registro se agrego correctamente.
</div>
';
}

/* quitar-suscripcion */
if (isset_post('quitar-suscripcion')) {
    $id_categoria = post('categoria');
    query("DELETE FROM cursos_rel_usu_cat WHERE id_categoria='$id_categoria' AND id_usuario='$id_usuario' limit 5 ");
    $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> el registro se elimin&oacute; correctamente.
</div>
';
}

/* actualizar-datos */
if (isset_post('actualizar-datos')) {
    $celular_usuario = post('celular');
    $email_usuario = post('correo');
    $id_departamento = post('id_departamento');
    query("UPDATE cursos_usuarios SET celular='$celular_usuario',email='$email_usuario',id_departamento='$id_departamento' WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
    $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> el registro se modific&oacute; correctamente.
</div>
';
}

/* data usuario */
$rqdu1 = query("SELECT id_departamento,nombres,apellidos,celular,email FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
$rqdu2 = fetch($rqdu1);
$nombre_usuario = $rqdu2['nombres'];
$celular_usuario = $rqdu2['celular'];
$email_usuario = $rqdu2['email'];
if ($email_usuario == 'no-email-data') {
    $email_usuario = '';
}
$id_departamento_usuario = $rqdu2['id_departamento'];
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #f6f5f5;">
            <div class="col-md-2 hidden-xs">
                <?php
                include_once 'pages/items/item.d.menu_usuario.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">

                <?php echo $mensaje; ?>

                <?php
                if (isset($get[2]) && $get[2] == 'bienvenida' && ($celular_usuario == '' || $email_usuario == '' || $id_departamento_usuario == '0')) {
                    ?>
                    <div class="TituloArea">
                        <h3>BIENVENIDO <?php echo strtoupper($nombre_usuario); ?></h3>
                    </div>
                    <div class="Titulo_texto1">
                        <p>
                            Con el inter&eacute;s de ayudarle a progresar como profesional, 
                            en nuestra plataforma estaremos continuamente publicando cursos que sean relevantes y &uacute;tiles para usted y su profesi&oacute;n. 
                            Para poderle notificar adecuadamente de los cursos de su inter&eacute;s le solicitamos por favor nos proporcione los datos de contacto faltantes:
                        </p>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <form action="" method="post">
                                    <table class="table table-bordered table-striped table-hover">
                                        <?php
                                        if ($celular_usuario == '') {
                                            ?>
                                            <tr>
                                                <td><b>Celular:</b></td>
                                                <td><input type="number" name="celular" value="<?php echo $celular_usuario; ?>" placeholder="Ingresa tu n&uacute;mero de celular." class="form-control"/></td>
                                            </tr>
                                            <?php
                                        } else {
                                            echo '<input type="hidden" name="celular" value="' . $celular_usuario . '"/>';
                                        }
                                        if ($email_usuario == '') {
                                            ?>
                                            <tr>
                                                <td><b>Correo electr&oacute;nico:</b></td>
                                                <td><input type="email" name="correo" value="<?php echo $email_usuario; ?>" placeholder="Ingresa tu correo electr&oacute;nico." class="form-control"/></td>
                                            </tr>
                                            <?php
                                        } else {
                                            echo '<input type="hidden" name="correo" value="' . $email_usuario . '"/>';
                                        }
                                        if ($id_departamento_usuario == '0') {
                                            ?>
                                            <tr>
                                                <td><b>Departamento:</b></td>
                                                <td>
                                                    <select name="id_departamento" class="form-control">
                                                        <?php
                                                        $rqdc1 = query("SELECT id,nombre FROM departamentos ORDER BY orden ASC ");
                                                        while ($rqdc2 = fetch($rqdc1)) {
                                                            ?>
                                                            <option value="<?php echo $rqdc2['id']; ?>"><?php echo $rqdc2['nombre']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <?php
                                        } else {
                                            echo '<input type="hidden" name="id_departamento" value="' . $id_departamento_usuario . '"/>';
                                        }
                                        ?>

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
                    <?php
                }
                ?>

                <?php
                if (isset($get[2]) && $get[2] == 'bienvenida' && ($id_departamento_usuario !== '0')) {
                    $rqddw1 = query("SELECT nombre,id_whatsapp_grupo FROM departamentos WHERE id='$id_departamento_usuario' LIMIT 1 ");
                    $rqddw2 = fetch($rqddw1);
                    $nombre_departamento = $rqddw2['nombre'];
                    $id_whatsapp_grupo = $rqddw2['id_whatsapp_grupo'];

                    if ($id_whatsapp_grupo !== '0') {
                        $rqdgwd1 = query("SELECT enlace_ingreso FROM whatsapp_grupos WHERE id='$id_whatsapp_grupo' ORDER BY id DESC limit 1 ");
                        $rqdgwd2 = fetch($rqdgwd1);
                        $enlace_ingreso_gw = $rqdgwd2['enlace_ingreso'];
                        ?>
                        <div class="TituloArea">
                            <h3>UNETE AL GRUPO DE WHATSAPP</h3>
                        </div>
                        <div class="Titulo_texto1">
                            <p>
                                Enterate de los cursos de tu inter&eacute;s mediante el grupo de difusion de cursos en 'Whatsapp'. Ingresa al siguiente link o presiona el boton 'whatsapp' para ingresar al grupo de difusion de cursos de capacitaci&oacute;n en el departamento de '<?php echo $nombre_departamento; ?>'.
                            </p>
                            <div class="text-center">
                                <a href="<?php echo $enlace_ingreso_gw; ?>" target="_blank">
                                    <img src='https://www.infosicoes.com/contenido/imagenes/paginas/1510747809whatsapp__.png' style="width:40%;"/>
                                </a>
                                <br/>
                                <br/>
                                <i>Enlace de ingreso al grupo:</i>
                                <br/>
                                <br/>
                                <a href="<?php echo $enlace_ingreso_gw; ?>" style="color: #0f90dc;text-decoration: underline;" target="_blank"><?php echo $enlace_ingreso_gw; ?></a>
                            </div>
                        </div>
                        <hr/>
                        <?php
                    }
                }
                ?>



                <div class="TituloArea">
                    <h3>PREFERENCIAS</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        Suscribete a las distintas categorias de cursos para as&iacute; estar al tanto de las fechas cuando se impartiran los cursos de esa categoria, 
                        tambi&eacute;n nos ayudara a saber que tipo de cursos te interesan y asi nosotros podamos brindarte cursos relacionados en el futuro.
                    </p>
                    <p>Presiona el boton 'suscribirme' en las categorias de cursos que te interesan.</p>
                </div>

                <table class='table table-striped table-bordered table-hover'>
                    <tr>
                        <th>
                            #
                        </th>
                        <th>
                            Categoria
                        </th>
                        <th>
                            Descripci&oacute;n
                        </th>
                        <th>
                            Estado
                        </th>
                        <th>
                            -
                        </th>
                    </tr>
                    <?php
                    $qrdcu1 = query("SELECT *,(select count(1) from cursos_rel_usu_cat where id_categoria=c.id and id_usuario='$id_usuario')suscrito FROM cursos_categorias c WHERE estado='1' ORDER BY id ASC ");
                    $cnt = 0;
                    while ($qrdcu2 = fetch($qrdcu1)) {
                        ?>
                        <tr>
                            <td>
                                <?php echo ++$cnt; ?>
                            </td>
                            <td>
                                <?php echo $qrdcu2['titulo']; ?>
                            </td>
                            <td>
                                <?php echo $qrdcu2['descripcion']; ?>
                            </td>
                            <td>
                                <?php
                                if ($qrdcu2['suscrito'] == '1') {
                                    echo "<b style='color:green;'><i class='icon-ok'></i> SUSCRITO</b>";
                                } else {
                                    echo "<i class='text-default'>NO SUSCRITO</i>";
                                }
                                ?>
                            </td>
                            <td class="text-right">
                                <form action="" method="post">
                                    <input type="hidden" name="categoria" value="<?php echo $qrdcu2['id']; ?>"/>
                                    <?php
                                    if ($qrdcu2['suscrito'] == '1') {
                                        echo "<input type='submit' name='quitar-suscripcion' value='Quitar'/>";
                                    } else {
                                        echo "<input type='submit' name='suscribir' value='SUSCRIBIRME' class='btn btn-success btn-block'/>";
                                    }
                                    ?>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>






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