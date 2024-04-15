<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_participante = post('id_participante');
$prefijo = post('prefijo');
$nombres = ucfirst(trim(post('nombres')));
$apellidos = ucfirst(trim(post('apellidos')));
$ci = post('ci');
$ci_expedido = post('ci_expedido');
$celular = post('celular');
$correo = post('correo');
$razon_social = post('razon_social');
$nit = post('nit');
$monto_pago = post('monto_pago');
$id_curso = post('id_curso');
$id_turno = post('id_turno');
$observacion = post('observacion');
$numeracion = post('numeracion');
$id_departamento = post('id_departamento');

$id_administrador = administrador('id');

/* imagen deposito */
$imagen_deposito = post('actual_imagen_deposito');
if (is_uploaded_file(archivo('imagen_deposito'))) {
    if ($imagen_deposito !== '') {
        logcursos('Sube imagen respaldo de pago[prev:' . $imagen_deposito . ']', 'partipante-edicion', 'participante', $id_participante);
    }
    $imagen_deposito = 'depos-' . rand(0, 99) . '-' . substr(str_replace(' ', '-', archivoName('imagen_deposito')), (strlen(archivoName('imagen_deposito')) - 7));
    move_uploaded_file(archivo('imagen_deposito'), $___path_raiz.'contenido/imagenes/depositos/' . $imagen_deposito);
}


/* re enumeracion */
if (isset_post('reenumerar') && post('reenumerar') == '1') {
    /*
      $rqinp1 = query("SELECT numeracion FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
      $rqinp2 = fetch($rqinp1);
      $inicio_numeracion_previo = $rqinp2['numeracion'];
     */
    /* actualizar numeracion */
    $aux_numeracion = $numeracion;
    $rqan1 = query("SELECT id FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' AND id>='$id_participante' AND id NOT IN (select id_participante from cursos_part_apartados) ORDER BY id ASC ");
    while ($rqan2 = fetch($rqan1)) {
        $id_part_temp = $rqan2['id'];
        query("UPDATE cursos_participantes SET numeracion='$aux_numeracion' WHERE id='$id_part_temp' ORDER BY id DESC limit 1 ");
        $aux_numeracion++;
    }
}

