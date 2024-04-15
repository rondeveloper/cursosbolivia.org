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


if (!acceso_cod('adm-traspart')) {
    echo "ACCESO DENEGADO";
    exit;
}



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
        <div id="AJAXCONTENT-emite_certificados_multiple_p2">
            <div style="background: #efefef;padding: 25px;border-radius: 15px;line-height: 2.4;border: 1px solid #dedede;">
                <b>CURSOS VIRTUALES HABILITADOS:</b>
                <br>
                <?php
                $rqccg1 = query("SELECT oc.titulo,oc.id FROM cursos_onlinecourse oc INNER JOIN cursos_rel_cursoonlinecourse r ON oc.id=r.id_onlinecourse WHERE r.id_curso='$id_curso' AND r.estado='1' ");
                $ids_cvirtuales_activar = '';
                while($rqccg2 = fetch($rqccg1)){
                    echo ' - '.$rqccg2['titulo'].'<br>';
                    $ids_cvirtuales_activar .= ','.$rqccg2['id'];
                }
                ?>
            </div>
            <hr>
            <table class="table table-striped table-bordered">
                <?php
                while ($rqcp2 = fetch($rqcp1)) {    
                    if($rqcp2['sw_pago_enviado']=='1' && $rqcp2['dr_id_verifpago']!=""){
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
                            <td id="tr-ajaxcontent-proceso-dos-<?php echo $rqcp2['id']; ?>"></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
            <br>
            <div id="activacion-options">
                <p class='text-center'>
                    <b>&iquest; Desea activar todos los cursos virtuales disponibles a estos participantes ?</b>
                    <br>
                    <i>( En caso de ya estar activados, solo se confirmar&aacute; la activacion )</i>
                </p>
                <div class="text-center">
                    <b class="btn btn-success" onclick="activar_participantes_proceso_uno();">
                        <i class="fa fa-check"></i> ACTIVAR PARTICIPANTES
                    </b>
                </div>
            </div>
            <hr>
            <div id="correo-acceso-options">
                <p class='text-center'>
                    <b>&iquest; Desea enviar los accesos por correo a estos participantes ?</b>
                    <br>
                    <i>( En caso de no tener cursos activados no se har&aacute; el env&iacute;o de correo )</i>
                </p>
                <div class="text-center">
                    <b class="btn btn-info" onclick="activar_participantes_proceso_dos();">
                        <i class="fa fa-envelop-o"></i> ENVIAR ACCESOS POR CORREO
                    </b>
                </div>
            </div>
        </div>
<?php
    }
}
?>

<script>
    function activar_participantes_proceso_uno() {
        if(confirm(' CONFIRMACION\n Desea proceder con la activacion ?')){
            let ids = ('<?php echo $ids_participantes; ?>').split(',');
            ids.filter(id => id != '0').forEach(id => {
                if ($('#checkbox-idpart-'+id).is(":checked")) {
                    var formData = new FormData();
                    formData.append('id_participante', id);
                    formData.append('id_onlinecourse', '<?php echo trim($ids_cvirtuales_activar, ','); ?>');
                    $("#tr-ajaxcontent-proceso-uno-"+id).html('Activando...');
                    $.ajax({
                        url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_cvirtual_multiple.php',
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        dataType: 'html',
                        success: function(data) {
                            console.log(data);
                            $("#tr-ajaxcontent-proceso-uno-"+id).html('<b class="text-success">ACTIVADO</b><br>');
                        }
                    });
                }
            });
            $("#activacion-options").html('<div class="alert alert-success">Los participantes fueron activados</div>');
            lista_participantes(<?php echo $id_curso; ?>, 0);
        }
    }
</script>

<script>
    function activar_participantes_proceso_dos() {
        if(confirm(' CONFIRMACION\n Desea proceder con el envio de accesos por correo ?')){
            let ids = ('<?php echo $ids_participantes; ?>').split(',');
            ids.filter(id => id != '0').forEach(id => {
                if ($('#checkbox-idpart-'+id).is(":checked")) {
                    var formData = new FormData();
                    formData.append('id_participante', id);
                    $("#tr-ajaxcontent-proceso-dos-"+id).html('Enviando...');
                    $.ajax({
                        url: 'pages/ajax/ajax.cursos-participantes.cvirtual_enviar_correo_accesos.php',
                        data: formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        dataType: 'html',
                        success: function(data) {
                            console.log(data);
                            $("#tr-ajaxcontent-proceso-dos-"+id).html('<b class="text-info">Accesos enviados</b><br>');
                        }
                    });
                }
            });
            $("#correo-acceso-options").html('<div class="alert alert-success">Los accesos fueron enviados a los participantes.</div>');
        }
    }
</script>