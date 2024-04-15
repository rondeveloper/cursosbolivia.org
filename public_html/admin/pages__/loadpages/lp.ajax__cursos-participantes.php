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

/* id de curso */
$id_curso = 0;
if (isset($get[2])) {
    $id_curso = (int) $get[2];
}

/* id de turno */
$id_turno_curso = 0;
$qr_turno = '';
if (isset($get[3]) && $get[3]!= 'no-turn' ) {
    $id_turno_curso = (int) $get[3];
    $qr_turno = " AND id_turno='$id_turno_curso' ";
}

/* filtro id especifico */
if (isset($get[4]) && $get[4]!= 'no-id' ) {
    $id_part_especifico = (int) $get[3];
}

/* filtro con/sin pago */
$modo_de_pago = 'todos';
if (isset($get[5])) {
    $modo_de_pago = $get[5];
}

/* sw de habilitacion de procesos */
$rqvhc1 = query("SELECT estado FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqvhc2 = fetch($rqvhc1);
if ($rqvhc2['estado'] == '1' || $rqvhc2['estado'] == '2') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}

$resultado1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' $qr_busqueda $qr_turno ORDER BY id DESC ");
/* res aux numeracion */
$resultado_aux_numeracion1 = query("SELECT numeracion FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' $qr_busqueda $qr_turno ORDER BY numeracion DESC LIMIT 1 ");
$resultado_aux_numeracion2 = fetch($resultado_aux_numeracion1);
$numeracion_por_participantes = $resultado_aux_numeracion2['numeracion'];

/* datos del curso */
$rqc1 = query("SELECT titulo,titulo_identificador,fecha,fecha2,fecha3,imagen,costo,costo2,costo3,sw_fecha2,sw_fecha3,fecha_e,sw_fecha_e,costo_e,costo_e2,id_certificado,id_certificado_2,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado,inicio_numeracion,id_modalidad,c.horarios FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$nombre_del_curso = $rqc2['titulo'];
$inicio_numeracion = $rqc2['inicio_numeracion'];
$titulo_identificador_del_curso = $rqc2['titulo_identificador'];
$fecha_del_curso = $rqc2['fecha'];
$codigo_de_certificado_del_curso = $rqc2['codigo_certificado'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
$url_imagen_del_curso = $dominio_www."contenido/imagenes/paginas/" . $rqc2['imagen'];

$costo_curso = $rqc2['costo'];
$costo2_curso = $rqc2['costo2'];
$costo3_curso = $rqc2['costo3'];
$costoe_curso = $rqc2['costo_e'];
$costoe2_curso = $rqc2['costo_e2'];
$id_modalidad_curso = $rqc2['id_modalidad'];

/* modalidad */
$rqdmldc1 = query("SELECT nombre FROM cursos_modalidades WHERE id='$id_modalidad_curso' ORDER BY id DESC limit 1 ");
$rqdmldc2 = fetch($rqdmldc1);
$modalidad_del_curso = $rqdmldc2['nombre'];

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
    var VAR_modo_de_pago = '<?php echo $modo_de_pago; ?>';
    var VAR_id_curso = '<?php echo $id_curso; ?>';
    var VAR_id_turno = '<?php echo $id_turno_curso; ?>';
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
        </ul>
        <div class="hidden-md hidden-lg" style="background: #d2d2d2;
    padding: 10px;
    border: 1px solid #ada4a4;
    margin: 10px;
    clear: both;
    text-align: center;
    border-radius: 15px;">
            <?php
            if (acceso_cod('adm-cursos-estado')) {
                ?>
                <span class="box-desactivar-curso">
                    <?php
                    if ($rqvhc2['estado'] == '1') {
                        ?>
                        <i class="btn btn-xs btn-success">Activado</i>
                        <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'temporal');">Temporal</i>
                        <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'desactivado');">Desactivado</i>
                        <?php
                    } elseif ($rqvhc2['estado'] == '2') {
                        ?>
                        <i class="btn btn-xs btn-danger">Temporal</i>
                        <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'activado');">Activado</i>
                        <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'desactivado');">Desactivado</i>
                        <?php
                    } else {
                        ?>
                        <i class="btn btn-xs btn-default active">Desactivado</i>
                        <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'activado');">Activado</i>
                        <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'temporal');">Temporal</i>
                        <?php
                    }
                    ?>
                </span>
                <?php
            }
            ?>
        </div>
        <div class="form-group hiddn-minibar pull-right">
            <div class="hidden-sm hidden-xs">
                <?php
                if (acceso_cod('adm-cursos-estado')) {
                    ?>
                    <span class="box-desactivar-curso">
                        <?php
                        if ($rqvhc2['estado'] == '1') {
                            ?>
                            <i class="btn btn-xs btn-success">Activado</i>
                            <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'temporal');">Temporal</i>
                            <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'desactivado');">Desactivado</i>
                            <?php
                        } elseif ($rqvhc2['estado'] == '2') {
                            ?>
                            <i class="btn btn-xs btn-danger">Temporal</i>
                            <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'activado');">Activado</i>
                            <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'desactivado');">Desactivado</i>
                            <?php
                        } else {
                            ?>
                            <i class="btn btn-xs btn-default active">Desactivado</i>
                            <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'activado');">Activado</i>
                            <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'temporal');">Temporal</i>
                            <?php
                        }
                        ?>
                        &nbsp;|&nbsp;
                    </span>
                    <?php
                }
                ?>
                <a href="<?php echo $dominio.$titulo_identificador_del_curso; ?>.html" target="_blank" class="btn btn-sm btn-info active"><i class="fa fa-eye"></i> VISUALIZAR</a>
                &nbsp;|&nbsp;
                <a href="cursos-editar/<?php echo $id_curso; ?>.adm" class="btn btn-sm btn-info active"><i class="fa fa-edit"></i> EDITAR</a>
                &nbsp;|&nbsp;
                <b onclick="show_estadisticas();" class="btn btn-sm btn-info active"><i class="fa fa-signal"></i> ESTADISTICAS</b>
                &nbsp;
            </div>
            <div class="hidden-md">
                <a href="<?php echo $dominio.$titulo_identificador_del_curso; ?>.html" target="_blank" class="btn btn-sm btn-info active" title="VISUALIZAR CURSO"><i class="fa fa-eye"></i></a>
                &nbsp;|&nbsp;
                <a href="cursos-editar/<?php echo $id_curso; ?>.adm" class="btn btn-sm btn-info active" title="EDITAR CURSO"><i class="fa fa-edit"></i></a>
                &nbsp;|&nbsp;
                <b onclick="show_estadisticas();"  class="btn btn-sm btn-info active" title="ESTADISTICAS"><i class="fa fa-signal"></i></b>
            </div>
        </div>
        <div>
            <span class="btn btn-success active" style="font-weight: bold;color: #ff6161;font-size: 17pt;border: 1px solid #ffb8b8;text-align: center;background: white;padding: 1px 7px;"><?php echo $id_curso; ?></span> |
            <i class='btn btn-success active'><?php echo date("d  M  y", strtotime($fecha_del_curso)); ?></i> | 
            <b class="btn btn-sm btn-info" onclick="copyToClipboardInfoCurso();" title="Copiar informacion al clipboard." style="border-radius: 3px;"><i class="fa fa-copy"></i></b> | 
            <b class="btn btn-info btn-sm active hidden-xs" style="background: #19bfbf;"><?php echo $modalidad_del_curso; ?></b>
        </div>
    </div>
