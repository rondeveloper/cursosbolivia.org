<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {

    /* data */
    $mes = get('mes');
    $anio = get('anio');
    $id_actividad = get('id_actividad');
    $estado = get('estado');

    $array_meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $txt_mes = $array_meses[(int) ($mes) - 1];

    if ($estado == 'valido') {
        $estado_fact = '1';
        $nombre_archivo = 'Facturas-VALIDAS';
    } else {
        $estado_fact = '2';
        $nombre_archivo = 'Facturas-ANULADAS';
    }
    
    /* actividad */
    $rqact1 = query("SELECT actividad FROM facturas_actividades WHERE id='$id_actividad' LIMIT 1 ");
    $rqact2 = mysql_fetch_array($rqact1);
    $txt_actividad = limpiar_enlace(strtolower($rqact2['actividad']));

    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=$nombre_archivo-$txt_mes-$anio--$txt_actividad--NEMABOL.xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);

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


    $fecha_emision_fact = $anio.'-'.$mes.'-%';

    $resultado1 = query("SELECT *,(select nombre from administradores where id=f.id_administrador limit 1)administrador FROM facturas_emisiones f WHERE estado='$estado_fact' AND id_actividad='$id_actividad' AND fecha_emision LIKE '$fecha_emision_fact' ORDER BY id DESC ");

    $total_registros = mysql_num_rows($resultado1);
    $cnt = $total_registros;
    ?>
    <table>
        <tr>
            <th style="font-size:10pt;">Fecha</th>
            <th style="font-size:10pt;">Nro Factura</th>
            <th style="font-size:10pt;">Nro autorizaci&oacute;n</th>
            <th style="font-size:10pt;">Nombre Cliente</th>
            <th style="font-size:10pt;">Nit Cliente</th>
            <th style="font-size:10pt;">Monto</th>
            <th style="font-size:10pt;">Codigo de Control</th>
            <th style="font-size:10pt;">Fecha limite de emisi&oacute;n</th>
            <th style="font-size:10pt;">Nro Tramite</th>
        </tr>
        <?php
        while ($producto = mysql_fetch_array($resultado1)) {
            ?>
            <tr>
                <td>
                    <?php
                    echo date("d/m/Y", strtotime($producto['fecha_emision']));
                    ?> 
                </td>
                <td>
                    <?php
                    echo str_pad($producto['nro_factura'], 5, "0", STR_PAD_LEFT);
                    ?>         
                </td>
                <td>
                    <?php
                    echo utf8_encode($producto['nro_autorizacion']) . '&nbsp;';
                    ?> 
                </td>
                <td>
                    <?php
                    echo utf8_decode($producto['nombre_receptor']);
                    ?> 
                </td>
                <td>
                    <?php
                    echo utf8_encode($producto['nit_receptor']) . '&nbsp;';
                    ?> 
                </td>
                <td>
                    <?php
                    echo $producto['total'];
                    ?> 
                </td>
                <td>
                    <?php
                    echo $producto['codigo_de_control'];
                    ?> 
                </td>
                <td>
                    <?php
                    echo date("d/m/Y", strtotime($producto['fecha_limite_emision']));
                    ?> 
                </td>
                <td>
                    <?php
                    $rqnt1 = query("SELECT nro_tramite FROM facturas_dosificaciones WHERE nro_autorizacion='" . $producto['nro_autorizacion'] . "' limit 1");
                    $rqnt2 = mysql_fetch_array($rqnt1);
                    echo $rqnt2['nro_tramite'];
                    ?> 
                </td>
            </tr>
            <?php
        }
        ?>
    </table>

    <?php
} else {
    echo "Acceso denegado!";
}

function my_date_curso($dat) {
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        //return $ar1[2] . " " . $arraymes[(int)$ar1[1]] . " " . substr($ar1[0],2,2);
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]];
    }
}

