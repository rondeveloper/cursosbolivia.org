<?php
/* mensaje */
$mensaje = '';

/* vista */
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

$registros_a_mostrar = 70;
$start = ($vista - 1) * $registros_a_mostrar;

$buscar = "";
$qr_busqueda = "";
if (isset_post('buscador') || isset($get[5])) {
    $sw_busqueda = true;
    if (isset_post('buscar')) {
        $buscar = post('buscar');
    } elseif (isset($get[5])) {
        $buscar = $get[5];
    }
    if (isset_post('id_ciudad') && post('id_ciudad') !== '0') {
        $id_ciudad = post('id_ciudad');
        $qr_busqueda = " AND l.id_ciudad='$id_ciudad' AND ( l.busqueda LIKE '%$buscar%') ";
    } elseif (isset_post('id_departamento') && post('id_departamento') !== '0') {
        $id_departamento = post('id_departamento');
        $qr_busqueda = " AND l.id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') AND ( l.busqueda LIKE '%$buscar%') ";
    } else {
        $qr_busqueda = " AND ( l.busqueda LIKE '%$buscar%') ";
    }

    $vista = 1;
} else {
    $sw_busqueda = false;
}


/* agregar-lugar */
if (isset_post('agregar-lugar')) {
    $nombre = post('nombre');
    $id_ciudad = post('id_ciudad');
    $salon = post('salon');
    $direccion = post('direccion');
    $google_maps = post_html('google_maps');
    $estado = post('estado');

    $result = query("INSERT INTO cursos_busquedas("
            . "id_ciudad,"
            . "nombre,"
            . "salon,"
            . "direccion,"
            . "google_maps,"
            . "estado"
            . ") VALUES("
            . "'$id_ciudad',"
            . "'$nombre',"
            . "'$salon',"
            . "'$direccion',"
            . "'$google_maps',"
            . "'$estado'"
            . " ) ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro agregado exitosamente.
</div>';
}


/* editar-lugar */
if (isset_post('editar-lugar')) {
    $id_lugar = post('id_lugar');
    $nombre = post('nombre');
    $id_ciudad = post('id_ciudad');
    $salon = post('salon');
    $direccion = post('direccion');
    $google_maps = post_html('google_maps');
    $estado = post('estado');

    query("UPDATE cursos_busquedas SET "
            . "nombre='$nombre',"
            . "id_ciudad='$id_ciudad',"
            . "salon='$salon',"
            . "direccion='$direccion',"
            . "google_maps='$google_maps',"
            . "estado='$estado'"
            . " WHERE id='$id_lugar' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* eliminar-banner */
if (isset_post('eliminar-banner')) {
    $id_lugar = post('id_lugar');

    query("UPDATE cursos_busquedas SET "
            . "estado='0' "
            . " WHERE id='$id_lugar' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro fue eliminado correctamente.
</div>';
}

$resultado1 = query("SELECT * FROM cursos_busquedas l WHERE 1 $qr_busqueda ORDER BY l.id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT count(*) AS total FROM cursos_busquedas l WHERE 1 $qr_busqueda ");
$resultado2b = mysql_fetch_array($resultado2);

$total_registros = $resultado2b['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="cursos-listar.adm">Cursos</a></li>
            <li class="active">Busquedas</li>
        </ul>

        <div class="form-group pull-right">
            <!--            <button class="btn btn-success" data-toggle="modal" data-target="#MODAL-agregar-lugar">
                            <i class="fa fa-plus"></i> 
                            AGREGAR LUGAR
                        </button> &nbsp;&nbsp;-->
        </div>
        <h3 class="page-header"> BUSQUEDAS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de busquedas registrados.
            </p>
        </blockquote>

        <form action="" method="post">
            <div class="col-md-6">
                <div class="input-group col-sm-12">
                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Buscador: </span>
                    <input type="text" name="buscar" value="<?php echo $busqueda; ?>" class="form-control" placeholder="Ingrese criterio de busqueda..."/>
                </div>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="id_departamento" id="select_departamento" onchange="actualiza_ciudades();">
                    <?php
                    echo "<option value='0'>Todos los departamentos...</option>";
                    $rqd1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ");
                    while ($rqd2 = mysql_fetch_array($rqd1)) {
                        $text_check = '';
                        if ($id_departamento == $rqd2['id']) {
                            $text_check = ' selected="selected" ';
                        }
                        echo "<option value='" . $rqd2['id'] . "' $text_check>" . $rqd2['nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="id_ciudad" id="select_ciudad">
                    <?php
                    echo "<option value='0'>Todos las ciudades...</option>";
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="submit" value="BUSCAR" name="buscador" class="btn btn-warning btn-block active"/>
            </div>
        </form>

    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <br/>

        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de busquedas realizadas
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>DEPARTAMENTO</th>
                                <th>CIUDAD</th>
                                <th>BUSQUEDA</th>
                                <th>FECHA</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = mysql_fetch_array($resultado1)) {
                                /* departamento */
                                $rqdd1 = query("SELECT nombre FROM departamentos WHERE id='" . $producto['id_departamento'] . "' LIMIT 1 ");
                                $nom_departamento = '<span style="color:#AAA;">Todos</span>';
                                if (mysql_num_rows($rqdd1) > 0) {
                                    $rqdd2 = mysql_fetch_array($rqdd1);
                                    $nom_departamento = $rqdd2['nombre'];
                                }

                                /* ciudad */
                                $rqdc1 = query("SELECT nombre FROM ciudades WHERE id='" . $producto['id_ciudad'] . "' LIMIT 1 ");
                                $nom_ciudad = '<span style="color:#AAA;">Todos</span>';
                                if (mysql_num_rows($rqdc1) > 0) {
                                    $rqdc2 = mysql_fetch_array($rqdc1);
                                    $nom_ciudad = $rqdc2['nombre'];
                                }
                                ?>
                                <tr>
                                    <td><?php echo $cnt--; ?></td>
                                    <td><?php echo $nom_departamento; ?></td>
                                    <td><?php echo $nom_ciudad; ?></td>
                                    <td><?php echo $producto['busqueda']; ?></td>
                                    <td><?php echo date("d/M - H:i", strtotime($producto['fecha'])); ?></td>
                                    <td><?php echo $producto['ip']; ?></td>
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

                    <li><a href="busquedas/1.adm">Primero</a></li>                           
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
                                echo '<li><a href="busquedas/' . $i . '.adm">' . $i . '</a></li>';
                            }
                        }
                    }
                    ?>                            
                    <li><a href="busquedas/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                </ul>
            </div><!-- /col-md-12 -->	
        </div>

    </div>
</div>

<script>
    function actualiza_ciudades() {
        $("#select_ciudad").html('<option>Cargando...</option>');
        var id_departamento = $("#select_departamento").val();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.actualiza_ciudades.php',
            data: {id_departamento: id_departamento, current_id_ciudad: '0', sw_option_todos: '1'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#select_ciudad").html(data);
            }
        });
    }
</script>
<script>
    function actualiza_ciudades_r(dat) {
        $("#select_ciudad_r" + dat).html('<option>Cargando...</option>');
        var id_departamento = $("#select_departamento_r" + dat).val();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.actualiza_ciudades.php',
            data: {id_departamento: id_departamento, current_id_ciudad: '0'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#select_ciudad_r" + dat).html(data);
            }
        });
    }
</script>
<script>
    function actualiza_ciudades_t() {
        $("#select_ciudad_t").html('<option>Cargando...</option>');
        var id_departamento = $("#select_departamento_t").val();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.actualiza_ciudades.php',
            data: {id_departamento: id_departamento, current_id_ciudad: '0'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#select_ciudad_t").html(data);
            }
        });
    }
</script>
<script>
    actualiza_ciudades();
</script>


