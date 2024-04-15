<?php

session_set_cookie_params(86400); 
ini_set('session.gc_maxlifetime', 86400);

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* ip */
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_coneccion = mysql_real_escape_string($_SERVER['HTTP_X_FORWARDED_FOR']);
} else {
    $ip_coneccion = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
}
$user_agent = mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);
$hash_useragent = md5($user_agent);

if (isset_administrador()) {

    /* DATA LOGIN */
    $id_administrador = administrador('id');
    $fecha_actual = date("Y-m-d H:i:s");
    
    administradorSet('id', administrador('id'));
    
    $fecha_range_login = date("Y-m-d H:i:s", strtotime("-5 minutes", strtotime($fecha_actual)));
    /* */
    
    $rqds1 = query("SELECT * FROM login_log WHERE id_administrador='$id_administrador' AND fecha_final>'$fecha_range_login' AND ip='$ip_coneccion' AND hash_useragent LIKE '$hash_useragent' ORDER BY id DESC limit 1");
    if(mysql_num_rows($rqds1)==0){
        /* NUEVO LOGIN*/
        query("INSERT INTO login_log (
               id_administrador,
               fecha_inicio,
               fecha_final,
               user_agent,
               ip,
               hash_useragent,
               estado
               ) VALUES (
               '$id_administrador',
               '$fecha_actual',
               '$fecha_actual',
               '$user_agent',
               '$ip_coneccion',
               '$hash_useragent',
               '1'
               ) ");
    }else{
        $rqds2 = mysql_fetch_array($rqds1);
        $id_login_log = $rqds2['id'];
        query("UPDATE login_log SET fecha_final='$fecha_actual' WHERE id='$id_login_log' ORDER BY id DESC limit 1 ");
    }
    echo "TDH Session: ".time().":: ".date("H:i:s") ;
}
