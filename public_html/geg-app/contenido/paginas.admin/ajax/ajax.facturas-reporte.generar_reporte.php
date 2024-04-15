<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {

    /* data */
    $mes = post('mes');
    $anio = post('anio');
    $id_actividad = post('id_actividad');

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
    $resultado1 = query("SELECT *,(select nombre from administradores where id=f.id_administrador limit 1)administrador FROM facturas_emisiones f WHERE estado='$estado_fact' AND id_actividad='$id_actividad' AND fecha_emision LIKE '$fecha_emision_fact' ORDER BY id DESC");
    $cnt = mysql_num_rows($resultado1);
    echo "Se encontraron $cnt facturas validas";
    echo "</div>";
    echo "<div class='col-md-6'>";
    if ($cnt > 0) {
        $_SESSION['key_df'] = $key;
        $url_descarga = "contenido/paginas.admin/ajax/ajax.z.descarga.reporte_facturas.php?mes=$mes&anio=$anio&id_actividad=$id_actividad&estado=valido&key_df=$key";
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
    $resultado1 = query("SELECT *,(select nombre from administradores where id=f.id_administrador limit 1)administrador FROM facturas_emisiones f WHERE estado='$estado_fact' AND id_actividad='$id_actividad' AND fecha_emision LIKE '$fecha_emision_fact' ORDER BY id DESC");
    $cnt = mysql_num_rows($resultado1);
    echo "Se encontraron $cnt facturas anuladas";
    echo "</div>";
    echo "<div class='col-md-6'>";
    if ($cnt > 0) {
        $_SESSION['key_df'] = $key;
        $url_descarga = "contenido/paginas.admin/ajax/ajax.z.descarga.reporte_facturas.php?mes=$mes&anio=$anio&id_actividad=$id_actividad&estado=anulado&key_df=$key";
        echo "<a href='$url_descarga' class='btn btn-success'>DESCARGAR REPORTE</a>";
    }
    echo "</div>";
    echo "</div>";
    echo "<hr/>";
} else {
    echo "Denegado!";
}
?>