/* verificacion de cambio de nombre */
$qrvcn1 = query("SELECT nombres,apellidos FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$qrvcn2 = fetch($qrvcn1);
$prev_name = trim($qrvcn2['nombres']).' '.trim($qrvcn2['apellidos']);
$curr_name = trim($nombres).' '.trim($apellidos);
if($prev_name !=$curr_name){
    query("INSERT INTO participantes_cambios_de_nombre(id_participante, id_administrador, nombre_anterior, nombre_actual, fecha) VALUES ('$id_participante','$id_administrador','$prev_name','$curr_name',NOW())");
}


/* edicion de datos de participante */
query("UPDATE cursos_participantes SET 
            prefijo='$prefijo',
            nombres='$nombres',
            apellidos='$apellidos',
            ci='$ci',
            ci_expedido='$ci_expedido',
            celular='$celular',
            correo='$correo',
            observacion='$observacion',
            numeracion='$numeracion',
            id_turno='$id_turno', 
            id_departamento='$id_departamento', 
            sw_notif='1' 
             WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

/* edicion de datos de registro */
$rqdr1 = query("SELECT id_proceso_registro FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdr2 = fetch($rqdr1);
$id_proceso_registro = $rqdr2['id_proceso_registro'];

query("UPDATE cursos_proceso_registro SET 
            razon_social='$razon_social',
            nit='$nit',
            id_turno='$id_turno' 
            WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

/* datos auxiliares de participante */
$rqdap1 = query("SELECT id_emision_certificado,id_emision_certificado_2,id_emision_certificado_3 FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdap2 = fetch($rqdap1);
$id_emision_certificado = $rqdap2['id_emision_certificado'];
$id_emision_certificado_2 = $rqdap2['id_emision_certificado_2'];
$id_emision_certificado_3 = $rqdap2['id_emision_certificado_3'];


/* datos auxiliares de factura */
$rqdrp1 = query("SELECT id_emision_factura FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
$rqdrp2 = fetch($rqdrp1);
$id_emision_factura = $rqdrp2['id_emision_factura'];

/* datos de curso */
$rqauxc01 = query("SELECT fecha,id_certificado,id_certificado_2,id_certificado_3 FROM cursos WHERE id='$id_curso' LIMIT 1 ");
$rqauxc02 = fetch($rqauxc01);
$fecha_curso = $rqauxc02['fecha'];
$id_certificado_curso = $rqauxc02['id_certificado'];
$id_certificado_2_curso = $rqauxc02['id_certificado_2'];
$id_certificado_3_curso = $rqauxc02['id_certificado_3'];

logcursos('Edicion de datos de participante', 'partipante-edicion', 'participante', $id_participante);
if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
    logcursos('Edicion de participante [fuera de fecha][' . $nombres . ' ' . $apellidos . ']', 'curso-edicion', 'curso', $id_curso);
}
query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
?>

<div class="alert alert-success">
    <strong>Exito!</strong> Participante editado correctamente.
</div>

<b>Enlaces rapidos:</b>
<br/>
<br/>
<div class='row'>
    <?php
    /* factura */
    if ($id_emision_factura == '0') {
        ?>
        <b data-toggle="modal" data-target="#MODAL-emite-factura" class='col-md-5 btn btn-warning active' onclick="emite_factura_p1(<?php echo $id_participante; ?>);">EMITIR FACTURA</b>
        <?php
    } else {
        $rqdf1 = query("SELECT id_dosificacion,nro_factura,nit_receptor,total,codigo_de_control,concepto FROM facturas_emisiones WHERE id='$id_emision_factura' ORDER BY id DESC limit 1 ");
        $rqdf2 = fetch($rqdf1);
        $nro_factura = $rqdf2['nro_factura'];
        $id_dosificacion = $rqdf2['id_dosificacion'];
        $nit_receptor_factura = $rqdf2['nit_receptor'];
        $total_factura = $rqdf2['total'];
        $codigo_de_control_factura = $rqdf2['codigo_de_control'];
        $concepto_factura = $rqdf2['concepto'];
        /* edicion de factura */
        if (($nit_receptor_factura == $nit) && ($total_factura == $monto_pago)) {
            $arccp1 = explode('Participante:', $concepto_factura);
            $concepto_factura = $arccp1[0] . 'Participante: ' . $nombres . ' ' . $apellidos;
            query("UPDATE facturas_emisiones SET nombre_receptor='$razon_social',concepto='$concepto_factura' WHERE id='$id_emision_factura' ORDER BY id DESC limit 1 ");
        } else {
            /* creacion de nueva factura con nuevos datos */
            $nombre_a_facturar = $razon_social;
            $nit_a_facturar = $nit;
            $monto_a_facturar = $monto_pago;
            if ((int) $monto_a_facturar > 0) {

                $id_certificado = $id_emision_certificado;
                $id_curso = $id_curso;
                $id_participante = $id_participante;
                $id_administrador = administrador('id');

                /* datos para emision de factura */
                $rqdf1 = query("SELECT * FROM facturas_dosificaciones WHERE estado='1' ORDER BY id DESC limit 1 ");
                $rqdf2 = fetch($rqdf1);

                $nro_autorizacion = $rqdf2['nro_autorizacion'];
                $nit_emisor = $rqdf2['nit_emisor'];
                $fecha_limite_emision = $rqdf2['fecha_limite_emision'];
                $llave_dosificacion = $rqdf2['llave_dosificacion'];

                /* datos curso */
                $rqauxc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' LIMIT 1 ");
                $rqauxc2 = fetch($rqauxc1);
                $titulo_curso = $rqauxc2['titulo'];

                /* datos participante */
                $participante_curso = $nombres . ' ' . $apellidos;

                $concepto = strtoupper($titulo_curso . ' - PARTICIPANTE: ' . $participante_curso);
                $fecha_emision = date("Y-m-d");
                $fecha_registro = date("Y-m-d H:i");

                /* numero de factura */
                $nro_factura;

                /* generacion de codigo de control */
                $codigo_de_control = CodigoControlV7::generar($nro_autorizacion, $nro_factura, $nit_a_facturar, str_replace('-', '', $fecha_emision), Util::redondeoMontoCodigoControl($monto_a_facturar), $llave_dosificacion);

                query("INSERT INTO facturas_emisiones(
           id_dosificacion,
           id_administrador,
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
            '$id_dosificacion', 
            '$id_administrador', 
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
           '1'
           )");

                /* id de emision de factura */
                $rqef1 = query("SELECT id FROM facturas_emisiones WHERE nro_factura='$nro_factura' AND codigo_de_control='$codigo_de_control' ORDER BY id DESC limit 1 ");
                $rqef2 = fetch($rqef1);
                $id_emision_nueva_factura = $rqef2['id'];

                /* actualizacion de datos de proceso de registro */
                query("UPDATE cursos_proceso_registro SET id_emision_factura='$id_emision_nueva_factura' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

                /* eliminacion de anterior factura */
                query("DELETE FROM facturas_emisiones WHERE id='$id_emision_factura' ORDER BY id DESC limit 1 ");

                logcursos('Emision de nueva factura por cambio de datos de facturacion[F:' . $nro_factura . ']', 'partipante-edicion', 'participante', $id_participante);
                if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
                    logcursos('Emision de factura [fuera de fecha][F:' . $nro_factura . ']', 'curso-edicion', 'curso', $id_curso);
                }
                ?>
                <div class="alert alert-warning">
                    <strong>AVISO!</strong> Se elimin&oacute; la factura [<?php echo $codigo_de_control_factura; ?>] y fu&eacute; remplazada por  [<?php echo $codigo_de_control; ?>].
                </div>
                <?php
            } else {
                echo "<b>Error!</b> no se ingreso monto para la facturaci&oacute;n.";
            }
        }
        ?>
        <b onclick="window.open('http://www.infosicoes.com/contenido/paginas/procesos/pdfs/factura-1.php?nro_factura=<?php echo $nro_factura; ?>', 'popup', 'width=700,height=500');" class='col-md-5 btn btn-success active'>IMPRIMIR FACTURA</b>
        <?php
    }
    ?>

    <b class='col-md-2'></b>

    <div class="col-md-5">
        <?php
        /* primer certificado */
        /*
          if ($id_emision_certificado == '0') {

          $rqdc1 = query("SELECT id_certificado FROM cursos WHERE id='$id_curso' LIMIT 1 ");
          $rqdc2 = fetch($rqdc1);
          if ($rqdc2['id_certificado'] !== '0') {
          ?>
          <b data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $id_participante; ?>, 1);" class='col-md-5 btn btn-primary active'>EMITIR CERTIFICADO</b>
          <?php
          }
          } else {
          ?>
          <b onclick="imprimir_certificado_individual('<?php echo $id_emision_certificado; ?>');" class='col-md-5 btn btn-info active'>IMPRIMIR CERTIFICADO</b>
          <?php
          }
         */



        $sw_existencia_certificado_uno = $sw_existencia_certificado_dos = $sw_existencia_certificado_tres = false;
        /* primer certificado */
        if ($id_emision_certificado == '0' && $id_certificado_curso !== '0') {
            $sw_existencia_certificado_uno = false;
            ?>
            <span id='box-modal_emision_certificado-button-<?php echo $id_participante; ?>'>
                <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $id_participante; ?>, 1);" class="btn btn-sm btn-primary active">C1</a>
            </span>
            <?php
        } elseif ($id_emision_certificado !== '0') {
            $sw_existencia_certificado_uno = $sw_exist_cert_uno = true;
            ?>
            <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $id_participante; ?>, 1);" class="btn btn-sm btn-warning active">C1</a>
            <?php
        }

        /* segundo certificado */
        if ($id_emision_certificado_2 == '0' && $id_certificado_2_curso !== '0') {
            $sw_existencia_certificado_dos = false;
            ?>
            <span id='box-modal_emision_certificado-button-2-<?php echo $id_participante; ?>'>
                <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $id_participante; ?>, 2);" class="btn btn-sm btn-primary active">C2</a>
            </span>
            <?php
            if (!$sw_existencia_certificado_uno && $id_certificado_3_curso == '0') {
                ?>
                <span id='box-modal_emision_certificado-button-12-<?php echo $id_participante; ?>'>
                    <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $id_participante; ?>, 12);" class="btn btn-sm btn-primary">C12</a>
                </span>
                <?php
            }
        } elseif ($id_emision_certificado_2 !== '0') {
            $sw_existencia_certificado_dos = $sw_exist_cert_dos = true;
            ?>
            <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $id_participante; ?>, 2);" class="btn btn-sm btn-warning active">C2</a>
            <?php
        }

        /* tercer certificado */
        if ($id_emision_certificado_3 == '0' && $id_certificado_3_curso !== '0') {
            $sw_existencia_certificado_tres = false;
            ?>
            <span id='box-modal_emision_certificado-button-3-<?php echo $id_participante; ?>'>
                <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $id_participante; ?>, 3);" class="btn btn-sm btn-primary active">C3</a>
            </span>
            <?php
            if (!$sw_existencia_certificado_uno && !$sw_existencia_certificado_dos) {
                ?>
                <span id='box-modal_emision_certificado-button-123-<?php echo $id_participante; ?>'>
                    <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $id_participante; ?>, 123);" class="btn btn-sm btn-primary btn-block">C123</a>
                </span>
                <?php
            }
        } elseif ($id_emision_certificado_3 !== '0') {
            $sw_existencia_certificado_tres = $sw_exist_cert_tres = true;
            ?>
            <a data-toggle="modal" data-target="#MODAL-emite-certificado" onclick="emite_certificado_p1(<?php echo $id_participante; ?>, 3);" class="btn btn-sm btn-warning active">C3</a>
            <?php
        }

        if ($sw_existencia_certificado_uno && $sw_existencia_certificado_dos && $sw_existencia_certificado_tres) {
            ?>
            <a onclick="imprimir_tres_certificados('<?php echo $id_emision_certificado . ',' . $id_emision_certificado_2 . ',' . $id_emision_certificado_3; ?>');" class="btn btn-sm btn-warning btn-block">C123</a>
            <?php
        } elseif ($sw_existencia_certificado_uno && $sw_existencia_certificado_dos && $id_certificado_3_curso == '0') {
            ?>
            <a onclick="imprimir_dos_certificados('<?php echo $id_emision_certificado . ',' . $id_emision_certificado_2; ?>');" class="btn btn-sm btn-warning">C12</a>
            <?php
        }
        ?>
    </div>
</div>





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