<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


/* acceso */
if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$id_curso = post('id_curso');
$id_administrador = administrador('id');

/* datos recibidos */
$ids_participantes_dat = post('dat');
if ($ids_participantes_dat == '') {
    $ids_participantes_dat = '0';
}

/* limpia datos de id participante */
$ar_exp_aux = explode(",", $ids_participantes_dat);
$ids_participantes = '0';
foreach ($ar_exp_aux as $value) {
    $ids_participantes .= "," . (int) $value;
}
/* END limpia datos de id participante */


if (isset_post('sw_trasladar_part')) {
    $id_curso_destino = post('id_curso_destino');
    $rqvc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso_destino' LIMIT 1 ");
    if(num_rows($rqvc1)==0){
        echo '<div class="alert alert-danger">
        <strong>ERROR</strong> el curso <b>' . $id_curso_destino . '</b> no existe.
      </div>';
        exit;
    }
    $ids_participantes = post('ids_participantes');
    $ar1 = explode(",", $ids_participantes_dat);
    foreach ($ar1 as $id_participante) {
        if (isset_post('idpart-' . $id_participante)) {
            $rqdp1 = query("SELECT id,nombres,apellidos FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
            $rqdp2 = fetch($rqdp1);
            $id_proceso_registro = $rqdp2['id'];
            $nombre_part = $rqdp2['nombres'] . ' ' . $rqdp2['apellidos'];
            query("UPDATE cursos_proceso_registro SET id_curso='$id_curso_destino' WHERE id_curso='$id_curso' AND id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
            query("UPDATE cursos_participantes SET id_curso='$id_curso_destino' WHERE id_curso='$id_curso' AND id='$id_participante' ORDER BY id DESC limit 1 ");
            logcursos('Participante trasladado ['.$id_curso.' -> '.$id_curso_destino.']', 'participante-edicion', 'participante', $id_participante);
            echo '<div class="alert alert-success">
            <strong>EXITO</strong> participante <b>' . $nombre_part . '</b> trasladado correctamente.
          </div>
          ';
        }
    }
} else {
    $rqcp1 = query("SELECT p.*,pr.sw_pago_enviado,(rvp.id)dr_id_verifpago 
    FROM cursos_participantes p 
    INNER JOIN cursos_proceso_registro pr ON p.id_proceso_registro=pr.id 
    LEFT JOIN rel_pagosverificados rvp ON rvp.id_participante=p.id 
    WHERE p.estado='1' AND p.id IN ($ids_participantes) AND p.id_curso='$id_curso' 
    ORDER BY p.id DESC ");
    if (num_rows($rqcp1) == 0) {
        echo "<br/><p>No se encontraron participantes.</p><br/><br/>";
    } else {
?>
        <div>
            <table class="table table-striped table-bordered">
                <?php
                while ($rqcp2 = fetch($rqcp1)) {    
                    if(true){
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" id="checkbox-idpart-<?php echo $rqcp2['id']; ?>" checked="" style="width:25px;height:25px;" />
                            </td>
                            <td>
                                <span style='font-size: 15pt !important;padding-bottom: 7pt;'><?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?></span>
                                <br>
                                <span style='font-size: 10pt !important;padding-bottom: 7pt;'><?php echo $rqcp2['correo']; ?></span>
                            </td>
                            <td id="tr-ajaxcontent-proceso-uno-<?php echo $rqcp2['id']; ?>"></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
            <br>
            <div id="activacion-options">
                <p class='text-center'>
                    <b>&iquest; Desea enviar los correos de sesiones ZOOM a estos participantes ?</b>
                </p>
                <div class="text-center">
                    <b class="btn btn-success" onclick="enviar_sesiones_zoom();">
                        <i class="fa fa-check"></i> ENVIAR SESIONES ZOOM
                    </b>
                </div>
            </div>
        </div>
<?php
    }
}
?>

<script>
    function enviar_sesiones_zoom() {
        if(confirm(' CONFIRMACION\n Desea proceder con el envio ?')){
            let ids = ('<?php echo $ids_participantes; ?>').split(',');
            ids.filter(id => id != '0').forEach(id => {
                if ($('#checkbox-idpart-'+id).is(":checked")) {
                    var formData = new FormData();
                    formData.append('id_participante', id);
                    formData.append('keyaccess', '5rw4t6gd1');
                    formData.append('id_administrador', '<?php echo $id_administrador; ?>');
                    $("#tr-ajaxcontent-proceso-uno-"+id).html('Activando...');
                    $.ajax({
                        url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-participantes.enviar_invitacion_zoom.php',
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        dataType: 'html',
                        success: function(data) {
                            console.log(data);
                            $("#tr-ajaxcontent-proceso-uno-"+id).html('<b class="text-success">ENVIADO</b><br>');
                        }
                    });
                }
            });
            $("#activacion-options").html('<div class="alert alert-success">Los correos ya fueron enviados</div>');
            lista_participantes(<?php echo $id_curso; ?>, 0);
        }
    }
</script>
