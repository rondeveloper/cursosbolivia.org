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
$id_curso = post('id_curso');

/* registro */
$rqdc1 = query("SELECT * FROM blog WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rqdc1);

/* imagen */
$url_img_curso = $dominio . "blog/" . $curso['imagen'] . ".size=3.img";

/* datos lugar */
$rqdl1 = query("SELECT nombre,salon FROM cursos_lugares WHERE id='" . $curso['id_lugar'] . "' ");
$rqdl2 = fetch($rqdl1);
$lugar_nombre = $rqdl2['nombre'];
$lugar_salon = $rqdl2['salon'];

/* ciudad departemento */
$curso_id_ciudad = $curso['id_ciudad'];
$rqdcd1 = query("SELECT d.nombre AS departamento, c.nombre AS ciudad FROM departamentos d INNER JOIN ciudades c ON c.id_departamento=d.id WHERE c.id='$curso_id_ciudad' ");
$rqdcd2 = fetch($rqdcd1);
$curso_nombre_departamento = $rqdcd2['departamento'];
$curso_nombre_ciudad = $rqdcd2['ciudad'];
$curso_text_ciudad = $curso_nombre_ciudad;
if ($curso_nombre_departamento !== $curso_nombre_ciudad) {
    $curso_text_ciudad = $curso_nombre_ciudad . ' - ' . $curso_nombre_departamento;
}
if($curso_id_ciudad=='24'){
    $curso_text_ciudad = $curso_nombre_ciudad = 'BOLIVIA';
}
?>
<div class="row">
    <div class="col-md-4 text-center">
        <img src='<?php echo $url_img_curso; ?>' style='width:100%;'/>
    </div>
    <div class="col-md-8">
        <h4><?php echo $curso['titulo']; ?></h4>
        <?php echo my_date_curso($curso['fecha']); ?>
        <br/>
        <?php echo $lugar_nombre . " " . $lugar_salon; ?>
        <br/>
        <?php echo $curso_text_ciudad; ?>
    </div>
</div>

<hr/>

<h3 style="background: #1bbae1;
    color: white;
    text-align: center;
    padding: 5px;">DATOS DE NOTIFICACION</h3>

<hr/>
<form action="" method="post" id="form-segmento-notificacion">
    <input type='hidden' name='id_curso' value='<?php echo $id_curso; ?>'/>
</form>

<b class='btn btn-warning active'><?php echo $curso_nombre_ciudad; ?></b>
<hr/>

<h4>
    TOTAL HABILITADOS 
    <span class="pull-right">
        <b class="btn btn-default btn-xs" onclick="actualiza_segmento_de_notificacion();">
            <i class="fa fa-refresh"></i>
        </b>
    </span>
</h4>

<div id="AJAXCONTENT-actualiza_segmento_de_notificacion"></div>

<hr/>


<?php
function my_date_curso($dat) {
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $arf2 = date("N", strtotime($dat));
        $array_dias = array('none', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
        $dia_de_inicio = $array_dias[$arf2];
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        return $dia_de_inicio . ", " . $ar1[2] . " " . $arraymes[(int) $ar1[1]] . " " . $ar1[0];
    }
}

