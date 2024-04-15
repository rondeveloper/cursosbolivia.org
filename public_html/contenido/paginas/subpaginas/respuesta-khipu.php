<?php
/* data */
$data = $get[2];
$data_decript = decrypt($data);

$sw_transaccion_aprobado = false;
$sw_transaccion_cancelado = false;

if (strpos("---" . $data_decript, 'transaccion-cancelado-id') > 0) {
    $sw_transaccion_cancelado = true;
    $ardata1 = explode('transaccion-cancelado-id', $data_decript);
    $ardata2 = explode('-', $ardata1[1]);
    $id_proceso_registro = (int) $ardata2[0];
}

if (strpos("---" . $data_decript, 'transaccion-aprobado-id') > 0) {
    $sw_transaccion_aprobado = true;
    $ardata1 = explode('transaccion-aprobado-id', $data_decript);
    $ardata2 = explode('-', $ardata1[1]);
    $id_proceso_registro = (int) $ardata2[0];
}

if ($sw_transaccion_aprobado) {
    //echo "transaccion aprobada";
} elseif ($sw_transaccion_cancelado) {
    //echo "transaccion cancelada";
} else {
    envio_email("brayan.desteco@gmail.com", "Error de dato en retorno de pago con Khipu", "------>" . $data . "***" . decrypt($data));
    //echo "Error";
    echo "<script>location.href='$dominio';</script>";
    exit;
}


$rqvr1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
if (num_rows($rqvr1) == 0) {
    envio_email("brayan.desteco@gmail.com", "Error de dato en retorno de pago con Khipu [no encontrado id de proceso $id_proceso_registro]", "------>" . $data . "***" . decrypt($data));
    echo "<script>location.href='$dominio';</script>";
    exit;
}

/* datos del proceso de registro */
$rqvr2 = fetch($rqvr1);
$id_curso = $rqvr2['id_curso'];
$id_cobro_khipu = $rqvr2['id_cobro_khipu'];
$codigo_de_registro = $rqvr2['codigo'];
$correo_proceso_registro = $rqvr2['correo_contacto'];

/* datos de curso */
$rqd1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$enlace_impresion_registro_participantes = 'registro-participantes-curso';
if((int)$id_curso==0){
    $id_curso_aux = $rqvr2['id_grupo'];
    $rqd1 = query("SELECT * FROM cursos_agrupaciones WHERE id='$id_curso_aux' ORDER BY id DESC limit 1 ");
    $enlace_impresion_registro_participantes = 'registro-participantes-grupo';
}
$curso = fetch($rqd1);
$titulo_curso = $curso['titulo'];

/* enlace_khipu */
$rqdk1 = query("SELECT payment_id FROM khipu_cobros WHERE id='$id_cobro_khipu' LIMIT 1 ");
$rqdk2 = fetch($rqdk1);
$enlace_khipu = $rqdk2['payment_id'];

?>


<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">

