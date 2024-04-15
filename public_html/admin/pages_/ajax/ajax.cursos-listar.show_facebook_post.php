<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_curso = post('id_curso');

/* curso */
$rqdc1 = query("SELECT titulo,fecha,id_ciudad,fb_txt_requisitos,fb_txt_dirigido,fb_hashtags FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$nombre_curso = $rqdc2['titulo'];
$id_ciudad_curso = $rqdc2['id_ciudad'];
$fecha_curso = fecha_curso_D_d_m($rqdc2['fecha']);
$numero_wamsm_predeternimado = '69714008';
$rqdwn1 = query("SELECT w.numero FROM whatsapp_numeros w INNER JOIN cursos_rel_cursowapnum r ON r.id_whats_numero=w.id WHERE r.id_curso='" . $id_curso . "' ORDER BY r.id ASC LIMIT 1 ");
if (num_rows($rqdwn1) == 0) {
    $cel_wamsm = '591' . $numero_wamsm_predeternimado;
} else {
    $rqdwn2 = fetch($rqdwn1);
    $cel_wamsm = '591' . $rqdwn2['numero'];
}
$whats_numero_curso = $cel_wamsm;
$url_corta = $dominio. numIDcurso($id_curso) . '/';
$fb_txt_requisitos = $rqdc2['fb_txt_requisitos'];
$fb_txt_dirigido = $rqdc2['fb_txt_dirigido'];
$fb_hashtags = trim($rqdc2['fb_hashtags']);

$htar1 = explode(',',$fb_hashtags);
$txt_hashtag = '';
foreach ($htar1 as $ht){
    if($ht!==''){
        $txt_hashtag .= '#'.trim($ht).' ';
    }
}

/* ciudad */
$rqdcd1 = query("SELECT nombre FROM ciudades WHERE id='$id_ciudad_curso' LIMIT 1 ");
$rqdcd2 = fetch($rqdcd1);
$nombre_ciudad = str_replace(' ','',str_replace('CHUQUISACA','SUCRE',strtoupper($rqdcd2['nombre'])));

$data = ('#'.$nombre_ciudad.' '.trim($txt_hashtag).' &#10148; '.$fecha_curso.' &#10148; '.$nombre_curso.' &#10148; '.$fb_txt_requisitos.' &#10148; '.$fb_txt_dirigido.' &#10148; Consultas &oacute; WhatsApp '.$whats_numero_curso.' &#10148; Registro y descuentos en '.$url_corta);
?>
<table class="table table-bordered table-striped">
    <tr>
        <td>
            <h3><?php echo $nombre_curso; ?></h3>
        </td>
    <tr>
    <tr>
        <td>
            <textarea class="form-control" style="height: 350px;"><?php echo $data; ?></textarea>
        </td>
    <tr>
</table>