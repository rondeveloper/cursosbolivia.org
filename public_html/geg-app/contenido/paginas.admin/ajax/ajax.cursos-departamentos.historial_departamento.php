<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_departamento = post('id_departamento');

/* registros */
$rqdl1 = query("SELECT *,(select nombre from administradores where id=cursos_log.id_usuario)administrador FROM cursos_log WHERE objeto='departamento' AND id_objeto='$id_departamento' ORDER BY id DESC ");
if (mysql_num_rows($rqdl1) == 0) {
    echo '<div class="alert alert-info">
  <strong>Aviso!</strong> no se registro movimientos realizados vinculados con este departamento.
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
        while ($rqdl2 = mysql_fetch_array($rqdl1)) {
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
                <td>
                    <?php 
                    if($rqdl2['administrador']==''){
                        echo "Sistema";
                    }else{
                        echo $rqdl2['administrador']; 
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>

    <?php
}


