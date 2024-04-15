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
$_POST = json_decode(base64_decode($_GET['data']), true);

/* predata */
$qr_busqueda = "";
$busqueda = "";
$rq_departamento = '';
$qr_nombre = ' 1 ';
$qr_administrador = '';
$nro_semana = date("W");
$id_administrador = '99';
$id_departamento = '0';

$cod_reporte = 'S'.$nro_semana;

/* busqueda */
if (isset_post('realizar-busqueda')) {
    $busqueda = str_replace(' ', '%', post('input-buscador'));
    $id_departamento = post('id_departamento');
    $id_administrador = post('id_administrador');
    $nro_semana = post('nro_semana');
    $cod_reporte = 'S'.$nro_semana;

    if (strlen($busqueda) > 0) {
        $qr_nombre = " CONCAT(p.nombres,' ',p.apellidos) LIKE '%$busqueda%' ";
    }
    if ($id_administrador !== '99') {
        $qr_administrador = " AND r.id_administrador='$id_administrador' ";
        $cod_reporte = $cod_reporte.'-A'.$id_administrador;
    }
    if ($id_departamento !== '0') {
        $rq_departamento = " AND p.id_curso IN (select id from cursos where id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ) ";
        $cod_reporte = $cod_reporte.'-D'.$id_departamento;
    }
}

/* qr_semana */
$qr_semana = "";
if ($nro_semana != '0') {
    $f_inicio = date('Y-m-d', strtotime('01/01 +' . ($nro_semana - 1) . ' weeks first day -3 day')) . '<br />';
    $f_final = date('Y-m-d', strtotime('01/01 +' . ($nro_semana - 1) . ' weeks first day +3 day')) . '<br />';
    $qr_semana = " AND DATE(r.fecha_registro)>='$f_inicio' AND DATE(r.fecha_registro)<='$f_final' ";
}

$data_required = "*,(r.id_emision_factura)dr_id_emision_factura,(r.razon_social)dr_razon_social,(r.nit)dr_nit,(r.fecha_registro)dr_fecha_registro,(c.fecha)dr_fecha_curso,(c.titulo)dr_titulo_curso,(select nombre from ciudades where id=c.id_ciudad)dr_departamento_curso,(c.estado)dr_estado_curso,(p.estado)dr_estado_participante,(select nombre from administradores where id=r.id_administrador)dr_nombre_administrador,(c.numero)dr_numero_curso,(p.id)dr_id_participante,(c.id)dr_id_curso,(p.id_modo_pago)dr_modo_pago,(r.monto_deposito)dr_monto_pago";
$resultado1 = query("SELECT $data_required FROM cursos_participantes p INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id INNER JOIN cursos c ON r.id_curso=c.id WHERE DATE(r.fecha_registro)>c.fecha AND ( $qr_nombre ) $rq_departamento $qr_administrador $qr_semana ORDER BY r.fecha_registro ASC ");
$resultado_b1 = query("SELECT count(*) AS total FROM cursos_participantes p INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id INNER JOIN cursos c ON r.id_curso=c.id WHERE DATE(r.fecha_registro)>c.fecha AND ( $qr_nombre ) $rq_departamento $qr_administrador $qr_semana ");
$resultado_b2 = fetch($resultado_b1);
$cnt = $resultado_b2['total'];
?>
<style>
    table{
        width:100%;
    }
    td,th{
        border:1px solid #AAA;
        padding:7px 10px;
        font-family: arial;
        font-size: 8pt;
    }
</style>
<table>
    <tr>
        <td colspan="5" style="text-align:center;">
            <span style="font-size:11pt;">REGISTRADOS FUERA DE FECHA</span>
        </td>
        <td style="text-align:center;">
            <b style='font-size:25pt;'><?php echo $cod_reporte; ?></b>
            <br/>
            N&Uacute;MERO DE SEMANA <?php echo $nro_semana; ?>
        </td>
    </tr>
    <tr>
        <td>
            Desde:
        </td>
        <td colspan="2">
            <?php echo mydatefechacurso($f_inicio); ?>
        </td>
        <td>
            Hasta:
        </td>
        <td colspan="2">
            <?php echo mydatefechacurso($f_final); ?>
        </td>
    </tr>
    <tr>
        <td>
            Administrador:
        </td>
        <td colspan="2">
            <?php
            if($id_administrador=='99'){
                echo "TODOS";
            }elseif($id_administrador=='0'){
                echo "Sin administrador";
            }else{
                $qrdad1 = query("SELECT nombre FROM administradores WHERE id='$id_administrador' ");
                $qrdad2 = fetch($qrdad1);
                echo $qrdad2['nombre'];
            }
            ?>
        </td>
        <td>
            Departamento:
        </td>
        <td colspan="2">
            <?php
            if($id_departamento=='0'){
                echo "TODOS";
            }else{
                $qrdp1 = query("SELECT nombre FROM departamentos WHERE id='$id_departamento' ");
                $qrdp2 = fetch($qrdp1);
                echo $qrdp2['nombre'];
            }
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="6">&nbsp;</td>
    </tr>
