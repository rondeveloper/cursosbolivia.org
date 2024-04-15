<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');


/* curso */
$rqdc1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rqdc1);
$id_curso = $curso['id'];
$nombre_curso = $curso['titulo'];
$id_ciudad_curso = $curso['id_ciudad'];
$id_modalidad_curso = $curso['id_modalidad'];
$fecha_curso = fecha_curso_D_d_m($curso['fecha']);
$costo_curso = $curso['costo'];
$id_docente_curso = $curso['id_docente'];

$numero_wamsm_predeternimado = '69714008';
$rqdwn1 = query("SELECT w.numero FROM whatsapp_numeros w INNER JOIN cursos_rel_cursowapnum r ON r.id_whats_numero=w.id WHERE r.id_curso='" . $id_curso . "' ORDER BY r.id ASC LIMIT 1 ");
if (num_rows($rqdwn1) == 0) {
    $whats_numero_curso = $numero_wamsm_predeternimado;
} else {
    $rqdwn2 = fetch($rqdwn1);
    $whats_numero_curso = $rqdwn2['numero'];
}
$url_corta = $dominio . numIDcurso($id_curso) . '/';
$fb_txt_requisitos = $curso['fb_txt_requisitos'];
$fb_txt_dirigido = $curso['fb_txt_dirigido'];
$fb_hashtags = trim($curso['fb_hashtags']);

$htar1 = explode(',', $fb_hashtags);
$txt_hashtag = '';
foreach ($htar1 as $ht) {
    if ($ht !== '') {
        $txt_hashtag .= '#' . trim($ht) . ' ';
    }
}



/* ciudad */
$rqdcd1 = query("SELECT nombre FROM ciudades WHERE id='$id_ciudad_curso' LIMIT 1 ");
$rqdcd2 = fetch($rqdcd1);
$nombre_ciudad = str_replace(' ', '', str_replace('CHUQUISACA', 'SUCRE', strtoupper($rqdcd2['nombre'])));
$dt_ciudad = '#' . $nombre_ciudad . ' ';
if ($nombre_ciudad == 'BOLIVIA') {
    $dt_ciudad = '';
}

/* modalidad */
$dt_modalidad = '#CursoVirtual ';
if ($id_modalidad_curso == '1') {
    $dt_modalidad = '#CursoPresencial ';
}

/* docente */
$rqddct1 = query("SELECT nombres,apellidos FROM cursos_docentes WHERE id='$id_docente_curso' ");
if ($id_docente_curso != '0') {
    $rqddct2 = fetch($rqddct1);
    $nombre_docente = trim($rqddct2['nombres'] . ' ' . $rqddct2['apellidos']);
}

$url_corta = $dominio . numIDcurso($id_curso) . '/';
$url_corta_registro = $dominio .'R/'. $id_curso . '/';
$rqenc1 = query("SELECT e.enlace FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON e.id=r.id_enlace WHERE r.id_curso='".$id_curso."' AND r.estado=1 ");
if(num_rows($rqenc1)>0){
    $rqenc2 = fetch($rqenc1);
    $url_corta = $dominio.$rqenc2['enlace'] . "/";
    $url_corta_registro = $dominio.'R/'.$rqenc2['enlace'] . "/";
}


$data = trim(
    $dt_modalidad .
        $dt_ciudad .
        trim($txt_hashtag) .
        ' &#10148; ' .
        $nombre_curso .
        ' &#10148; ' .
        $fecha_curso .
        ' &#10148; ' .
        $fb_txt_requisitos .
        ' &#10148; ' .
        $fb_txt_dirigido .
        ' &#10148; Consultas &oacute; WhatsApp ' .
        $whats_numero_curso .
        ' &#10148; Registro y descuentos en ' . $url_corta . ' clic en la imagen para ver detalles <br>'
);

