<?php
require_once "contenido/librerias/classes/class.codigo-control-v7.php";

use clases\CodigoControlV7;

/* mensaje */

$mensaje = '';

/* data */
$id_curso = $get[2];
$hash = $get[3];

/* verificacion */
if (md5(md5('autgencupon1011' . $id_curso)) != $hash) {
    echo "<script>alert('DENEGADO');location.href='$dominio';</script>";
    exit;
}


/* curso */
$rqauxc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' LIMIT 1 ");
$rqauxc2 = fetch($rqauxc1);
$nombre_curso = $rqauxc2['titulo'];

/* cupon */
$rqecd1 = query("SELECT * FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' ");
$rqecd2 = fetch($rqecd1);
$id_cupon = $rqecd2['id'];
$id_paquete_cupon = $rqecd2['id_paquete'];
$duracion_cupon = $rqecd2['duracion'];
$fecha_expiracion_cupon = $rqecd2['fecha_expiracion'];
$array_paquetes_infosicoes = array();
$array_paquetes_infosicoes[2] = 'PAQUETE PyME';
$array_paquetes_infosicoes[3] = 'PAQUETE BASICO';
$array_paquetes_infosicoes[4] = 'PAQUETE MEDIO';
$array_paquetes_infosicoes[5] = 'PAQUETE INTERMEDIO';
$array_paquetes_infosicoes[6] = 'PAQUETE EMPRESARIAL';
$array_paquetes_infosicoes[7] = 'PAQUETE COORPORATIVO';
$array_paquetes_infosicoes[10] = 'PAQUETE Consultor - BASICO';
$array_paquetes_infosicoes[11] = 'PAQUETE Consultor - GOLD';
$array_paquetes_infosicoes[12] = 'PAQUETE Consultor - PREMIUM';


/* proceso registro */
$rqdpr1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
$procreg = fetch($rqdpr1);
$monto_a_facturar = $procreg['monto_deposito'];
$id_emision_factura = (int)$procreg['id_emision_factura'];

$sw_cupon_emitido = false;
$sw_cupon_prev_emitido = false;

