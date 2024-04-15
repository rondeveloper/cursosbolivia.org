<?php
session_start();
include_once '../../../../configuracion/config.php';
include_once '../../../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


$rqdc1 = query("SELECT c.*,(md.nombre)dr_nombre_modalidad FROM cursos c INNER JOIN cursos_modalidades md ON c.id_modalidad=md.id WHERE c.estado IN (1,2) ");
?>
<table class="table table-striped table-bordered table-hover">
    <?php
    while ($curso = fetch($rqdc1)) {
        ?>
        <tr>
            <td><?php echo $curso['titulo']; ?></td>
            <td class="text-right"><?php echo $curso['costo'].' BS'; ?></td>
            <td>
                <?php echo $curso['dr_nombre_modalidad']; ?>
                <br>
                <a href="<?php echo $dominio.$curso['titulo_identificador']; ?>.html" target="_blank" style="font-size:9pt;">Ver detalles</a>
                <br>
                Estado:
                <?php 
                if($curso['estado']=='1'){
                    echo '<b class="text-success">Activo</b>';
                } elseif($curso['estado']=='2'){
                    echo '<b class="text-danger">Temporal</b>';
                }
                ?>
            </td>
            <td class="text-center">
                <b class="btn btn-sm btn-success">+ REGISTRAR</b>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

