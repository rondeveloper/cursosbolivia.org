<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

/* datos de formulario post */
$id_proceso_registro = post('id_proceso_registro');

/* datos de registro */
$rqdr1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
$registro_curso = fetch($rqdr1);

$id_curso = $registro_curso['id_curso'];
$nro_participantes = $registro_curso['cnt_participantes'];
$id_turno = $registro_curso['id_turno'];

$cnt_participantes = $nro_participantes;
$nit = $registro_curso['nit'];
$razon_social = $registro_curso['razon_social'];
$fecha_registro = $registro_curso['fecha_registro'];
$correo_proceso_registro = $registro_curso['correo_contacto'];
$celular_proceso_registro = $registro_curso['celular_contacto'];
$codigo_de_registro = $registro_curso['codigo'];
$hashcod_registro = $registro_curso['hash_cod'];

/* datos del curso */
$rq1 = query("SELECT *,(select titulo from departamentos where id=c.id_ciudad)ciudad,(select nombre from cursos_lugares where id=c.id_lugar limit 1)lugar_curso FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rq1);
$titulo_identificador_curso = $curso['titulo_identificador'];

$titulo_curso = $curso['titulo'];
$url_curso = $curso['titulo_identificador'];
$fecha_curso = $curso['fecha'];
$ciudad_curso = $curso['ciudad'];
$lugar_curso = $curso['lugar_curso'];
$horario_curso = $curso['horarios'];

if (isset_post('inscripcion')) {

    /* imagen de deposito */
    $monto_deposito = post('monto_deposito');
    $transaccion_id = post('transaccion_id');
    $ciudad_pago = post('ciudad');
    $fecha_pago = post('fecha').' '.post('hora');
    $name_arch = 'imagen_deposito';
    $archivo_name = $_FILES[$name_arch]['name'];
    $archivo_type = $_FILES[$name_arch]['type'];
    $archivo_tmp_name = $_FILES[$name_arch]['tmp_name'];
    $archivo_size = $_FILES[$name_arch]['size'];
    $arext1 = explode('.', $archivo_name);
    $archivo_extension = strtolower($arext1[count($arext1) - 1]);
    $archivo_new_name = "deposito-" . date("ymd") . "-" . rand(99, 999) . "";
    $sw_pago_enviado = '1';

    if (is_uploaded_file($_FILES[$name_arch]['tmp_name'])) {

        if ($archivo_size > (7 * 1048576)) {
            $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                El archivo no debe superar los ' . 7 . ' Megabyte(s).
              </div>';
            echo "<script>alert('Error! El archivo no debe superar los 7 Megabyte(s).');location.href='registro-curso/$url_curso.html';</script>";
            exit;
        } elseif ($archivo_type !== 'image/png' && $archivo_type !== 'image/jpj' && $archivo_type !== 'image/jpeg') {
            $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                Solo se permiten archivos PNG / JPG / JPEG
              </div>';
            echo "<script>alert('Error! Solo se permiten archivos PNG / JPG / JPEG');location.href='registro-curso/$url_curso.html';</script>";
            exit;
        } elseif ($archivo_extension !== 'png' && $archivo_extension !== 'jpg' && $archivo_extension !== 'jpeg') {
            $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                Solo se permiten archivos PNG / JPG / JPEG
              </div>';
            echo "<script>alert('Error! Solo se permiten archivos PNG / JPG / JPEG');location.href='registro-curso/$url_curso.html';</script>";
            exit;
        } else {

            /* Se carga la clase de redimencion de imagen */
            require_once ("contenido/librerias/classes/Thumbnail.php");

            $thumb = new Thumbnail($archivo_tmp_name);
            if ($thumb->error) {
                echo $thumb->error;
                $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                No se pudo subir el archivo - ' . $thumb->error . '
              </div>';
            } else {
                $thumb->maxHeight(1200);
                $thumb->save_jpg("", "$archivo_new_name");
                $archivo_new_name_w_extension = $archivo_new_name . ".jpeg";
                rename($archivo_new_name_w_extension, "contenido/imagenes/depositos/$archivo_new_name_w_extension");

                //query("UPDATE articulos SET imagen='$archivo_new_name_w_extension' WHERE id='$id_articulo' LIMIT 1 ");
                $mensaje = '<div class="alert alert-success alert-dismissible">
                <h4><i class="fa fa-thumbs-up"></i> Exito!</h4>
                El registro se realizo correctamente.
              </div>';
            }
        }
    } else {
        echo "<script>alert('Es necesario que se ingrese la foto del deposito para completar el registro!');history.back();</script>";
        exit;
    }

    /* proceso registro */
    query("UPDATE cursos_proceso_registro SET id_modo_pago='3',imagen_deposito='$archivo_new_name_w_extension',monto_deposito='$monto_deposito',sw_pago_enviado='$sw_pago_enviado',transaccion_id='$transaccion_id',paydata_fecha=NOW(),paydata_id_administrador='1',pago_id_departamento='$ciudad_pago',pago_fechahora='$fecha_pago' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
    query("UPDATE cursos_participantes SET id_modo_pago='3',sw_pago='$sw_pago_enviado' WHERE id_proceso_registro='$id_proceso_registro' ");
    

    $datos_formulario_de_inscripcion = "<table style='width:100%;'>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Codigo de registro:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$codigo_de_registro</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Nro de participantes:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$cnt_participantes participante(s)</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Curso:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$titulo_curso</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Url del curso:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$url_curso</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Fecha:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$fecha_curso</td>";
    $datos_formulario_de_inscripcion .= "</tr>";


    /* PARTICIPANTES DEL CURSO */
    $rqpic1 = query("SELECT * FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ");
    $i = 0;
    while ($rqpc2 = fetch($rqpic1)) {
        $i++;
        $nombres = $rqpc2['nombres'];
        $apellidos = $rqpc2['apellidos'];
        $prefijo = $rqpc2['prefijo'];
        $correo = $rqpc2['correo'];
        $celular = $rqpc2['celular'];
        $id_participante = $rqpc2['id'];

        $datos_formulario_de_inscripcion .= "<tr>";
        $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Participante $i:</td>";
        $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$nombres $apellidos<br/>$correo<br/>$celular</td>";
        $datos_formulario_de_inscripcion .= "</tr>";
        
        logcursos('ENVIO IMAGEN DEPOSITO DE PAGO', 'participante-registro', 'participante', $id_participante);
    }

    $datos_formulario_de_inscripcion .= "</table>";

    if ($id_turno == '0') {
        $tr_turno = "";
    } else {
        $rqdt1 = query("SELECT titulo,descripcion FROM cursos_turnos WHERE id='$id_turno' LIMIT 1 ");
        $rqdt2 = fetch($rqdt1);
        $tr_turno = "<tr><td style='padding:5px;'>Turno:</td><td style='padding:5px;'>" . $rqdt2['titulo'] . " | " . $rqdt2['descripcion'] . "</td></tr>";
    }
}



/* ingreso de deposito posterior */
if (isset_post('inscripcion-deposito-posterior')) {

    /* proceso registro */
    query("UPDATE cursos_proceso_registro SET id_modo_pago='3' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
    query("UPDATE cursos_participantes SET id_modo_pago='3' WHERE id_proceso_registro='$id_proceso_registro' ");


    $datos_formulario_de_inscripcion = "<table style='width:100%;'>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Codigo de registro:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$codigo_de_registro</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Nro de participantes:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$cnt_participantes participante(s)</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Curso:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$titulo_curso</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Url del curso:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$url_curso</td>";
    $datos_formulario_de_inscripcion .= "</tr>";
    $datos_formulario_de_inscripcion .= "<tr>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Fecha:</td>";
    $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$fecha_curso</td>";
    $datos_formulario_de_inscripcion .= "</tr>";

    /* PARTICIPANTES DEL CURSO */
    $rqpic1 = query("SELECT * FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ");
    $i = 0;
    while ($rqpc2 = fetch($rqpic1)) {
        $i++;
        $nombres = $rqpc2['nombres'];
        $apellidos = $rqpc2['apellidos'];
        $prefijo = $rqpc2['prefijo'];
        $correo = $rqpc2['correo'];
        $celular = $rqpc2['celular'];
        $id_participante = $rqpc2['id'];

        $datos_formulario_de_inscripcion .= "<tr>";
        $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>Participante $i:</td>";
        $datos_formulario_de_inscripcion .= "<td style='padding:5px;'>$nombres $apellidos<br/>$correo<br/>$celular</td>";
        $datos_formulario_de_inscripcion .= "</tr>";
        
        logcursos('Eleccion envio de DEPOSITO posteriormente', 'participante-registro', 'participante', $id_participante);
    }

    $datos_formulario_de_inscripcion .= "</table>";


    if ($id_turno == '0') {
        $tr_turno = "";
    } else {
        $rqdt1 = query("SELECT titulo,descripcion FROM cursos_turnos WHERE id='$id_turno' LIMIT 1 ");
        $rqdt2 = fetch($rqdt1);
        $tr_turno = "<tr><td style='padding:5px;'>Turno:</td><td style='padding:5px;'>" . $rqdt2['titulo'] . " | " . $rqdt2['descripcion'] . "</td></tr>";
    }
}
?>


<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">

        <div class="box_seccion_a" style="width:100%;">
            <div class="seccion_a">
                <div class="contenido_seccion white-content-one">


                    <div class='row'>
                        <div class='panel'>
                            <div class='panel-body'>

                                <div class="areaRegistro1 ftb-registro-5">

                                    <h3 class="tit-02">INSCRIPCI&Oacute;N DE PARTICIPANTES</h3>

                                    <div class="row">
                                        <?php
                                        include_once 'contenido/paginas/items/item.m.datos_curso.php';
                                        ?>
                                    </div>

                                    <div>
                                        <h3 style="background:#DDD;color:#444;margin-top: 20px;">INSCRIPCION FINALIZADA</h3>

                                        <?php
                                        echo "$mensaje";
                                        ?>

                                        <div class="panel">
                                            <?php
                                            $rqrpc1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
                                            $rqrpc2 = fetch($rqrpc1);
                                            $codigo_registro = $rqrpc2['codigo'];
                                            $cnt_participantes_registro = $rqrpc2['cnt_participantes'];
                                            $razon_social_registro = $rqrpc2['razon_social'];
                                            $nit_registro = $rqrpc2['nit'];
                                            $fecha_registro = date("d/m/Y H:i",strtotime($rqrpc2['fecha_registro']));
                                            $monto_deposito_registro = $rqrpc2['monto_deposito'];
                                            $rqc1 = query("SELECT *,(select nombre from departamentos where id=c.id_ciudad limit 1)ciudad,(select nombre from cursos_lugares where id=c.id_lugar limit 1)lugar_curso FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
                                            $rqc2 = fetch($rqc1);
                                            $nombre_curso = $rqc2['titulo'];
                                            $url_curso = $dominio . $rqc2['titulo_identificador'] . ".html";
                                            $lugar_curso = $rqc2['lugar_curso'];
                                            $fecha_curso = date("d/m/Y",strtotime($rqc2['fecha']));
                                            $horario_curso = $rqc2['horarios'];
                                            $ciudad_curso = $rqc2['ciudad'];
                                            ?>
                                            <style>
                                                .aux-css-static-1{
                                                    padding:10px 30px;
                                                }
                                                @media screen and (max-width: 650px){
                                                    .aux-css-static-1{
                                                        padding:5px 0px;
                                                    }
                                                    .areaRegistro1 td {
                                                        float: left;
                                                        width: 50%;
                                                        text-align: left !important;
                                                    }
                                                }
                                            </style>

                                            <div class="panel-body">
                                                <div>
                                                    <p style="text-align: left;"><span style="font-size: 12pt; color: #ff0000;"><strong><?php echo $nombre_curso; ?><br></strong></span></p>
                                                    <p style="text-align: left;">
                                                        <span style="font-size: 10pt;text-align: justify; color: #000000;">
                                                            Proceso de inscripci&oacute;n para el curso '<?php echo $nombre_curso; ?>' a llevarse a cabo 
                                                            en fecha <?php echo $fecha_curso; ?> en la ciudad de <?php echo $ciudad_curso; ?>.
                                                            <br/>
                                                            A continuaci&oacute;n se muestran detalles de la inscripci&oacute;n. 
                                                        </span>
                                                        <br/>
                                                    </p>

                                                    <div class="panel panel-info">
                                                        <div class="panel-heading">
                                                            FICHA DE INSCRIPCI&Oacute;N
                                                        </div>
                                                        <div class="panel-body text-center">
                                                            <p style="text-align: left;">
                                                                <span style="font-size: 10pt;text-align: justify; color: #000000;">
                                                                    Para poder hacer el ingreso el d&iacute;a del curso es necesario llevar esta ficha previamente impresa junto con el deposito original del pago realizado.
                                                                </span>
                                                            </p>
                                                            <br/>
                                                            <button class="btn btn-primary" onclick="window.open('<?php echo $dominio.encrypt('registro-participantes-curso/' . $id_proceso_registro); ?>.impresion', 'popup', 'width=700,height=500');
                                                            return false;"><i class="fa fa-print"></i> IMPRIMIR FICHA DE INSCRIPCI&Oacute;N</button>
                                                            <br/>
                                                            <br/>
                                                            <a href="<?php echo $dominio.encrypt('registro-participantes-curso/' . $id_proceso_registro . '/pdf'); ?>.impresion" class="btn btn-danger"><i class="fa fa-print"></i> DESCARGAR EN PDF</a>
                                                            <br/>

                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="panel panel-info">
                                                                <div class="panel-heading">
                                                                    DATOS DEL CURSO
                                                                </div>
                                                                <div class="panel-body">
                                                                    <table class="table table-striped">
                                                                        <tr><td style="padding:5px;"><b>Codigo de registro:</b></td><td style="padding:5px;"><?php echo $codigo_registro; ?></td></tr>
                                                                        <tr><td style="padding:5px;"><b>Curso:</b></td><td style="padding:5px;"><?php echo $nombre_curso; ?></td></tr>
                                                                        <tr><td style="padding:5px;"><b>Url del curso:</b></td><td style="padding:5px;"><?php echo $url_curso; ?></td></tr>
<!--                                                                        <tr><td style="padding:5px;"><b>Lugar:</b></td><td style="padding:5px;"><?php echo $lugar_curso; ?></td></tr>-->
                                                                        <tr><td style="padding:5px;"><b>Fecha:</b></td><td style="padding:5px;"><?php echo $fecha_curso; ?></td></tr>
                                                                        <?php echo $tr_turno; ?>
                                                                        <tr><td style="padding:5px;"><b>Fecha de registro:</b></td><td style="padding:5px;"><?php echo $fecha_registro; ?></td></tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="panel panel-info">
                                                                <div class="panel-heading">
                                                                    DATOS DE LOS PARTICIPANTES
                                                                </div>
                                                                <div class="panel-body">
                                                                    <?php
                                                                    $sw_facturacion = false;
                                                                    if ((strlen($razon_social_registro) > 3) && (strlen($nit_registro) > 3)) {
                                                                        $sw_facturacion = true;
                                                                    }
                                                                    ?>
                                                                    <table class="table table-striped">
                                                                        <tr>
                                                                            <td style="padding:5px;"><b>Nro de participantes:</b></td>
                                                                            <td style="padding:5px;"><?php echo $cnt_participantes_registro; ?> participante(s)</td>
                                                                        </tr>
                                                                        <?php
                                                                        if ($sw_facturacion) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><b>Factura a nombre de:</b></td>
                                                                                <td><?php echo $razon_social_registro; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>N&uacute;mero de NIT:</b></td>
                                                                                <td><?php echo $nit_registro; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="2" style='text-align:center;'><b>Participante(s):</b></td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                        $rqpc1 = query("SELECT * FROM cursos_participantes WHERE id_proceso_registro='$id_proceso_registro' ORDER BY id DESC limit 20 ");
                                                                        while ($rqpc2 = fetch($rqpc1)) {
                                                                            $nombres_participante = $rqpc2['nombres'];
                                                                            $apellidos_participante = $rqpc2['apellidos'];
                                                                            $prefijo_participante = $rqpc2['prefijo'];
                                                                            $celular_participante = $rqpc2['celular'];
                                                                            $ci_participante = $rqpc2['ci'];
                                                                            $ci_expedido_participante = $rqpc2['ci_expedido'];
                                                                            $correo_participante = $rqpc2['correo'];
                                                                            ?>
                                                                            <tr>
                                                                                <td><b>Nombres:</b></td>
                                                                                <td><?php echo $nombres_participante; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Apellidos:</b></td>
                                                                                <td><?php echo $apellidos_participante; ?></td>
                                                                            </tr>
<!--                                                                            <tr>
                                                                                <td><b>Prefijo:</b> (Profesi&oacute;n)</td>
                                                                                <td><?php echo $prefijo_participante; ?></td>
                                                                            </tr>-->
                                                                            <tr>
                                                                                <?php
                                                                                 $array_expedido_base = array('LP','OR','PS','CB','SC','BN','PD','TJ','CH');
                                                                                 $array_expedido_short = array('LP','OR','PT','CB','SC','BN','PA','TJ','CH');
                                                                                ?>
                                                                                <td><b>C.I.:</b></td>
                                                                                <td><?php echo $ci_participante.' '.str_replace($array_expedido_base, $array_expedido_short, $ci_expedido_participante); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Celular:</b></td>
                                                                                <td><?php echo $celular_participante; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><b>Correo:</b></td>
                                                                                <td><?php echo $correo_participante; ?></td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>



                                    <p style="text-align: left;">
                                        <span style="font-size: 10pt;text-align: justify; color: #000000;">
                                            Muchas gracias por realizar tu inscripci&oacute;n.
                                        </span>
                                    </p>

                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-sm-12 text-center">
                                                <?php echo $___nombre_del_sitio; ?>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>



    </section>
</div>     

<script>
    function habilitar_participantes(nro) {

        if (nro > 1) {
            $("#correo_part").css("display", "block");
            $("#cel_part").css("display", "block");
        } else {
            $("#correo_part").css("display", "none");
            $("#cel_part").css("display", "none");
        }

        for (var i = 1; i <= 7; i++) {

            if (i <= nro) {
                $("#box-participante-" + i).css("display", "block");
            } else {
                $("#box-participante-" + i).css("display", "none");
            }
        }
    }
</script>


<?php
function fecha_curso($dat) {
    $ar1 = explode("-", $dat);
    $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $ar1[2] . " de " . $array_meses[(int) $ar1[1]];
}