/* generar-cupon */
if (isset_post('generar-cupon')) {

    $ci = post('ci');

    /* participante */
    $rqdp1 = query("SELECT * FROM cursos_participantes WHERE ci='$ci' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
    if (num_rows($rqdp1) == 0) {
        $mensaje .= '<br><br><div class="alert alert-danger">
        <strong>ERROR</strong> el C.I. no corresponde a alg&uacute;n participante de este curso.
      </div>';
    } else {

        $participante = fetch($rqdp1);
        $id_participante = $participante['id'];
        $nombre_participante = $participante['nombres'] . ' ' . $participante['apellidos'];
        $correo_participante = $participante['correo'];
        $ci_participante = $participante['ci'];
        $id_proceso_registro = $participante['id_proceso_registro'];
        $id_departamento = $participante['id_departamento'];


        /* dosificacion de cupon */
        $cupones = obtiene_cupon($id_paquete_cupon, $duracion_cupon, $fecha_expiracion_cupon, 1);

        $array_cupones = explode(',', str_replace(',completo', '', $cupones));

        /* verificacion de emision anterior */
        $rqve1 = query("SELECT codigo FROM cursos_emisiones_cupones_infosicoes WHERE id_cupon='$id_cupon' AND id_curso='$id_curso' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
        if (num_rows($rqve1) > 0) {
            $rqve2 = fetch($rqve1);
            $codigo = $rqve2['codigo'];
            $mensaje .= '<br><br><div class="alert alert-info">
        <strong>AVISO</strong> este cup&oacute;n ya fu&eacute; emitido a este participante.
      </div>';
            $sw_cupon_prev_emitido = true;
        } else {
            $codigo = array_pop($array_cupones);
            $id_administrador = 99;

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

            logcursos('AUTO-GENERADO de CUPON [' . $codigo . ']', 'partipante-certificados', 'participante', $id_participante);

            $sw_cupon_emitido = true;

            $mensaje .= '<br><div class="alert alert-success">
            <strong>EXITO</strong> cup&oacute;n generado exitosamente.
        </div>';
        }
    }
}

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
                    <h3>GENERAR CUP&Oacute;N INFOSICOES</h3>
                </div>

                <div class="Titulo_texto1">
                    <?php
                    if ($sw_cupon_emitido) {
                    ?>
                        <div style="background: #ffefd3;padding: 20px;font-size: 12pt;text-align: justify;">
                            Cup&oacute;n generado
                        </div>

                        <table class="table table-striped table-bordered">
                            <tr>
                                <td style="padding: 15px;"><b>Curso:</b></td>
                                <td style="padding: 15px;"><?php echo $nombre_curso; ?></td>
                            </tr>
                            <tr>
                                <td style="padding: 15px;"><b>Cup&oacute;n:</b></td>
                                <td style="padding: 15px;">
                                    INFOSICOES
                                    <br>
                                    <?php echo $array_paquetes_infosicoes[$id_paquete_cupon]; ?>
                                    <br>
                                    <?php echo $duracion_cupon; ?> MESES
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 15px;">Receptor: </td>
                                <td style="padding: 15px;"><?php echo $nombre_participante; ?></td>
                            </tr>
                            <tr>
                                <td style="padding: 15px;">PDF: </td>
                                <td style="text-align: center;padding: 15px 0px;">
                                    <a href="<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/cupon-infosicoes.php?id_cupon=<?php echo $id_cupon; ?>&id_participante=<?php echo $id_participante; ?>&download=true" class="btn btn-default btn-lg">
                                        DESCARGAR CUP&Oacute;N
                                    </a>
                                </td>
                            </tr>
                        </table>
                    <?php
                    } elseif ($sw_cupon_prev_emitido) {
                    ?>
                        <div style="background: #ffefd3;padding: 20px;font-size: 12pt;text-align: justify;">
                            Cup&oacute;n generado
                        </div>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <td style="padding: 15px;"><b>Curso:</b></td>
                                <td style="padding: 15px;"><?php echo $nombre_curso; ?></td>
                            </tr>
                            <tr>
                                <td style="padding: 15px;"><b>Cup&oacute;n:</b></td>
                                <td style="padding: 15px;">
                                    INFOSICOES
                                    <br>
                                    <?php echo $array_paquetes_infosicoes[$id_paquete_cupon]; ?>
                                    <br>
                                    <?php echo $duracion_cupon; ?> MESES
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 15px;">Receptor: </td>
                                <td style="padding: 15px;"><?php echo $nombre_participante; ?></td>
                            </tr>
                            <tr>
                                <td style="padding: 15px;">PDF: </td>
                                <td style="text-align: center;padding: 15px 0px;">
                                    <a href="<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/cupon-infosicoes.php?id_cupon=<?php echo $id_cupon; ?>&id_participante=<?php echo $id_participante; ?>&download=true" class="btn btn-default btn-lg">
                                        DESCARGAR CUP&Oacute;N
                                    </a>
                                </td>
                            </tr>
                        </table>
                    <?php


                    } else {
                    ?>
                        <div style="background: #f3fbff;padding: 20px;font-size: 12pt;text-align: justify;border: 1px solid #e0e0e0;">
                            Hola <?php echo $nombre_participante; ?>
                            <br>
                            <br>
                            Desde aqu&iacute; puedes generar el cup&oacute;n Infosicoes asociado a tu curso, para ello llena cuidadosamente los datos del siguiente formulario:
                            <br>
                            <br>

                            <div style="text-align:center;padding: 20px 0px;">
                                <form action="" method="post">
                                    <table class="table table-bordered" style="background: #FFF;">
                                        <tr>
                                            <td style="padding: 15px;"><b>Curso:</b></td>
                                            <td style="padding: 15px;"><?php echo $nombre_curso; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 15px;"><b>Cup&oacute;n:</b></td>
                                            <td style="padding: 15px;">
                                                INFOSICOES
                                                <br>
                                                <?php echo $array_paquetes_infosicoes[$id_paquete_cupon]; ?>
                                                <br>
                                                <?php echo $duracion_cupon; ?> MESES
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 15px;"><b>N&uacute;mero de C.I.:</b></td>
                                            <td><input type="text" value="" class="form-control" name="ci" placeholder="Ingresa el CI con el que te registraste..." required="" autocomplete="off" /></td>
                                        </tr>
                                    </table>
                                    <br>
                                    <input type="submit" name="generar-cupon" value="GENERAR CUP&Oacute;N" class="btn btn-success" style="width: auto;border-radius: 5px;padding: 10px 20px;">
                                </form>
                            </div>
                        </div>
                    <?php

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

<?php

function obtiene_cupon($id_paquete, $duracion, $fecha_expiracion, $cnt_participantes)
{
    $cont = file_get_contents("https://www.infosicoes.com/contenido/paginas/procesos/externos/webservice.cursosbo.cupones.php?id_paquete=$id_paquete&duracion=$duracion&fecha_expiracion=$fecha_expiracion&cnt_participantes=$cnt_participantes");
    return $cont;
}
