<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {


    /* data import */
    $data = get('data');
    $_POST = json_decode(base64_decode($data), true);

    if (isset_get('excel')) {
        $filename = "Reporte-generado-en-".date("d-m-Y--H-i").".xls";
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
    } elseif (isset_get('word')) {
        $filename = "Reporte-generado-en-".date("d-m-Y--H-i").".doc";
        header("Content-Type: application/vnd.ms-word;");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
    } elseif (isset_get('pdf')) {
        echo "Exportado en PDF no disponible.";
    } elseif (isset_get('html')) {
        //muestra html
    }



    /* data post */
    $fecha_inicio = post('fecha_inicio');
    $fecha_fin = post('fecha_fin');
    $id_departamento = (int) post('id_departamento');
    $id_ciudad = (int) post('id_ciudad');
    $id_lugar = (int) post('id_lugar');
    $id_docente = (int) post('id_docente');
    $numero = (int) post('numero');
    $nombre = trim(post('nombre'));

    /* data post export */
    $postdata_exort = base64_encode(json_encode($_POST));

    /* vista */
    $vista = 1;
    $registros_a_mostrar = 500;
    $start = ($vista - 1) * $registros_a_mostrar;

    /* busqueda */
    $qr_busqueda = "";
    $busqueda = "";
    $rq_ciudad_departamento = "";
    $qr_nombre = ' 1 ';
    $rq_docente = "";
    $rq_numero = "";
    $rq_lugar = "";
    $rq_nombre = "";
    if ($numero !== 0) {
        $rq_numero = " AND c.numero='$numero'  ";
    } else {
        if ($id_departamento !== 0) {
            $rq_ciudad_departamento = " AND d.id='$id_departamento'  ";
        }
        if ($id_docente !== 0) {
            $rq_docente = " AND dc.id='$id_docente'  ";
        }
        if ($id_ciudad !== 0) {
            $rq_ciudad_departamento = " AND cd.id='$id_ciudad'  ";
        }
        if ($id_lugar !== 0) {
            $rq_lugar = " AND l.id='$id_lugar'  ";
        }
        if ($nombre !== '') {
            $rq_nombre = " AND c.titulo LIKE '%$nombre%'  ";
        }
    }


    /* data required */
    $data_required = "
*,
(c.id)dr_id_curso,
(cd.nombre)dr_nombre_ciudad,
(d.nombre)dr_nombre_departamento,
(dc.nombres)dr_nombre_docente,
(select count(*) from cursos_participantes where id_curso=c.id and estado=1)dr_cnt_participantes,
(select sum(inn_cpr.monto_deposito) from cursos_proceso_registro inn_cpr inner join cursos_participantes inn_cp on inn_cpr.id=inn_cp.id_proceso_registro where inn_cp.id_curso=c.id and inn_cp.estado=1)dr_total_pagos,
(select sum(inn_cpr.monto_deposito) from cursos_proceso_registro inn_cpr inner join cursos_participantes inn_cp on inn_cpr.id=inn_cp.id_proceso_registro where inn_cp.id_curso=c.id and inn_cp.estado=1 and id_modo_pago IN (4,3))dr_total_pago_banco,
(select sum(inn_cpr.monto_deposito) from cursos_proceso_registro inn_cpr inner join cursos_participantes inn_cp on inn_cpr.id=inn_cp.id_proceso_registro where inn_cp.id_curso=c.id and inn_cp.estado=1 and id_modo_pago IN (1))dr_total_pago_efectivo,
(select sum(inn_cpr.monto_deposito) from cursos_proceso_registro inn_cpr inner join cursos_participantes inn_cp on inn_cpr.id=inn_cp.id_proceso_registro where inn_cp.id_curso=c.id and inn_cp.estado=1 and id_modo_pago IN (5))dr_total_pago_tigomoney,
(select sum(inn_cpr.monto_deposito) from cursos_proceso_registro inn_cpr inner join cursos_participantes inn_cp on inn_cpr.id=inn_cp.id_proceso_registro where inn_cp.id_curso=c.id and inn_cp.estado=1 and id_modo_pago NOT IN (4,3,1,5))dr_total_pago_otros,
(l.nombre)dr_nombre_lugar,
(l.salon)dr_nombre_salon
";
    /* query */
    $resultado1 = query("
SELECT $data_required FROM 
cursos c 
INNER JOIN ciudades cd ON c.id_ciudad=cd.id 
INNER JOIN departamentos d ON cd.id_departamento=d.id 
LEFT JOIN cursos_docentes dc ON c.id_docente=dc.id  
LEFT JOIN cursos_lugares l ON c.id_lugar=l.id 
WHERE c.numero<>'0' AND c.estado IN (0,1,2) 
$rq_numero $rq_ciudad_departamento $rq_docente $rq_nombre $rq_lugar 
AND c.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' 
ORDER BY c.numero DESC LIMIT $start,$registros_a_mostrar
");
    $total_registros = num_rows($resultado1);

    $cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
    ?>
    <style>
        td,th{
            border:1px solid #AAA;
            padding:10px 15px;
            font-family: arial;
            font-size: 9pt;
        }
    </style>
    <table style="width:100%;">
        <tr>
            <td colspan="7" style="text-align:center;">
                &nbsp;
                <br/>
                <b>REPORTE PRINCIPAL</b>
                <br/>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Nombre especifico:
            </td>
            <td colspan="2">
                <?php 
                if($nombre!==''){
                    echo $nombre;
                }else{
                    echo '...'; 
                }
                ?>
            </td>
            <td>
                N&uacute;mero:
            </td>
            <td colspan="2">
                <?php 
                if($numero!==0){
                    echo $numero;
                }else{
                    echo '...'; 
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Departamento:
            </td>
            <td colspan="2">
                <?php 
                if($id_departamento!==0){
                    $rqdd1 = query("SELECT nombre FROM departamentos WHERE id='$id_departamento' ");
                    $rqdd2 = fetch($rqdd1);
                    echo $rqdd2['nombre'];
                }else{
                    echo 'Todos los departamentos'; 
                }
                ?>
            </td>
            <td>
                Ciudad:
            </td>
            <td colspan="2">
                <?php 
                if($id_ciudad!==0){
                    $rqdd1 = query("SELECT nombre FROM ciudades WHERE id='$id_ciudad' ");
                    $rqdd2 = fetch($rqdd1);
                    echo $rqdd2['nombre'];
                }else{
                    echo 'Todas las ciudades'; 
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Lugar:
            </td>
            <td colspan="2">
                <?php 
                if($id_lugar!==0){
                    $rqdd1 = query("SELECT lugar,salon FROM cursos_lugares WHERE id='$id_lugar' ");
                    $rqdd2 = fetch($rqdd1);
                    echo $rqdd2['lugar'].' | '.$rqdd2['salon'];
                }else{
                    echo 'Todos los lugares'; 
                }
                ?>
            </td>
            <td>
                Docente:
            </td>
            <td colspan="2">
                <?php 
                if($id_docente!==0){
                    $rqdd1 = query("SELECT nombre,prefijo FROM cursos_docentes WHERE id='$id_docente' ");
                    $rqdd2 = fetch($rqdd1);
                    echo $rqdd2['prefijo'].' '.$rqdd2['nombre'];
                }else{
                    echo 'Todos los docentes'; 
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                Desde:
            </td>
            <td colspan="2">
                <?php echo date("d/m/Y",strtotime($fecha_inicio)); ?>
            </td>
            <td>
                Hasta:
            </td>
            <td colspan="2">
                <?php echo date("d/m/Y",strtotime($fecha_fin)); ?>
            </td>
        </tr>
        <tr>
            <td colspan="7">&nbsp;</td>
        </tr>
    </table>
    <?php
    if (num_rows($resultado1) == 0) {
        echo "<table><tr><td colspan='4'>No se encontraron registros.</td></tr></table>";
    }
    ?>
    <meta http-equiv="content-type" content="text/html; utf-8"/>

    <table style="width:100%;">
        <thead>
            <tr>
                <th style="font-size:10pt;">#</th>
                <th style="font-size:10pt;">Fecha</th>
                <th style="font-size:10pt;">Curso</th>
                <th style="font-size:10pt;">N&uacute;mero</th>
                <th style="font-size:10pt;">Departamento</th>
                <th style="font-size:10pt;">Ciudad</th>
                <th style="font-size:10pt;">Lugar</th>
                <th style="font-size:10pt;">Sal&oacute;n</th>
                <th style="font-size:10pt;">Docente</th>
                <th style="font-size:10pt;">Apoyo</th>
                <th style="font-size:10pt;">Participantes</th>
                <th style="font-size:10pt;">Recaudaci&oacute;n</th>
                <th style="font-size:10pt;">Banco</th>
                <th style="font-size:10pt;">Efectivo</th>
                <th style="font-size:10pt;">Tigomoney</th>
                <th style="font-size:10pt;">Otros</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total__cnt_participantes = 0;
            $total__total_pagos = 0;
            $total__total_pago_banco = 0;
            $total__total_pago_efectivo = 0;
            $total__total_pago_tigomoney = 0;
            $total__total_pago_otros = 0;
            while ($producto = fetch($resultado1)) {
                /* apotyo admin */
                $rqapa1 = query("select nombre from administradores where id=(select id_administrador from (select id_administrador,count(*) as cnt_reg from cursos_proceso_registro where id_curso='" . $producto['dr_id_curso'] . "' group by id_administrador)t_derived order by cnt_reg desc limit 1)");
                $rqapa2 = fetch($rqapa1);
                $apoyo_admin = $rqapa2['nombre'];
                ?>
                <tr>
                    <td>
                        <?php echo $cnt--; ?>
                    </td>
                    <td>
                        <?php echo date("d/M/Y", strtotime($producto['fecha'])); ?>
                    </td>
                    <td>
                        <?php echo $producto['titulo']; ?>
                    </td>
                    <td>
                        <?php echo $producto['numero']; ?>
                    </td>
                    <td>
                        <?php echo $producto['dr_nombre_departamento']; ?>
                    </td>
                    <td>
                        <?php echo $producto['dr_nombre_ciudad']; ?>
                    </td>
                    <td>
                        <?php echo $producto['dr_nombre_lugar']; ?>
                    </td>
                    <td>
                        <?php echo $producto['dr_nombre_salon']; ?>
                    </td>
                    <td>
                        <?php echo $producto['dr_nombre_docente']; ?>
                    </td>
                    <td>
                        <?php echo $apoyo_admin; ?>
                    </td>
                    <td>
                        <?php echo format_monto($producto['dr_cnt_participantes']); ?>
                    </td>
                    <td>
                        <?php echo format_monto($producto['dr_total_pagos']); ?>
                    </td>
                    <td>
                        <?php echo format_monto($producto['dr_total_pago_banco']); ?>
                    </td>
                    <td>
                        <?php echo format_monto($producto['dr_total_pago_efectivo']); ?>
                    </td>
                    <td>
                        <?php echo format_monto($producto['dr_total_pago_tigomoney']); ?>
                    </td>
                    <td>
                        <?php echo format_monto($producto['dr_total_pago_otros']); ?>
                    </td>
                </tr>
                <?php
                $total__cnt_participantes += (int) $producto['dr_cnt_participantes'];
                $total__total_pagos += (int) $producto['dr_total_pagos'];
                $total__total_pago_banco += (int) $producto['dr_total_pago_banco'];
                $total__total_pago_efectivo += (int) $producto['dr_total_pago_efectivo'];
                $total__total_pago_tigomoney += (int) $producto['dr_total_pago_tigomoney'];
                $total__total_pago_otros += (int) $producto['dr_total_pago_otros'];
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    -
                </td>
                <td colspan="9">
                    Total cursos: <?php echo $total_registros; ?>
                </td>
                <td>
                    <?php echo format_monto($total__cnt_participantes); ?>
                </td>
                <td>
                    <?php echo format_monto($total__total_pagos); ?>
                </td>
                <td>
                    <?php echo format_monto($total__total_pago_banco); ?>
                </td>
                <td>
                    <?php echo format_monto($total__total_pago_efectivo); ?>
                </td>
                <td>
                    <?php echo format_monto($total__total_pago_tigomoney); ?>
                </td>
                <td>
                    <?php echo format_monto($total__total_pago_otros); ?>
                </td>
            </tr>
        </tfoot>
    </table>


    <?php
    if (isset_get('impresion')) {
        echo "<script>window.print();window.close();</script>";
    }
    ?>

    <?php
} else {
    echo "Denegado!";
}


/* funciones */
function format_monto($dat) {
    $monto = (int) $dat;
    if ($monto == 0) {
        return "<span style='color:#DDD;'>$monto</span>";
    } else {
        return $monto;
    }
}
