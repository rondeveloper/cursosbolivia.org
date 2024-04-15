<?php
/* mensaje */
$mensaje = "";

?>


<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
        </ul>       
        <h3 class="page-header">
            <i class="fa fa-indent"></i> Listado auxiliar <i class="fa fa-info-circle animated bounceInDown show-info"></i>
        </h3>
    </div>
</div>

<?php echo $mensaje; ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-cascade">


        <?php
        
/* admisnitrador */
$id_administrador = administrador('id');

/* recepcion de datos POST */
$id_curso = 2483;

/* sw de habilitacion de procesos */
$rqvhc1 = query("SELECT estado,fecha,id_modalidad FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqvhc2 = fetch($rqvhc1);
if ($rqvhc2['estado'] == '1' || $rqvhc2['estado'] == '2') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}

$fecha_curso = $rqvhc2['fecha'];
$id_modalidad_curso = (int) $rqvhc2['id_modalidad'];

/* sw_curso_virtual */
$sw_curso_virtual = false;
$qrcoe1 = query("SELECT count(*) AS total FROM cursos_rel_cursoonlinecourse WHERE id_curso='$id_curso' AND estado='1' ORDER BY id DESC limit 1 ");
$qrcoe2 = fetch($qrcoe1);
$cnt_cursos_cirtuales_asociados = (int) $qrcoe2['total'];
if ($cnt_cursos_cirtuales_asociados > 0 && ($id_modalidad_curso == 2 || $id_modalidad_curso == 3 || $id_modalidad_curso == 4)) {
    $sw_curso_virtual = true;
}

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
if (isset_post('busc') && post('busc')!='no-id' && post('busc')!='') {
    $busqueda = post('busc');
    if(strpos($busqueda,'ids_')>0){
        $qr_busqueda = " AND ( p.id IN (".str_replace('__ids_','',$busqueda).") ) ";
    }else{
        $qr_busqueda = " AND ( p.id='$busqueda' OR p.nombres LIKE '%$busqueda%' OR p.apellidos LIKE '%$busqueda%' OR p.correo LIKE '%$busqueda%' OR p.id_proceso_registro IN (select id from cursos_proceso_registro where codigo='$busqueda') ) ";
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


/* datos de curso */
$rqc1 = query("SELECT id_certificado,id_certificado_2,id_certificado_3,id_material,titulo,mailto_subject,mailto_content,sw_askfactura,sw_ipelc,mod_notas FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$nombre_curso = $rqc2['titulo'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
$id_certificado_3_curso = $rqc2['id_certificado_3'];
$sw_askfactura_curso = $rqc2['sw_askfactura'];
$sw_ipelc_curso = $rqc2['sw_ipelc'];
$mod_notas = $rqc2['mod_notas'];
$id_material_curso = $rqc2['id_material'];
$mailto_subject = str_replace(' ','%20',$rqc2['mailto_subject']);
$mailto_content = str_replace(' ','%20',str_replace(array("\r\n", "\n\r", "\r", "\n"),'%0D%0A',$rqc2['mailto_content']));
$whatsto_subject = $rqc2['mailto_subject'].'%0D%0A'.str_replace(array("\r\n", "\n\r", "\r", "\n"),'%0D%0A',$rqc2['mailto_content']);

/* sw_turno */
$sw_turnos = false;
$rqtc1 = query("SELECT id,titulo FROM cursos_turnos WHERE id_curso='$id_curso'  ");
if (num_rows($rqtc1) > 0) {
    $sw_turnos = true;
    while ($rqtc2 = fetch($rqtc1)) {
        $turno[$rqtc2['id']] = $rqtc2['titulo'];
    }
}

/* pago */
$qr_pago = "";

if (isset_post('pago')) {
    $pago = post('pago');
    switch ($pago) {
        case 'conpago':
            $qr_pago = " AND p.id_proceso_registro IN (select id from cursos_proceso_registro where sw_pago_enviado='1' AND id_modo_pago<>'10' AND id_curso='$id_curso' ) ";
            break;
        case 'sinpago':
            $qr_pago = " AND p.id_proceso_registro IN (select id from cursos_proceso_registro where sw_pago_enviado='0' AND id_curso='$id_curso' ) ";
            $pago = '';
            break;
        case 'gratuito':
            $qr_pago = " AND p.id_proceso_registro IN (select id from cursos_proceso_registro where sw_pago_enviado='1' AND id_modo_pago='10' AND id_curso='$id_curso' ) ";
            $pago = '';
            break;
        default:
            break;
    }
}

/* sw cursos_cupones_infosicoes */
$sw_cupon_infosicoes = false;
$id_cupon_infosicoes = 0;
$rqveci1 = query("SELECT id FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' LIMIT 1 ");
if (num_rows($rqveci1) > 0) {
    $sw_cupon_infosicoes = true;
    $rqveci2 = fetch($rqveci1);
    $id_cupon_infosicoes = $rqveci2['id'];
}

/* query principal */
//$resultado1 = query("SELECT p.*,(d.nombre)dr_nombre_departamento FROM cursos_participantes p LEFT JOIN departamentos d ON p.id_departamento=d.id WHERE p.id NOT IN (select id_participante from cursos_part_apartados) AND p.id_curso='$id_curso' AND p.estado='1' $qr_busqueda $qr_turno $qr_pago ORDER BY p.id DESC ");
$resultado1 = query("SELECT * FROM (SELECT 
p.*,count(p.id_curso)cursos_tomados,(sum(pr.monto_deposito))inversion_total 
FROM cursos_participantes p 
INNER JOIN cursos_proceso_registro pr ON pr.id=p.id_proceso_registro 
INNER JOIN cursos c ON c.id=p.id_curso 
WHERE p.correo<>'' AND pr.sw_pago_enviado='1' AND pr.monto_deposito>0 AND p.estado=1 AND nombres<>''
AND c.titulo NOT LIKE '%icrosoft%' AND c.titulo NOT LIKE '%word%' AND c.titulo NOT LIKE '%excel%' AND c.titulo NOT LIKE '%power%' 
AND c.titulo NOT LIKE '%windows%' AND c.titulo NOT LIKE '%outlook%' 
AND p.id IN (select id from cursos_participantes where id_curso in (select id from cursos where titulo like '%ley%' or titulo like '%aymara%' or titulo like '%quechua%' )) 
GROUP BY p.correo 
ORDER BY cursos_tomados DESC, inversion_total DESC)tabla_auxiliar WHERE cursos_tomados>=3 LIMIT 106 ");



/* contador */
$cnt = num_rows($resultado1);

/* aux ids almacenador */
$aux_idsalmacenador = '0';

/* habilitador de cursos gratuitos asociados */
$sw_curso_gratuito_asociado = false;
$rqvcga1 = query("SELECT id FROM cursos_rel_cursofreecur WHERE id_curso='$id_curso' ORDER BY id DESC limit 1");
if(num_rows($rqvcga1)>0){
    $sw_curso_gratuito_asociado = true;
}

/* $sw_col_material */
$sw_col_material = false;
$rqcmc1 = query("SELECT count(*) AS total FROM materiales_curso WHERE id_curso='$id_curso' ");
$rqcmc2 = fetch($rqcmc1);
if ($id_modalidad_curso==4 && ((int)$rqcmc2['total']>0) ) {
    $sw_col_material = true;
}

/* $sw_col_act_suev */
$sw_col_act_suev = false;
if ($id_modalidad_curso==4) {
    $sw_col_act_suev = true;
}
?>

<style>
    .reg-fuerafecha .simple-td{
        background: #faf2f2 !important;
    }
</style>

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">#</th>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Nombre</th>
                <th class="simple-td" style="padding-top: 2px;padding-bottom: 2px;">Apellidos</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $sw_exist_cert_uno = false;
            $sw_exist_cert_dos = false;
            $sw_exist_cert_tres = false;
            $sw_existencia_facturas = false;

            if (num_rows($resultado1) == 0) {
                echo "<tr><td colspan='11'>No existen participantes registrados.</td></tr>";
            }

            $aux_line_correos = '<button onclick="copyToClipboard(\'agr10-1\')">Copiar</button> <span id="agr10-1">';
            $aux_cnt_line_correos = 0;
            $aux_cnt_line_correos_block = 1;
            
            while ($participante = fetch($resultado1)) {

                /* datos de registro */
                $rqrp1 = query("SELECT id,codigo,fecha_registro,celular_contacto,correo_contacto,id_modo_pago,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_cobro_khipu,sw_pago_enviado,id_administrador FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
                $data_registro = fetch($rqrp1);
                $id_proceso_de_registro = $data_registro['id'];
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

                $sw_pago_enviado = $data_registro['sw_pago_enviado'];
                $id_cobro_khipu = $data_registro['id_cobro_khipu'];

                $aux_idsalmacenador .= ',' . $participante['id'];

                $tr_class = '';
                $text_msj = '';
                if (strtotime(date("Y-m-d", strtotime($data_registro['fecha_registro']))) > strtotime($fecha_curso)) {
                    $tr_class = 'reg-fuerafecha';
                    $text_msj = '<br/><i style="color:red;font-size:7pt;">Fuera de fecha</i>';
                }
                
                $aux_line_correos .= $participante['correo'].', ';
                if(++$aux_cnt_line_correos==10){
                    $aux_cnt_line_correos=0;
                    $aux_line_correos .= '</span><br><hr><br>'.'<button onclick="copyToClipboard(\'agr10-'.++$aux_cnt_line_correos_block.'\')">Copiar</button> <span id="agr10-'.$aux_cnt_line_correos_block.'">';
                }
                
                $id_pais = $participante['id_pais'];
                
                /* codigo pais */
                $rqdcw1 = query("SELECT codigo FROM paises WHERE id='$id_pais' LIMIT 1 ");
                $rqdcw2 = fetch($rqdcw1);
                $codigo_pais = $rqdcw2['codigo'];
                ?>
                <tr id="ajaxbox-tr-participante-<?php echo $participante['id']; ?>" class="<?php echo $tr_class; ?>">
                    <td class="simple-td">
                        <?php echo $cnt--; ?>
                        <br/>
                        <br/>
                        <b class="btn btn-default btn-xs" onclick="historial_participante('<?php echo $participante['id']; ?>');" data-toggle="modal" data-target="#MODAL-historial_participante">
                            <i class="fa fa-list" style="color:#8f8f8f;"></i>
                        </b>
                    </td>
                    <td class="nombre-td" onclick="check_participante('<?php echo $participante['id']; ?>');" style="cursor:pointer;">
                        <?php
                        echo trim($participante['nombres']);
                        ?>
                        <br/>
                        <b style="font-size:7pt;color:#2180be;">No. <?php echo $participante['numeracion']; ?></b>
                        <?php
                        echo "<br/>";
                        echo '<span style="color:gray;font-size:8pt;"><a href="mailto:' . $participante['correo'] . '?subject='. $mailto_subject . '&body='.$mailto_content.'">' . $participante['correo'] . '</a><br/><a target="_blank" href="https://api.whatsapp.com/send?phone=' . $codigo_pais .$participante['celular'] . '&text='.($mailto_content).'" id="c'.$participante['celular'].'">' . $participante['celular'] . '</a></span>';
                    

                        $nombre_participante = $participante['nombres'].' '.$participante['apellidos'];

                        $enlace_registro = "https://cursos.bo/reg-free/".$participante['id']."/".md5($participante['id'].'key15613').".html";
                    
                        $box_msj = 'Estimad@ '.$nombre_participante.',
__Notamos que usted tomó varios cursos en nuestra plataforma y tiene la intención de seguir aprendiendo, es por eso que queremos ofrecerle el siguiente curso de manera completamente gratuita.
__*Curso Gerencia Pública en el Marco de la Gestión por Resultados En VIVO mediante Google Meet*__(del 14 al 16 de Diciembre de 19:30 a 21:30, Certificado con valor curricular Digital con carga Horaria al finalizar el Curso)
__Para poder incribirse a este curso solamente debe ingresar al siguiente link y confirmar su asistencia:
__'.$enlace_registro.'';



                        $cont_msj = str_replace(array(' ','__'),array('%20','%0A'),$box_msj);
                        ?>
                        <br>
                        <a href="https://api.whatsapp.com/send?phone=591<?php echo $participante['celular']; ?>&text=<?php echo $cont_msj; ?>" target="_blank">
                            <img src="https://www.cursos.bo/contenido/imagenes/wapicons/wap-hoja-0.jpg" style="height: 25px;border-radius: 20%;cursor:pointer;">
                        </a>
                        &nbsp;&nbsp;
                        <a href="https://api.whatsapp.com/send?phone=591<?php echo $participante['celular']; ?>&text=" target="_blank">
                            <img src="https://www.cursos.bo/contenido/imagenes/wapicons/wap-init-0.jpg" style="height: 25px;border-radius: 20%;cursor:pointer;">
                        </a>
                    </td>
                    <td class="nombre-td" onclick="check_participante('<?php echo $participante['id']; ?>');" style="cursor:pointer;">
                        <?php
                        echo trim($participante['apellidos']);
                        ?>
                        <br/>
                        <br/>
                        <b style="font-size: 10pt;color: #505050;"><?php echo $participante['ci'] . ' <span style="font-weight:normal">' . $participante['ci_expedido'] . '</span>'; ?></b>
                        <br>
                        <br>
                        <b style="color:orange;"><?php echo strtoupper($participante['dr_nombre_departamento']); ?></b>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