</table>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha registro</th>
            <th>Participante</th>
            <th>Curso</th>
            <th>Factura</th>
            <th>Pago</th>
            <th>Registrado por</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(num_rows($resultado1)==0){
            echo "<tr><td colspan='8'>SIN PARTICIPANTES REGISTRADOS...</td></tr>";
        }
        $cnt = 1;
        while ($producto = fetch($resultado1)) {
            ?>
            <tr>
                <td>
                    <?php echo $cnt++; ?>
                </td>
                <td>
                    <?php echo date("d/M/Y H:i", strtotime($producto['dr_fecha_registro'])); ?>
                </td>
                <td>
                    <span style="font-size:9pt;">
                        <?php echo trim($producto['prefijo'] . ' ' . $producto['nombres'] . ' ' . $producto['apellidos']); ?>
                    </span>
                    <br/>
                    <?php
                    if ($producto['dr_estado_participante'] == '1') {
                        echo "<i>Habilitado</i>";
                    } else {
                        echo "<b>Des-habilitado</b>";
                    }
                    ?>
                </td>
                <td>
                    <span style="font-size:9pt;"><?php echo $producto['dr_titulo_curso']; ?></span>
                    <br/>
                    <b><?php echo $producto['dr_departamento_curso']; ?></b>
                    <br/>
                    <?php echo date("d / M / Y", strtotime($producto['dr_fecha_curso'])); ?> &nbsp;&nbsp;&nbsp; <b>[<?php echo $producto['dr_numero_curso']; ?>]</b>
                </td>
                <td class="simple-td">
                    <?php
                    if ($producto['dr_id_emision_factura'] != '0') {
                        $sw_existencia_facturas = true;
                        echo '<i class="btn btn-xs btn-success" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(' . $producto['dr_id_participante'] . ');">Emitida</i>';
                        echo '</br>';
                    } else {
                        if (strlen(trim($producto['dr_razon_social'] . $producto['dr_nit'])) <= 2) {
                            echo '<i class="btn btn-xs btn-warning" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(' . $producto['dr_id_participante'] . ');">No solicitada</i></br>';
                        } else {
                            echo '<i class="btn btn-xs btn-danger" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(' . $producto['dr_id_participante'] . ');">No emitida</i></br>';
                        }
                    }
                    echo $producto['dr_razon_social'];
                    echo "<br/>";
                    echo $producto['dr_nit'];
                    ?>
                </td>
                <td>
                    <?php
                    if ($producto['dr_monto_pago'] !== '' && $producto['dr_modo_pago'] != '0') {
                        echo $producto['dr_monto_pago'];
                        echo "<br/>";
                        echo "<span style='color:gray;font-size:8pt;'>" . $producto['dr_modo_pago'] . "</span>";
                    } else {
                        echo 'SIN PAGO';
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($producto['dr_nombre_administrador'] == '') {
                        echo "Sin administrador";
                    } else {
                        echo $producto['dr_nombre_administrador'];
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>

<script>window.print();</script>


<?php

function my_date_curso($dat) {
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]] . " " . substr($ar1[0], 2, 2);
    }
}

function selutf($dat) {
    if (isset($_GET['excel'])) {
        return ($dat);
    } else {
        return $dat;
    }
}

function fecha_corta($data) {
    return date("d / m", strtotime($data));
}

function mydatefechacurso($dat) {
    $day = date("w", strtotime($dat));
    $arf1 = explode("-", $dat);
    $array_meses = array('NONE', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
    $array_dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
    return $array_dias[(int) $day] . " " . $arf1[2] . " de " . ucfirst(strtolower($array_meses[(int) ($arf1[1])]));
}

function mydatefechacurso2($dat) {
    $arf1 = explode("-", $dat);
    $array_meses = array('NONE', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
    return $arf1[2] . " de " . ucfirst($array_meses[(int) ($arf1[1])]);
}

function fecha_curso($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    $anio = date("Y", strtotime($fecha));
    return "$nombredia, $dia de $nombremes de $anio";
}