</div>
<div>
    <h4 class="titulo-head-panelcurso">
        <?php echo $nombre_del_curso; ?>
        <?php
        /* curso virtual */
        if ($id_modalidad_curso == '2' || $id_modalidad_curso == '3') {
            $rqdcv1 = query("SELECT fecha_inicio,fecha_final,(cv.titulo)dr_titulo_curso_virtual,(d.nombres)dr_nombre_docente,cv.urltag FROM cursos_rel_cursoonlinecourse r INNER JOIN cursos_onlinecourse cv ON r.id_onlinecourse=cv.id INNER JOIN cursos_docentes d ON r.id_docente=d.id WHERE r.id_curso='" . $id_curso . "' ");
            $cnt_cursos_virtuales = num_rows($rqdcv1);
            ?>
            <b class="btn btn-xs btn-default pull-right hidden-sm" data-toggle="collapse" data-target="#COLLAPSE-cvir"><?php echo $cnt_cursos_virtuales; ?> C-Vir</b>
            <?php
        }
        ?>
    </h4>
</div>
<div id="contentInfo" style="display:none;">
    <?php
    /* costo */
                $costo_curso = $rqc2['costo'];
                $f_h = date("H:i", strtotime($rqc2['fecha2']));
                if ($f_h !== '00:00') {
                    $f_actual = strtotime(date("Y-m-d H:i"));
                    $f_limite = strtotime($rqc2['fecha2']);
                } else {
                    $f_actual = strtotime(date("Y-m-d"));
                    $f_limite = strtotime(substr($rqc2['fecha2'], 0, 10));
                }
                $rqdwn1 = query("SELECT w.numero FROM whatsapp_numeros w INNER JOIN cursos_rel_cursowapnum r ON r.id_whats_numero=w.id WHERE r.id_curso='".$id_curso."' ORDER BY r.id ASC LIMIT 1 ");
                if(num_rows($rqdwn1)==0){
                    $num_whatsapp = "69714008";
                }else{
                    $rqdwn2 = fetch($rqdwn1);
                    $num_whatsapp = $rqdwn2['numero'];
                }
                ?>
                    <div>*<?php echo $nombre_del_curso; ?>*</div>
                    <div><br></div>
                    <?php if ($rqc2['id_modalidad'] !== '2') { ?>
                    <div>Fecha: &nbsp; <?php echo date("d/m/Y", strtotime($fecha_del_curso)); ?></div>
                    <div><br></div>
                    <?php } ?>
                    <div>*Duraci&oacute;n:* &nbsp; <?php echo $rqc2['horarios']; ?></div>
                    <div><br></div>
                    <?php if ($rqc2['id_modalidad'] == '4') { ?>
                    <div>*Modalidad:* &nbsp;Online mediante Google Meet</div>
                    <?php }else{ ?>
                    <div>*Modalidad:* &nbsp;<?php echo $rqc2['id_modalidad']=='1'?'PRESENCIAL':'VIRTUAL'; ?></div>
                    <?php } ?>
                    <div><br></div>
                    <div>*Detalle completo del curso:* &nbsp; <?php echo $dominio . numIDcurso($id_curso) . '/'; ?></div>
                    <div><br></div>
                    <?php if ($rqc2['estado'] !== '0') { ?>
                    <div>
                    <?php if ((int) $rqc2['costo'] > 0) { ?>
                    *Inversi&oacute;n:* &nbsp; <?php echo $rqc2['costo']; ?> Bs.
                    <div><br></div>
                    <?php }else{ ?>
                    *Ingreso:* GRATUITO con c&eacute;dula de identidad
                    <div><br></div>
                    <?php } ?>
                    </div>
                    <?php if ($rqc2['sw_fecha2'] == '1' && ( $f_actual <= $f_limite )) { ?>
                        <div>*DESCUENTO POR PAGO ANTICIPADO:*</div>
                        <div><br></div>
                        <div>*Inversi&oacute;n:* <?php echo $rqc2['costo2']; ?> Bs. hasta <?php echo date("d/m",strtotime($rqc2['fecha2'])); ?> <?php echo date("H:i",strtotime($rqc2['fecha2']))=='00:00'?'':date("H:i",strtotime($rqc2['fecha2'])); ?></div>
                        <div><br></div>
                        <?php if ($rqc2['sw_fecha3'] == '1' && ( date("Y-m-d") <= $rqc2['fecha3'])) { ?>
                            <div>*Inversi&oacute;n:* <?php echo $rqc2['costo3']; ?> Bs. hasta <?php echo date("d/m",strtotime($rqc2['fecha3'])); ?> <?php echo date("H:i",strtotime($rqc2['fecha3']))=='00:00'?'':date("H:i",strtotime($rqc2['fecha3'])); ?></div>
                            <div><br></div>
                        <?php } ?>
                        <?php if ($rqc2['sw_fecha_e'] == '1' && ( date("Y-m-d") <= $rqc2['fecha_e'])) { ?>
                            <div>*Estudiantes:* <?php echo $rqc2['costo_e']; ?> Bs. presentando carnet universitario</div>
                            <div><br></div>
                        <?php } ?>
                    <?php } ?>
                    <div>*Whatsapp:* &nbsp; <?php echo 'https://wa.me/591'.$num_whatsapp; ?></div>
                    <div><br></div>
                    <?php if ((int) $rqc2['costo'] > 0) { ?>
                    <?php 
                    $rqdtcb1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='$id_curso' AND r.sw_cprin=1 AND r.estado=1 ORDER BY c.id ASC ");
                    $rqdtcb2 = fetch($rqdtcb1);?>
                    <div>*PAGOS:*</div>
                    <div><br></div>
                    <div><?php echo $rqdtcb2['nombre_banco']; ?> cuenta <?php echo $rqdtcb2['numero_cuenta']; ?> :&nbsp; Titular <?php echo $rqdtcb2['titular']; ?></div>
                    <div><br></div>
                    <div>Pago por TigoMoney <?php echo $___numero_tigomoney; ?> (sin recargo)</div>
                    <div><br></div>
                    <div>*Otras formas de pago:* <?php echo $dominio; ?>formas-de-pago.html</div>
                    <div><br></div>
                    <div>Una vez realizado el pago, tiene que registrarse en: <?php echo $dominio; ?>R/<?php echo $id_curso; ?>/</div>
                    <div><br></div>
                    <?php } ?>
                    <?php } ?>
                    <div><br></div>
