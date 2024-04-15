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
    $data .= '<br>CUENTA BANCARIAS <br>
    𝗕𝗮𝗻𝗰𝗼 𝗨𝗡𝗜𝗢𝗡 A nombre de : NEMABOL cuenta 124033833 (CUENTA JURIDICA) <br>
    𝗕𝗔𝗡𝗖𝗢 𝗗𝗘 𝗖𝗥𝗘𝗗𝗜𝗧𝗢 𝗕𝗖𝗣 A nombre de : Evangelina Sardon Tintaya cuenta 201-50853966-3-23 <br>
    𝗕𝗔𝗡𝗖𝗢 𝗦𝗢𝗟 A nombre de : Evangelina Sardon Tintaya cuenta 1166531-000-001 <br>
    𝗕𝗔𝗡𝗖𝗢 𝗡𝗔𝗖𝗜𝗢𝗡𝗔𝗟 𝗕𝗡𝗕 A nombre de : Evangelina Sardon Tintaya cuenta 1501512288 <br>
    𝗕𝗔𝗡𝗖𝗢 𝗠𝗘𝗥𝗖𝗔𝗡𝗧𝗜𝗟 𝗦𝗔𝗡𝗧𝗔 𝗖𝗥𝗨𝗭 A nombre de : Evangelina Sardon Tintaya cuenta 4066860455 <br>
    𝗕𝗔𝗡𝗖𝗢 𝗙𝗜𝗘 A nombre de : Evangelina Sardon Tintaya cuenta 40004725631 <br>
    <br>
    𝗧𝗥𝗔𝗡𝗦𝗙𝗘𝗥𝗘𝗡𝗖𝗜𝗔 𝗕𝗔𝗡𝗖𝗔𝗥𝗜𝗢: <br>
    𝗗𝗮𝘁𝗼𝘀 𝗰𝘂𝗲𝗻𝘁𝗮 𝗝𝗨𝗥𝗜𝗗𝗜𝗖𝗔 NEMABOL(Caja de Ahorro, CI 2044323 LP, NIT 2044323014 CIUDAD LA PAZ) <br>
    𝗗𝗮𝘁𝗼𝘀 𝗰𝘂𝗲𝗻𝘁𝗮 𝗣𝗘𝗥𝗦𝗢𝗡𝗔 𝗡𝗔𝗧𝗨𝗥𝗔𝗟 EVANGELINA SARDON TINTAYA (Caja de Ahorro, CI 6845644 LP CIUDAD LA PAZ <br>
    <br>
    𝗧𝗥𝗔𝗡𝗦𝗙𝗘𝗥𝗘𝗡𝗖𝗜𝗔 𝗗𝗘𝗦𝗧𝗘 𝗖𝗔𝗝𝗘𝗥𝗢 𝗕𝗔𝗡𝗖𝗢 𝗨𝗡𝗜𝗢𝗡: <br>
    Datos cuenta BANCO UNION 114318998 PERSONA NATURAL EVANGELINA SARDON TINTAYA <br>
    <br>
    𝗧𝗜𝗚𝗢 𝗠𝗢𝗡𝗘𝗬: A la linea 69714008 el costo sin recargo (Titular Edgar Aliaga) <br>';
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
    $data .= '<br>𝗪𝗵𝗮𝘁𝘀𝗮𝗽𝗽📲: https://wa.me/' . $cel_wamsm . '?text=' . str_replace('+', '%20', urlencode($mensaje_wamsm_predeternimado)) . ' <br>';
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