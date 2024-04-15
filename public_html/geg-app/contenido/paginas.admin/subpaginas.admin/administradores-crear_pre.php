<?php
/* mensaje */
$mensaje = "";

if (isset_post('agregar-administrador')) {

    $rverif1 = query("SELECT id FROM administradores WHERE nick='" . post('nick') . "' ");
    $cnt_verif1 = mysql_num_rows($rverif1);

    if (post('nick') == '' || post('password') == '' || post('nombre') == '') {
        echo "<script>alert('Debes llenar los Campos Necesarios!');history.back();</script>";
    } elseif ($cnt_verif1 > 0) {
        echo "<script>alert('Nick ya existente!');history.back();</script>";
    } else {
        query("INSERT INTO administradores ("
                . "nick,"
                . "password,"
                . "nombre,"
                . "ocupacion,"
                . "email,"
                . "nivel,"
                . "nombre_registro,"
                . "fecha_registro,"
                . "estado"
                . ") VALUES("
                . "'" . post('nick') . "',"
                . "'" . post('password') . "',"
                . "'" . post('nombre') . "',"
                . "'" . post('ocupacion') . "',"
                . "'" . post('email') . "',"
                . "'" . "9" . "',"
                . "'" . post('nombre') . "',"
                . "'" . date("Y-m-d h:i:s") . "',"
                . "'1'"
                . ")");

        $ra1 = query("SELECT id FROM administradores WHERE nick='" . post('nick') . "' AND password='" . post('password') . "' ");
        $ra2 = mysql_fetch_array($ra1);
        movimiento('Creacion de administrador', 'creacion-administrador', 'administrador', $ra2['id']);

        $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> Administador agregado correctamente.
  <hr/><br/><br/><a href="administradores-listar.adm">Listar administradores</a>
</div>';
    }
}
?>


<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a >administradores</a></li>
            <li class="active">Crear nuevo</li>
        </ul>       
        <h3 class="page-header">
            <i class="fa fa-indent"></i> Crear nuevo administrador <i class="fa fa-info-circle animated bounceInDown show-info"></i>
        </h3>
    </div>
</div>


<?php echo $mensaje; ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-cascade">
            <div class="panel-heading">
                <h3 class="panel-title">
                    En esta seccion puedes agregar a un nuevo administrador
                    <span class="pull-right">
                        <a href="administradores-listar.adm">Listar administradores </a>
                        <a class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
                    </span>
                </h3>
            </div>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="panel-body">
                    <table style='margin:auto;width:80%;'>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="nombre">Nombre</label> *
                                    <input name="nombre" type="text" class="form-control" id="email" placeholder="Introduce el Nombre Completo" required=""/>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="nivel">Nivel</label>
                                    <select name="nivel" id="role" class="form-control">
                                        <option value="">Nuevo administrador (Sin permisos)</option>
                                        <!--                                            <option value="2">Administrador de contenido (NIVEL 2)</option>
                                                                                    <option value="1">Administrador general (NIVEL 1)</option>-->
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="email">Direcci&oacute;n de E-mail</label>
                                    <input name='email' type="email" class="form-control" id="email" placeholder="Introduce tu E-mail" required=""/>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="ocupacion">Cargo en Infosicoes</label>
                                    <input name="ocupacion" type="text" class="form-control" id="email" placeholder="Introduce el cargo asignado" required=""/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label for="nick">Nick</label> *
                                    <input name="nick" type="text" class="form-control" id="email" placeholder="Introduce el nick de usuario" required=""/>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label for="password">Contrase&ntilde;a</label> *
                                    <input name="password" type="password" class="form-control" placeholder="Introduce la contrase&ntilde;a" required=""/>
                                </div>
                            </td>
                        </tr>
                        <!-- Mas campos aqui… -->
                    </table>
                </div>
                <div class="form-group">
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <input type="submit" name="agregar-administrador" class="btn btn-success btn-lg btn-animate-demo" value="AGREGAR ADMINISTRADOR"/>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>





