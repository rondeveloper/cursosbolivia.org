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

/* ids participantes */
$ids_participantes = post('dat');
if ($ids_participantes == '') {
    $ids_participantes = '0';
}

/* verficacion de cupon */
$rqdcd1 = query("SELECT * FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' LIMIT 1 ");
if (num_rows($rqdcd1) == 0) {
    echo "<br/><b>No se habilito cupones para este curso!</b><br/>";
    exit;
}
/* cupon */
$rqdcd2 = fetch($rqdcd1);
$id_cupon = $rqdcd2['id'];

/* limpia datos de id participante */
$ar_exp_aux = explode(",", $ids_participantes);
$ids_participantes = '0';
foreach ($ar_exp_aux as $value) {
    $ids_participantes .= "," . (int) $value;
}

$sw_a_emitir = false;
$rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id_curso='$id_curso' AND id IN ($ids_participantes) AND sw_pago='1' AND id NOT IN (select id_participante from rel_partexceptcupon where id_curso='$id_curso') ORDER BY id DESC ");
if (num_rows($rqcp1) == 0) {
    echo "<br/><p>No se encontraron registros disponibles para la emision de cupones.</p><br/><br/>";
} else {
?>
    <table class="table table-bordered table-striped">
        <?php
        $cnt = 1;
        while ($rqcp2 = fetch($rqcp1)) {
            /* verificacion de emision anterior */
            $rqve1 = query("SELECT id FROM cursos_emisiones_cupones_infosicoes WHERE id_participante='" . $rqcp2['id'] . "' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
            if (num_rows($rqve1) > 0) {
                continue;
            }
            $sw_a_emitir = true;
        ?>
            <tr>
                <td>
                    <?php echo $cnt++; ?>
                </td>
                <td>
                    <?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
    <?php if ($sw_a_emitir) { ?>
        <br />
        <p class='text-center'><b>&iquest; Desea emitir los cupones ?</b></p>
        <button class="btn btn-success" onclick="emision_cupones_infosicoes_p2();">EMITIR CUPONES</button>
        &nbsp;&nbsp;&nbsp;
        <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
        <br>
        <br>
    <?php } else {
        echo "NO HAY REGISTROS PARA EMITIR";
    } ?>

<?php
}
?>

<br>
<br>
<hr>
<h5 class="">
    CUPONES EMITIDOS ANTERIORMENTE
</h5>
<hr />
<div class="row">
    <table class="table table-bordered table-striped">
        <?php
        $ids_a_enviar = '0';
        $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id_curso='$id_curso' AND sw_pago='1' AND id NOT IN (select id_participante from rel_partexceptcupon where id_curso='$id_curso') ORDER BY id DESC ");
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
                            $url_desc_cupon = 'https://cursos.bo/CPN/'.$rqve2['codigo'].'/';
                            $text_wap = str_replace(' ','%20',str_replace('__','%0A','Hola '.$rqcp2['nombres'] . ' ' . $rqcp2['apellidos'].',__Puedes descargar tu cupón Infosicoes en el siguiente enlace:__'.$url_desc_cupon));
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
<br />
<br />
<button onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/cupon-infosicoes.php?id_cupon=<?php echo $id_cupon; ?>&id_curso=<?php echo $id_curso; ?>', 'popup', 'width=700,height=500');" class="btn btn-info active btn-block">
    <i class="fa fa-print"></i> IMPRIMIR TODOS LOS CUP&Oacute;NES
</button>
<hr>
<div id="boxid-enviar_todos_los_cupones">
    <button onclick="enviar_todos_los_cupones();" class="btn btn-success btn-block">
        <i class="fa fa-envelope"></i> ENVIAR TODOS LOS CUP&Oacute;NES
    </button>
</div>
<hr />

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

<script>
    var ids_a_enviar = ('<?php echo $ids_a_enviar; ?>').split(",");

    function enviar_todos_los_cupones() {
        $("#boxid-enviar_todos_los_cupones").html('<h3>LOS CUPONES SE ESTAN ENVIANDO</h3>');
        var id_emision_cupon_infosicoes = ids_a_enviar.pop();
        enviar_cupon_infosicoes_recursivamente(id_emision_cupon_infosicoes)
    }

    function enviar_cupon_infosicoes_recursivamente(id_emision_cupon_infosicoes) {
        if (id_emision_cupon_infosicoes != '0') {
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
                    if (ids_a_enviar.length > 0) {
                        var n_id_emision_cupon_infosicoes = ids_a_enviar.pop();
                        enviar_cupon_infosicoes_recursivamente(n_id_emision_cupon_infosicoes);
                    } else {
                        alert('ENVIOS DE CUPONES FINALIZADO');
                    }
                }
            });
        } else {
            if (ids_a_enviar.length > 0) {
                var n_id_emision_cupon_infosicoes = ids_a_enviar.pop();
                enviar_cupon_infosicoes_recursivamente(n_id_emision_cupon_infosicoes);
            } else {
                $("#boxid-enviar_todos_los_cupones").html('<h3>ENVIOS FINALIZADOS</h3>');
                alert('ENVIOS DE CUPONES FINALIZADO');
            }
        }
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

<!-- ajax emision cupones descuento2 -->
<script>
    function test123() {
        var ids;
        ids = $('input.nestedclass:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_curso = '<?php echo $id_curso; ?>';
        alert(ids);
    }
</script>