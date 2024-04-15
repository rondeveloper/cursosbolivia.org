<?php
$mensaje = '';

/* crear registro */
if (isset_post('emitir-factura')) {

    $id_actividad = post('id_actividad');
    $concepto = post('concepto');
    $total = post('total');
    $nombre_receptor = post('nombre_receptor');
    $nit_receptor = post('nit_receptor');

    $nombre_a_facturar = $nombre_receptor;
    $nit_a_facturar = $nit_receptor;
    $monto_a_facturar = $total;

    $id_administrador = administrador('id');

    /* datos para emision de factura */
    $rqdf1 = query("SELECT * FROM facturas_dosificaciones WHERE estado='1' AND id_actividad='$id_actividad' ORDER BY id DESC limit 1 ");
    $rqdf2 = mysql_fetch_array($rqdf1);

    $nro_autorizacion = $rqdf2['nro_autorizacion'];
    $nit_emisor = $rqdf2['nit_emisor'];
    $fecha_limite_emision = $rqdf2['fecha_limite_emision'];
    $llave_dosificacion = $rqdf2['llave_dosificacion'];

    $fecha_emision = date("Y-m-d");
    $fecha_registro = date("Y-m-d H:i");

    /* numero de factura */
    $rqfea1 = query("SELECT nro_factura FROM facturas_emisiones WHERE id_actividad='$id_actividad' ORDER BY nro_factura DESC limit 1 ");
    $rqfea2 = mysql_fetch_array($rqfea1);
    $nro_factura =  (int)$rqfea2['nro_factura'] + 1;

    /* generacion de codigo de control */
    $codigo_de_control = CodigoControlV7::generar($nro_autorizacion, $nro_factura, $nit_a_facturar, str_replace('-', '', $fecha_emision), $monto_a_facturar, $llave_dosificacion);
    
    /* estado */
    $estado = '1';
    
    $archivo_visualizador = 'factura-1';
    
    /* prefactura */
    $sw_prefactura = post('sw_prefactura');
    if($sw_prefactura == '1'){
        $estado = '3';
        $archivo_visualizador = 'factura-2';
    }

    query("INSERT INTO facturas_emisiones(
           id_administrador, 
           id_actividad, 
           nro_factura, 
           nro_autorizacion, 
           nit_emisor, 
           fecha_limite_emision, 
           codigo_de_control, 
           nombre_receptor, 
           nit_receptor, 
           total, 
           concepto, 
           fecha_emision, 
           ciudad_emision, 
           fecha_registro, 
           estado
           ) VALUES (
           '$id_administrador',
           '$id_actividad',
           '$nro_factura',
           '$nro_autorizacion',
           '$nit_emisor',
           '$fecha_limite_emision',
           '$codigo_de_control',
           '$nombre_a_facturar',
           '$nit_a_facturar',
           '$monto_a_facturar',
           '$concepto',
           '$fecha_emision',
           'LA PAZ',
           '$fecha_registro',
           '$estado'
           )");

    /* id de emision de factura */
    $id_emision_factura = mysql_insert_id();


    $mensaje .= '<div class="alert alert-success">
        <strong>Exito!</strong> Factura emitida exitosamente.
    </div>

    <table class="table table-striped">
        <tr>
            <td>Nro. de Factura: </td>
            <td>' . $nro_factura . '</td>
        </tr>
        <tr>
            <td>Factura a nombre de: </td>
            <td>' . $nombre_a_facturar . '</td>
        </tr>
        <tr>
            <td>NIT: </td>
            <td>' . $nit_a_facturar . '</td>
        </tr>
        <tr>
            <td>Monto facturado: </td>
            <td>' . $monto_a_facturar . '</td>
        </tr>
        <tr>
            <td>Fecha de emision: </td>
            <td>' . $fecha_emision . '</td>
        </tr>
        <tr>
            <td>Codigo de control: </td>
            <td>' . $codigo_de_control . '</td>
        </tr>
        <tr>
            <td>Nro. de autorizaci&oacute;n: </td>
            <td>' . $nro_autorizacion . '</td>
        </tr>
        <tr>
            <td colspan="2" class="text-center">
                <br/>
                <br/>
                <button class="btn btn-warning" onclick="window.open(\'https://cursos.bo/contenido/librerias/fpdf/tutorial/'.$archivo_visualizador.'.php?id_factura=' . $id_emision_factura . '\', \'popup\', \'width=700,height=500\');" class="btn btn-default btn-xs">IMPRIMIR FACTURA</button>
            </td>
        </tr>
    </table>';
}

