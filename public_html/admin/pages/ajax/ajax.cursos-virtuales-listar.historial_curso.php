<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_curso = post('id_curso');

/* registros */
$rqdl1 = query("SELECT *,(select nombre from administradores where id=cursos_log.id_usuario)administrador FROM cursos_log WHERE objeto='curso-virtual' AND id_objeto='$id_curso' ORDER BY id DESC ");
if (num_rows($rqdl1) == 0) {
    echo '<div class="alert alert-info">
  <strong>Aviso!</strong> no se registro movimientos realizados vinculados con este curso.
</div>
';
} else {
    ?>
    <table class="table table-striped table-bordered">
        <tr>
            <th>FECHA</th>
            <th>MOVIMIENTO</th>
            <th>PROCESO/IP</th>
            <th>ADMINISTRADOR</th>
        </tr>
        <?php
        while ($rqdl2 = fetch($rqdl1)) {
            ?>
            <tr>
                <td>
                    <?php 
                    echo date("d/M/y", strtotime($rqdl2['fecha']));
                    echo "<br/>";
                    echo date("H:i", strtotime($rqdl2['fecha']));
                    ?>
                </td>
                <td><?php echo $rqdl2['movimiento']; ?></td>
                <td>
                    <?php echo $rqdl2['proceso']; ?>
                    <br/>
                    <?php echo $rqdl2['ip']; ?>
                </td>
                <td><?php echo $rqdl2['administrador']; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>

    <?php
}


