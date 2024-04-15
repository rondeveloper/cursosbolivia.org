<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* id curso */
$id_curso = (int) post('id_curso');

/* id_participante */
$id_participante = (int) post('id_participante');

/* verficacion de cupon */
$rqdcd1 = query("SELECT * FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' LIMIT 1 ");
if (num_rows($rqdcd1) == 0) {
    echo "<br/><b>No se habilito cupones para este curso!</b><br/>";
    exit;
}

/* cupon */
$rqdcd2 = fetch($rqdcd1);
$id_cupon = $rqdcd2['id'];

/* participante */
$rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id_curso='$id_curso' AND id='$id_participante' AND sw_pago='1' ORDER BY id DESC ");
$rqcp2 = fetch($rqcp1);
$nombre_participante = trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']);

/* exceptuar_participante */
$sw_exceptuado = false;
$rqv1 = query("SELECT id FROM rel_partexceptcupon WHERE id_curso='$id_curso' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
if(num_rows($rqv1)>0){
    $sw_exceptuado = true;
}
if(isset_post('exceptuar_participante')){
    $rqv1 = query("SELECT id FROM rel_partexceptcupon WHERE id_curso='$id_curso' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
    if(num_rows($rqv1)==0){
        query("INSERT INTO rel_partexceptcupon (id_curso,id_participante) VALUES ('$id_curso','$id_participante') ");
        echo '<div class="alert alert-success">
        <strong>EXITO</strong> registro modificado correctamente.
    </div>';
    }
    $sw_exceptuado = true;
}

/* sw_emitir_cupon */
if (isset_post('sw_emitir_cupon')) {
    $id_administrador = administrador('id');
    /* cupon */
    $rqdcd1 = query("SELECT * FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' LIMIT 1 ");
    $rqdcd2 = fetch($rqdcd1);
    $id_cupon = $rqdcd2['id'];
    $id_paquete = $rqdcd2['id_paquete'];
    $duracion = $rqdcd2['duracion'];
    $fecha_expiracion = $rqdcd2['fecha_expiracion'];

    /* dosificacion de cupones */
    $cupones = obtiene_cupones($id_paquete, $duracion, $fecha_expiracion, 1);
    $array_cupones = explode(',', str_replace(',completo', '', $cupones));

    /* verificacion de emision anterior */
    $rqve1 = query("SELECT id FROM cursos_emisiones_cupones_infosicoes WHERE id_cupon='$id_cupon' AND id_curso='$id_curso' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
    if (num_rows($rqve1) == 0) {
        $codigo = array_pop($array_cupones);
        query("INSERT INTO cursos_emisiones_cupones_infosicoes(
           id_cupon, 
           id_curso, 
           id_participante, 
           codigo, 
           id_administrador, 
           fecha_registro, 
           estado
           ) VALUES (
           '$id_cupon',
           '$id_curso',
           '$id_participante',
           '$codigo',
           '$id_administrador',
           NOW(),
           '1'
           )");
?>
        <div class="alert alert-success">
            <strong>EXITO</strong> registro agregado correctamente.
        </div>
    <?php
    }
}

/* verificacion de emision anterior */
$rqve1 = query("SELECT id FROM cursos_emisiones_cupones_infosicoes WHERE id_participante='$id_participante' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
if($sw_exceptuado){
    echo "<b>PARTICIPANTE NO HABILITADO PARA CUP&Oacute;N</b>";
} elseif (num_rows($rqve1) == 0) {
    ?>
    <table class="table table-bordered table-striped">
        <tr>
            <td class='text-center'>
                <p><b>&iquest; Desea emitir el cupon a este participante ?</b></p>
            </td>
        </tr>
        <tr>
            <td class='text-center'>
                <h3><?php echo $nombre_participante; ?></h3>
            </td>
        </tr>
        <tr>
            <td class='text-center'>
                <button class="btn btn-success" onclick="cupon_infosicoes_emitir();">EMITIR CUPON</button>
            </td>
        </tr>
    </table>
<?php
} else {
?>
    <h5 class="">
        CUPON EMITIDO
    </h5>
    <hr />
    <div class="row">
        <table class="table table-bordered table-striped">
            <?php
            $ids_a_enviar = '0';
            $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id_curso='$id_curso' AND id='$id_participante' AND sw_pago='1' ORDER BY id DESC ");
            while ($rqcp2 = fetch($rqcp1)) {
                $id_participante = $rqcp2['id'];
                /* verificacion de emision anterior */
                $rqve1 = query("SELECT * FROM cursos_emisiones_cupones_infosicoes WHERE id_cupon='$id_cupon' AND id_curso='$id_curso' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
                if (num_rows($rqve1) > 0) {
                    $rqve2 = fetch($rqve1);
                    $ids_a_enviar .= ',' . $rqve2['id'];
            ?>
                    <tr>
                        <td>
                            <span style='font-size: 12pt !important;'><?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?></span>
                            <br>
                            <b style='font-size: 8pt !important;'><?php echo trim($rqcp2['correo']); ?></b>
                        </td>
                        <td>
                            <?php echo substr($rqve2['codigo'], 0, (strlen($rqve2['codigo']) - 3)) . '***'; ?>
                            <br>
                            <br>
                            <button onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/cupon-infosicoes.php?id_cupon=<?php echo $id_cupon; ?>&id_participante=<?php echo $id_participante; ?>', 'popup', 'width=700,height=500');" class="btn btn-info btn-xs">IMPRIMIR</button>
                        </td>
                        <td>
                            <?php if ($rqcp2['correo'] !== '') { ?>
                                <span id="ajaxcontent-enviar_cupon-<?php echo $rqve2['id']; ?>">
                                    <b class="btn btn-default btn-xs" onclick="enviar_cupon_infosicoes('<?php echo $rqve2['id']; ?>');">ENVIAR CORREO</b>
                                </span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php
                            if (strlen($rqcp2['celular']) == 8) {
                                $url_desc_cupon = 'https://cursos.bo/CPN/' . $rqve2['codigo'] . '/';
                                $text_wap = str_replace(' ', '%20', str_replace('__', '%0A', 'Hola ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos'] . ',__Puedes descargar tu cupón Infosicoes en el siguiente enlace:__' . $url_desc_cupon));
                            ?>
                                <a href="https://api.whatsapp.com/send?phone=591<?php echo $rqcp2['celular']; ?>&text=<?php echo $text_wap; ?>" target="_blank">
                                    <img src="<?php echo $dominio_www; ?>contenido/imagenes/wapicons/wap-init-0.jpg" style="height: 45px;border-radius: 20%;cursor: pointer;border: 1px solid #1bc564;">
                                </a>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </table>
    </div>

    <hr>
<?php
}

if(!$sw_exceptuado){
?>
<br>
<hr>
<b class="btn btn-danger btn-xs pull-right" onclick="exceptuar();">
× EXCEPTUAR ×
</b>
<br>
<br>
<?php
}
?>

<script>
    function exceptuar() {
        if(confirm('ESTA SEGURO DE EXCEPTUAR A ESTE PARTICIPANTE PARA RECIBIR CUPON ?')){
            $("#AJAXCONTENT-modgeneral").html('Cargando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.cupon_infosicoes.php',
                data: {
                    id_curso: '<?php echo $id_curso; ?>',
                    id_participante: '<?php echo $id_participante; ?>',
                    exceptuar_participante: 1
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
            });
        }
    }
</script>

<script>
    function enviar_cupon_infosicoes(id_emision_cupon_infosicoes) {
        $("#ajaxcontent-enviar_cupon-" + id_emision_cupon_infosicoes).html('Enviando...');
        $.ajax({
            url: 'pages/ajax/ajax.instant.enviar_cupon_infosicoes.php',
            data: {
                id_emision_cupon_infosicoes: id_emision_cupon_infosicoes
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxcontent-enviar_cupon-" + id_emision_cupon_infosicoes).html(data);
            }
        });
    }
</script>


<!-- ajax emision cupones descuento2 -->
<script>
    function emision_cupones_infosicoes_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_curso = '<?php echo $id_curso; ?>';
        $("#AJAXCONTENT-emite_certificados_multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $("#box-modal_emision_cupones-descuento").html("PROCESANDO...");
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_cupones_infosicoes_p2.php',
            data: {
                dat: ids.join(','),
                id_curso: id_curso
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                //$("#box-modal_emision_cupones-descuento").html(data);
                //lista_participantes(<?php echo $id_curso; ?>, 0);
                //alert("CUPONES EMITIDOS CORRECTAMENTE");
                emision_cupones_infosicoes();
            }
        });
    }
</script>






<!-- cupon_infosicoes -->
<script>
    function cupon_infosicoes_emitir() {
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.cupon_infosicoes.php',
            data: {
                id_participante: '<?php echo $id_participante; ?>',
                id_curso: '<?php echo $id_curso; ?>',
                sw_emitir_cupon: 1
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<?php
function obtiene_cupones($id_paquete, $duracion, $fecha_expiracion, $cnt_participantes){
    $cont = file_get_contents("https://www.infosicoes.com/contenido/paginas/procesos/externos/webservice.cursosbo.cupones.php?id_paquete=$id_paquete&duracion=$duracion&fecha_expiracion=$fecha_expiracion&cnt_participantes=$cnt_participantes");
    return $cont;
}
