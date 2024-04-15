<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}
$search = post('search');
?>
<table class="table table-striped table-bordered">
    <tr>
        <th>Fecha</th>
        <th>Nombre</th>
        <th>N&uacute;mero de contacto</th>
        <th>Detalle de consulta</th>
        <th>Administrador</th>
        <th>Acci&oacute;n</th>
    </tr>
    <?php
    $qr_busc = '';
    if ($search !== '') {
        $busc_consuta = $search;
        $qr_busc = " WHERE nombre LIKE '%$busc_consuta%' OR tel_cel LIKE '%$busc_consuta%' OR contenido LIKE '%$busc_consuta%' ";
    }
    $rqcn1 = query("SELECT id,nombre,tel_cel,contenido,(select nombre from administradores where id=c.id_administrador limit 1)administrador,sw_leido,fecha_registro FROM consultas c $qr_busc ORDER BY id DESC LIMIT 50 ");
    while ($rqcn2 = mysql_fetch_array($rqcn1)) {
        $style = 'readed';
        if ($rqcn2['sw_leido'] == '1') {
            $style = 'not-readed';
        }
        ?>
        <tr class="<?php echo $style; ?>" ondblclick="marcar_consulta_leido('<?php echo $rqcn2['id']; ?>');" id="row-consult-<?php echo $rqcn2['id']; ?>">
            <td style="font-size:8pt;">
                <?php echo date("d/M  H:i", strtotime($rqcn2['fecha_registro'])); ?>
                <input type="hidden" id="sw-leido-<?php echo $rqcn2['id']; ?>" value="<?php echo $rqcn2['sw_leido']; ?>"/>
            </td>
            <td><?php echo $rqcn2['nombre']; ?></td>
            <td><?php echo $rqcn2['tel_cel']; ?></td>
            <td>
                <?php
                echo $rqcn2['contenido'];
                $rqcr1 = query("SELECT respuesta,(select nombre from administradores where id=cr.id_administrador)administrador FROM consultas_respuestas cr WHERE id_consulta='" . $rqcn2['id'] . "' ");
                while ($rqcr2 = mysql_fetch_array($rqcr1)) {
                    ?>
                    <div style="margin:8px 0px;">
                        <b><?php echo $rqcr2['administrador']; ?></b> : 
                        <span style="background:#93f9ff;border-radius:3px;padding: 3px 4px;">
                            <?php echo $rqcr2['respuesta']; ?>
                        </span>
                    </div>
                    <?php
                }
                ?>
            </td>
            <td><?php echo $rqcn2['administrador']; ?></td>
            <td>
                <b class='btn btn-xs btn-success btn-response' onclick="responder_consulta_p1('<?php echo $rqcn2['id']; ?>');" data-toggle="modal" data-target="#MODAL-responde-consultas">responder</b>
            </td>
        </tr>
        <?php
    }
    ?>
</table>