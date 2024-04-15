<?php
$mensaje = '';

$id_proceso_registro = $get[3];
$hash = $get[2];
if ($hash != md5('idr-' . $id_proceso_registro)) {
    echo "<script>location.href='$dominio';</script>";
    exit;
}

if (isset_post('inscripcion')) {
    $razon_social = post('razon_social');
    $nit = post('nit');
    query("UPDATE cursos_proceso_registro SET razon_social='$razon_social',nit='$nit' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
    $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> los datos se enviaron correctamente.
</div>';
}

/* proceso registro */
$rqpr1 = query("SELECT id_curso,razon_social,nit FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
$rqpr2 = fetch($rqpr1);

/* datos de formulario post */
$id_curso = $rqpr2['id_curso'];
$razon_social = $rqpr2['razon_social'];
$nit = $rqpr2['nit'];

/* datos del curso */
$rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
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
                        <form action="" method="post">
                            <div class="row">
                                <?php
                                include_once 'contenido/paginas/items/item.m.datos_curso.php';
                                ?>
                            </div>
                            <?php echo $mensaje; ?>
                            <h3 class="titulo-pagreg">DATOS DE FACTURACI&Oacute;N</h3>
                            <p>
                                Ingresa los datos de facturaci&oacute;n:
                            </p>
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <table class="table table-bordered table-striped table-hover">
                                        <tr>
                                            <td>
                                                <b>Factura a nombre de:</b>
                                            </td>
                                            <td>
                                                <input class="form-control myinput" type='text' size='40' name='razon_social' placeholder="Factura a nombre de..." value="<?php echo $razon_social; ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>N&uacute;mero NIT:</b>
                                            </td>
                                            <td>
                                                <input class="form-control myinput" type='text' size='40' name='nit' placeholder="NIT..." value="<?php echo $nit; ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <br>
                                                <input type="submit" class="btn btn-success" value='ENVIAR DATOS' style="color:#FFF;" name='inscripcion'/>
                                                <br>
                                                &nbsp;
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

                            <br/>

                        </form>

                    </div>

                </div>
            </div>
        </div>

        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>


    </section>
</div>      



<?php

function fecha_curso($dat) {
    $ar1 = explode("-", $dat);
    $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $ar1[2] . " de " . $array_meses[(int) $ar1[1]];
}
