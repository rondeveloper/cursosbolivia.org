<?php
$mensaje = '';

if (isset_post('editar')) {

    $id = $get[2];

    $nombre = post('nombre');
    $nick = post('nick');
    $password = post('password');
    $ocupacion = post('ocupacion');
    $email = post('email');
    $nivel = post('nivel');

    $result = query(" UPDATE administradores SET "
            . "nombre='$nombre',"
            . "nick='$nick',"
            . "password='$password',"
            . "ocupacion='$ocupacion',"
            . "email='$email',"
            . "nivel='$nivel' "
            . " WHERE id='$id' ");

    if ($result) {
        echo "<script>alert('Actualizacion exitosa!');</script>";
        movimiento('Edicion de administrador - [' . $nombre . ']', 'edicion-administrador', 'administrador', $id);
    }
}

$r1 = query("SELECT * FROM administradores WHERE id='" . $get[2] . "'");
$r2 = mysql_fetch_array($r1);
?>



<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="administradores-listar.adm">Administradores</a></li>
            <li class="active">Administradores</li>
        </ul>

        <h3 class="page-header">
            <i class="fa fa-indent"></i> Edici&oacute;n de Administradores <i class="fa fa-info-circle animated bounceInDown show-info"></i>
        </h3>
    </div>
</div>

<?php echo $mensaje; ?>

<?php //editorTinyMCE('editor');    ?>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-cascade">
            <div class="panel-heading">
                <h3 class="panel-title">
                    ADMINISTRADOR
                    <span class="pull-right">
                        <a href="administradores-listar.adm" class="add-button" style="float:right;">Listar </a>
                        <a class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
                    </span>
                </h3>
            </div>
            <div class="panel-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group has-success">
                        <div class="col-lg-12 col-md-12">
                            <table style='margin:auto;width:80%;'>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label> *
                                            <input name="nombre" class="form-control" id="email" placeholder="Introduce el Nombre Completo" value="<?php echo $r2['nombre']; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="nivel">Nivel</label>
                                            <select name="nivel" id="role" class="form-control">
                                                <option value="<?php echo $r2['nivel']; ?>">
                                                    <?php 
                                                    if($r2['nivel']=='1'){
                                                        echo "Administrador general (NIVEL 1)";
                                                    }else{
                                                        echo "Administrador de contenido (NIVEL 2)";
                                                    }
                                                    ?>
                                                </option> 
                                                <option disabled>----</option>
                                                <option value="2">Administrador de contenido (NIVEL 2)</option>
                                                <option value="1">Administrador general (NIVEL 1)</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="email">Dirección de E-mail</label>
                                            <input name='email' class="form-control" id="email" placeholder="Introduce tu E-mail" value="<?php echo $r2['email']; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="ocupacion">Ocupaci&oacute;n en Infosicoes</label>
                                            <input name="ocupacion" class="form-control" id="email" placeholder="Introduce tu E-mail" value="<?php echo $r2['ocupacion']; ?>">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="nick">Nick</label> *
                                            <input name="nick" class="form-control" id="email" placeholder="Introduce tu E-mail" value="<?php echo $r2['nick']; ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <label for="password">Contrase&ntilde;a</label> *
                                            <input name="password" type="password" class="form-control" id="email" placeholder="Introduce tu E-mail" value="<?php echo $r2['password']; ?>">
                                        </div>
                                    </td>
                                </tr>
                                <!-- Mas campos aqui… -->
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <p style="text-align: center;">
                            <input type="submit" name="editar" class="btn btn-success btn-lg btn-animate-demo" value="Actualizar"/>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