</div>
<?php
/* curso virtual */
if ($id_modalidad_curso == '2' || $id_modalidad_curso == '3') {
    echo '<div id="COLLAPSE-cvir" class="collapse">';
    while ($rqdcv2 = fetch($rqdcv1)) {
        ?>
        <div class="row" style='color: #484848;font-size: 8pt;clear: both;background: #bae4ff;border-radius: 5px;padding: 5px;margin-top: 5px;border: 1px solid #79b1c3;'>
            <div class="col-md-4">
                <b style="font-size: 7pt;">C-VIRTUAL:</b> 
                <?php echo $rqdcv2['dr_titulo_curso_virtual']; ?>
            </div>
            <div class="col-md-2">
                <b style="font-size: 7pt;">TUTOR:</b> 
                <?php echo $rqdcv2['dr_nombre_docente']; ?>
            </div>
            <div class="col-md-2">
                <b style="font-size: 7pt;">DISPONIBLE:</b> 
                <?php echo date("d/m/Y", strtotime($rqdcv2['fecha_inicio'])); ?> hasta <?php echo date("d/m/Y", strtotime($rqdcv2['fecha_final'])); ?>
            </div>
            <div class="col-md-4">
                <b style="font-size: 7pt;">URL:</b> 
                <a href="<?php echo $dominio_plataforma; ?>ingreso/<?php echo $rqdcv2['urltag']; ?>.html" target="_blank"><?php echo $dominio_plataforma; ?>ingreso/<?php echo $rqdcv2['urltag']; ?>.html</a>
            </div>
        </div>
        <?php
    }
    echo '</div>';
}
?>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-12NOT">
        <div class="panelNOT">

            <div class="row" style="background: #def5fb;padding: 3px 0px;margin: 5px 0px;border-radius: 5px;border:1px solid #72d5e2;">
                <div class="col-md-6 hidden-xs">
                    <form action="" method="post" onsubmit="return realizar_busqueda();">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon" onclick="realizar_busqueda();" style="cursor:pointer;"><i class="fa fa-search"></i> &nbsp; Buscar: </span>
                            <input type="text" name="input-buscador" value="" id="inputbuscador" class="form-control" placeholder="Nombres / Apellidos / Celular / Correo / Codigo de registro ..."/>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="text-right" style="padding:5px 0px;<?php if($costo_curso==0) { echo "display:none;"; } ?>" >
                        <a onclick="$('#inputbuscador').val('no-id');VAR_modo_de_pago = 'todos';
                                lista_participantes(VAR_id_curso, 0);
                                lista_participantes_eliminados(VAR_id_curso, 0);" id="btnmodopago-todos" class="btnmodopago btn btn-sm btn-default" style="font-size: 8pt;"><i class="fa fa-list"></i> TODOS </a>
                        <a onclick="$('#inputbuscador').val('no-id');VAR_modo_de_pago = 'habilitados';
                                lista_participantes(VAR_id_curso, 0);
                                lista_participantes_eliminados(VAR_id_curso, 0);" id="btnmodopago-habilitados" class="btnmodopago btn btn-sm btn-default" style="font-size: 8pt;"><i class="fa fa-list"></i> HABILITADOS </a>
                        <a onclick="$('#inputbuscador').val('no-id');VAR_modo_de_pago = 'conpago';
                                lista_participantes(VAR_id_curso, 0);
                                lista_participantes_eliminados(VAR_id_curso, 0);" id="btnmodopago-conpago" class="btnmodopago btn btn-sm btn-default" style="font-size: 8pt;"><i class="fa fa-list"></i> CON PAGO</a>
                        <a onclick="$('#inputbuscador').val('no-id');VAR_modo_de_pago = 'sinpago';
                                lista_participantes(VAR_id_curso, 0);
                                lista_participantes_eliminados(VAR_id_curso, 0);" id="btnmodopago-sinpago" class="btnmodopago btn btn-sm btn-default" style="font-size: 8pt;"><i class="fa fa-list"></i> SIN PAGO</a>
                        <a onclick="$('#inputbuscador').val('no-id');VAR_modo_de_pago = 'gratuito';
                                lista_participantes(VAR_id_curso, 0);
                                lista_participantes_eliminados(VAR_id_curso, 0);" id="btnmodopago-gratuito" class="btnmodopago btn btn-sm btn-default" style="font-size: 8pt;"><i class="fa fa-list"></i> GRATUITOS</a>
                    </div>
                    <?php
                    $sw_turnos = false;
                    $rqtc1 = query("SELECT id,titulo FROM cursos_turnos WHERE id_curso='$id_curso' ");
                    if (num_rows($rqtc1) > 0) {
                        $aux_class_tc = "btn-success";
                        if ($id_turno_curso !== 0) {
                            $aux_class_tc = "btn-info";
                        }
                        ?>
                        <span class="pull-right">
                            <a onclick="lista_participantes(VAR_id_curso, 0);
                                    lista_participantes_eliminados(VAR_id_curso, 0);" id="btnturno-0" class="btnturno btn btn-xs active <?php echo $aux_class_tc; ?>"><i class="fa fa-clock-o"></i> Todos los turnos</a>
                               <?php
                               $turno = array();
                               $turno[0] = 'Sin turno';
                               $sw_turnos = true;
                               while ($rqtc2 = fetch($rqtc1)) {
                                   $turno[$rqtc2['id']] = $rqtc2['titulo'];
                                   $aux_class_tc = "btn-info";
                                   if ($id_turno_curso == $rqtc2['id']) {
                                       $aux_class_tc = "btn-success";
                                   }
                                   ?>
                                <a onclick="lista_participantes(<?php echo $id_curso; ?>,<?php echo $rqtc2['id']; ?>);
                                        lista_participantes_eliminados(<?php echo $id_curso; ?>, <?php echo $rqtc2['id']; ?>);" id="btnturno-<?php echo $rqtc2['id']; ?>" class="btnturno btn btn-xs active <?php echo $aux_class_tc; ?>"><i class="fa fa-clock-o"></i> <?php echo $rqtc2['titulo']; ?></a>
                                   <?php
                               }
                               ?>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                        </span>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <?php
            $styledisplay_addparticipante = "display:none;";
            if ($sw_habilitacion_procesos) {
                $styledisplay_addparticipante = "display:block;";
            }
            ?>
            <button class="btn btn-xs btn-success btn-block" onclick="registra_participante();"><i class="fa fa-plus"></i> AGREGAR PARTICIPANTE</button>
            
            <div class="row">
                <div class="col-md-2">
                    
                </div>
                <div class="col-md-8" style="margin-top: 12px;">
                    <table class="table table-bordered hidden-xs" style="background: #f9f9f9;">
                        <tr>
                            <td class="hidden-xs" style="width: 110px;vertical-align: middle;font-weight: bold;font-size: 11pt;color: #3885b7;">BUSCAR:</td>
                            <td><input type="text" class="form-control" id="input-busca-participante"/></td>
                            <td style="width: 200px;"><b class="btn btn-block btn-info" onclick="buscar_participante('<?php echo $aux_sw_virt; ?>');">PARTICIPANTE</b></td>
                            <td style="width: 200px;"><b class="btn btn-block btn-warning" onclick="buscar_curso();">CURSO</b></td>
                        </tr>
                    </table>
                    <div class="hidden-sm hidden-md hidden-lg" style="background: #f9f9f9;padding: 10px;border: 1px solid #abd8ab;line-height: 0.7;">
                        <input type="text" class="form-control" id="input-busca-participante-movil" style="height: auto;font-size: 11pt;padding: 12px 10px;" placeholder="..."/>
                        <br>
                        <b class="btn btn-xs btn-block btn-info" onclick="buscar_participante('<?php echo $aux_sw_virt; ?>');">PARTICIPANTE</b>
                        <br>
                        <b class="btn btn-xs btn-block btn-warning" onclick="buscar_curso();">CURSO</b>
                    </div>
                </div>
            </div>
            <div id="AJAXCONTENT-buscar_participante"></div>
            <script>
                function buscar_participante(modcourse) {
                    $("#AJAXCONTENT-buscar_participante").html("Cargando...");
                    let dat = $("#input-busca-participante").val();
                    if(dat==''){
                        dat = $("#input-busca-participante-movil").val();
                    }
                    $.ajax({
                        url: 'pages/ajax/ajax.cursos-listar.buscar_participante.php',
                        data: {dat: dat, modcourse: modcourse},
                        type: 'POST',
                        dataType: 'html',
                        success: function(data) {
                            $("#AJAXCONTENT-buscar_participante").html(data);
                        }
                    });
                }
            </script>
            <script>
                function buscar_curso() {
                    $("#AJAXCONTENT-buscar_curso").html("Cargando...");
                    let dat = $("#input-busca-participante").val();
                    if(dat==''){
                        dat = $("#input-busca-participante-movil").val();
                    }
                    $.ajax({
                        url: '<?php echo $dominio_admin; ?>pages/ajax/ajax.inicio.buscar_curso.php',
                        data: {dat: dat},
                        type: 'POST',
                        dataType: 'html',
                        success: function(data) {
                            $("#AJAXCONTENT-buscar_participante").html(data);
                        }
                    });
                }
            </script>


            <?php
            if (num_rows($resultado1) == 0) {
                echo "<p>No se registraron participantes para este curso</p>";
            }
            ?>

            <div class="panel-bodyNOT" style="padding-top: 0px;">


                <!-- DIV CONTENT AJAX :: LISTADO DE PARTICIPANTES -->
                <div id="ajaxloading-lista_participantes"></div>
                <div id="ajaxbox-lista_participantes">
                    ....
                </div>


                <!-- DIV CONTENT AJAX :: LISTADO DE PARTICIPANTES ELIMINADOS -->
                <div id="ajaxloading-lista_participantes_eliminados"></div>
                <div id="ajaxbox-lista_participantes_eliminados">
                    ....
                </div>


            </div>
        </div>
    </div>
</div>


<!-- ajax habilitacion de participante -->
<script>
    function habilitar_participante(dat) {

        if (confirm("Desea habilitar nuevamente al participante?")) {

            $.ajax({
                url: 'pages/ajax/ajax.instant.curso_habilitar_participante.php',
                data: {dat: dat},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    //$("#ajaxbox-tr-participante-" + dat).html(data);
                    alert('Participante-habilitado');
                    //location.href = 'cursos-participantes/<?php echo $id_curso; ?>.adm';
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                    $('.alert').hide();
                }
            });

        }

    }