?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
<?php
include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
?>
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li class="active">Emisi&oacute;n de factura computarizada</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <h3 class="page-header"> FACTURA COMPUTARIZADA</h3>
        <blockquote class="page-information hidden">
            <p>
                Emisi&oacute;n de factura computarizada
            </p>
        </blockquote>
    </div>
</div>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="panel">

            <div class="panel-body">

                <div class="panel panel-primary">
                    <div class="panel-heading">DATOS DE LA FACTURA</div>
                    <div class="panel-body">

                        <form action="" method="post">
                            <table style="width:100%;" class="table table-striped">
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; ACTIVIDAD: </span>
                                    </td>
                                    <td>
                                        <select name="id_actividad" class="form-control">
                                            <?php
                                            $rqac1 = query("SELECT * FROM facturas_actividades WHERE estado='1' ");
                                            while($rqac2 = mysql_fetch_array($rqac1)){
                                            ?>
                                                <option value="<?php echo $rqac2['id']; ?>"><?php echo $rqac2['actividad']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; CONCEPTO: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="concepto" value="" class="form-control" id="date" required="" autocomplete="off">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; TOTAL (BS): </span>
                                    </td>
                                    <td>
                                        <input type="number" name="total" value="" class="form-control" id="date" required="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; A NOMBRE DE: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="nombre_receptor" value='<?php echo $curso['cont_uno']; ?>' class="form-control" id="date" required="" autocomplete="off">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; N&Uacute;MERO NIT: </span>
                                    </td>
                                    <td>
                                        <input type="number" name="nit_receptor" value='<?php echo $curso['cont_dos']; ?>' class="form-control" id="date" required="" autocomplete="off">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; MODO EMISI&Oacute;N: </span>
                                    </td>
                                    <td class='text-center'>
                                        <label for='mpf1'>
                                            <input type='radio' id='mpf1' name='sw_prefactura' value='0' checked=""/> Factura comun
                                        </label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <label for='mpf2'>
                                            <input type='radio' id='mpf2' name='sw_prefactura' value='1' disabled=""/> Emitir como Pre-facura
                                        </label>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2">
                                        <div style="text-align: center;padding:20px;">
                                            <input type="submit" name="emitir-factura" value="EMITIR FACTURA" class="btn btn-success btn-block"/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<!-- Modal-1 -->
<div id="MODAL-asignar-certificado" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ASIGNACI&Oacute;N DE CERTIFICADO AL CURSO</h4>
            </div>
            <div class="modal-body">
                <p>
                    Una vez llene el siguiente formulario el curso '<?php echo $curso['titulo']; ?>' sera habilitado para emitir certificados a los participantes inscriptos.
                </p>
                <hr/>
                <form action='' method='post'>
                    <div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>TITULO:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="titulo_certificado" value="CERTIFICADO DE PARTICIPACION"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 1:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo utf8_encode("Por cuanto se reconoce que completo satisfactoriamente el curso de capacitación:"); ?>'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 2:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="contenido_dos_certificado" value='"<?php echo $curso['titulo']; ?>", con una carga horaria de 8 horas.'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 3:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
<?php
$dia_curso = date("d", strtotime($curso['fecha']));
$mes_curso = date("m", strtotime($curso['fecha']));
$array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$rqcc1 = query("SELECT nombre FROM departamentos WHERE id='" . $curso['id_ciudad'] . "' LIMIT 1 ");
$rqcc2 = mysql_fetch_array($rqcc1);
?>
                                <input type="text" class="form-control" name="contenido_tres_certificado" value="Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de 2017"/>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="text-center">
                        <input type='submit' name='habilitar-certificado' class="btn btn-success" value="HABILITAR CERTIFICADO"/>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                    </div>

                </form>

                <hr/>                                        

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-1 -->



<?php

//codigo de control
//AllegedRC4
class AllegedRC4 {

    public static function encode($msg, $key, $mode = 'hex') {
        $state = array();
        for ($i = 0; $i < 256; $i++)
            $state[] = $i;
        $x = $y = $i1 = $i2 = 0;
        $key_length = strlen($key);
        for ($i = 0; $i < 256; $i++) {
            $i2 = (ord($key[$i1]) + $state[$i] + $i2) % 256;
            self::swap($state[$i], $state[$i2]);
            $i1 = ($i1 + 1) % $key_length;
        }
        $msg_length = strlen($msg);
        $msg_hex = '';
        for ($i = 0; $i < $msg_length; $i++) {
            $x = ($x + 1) % 256;
            $y = ($state[$x] + $y) % 256;
            self::swap($state[$x], $state[$y]);
            $xi = ($state[$x] + $state[$y]) % 256;
            $r = ord($msg[$i]) ^ $state[$xi];
            $msg[$i] = chr($r);
            $msg_hex .= sprintf("%02X", $r);
        }
        return ($mode == 'hex' ? $msg_hex : $msg);
    }

    private static function swap(&$x, &$y) {
        $z = $x;
        $x = $y;
        $y = $z;
    }

}

//Verhoeff
class Verhoeff {

    private static $mul = array(
        array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
        array(1, 2, 3, 4, 0, 6, 7, 8, 9, 5),
        array(2, 3, 4, 0, 1, 7, 8, 9, 5, 6),
        array(3, 4, 0, 1, 2, 8, 9, 5, 6, 7),
        array(4, 0, 1, 2, 3, 9, 5, 6, 7, 8),
        array(5, 9, 8, 7, 6, 0, 4, 3, 2, 1),
        array(6, 5, 9, 8, 7, 1, 0, 4, 3, 2),
        array(7, 6, 5, 9, 8, 2, 1, 0, 4, 3),
        array(8, 7, 6, 5, 9, 3, 2, 1, 0, 4),
        array(9, 8, 7, 6, 5, 4, 3, 2, 1, 0),
    );
    private static $per = array(
        array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
        array(1, 5, 7, 6, 2, 8, 3, 0, 9, 4),
        array(5, 8, 0, 3, 7, 9, 6, 1, 4, 2),
        array(8, 9, 1, 6, 0, 4, 3, 5, 2, 7),
        array(9, 4, 5, 3, 1, 2, 6, 8, 7, 0),
        array(4, 2, 8, 6, 5, 7, 3, 9, 0, 1),
        array(2, 7, 9, 3, 8, 0, 6, 4, 1, 5),
        array(7, 0, 4, 6, 9, 1, 3, 2, 5, 8),
    );
    private static $inv = array(0, 4, 3, 2, 1, 5, 6, 7, 8, 9);

    public static function get($num) {
        $ck = 0;
        $num = '' . $num;
        $num_length = strlen($num);
        for ($i = $num_length - 1; $i >= 0; $i--)
            $ck = self::$mul[$ck][self::$per[($num_length - $i) % 8][$num[$i]]];
        return self::$inv[$ck];
    }

}

//CodigoControlV7
class CodigoControlV7 {

    public static function generar($numautorizacion, $numfactura, $nitcliente, $fecha, $monto, $llave_dosificacion) {
        $numfactura = self::appendVerhoeff($numfactura, 2);
        $nitcliente = self::appendVerhoeff($nitcliente, 2);
        $fecha = self::appendVerhoeff($fecha, 2);
        $monto = self::appendVerhoeff($monto, 2);
        $suma = $numfactura + $nitcliente + $fecha + $monto;
        $suma = self::appendVerhoeff($suma, 5);
        $dv = substr($suma, -5);
        $cads = array($numautorizacion, $numfactura, $nitcliente, $fecha, $monto);
        $msg = '';
        $p = 0;
        for ($i = 0; $i < 5; $i++) {
            $msg .= $cads[$i] . substr($llave_dosificacion, $p, 1 + $dv[$i]);
            $p += 1 + $dv[$i];
        }
        $codif = AllegedRC4::encode($msg, $llave_dosificacion . $dv);
        $st = 0;
        $sp = array(0, 0, 0, 0, 0);
        $codif_length = strlen($codif);
        for ($i = 0; $i < $codif_length; $i++) {
            $st += ord($codif[$i]);
            $sp[$i % 5] += ord($codif[$i]);
        }
        $stt = 0;
        for ($i = 0; $i < 5; $i++)
            $stt += (int) (($st * $sp[$i]) / (1 + $dv[$i]));

        return implode('-', str_split(AllegedRC4::encode(self::base64($stt), $llave_dosificacion . $dv), 2));
    }

    public static function base64($n) {
        $d = array(
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd',
            'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
            'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
            'y', 'z', '+', '|');
        $c = 1;
        $r = '';
        while ($c > 0) {
            $c = (int) ($n / 64);
            $r = $d[$n % 64] . $r;
            $n = $c;
        }
        return $r;
    }

    public static function appendVerhoeff($n, $c) {
        for (; $c > 0; $c--)
            $n .= Verhoeff::get($n);
        return $n;
    }

}
?>