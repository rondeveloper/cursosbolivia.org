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
    $rqcp1 = query("SELECT p.* FROM cursos_participantes p WHERE p.estado='1' AND p.id IN ($ids_participantes) AND p.id_curso='$id_curso' ORDER BY p.id DESC ");
    if (num_rows($rqcp1) == 0) {
        echo "<br/><p>No se encontraron participantes.</p><br/><br/>";
    } else {
?>
        <div id="AJAXCONTENT-emite_certificados_multiple_p2">
            <form id="FORM-traspart" action='' method='post'>
                <div style="background: #efefef;padding: 25px;border-radius: 15px;line-height: 2.4;border: 1px solid #dedede;">
                    <b>ID del curso destino del traslado:</b>
                    <br>
                    <input type="number" name="id_curso_destino" value="" required="" class="form-control" placeholder="ID del curso..." />
                </div>
                <hr>
                <table class="table table-striped table-bordered">
                    <?php
                    $ids_emisioncert = '0';
                    while ($rqcp2 = fetch($rqcp1)) {
                        $ids_emisioncert .= ',' . $rqcp2['id_emision_certificado'];
                    ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="idpart-<?php echo $rqcp2['id']; ?>" checked="" style="width:25px;height:25px;" />
                            </td>
                            <td>
                                <span style='font-size: 15pt !important;padding-bottom: 7pt;'><?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?></span>
                                <br>
                                <span style='font-size: 10pt !important;padding-bottom: 7pt;'><?php echo $rqcp2['correo']; ?></span>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
                <br />
                <p class='text-center'>
                    <b>&iquest; Desea trasladar a estos participantes ?</b>
                </p>

                <input type="hidden" name="ids_participantes" value="<?php echo $ids_participantes; ?>" />
                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                <input type="hidden" name="dat" value="<?php echo $ids_participantes_dat; ?>" />
                <input type="hidden" name="sw_trasladar_part" value="1" />

                <div class="text-center">
                    <b class="btn btn-success" onclick="trasladar_participantes_p2();">TRASLADAR PARTICIPANTES</b>
                </div>
            </form>
        </div>
<?php
    }
}
?>

<script>
    function trasladar_participantes_p2() {
        if(confirm(' CONFIRMACION\n Desea proceder con el traslado ?')){
            var form = $("#FORM-traspart").serialize();
            $("#AJAXCONTENT-modgeneral").html('Cargando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.trasladar_participantes.php',
                data: form,
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                }
            });
        }
    }
</script>