<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* verificacion de sesion */
if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}
/* manejo de parametros */
$data = 'nonedata/' . post('data');
$get = explode('/', $data);
if ($get[count($get) - 1] == '') {
    array_splice($get, (count($get) - 1), 1);
}
/* parametros post */
$postdata = post('postdata');
if ($postdata !== '') {
    $_POST = json_decode(base64_decode($postdata), true);
}
?>

<!-- CONTENIDO DE PAGINA -->

<?php
$mensaje = "";

/* id de rel_cursoonlinecourse */
$id_rel_cursoonlinecourse = 0;
if (isset($get[2])) {
    $id_rel_cursoonlinecourse = (int) $get[2];
}

/* sw de habilitacion de procesos */
$rqvhc1 = query("SELECT estado FROM cursos_rel_cursoonlinecourse WHERE id='$id_rel_cursoonlinecourse' ORDER BY id DESC limit 1 ");
$rqvhc2 = fetch($rqvhc1);
if ($rqvhc2['estado'] == '1') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}

/* datos del curso virtual */
$rqcv1 = query("SELECT * FROM cursos_onlinecourse WHERE id=(SELECT id_onlinecourse FROM cursos_rel_cursoonlinecourse WHERE id='$id_rel_cursoonlinecourse') ");
$rqcv2 = fetch($rqcv1);
$id_onlinecourse = $rqcv2['id'];

$resultado1 = query("SELECT * FROM cursos_usuarios WHERE id IN (SELECT id_usuario FROM cursos_participantes WHERE id_curso=(SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id='$id_rel_cursoonlinecourse' ) ) $qr_busqueda ORDER BY id DESC ");
/* res aux numeracion */
$resultado_aux_numeracion1 = query("SELECT numeracion FROM cursos_participantes WHERE id_curso='$id_rel_cursoonlinecourse' AND estado='1' $qr_busqueda ORDER BY numeracion DESC LIMIT 1 ");
$resultado_aux_numeracion2 = fetch($resultado_aux_numeracion1);
$numeracion_por_participantes = $resultado_aux_numeracion2['numeracion'];

