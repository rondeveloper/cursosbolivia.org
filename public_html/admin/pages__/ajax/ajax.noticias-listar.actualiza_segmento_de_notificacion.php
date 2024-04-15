<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador() && !isset_organizador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_noticia = post('id_noticia');

/* registro */
$rqdc1 = query("SELECT * FROM publicaciones WHERE id='$id_noticia' ORDER BY id DESC limit 1 ");
$noticia = fetch($rqdc1);

$noticia_id_ciudad = (int) $noticia['id_ciudad'];
$id_categoria = (int) $noticia['id_categoria'];

/* id departamento */
$rqdd1 = query("SELECT id_departamento FROM ciudades WHERE id='$noticia_id_ciudad' LIMIT 1 ");
$rqdd2 = fetch($rqdd1);
$id_departamento = $rqdd2['id_departamento'];
?>

<?php
/* HABILITADOS */

/* etiquetas seleccionadas */
$ids_etiquetas = '0';
$rqdctob1 = query("SELECT id FROM cursos_tags WHERE id_categoria='$id_categoria' ");
while ($rqdctob2 = fetch($rqdctob1)) {
    $id_tag = $rqdctob2['id'];
    if (isset_post('tag' . $id_tag)) {
        $ids_etiquetas .= ',' . $id_tag;
    }
}

/* $total_usuarios_registrados */
if($noticia_id_ciudad!==0){
    $rqtur1 = query("SELECT count(*) AS total FROM cursos_usuarios WHERE sw_notif=1 AND email LIKE '%@%' AND email NOT IN (select email from cursos_rel_notifnotiemail where id_noticia='$id_noticia') AND id_ciudad IN (0,$noticia_id_ciudad) ");
}else{
    $rqtur1 = query("SELECT count(*) AS total FROM cursos_usuarios WHERE sw_notif=1 AND email LIKE '%@%' AND email NOT IN (select email from cursos_rel_notifnotiemail where id_noticia='$id_noticia') ");
}
$rqtur2 = fetch($rqtur1);
$total_usuarios_registrados = $rqtur2['total'];

/* $total_anteriores_participantes */
if($noticia_id_ciudad!==0){
    $rqturap1 = query("SELECT count(*) AS total FROM cursos_participantes WHERE sw_notif=1 AND correo LIKE '%@%' AND id_curso IN ("
        . "select id from cursos where id_ciudad='$noticia_id_ciudad' and id in (select id_curso from cursos_rel_cursostags where id_tag in ($ids_etiquetas) ) "
        . ") AND correo NOT IN (select email from cursos_rel_notifnotiemail where id_noticia='$id_noticia' ) ");
}else{
    $rqturap1 = query("SELECT count(*) AS total FROM cursos_participantes WHERE sw_notif=1 AND correo LIKE '%@%' AND id_curso IN (select id_curso from cursos_rel_cursostags where id_tag in ($ids_etiquetas) ) AND correo NOT IN (select email from cursos_rel_notifnotiemail where id_noticia='$id_noticia' ) ");
}
$rqturap2 = fetch($rqturap1);
$total_anteriores_participantes = $rqturap2['total'];

/* $total_suscripcion_navegador */
//*$rqdn1 = query("SELECT COUNT(*) AS total FROM cursos_suscnav WHERE estado='1' AND id_departamento IN ('0','$id_departamento') AND id NOT IN (select id_tokensusc from cursos_rel_notifnotisuscpush where id_noticia='$id_noticia' and fecha_envio=CURDATE() ) ");
if($noticia_id_ciudad!==0){
    $rqdn1 = query("SELECT COUNT(*) AS total FROM cursos_suscnav WHERE estado='1' AND id_departamento IN ('0','$id_departamento') ");
}else{
    $rqdn1 = query("SELECT COUNT(*) AS total FROM cursos_suscnav WHERE estado='1' ");
}
$rqdn2 = fetch($rqdn1);
$total_suscripcion_navegador = $rqdn2['total'];

/* total_pushnav_enviados_hoy */
$rqdenp1 = query("SELECT count(*) AS total FROM cursos_rel_notifnotisuscpush WHERE id_noticia='$id_noticia' and fecha_envio=CURDATE() ");
$rqdenp2 = fetch($rqdenp1);
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
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('usuariosreg', 1);" id="btn-usuariosreg-1">ENVIAR BLOQUE 1</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('usuariosreg', 2);" id="btn-usuariosreg-2">ENVIAR BLOQUE 2</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('usuariosreg', 3);" id="btn-usuariosreg-3">ENVIAR BLOQUE 3</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('usuariosreg', 4);" id="btn-usuariosreg-4">ENVIAR BLOQUE 4</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('usuariosreg', 5);" id="btn-usuariosreg-5">ENVIAR BLOQUE 5</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('usuariosreg', 6);" id="btn-usuariosreg-6">ENVIAR BLOQUE 6</button>
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
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('anterpart', 1);" id="btn-anterpart-1">ENVIAR BLOQUE 1</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('anterpart', 2);" id="btn-anterpart-2">ENVIAR BLOQUE 2</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('anterpart', 3);" id="btn-anterpart-3">ENVIAR BLOQUE 3</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('anterpart', 4);" id="btn-anterpart-4">ENVIAR BLOQUE 4</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('anterpart', 5);" id="btn-anterpart-5">ENVIAR BLOQUE 5</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('anterpart', 6);" id="btn-anterpart-6">ENVIAR BLOQUE 6</button>
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
            <textarea class="form-control" id="texto-push" placeholder="Texto principal notificaci&oacute;n push." style="height: 70px;"><?php echo $noticia['titulo']; ?></textarea>
        </td>
        <td style="text-align:center;vertical-align: middle;">
            <span style="font-size:15pt;"><?php echo $total_suscripcion_navegador; ?></span>
        </td>
        <td id="boxsend-pushnav">
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('pushnav', 1);" id="btn-pushnav-1">ENVIAR BLOQUE 1</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('pushnav', 2);" id="btn-pushnav-2">ENVIAR BLOQUE 2</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('pushnav', 3);" id="btn-pushnav-3">ENVIAR BLOQUE 3</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('pushnav', 4);" id="btn-pushnav-4">ENVIAR BLOQUE 4</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('pushnav', 5);" id="btn-pushnav-5">ENVIAR BLOQUE 5</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_noticia('pushnav', 6);" id="btn-pushnav-6">ENVIAR BLOQUE 6</button>
        </td>
    </tr>
</table>