</script>

<!-- ajax mensaje_usuarios_multiple -->
<script>
    function mensaje_usuarios_multiple() {
        $("#AJAXCONTENT-mensaje_usuarios_multiple").html('Cargando...');
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.mensaje_usuarios_multiple.php',
            data: {id_curso: <?php echo $id_curso; ?>, ids: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-mensaje_usuarios_multiple").html(data);
            }
        });
    }
</script>
<!-- Modal mensaje_usuarios_multiple -->
<div id="MODAL-mensaje_usuarios_multiple" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">DATOS DE USUARIO</h4>
      </div>
      <div class="modal-body">
          <div id="AJAXCONTENT-mensaje_usuarios_multiple"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<!-- ajax documentos_de_usuarios -->
<script>
    function documentos_de_usuarios() {
        $("#AJAXCONTENT-documentos_de_usuarios").html('Cargando...');
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.documentos_de_usuarios.php',
            data: {id_curso: <?php echo $id_curso; ?>, ids: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-documentos_de_usuarios").html(data);
            }
        });
    }
</script>
<!-- Modal documentos_de_usuarios -->
<div id="MODAL-documentos_de_usuarios" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">DOCUMENTOS DE USUARIOS</h4>
      </div>
      <div class="modal-body">
          <div id="AJAXCONTENT-documentos_de_usuarios"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- ajax imprimir certificados multiple -->
<script>
    function imprimir_certificados_multiple(dat) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $.ajax({
            url: 'pages/ajax/ajax.aux.cursos-participantes.imprimir_certificados_multiple.php',
            data: {id_curso: <?php echo $id_curso; ?>, nro_certificado: dat, ids: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                window.open(data, 'popup', 'width=700,height=500');
                setTimeout(function() {
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                }, 5000);
            }
        });
    }
</script>

<!-- ajax imprimir certificados multiple digitales -->
<script>
    function imprimir_certificados_multiple_digitales(dat) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $.ajax({
            url: 'pages/ajax/ajax.aux.cursos-participantes.imprimir_certificados_multiple_digitales.php',
            data: {id_curso: <?php echo $id_curso; ?>, nro_certificado: dat, ids: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                window.open(data, 'popup', 'width=700,height=500');
                setTimeout(function() {
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                }, 5000);
            }
        });
    }
</script>


<!-- ajax imprimir_certificados_culminacion_multiple -->
<script>
    function imprimir_certificados_culminacion_multiple(dat) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $.ajax({
            url: 'pages/ajax/ajax.aux.cursos-participantes.imprimir_certificados_culminacion_multiple.php',
            data: {id_curso: <?php echo $id_curso; ?>, id_certificado: dat, ids: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                window.open(data, 'popup', 'width=700,height=500');
            }
        });
    }
</script>


<!-- ajax imprimir dos_certificados -->
<script>
    function imprimir_dos_certificados(dat) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $.ajax({
            url: 'pages/ajax/ajax.aux.cursos-participantes.imprimir_dos_certificados.php',
            data: {id_curso: <?php echo $id_curso; ?>, ids: dat},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                window.open(data, 'popup', 'width=700,height=500');
                setTimeout(function() {
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                }, 5000);
            }
        });
    }
</script>

<!-- ajax imprimir tres certificados -->
<script>
    function imprimir_tres_certificados(dat) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $.ajax({
            url: 'pages/ajax/ajax.aux.cursos-participantes.imprimir_tres_certificados.php',
            data: {id_curso: <?php echo $id_curso; ?>, ids: dat},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                window.open(data, 'popup', 'width=700,height=500');
                setTimeout(function() {
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                }, 5000);
            }
        });
    }
</script>

<!-- ajax imprimir copia legalizada -->
<script>
    function imprimir_copia_legalizada(dat,anv_rev) {

        if (dat > 0) {
            $.ajax({
                url: 'pages/ajax/ajax.aux.cursos-participantes.imprimir_copia_legalizada.php',
                data: {dat: dat, anv_rev: anv_rev},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    window.open(data, 'popup', 'width=700,height=500');
                    /*
                     setTimeout(function() {
                     lista_participantes(<?php //echo $id_curso;                       ?>, 0);
                     }, 2000);
                     */
                }
            });
        } else {
            alert('Error en el ID de certificado');
        }
    }
</script>


<!-- ajax emision certificados masivamente -->
<script>
    function emision_multiple_certificados() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#AJAXCONTENT-emite_certificados_multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_certificados.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-emite_certificados_multiple").html(data);
                //lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!-- ajax emision certificados masivamente Certificado 2 -->
