<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$id_curso = post('id_curso');
$rqdc1 = query("SELECT cnt_reproducciones FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$cnt_reproducciones = (int)$rqdc2['cnt_reproducciones'];
?>
<table class="table table-striped table-bordered">
    <tr>
        <th>Fecha</th>
        <?php
        for ($i = 0; $i <= 6; $i++) {
            $fecha = date("d/M", strtotime("-$i day"));
            echo "<th>$fecha</th>";
        }
        ?>
    </tr>
    <tr>
        <th>D&iacute;a</th>
        <td>Hoy</td>
        <td>Ayer</td>
        <td colspan="5"></td>
    </tr>
    <tr>
        <th>Vistas</th>
        <td colspan="7" class="text-center"><?php echo $cnt_reproducciones; ?></td>
    </tr>
    <tr>
        <th>Clics</th>
        <?php
        for ($i = 0; $i <= 6; $i++) {
            $fecha = date("Y-m-d", strtotime("-$i day"));
            echo "<td>";

            $rqcenp1 = query("SELECT SUM(reproducciones) AS total FROM metricas_e_cursos WHERE id_curso='$id_curso' AND fecha='$fecha' ORDER BY id DESC limit 1 ");
            $rqcenp2 = fetch($rqcenp1);
            $hoy_reproducciones = (int) $rqcenp2['total'];
            echo "<b class='text-warning'>$hoy_reproducciones</b>";

            echo "</td>";
        }
        ?>
    </tr>
</table>

