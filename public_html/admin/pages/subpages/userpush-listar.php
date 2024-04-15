<?php
/* acceso */
if (!acceso_cod('adm-usuarios')) {
    echo "DENEGADO";
    exit;
}

/* mensaje */
$mensaje = '';

/* vista */
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

$registros_a_mostrar = 60;
$start = ($vista - 1) * $registros_a_mostrar;


/* seleccion */
$qr_seleccion = '';
if (isset($get[3])) {
    switch ($get[3]) {
        case 'con-cuenta':
            $qr_seleccion = " AND id_usuario<>'0' ";
            break;
        default:
            break;
    }
}

/* departamento */
$qr_departamento = '';
if (isset($get[4])) {
    $id_departamento = $get[4];
    $qr_departamento = " AND id_departamento='$id_departamento' ";
}

$buscar = "";
$qr_busqueda = "";
if (isset_post('buscar') || isset($get[5])) {
    $sw_busqueda = true;
    if (isset_post('buscar')) {
        $buscar = post('buscar');
    } else {
        $buscar = $get[5];
    }
    $qr_busqueda = " AND ( nombres LIKE '%$buscar%' OR apellidos LIKE '%$buscar%' OR email LIKE '%$buscar%') ";
    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-usuario */
if (isset_post('agregar-usuario')) {
    $descripcion = post('descripcion');
    $estado = post('estado');
    $nom_img = 'imagen';

    if (!isset_archivo($nom_img)) {
        $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> no se selecciono ninguna imagen.
</div>';
    } else {

        $nombre_imagen = time() . archivoName($nom_img);
        move_uploaded_file(archivo($nom_img), $___path_raiz."contenido/imagenes/usuarios/$nombre_imagen");
        $result = query("INSERT INTO cursos_usuarios("
                . "imagen,"
                . "descripcion,"
                . "estado"
                . ") VALUES("
                . "'$nombre_imagen',"
                . "'$descripcion',"
                . "'$estado'"
                . " ) ");

        $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
    }
}


/* editar-usuario */
if (isset_post('editar-usuario')) {
    $id_usuario = post('id_usuario');
    $nombres = post('nombres');
    $apellidos = post('apellidos');
    $email = post('email');
    $celular = post('celular');
    $estado = post('estado');
    $nom_img = 'imagen';
    if (isset_archivo($nom_img)) {
        $nombre_imagen = time() . archivoName($nom_img);
        move_uploaded_file(archivo($nom_img), $___path_raiz."contenido/imagenes/usuarios/$nombre_imagen");
    }
    query("UPDATE cursos_usuarios SET "
            . "nombres='$nombres',"
            . "apellidos='$apellidos',"
            . "email='$email',"
            . "celular='$celular',"
            . "estado='$estado'"
            . " WHERE id='$id_usuario' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-usuario */
if (isset_post('eliminar-usuario')) {
    $id_usuario = post('id_usuario');

    query("UPDATE cursos_usuarios SET "
            . "estado='0' "
            . " WHERE id='$id_usuario' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT * FROM cursos_suscnav u WHERE estado IN ('1','2','3') $qr_busqueda $qr_seleccion $qr_departamento ORDER BY id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_suscnav WHERE estado IN ('1','2','3') $qr_busqueda $qr_seleccion $qr_departamento ");
$resultado2b = fetch($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel principal</a></li>
            <li><a href="userpush-listar.adm">Usuarios</a></li>
            <li class="active">Listado de usuarios</li>
        </ul>

        <div class="form-group pull-right">
            <!--            <button class="btn btn-success" data-toggle="modal" data-target="#MODAL-agregar-usuario">
                            <i class="fa fa-plus"></i> 
                            AGREGAR USUARIO
                        </button> &nbsp;&nbsp;-->
            <?php
            $rqdd1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
            while ($rqdd2 = fetch($rqdd1)) {
                $rqdt1 = query("SELECT count(*) AS total FROM cursos_suscnav WHERE id_departamento='" . $rqdd2['id'] . "' ");
                $rqdt2 = fetch($rqdt1);
                ?>
                <a href="userpush-listar/1/todos/<?php echo $rqdd2['id']; ?>.adm" class="btn btn-default btn-xs">(<?php echo $rqdt2['total']; ?>) <?php echo $rqdd2['nombre']; ?></a>&nbsp;&nbsp;
                <?php
            }
            ?>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="userpush-listar/1/todos.adm" class="btn btn-default">TODOS</a>&nbsp;&nbsp;
            <a href="userpush-listar/1/con-cuenta.adm" class="btn btn-default">CON CUENTA</a>&nbsp;&nbsp;
        </div>
        <h3 class="page-header"> USUARIOS NAV PUSH <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de usuarios registrados.
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de usuarios
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="">
                    <form action="" method="post">
                        <div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">@ BUSCADOR:</span>
                                <input type="text" class="form-control" name="buscar" placeholder="Ingrese criterio de busqueda de usuario..." value="<?php echo $buscar; ?>" autocomplete="off" onkeyup="busca_usuarios();"/>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default" type="button" onclick="busca_usuarios();"><i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>TOKEN</th>
                                <th>DEPARTAMENTO</th>
                                <th>CUENTA</th>
                                <th>IP</th>
                                <th>FECHA REGSITRO</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = fetch($resultado1)) {
                                $txt_estado = '<span style="color:red;">DES-REGISTRADO</span>';
                                if ($producto['estado'] == '1') {
                                    $txt_estado = '<span style="color:green;">REGISTRADO</span>';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $cnt--; ?></td>
                                    <td><?php echo substr($producto['token'], 0, 50); ?>...</td>
                                    <td>
                                        <?php
                                        if ($producto['id_departamento'] == '0') {
                                            echo "<span style='color:gray;'>No registrado</span>";
                                        } else {
                                            $rqdu1 = query("SELECT nombre FROM departamentos WHERE id='" . $producto['id_departamento'] . "' ORDER BY id DESC limit 1 ");
                                            $rqdu2 = fetch($rqdu1);
                                            echo $rqdu2['nombre'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($producto['id_usuario'] == '0') {
                                            echo "<span style='color:gray;'>Sin cuenta</span>";
                                        } else {
                                            $rqdu1 = query("SELECT nombres,apellidos FROM cursos_usuarios WHERE id='" . $producto['id_usuario'] . "' ORDER BY id DESC limit 1 ");
                                            $rqdu2 = fetch($rqdu1);
                                            echo trim($rqdu2['nombres'] . ' ' . $rqdu2['apellidos']);
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $producto['ip']; ?></td>
                                    <td><?php echo date("d/m/Y H:i", strtotime($producto['fecha_registro'])); ?></td>
                                    <td><?php echo $txt_estado; ?></td>
                                    <td>
                                        <a data-toggle="modal" data-target="#MODAL-editar-usuario-<?php echo $producto['id']; ?>" title="Editar">
                                            <button type="button" class="btn btn-info btn-xs btn-block active"><i class="fa fa-edit"></i> EDITAR</button>
                                        </a>

                                        <!-- Modal -->
                                        <div id="MODAL-editar-usuario-<?php echo $producto['id']; ?>" class="modal modal-info fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"><i class="fa fa-edit"></i> EDICI&Oacute;N DE USUARIO</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-default">
                                                            <?php echo $producto['token']; ?>
                                                        </div>
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
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>



        <div class="row">
            <div class="col-md-12">
                <ul class="pagination">
                    <?php
                    $urlget3 = '';
                    if (isset($get[3])) {
                        $urlget3 = '/' . $get[3];
                    }
                    $urlget4 = '';
                    if (isset($get[4])) {
                        $urlget4 = '/' . $get[4];
                    }
                    $urlget5 = '';
                    if (isset($buscar)) {
                        if ($urlget3 == '') {
                            $urlget3 = '/--';
                        }
                        if ($urlget4 == '') {
                            $urlget4 = '/--';
                        }
                        $urlget5 = '/' . $buscar;
                    }
                    ?>

                    <li><a href="userpush-listar/1.adm">Primero</a></li>                           
                    <?php
                    $inicio_paginador = 1;
                    $fin_paginador = 15;
                    $total_cursos = ceil($total_registros / $registros_a_mostrar);

                    if ($vista > 10) {
                        $inicio_paginador = $vista - 5;
                        $fin_paginador = $vista + 10;
                    }
                    if ($fin_paginador > $total_cursos) {
                        $fin_paginador = $total_cursos;
                    }

                    if ($total_cursos > 1) {
                        for ($i = $inicio_paginador; $i <= $fin_paginador; $i++) {
                            if ($vista == $i) {
                                echo '<li class="active"><a href="productos/' . $i . '.adm">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="userpush-listar/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="userpush-listar/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>


<!----------->


<hr/>

<b class="btn btn-info btn-block" onclick="accionar();">ACCIONAR</b>

<hr/>

<br/><br/><br/><br/><br/><br/>