<script>
    function emision_multiple_c2_certificados() {
        var ids_to_send;
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();

        var aux_idsalmacenador_2 = '0';
        var arraux1 = aux_idsalmacenador.split(',');
        for (var index = 0; index < arraux1.length; ++index) {
            if (array_check_participante[arraux1[index]] !== undefined) {
                //console.log(arraux1[index]);
                aux_idsalmacenador_2 = aux_idsalmacenador_2 + ',' + arraux1[index];
            }
        }
        if (aux_idsalmacenador_2 === '0') {
            ids_to_send = ids.join(',');
        } else {
            ids_to_send = aux_idsalmacenador_2;
        }

        //alert(ids_to_send);
        if (true) {

            $("#AJAXCONTENT-emite_certificados_multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
            $.ajax({
                url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_c2_certificados.php',
                data: {dat: ids_to_send},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-emite_certificados_multiple").html(data);
                    //lista_participantes(<?php echo $id_curso; ?>, 0);
                }
            });

        }
    }
</script>

<!-- ajax emision certificados a eleccion -->
<script>
    function emision_certificados_a_eleccion() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-a-eleccion").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion p2 -->
<script>
    function emision_certificados_a_eleccion_p2() {
        var ids;
        var id_modelo_certificado;
        id_modelo_certificado = $("#id-modelo-certificado").val();
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion_p2.php',
            data: {dat: ids.join(','), id_modelo_certificado: id_modelo_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-a-eleccion").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion p3 -->
<script>
    function emision_certificados_a_eleccion_p3() {
        var ids;
        var id_modelo_certificado;
        id_modelo_certificado = $("#id-modelo-certificado").val();
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion_p3.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso, id_modelo_certificado: id_modelo_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-a-eleccion").html(data);
            }
        });
    }
</script>



<!-- ajax emision certificados a eleccion -->
<script>
    function imprime_certificados_a_eleccion() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_impresion_certificados-a-eleccion").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.impresion_certificados_a_eleccion.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_impresion_certificados-a-eleccion").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion p2 -->
<script>
    function imprime_certificados_a_eleccion_p2() {
        var ids;
        var id_modelo_certificado;
        id_modelo_certificado = $("#id-modelo-certificado-imp").val();
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_impresion_certificados-a-eleccion").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.impresion_certificados_a_eleccion_p2.php',
            data: {dat: ids.join(','), id_modelo_certificado: id_modelo_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_impresion_certificados-a-eleccion").html(data);
            }
        });
    }
</script>


<!-- AJAX emite_certificados_multiple -->
<script>
    function emite_certificados_multiple(id_certificado, id_curso, modo) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#AJAXCONTENT-emite_certificados_multiple").html('PROCESANDO...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_certificados_multiple.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso, modo: modo},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-emite_certificados_multiple").html(data);
            }
        });
    }
</script>

<!-- AJAX emite_certificados_culminacion_multiple -->
<script>
    function emite_certificados_culminacion_multiple(id_certificado) {
        $("#TITLE-modgeneral").html('EMISION DE CERTIFICADO DE CULMINACION');
        $("#AJAXCONTENT-modgeneral").html('PROCESANDO...');
        $("#MODAL-modgeneral").modal('show');
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_certificados_culminacion_multiple.php',
            data: {dat: ids.join(','), id_certificado: id_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- AJAX envio_multiples_certificados -->
<script>
    function envio_multiples_certificados(id_certificado, id_curso, modo) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#AJAXCONTENT-emite_certificados_multiple").html('PROCESANDO...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_certificados_multiple.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso, modo: modo},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-emite_certificados_multiple").html(data);
            }
        });
    }
</script>


<!-- ajax emision certificados masivamente p2 -->
<script>
    function emision_multiple_certificados_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#AJAXCONTENT-emite_certificados_multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_certificados_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-emite_certificados_multiple").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!-- ajax emision certificados masivamente p2 Certificado 2 -->
<script>
    function emision_multiple_c2_certificados_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_2_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#AJAXCONTENT-emite_certificados_multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_c2_certificados_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-emite_certificados_multiple").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>




<!-- ajax imprimir facturas masivamente -->
<script>
    function imprimir_facturas() {

        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();

        //alert('IDS: ' + ids.join(','));

        window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/factura-1-masivo.php?id_participantes=' + ids.join(','), 'popup', 'width=700,height=500');


    }
</script>

<!-- ajax emision facturas masivamente -->
<script>
    function emision_multiple_facturas() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_facturas-multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_facturas.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_facturas-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax emision facturas masivamente p2 -->
<script>
    function emision_multiple_facturas_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_facturas-multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_facturas_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_facturas-multiple").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!-- ajax procesar_certificados_secundarios -->
<script>
    function procesar_certificados_secundarios(id_participante) {
        var id_curso = '<?php echo $id_curso; ?>';
        $("#BOX-AJAX-certificados-secundarios").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.procesar_certificados_secundarios.php',
            data: {id_participante: id_participante, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#BOX-AJAX-certificados-secundarios").html(data);
            }
        });
    }
</script>

<!-- ajax procesar_certificados_secundarios p2 -->
<script>
    function procesar_certificados_secundarios_p2() {
        var arraydata = $("#formajax1").serialize();
        $("#BOX-AJAX-certificados-secundarios").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.procesar_certificados_secundarios_p2.php',
            data: arraydata,
            type: 'POST',
            success: function(data) {
                $("#BOX-AJAX-certificados-secundarios").html(data);
            }
        });
    }
</script>

<!-- ajax deshabilita participantes no seleccionados p1 -->
<script>
    function deshabilita_participantes_no_seleccionados() {

        var aux_idsalmacenador_2 = '0';

        var arraux1 = aux_idsalmacenador.split(',');
        for (var index = 0; index < arraux1.length; ++index) {
            if (array_check_participante[arraux1[index]] === undefined) {
                //console.log(arraux1[index]);
                aux_idsalmacenador_2 = aux_idsalmacenador_2 + ',' + arraux1[index];
            }
        }
        //alert(aux_idsalmacenador_2);

        $("#ajaxloading-deshabilita_participantes_no_seleccionados").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.deshabilita_participantes_no_seleccionados_p1.php',
            data: {dat: aux_idsalmacenador_2},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-deshabilita_participantes_no_seleccionados").html('');
                $("#ajaxbox-deshabilita_participantes_no_seleccionados").html(data);
            }
        });
    }
</script>

