<?php
/* mensaje */
$mensaje = '';

/* ip */
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_coneccion = real_escape_string($_SERVER['HTTP_X_FORWARDED_FOR']);
} else {
    $ip_coneccion = real_escape_string($_SERVER['REMOTE_ADDR']);
}

/* $certificado_id */
$certificado_id = '';
if (isset_post('id_certificado')) {
    $certificado_id = post('id_certificado');
}

$sw_certificado_valido = false;

/* validacion */
if (isset_post('valida-certificado')) {
    $secret = "6LcNOxgTAAAAADNCXONZjIu37Abq0yVOF5Mg0pgw";
    $response = null;
    $reCaptcha = new ReCaptcha($secret);
    if ($_POST["g-recaptcha-response"]) {
        $response = $reCaptcha->verifyResponse(
                $_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]
        );
    }
    if (($response != null && $response->success)) {

        $certificado_id = post('id_certificado');
        $apellido = trim(str_replace('%', '', post('apellido')));
        $nombre_institucion = post('nombre_institucion');
        $fecha = date("Y-m-d H:i");

        if (strlen($apellido) >= 3) {
            /* verificacion */
            $rqdv1 = query("SELECT 
                            *,
                            ec.id_participante,
                            ec.fecha_qr,
                            ec.fecha2_qr,
                            ec.texto_qr,
                            ec.cont_uno,
                            ec.cont_dos,
                            ec.cont_tres,
                            (concat(p.prefijo,' ',p.nombres,' ',p.apellidos))receptor_certificado, 
                            (concat(p.apellidos,' ',p.nombres))participante, 
                            (ec.id_certificado)id_de_certificado 
                            FROM cursos_emisiones_certificados ec 
                            INNER JOIN cursos_participantes p 
                            ON ec.id_participante=p.id 
                            WHERE ec.certificado_id='$certificado_id' AND p.apellidos LIKE '%$apellido%' ORDER BY ec.id DESC limit 1 ");
            if (num_rows($rqdv1) > 0) {

                query("INSERT INTO cursos_validacion_certificados(
                     certificado_id, 
                     apellido, 
                     nombre_institucion,
                     fecha, 
                     ip
                     ) VALUES (
                     '$certificado_id',
                     '$apellido',
                     '$nombre_institucion',
                     '$fecha',
                     '$ip_coneccion'
                     )");
                $id_validacion = insert_id();

                $sw_certificado_valido = true;
                $certificado = fetch($rqdv1);

                logcursos('Solicitud de validacion de certificado [' . $id_validacion . ':' . $nombre_institucion . ']', 'certificado-validacion', 'participante', $certificado['id_participante']);
                logcursos('Solicitud de validacion de certificado [' . $certificado['receptor_de_certificado'] . '][' . $id_validacion . ':' . $nombre_institucion . ']', 'certificado-validacion', 'curso', $certificado['id_curso']);

                $mensaje = '<div class="alert alert-success">
  <strong>CERTIFICADO VALIDO</strong> los datos se verificaron correctamente en nuestros registros y el certificado con ID ' . $certificado_id . ' corresponde a los datos mostrados a continuaci&oacute;n.
</div>';
            } else {
                $mensaje = '<div class="alert alert-danger">
  <strong>REGISTRO NO ENCONTRADO</strong> verifique que el ID de certificado y el primer apellido sean correctos e intente nuevamente.
</div>';
            }
        } else {
            $mensaje = '<div class="alert alert-info">
  <strong>AVISO!</strong> debe ingresar el primer apellido correctamente.
</div>';
        }
    } else {
        echo "<script>alert('Verifica que no eres un robot');location.href='validacion-de-certificado.html';</script>";
    }
}
?>
<style>
    .TituloArea h3:after {
        width: 355px !important;
    }
