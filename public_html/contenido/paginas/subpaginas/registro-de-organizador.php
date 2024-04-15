<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

/* mensaje */
$mensaje = '';

$nombres = '';
$apellidos = '';
$email = '';
$celular = '';
$sw_registro = false;

/* agregar-organizador */
if (isset_post('agregar-organizador')) {
    $secret = "6LcNOxgTAAAAADNCXONZjIu37Abq0yVOF5Mg0pgw";
    $response = null;
    $reCaptcha = new ReCaptcha($secret);
    if ($_POST["g-recaptcha-response"]) {
        $response = $reCaptcha->verifyResponse(
                $_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]
        );
    }
    if (($response != null && $response->success) || $sw_cookie) {

        $nombre_extendido = post('nombre_extendido');
        $nom_img = 'imagen';
        $titulo = post('titulo');
        $titulo_identificador = limpiar_enlace($titulo);
        $codigo = 'S/COD';
        $id_departamento = post('id_departamento');
        $nit = post('nit');
        $direccion = post('direccion');
        $telefono = post('telefono');
        $correo = post('correo');
        $descripcion = post('descripcion');

        /* imagen de organizador */
        $sw_imagen_uploaded = false;
        $name_arch = 'imagen';
        $archivo_name = $_FILES[$name_arch]['name'];
        $archivo_type = $_FILES[$name_arch]['type'];
        $archivo_tmp_name = $_FILES[$name_arch]['tmp_name'];
        $archivo_size = $_FILES[$name_arch]['size'];
        $arext1 = explode('.', $archivo_name);
        $archivo_extension = strtolower($arext1[count($arext1) - 1]);
        $archivo_new_name = "orgzdr-" . date("ymd") . "-" . rand(9, 9999) . "";

        if (is_uploaded_file($archivo_tmp_name)) {

            if ($archivo_size > (7 * 1048576)) {
                $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                El archivo no debe superar los ' . 7 . ' Megabyte(s).
              </div>';
            } elseif ($archivo_type !== 'image/png' && $archivo_type !== 'image/jpj' && $archivo_type !== 'image/jpeg') {
                $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                Solo se permiten archivos PNG / JPG / JPEG
              </div>';
            } elseif ($archivo_extension !== 'png' && $archivo_extension !== 'jpg' && $archivo_extension !== 'jpeg') {
                $mensaje = '<div class="alert alert-danger alert-dismissible">
                <h4><i class="glyphicon glyphicon-ok"></i> Error!</h4>
                Solo se permiten archivos PNG / JPG / JPEG
              </div>';
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
                    $thumb->save_jpg("", $archivo_new_name);
                    $archivo_new_name_w_extension = $archivo_new_name . ".jpeg";
                    rename($archivo_new_name_w_extension, "contenido/imagenes/organizadores/$archivo_new_name_w_extension");

                    $mensaje = '<div class="alert alert-success alert-dismissible">
                <h4><i class="fa fa-thumbs-up"></i> Exito!</h4>
                El registro se realizo correctamente.
              </div>';
                    $sw_imagen_uploaded = true;
                }
            }
        } else {
            $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> no se selecciono ninguna imagen.
</div>';
        }


        if ($sw_imagen_uploaded) {
            query("INSERT INTO cursos_organizadores("
                    . "imagen,"
                    . "nombre_extendido,"
                    . "titulo,"
                    . "titulo_identificador,"
                    . "codigo,"
                    . "id_departamento,"
                    . "nit,"
                    . "direccion,"
                    . "telefono,"
                    . "correo,"
                    . "descripcion,"
                    . "estado"
                    . ") VALUES("
                    . "'$archivo_new_name_w_extension',"
                    . "'$nombre_extendido',"
                    . "'$titulo',"
                    . "'$titulo_identificador',"
                    . "'$codigo',"
                    . "'$id_departamento',"
                    . "'$nit',"
                    . "'$direccion',"
                    . "'$telefono',"
                    . "'$correo',"
                    . "'$descripcion',"
                    . "'2'"
                    . " ) ");

            $sw_registro = true;

            /* envio de correo de notificacion de registro */
            $rqdc1 = query("SELECT nombre FROM departamentos WHERE id='$id_departamento' ");
            $rqdc2 = fetch($rqdc1);
            $nombre_departamento = $rqdc2['nombre'];
            $subject = utf8_encode('Registro de organizador - '.$___nombre_del_sitio.' : ' . $titulo);
            $body = '
        <div>
        <div style="background:#31b312;padding:4px 0px;text-align:center;border-radius: 5px;">
        <img src="'.$dominio.'contenido/alt/logotipo-v3.png" style="130px;"/>
        </div>
        <div style="background:#fff;padding:10px">
            <div style="border: 1px solid #dadada;padding:  20px;border-radius: 5px;line-height: 2;font-family: candara;font-size: 12pt;">
             Se hizo el registro de un nuevo organizador para '.$___nombre_del_sitio.' ' . $titulo . ' 
             <br/>
             <center><img src="'.$dominio.'contenido/imagenes/organizadores/' . $nombre_imagen . '" style="height:170px;"/></center>
             <br/>
             <b>DATOS PROPORCIONADOS</b>
             <br/>
             Nombre: ' . $titulo . '
             <br/>
             Nombre extendido: ' . $nombre_extendido . '
             <br/>
             Departamento: ' . $nombre_departamento . '
             <br/>
             Nit: ' . $nit . '
             <br/>
             Direcci&oacute;n: ' . $direccion . '
             <br/>
             Tel&eacute;fono: ' . $telefono . '
             <br/>
             Correo: ' . $correo . '
             <br/>
             Descripci&oacute;n: ' . $descripcion . '
             <br/>
             <br/>
             <br/>
            </div>
        </div>
        <div style="background:#31b312;padding:4px 0px;color:#FFF;text-align:center;border-radius: 5px;">
        Cursos y capacitaciones Bolivia
        </div>
        </div>';
            $email = 'info@nemabol.com';
            //*envio_email($email, $subject, $body);
            //*envio_email('brayan.desteco@gmail.com', $subject, $body);
        }
    } else {
        echo "<script>alert('Verifica que no eres un Robot');history.back();</script>";
    }
}
?>
<style>
    div.panel {
        margin-top: 0px;
        max-height: none !important;
        opacity: 1 !important;
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
                    <h3>Registro organizador</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        Llena el siguiente formulario para poder crear una cuenta de organizador en nuestra plataforma.
                    </p>
                </div>

                <?php echo $mensaje; ?>

                <?php
                if (!$sw_registro) {
                    //if (false) {
                    ?>

                    <div class="boxForm ajusta_form_contacto">
                        <div class="panel panel-default">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-lg-3 col-md-3 control-label text-primary">LOGO</label>
                                            <div class="col-lg-9 col-md-9">
                                                <input type="file" class="form-control form-cascade-control" name="imagen" value="" placeholder="Imagen del organizador..." required=""/>
                                                <br/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE EXTENDIDO</label>
                                            <div class="col-lg-9 col-md-9">
                                                <input type="text" class="form-control form-cascade-control" name="nombre_extendido" value="" required placeholder="Nombre del organizador..." autocomplete="off"/>
                                                <br/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE</label>
                                            <div class="col-lg-9 col-md-9">
                                                <input type="text" class="form-control form-cascade-control" name="titulo" value="" required placeholder="Nombre del organizador..." autocomplete="off"/>
                                                <br/>
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-lg-3 col-md-3 control-label text-primary">DEPARTAMENTO</label>
                                            <div class="col-lg-9 col-md-9">
                                                <select class="form-control form-cascade-control" name="id_departamento">
                                                    <?php
                                                    $rqd1 = query("SELECT * FROM departamentos WHERE tipo='1' ");
                                                    while ($rqd2 = fetch($rqd1)) {
                                                        ?>
                                                        <option value="<?php echo $rqd2['id']; ?>"><?php echo $rqd2['nombre']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                <br/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-lg-3 col-md-3 control-label text-primary">NIT</label>
                                            <div class="col-lg-9 col-md-9">
                                                <input type="text" class="form-control form-cascade-control" name="nit" value="" required placeholder="N&uacute;mero de nit del organizador..." autocomplete="off"/>
                                                <br/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-lg-3 col-md-3 control-label text-primary">DIRECCI&Oacute;N</label>
                                            <div class="col-lg-9 col-md-9">
                                                <input type="text" class="form-control form-cascade-control" name="direccion" value="" required placeholder="Direcci&oacute;n del organizador..." autocomplete="off"/>
                                                <br/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-lg-3 col-md-3 control-label text-primary">TEL&Eacute;FONO</label>
                                            <div class="col-lg-9 col-md-9">
                                                <input type="text" class="form-control form-cascade-control" name="telefono" value="" required placeholder="Tel&eacute;fono del organizador..." autocomplete="off"/>
                                                <br/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-lg-3 col-md-3 control-label text-primary">CORREO</label>
                                            <div class="col-lg-9 col-md-9">
                                                <input type="text" class="form-control form-cascade-control" name="correo" value="" required placeholder="Correo del organizador..." autocomplete="off"/>
                                                <br/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCI&Oacute;N</label>
                                            <div class="col-lg-9 col-md-9">
                                                <textarea class="form-control form-cascade-control" name="descripcion" rows="3" required placeholder="Descripci&oacute;n del organizador..."></textarea>
                                                <br/>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div style="width:300px;margin:auto;">
                                            <script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
                                            <div class="g-recaptcha" data-sitekey="6LcNOxgTAAAAAOIHv-MOGQ-9JMshusUgy6XTmJzD"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <input type="submit" name="agregar-organizador" class="btn btn-success btn-sm btn-animate-demo" value="FINALIZAR REGISTRO"/>
                                </div>
                            </form>
                        </div>

                    </div>



                    <?php
                } else {
                    ?>
                    <p>
                        FELICIDADES! Tu cuenta de organizador ha sido creada exitosamente, uno de nuestros operadores se comunicara con usted para hacer la verificaci&oacute;n correspondiente, posteriormente usted podr&aacute; hacer uso de nuestra plataforma. Para cualquier consulta puede comunicarse con el correo info@nemabol.com
                        <br/>
                    <hr/>
                    Gracias por unirte a <?php echo $___nombre_del_sitio; ?>
                    </p>

                    <?php
                }
                ?>


                <br />
                <br />


            </div>
            <div class="col-md-4">
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
