<?php
/* data get */
$id_proceso_registro = $get[3];
$hash = $get[2];
if ($hash !== md5('idr-' . $id_proceso_registro)) {
    echo "<script>location.href='$dominio';</script>";exit;
}

/* proceso registro */
$rqdprp1 = query("SELECT id_curso,codigo FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
$rqdprp2 = fetch($rqdprp1);
$id_curso = $rqdprp2['id_curso'];
$codigo_de_registro = $rqdprp2['codigo'];

/* datos del curso */
$rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' AND estado IN (1,2) ORDER BY FIELD(estado,1,2),id DESC limit 1 ");
$curso = fetch($rq1);
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
        background: #d9faff;
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
                                    $rqdcl1 = query("SELECT nombres,apellidos FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ORDER BY id DESC limit 1 ");
                                    $rqdcl2 = fetch($rqdcl1);
                                    $nombre_cliente = $rqdcl2['nombres'] . ' ' . $rqdcl2['apellidos'];
                                    ?>
                                    <br>
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <td style='padding:5px;'>C&oacute;digo de registro:</td>
                                            <td style='padding:5px;'><?php echo $codigo_de_registro; ?></td>
                                        </tr>
                                        <tr>
                                            <td style='padding:5px;'>Participante:</td>
                                            <td style='padding:5px;'><?php echo $nombre_cliente; ?></td>
                                        </tr>
                                        <tr>
                                            <td style='padding:5px;'>Curso:</td>
                                            <td style='padding:5px;'><?php echo $curso['titulo']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style='padding:5px;'>Ficha:</td>
                                            <td style='padding:5px;'>
                                                <div class="text-center">
                                                    <br/>
                                                    <button class="btn btn-primary" onclick="window.open('<?php echo $dominio.encrypt('registro-participantes-curso/' . $id_proceso_registro); ?>.impresion', 'popup', 'width=700,height=500');
                                                            return false;"><i class="fa fa-print"></i> IMPRIMIR FICHA DE INSCRIPCI&Oacute;N</button>
                                                    <br/>
                                                    <br/>
                                                    <a href="<?php echo $dominio.encrypt('registro-participantes-curso/' . $id_proceso_registro . '/pdf'); ?>.impresion" class="btn btn-danger"><i class="fa fa-print"></i> DESCARGAR EN PDF</a>
                                                    <br/>
                                                    <br/>
                                                    &nbsp;
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="panel panel-info">
                                <?php
                                if((int)$curso['sw_askfactura']==1){
                                    ?>
                                    <p>
                                        En caso de existir alg&uacute;n error en el reporte de pago y la imagen no este clara o sea erronea puedes volver a subir la imagen/foto del reporte de dep&oacute;sito o transferencia desde el siguiente enlace: <a href="registro-curso-modificar/<?php echo md5('idr-' . $id_proceso_registro); ?>/<?php echo $id_proceso_registro; ?>.html" style="color:#004eff;text-decoration: underline;">modificar reporte de pago</a>, puede llenar los datos de facturaci&oacute;n en el siguiente enlace: <a href="registro-curso-facturacion/<?php echo md5('idr-' . $id_proceso_registro); ?>/<?php echo $id_proceso_registro; ?>.html" style="color:#004eff;text-decoration: underline;">datos de facturaci&oacute;n</a>.
                                    </p>
                                    <?php
                                }else{
                                    ?>
                                    <p>
                                        En caso de existir alg&uacute;n error en el reporte de pago y la imagen no este clara o sea erronea puedes volver a subir la imagen/foto del reporte de dep&oacute;sito o transferencia desde el siguiente enlace: <a href="registro-curso-modificar/<?php echo md5('idr-' . $id_proceso_registro); ?>/<?php echo $id_proceso_registro; ?>.html" style="color:#004eff;text-decoration: underline;">modificar reporte de pago</a>.
                                    </p>
                                    <?php
                                }
                                ?>
                            </div>

                            <br/>
                            <br/>
                    </div>
                    <hr/>
                </div>
            </div>
        </div>



    </section>
</div>                     



<script>
    function habilitar_participantes(nro) {

        if (nro > 1) {
            $("#correo_part").css("display", "block");
            $("#cel_part").css("display", "block");
        } else {
            $("#correo_part").css("display", "none");
            $("#cel_part").css("display", "none");
        }

        for (var i = 1; i <= 7; i++) {

            if (i <= nro) {
                $("#box-participante-" + i).css("display", "block");
            } else {
                $("#box-participante-" + i).css("display", "none");
            }
        }
    }
</script>

<script>
    function checkParticipante(dat, p) {
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.registro-curso.checkParticipante.php',
            data: {dat: dat},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                var data_json_parsed = JSON.parse(data);
                if (data_json_parsed['estado'] === 1) {
                    $("#nombres_p" + p).val(data_json_parsed['nombres']);
                    $("#apellidos_p" + p).val(data_json_parsed['apellidos']);
                    $("#correo_p" + p).val(data_json_parsed['correo']);
                    $("#prefijo_p" + p).val(data_json_parsed['prefijo']).change();
                }
            }
        });
    }
</script>



<?php

function fecha_curso($dat) {
    $ar1 = explode("-", $dat);
    $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $ar1[2] . " de " . $array_meses[(int) $ar1[1]];
}
