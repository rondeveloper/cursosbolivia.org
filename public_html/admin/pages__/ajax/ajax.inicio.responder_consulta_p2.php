<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

$id_consulta = post('id_consulta');
$id_administrador = administrador('id');
$respuesta = post('respuesta');
$fecha_registro = date("Y-m-d H:i");
if (strlen($respuesta) > 0) {
    query("INSERT INTO consultas_respuestas(id_consulta,respuesta, id_administrador, fecha_registro) VALUES ('$id_consulta','$respuesta','$id_administrador','$fecha_registro')");
}

$rqcr1 = query("SELECT *,(select nombre from administradores where id=cr.id_administrador)administrador FROM consultas_respuestas cr WHERE id_consulta='$id_consulta' ");
while ($rqcr2 = fetch($rqcr1)) {
    ?>
    <tr>
        <td>
            <b><?php echo $rqcr2['administrador']; ?>:</b> 
            <?php echo $rqcr2['respuesta']; ?>
        </td>
        <td class="text-right">
            <?php echo date("d/M H:i", strtotime($rqcr2['fecha_registro'])); ?>
        </td>
    </tr>
    <?php
}
