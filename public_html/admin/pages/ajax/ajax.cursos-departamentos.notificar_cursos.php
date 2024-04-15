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
$id_departamento = post('id_departamento');

/* registro */
$rqdc1 = query("SELECT * FROM departamentos WHERE id='$id_departamento' ORDER BY id DESC limit 1 ");
$departamento = fetch($rqdc1);

/* imagen */
if ($id_departamento == '0' || $id_departamento == '10') {
    $url_img_curso = $dominio_www."contenido/imagenes/departamentos/bolivia.jpg";
    $nombre_departamento = 'Toda Bolivia';
    $qr_departamento = "";
} else {
    $url_img_curso = $dominio_www."contenido/imagenes/departamentos/" . $departamento['imagen'];
    $nombre_departamento = $departamento['nombre'];
    $qr_departamento = " AND id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ";
}

$qr_modalidad = " AND id_modalidad='1' ";
if($id_departamento=='10'){
    $qr_modalidad = " AND id_modalidad<>'1' ";
    $nombre_departamento .= ' (VIRTUALES)';
}

$rdc1 = query("SELECT count(*) AS total FROM cursos WHERE estado='1' $qr_modalidad $qr_departamento ");
$rdc2 = fetch($rdc1);
$cnt_cursosactivos = $rdc2['total'];
?>
<div class="row">
    <div class="col-md-4 text-center">
        <img src='<?php echo $url_img_curso; ?>' style='height:80px;'/>
    </div>
    <div class="col-md-8">
        <h4>DEPARTAMENTO: <?php echo strtoupper($nombre_departamento); ?></h4>
        <br/>
        <?php echo "CURSOS: $cnt_cursosactivos activos"; ?>
    </div>
</div>

<hr/>

<h3 style="background: #1bbae1;color: white;text-align: center;padding: 5px;">DATOS DE NOTIFICACION</h3>

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
<form action="" method="post" id="form-segmento-notificacion">
    <h4>CURSOS A NOTIFICAR:</h4>
    <table class="table table-bordered">
        <?php
        $rqdctob1 = query("SELECT id,titulo,fecha FROM cursos WHERE estado='1' $qr_modalidad $qr_departamento ");
        $cnt = 1;
        while ($rqdctob2 = fetch($rqdctob1)) {
            $id_tag = $rqdctob2['id'];
            ?>
            <tr>
                <td><?php echo $cnt++; ?></td>
                <td>
                    <?php echo $rqdctob2['titulo']; ?>
                    <br/>
                    <span style="color:#00789f;"><?php echo fecha_curso_D_d_m($rqdctob2['fecha']); ?></span>
                </td>
                <td>
                    <label style="
                           border: 1px solid #bce4fb;
                           padding: 5px;
                           background: #f7f7f7;
                           width: 100%;
                           cursor: pointer;
                           border-radius: 5px;
                           text-align: center;" for="tag<?php echo $id_tag; ?>">
                        <input type="checkbox" name="tag<?php echo $id_tag; ?>" id="tag<?php echo $id_tag; ?>" checked="checked" style="width:15px;height:15px;"/> &nbsp;
                    </label>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <input type='hidden' name='id_departamento' value='<?php echo $id_departamento; ?>'/>
</form>

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
