<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {

    /* data */
    $mes = post('mes');
    $anio = post('anio');
    $id_dosificacion = post('id_dosificacion');

    /* verif */
    if ((int) $mes <= 0 || (int) $mes > 12) {
        echo "Error en los datos.";
        exit;
    }
    if ((int) $anio <= 2015 || (int) $anio > 2050) {
        echo "Error en los datos.";
        exit;
    }
    /* END verif */

    //$estado_fact = '2';
    $fecha_emision_fact = $anio . '-' . $mes . '-%';
    
    $array_meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    echo "<p>Reporte correspondiente al mes de ".$array_meses[(int)($mes)-1]." del a&ntilde;o $anio</p>";

    $key = md5(rand(1,999));

    /* facturas validas */
    echo "<div class='row'>";
    echo "<div class='col-md-6'>";
    echo "<b>FACTURAS VALIDAS</b><br/>";
    $estado_fact = '1';
    $resultado1 = query("SELECT *,(select nombre from administradores where id=f.id_administrador limit 1)administrador FROM facturas_emisiones f WHERE estado='$estado_fact' AND id_dosificacion='$id_dosificacion' AND fecha_emision LIKE '$fecha_emision_fact' ORDER BY id DESC");
    $cnt = num_rows($resultado1);
    echo "Se encontraron $cnt facturas validas";
    echo "</div>";
    echo "<div class='col-md-6'>";
    if ($cnt > 0) {
        $_SESSION['key_df'] = $key;
        $url_descarga = "pages/ajax/ajax.z.descarga.reporte_facturas.php?mes=$mes&anio=$anio&id_dosificacion=$id_dosificacion&estado=valido&key_df=$key";
        echo "<a href='$url_descarga' class='btn btn-success'>DESCARGAR REPORTE</a>";
    }
    echo "</div>";
    echo "</div>";
    echo "<hr/>";

    /* facturas anuladas */
    echo "<div class='row'>";
    echo "<div class='col-md-6'>";
    echo "<b>FACTURAS ANULADAS</b><br/>";
    $estado_fact = '2';
    $resultado1 = query("SELECT *,(select nombre from administradores where id=f.id_administrador limit 1)administrador FROM facturas_emisiones f WHERE estado='$estado_fact' AND id_dosificacion='$id_dosificacion' AND fecha_emision LIKE '$fecha_emision_fact' ORDER BY id DESC");
    $cnt = num_rows($resultado1);
    echo "Se encontraron $cnt facturas anuladas";
    echo "</div>";
    echo "<div class='col-md-6'>";
    if ($cnt > 0) {
        $_SESSION['key_df'] = $key;
        $url_descarga = "pages/ajax/ajax.z.descarga.reporte_facturas.php?mes=$mes&anio=$anio&id_dosificacion=$id_dosificacion&estado=anulado&key_df=$key";
        echo "<a href='$url_descarga' class='btn btn-success'>DESCARGAR REPORTE</a>";
    }
    echo "</div>";
    echo "</div>";
    echo "<hr/>";
} else {
    echo "Denegado!";
}
?>