<div class="box_seccion_a" style="width:100%;">
    <div class="seccion_a">
        <div class="contenido_seccion white-content-one">



            <div class='row'>
                <div class=''>
                    <div class=''>

                        <div class="areaRegistro1 ftb-registro-5">
                            <div class="row">
                                <?php
                                include_once 'contenido/paginas/items/item.m.datos_curso.php';
                                ?>
                            </div>
                            <div>
                                <?php
                                if ($sw_transaccion_aprobado) {
                                    ?>
                                    <div>
                                        <h3 style="background:#DDD;color:#444;margin-top: 20px;">INSCRIPCION FINALIZADA CORRECTAMENTE</h3>
                                        <div class="alert alert-success">
                                            <strong>Exito!</strong> El pago fue ejecutado exitosamente.
                                        </div>
                                        <div>
                                            <?php
                                            $rqrpc1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
                                            $rqrpc2 = fetch($rqrpc1);
                                            $codigo_registro = $rqrpc2['codigo'];
                                            $cnt_participantes_registro = $rqrpc2['cnt_participantes'];
                                            $razon_social_registro = $rqrpc2['razon_social'];
                                            $nit_registro = $rqrpc2['nit'];
                                            $fecha_registro = $rqrpc2['fecha_registro'];
                                            $monto_deposito_registro = $rqrpc2['monto_deposito'];
                                            $id_curso = $rqrpc2['id_curso'];
                                            $rqc1 = query("SELECT *,(select nombre from departamentos where id=c.id_ciudad limit 1)ciudad FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
                                            if((int)$id_curso==0){
                                                $id_curso_aux = $rqrpc2['id_grupo'];
                                                $rqc1 = query("SELECT *,(select nombre from departamentos where id=c.id_ciudad limit 1)ciudad FROM cursos_agrupaciones c WHERE id='$id_curso_aux' ORDER BY id DESC limit 1 ");
                                            }
                                            $rqc2 = fetch($rqc1);
                                            $nombre_curso = $rqc2['titulo'];
                                            $url_curso = $dominio . $rqc2['titulo_identificador'] . ".html";
                                            $lugar_curso = $rqc2['lugar'];
                                            $fecha_curso = $rqc2['fecha'];
                                            $horario_curso = $rqc2['horarios'];
                                            $ciudad_curso = $rqc2['ciudad'];
                                            ?>
                                            <style>
                                                .aux-css-static-1{
                                                    padding:10px 30px;
                                                }
                                                @media screen and (max-width: 650px){
                                                    .aux-css-static-1{
                                                        padding:5px 0px;
                                                    }
                                                    .areaRegistro1 td {
                                                        float: left;
                                                        width: 50%;
                                                        text-align: left !important;
                                                    }
                                                }
                                            </style>

                                            <div style="border:1px dashed #DDD;overflow: hidden;">
                                                <div>
                                                    <p style="text-align: left;"><span style="font-size: 12pt; color: #ff0000;"><strong><?php echo $nombre_curso; ?><br></strong></span></p>
                                                    <p style="text-align: left;">
                                                        <span style="font-size: 10pt;text-align: justify; color: #000000;">
                                                            Proceso de inscripci&oacute;n para el curso '<?php echo $nombre_curso; ?>' a llevarse a cabo 
                                                            en fecha <?php echo $fecha_curso; ?> en la ciudad de <?php echo $ciudad_curso; ?>.
                                                            <br/>
                                                            A continuaci&oacute;n se muestran detalles de la inscripci&oacute;n. 
                                                        </span>
                                                        <br/>
                                                    </p>

                                                    <div class="panel panel-info">
                                                        <div class="panel-heading">
                                                            FICHA DE INSCRIPCI&Oacute;N
                                                        </div>
                                                        <div class="panel-body text-center">
                                                            <p style="text-align: left;">
                                                                <span style="font-size: 10pt;text-align: justify; color: #000000;">
                                                                    Para poder hacer el ingreso el d&iacute;a del curso es necesario llevar esta ficha previamente impresa junto con el comprobante de pago emitido por khipu.
                                                                </span>
                                                            </p>
                                                            <br/>
                                                            <button class="btn btn-primary" onclick="window.open('<?php echo $dominio.encrypt($enlace_impresion_registro_participantes.'/' . $id_proceso_registro); ?>.impresion', 'popup', 'width=700,height=500');
                                                                    return false;"><i class="fa fa-print"></i> IMPRIMIR FICHA DE INSCRIPCI&Oacute;N</button>
                                                            <br/>
                                                            <br/>
                                                            <a href="<?php echo $dominio.encrypt($enlace_impresion_registro_participantes.'/' . $id_proceso_registro . '/pdf'); ?>.impresion" class="btn btn-danger"><i class="fa fa-print"></i> DESCARGAR EN PDF</a>
                                                            <br/>

                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                    
                                                    <br/>
                                                    <p style="text-align: left;">
                                                        <span style="font-size: 10pt;text-align: justify; color: #000000;">
                                                            Muchas gracias por realizar tu inscripci&oacute;n.
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        &nbsp;
                                        <br/>
                                        <button class="btn btn-primary" onclick="window.open('<?php echo $dominio.encrypt($enlace_impresion_registro_participantes.'/' . $id_proceso_registro); ?>.impresion', 'popup', 'width=700,height=500');
                                                return false;"><i class="fa fa-print"></i> IMPRIMIR FICHA DE INSCRIPCI&Oacute;N</button>
                                        <br/>
                                        <br/>
                                        <a href="<?php echo $dominio.encrypt($enlace_impresion_registro_participantes.'/' . $id_proceso_registro . '/pdf'); ?>.impresion" class="btn btn-danger" ><i class="fa fa-print"></i> EXPORTAR A PDF</a>
                                        <br/>
                                        <br/>
                                        &nbsp;
                                    </div>

                                    <?php
                                } elseif ($sw_transaccion_cancelado) {
                                    ?>
                                    <div>
                                        <h3 style="background:#DDD;color:#444;margin-top: 20px;padding: 10px;">PROCESO DE INSCRIPCI&Oacute;N</h3>
                                        <div class="alert alert-warning">
                                            <strong>Aviso!</strong> no se completo el pago del curso mediante tarjeta, puede volver a intentarlo a traves del siguiente enlace: &nbsp;&nbsp;&nbsp;&nbsp; <br/><br/>
                                            <a href="registro-curso-p5c/<?php echo md5('idr-' . $id_proceso_registro); ?>/<?php echo $id_proceso_registro; ?>/update.html" style="color: #ffffff;
    text-decoration: underline;
    background: #54bd54;
    padding: 5px 20px;
    border-radius: 5px;">
                                                                REALIZAR PAGO
                                                            </a>
                                        </div>
                                        <br>
                                        <br>
                                        <p style="margin: 20px;">
                                                            Puede realizar el pago o subir la imagen/foto del reporte de dep&oacute;sito / transferencia desde el siguiente enlace: 
                                                            <a href="registro-curso-p5c/<?php echo md5('idr-' . $id_proceso_registro); ?>/<?php echo $id_proceso_registro; ?>/update.html" style="color:#004eff;text-decoration: underline;">
                                                                REALIZAR PAGO
                                                            </a>.
                                                        </p>
                                                        <br>
                                                        <br>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
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

            <div class="clear">.</div>
        </div>
    </div>
</div>

</section>
</div> 

<?php

function fecha_curso($dat) {
    $ar1 = explode("-", $dat);
    $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $ar1[2] . " de " . $array_meses[(int) $ar1[1]];
}
?>