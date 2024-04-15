<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* recepcion de datos POST */
$id_curso = post('id_curso');

/* sw de habilitacion de procesos */
$rqvhc1 = query("SELECT estado FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqvhc2 = fetch($rqvhc1);
if ($rqvhc2['estado'] == '1' || $rqvhc2['estado'] == '2') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}

/* datos de curso */
$sw_turnos = false;
$rqtc1 = query("SELECT id FROM cursos_turnos WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");
if (num_rows($rqtc1) > 0) {
    $sw_turnos = true;
}

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
if ( isset_post('busc') && post('busc')!='no-id' && post('busc')!='' ) {
    $busqueda = post('busc');
    if(strpos($busqueda,'ids_')>0){
        $qr_busqueda = " AND ( p.id IN (".str_replace('__ids_','',$busqueda).") ) ";
    }else{
        $qr_busqueda = " AND ( p.id='$busqueda' OR p.nombres LIKE '%$busqueda%' OR p.apellidos LIKE '%$busqueda%' OR p.correo LIKE '%$busqueda%' ) ";
    }
    $vista = 1;
}

/* id de turno */
$id_turno_curso = 0;
$qr_turno = '';
if (isset_post('id_turno') && (post('id_turno') > 0)) {
    $id_turno_curso = (int) post('id_turno');
    $qr_turno = " AND p.id_turno='$id_turno_curso' ";
}

/* pago */
$qr_pago = "";

if (isset_post('pago')) {
    $pago = post('pago');
    switch ($pago) {
        case 'conpago':
            $qr_pago = " AND p.id_proceso_registro AND pr.sw_pago_enviado='1' AND pr.id_modo_pago<>'10' ";
            break;
        case 'sinpago':
            $qr_pago = " AND p.id_proceso_registro AND pr.sw_pago_enviado='0' ";
            $pago = '';
            break;
        case 'gratuito':
            $qr_pago = " AND p.id_proceso_registro AND pr.sw_pago_enviado='1' AND pr.id_modo_pago='10' ";
            $pago = '';
            break;
        case 'habilitados':
            $qr_pago = " AND p.id_proceso_registro AND pr.sw_pago_enviado='1' ";
            $pago = '';
            break;
        default:
            break;
    }
}


