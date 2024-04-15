<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador() && !isset_organizador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_curso = post('id_curso');

/* curso */
$rqdc1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = mysql_fetch_array($rqdc1);

$curso_id_ciudad = (int) $curso['id_ciudad'];
$id_categoria = (int) $curso['id_categoria'];

/* id departamento */
$rqdd1 = query("SELECT id_departamento FROM ciudades WHERE id='$curso_id_ciudad' LIMIT 1 ");
$rqdd2 = mysql_fetch_array($rqdd1);
$id_departamento = $rqdd2['id_departamento'];
?>

<?php
/* HABILITADOS */

/* etiquetas seleccionadas */
$ids_etiquetas = '0';
$rqdctob1 = query("SELECT id FROM cursos_tags WHERE id_categoria='$id_categoria' ");
while ($rqdctob2 = mysql_fetch_array($rqdctob1)) {
    $id_tag = $rqdctob2['id'];
    if (isset_post('tag' . $id_tag)) {
        $ids_etiquetas .= ',' . $id_tag;
    }
}

/* qr ciudades */
$qr_ciudades_1 = " AND id_ciudad IN (0,$curso_id_ciudad) ";
if($curso['id_modalidad'] == '3'){
    $qr_ciudades_1 = "";
}
/* $total_usuarios_registrados */
$rqtur1 = query("SELECT count(*) AS total FROM cursos_usuarios WHERE sw_notif=1 AND email LIKE '%@%' AND email NOT IN (select email from cursos_rel_notifcuremail where id_curso='$id_curso') AND email NOT IN (select email from cursos_unsubscribe_ustag where id_tag IN (SELECT id_tag FROM cursos_rel_cursostags WHERE id_curso='$id_curso')) $qr_ciudades_1 ");
$rqtur2 = mysql_fetch_array($rqtur1);
$total_usuarios_registrados = $rqtur2['total'];

/* qr ciudades */
$qr_ciudades_2 = " id_ciudad='$curso_id_ciudad' and ";
if($curso['id_modalidad'] == '3'){
    $qr_ciudades_2 = "";
}
/* $total_anteriores_participantes */
$rqturap1 = query("SELECT count(*) AS total FROM cursos_participantes WHERE sw_notif=1 AND correo LIKE '%@%' AND id_curso IN ("
        . "select id from cursos where $qr_ciudades_2 id in (select id_curso from cursos_rel_cursostags where id_tag in ($ids_etiquetas) ) "
        . ") AND correo NOT IN (select email from cursos_rel_notifcuremail where id_curso='$id_curso' ) AND correo NOT IN (select email from cursos_unsubscribe_ustag where id_tag IN (SELECT id_tag FROM cursos_rel_cursostags WHERE id_curso='$id_curso')) ");
$rqturap2 = mysql_fetch_array($rqturap1);
$total_anteriores_participantes = $rqturap2['total'];

/* qr ciudades */
$qr_ciudades_3 = " AND id_ciudad='$curso_id_ciudad' ";
if($curso['id_modalidad'] == '3'){
    $qr_ciudades_3 = "";
}
/* $total_participantes_sin_asistir */
$rqturapn1 = query("SELECT count(*) AS total FROM cursos_participantes WHERE sw_notif=1 AND estado='0' AND correo LIKE '%@%' AND id_curso IN ("
        . "select id from cursos where id in (select id_curso from cursos_rel_cursostags where id_tag in (SELECT id_tag FROM cursos_rel_cursostags WHERE id_curso='$id_curso') ) $qr_ciudades_3 "
        . ") AND correo NOT IN (select email from cursos_rel_notifcuremail where id_curso='$id_curso' ) AND correo NOT IN (select email from cursos_unsubscribe_ustag where id_tag IN (SELECT id_tag FROM cursos_rel_cursostags WHERE id_curso='$id_curso')) ");

$rqturapn2 = mysql_fetch_array($rqturapn1);
$total_participantes_sin_asistir = $rqturapn2['total'];

/* qr ciudades */
$qr_ciudades_4 = " AND id_departamento IN ('0','$id_departamento') ";
if($curso['id_modalidad'] == '3'){
    $qr_ciudades_4 = "";
}
/* $total_suscripcion_navegador */
$rqdn1 = query("SELECT COUNT(*) AS total FROM cursos_suscnav WHERE estado='1' $qr_ciudades_4 AND id NOT IN (select id_tokensusc from cursos_rel_notifsuscpush where id_curso='$id_curso' and fecha_envio=CURDATE() ) ");
//***$rqdn1 = query("SELECT COUNT(*) AS total FROM cursos_suscnav WHERE estado='1' $qr_ciudades_4 ");
$rqdn2 = mysql_fetch_array($rqdn1);
$total_suscripcion_navegador = $rqdn2['total'];

