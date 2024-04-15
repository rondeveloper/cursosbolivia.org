<?php

/* mensaje */
$mensaje = "";

/* generar-codigo */
if (isset_post('generar-codigo')) {

    $codigo = post('codigo');
    $descuento = post('descuento');
    $limite_usos = post('limite_usos');
    $fecha_expiracion = post('fecha_expiracion');
    $id_curso = post('id_curso');
    $id_administrador = administrador('id');

    $qrverif =  query("SELECT id FROM codigos_descuento WHERE codigo='$codigo' AND fecha_expiracion>CURDATE() ");
    if (num_rows($qrverif) > 0) {
        $mensaje .= '<div class="alert alert-danger">
  <strong>ERROR</strong> existe el codigo ' . $codigo . ' con fecha de expiraci&oacute;n valida.
</div>';
    } else {
        query("INSERT INTO codigos_descuento 
    (id_curso, id_administrador, codigo, descuento, limite_usos, fecha_expiracion, fecha_registro, estado) 
    VALUES 
    ('$id_curso','$id_administrador','$codigo','$descuento','$limite_usos','$fecha_expiracion',NOW(),'1')");

        $mensaje = '<div class="alert alert-success">
  <strong>EXITO</strong> registro generado correctamente.
</div>';
    }
}
?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li class="active">C&Oacute;DIGO DE DESCUENTO</li>
        </ul>
        <h3 class="page-header">
            <i class="fa fa-indent"></i> C&Oacute;DIGO DE DESCUENTO <i class="fa fa-info-circle animated bounceInDown show-info"></i>
        </h3>
    </div>
</div>

<?php echo $mensaje; ?>

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">
                    C&Oacute;DIGO DE DESCUENTO
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
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td><b>C&Oacute;DIGO:</b></td>
                                    <td><input type="text" name="codigo" class="form-control" required="" value="<?php echo strtoupper(substr(md5(rand(999, 999999)), 7, 7)); ?>" /></td>
                                </tr>
                                <tr>
                                    <td><b>DESCUENTO:</b> (BS)</td>
                                    <td><input type="number" name="descuento" class="form-control" required="" value="0" min="1" max="999" /></td>
                                </tr>
                                <tr>
                                    <td><b>L&Iacute;MITE DE USOS:</b></td>
                                    <td><input type="number" name="limite_usos" class="form-control" required="" value="1" /></td>
                                </tr>
                                <tr>
                                    <td><b>FECHA DE EXPIRACI&Oacute;N:</b></td>
                                    <td><input type="date" name="fecha_expiracion" class="form-control" required="" value="<?php echo date("Y-m-d", strtotime("+1 month", time())); ?>" /></td>
                                </tr>
                                <tr id="tr-cur">
                                    <td><b>CURSO:</b></td>
                                    <td style="width: 70%;">
                                        <select name="id_curso" class="form-control">
                                            <option value="0">VALIDO PARA TODOS LOS CURSOS</option>
                                            <?php
                                            $rqdh1 = query("SELECT id,titulo,fecha FROM cursos WHERE estado IN (1,2) ");
                                            while ($rqdh2 = fetch($rqdh1)) {
                                            ?>
                                                <option value="<?php echo $rqdh2['id']; ?>"><?php echo $rqdh2['id'] . ' | ' . $rqdh2['titulo'] . ' (' . $rqdh2['fecha'] . ')'; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <input type="submit" name="generar-codigo" class="btn btn-success btn-lg" value="GENERAR C&Oacute;DIGO" />
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <hr>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    C&Oacute;DISO GENERADOS
                    <span class="pull-right">
                        <a class="panel-minimize"><i class="fa fa-list"></i></a>
                    </span>
                </h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>#</th>
                        <th>C&Oacute;DIGO</th>
                        <th>DESCUENTO</th>
                        <th>USOS</th>
                        <th>FECHA DE EXPIRACI&Oacute;N</th>
                        <th>CURSO</th>
                        <th>FECHA DE REGISTRO</th>
                        <th>ADMINISTRADOR</th>
                        <th>ESTADO</th>
                    </tr>
                    <?php
                    $rqdcd1 = query("SELECT c.*,(a.nombre)dr_adminsitrador FROM codigos_descuento c INNER JOIN administradores a ON c.id_administrador=a.id ORDER BY c.id DESC limit 150 ");
                    $cnt = num_rows($rqdcd1);
                    while ($rqdcd2 = fetch($rqdcd1)) {
                    ?>
                        <tr>
                            <td><?php echo $cnt--; ?></td>
                            <td><?php echo $rqdcd2['codigo']; ?></td>
                            <td><?php echo $rqdcd2['descuento']; ?> BS</td>
                            <td>
                                <?php
                                $rqddcv1 = query("SELECT COUNT(*) AS total FROM codigos_descuento_usos WHERE id_codigo_descuento='" . $rqdcd2['id'] . "' ");
                                $rqddcv2 = fetch($rqddcv1);
                                echo $rqddcv2['total'] . ' / ' . $rqdcd2['limite_usos'];
                                ?>
                            </td>
                            <td><?php echo date("d / m / Y", strtotime($rqdcd2['fecha_expiracion'])); ?></td>
                            <td>
                                <?php
                                if ($rqdcd2['id_curso'] == '0') {
                                    echo "VALIDO PARA TODOS";
                                } else {
                                    $rqcr1 = query("SELECT titulo FROM cursos WHERE id='" . $rqdcd2['id_curso'] . "' ORDER BY id DESC LIMIT 1 ");
                                    $rqcr2 = fetch($rqcr1);
                                    echo $rqcr2['titulo'];
                                }
                                ?>
                            </td>
                            <td><?php echo date("d / m / Y H:i", strtotime($rqdcd2['fecha_registro'])); ?></td>
                            <td><?php echo $rqdcd2['dr_adminsitrador']; ?></td>
                            <td>
                                <?php
                                if ($rqdcd2['estado'] == '1') {
                                    echo "VALIDO";
                                } else {
                                    echo "NO VALIDO";
                                }
                                ?>
                            </td>
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
    function editar_correo(ref, id_ref) {
        $("#TITLE-modgeneral").html('EDITAR CORREO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.depurar-correos.editar_correo.php',
            data: {
                ref: ref,
                id_ref: id_ref
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>