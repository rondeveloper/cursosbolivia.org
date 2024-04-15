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
    $nombres_usuario = post('nombres');
    $apellidos_usuario = post('apellidos');
    $celular_usuario = post('celular');
    $email_usuario = post('correo');
    $id_departamento = post('id_departamento');
    query("UPDATE cursos_usuarios SET nombres='$nombres_usuario',apellidos='$apellidos_usuario',celular='$celular_usuario',email='$email_usuario',id_departamento='$id_departamento' WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
    $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> el registro se modific&oacute; correctamente.
</div>
';
}

/* data usuario */
$rqdu1 = query("SELECT id_departamento,nombres,apellidos,celular,email FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
$rqdu2 = fetch($rqdu1);
$nombre_usuario = $rqdu2['nombres'];
$apellidos_usuario = $rqdu2['apellidos'];
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
                        En esta seccion puedes actualizar los datos principales de tu cuenta en la plataforma <?php echo $___nombre_del_sitio; ?>
                    </p>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <form action="" method="post">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <td><b>Nombres:</b></td>
                                        <td><input type="text" name="nombres" value="<?php echo $nombre_usuario; ?>" placeholder="Ingresa tu nombre." class="form-control"/></td>
                                    </tr>
                                    <tr>
                                        <td><b>Apellidos:</b></td>
                                        <td><input type="text" name="apellidos" value="<?php echo $apellidos_usuario; ?>" placeholder="Ingresa tus apellidos." class="form-control"/></td>
                                    </tr>
                                    <tr>
                                        <td><b>Celular:</b></td>
                                        <td><input type="number" name="celular" value="<?php echo $celular_usuario; ?>" placeholder="Ingresa tu n&uacute;mero de celular." class="form-control"/></td>
                                    </tr>
                                    <tr>
                                        <td><b>Correo electr&oacute;nico:</b></td>
                                        <td><input type="email" name="correo" value="<?php echo $email_usuario; ?>" placeholder="Ingresa tu correo electr&oacute;nico." class="form-control"/></td>
                                    </tr>
                                    <tr>
                                        <td><b>Departamento:</b></td>
                                        <td>
                                            <select name="id_departamento" class="form-control">
                                                <?php
                                                $rqdc1 = query("SELECT id,nombre FROM departamentos ORDER BY orden ASC ");
                                                while ($rqdc2 = fetch($rqdc1)) {
                                                    $check = '';
                                                    if($rqdc2['id']==$id_departamento_usuario){
                                                        $check = ' selected="selected" ';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $rqdc2['id']; ?>" <?php echo $check; ?>><?php echo $rqdc2['nombre']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
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