<!-- ajax deshabilita participantes no seleccionados p2 -->
<script>
    function deshabilita_participantes_no_seleccionados_p2() {
        var aux_idsalmacenador_2 = '0';
        var arraux1 = aux_idsalmacenador.split(',');
        for (var index = 0; index < arraux1.length; ++index) {
            if (array_check_participante[arraux1[index]] === undefined) {
                aux_idsalmacenador_2 = aux_idsalmacenador_2 + ',' + arraux1[index];
            }
        }
        $("#ajaxloading-deshabilita_participantes_no_seleccionados_p2").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.deshabilita_participantes_no_seleccionados_p2.php',
            data: {dat: aux_idsalmacenador_2},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-deshabilita_participantes_no_seleccionados_p2").html('');
                $("#ajaxbox-deshabilita_participantes_no_seleccionados_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!-- ajax emision cupones descuento -->
<script>
    function emision_cupones_infosicoes() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_curso = '<?php echo $id_curso; ?>';
        $("#AJAXCONTENT-emite_certificados_multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_cupones_infosicoes.php',
            data: {dat: ids.join(','), id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_cupones-descuento").html(data);
                //lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>


<style>
    .fila_seleccionada{
        color: #1717c1;
        border-left: 5px solid #6969c5;
    }
    .fila_seleccionada:hover td{
        background: #dadada !important;
    }
</style>
<script>
    function check_participante(dat) {
        if (array_check_participante[dat] === undefined) {
            //$("#ajaxbox-tr-participante-" + dat).css("background", "#dadada");
            $("#ajaxbox-tr-participante-" + dat).addClass("fila_seleccionada");
            array_check_participante[dat] = true;
        } else {
            array_check_participante[dat] = undefined;
            //alert(array_check_participante[dat]);
            //$("#ajaxbox-tr-participante-" + dat).css("background", "#ffffff");
            $("#ajaxbox-tr-participante-" + dat).removeClass("fila_seleccionada");
        }
    }

    function procesa_checked_participantes() {
        alert(JSON.stringify(array_check_participante));
    }

    function deshabilita_participantes_no_seleccionados_cero() {
        //alert("YEY2 -> "+aux_idsalmacenador;
        var aux_idsalmacenador_2 = '0';

        var arraux1 = aux_idsalmacenador.split(',');
        for (var index = 0; index < arraux1.length; ++index) {
            if (array_check_participante[arraux1[index]] === undefined) {
                //console.log(arraux1[index]);
                aux_idsalmacenador_2 = aux_idsalmacenador_2 + ',' + arraux1[index];
            }
        }
        alert(aux_idsalmacenador_2);
    }
</script>


<!-- MODALS -->


<!-- Modal deshabilita de participantes no seleccionados -->
<div id="MODAL-deshabilita_participantes_no_seleccionados" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DESHABILITACION DE PARTICIPANTES</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: DESHABILITACION DE PARTICIPANTES P1 -->
                <div id="ajaxloading-deshabilita_participantes_no_seleccionados"></div>
                <div id="ajaxbox-deshabilita_participantes_no_seleccionados">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>
<!-- END Modal deshabilita de participantes no seleccionados -->

<style>
    @media (min-width: 890px){
        .modal-large{
            width: 800px;
        }
    }
</style>

<!-- Modal edicion de participante -->
<div id="MODAL-edicion-participante" class="modal fade" role="dialog">
    <div class="modal-dialog modal-large">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EDICI&Oacute;N DE PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: EDCICION DE PARTICIPANTE P1 -->
                <div id="ajaxloading-edita_participante_p1"></div>
                <div id="ajaxbox-edita_participante_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal edicion de participante -->


<script>
    function acceso_cursos_virtuales(id_participante) {
        //$("#AJAXCONTENT-acceso_cursos_virtuales").html('Enviando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.acceso_cursos_virtuales.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-acceso_cursos_virtuales").html(data);
            }
        });
    }
</script>

<!-- Modal acceso_cursos_virtuales -->
<div id="MODAL-acceso_cursos_virtuales" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CURSOS VIRTUALES</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-acceso_cursos_virtuales"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal pago-participante -->
<div id="MODAL-pago-participante" class="modal fade" role="dialog">
    <div class="modal-dialog modal-large">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PAGO CORRESPONDIENTE AL PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: EDCICION DE PARTICIPANTE P1 -->
                <div id="ajaxloading-pago_participante"></div>
                <div id="ajaxbox-pago_participante">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal edicion de participante -->


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


<!-- Modal Facturacion -->
<div id="MODAL-emite-factura" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DATOS DE FACTURACION</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: EMITE FACTURA P1 -->
                <div id="ajaxloading-emite_factura_p1"></div>
                <div id="ajaxbox-emite_factura_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Facturacion -->

<!-- Modal Elimina participante -->
<div id="MODAL-elimina-participante" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content panel-danger">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ELIMINACION DE PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: ELIMINA PARTICIPANTE P1 -->
                <div id="ajaxloading-elimina_participante_p1"></div>
                <div id="ajaxbox-elimina_participante_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Elimina participante -->


<!-- Modal Habilita participante -->
<div id="MODAL-habilita-participante" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">HABILITACION DE PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: HABILITACION PARTICIPANTE P1 -->
                <div id="ajaxloading-habilita_participante_p1"></div>
                <div id="ajaxbox-habilita_participante_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Elimina participante -->




<!-- Modal emitir certificados - multiple -->
<div id="MODAL-emite-certificados-multiple" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION MULTIPLE DE CERTIFICADOS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <h5 class="text-center">
                    Emisi&oacute;n de certificados para
                </h5>
                <div class="text-center" id='AJAXCONTENT-emite_certificados_multiple'>
                    <!-- ajax content -->
                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal emitir certificados - multiple -->


<!-- Modal emitir cupones descuento -->
<div id="MODAL-emite-cupones-descuento" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION DE CUPONES DESCUENTO</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <h5 class="text-center">
                    Emisi&oacute;n de cupones para
                </h5>
                <div class="text-center" id='box-modal_emision_cupones-descuento'>
                    <!-- ajax content -->
                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal emitir cupones descuento -->

<!-- Modal emitir certificados a eleccion -->
<div id="MODAL-emite-certificados-a-eleccion" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION DE CERTIFICADOS A ELECCION</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="text-center" id='box-modal_emision_certificados-a-eleccion'>

                    <!-- ajax content -->

                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal emitir certificados a eleccion -->

<!-- Modal imprime certificados a eleccion -->
<div id="MODAL-imprime-certificados-a-eleccion" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">IMRPESION DE CERTIFICADOS A ELECCION</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="text-center" id='box-modal_impresion_certificados-a-eleccion'>

                    <!-- ajax content -->

                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal imprime certificados a eleccion -->

<!-- Modal emitir facturas - multiple -->
<div id="MODAL-emite-facturas-multiple" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION MULTIPLE DE FACTURAS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <h5 class="text-center">
                    Emisi&oacute;n de facturas para
                </h5>
                <div class="text-center" id='box-modal_emision_facturas-multiple'>
                    <!-- ajax content -->
                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal emitir facturas - multiple -->


<!-- Modal-certificados-secundarios -->
<div id="MODAL-certificados-secundarios" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION DE CERTIFICADOS SECUNDARIOS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                        <br/>
                    </div>
                    <div class="col-md-6 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div id="BOX-AJAX-certificados-secundarios">
                        <!-- ajax content -->

                    </div>
                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-certificados-secundarios -->

<!-- Modal-generar reporte -->
<div id="MODAL-generar-reporte" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">GENERAR REPORTE</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 text-left">
                        <b>CURSO:</b> &nbsp; <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> &nbsp; <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-4 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-12">
                        <!-- AJAX CONTENT -->
                        <div id="AJAXBOX-reporte_cierre"></div>
                    </div>
                </div>
                <hr/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-generar reporte -->


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


<!-- MODAL proceso_envio_de_certificado -->
<div id="MODAL-proceso_envio_de_certificado" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PROCESO ENVIO DE CERTIFICADO</h4>
            </div>
            <div class="modal-body">
                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-proceso_envio_de_certificado"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!-- Modal avance-cvirtual -->
<div id="MODAL-avance-cvirtual" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PANEL DE CURSO VIRTUAL</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: HABILITACION PARTICIPANTE P1 -->
                <div id="ajaxloading-avance_cvirtual"></div>
                <div id="ajaxbox-avance_cvirtual">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal avance-cvirtual -->




<script>
    function proceso_envio_de_certificado(id_emision_certificado) {
        $("#AJAXCONTENT-proceso_envio_de_certificado").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.proceso_envio_de_certificado.php',
            data: {id_emision_certificado: id_emision_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-proceso_envio_de_certificado").html(data);
            }
        });
    }