if (isset_post('data_detalle')) {
    $data .= '<br>
    📌Inversión del Curso ' . $costo_curso . ' 𝗕𝘀.
    <br>';
    if ($curso['sw_fecha2'] == '1' || $curso['sw_fecha3'] == '1') {
        $data .= '🔴DESCUENTOS(Pago en oficina, mediante dep&oacute;sito Bancarios, Transferencias y/o Giro TigoMoney)<br>';
    }
    if ($curso['sw_fecha2'] == '1') {
        $data .= '✅' . $curso['costo2'] . ' 𝗕𝘀. hasta el ' . fecha_curso_D_d_m($curso['fecha2']);
        $hour = date("H:i", strtotime($curso['fecha2']));
        if ($hour != '00:00') {
            $data .= ' hasta las ' . $hour;
        }
        $data .= '<br>';
    }
    if ($curso['sw_fecha3'] == '1') {
        $data .= '✅' . $curso['costo3'] . ' 𝗕𝘀. hasta el ' . fecha_curso_D_d_m($curso['fecha3']);
        $hour = date("H:i", strtotime($curso['fecha3']));
        if ($hour != '00:00') {
            $data .= ' hasta las ' . $hour;
        }
        $data .= '<br>';
    }
    if ($id_modalidad_curso != '1') {
        $data .= '💻Clases en línea (en vivo)
    <br>
    💻Acceso a la plataforma Virtual
    <br>
    📼Plataforma Zoom
    <br>';
    }
    if ($id_docente_curso != '0') {
        $data .= '📌Facilitador ' . $nombre_docente . ' (20 años de experiencia)<br>';
    }
    $data .= '🔻🔻🔻🔻🔻🔻🔻🔻🔻🔻🔻🔻🔻🔻🔻🔻🔻🔻
    <br>
    ' . $fecha_curso . ' (' . $curso['horarios'] . ')
    <br>
    🔺🔺🔺🔺🔺🔺🔺🔺🔺🔺🔺🔺🔺🔺🔺🔺🔺🔺
    <br>';
}

if (isset_post('data_forma_pago')) {
    $data .= getBancosTigoMoney($id_curso);
}

if (isset_post('data_reporte_pago')) {
    $data .= '<br>𝗥𝗲𝗽𝗼𝗿𝘁𝗮𝗿 𝗽𝗮𝗴𝗼 𝗲𝗻 : '.$url_corta_registro.' <br>';
}

