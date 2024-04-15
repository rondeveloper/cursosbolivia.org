<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo 'DENEGADO';
    exit;
}

/* data */
$id_grupo = post('id_grupo');

/* grupo */
$rqdg1 = query("SELECT titulo,fecha,horarios,cnt_reproducciones FROM cursos_agrupaciones WHERE id='$id_grupo' LIMIT 1 ");
$rqdg2 = mysql_fetch_array($rqdg1);
$nombre_grupo = $rqdg2['titulo'];
$fecha_grupo = $rqdg2['fecha'];
$horarios_grupo = $rqdg2['horarios'];
$cnt_reproducciones = $rqdg2['cnt_reproducciones'];


$rqdcl1 = query("SELECT count(*) AS total FROM cursos_rel_agrupcursos WHERE id_grupo='$id_grupo' ");
$rqdcl2 = mysql_fetch_array($rqdcl1);
$cnt_cursos = $rqdcl2['total'];

/* total registros */
$rqdcpigr1 = query("SELECT count(*) AS total FROM cursos_proceso_registro WHERE estado IN (0,1) AND (id_grupo='$id_grupo' OR id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') ) ");
$rqdcpigr2 = mysql_fetch_array($rqdcpigr1);
$cnt_registros = $rqdcpigr2['total'];

/* total registros con pago */
$rqdcpigrp1 = query("SELECT count(*) AS total FROM cursos_proceso_registro WHERE estado IN (0,1) AND (id_grupo='$id_grupo' OR id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') ) AND sw_pago_enviado='1' ");
$rqdcpigrp2 = mysql_fetch_array($rqdcpigrp1);
$cnt_registros_conpago = $rqdcpigrp2['total'];

/* total registros sin pago */
$rqdcpigrps1 = query("SELECT count(*) AS total FROM cursos_proceso_registro WHERE estado IN (0,1) AND (id_grupo='$id_grupo' OR id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') ) AND sw_pago_enviado='0' ");
$rqdcpigrps2 = mysql_fetch_array($rqdcpigrps1);
$cnt_registros_sinpago = $rqdcpigrps2['total'];

$rqdcpig1 = query("SELECT DISTINCT nombres,apellidos FROM cursos_participantes WHERE estado IN (0,1) AND id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') ");
$cnt_participantes = mysql_num_rows($rqdcpig1);

/*
while($rqdcpig2 = mysql_fetch_array($rqdcpig2)){
    $nom_participante = $rqdcpig2['nombres'];
    $ape_participante = $rqdcpig2['apellidos'];
}
*/
?>

<h3><?php echo $nombre_grupo; ?></h3>
<hr>

<table class="table table-bordered table-striped">
    <tr>
        <td><b>Fecha:</b></td>
        <td><?php echo $fecha_grupo; ?></td>
    </tr>
    <tr>
        <td><b>Horarios:</b></td>
        <td><?php echo $horarios_grupo; ?></td>
    </tr>
    <tr>
        <td><b>Cursos:</b></td>
        <td><?php echo $cnt_cursos; ?></td>
    </tr>
    <tr>
        <td><b>Vistas:</b></td>
        <td><?php echo $cnt_reproducciones; ?></td>
    </tr>
    <tr>
        <td><b>Total participantes:</b></td>
        <td><?php echo $cnt_participantes; ?> participantes</td>
    </tr>
    <tr>
        <td><b>Total registros:</b></td>
        <td><?php echo $cnt_registros; ?> registros</td>
    </tr>
    <tr>
        <td><b>Registros con pago:</b></td>
        <td><?php echo $cnt_registros_conpago; ?> registros</td>
    </tr>
    <tr>
        <td><b>Registros sin pago:</b></td>
        <td><?php echo $cnt_registros_sinpago; ?> registros</td>
    </tr>
<!--    <tr>
        <td><b>Registros activados:</b></td>
        <td><?php echo $cnt_participantes; ?></td>
    </tr>
    <tr>
        <td><b>Registros no activados:</b></td>
        <td><?php echo $cnt_participantes; ?></td>
    </tr>-->
    <tr>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Solicitud de pago</td>
        <td id="AJAXCONTENT-solicitar_pago_grupo">
            <b class="btn btn-success btn-block" onclick="solicitar_pago_grupo('<?php echo $id_grupo; ?>');">SOLICITAR PAGO</b>
        </td>
    </tr>
</table>

<hr>

<!-- historial_participante -->
<script>
    function solicitar_pago_grupo(id_grupo) {
        $("#AJAXCONTENT-solicitar_pago_grupo").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos.solicitar_pago_grupo.php',
            data: {id_grupo: id_grupo},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-solicitar_pago_grupo").html(data);
            }
        });
    }
</script>

