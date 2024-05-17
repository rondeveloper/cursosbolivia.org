<?php

if (!isset_post('id_proceso_registro')) {
    echo "<script>alert('ERROR');history.back();</script>";
    exit;
}

/* datos del curso */
$id_proceso_registro = post('id_proceso_registro');

/* data proceso registro */
$rqdpr1 = query("SELECT id_curso,monto_deposito FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
$rqdpr2 = fetch($rqdpr1);
$id_curso = $rqdpr2['id_curso'];
$monto_deposito = $rqdpr2['monto_deposito'];

/* curso */
$rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY estado DESC,id DESC limit 1 ");
$curso = fetch($rq1);

/* participante */
$rqddprt1 = query("SELECT id FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ORDER BY id DESC limit 1 ");
$rqddprt2 = fetch($rqddprt1);
$id_participante = $rqddprt2['id'];

/* codigo de registro */
$codigo_de_registro = "R00$id_proceso_registro";


?>

<style>
    .titulo-pagreg{
        background: #DDD;
        color: #444;
        margin-top: 20px;
        padding: 7px 0px;
        text-align: center;
        border-radius: 7px;
        border: 1px solid #bfbfbf;
    }
    .link-set-fpay{
        background: #46d023 !important;
    }
    .myinput{
        background: #dbffd9;
        padding: 10px 20px;
        height: auto;
        border-radius: 10px;
    }
</style>
<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">

        <div class="box_seccion_a" style="width:100%;">
            <div class="seccion_a">
                <div class="contenido_seccion white-content-one">

                    <div class="areaRegistro1 ftb-registro-5">
                        <div class="row">
                            <?php
                            include_once 'contenido/paginas/items/item.m.datos_curso.php';
                            ?>
                        </div>
                        <h3 class="titulo-pagreg">FICHA DE INSCRIPCI&Oacute;N</h3>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <?php
                                $rqdcl1 = query("SELECT nombres,apellidos,ci,prefijo,correo,celular,id_departamento FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ORDER BY id DESC limit 1 ");
                                $rqdcl2 = fetch($rqdcl1);
                                $nombres_cliente = $rqdcl2['nombres'];
                                $apellidos_cliente = $rqdcl2['apellidos'];
                                $ci_cliente = $rqdcl2['ci'];
                                $correo_cliente = $rqdcl2['correo'];
                                $celular_cliente = $rqdcl2['celular'];
                                $prefijo_cliente = $rqdcl2['prefijo'];
                                $id_departamento = $rqdcl2['id_departamento'];
                                $nombre_cliente = $rqdcl2['nombres'] . ' ' . $rqdcl2['apellidos'];

                                /* datos de registro */
                                $rqrp1 = query("SELECT codigo,fecha_registro,celular_contacto,correo_contacto,id_modo_pago,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_administrador,sw_pago_enviado,id_cobro_khipu,paydata_fecha,paydata_id_administrador FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
                                $data_registro = fetch($rqrp1);
                                $fecha_de_registro = $data_registro['fecha_registro'];
                                $monto_deposito = $data_registro['monto_deposito'];

                                $rqdc1 = query("SELECT * FROM departamentos WHERE id='$id_departamento' ORDER BY id DESC limit 1 ");
                                $departamento = fetch($rqdc1);
                                $nombre_departamento = $departamento['nombre'];

                                if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
                                    $ip_registro = $_SERVER['HTTP_X_FORWARDED_FOR'];
                                } else {
                                    $ip_registro = $_SERVER['REMOTE_ADDR'];
                                }
                                ?>
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <td style='padding:5px;'>C&oacute;digo de registro:</td>
                                        <td style='padding:5px;'><?php echo $codigo_de_registro; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>C.I.:</td>
                                        <td style='padding:5px;'><?php echo $ci_cliente; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>Nombres:</td>
                                        <td style='padding:5px;'><?php echo $nombres_cliente; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>Apellidos:</td>
                                        <td style='padding:5px;'><?php echo $apellidos_cliente; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>Prefijo:</td>
                                        <td style='padding:5px;'><?php echo $prefijo_cliente; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>Departamento:</td>
                                        <td style='padding:5px;'><?php echo $nombre_departamento; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>Correo:</td>
                                        <td style='padding:5px;'><?php echo $correo_cliente; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>Celular:</td>
                                        <td style='padding:5px;'><?php echo $celular_cliente; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;'>Curso:</td>
                                        <td style='padding:5px;'><?php echo $curso['titulo']; ?></td>
                                    </tr>
                                    <tr>
                                    <td style='padding:5px;'>Total BS:</td>
                                        <td style='padding:5px;'><?php echo (int)$monto_deposito; ?></td>
                                    </tr>
                                    <td style='padding:5px;'>Fecha:</td>
                                        <td style='padding:5px;'><?php echo fecha_curso_D_d_m($fecha_de_registro); ?></td>
                                    </tr>
                                    <td style='padding:5px;'>IP:</td>
                                        <td style='padding:5px;'><?php echo $ip_registro; ?></td>
                                    </tr>
                                    <tr>
                                        <td style='padding:5px;' colspan="2">
                                            <div class="text-center" style="padding: 20px;">
                                                <br/>
                                                <br/>
                                                
                                                <div class="alert alert-success">
                                                    GRACIAS POR REALIZAR TU COMPRA
                                                </div>

                                                <br/>
                                                &nbsp;
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <br/>
                        <br/>

                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <?php echo $___nombre_del_sitio; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