/* total_pushnav_enviados_hoy */
$rqdenp1 = query("SELECT count(*) AS total FROM cursos_rel_notifsuscpush WHERE id_curso='$id_curso' and fecha_envio=CURDATE() ");
$rqdenp2 = mysql_fetch_array($rqdenp1);
$total_pushnav_enviados_hoy = $rqdenp2['total'];
?>
<table class="table table-bordered table-hover table-striped table-responsive">
    <tr>
        <td>
            <b>[EMAIL]</b>
            <br/>
            USUARIOS REGISTRADOS
        </td>
        <td style="text-align:center;vertical-align: middle;">
            <span style="font-size:15pt;"><?php echo $total_usuarios_registrados; ?></span>
        </td>
        <td id="boxsend-usuariosreg">
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('usuariosreg', 1);" id="btn-usuariosreg-1">ENVIAR BLOQUE 1</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('usuariosreg', 2);" id="btn-usuariosreg-2">ENVIAR BLOQUE 2</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('usuariosreg', 3);" id="btn-usuariosreg-3">ENVIAR BLOQUE 3</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('usuariosreg', 4);" id="btn-usuariosreg-4">ENVIAR BLOQUE 4</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('usuariosreg', 5);" id="btn-usuariosreg-5">ENVIAR BLOQUE 5</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('usuariosreg', 6);" id="btn-usuariosreg-6">ENVIAR BLOQUE 6</button>
        </td>
    </tr>
    <tr>
        <td>
            <b>[EMAIL]</b>
            <br/>
            ANTERIORES PARTICIPANTES
        </td>
        <td style="text-align:center;vertical-align: middle;">
            <span style="font-size:15pt;"><?php echo $total_anteriores_participantes; ?></span>
        </td>
        <td id="boxsend-anterpart">
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('anterpart', 1);" id="btn-anterpart-1">ENVIAR BLOQUE 1</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('anterpart', 2);" id="btn-anterpart-2">ENVIAR BLOQUE 2</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('anterpart', 3);" id="btn-anterpart-3">ENVIAR BLOQUE 3</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('anterpart', 4);" id="btn-anterpart-4">ENVIAR BLOQUE 4</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('anterpart', 5);" id="btn-anterpart-5">ENVIAR BLOQUE 5</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('anterpart', 6);" id="btn-anterpart-6">ENVIAR BLOQUE 6</button>
        </td>
    </tr>
    <tr>
        <td>
            <b>[EMAIL]</b>
            <br/>
            ANTERIORES QUE NO ASISTIERON
        </td>
        <td style="text-align:center;vertical-align: middle;">
            <span style="font-size:15pt;"><?php echo $total_participantes_sin_asistir; ?></span>
        </td>
        <td id="boxsend-partnoasist">
            <button class="btn btn-success btn-block btn-sm" onclick="enviar_notificacion_curso('partnoasist', 0);" id="btn-partnoasist-0">ENVIAR</button>
        </td>
    </tr>
    <tr>
        <td>
            <b>[NOTIFICACION]</b>
            <br/>
            APLICACION MOVIL
        </td>
        <td style="text-align:center;vertical-align: middle;">
            <span style="font-size:15pt;">0</span>
        </td>
        <td id="boxsend-appmovil">
            <button class="btn btn-success btn-block btn-sm" onclick="enviar_notificacion_curso('appmovil', 0);" id="btn-appmovil-0">ENVIAR</button>
        </td>
    </tr>
    <tr>
        <td>
            <b>[PUSH-NAVEGADOR]</b>
            <br/>
            SUSCRIPCION POR NAVEGADOR
            <br/>
            <br/>
            <i>Enviados HOY</i>: &nbsp; <b style="color:blue;"><?php echo $total_pushnav_enviados_hoy; ?></b>
            <br/>
            <br/>
            <textarea class="form-control" id="texto-push" placeholder="Texto principal notificaci&oacute;n push." style="height: 70px;"><?php echo $curso['titulo']; ?></textarea>
        </td>
        <td style="text-align:center;vertical-align: middle;">
            <span style="font-size:15pt;"><?php echo $total_suscripcion_navegador; ?></span>
        </td>
        <td id="boxsend-pushnav">
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('pushnav', 1);" id="btn-pushnav-1">ENVIAR BLOQUE 1</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('pushnav', 2);" id="btn-pushnav-2">ENVIAR BLOQUE 2</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('pushnav', 3);" id="btn-pushnav-3">ENVIAR BLOQUE 3</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('pushnav', 4);" id="btn-pushnav-4">ENVIAR BLOQUE 4</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('pushnav', 5);" id="btn-pushnav-5">ENVIAR BLOQUE 5</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_curso('pushnav', 6);" id="btn-pushnav-6">ENVIAR BLOQUE 6</button>
        </td>
    </tr>
</table>