/* datos del curso */
$rqc1 = query("SELECT id,titulo,titulo_identificador,fecha,imagen,costo,costo2,costo3,costo_e,costo_e2,id_certificado,id_certificado_2,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado,inicio_numeracion FROM cursos c WHERE id=(SELECT id_curso FROM cursos_rel_cursoonlinecourse WHERE id='$id_rel_cursoonlinecourse') ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$id_curso = $rqc2['id'];
$nombre_del_curso = $rqc2['titulo'];
$inicio_numeracion = $rqc2['inicio_numeracion'];
$titulo_identificador_del_curso = $rqc2['titulo_identificador'];
$fecha_del_curso = $rqc2['fecha'];
$codigo_de_certificado_del_curso = $rqc2['codigo_certificado'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
if ($rqc2['imagen'] !== '') {
    $url_imagen_del_curso = $dominio_www."contenido/imagenes/paginas/" . $rqc2['imagen'];
} else {
    $url_imagen_del_curso = "https://www.infosicoes.com/images/banner-cursos.png.size=4.img";
}
$costo_curso = $rqc2['costo'];
$costo2_curso = $rqc2['costo2'];
$costo3_curso = $rqc2['costo3'];
$costoe_curso = $rqc2['costo_e'];
$costoe2_curso = $rqc2['costo_e2'];

if ($numeracion_por_participantes > $inicio_numeracion) {
    $inicio_numeracion = (int) $numeracion_por_participantes + 1;
}


$cnt = num_rows($resultado1);
?>
<style>
    .modal-header{
        background: #00789f;
    }
    .modal-title{
        color:#FFF;
    }
    .modal-footer .btn-default{
        background: #00789f;
        color: #FFF;
    }
</style>
<script>
    var VAR_modo_de_pago = 'todos';
</script>


<div class="hidden-lg">
    <?php
    include_once '../items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once '../items/item.enlaces_top.php';
            ?>
            <li><a <?php echo loadpage('inicio'); ?>>Panel principal</a></li>
            <li><a <?php echo loadpage('cursos-virtuales-listar'); ?>>Cursos virtuales</a></li>
            <li class="active">PANEL 2 - Seguimiento de participantes</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <div class="hidden-sm">
                <a href="cursos-participantes/<?php echo $id_curso; ?>.adm" class="btn btn-sm btn-info active"><i class="fa fa-list"></i> PANEL 1</a>
            </div>
            <div class="hidden-md">
                <a href="cursos-participantes/<?php echo $id_curso; ?>.adm" class="btn btn-sm btn-info active" title="PANEL 1"><i class="fa fa-list"></i></a>
            </div>
        </div>
        <h4 class="page-header"> SEGUIMIENTO DE PARTICIPANTES | CURSO VIRTUAL <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h4>
        <blockquote class="page-information hidden">
            <p>
                Seguimiento de participantes
            </p>
        </blockquote>
    </div>
</div>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-12NOT">
        <div class="panelNOT">

            <div class="row">
                <div class="col-md-6">
                    <h4 style="margin: 0px;">
                        <i class='btn btn-success active'><?php echo date("d  M  y", strtotime($fecha_del_curso)); ?></i> | 
                        <?php echo $nombre_del_curso; ?>
                    </h4>
                </div>
                <div class="col-md-6">
                    <form action="" method="post">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Buscador: </span>
                            <input type="text" name="input-buscador" value="" id="inputbuscador" class="form-control" placeholder="Nombres / Apellidos / Celular / Correo / Codigo de registro ..."/>
                        </div>
                    </form>
                </div>
            </div>

            <hr/>

            <?php
            if (num_rows($resultado1) == 0) {
                echo "<p>No se registraron participantes para este curso</p>";
            }
            ?>

            <div class="panel-bodyNOT" style="padding-top: 0px;">


                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">#</th>
                                <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">Nombre</th>
                                <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">Apellidos</th>
                                <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">Lecciones avanzadas</th>
                                <th class="visible-lgNOT" style="padding-top: 2px;padding-bottom: 2px;">Evaluaciones realizadas</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $sw_existencia_certificado_uno = false;
                            $sw_existencia_certificado_dos = false;
                            $sw_existencia_facturas = false;

                            if (num_rows($resultado1) == 0) {
                                echo "<tr><td colspan='11'>No existen participantes registrados.</td></tr>";
                            }

                            while ($participante = fetch($resultado1)) {

                                /* datos de registro */
                                $rqrp1 = query("SELECT id,codigo,fecha_registro,celular_contacto,correo_contacto,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_cobro_khipu,sw_pago_enviado,id_administrador FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
                                $data_registro = fetch($rqrp1);
                                $id_proceso_de_registro = $data_registro['id'];
                                $codigo_de_registro = $data_registro['codigo'];
                                $fecha_de_registro = $data_registro['fecha_registro'];
                                $celular_de_registro = $data_registro['celular_contacto'];
                                $correo_de_registro = $data_registro['correo_contacto'];
                                $nro_participantes_de_registro = $data_registro['cnt_participantes'];
                                $id_emision_factura = $data_registro['id_emision_factura'];

                                $monto_de_pago = $data_registro['monto_deposito'];
                                $imagen_de_deposito = $data_registro['imagen_deposito'];

                                $razon_social_de_registro = $data_registro['razon_social'];
                                $nit_de_registro = $data_registro['nit'];

                                $sw_pago_enviado = $data_registro['sw_pago_enviado'];
                                $id_cobro_khipu = $data_registro['id_cobro_khipu'];

                                $aux_idsalmacenador .= ',' . $participante['id'];

                                $tr_class = '';
                                $text_msj = '';
                                if (strtotime(date("Y-m-d", strtotime($data_registro['fecha_registro']))) > strtotime($fecha_curso)) {
                                    $tr_class = 'reg-fuerafecha';
                                    $text_msj = '<br/><i style="color:red;font-size:7pt;">Fuera de fecha</i>';
                                }
                                ?>
                                <tr id="ajaxbox-tr-participante-<?php echo $participante['id']; ?>" class="<?php echo $tr_class; ?>">
                                    <td class="visible-lgNOT">
                                        <?php echo $cnt--; ?>
                                        <br/>
                                        <br/>
                                        <b class="btn btn-default btn-xs" onclick="historial_participante('<?php echo $participante['id']; ?>');" data-toggle="modal" data-target="#MODAL-historial_participante">
                                            <i class="fa fa-list" style="color:#8f8f8f;"></i>
                                        </b>
                                    </td>
                                    <td class="visible-lgNOT" onclick="check_participante('<?php echo $participante['id']; ?>');">
                                        <?php
                                        echo trim($participante['nombres']);
                                        ?>
                                        <br/>
                                        <b style="font-size:7pt;color:#2180be;">No. <?php echo $participante['numeracion']; ?></b>
                                    </td>
                                    <td class="visible-lgNOT" onclick="check_participante('<?php echo $participante['id']; ?>');">
                                        <?php
                                        echo trim($participante['apellidos']);
                                        ?>
                                        <br/>
                                        <b style="font-size:7pt;color:#1b6596;"><?php echo $participante['ci'] . ' ' . $participante['ci_expedido']; ?></b>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        $rqdavl1 = query("SELECT l.titulo,l.minutos,a.segundos FROM cursos_onlinecourse_lec_avance a INNER JOIN cursos_onlinecourse_lecciones l ON a.id_onlinecourse_leccion=l.id WHERE a.id_usuario='" . $participante['id'] . "' AND l.id IN (select id from cursos_onlinecourse_lecciones where id_onlinecourse='$id_onlinecourse') ");
                                        if (num_rows($rqdavl1) == 0) {
                                            echo "No se registro avance en lecciones";
                                        }
                                        while ($rqdavl2 = fetch($rqdavl1)) {
                                            $t = $rqdavl2['minutos'] * 60;
                                            $s = $rqdavl2['segundos'];
                                            $p = round($s * 100 / $t);
                                            if ($p > 100) {
                                                $p = 100;
                                                $rqdavl2['segundos'] = $t;
                                            }
                                            ?>
                                            <?php echo $rqdavl2['titulo']; ?>
                                            <span class="pull-right"><?php echo round(($rqdavl2['segundos']) / 60, 2); ?>/<?php echo $rqdavl2['minutos']; ?> minutos</span>
                                            <br/>
                                            <div class="progress" style="background: #d2d8dc;">
                                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $p; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $p; ?>%;">
                                                    <?php echo $p; ?>% Completo (terminado)
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        $rqdael1 = query("SELECT * FROM cursos_onlinecourse_evaluaciones WHERE id_usuario='" . $participante['id'] . "' AND id_onlinecourse='$id_onlinecourse' ");
                                        if (num_rows($rqdael1) == 0) {
                                            echo "No se registraron evaluaciones";
                                        }
                                        while ($rqdavl2 = fetch($rqdael1)) {
                                            ?>
                                            -&nbsp;
                                            <b class="btn btn-sm btn-success active"><?php echo round(($rqdavl2['total_correctas'] * 100) / $rqdavl2['total_preguntas'], 1); ?>%</b>
                                            &nbsp;|&nbsp;
                                            <b class="label label-primary"><?php echo $rqdavl2['total_correctas'] . '/' . $rqdavl2['total_preguntas']; ?> respuestas correctas</b>
                                            &nbsp;|&nbsp;
                                            Fecha: <?php echo date("d/m/Y H:i", strtotime($rqdavl2['fecha'])); ?>
                                            <br/>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
</div>




<!-- MODALS -->


<!-- Modal Datos de registro -->
<div id="MODAL-datos-registro" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DATOS DE REGISTRO</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: DATOS DE REGISTRO -->
                <div id="ajaxloading-datos_registro"></div>
                <div id="ajaxbox-datos_registro">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Datos de registro -->


<!-- MODAL historial_participante -->
<div id="MODAL-historial_participante" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">LOG DE MOVIMIENTOS</h4>
            </div>
            <div class="modal-body">

                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-historial_participante"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>






<!--FUNCIONES AJAX DE CONTEXTO-->
<script>
    function datos_registro(id_participante) {
        $("#ajaxbox-datos_registro").html("");
        $("#ajaxloading-datos_registro").html(text__loading_uno);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.datos_registro.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-datos_registro").html("");
                $("#ajaxbox-datos_registro").html(data);
            }
        });
    }
    function elimina_participante_p1(id_participante) {
        $("#ajaxbox-elimina_participante_p1").html("");
        $("#ajaxloading-elimina_participante_p1").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.elimina_participante_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-elimina_participante_p1").html("");
                $("#ajaxbox-elimina_participante_p1").html(data);
            }
        });
    }
    function elimina_participante_p2(id_participante) {
        $("#ajaxbox-elimina_participante_p2").html("");
        $("#ajaxloading-elimina_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.elimina_participante_p2.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-elimina_participante_p2").html("");
                $("#ajaxbox-elimina_participante_p2").html(data);
                lista_participantes(<?php echo $id_rel_cursoonlinecourse; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_rel_cursoonlinecourse; ?>, 0);
            }
        });
    }
    function habilita_participante_p1(id_participante) {
        $("#ajaxbox-habilita_participante_p1").html("");
        $("#ajaxloading-habilita_participante_p1").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-habilita_participante_p1").html("");
                $("#ajaxbox-habilita_participante_p1").html(data);
            }
        });
    }
    function habilita_participante_p2(id_participante) {
        $("#ajaxbox-habilita_participante_p2").html("");
        $("#ajaxloading-habilita_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_p2.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-habilita_participante_p2").html("");
                $("#ajaxbox-habilita_participante_p2").html(data);
                lista_participantes(<?php echo $id_rel_cursoonlinecourse; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_rel_cursoonlinecourse; ?>, 0);
            }
        });
    }
</script>


<!-- historial_participante -->
<script>
    function historial_participante(id_participante) {
        $("#AJAXCONTENT-historial_participante").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.historial_participante.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-historial_participante").html(data);
            }
        });
    }
</script>




<?php
function my_date_curso($dat) {
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]] . " " . substr($ar1[0], 2, 2);
    }
}