</script>





<!-- envio de factura -->
<script>
    function enviar_factura(id) {

        var email = $("#correo-de-envio-" + id).val();
        $("#box-modal_envia-factura-" + id).html("Enviando correo...");
        $.ajax({
            url: 'pages/ajax/ajax.instant.enviar_factura.php?id_factura=' + id + '&email_a_enviar=' + email,
            data: {id: id},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_envia-factura-" + id).html(data);
            }
        });
    }
</script>
<script>
    function enviar_factura2(id) {

        var email = $("#correo-de-envio-" + id).val();
        $("#ffl-" + id).html("Enviando correo...");
        $.ajax({
            url: 'pages/ajax/ajax.instant.enviar_factura.php?id_factura=' + id + '&email_a_enviar=' + email,
            data: {id: id},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ffl-" + id).html('<i class="btn btn-xs btn-default"><b class="fa fa-send"></b> Enviado!</i>');
            }
        });
    }
</script>





<!--FUNCIONES AJAX DE CONTEXTO-->
<script>
    function lista_participantes(id_curso, id_turno) {
        $("#ajaxloading-lista_participantes").html(text__loading_tres);
        /* document.getElementById('inputbuscador').value = ""; */

        <?php if($costo_curso==0) { echo "VAR_modo_de_pago = 'todos'; "; } ?>

        var busc = $("#inputbuscador").val();
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.lista_participantes.php',
            data: {id_curso: id_curso, id_turno: id_turno, busc: busc, pago: VAR_modo_de_pago},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes").html("");
                $("#ajaxbox-lista_participantes").html(data);

                $(".btnmodopago").removeClass("btn-info");
                $(".btnmodopago").addClass("btn-default");
                $("#btnmodopago-" + VAR_modo_de_pago).removeClass("btn-default");
                $("#btnmodopago-" + VAR_modo_de_pago).addClass("btn-info");

                $(".btnturno").removeClass("btn-success");
                $(".btnturno").addClass("btn-info");
                $("#btnturno-" + id_turno).removeClass("btn-info");
                $("#btnturno-" + id_turno).addClass("btn-success");

                document.getElementById("idturno").value = id_turno;
            }
        });
    }
    function lista_participantes_INICIO(id_curso, id_turno) {
        $("#ajaxloading-lista_participantes").html(text__loading_uno);
        var busc = $("#inputbuscador").val();
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.lista_participantes.php',
            data: {id_curso: id_curso, id_turno: id_turno, busc: busc, pago: VAR_modo_de_pago},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes").html("");
                $("#ajaxbox-lista_participantes").html(data);
                
                $(".btnmodopago").removeClass("btn-info");
                $(".btnmodopago").addClass("btn-default");
                $("#btnmodopago-" + VAR_modo_de_pago).removeClass("btn-default");
                $("#btnmodopago-" + VAR_modo_de_pago).addClass("btn-info");

                $(".btnturno").removeClass("btn-success");
                $(".btnturno").addClass("btn-info");
                $("#btnturno-" + id_turno).removeClass("btn-info");
                $("#btnturno-" + id_turno).addClass("btn-success");

                document.getElementById("idturno").value = id_turno;
            }
        });
    }
    function lista_participantes_eliminados(id_curso, id_turno) {
        $("#ajaxloading-lista_participantes_eliminados").html(text__loading_dos);
        var busc = $("#inputbuscador").val();
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.lista_participantes_eliminados.php',
            data: {id_curso: id_curso, id_turno: id_turno, busc: busc, pago: VAR_modo_de_pago},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes_eliminados").html("");
                $("#ajaxbox-lista_participantes_eliminados").html(data);
            }
        });
    }
    function edita_participante_p1(id_participante, nro_lista) {
        $("#ajaxloading-edita_participante_p1").html(text__loading_dos);
        $("#ajaxbox-edita_participante_p1").html("");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.edita_participante_p1.php',
            data: {id_participante: id_participante, nro_lista: nro_lista},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-edita_participante_p1").html("");
                $("#ajaxbox-edita_participante_p1").html(data);
            }
        });
    }
    function pago_participante(id_participante) {
        $("#ajaxloading-pago_participante").html(text__loading_dos);
        $("#ajaxbox-pago_participante").html("");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.pago_participante.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-pago_participante").html("");
                $("#ajaxbox-pago_participante").html(data);
            }
        });
    }
   
    function emite_certificado_p1(id_participante, nro_certificado) {
        $("#TITLE-modgeneral").html('EMISION DE CERTIFICADO');
        $("#AJAXCONTENT-modgeneral").html(text__loading_dos);
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_certificado_p1.php',
            data: {id_participante: id_participante, nro_certificado: nro_certificado},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
    function emite_certificado_p2(id_participante, nro_certificado) {

        var receptor_de_certificado = $("#receptor_de_certificado-" + id_participante).val();
        var id_certificado = $("#id_certificado-" + id_participante).val();
        var id_curso = $("#id_curso-" + id_participante).val();

        var cont_tres = $("#cont_tres").val();
        var fecha_qr = $("#fecha_qr").val();
        var cont_dos = $("#cont_dos").val();
        var texto_qr = $("#texto_qr").val();

        $("#ajaxloading-emite_certificado_p2").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_certificado_p2.php',
            data: {receptor_de_certificado: receptor_de_certificado, id_certificado: id_certificado, id_curso: id_curso, id_participante: id_participante, nro_certificado: nro_certificado, cont_tres: cont_tres, fecha_qr: fecha_qr, cont_dos: cont_dos, texto_qr: texto_qr},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                if (nro_certificado === 1) {
                    $("#box-modal_emision_certificado-button-" + id_participante).html('<i class="btn-sm btn-default active">Emitido</i>');
                } else {
                    $("#box-modal_emision_certificado-button-2-" + id_participante).html('<i class="btn-sm btn-default active">Emitido</i>');
                }
                lista_participantes(<?php echo $id_curso; ?>, 0);
                $("#ajaxloading-emite_certificado_p2").html("");
                $("#ajaxbox-emite_certificado_p2").html(data);
            }
        });
    }
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
    function emite_factura_p1(id_participante) {
        $("#ajaxloading-emite_factura_p1").html(text__loading_dos);
        $("#ajaxbox-emite_factura_p1").html("");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_factura_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-emite_factura_p1").html("");
                $("#ajaxbox-emite_factura_p1").html(data);
            }
        });
    }
    function emite_factura_p2(id_participante) {
        var data_form = $("#form-emite-factura-" + id_participante).serialize();
        $("#ajaxloading-emite_factura_p2").html(text__loading_dos);
        $("#ajaxbox-emite_factura_p2").html("");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_factura_p2.php',
            data: data_form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-emite_factura_p2").html("");
                $("#ajaxbox-emite_factura_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
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
    function habilita_participante_cvirtual_p1_PRE(id_participante) {
        $("#ajaxbox-habilita_participante_p1").html("");
        $("#ajaxloading-habilita_participante_p1").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_cvirtual_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-habilita_participante_p1").html("");
                $("#ajaxbox-habilita_participante_p1").html(data);
            }
        });
    }
    function habilita_participante_cvirtual_p1(id_participante) {
        if(confirm('DESEA REALIZAR LA ACTIVACION ?')){
            habilita_participante_cvirtual_p2(id_participante, 0);
        }
    }
    function habilita_participante_cvirtual_p2_PRE(id_participante) {
        $("#ajaxbox-habilita_participante_p2").html("");
        $("#ajaxloading-habilita_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_cvirtual_p2.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-habilita_participante_p2").html("");
                $("#ajaxbox-habilita_participante_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
    function habilita_participante_cvirtual_p2(id_participante) {
        $("#TD-cvir-"+id_participante).html('Procesando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_cvirtual_p2.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#TD-cvir-"+id_participante).html(data);
                $("#clic_action-msj_accesos-"+id_participante).html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/wapicons/wap-llave-1.jpg" style="height: 25px;border-radius: 20%;curor:pointer;">');
            }
        });
    }
    function elimina_participante_cvirtual_p1(id_participante) {
        $("#ajaxbox-elimina_participante_p1").html("");
        $("#ajaxloading-elimina_participante_p1").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.elimina_participante_cvirtual_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-elimina_participante_p1").html("");
                $("#ajaxbox-elimina_participante_p1").html(data);
            }
        });
    }
    function elimina_participante_cvirtual_p2(id_participante) {
        $("#ajaxbox-elimina_participante_p2").html("");
        $("#ajaxloading-elimina_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.elimina_participante_cvirtual_p2.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-elimina_participante_p2").html("");
                $("#ajaxbox-elimina_participante_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }

    function avance_cvirtual(id_participante) {
        $("#ajaxbox-avance_cvirtual").html("");
        $("#ajaxloading-avance_cvirtual").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.avance_cvirtual.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-avance_cvirtual").html("");
                $("#ajaxbox-avance_cvirtual").html(data);
            }
        });
    }
