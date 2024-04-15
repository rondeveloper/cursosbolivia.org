<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {

    $fecha_inicio = get('fecha_inicio');
    $fecha_final = get('fecha_final');


    $sql = "SELECT * FROM reportes_de_pago WHERE fecha_pago>='$fecha_inicio' AND fecha_pago<='$fecha_final' ORDER BY id DESC ";

    $resultado_reportes = query($sql);

    $total_registros = mysql_num_rows($resultado_reportes);
    
    $fecha_inicio_aux = $fecha_inicio;
    
    if($fecha_inicio_aux=='0000-00-00'){
        $fecha_inicio_aux = 'inicio';
    }
    
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=reportes-de-pago-$fecha_inicio_aux-hasta-$fecha_final.xls");
header("Pragma: no-cache");
header("Expires: 0");
    
    ?>
<style>
    td,th{
        border:1px solid gray;
        font-family: arial;
    }
</style>
    <table>
        <thead>
            <tr>
                <th >#</th>
                <th >NOMBRE</th>
                <th >MONTO</th>
                <th >TIPO DE BANCO</th>
                <th >FECHA DE<br/>DEPOSITO</th>
                <th >RESERVA</th>
                <th >IMAGEN</th>
                <th >Empresa</th>
                <th >Codigo</th>
                <th >medio_pago</th>
                <th >moneda_pago</th>
                <th >monto</th>
                <th >razon</th>
                <th >detalles</th>
                <th >correo</th>
                <th >tel_cel</th>
                <th >fecha registro</th>
                <th >facturacion_razon_social</th>
                <th >facturacion_nit</th>
                <th >facturacion_direccion</th>
            </tr>
        </thead>                    
        <tbody>
            <?php
            $numerador = $total_registros - (($vista - 1) * $registros_a_mostrar);
            while ($reporte = mysql_fetch_array($resultado_reportes)) {

                $r1 = mysql_query("SELECT * FROM empresas WHERE id=" . $reporte['id_empresa']) or die(mysql_error());
                $empresa = mysql_fetch_array($r1);
                if ($empresa['clase'] == 'empresa') {
                    $nombre_emp = strtoupper($empresa['nombre_empresa']);
                } else {
                    $nombre_emp = trim($empresa['ap_paterno_representante'] . ' ' . $empresa['ap_materno_representante'] . ' ' . $reporte['nombre_representante']);
                }
                if(strlen($nombre_emp)==0){$nombre_emp = 'Sin registro de empresa';}
                $box_html = '';
                $cntt = 0;
                ?>
                <tr>
                    <td >
                        <?php echo $numerador--; ?>
                    </td>
                    <td  style='width:150px;'>
                        <?php echo $reporte['nombre']; ?>
                    </td>
                    <td >
                        <?php echo $reporte['monto_pago']; ?>
                    </td>
                    <td >
                        <?php echo $reporte['medio_pago']; ?>
                    </td>
                    <td >
                        <?php echo $reporte['fecha_pago']; ?>
                    </td>
                    <td >
                        <?php
                        $arr_nom = explode(" ", $reporte['nombre']);
                        $nomb = $arr_nom[0];
                        $ap_pat = $arr_nom[1];
                        $rd1 = query("SELECT id FROM reservas_online WHERE correo LIKE '%" . $reporte['correo'] . "%' OR (nombre LIKE '%" . $nomb . "%' AND ap_paterno LIKE '%" . $ap_pat . "%' ) ");
                        if(mysql_num_rows($rd1)==0){
                            echo "Sin reserva";
                        }
                        while ($rd2 = mysql_fetch_array($rd1)) {
                            echo "<a href='".$dominio."reservas-online-detalle/" . $rd2['id'] . ".adm'>Ver reserva</a>";
                            echo "<br/>";
                        }
                        ?>
                    </td>
                    <td >
                        <?php
                        if ($reporte['imagen'] !== 'sin-archivo.jpg') {
                            ?>
                            <a href="<?php echo $dominio; ?>contenido/imagenes/empresas/pagos/<?php echo $reporte['imagen']; ?>" target="_blank">Archivo</a>
                            <?php
                        } else {
                            echo "Sin archivo!";
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $nombre_emp; ?>
                    </td>
                    <td>
                        <?php 
                        if($reporte['codigo'] =='-00-'){
                            echo "Sin codigo";
                        }else{
                            echo $reporte['codigo']; 
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $reporte['medio_pago']; ?>
                    </td>
                    <td>
                        <?php echo $reporte['moneda_pago']; ?>
                    </td>
                    <td>
                        <?php echo $reporte['monto_pago']; ?>
                    </td>
                    <td>
                        <?php echo $reporte['razon']; ?>
                    </td>
                    <td>
                        <?php echo $reporte['detalles']; ?>
                    </td>
                    <td>
                        <?php echo $reporte['correo']; ?>
                    </td>
                    <td>
                        <?php echo $reporte['tel_cel']; ?>
                    </td>
                    <td>
                        <?php echo $reporte['fecha']; ?>
                    </td>
                    <td>
                        <?php echo $reporte['facturacion_razon_social']; ?>
                    </td>
                    <td>
                        <?php echo $reporte['facturacion_nit']; ?>
                    </td>
                    <td>
                        <?php echo $reporte['facturacion_direccion']; ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <?php
} else {
    echo "Acceso denegado";
}
?>