if (isset_post('data_whatsapp')) {
    $mensaje_wamsm_predeternimado = 'Hola, tengo interes en el Curso ' . trim(str_replace(array('curso', 'Curso', 'CURSO'), '', $curso['titulo']));
    $rqdwn1 = query("SELECT w.numero FROM whatsapp_numeros w INNER JOIN cursos_rel_cursowapnum r ON r.id_whats_numero=w.id WHERE r.id_curso='" . $id_curso . "' ORDER BY r.id ASC LIMIT 1 ");
    if (num_rows($rqdwn1) == 0) {
        $numero_wamsm_predeternimado = '69714008';
        $cel_wamsm = '591' . $numero_wamsm_predeternimado;
    } else {
        $rqdwn2 = fetch($rqdwn1);
        $cel_wamsm = '591' . $rqdwn2['numero'];
    }
    $url_wap = 'https://wa.me/' . $cel_wamsm . '?text=' . str_replace('+', '%20', urlencode($mensaje_wamsm_predeternimado)) ;
    $short_link = solicitarShortLink($url_wap);
    query("UPDATE cursos SET short_link='$short_link' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $data .= '<br>𝗪𝗵𝗮𝘁𝘀𝗮𝗽𝗽📲: ' . $short_link . ' <br>';
}

if (isset_post('data_direccion')) {
    $rqddsc1 = query("SELECT * FROM sucursales WHERE estado=1 ");
    while($rqddsc2 = fetch($rqddsc1)){
        $data .= '<br>*Pago en Oficina '.$rqddsc2['nombre'].'*: '.$rqddsc2['direccion'].' ('.$rqddsc2['horarios_atencion'].') Móvil '.$rqddsc2['num_celular'].' <br><br>';    
    }
}

if (isset_post('data_link_curso')) {

    $data .= 'Todo el detalle en ' . $url_corta;
}

?>
<div class="text-center">
    <b>POST GENERADO CORRECTAMENTE</b>
    <br>
    <br>
    <b class="btn btn-info btn-lg" onclick="copyToClipboard('cont-post');">
        <i class="fa fa-copy"></i> COPIAR
    </b>
</div>
<hr>
<div id="cont-post"><?php echo $data; ?></div>



<script>
    function copyToClipboard(cont_id) {
        var container = document.createElement('div');
        container.style.position = 'fixed';
        container.style.pointerEvents = 'none';
        container.style.opacity = 0;
        container.innerHTML = document.getElementById(cont_id).innerHTML;
        document.body.appendChild(container);
        window.getSelection().removeAllRanges();
        var range = document.createRange();
        range.selectNode(container);
        window.getSelection().addRange(range);
        document.execCommand('copy');
        document.body.removeChild(container);
        alert('Copiado al portapapeles Ctrl + C');
    }
</script>


<?php

function solicitarShortLink($url) {
    $response = file_get_contents('https://a.tv.bo/agregar-link.php?key=325W6WSDG3GS65&url='.base64_encode($url));
    if(strpos($response,'ok_')>0){
        $ar1 = explode('ok_',$response);
        return $ar1[1];
    }else{
        return $url;
    }
}


function getBancosTigoMoney($id_curso) {
    /* [INFO-PAGO-CUENTAS-BANCARIAS] */
    $data_info_pago_cuentas_bancarias = '<br>CUENTA BANCARIAS <br>';
    $rqcdbe1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='$id_curso' AND r.sw_transbancunion=0 AND r.estado=1 ORDER BY c.id ASC ");
    while($rqcdbe2 = fetch($rqcdbe1)){
        $data_info_pago_cuentas_bancarias .= $rqcdbe2['nombre_banco'].' A nombre de : '.$rqcdbe2['titular'].'  cuenta '.$rqcdbe2['numero_cuenta'].'<br>';
    }
    $rqcdbdbu1 = query("SELECT c.*,(b.nombre)nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='$id_curso' AND r.sw_transbancunion=1 AND r.estado=1 ORDER BY c.id ASC ");
    if(num_rows($rqcdbdbu1)>0){
        $data_info_pago_cuentas_bancarias .= '𝗧𝗥𝗔𝗡𝗦𝗙𝗘𝗥𝗘𝗡𝗖𝗜𝗔 𝗗𝗘𝗦𝗧𝗘 𝗖𝗔𝗝𝗘𝗥𝗢 𝗕𝗔𝗡𝗖𝗢 𝗨𝗡𝗜𝗢𝗡: <br>';
        while($rqcdbdbu2 = fetch($rqcdbdbu1)){
            $data_info_pago_cuentas_bancarias .= 'Datos cuenta '.$rqcdbdbu2['nombre_banco'].' '.$rqcdbdbu2['numero_cuenta'].' '.$rqcdbdbu2['tipo_cuenta'].' '.strtoupper($rqcdbdbu2['titular']).'<br>';
        }
    }
    $rqcddt1 = query("SELECT c.* FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id WHERE r.id_curso='$id_curso' AND r.estado=1 GROUP BY c.datos_adicionales ORDER BY c.id ASC ");
    if(num_rows($rqcddt1)>0){
        $data_info_pago_cuentas_bancarias .= '𝗧𝗥𝗔𝗡𝗦𝗙𝗘𝗥𝗘𝗡𝗖𝗜𝗔 𝗕𝗔𝗡𝗖𝗔𝗥𝗜𝗢: <br>';
        while($rqcddt2 = fetch($rqcddt1)){
            $data_info_pago_cuentas_bancarias .= 'Cuenta '.$rqcddt2['tipo_cuenta'].' '.strtoupper($rqcddt2['titular']).' ('.$rqcddt2['datos_adicionales'].')<br>';
        }
    }
    $rqcntm1 = query("SELECT t.* FROM rel_cursonumtigomoney r INNER JOIN tigomoney_numeros t ON r.id_numtigomoney=t.id WHERE r.id_curso='$id_curso' AND r.estado=1 ORDER BY t.id ASC ");
    if(num_rows($rqcntm1)>0){
        $data_info_pago_cuentas_bancarias .= '𝗧𝗜𝗚𝗢 𝗠𝗢𝗡𝗘𝗬: ';
        while($rqcntm2 = fetch($rqcntm1)){
            $data_info_pago_cuentas_bancarias .= 'A la linea '.$rqcntm2['numero'].' el costo sin recargo (Titular '.$rqcntm2['titular'].')<br>';
        }
    }
    return $data_info_pago_cuentas_bancarias;
}
