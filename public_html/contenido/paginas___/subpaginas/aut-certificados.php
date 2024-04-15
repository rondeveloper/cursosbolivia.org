<?php
require_once "contenido/librerias/classes/class.codigo-control-v7.php";

use clases\CodigoControlV7;

/* mensaje */

$mensaje = '';

/* data */
$id_curso = $get[2];
$id_participante = null;

/* datos curso */
$rqauxc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' LIMIT 1 ");
if (num_rows($rqauxc1) == 0) {
    echo "<b>Error!</b> no se encontro ID de curso";
    exit;
}
$rqauxc2 = fetch($rqauxc1);
$titulo_curso = strtoupper($rqauxc2['titulo']);

?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <?php echo $mensaje; ?>
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">

                <div class="TituloArea">
                    <h3>CERTIFICADOS HABILITADOS</h3>
                    <b>CURSO:</b> <?= $titulo_curso ?>
                    <br>
                    <br>
                </div>

                <div class="Titulo_texto1">
                    <?php if (!isset_post('verificar-participante')) { ?>
                        <div style="background: #f3fbff;padding: 20px;font-size: 12pt;text-align: justify;border: 1px solid #e0e0e0;">
                            Desde aqu&iacute; puede descargar los certificados del curso en el cual fue participante, por favor ingrese el numero de C.I. con el que se registro en el curso. <br>
                            <br>

                            <div style="text-align:center;padding: 20px 0px;">
                                <form action="" method="post">
                                    <table class="table table-bordered" style="background: #FFF;">
                                        <tr>
                                            <td style="padding: 15px;"><b>C.I.:</b></td>
                                            <td><input type="text" value="" class="form-control" name="ci" placeholder="Ingresa el CI con el que te registraste..." required="" autocomplete="off" /></td>
                                        </tr>
                                    </table>
                                    <br>
                                    <input type="submit" name="verificar-participante" value="CONTINUAR" class="btn btn-success" style="width: auto;border-radius: 5px;padding: 10px 20px;">
                                </form>
                            </div>
                        </div>
                        <?php
                    } else {
                        $ci_participante = post('ci');

                        /* participante */
                        $rqdp1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND ci='$ci_participante' ORDER BY id DESC limit 1 ");
                        if (num_rows($rqdp1) == 0) {
                            echo '<br><br><div class="alert alert-danger">
                        <strong>ERROR</strong> el C.I. no corresponde a ningun participante registrado en este curso.
                      </div>';
                        } else {
                            $participante = fetch($rqdp1);
                            $id_participante = $participante['id'];
                            $nombre_participante = $participante['nombres'] . ' ' . $participante['apellidos'];
                            $correo_participante = $participante['correo'];
                            $id_proceso_registro = $participante['id_proceso_registro'];

                            /* proceso registro */
                            $rqdpr1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
                            $procreg = fetch($rqdpr1);
                            $monto_a_facturar = $procreg['monto_deposito'];
                            $id_emision_factura = (int)$procreg['id_emision_factura'];
                        ?>
                            <div style="color: #777;">
                                <b>PARTICIPANTE:</b> <?= $nombre_participante ?>
                            </div>
                            <br>
                            <br>
                            <table class="table table-striped table-bordered table-responsive">
                                <?php
                                /* CERTIFICADOS INICIALES */
                                $rqcertin1 = query("SELECT * FROM cursos_certificados WHERE id IN (SELECT id_certificado FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso' ORDER BY id ASC )");
                                if (num_rows($rqcertin1) == 0) {
                                    echo "<tr><td colspan='5'><p>El curso actual no tiene certificados asociados.</p></td></tr>";
                                }
                                while ($rqdcrt2 = fetch($rqcertin1)) {
                                ?>
                                    <tr>
                                        <td style="padding: 10px;"><?php echo ++$cnt; ?></td>
                                        <td style="padding: 15px;">
                                            <input type="checkbox" name="" id="" style="height: 20px;width: 20px;" checked>
                                        </td>
                                        <td style="padding: 10px;">
                                            <?php
                                            echo "<b>" . $rqdcrt2['codigo'] . "</b>";
                                            echo "<br/>";
                                            echo "<b>Texto QR</b> &nbsp; " . $rqdcrt2['texto_qr'];
                                            echo "<br/>";
                                            echo "<b>Fecha 1</b> &nbsp; " . date(" d / m / Y", strtotime($rqdcrt2['fecha_qr']));
                                            echo "<br/>";
                                            echo "<b>Fecha 2</b> &nbsp; " . date(" d / m / Y", strtotime($rqdcrt2['fecha2_qr']));
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo $rqdcrt2['cont_titulo_curso'];
                                            echo "<br/>";
                                            echo $rqdcrt2['cont_dos'];
                                            echo "<br/>";
                                            echo $rqdcrt2['cont_tres'];
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>

                            <div style="text-align: center;padding:30px;">
                                <a class="btn btn-xs btn-info active" onclick="solicitar_certificados();">
                                    <i class='fa fa-edit'></i> SOLICITAR CERTIFICADOS
                                </a>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
                <hr />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
            </div>
        </div>
    </section>
</div>


<script>
    function solicitar_certificados() {
        $("#MODAL-general").modal('show');
        $("#title-MODAL-general").html('SOLICITAR CERTIFICADOS');
        $("#body-MODAL-general").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.aut-certificados.solicitar_certificados.php',
            data: {id_participante: '<?php echo $id_participante; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $('#body-MODAL-general').html(data);
            }
        });
    }
</script>