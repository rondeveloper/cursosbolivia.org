<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = get('id_usuario');

$qr_item = ' p.item=0 ';
if(isset_get('items')){
    $item_id = get('item');
    $qr_item = ' p.item='.$item_id.' ';
}
?>

<div _ngcontent-pjb-c45="" class="modal-body">
    <table class="table table-bordered table-striped table-responsive">
        <tr>
            <th>#</th>
            <th>USUARIO</th>
            <th>MONTO</th>
            <th>FECHA</th>
        </tr>
        <?php
        $cnt = 1;
        $rqde1 = query("SELECT (u.id)dr_id_usuario,p.monto,u.nombres,u.apellidos,p.fecha FROM simulador_sigep_propuestas p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE $qr_item AND u.id='$id_usuario' ORDER BY p.id ASC ");
        while ($rqde2 = fetch($rqde1)) {
        ?>
            <tr>
                <td><?php echo $cnt++; ?></td>
                <td><?php echo $rqde2['nombres'] . ' ' . $rqde2['apellidos']; ?></td>
                <td style="font-size: 15pt;"><?php echo $rqde2['monto']; ?> BS</td>
                <td><?php echo date("d/m/Y H:i", strtotime($rqde2['fecha'])); ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</div>

<div _ngcontent-pjb-c45="" class="modal-footer">
    <button onclick="close_modal();" _ngcontent-pjb-c45="" class="btn btn-secondary btn-sm" type="button">Cerrar</button>
</div>