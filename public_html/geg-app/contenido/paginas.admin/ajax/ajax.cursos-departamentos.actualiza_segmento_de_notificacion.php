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
$id_departamento = post('id_departamento');
?>

<?php
/* HABILITADOS */

$qr_ciudad_urd = "";
$qr_ciudad_prt = "";
$qr_ciudad_usrp = "";
if ($id_departamento !== '0' && $id_departamento !== '10') {
    $qr_ciudad_urd = " AND (id_ciudad='0' OR id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ) ";
    $qr_ciudad_prt = " AND id_curso IN ("
        . "select id from cursos where id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') "
        . ") ";
    $qr_ciudad_usrp = " AND id_departamento IN ('0','$id_departamento') ";
}

/* $total_usuarios_registrados */
$rqtur1 = query("SELECT count(*) AS total FROM cursos_usuarios WHERE sw_notif=1 AND email LIKE '%@%' AND email NOT IN (select email from cursos_rel_notifdepemail where id_departamento='$id_departamento' AND fecha=CURDATE()) $qr_ciudad_urd ");
$rqtur2 = mysql_fetch_array($rqtur1);
$total_usuarios_registrados = $rqtur2['total'];

/* $total_anteriores_participantes presenciales */
$rqturap1 = query("SELECT count(*) AS total FROM cursos_participantes WHERE sw_notif=1 AND id_curso IN (select id from cursos where id_modalidad IN (1) ) AND correo LIKE '%@%' $qr_ciudad_prt AND correo NOT IN (select email from cursos_rel_notifdepemail where id_departamento='$id_departamento' AND fecha=CURDATE() )");
$rqturap2 = mysql_fetch_array($rqturap1);
$total_anteriores_participantes_presenciales = $rqturap2['total'];

/* $total_anteriores_participantes virtuales */
$rqturapv1 = query("SELECT count(*) AS total FROM cursos_participantes WHERE sw_notif=1 AND id_curso IN (select id from cursos where id_modalidad IN (2,3) ) AND correo LIKE '%@%' $qr_ciudad_prt AND correo NOT IN (select email from cursos_rel_notifdepemail where id_departamento='$id_departamento' AND fecha=CURDATE() )");
$rqturapv2 = mysql_fetch_array($rqturapv1);
$total_anteriores_participantes_virtuales = $rqturapv2['total'];

/* $total_suscripcion_navegador */
$rqdn1 = query("SELECT COUNT(*) AS total FROM cursos_suscnav WHERE estado='1' $qr_ciudad_usrp  AND id NOT IN (select id_tokensusc from cursos_rel_notifdeppush where id_departamento='$id_departamento' and fecha_envio=CURDATE() ) ");
$rqdn2 = mysql_fetch_array($rqdn1);
$total_suscripcion_navegador = $rqdn2['total'];

/* total_pushnav_enviados_hoy */
$rqdenp1 = query("SELECT count(*) AS total FROM cursos_rel_notifdeppush WHERE id_departamento='$id_departamento' and fecha_envio=CURDATE() ");
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
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('usuariosreg', 1);" id="btn-usuariosreg-1">ENVIAR BLOQUE 1</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('usuariosreg', 2);" id="btn-usuariosreg-2">ENVIAR BLOQUE 2</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('usuariosreg', 3);" id="btn-usuariosreg-3">ENVIAR BLOQUE 3</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('usuariosreg', 4);" id="btn-usuariosreg-4">ENVIAR BLOQUE 4</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('usuariosreg', 5);" id="btn-usuariosreg-5">ENVIAR BLOQUE 5</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('usuariosreg', 6);" id="btn-usuariosreg-6">ENVIAR BLOQUE 6</button>
        </td>
    </tr>
    <tr>
        <td>
            <b>[EMAIL]</b>
            <br/>
            ANTERIORES PARTICIPANTES
            <br>
            <b class="text-info">PRESENCIALES</b>
        </td>
        <td style="text-align:center;vertical-align: middle;">
            <span style="font-size:15pt;"><?php echo $total_anteriores_participantes_presenciales; ?></span>
        </td>
        <td id="boxsend-anterpart">
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('anterpart-pres', 1);" id="btn-anterpart-1">ENVIAR BLOQUE 1</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('anterpart-pres', 2);" id="btn-anterpart-2">ENVIAR BLOQUE 2</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('anterpart-pres', 3);" id="btn-anterpart-3">ENVIAR BLOQUE 3</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('anterpart-pres', 4);" id="btn-anterpart-4">ENVIAR BLOQUE 4</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('anterpart-pres', 5);" id="btn-anterpart-5">ENVIAR BLOQUE 5</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('anterpart-pres', 6);" id="btn-anterpart-6">ENVIAR BLOQUE 6</button>
        </td>
    </tr>
    <tr>
        <td>
            <b>[EMAIL]</b>
            <br/>
            ANTERIORES PARTICIPANTES
            <br>
            <b class="text-info">VIRTUALES</b>
        </td>
        <td style="text-align:center;vertical-align: middle;">
            <span style="font-size:15pt;"><?php echo $total_anteriores_participantes_virtuales; ?></span>
        </td>
        <td id="boxsend-anterpart">
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('anterpart-vir', 1);" id="btn-anterpart-1">ENVIAR BLOQUE 1</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('anterpart-vir', 2);" id="btn-anterpart-2">ENVIAR BLOQUE 2</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('anterpart-vir', 3);" id="btn-anterpart-3">ENVIAR BLOQUE 3</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('anterpart-vir', 4);" id="btn-anterpart-4">ENVIAR BLOQUE 4</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('anterpart-vir', 5);" id="btn-anterpart-5">ENVIAR BLOQUE 5</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('anterpart-vir', 6);" id="btn-anterpart-6">ENVIAR BLOQUE 6</button>
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
            <?php
            if($id_departamento!=='0'){
                $rqdic1 = query("SELECT nombre FROM departamentos WHERE id='$id_departamento' ");
                $rqdic2 = mysql_fetch_array($rqdic1);
                $nom_departamento = $rqdic2['nombre'];
            }else{
                $nom_departamento = 'Bolivia';
            }
            $texto_push = 'Cursos para esta semana en '.$nom_departamento;
            
            if($id_departamento=='10'){
                $texto_push = 'Cursos VIRTUALES para esta semana';
            }
            ?>
            <textarea class="form-control" id="texto-push" placeholder="Texto principal notificaci&oacute;n push." style="height: 70px;"><?php echo $texto_push; ?></textarea>
        </td>
        <td style="text-align:center;vertical-align: middle;">
            <span style="font-size:15pt;"><?php echo $total_suscripcion_navegador; ?></span>
        </td>
        <td id="boxsend-pushnav">
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('pushnav', 1);" id="btn-pushnav-1">ENVIAR BLOQUE 1</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('pushnav', 2);" id="btn-pushnav-2">ENVIAR BLOQUE 2</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('pushnav', 3);" id="btn-pushnav-3">ENVIAR BLOQUE 3</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('pushnav', 4);" id="btn-pushnav-4">ENVIAR BLOQUE 4</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('pushnav', 5);" id="btn-pushnav-5">ENVIAR BLOQUE 5</button>
            <br/>
            <button class="btn btn-success btn-block btn-xs" onclick="enviar_notificacion_cursos('pushnav', 6);" id="btn-pushnav-6">ENVIAR BLOQUE 6</button>
        </td>
    </tr>
</table>