</script>

<script>
    function cambiar_estado_curso(id_curso, estado) {
        $(".box-desactivar-curso").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.cambiar_estado_curso.php',
            data: {id_curso: id_curso, estado: estado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                if (estado === 'desactivado') {
                    $("#div-add-participante").css('display', 'none');
                } else {
                    $("#div-add-participante").css('display', 'block');
                }
                $(".box-desactivar-curso").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!--FUNCIONES DE INICIO DE PAGINA-->
<script>
    var text__loading_uno = "<div style='text-align:center;'><img src='<?php echo $dominio_www; ?>contenido/imagenes/images/loader.gif'/></div>";
    var text__loading_dos = "Cargando...";
    var text__loading_tres = "<div style='background: #FFF;padding: 10px;border: 1px solid gray;border-radius: 5px;position: absolute;box-shadow: 2px 2px 8px 0px #80808087;'>Actualizando...</div>";

    <?php
    if (isset($get[4])) {
        ?>
        document.getElementById("inputbuscador").value = '<?php echo $get[4]; ?>';
        lista_participantes(<?php echo $id_curso; ?>, 0);
        <?php
    }else{
        ?>
        lista_participantes_INICIO(<?php echo $id_curso; ?>, 0);
        lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
        <?php
    }
    ?>
</script>

<!-- AUTOCOMPLETADO DE PARTICIPANTE POR CI -->
<script>
    function checkParticipante(dat) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.checkParticipante.php',
            data: {dat: dat},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                var data_json_parsed = JSON.parse(data);
                if (data_json_parsed['estado'] === 1) {
                    if ($("#f-nom").val() === '') {
                        $("#f-nom").val((data_json_parsed['nombres']).toUpperCase());
                    }
                    if ($("#f-ape").val() === '') {
                        $("#f-ape").val((data_json_parsed['apellidos']).toUpperCase());
                    }
                    if ($("#f-email").val() === '') {
                        $("#f-email").val((data_json_parsed['correo']).toLowerCase());
                    }
                    if ($("#f-celular").val() === '') {
                        $("#f-celular").val((data_json_parsed['celular']).toLowerCase());
                    }
                    if ($("#f-pref").val() === '') {
                        $("#f-pref").val((data_json_parsed['prefijo']).toUpperCase());
                    }
                    if ($("#f-exp").val() === '') {
                        $("#f-exp").val(data_json_parsed['ci_expedido']).change();
                    }
                    if ($("#f-raz").val() === '') {
                        $("#f-raz").val((data_json_parsed['razon_social']).toUpperCase());
                    }
                    if ($("#f-nit").val() === '') {
                        $("#f-nit").val((data_json_parsed['nit']).toUpperCase());
                    }
                } else {
                    $("#f-nom").val('');
                    $("#f-ape").val('');
                    $("#f-email").val('');
                    $("#f-celular").val('');
                    $("#f-pref").val('');
                    $("#f-exp").val('').change();
                    $("#f-raz").val('');
                    $("#f-nit").val('');
                }
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


<!-- reprogramacion_de_curso -->
<script>
    function reprogramacion_de_curso(id_participante) {
        $("#TR-AJAXBOX-reprogramacion_de_curso").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.reprogramacion_de_curso.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#TR-AJAXBOX-reprogramacion_de_curso").html(data);
            }
        });
    }
</script>

<!-- reprogramacion_de_curso_p2 -->
<script>
    function reprogramacion_de_curso_p2() {
        var form = $("#FORM-reprogramacion_de_curso").serialize();
        $("#TR-AJAXBOX-reprogramacion_de_curso").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.reprogramacion_de_curso_p2.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#TR-AJAXBOX-reprogramacion_de_curso").html(data);
            }
        });
    }
</script>

<script>
    function realizar_busqueda() {
        lista_participantes(VAR_id_curso, VAR_id_turno);
        lista_participantes_eliminados(VAR_id_curso, VAR_id_turno);
    }
</script>

<script>
    function copyToClipboardInfoCurso() {
        alert('Se ha copiado la informacion del curso al portapapeles (Ctrl + C)');
        var container = document.createElement('div');
        container.innerHTML = document.getElementById("contentInfo").innerHTML;
        var activeSheets = Array.prototype.slice.call(document.styleSheets).filter(function(sheet) {
            return !sheet.disabled;
        });
        document.body.appendChild(container);
        window.getSelection().removeAllRanges();
        var range = document.createRange();
        range.selectNode(container);
        window.getSelection().addRange(range);
        document.execCommand('copy');
        document.body.removeChild(container);
    }
</script>


<!-- registra_participante -->
<script>
    function registra_participante() {
        $("#TITLE-modgeneral").html('AGREGA NUEVO PARTICIPANTE');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.registra_participante.php',
            data: {id_curso: '<?php echo $id_curso; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- show_estadisticas -->
<script>
    function show_estadisticas() {
        $("#TITLE-modgeneral").html('ESTADISTICAS');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.show_estadisticas.php',
            data: {id_curso: '<?php echo $id_curso; ?>'},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
