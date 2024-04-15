<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$id_departamento = post('id_departamento');
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
        <th>Correos</th>
        <?php
        for ($i = 0; $i <= 6; $i++) {
            $fecha = date("Y-m-d", strtotime("-$i day"));
            echo "<td>";

            $rqcenv1 = query("SELECT count(*) AS total FROM cursos_rel_notifdepemail WHERE id_departamento='$id_departamento' AND fecha='$fecha' ");
            $rqcenv2 = fetch($rqcenv1);
            $hoy_total = $rqcenv2['total'];
            echo "<b class='text-success'>$hoy_total</b>";

            echo "</td>";
        }
        ?>
    </tr>
    <tr>
        <th>Push</th>
        <?php
        for ($i = 0; $i <= 6; $i++) {
            $fecha = date("Y-m-d", strtotime("-$i day"));
            echo "<td>";

            $rqcenp1 = query("SELECT count(*) AS total FROM cursos_rel_notifdeppush WHERE id_departamento='$id_departamento' AND fecha_envio='$fecha' ");
            $rqcenp2 = fetch($rqcenp1);
            $hoy_push_total = $rqcenp2['total'];
            echo "<b class='text-info'>$hoy_push_total</b>";

            echo "</td>";
        }
        ?>
    </tr>
    <tr>
        <th>Vistas</th>
        <?php
        for ($i = 0; $i <= 6; $i++) {
            $fecha = date("Y-m-d", strtotime("-$i day"));
            echo "<td>";

            $rqcenp1 = query("SELECT reproducciones FROM metricas_r_departamentos WHERE id_departamento='$id_departamento' AND fecha='$fecha' ORDER BY id DESC limit 1 ");
            $rqcenp2 = fetch($rqcenp1);
            $hoy_reproducciones = (int) $rqcenp2['reproducciones'];
            echo "<b class='text-warning'>$hoy_reproducciones</b>";

            echo "</td>";
        }
        ?>
    </tr>
</table>


<h4>CURSOS</h4>
<table class="table table-bordered">
    <?php
    $rqdctob1 = query("SELECT id,titulo,fecha,cnt_reproducciones FROM cursos WHERE estado='1' AND id_modalidad<>'2' AND id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ");
    $cnt = 1;
    while ($rqdctob2 = fetch($rqdctob1)) {
        $id_curso = $rqdctob2['id'];
        $rqdcl1 = query("SELECT SUM(reproducciones) AS total FROM metricas_e_cursos WHERE id_curso='$id_curso' AND fecha=CURDATE() ");
        $rqdcl2 = fetch($rqdcl1);
        ?>
        <tr>
            <td><?php echo $cnt++; ?></td>
            <td>
                <?php echo $rqdctob2['titulo']; ?>
                <br/>
                <span style="color:#00789f;"><?php echo fecha_curso_D_d_m($rqdctob2['fecha']); ?></span>
            </td>
            <td>
                <?php echo (int)$rqdcl2['total']; ?><br/>clics
            </td>
            <td>
                <?php echo (int)$rqdctob2['cnt_reproducciones']; ?><br/>vistas
            </td>
            <td>
                <b class="btn btn-xs btn-default pull-right" onclick="show_metrica_curso('<?php echo $id_curso; ?>');">Metricas</b>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<div id="AJAXCONTENT-show_metrica_curso"></div>

<!-- historial_curso -->
<script>
    function show_metrica_curso(id_curso) {
        $("#AJAXCONTENT-show_metrica_curso").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-departamentos.show_metrica_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-show_metrica_curso").html(data);
            }
        });
    }
</script>