</style>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="TituloArea">
                    <h3>VALIDADOR DE CERTIFICADOS</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        Mediante el sistema de validaci&oacute;n de certificados, se podr&aacute; verificar la autenticidad del certificado correspondiente emitido para cada uno de los cursos realizados, estos pueden ser solicitados por la instituci&oacute;n o persona que lo requiera.
                        <br/>
                        Para ello llene los datos del formulario siguiente e ingrese el <b>'ID de certificado'</b> ubicado en la parte inferior del c&oacute;digo QR del certificado emitido.
                        <br/>
                        <b>IMPORTANTE: La informaci&oacute;n v&aacute;lida es la generada en la pantalla.</b>
                    </p>
                </div>

                <br/>

                <?php echo $mensaje; ?>

                <div class="boxForm ajusta_form_contacto" style="background: #f7f7f7;border: 1px solid #5bc0de;box-shadow: 1px 1px 3px #d0d0d0;">
                    <?php
                    if ($sw_certificado_valido) {

                        /* cert */
                        $rqddcc1 = query("SELECT * FROM cursos_certificados WHERE id='" . $certificado['id_de_certificado'] . "' ORDER BY id DESC limit 1 ");
                        $rqddcc2 = fetch($rqddcc1);

                        /* datos de receptor de certificado */
                        $rqdpc1 = query("SELECT nombres,apellidos,ci,ci_expedido,prefijo,id_departamento,nombres,apellidos FROM cursos_participantes WHERE id='" . $certificado['id_participante'] . "' ORDER BY id DESC limit 1 ");
                        $rqdpc2 = fetch($rqdpc1);
                        $receptor_de_certificado = utf8_decode(trim(trim($rqdpc2['prefijo']) . ' ' . trim($rqdpc2['nombres']) . ' ' . trim($rqdpc2['apellidos'])));
                        $ci_participante = $rqdpc2['ci'];
                        $ci_expedido_participante = $rqdpc2['ci_expedido'];
                        $id_departamento_participante = $rqdpc2['id_departamento'];
                        $receptor_certificado = $rqdpc2['nombres'].' '.$rqdpc2['apellidos'];

                        $fecha_qr = $certificado['fecha_qr'];
                        $fecha2_qr = $certificado['fecha2_qr'];

                        /* generad de QR */
                        include_once 'contenido/librerias/phpqrcode/qrlib.php';
                        $file = 'qrcod-vcert-' . time() . '-' . $certificado_id . '.png';
                        $file_qr_certificado = 'contenido/imagenes/qrcode/' . $file;
                        if (!is_file($file_qr_certificado)) {
                            copy('contenido/imagenes/qrcode/jr-qrcode.png', $file_qr_certificado);
                        }
                        $aux_fecha2_qr = ' - '.$fecha2_qr;
                        if($fecha2_qr=='0000-00-00' || $fecha2_qr==$fecha_qr){
                            $aux_fecha2_qr = ''; 
                        }
                        $data = $certificado['certificado_id'] . ' | ' . utf8_decode($certificado['texto_qr']) . ' | ' . $receptor_certificado . ' | CI ' . $ci_participante . ' | ' . $certificado['fecha_qr'].$aux_fecha2_qr;
                        QRcode::png($data, $file_qr_certificado);


                        /* cont */
                        $cont_uno = $certificado['cont_uno'];
                        $cont_dos = $certificado['cont_dos'];
                        $cont_tres = $certificado['cont_tres'];

                        /* departamento de participante -> para certificado digital */
                        if($id_departamento_participante!='0'){
                            $rqddp1 = query("SELECT nombre FROM departamentos WHERE id='$id_departamento_participante' LIMIT 1 ");
                            $rqddp2 = fetch($rqddp1);
                            $cont_tres = str_replace('[DEPARTAMENTO-PARTICIPANTE]',$rqddp2['nombre'].', Bolivia', $cont_tres);
                        }else{
                            $cont_tres = str_replace('[DEPARTAMENTO-PARTICIPANTE]','BOLIVIA',$cont_tres);
                        }

                        
                        /* fechas_inicio_final */
                        $dia_curso = date("d", strtotime($fecha_qr));
                        $mes_curso = date("m", strtotime($fecha_qr));
                        $anio_curso = date("Y", strtotime($fecha_qr));
                        $array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                        if($fecha2_qr=='0000-00-00' || $fecha2_qr==$fecha_qr){
                            $fechas_inicio_final = ('a los '.$dia_curso.' d&iacute;as del mes de '.$array_meses[(int) $mes_curso].' de '.$anio_curso);
                        }else{
                            $dia2_curso = date("d", strtotime($fecha2_qr));
                            $mes2_curso = date("m", strtotime($fecha2_qr));
                            $anio2_curso = date("Y", strtotime($fecha2_qr));
                            if($mes_curso==$mes2_curso){
                                $fechas_inicio_final = 'del '.$dia_curso.' al '.$dia2_curso.' de '.$array_meses[(int) $mes_curso].' de '.$anio_curso;
                            }else{
                                $fechas_inicio_final = 'del '.$dia_curso.' de '.$array_meses[(int) $mes_curso].' al '.$dia2_curso.' de '.$array_meses[(int)$mes2_curso].' de '.$anio2_curso;
                            }
                        }

                        $cont_tres = (str_replace('[FECHAS-INICIO-FINAL]',$fechas_inicio_final,$cont_tres));
                        ?>
                        <table class="table table-striped table-hover table-bordered" style="background: #FFF;">
                            <tr>
                                <td><b>RECEPTOR DE CERTIFICADO:</b></td>
                                <td><?php echo $certificado['participante']; ?></td>
                            </tr>
                            <tr>
                                <td><b>ID DE CERTIFICADO:</b></td>
                                <td><?php echo $certificado['certificado_id']; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><?php echo $rqddcc2['cont_titulo']; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><?php echo $certificado['receptor_certificado']; ?></td>
                            </tr>
                            <?php
                            if ($ci_participante !== '' && $ci_participante !== '0') {
                                $array_expedido_base = array('LP', 'OR', 'PS', 'CB', 'SC', 'BN', 'PD', 'TJ', 'CH');
                                $array_expedido_short = array('LP', 'OR', 'PT', 'CB', 'SC', 'BN', 'PA', 'TJ', 'CH');
                                $array_expedido_literal = array('La Paz', 'Oruro', 'Potosi', 'Cochabamba', 'Santa Cruz', 'Beni', 'Pando', 'Tarija', 'Chuquisaca');
                                if ($ci_expedido_participante !== '') {
                                    $texto_cedula_identidad = "C&eacute;dula de identidad n&uacute;mero: $ci_participante expedido en " . strtoupper(str_replace($array_expedido_base, $array_expedido_literal, $ci_expedido_participante));
                                } else {
                                    $texto_cedula_identidad = "C&eacute;dula de identidad n&uacute;mero: $ci_participante";
                                }
                                ?>
                                <tr>
                                    <td colspan="2" class="text-center"><?php echo $texto_cedula_identidad; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td colspan="2" class="text-center"><?php echo $cont_uno; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><?php echo $cont_dos; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center"><?php echo $cont_tres; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <img src='<?php echo $file_qr_certificado; ?>' style="width: 200px;"/>
                                </td>
                            </tr>
                        </table>
                        <?php
                    } else {
                        ?>
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <b>ID de certificado:</b>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                                        <input type="text" name="id_certificado" value="<?php echo $certificado_id; ?>" class="form-control" placeholder="Ingrese el ID de certificado..." aria-describedby="basic-addon1" required=""/>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <b>Primer apellido:</b>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                                        <input type="text" name="apellido" value="" class="form-control" placeholder="Primer apellido del receptor del certificado..." aria-describedby="basic-addon1" onkeyup="this.value = this.value.toUpperCase()" required=""/>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <b>Nombre de la Instituci&oacute;n solicitante:</b>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                                        <input type="text" name="nombre_institucion" value="" class="form-control" placeholder="..." aria-describedby="basic-addon1"/>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center" style="margin-bottom: 10px;">
                                    <br/>
                                    <div style="width:300px;margin:auto;">
                                        <script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
                                        <div class="g-recaptcha" data-sitekey="6LcNOxgTAAAAAOIHv-MOGQ-9JMshusUgy6XTmJzD"></div>
                                    </div> 
                                    <br/>
                                </div>
                                <div class="col-md-12" style="margin-bottom: 10px;">
                                    <input type="submit" class="btn btn-info btn-block" value="VALIDAR CERTIFICADO" name="valida-certificado"/>
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                    ?>
                </div>



                <br/>
                <hr/>
                <br/>

            </div>
            <div class="col-md-2">
                <style>
                    .wtt-aux{
                        padding-left: 50px;
                    }
                </style>

            </div>
        </div>

    </section>
</div>                     



<?php

function fecha_curso($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    $anio = date("Y", strtotime($fecha));
    return "$nombredia, $dia de $nombremes de $anio";
}

/*
echo "<table>";
$rqc1 = query("SELECT p.nombres,p.apellidos,p.correo,p.celular,d.nombre,c.titulo FROM cursos_participantes p INNER JOIN departamentos d ON p.id_departamento=d.id INNER JOIN cursos c ON p.id_curso=c.id ORDER BY p.id DESC limit 200");
while($rqc2 = fetch($rqc1)){

    echo "<tr>";
    echo "<td>".$rqc2['nombres']."</td>";
    echo "<td>".$rqc2['apellidos']."</td>";
    echo "<td>".$rqc2['correo']."</td>";
    echo "<td>".$rqc2['celular']."</td>";
    echo "<td>".$rqc2['nombre']."</td>";
    echo "<td>".$rqc2['titulo']."</td>";
    echo "</tr>";

}
echo "</table>";
*/