/* query principal */
/* participantes eliminados */
$resultado1 = query("SELECT *,(p.id)dr_id_participante FROM cursos_participantes p INNER JOIN cursos_proceso_registro pr ON p.id_proceso_registro=pr.id WHERE p.id_curso='$id_curso' AND p.estado='0' $qr_busqueda $qr_turno $qr_pago ORDER BY p.id DESC ");
if (num_rows($resultado1) > 0) {
    ?>
    <hr/>
    <div class="panel panel-danger">
        <div class="panel-heading">PARTICIPANTES ELIMINADOS</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="visible-lgNOT">#</th>
                            <th class="visible-lgNOT">Nombre</th>
                            <th class="visible-lgNOT">Contacto</th>
                            <th class="visible-lgNOT">Fecha / Registro / Facturaci&oacute;n</th>
                            <th class="visible-lgNOT">Eliminaci&oacute;n</th>
                            <th class="visible-lgNOT">Certs.</th>
                            <th class="visible-lgNOT">MR</th>
                            <th class="visible-lgNOT">.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 0;
                        while ($participante = fetch($resultado1)) {

                            /* datos de registro */
                            $rqrp1 = query("SELECT codigo,fecha_registro,celular_contacto,correo_contacto,id_modo_pago,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_administrador FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
                            $data_registro = fetch($rqrp1);
                            $codigo_de_registro = $data_registro['codigo'];
                            $fecha_de_registro = $data_registro['fecha_registro'];
                            $celular_de_registro = $data_registro['celular_contacto'];
                            $correo_de_registro = $data_registro['correo_contacto'];
                            $nro_participantes_de_registro = $data_registro['cnt_participantes'];
                            $id_emision_factura = $data_registro['id_emision_factura'];

                            $id_modo_pago = $data_registro['id_modo_pago'];
                            $monto_de_pago = $data_registro['monto_deposito'];
                            $imagen_de_deposito = $data_registro['imagen_deposito'];

                            $razon_social_de_registro = $data_registro['razon_social'];
                            $nit_de_registro = $data_registro['nit'];
                            ?>
                            <tr id="ajaxbox-tr-participante-<?php echo $participante['dr_id_participante']; ?>">
                                <td class="visible-lgNOT" style="background: #f2dede;">
                                    <?php
                                    echo ++$cnt;
                                    ?>
                                    <br/>
                                    <br/>
                                    <b class="btn btn-default btn-xs" onclick="historial_participante('<?php echo $participante['dr_id_participante']; ?>');" data-toggle="modal" data-target="#MODAL-historial_participante">
                                        <i class="fa fa-list" style="color:#8f8f8f;"></i>
                                    </b>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    echo trim($participante['prefijo'] . ' ' . strtoupper($participante['nombres'] . ' ' . $participante['apellidos']));
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    echo $participante['celular'] . '<br>' . $participante['correo'];
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    echo date("d / M H:i", strtotime($fecha_de_registro));
                                    echo "<br/>";
                                    echo $codigo_de_registro;
                                    echo "<br>Factura:<br>";
                                    if ($id_emision_factura != '0') {
                                        echo '<b style="color:green;">Emitida</b></br>';
                                    } else {
                                        if (strlen(trim($razon_social_de_registro . $nit_de_registro)) <= 2) {
                                            echo '<i>No solicitada</i></br>';
                                        } else {
                                            echo '<b>No emitida</b></br>';
                                        }
                                    }
                                    echo "<br/>";
                                    echo $razon_social_de_registro . ' ' . $nit_de_registro;
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    $rqdel1 = query("SELECT e.*,(a.nombre)dr_administrador FROM eliminacion_participantes e INNER JOIN administradores a ON e.id_administrador=a.id WHERE e.id_participante='".$participante['dr_id_participante']."' ORDER BY e.id DESC limit 1 ");
                                    if(num_rows($rqdel1)==0){
                                        echo "Sin datos";
                                    }else{
                                        $rqdel2 = fetch($rqdel1);
                                        echo "<b>Motivo:</b> ".$rqdel2['motivo']."<br>";
                                        echo "<b>Archivo adjunto:</b> ".($rqdel2['archivo']==''?'Sin archivo':"<a href='".$dominio_www."contenido/imagenes/doc-usuarios/".$rqdel2['archivo']."' target='_blank'>Ver archivo</a>")."<br>";
                                        echo "<b>Adminsitrador:</b> ".$rqdel2['dr_administrador']."<br>";
                                        echo "<b>Fecha:</b> ".date("d/m/Y H:i",strtotime($rqdel2['fecha']))."<br>";
                                    }
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    $sw_existencia_certificado_uno = $sw_existencia_certificado_dos = false;
                                    /* primer certificado */
                                    if ($participante['id_emision_certificado'] == '0' && $sw_habilitacion_procesos && $id_certificado_curso !== '0') {
                                        $sw_existencia_certificado_uno = false;
                                        ?>
                                        <span >
                                            <a  class="btn btn-xs btn-primary active">C1</a>
                                        </span>
                                        <?php
                                    } elseif ($participante['id_emision_certificado'] !== '0') {
                                        $sw_existencia_certificado_uno = true;
                                        ?>
                                        <a  class="btn btn-xs btn-warning active">C1</a>
                                        <?php
                                    }

                                    /* segundo certificado */
                                    if ($participante['id_emision_certificado_2'] == '0' && $sw_habilitacion_procesos && $id_certificado_2_curso !== '0') {
                                        $sw_existencia_certificado_dos = false;
                                        ?>
                                        <span >
                                            <a  class="btn btn-xs btn-primary active">C2</a>
                                        </span>
                                        <span >
                                            <a  class="btn btn-xs btn-primary">C12</a>
                                        </span>
                                        <?php
                                    } elseif ($participante['id_emision_certificado_2'] !== '0') {
                                        $sw_existencia_certificado_dos = true;
                                        ?>
                                        <a  class="btn btn-xs btn-warning active">C2</a>
                                        <?php
                                    }

                                    if ($sw_existencia_certificado_uno && $sw_existencia_certificado_dos) {
                                        ?>
                                        <a  class="btn btn-xs btn-warning">C12</a>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <?php
                                    if ($data_registro['id_administrador'] == '0') {
                                        echo "<span style='color:gray;'>Sistema</span>";
                                    } else {
                                        $rqadr1 = query("SELECT nombre FROM administradores WHERE id='" . $data_registro['id_administrador'] . "' LIMIT 1 ");
                                        $rqadr2 = fetch($rqadr1);
                                        echo "<span style='color:gray;font-size:8pt;'>" . $rqadr2['nombre'] . "</span>";
                                    }
                                    ?>
                                </td>
                                <td class="visible-lgNOT">
                                    <a data-toggle="modal" data-target="#MODAL-datos-registro" onclick="datos_registro(<?php echo $participante['dr_id_participante']; ?>);" class="btn btn-xs btn-default btn-block"><i class="fa fa-eye"></i> Ficha</a>
                                    <?php
                                    if ($sw_habilitacion_procesos) {
                                        ?>
                                        <br>
                                        <a onclick="habilita_participante_p1(<?php echo $participante['dr_id_participante']; ?>, 0);" class="btn btn-xs btn-success btn-block"><i class="fa fa-check"></i> Habilitar </a>
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
    <?php
}
?>

<script>
/*
    function habilita_participante_p1(id_participante, apartar) {
        $("#ajaxbox-habilita_participante_p1").html("");
        $("#ajaxloading-habilita_participante_p1").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_p1.php',
            data: {id_participante: id_participante, apartar: apartar},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-habilita_participante_p1").html("");
                $("#ajaxbox-habilita_participante_p1").html(data);
            }
        });
    }
    */
    function habilita_participante_p1(id_participante, apartar) {
        if(confirm('DESEA HABILITAR AL PARTICIPANTE ?')){
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_p2.php',
                data: {id_participante: id_participante, apartar: apartar},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    load_page('cursos-participantes','<?php echo $id_curso; ?>/no-turn/'+id_participante+'.adm','');
                }
            });
        }
    }
</script>
