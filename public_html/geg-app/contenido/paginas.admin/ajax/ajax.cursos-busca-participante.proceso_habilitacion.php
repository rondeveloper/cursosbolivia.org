<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* recepcion de datos POST */
$id_participante = post('id_participante');
$proceso = post('proceso');

/* mensaje */
$mensaje = '';

/* acciones : temporalizar */
if ($proceso == 'temporalizar') {
    if (acceso_cod('adm-cursos-estado')) {
        $rqdc_aux1 = query("SELECT id_curso,(select titulo_identificador from cursos where id=cursos_participantes.id_curso)titulo_identificador FROM cursos_participantes WHERE id='$id_participante' ");
        $rqdc_aux2 = mysql_fetch_array($rqdc_aux1);
        $id_curso_aux = $rqdc_aux2['id_curso'];
        $titulo_identificador_curso_aux = $rqdc_aux2['titulo_identificador'];

        query("UPDATE cursos SET estado='2',titulo_identificador='$titulo_identificador_curso_aux-tmp' WHERE id='$id_curso_aux' ORDER BY id DESC limit 1 ");
        logcursos('Cambio de estado [TEMPORAL]', 'curso-edicion', 'curso', $id_curso_aux);

        $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> registro modificado correctamente.
</div>
';
    } else {
        $mensaje = '<div class="alert alert-info">
  <strong>Aviso!</strong> usted no tiene permisos.
</div>
';
    }
} elseif ($proceso == 'desactivar') {
    if (acceso_cod('adm-cursos-estado')) {
        $rqdc_aux1 = query("SELECT id_curso,(select titulo_identificador from cursos where id=cursos_participantes.id_curso)titulo_identificador FROM cursos_participantes WHERE id='$id_participante' ");
        $rqdc_aux2 = mysql_fetch_array($rqdc_aux1);
        $id_curso_aux = $rqdc_aux2['id_curso'];
        $titulo_identificador_curso_aux = str_replace('-tmp', '', $rqdc_aux2['titulo_identificador']);

        query("UPDATE cursos SET estado='0',titulo_identificador='$titulo_identificador_curso_aux-tmp' WHERE id='$id_curso_aux' ORDER BY id DESC limit 1 ");
        logcursos('Cambio de estado [DES-ACTIVADO]', 'curso-edicion', 'curso', $id_curso_aux);

        $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> registro modificado correctamente.
</div>
';
    } else {
        $mensaje = '<div class="alert alert-info">
  <strong>Aviso!</strong> usted no tiene permisos.
</div>
';
    }
} elseif ($proceso == 'habilitar') {
    query("UPDATE cursos_participantes SET estado='1' WHERE id='$id_participante' LIMIT 1 ");
    logcursos('Habilitacion de participante', 'partipante-deshabilitacion', 'participante', $id_participante);

    $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> registro modificado correctamente.
</div>
';
}


/* participante */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ");
$participante = mysql_fetch_array($resultado1);

/* datos de registro */
$rqrp1 = query("SELECT id,codigo,fecha_registro,sw_pago_enviado,celular_contacto,correo_contacto,metodo_de_pago,id_modo_de_registro,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_cobro_khipu,id_administrador FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
$data_registro = mysql_fetch_array($rqrp1);
$id_proceso_de_registro = $data_registro['id'];
$codigo_de_registro = $data_registro['codigo'];
$fecha_de_registro = $data_registro['fecha_registro'];
$celular_de_registro = $data_registro['celular_contacto'];
$correo_de_registro = $data_registro['correo_contacto'];
$nro_participantes_de_registro = $data_registro['cnt_participantes'];
$id_modo_de_registro = $data_registro['id_modo_de_registro'];
$id_emision_factura = $data_registro['id_emision_factura'];
$sw_pago_enviado = $data_registro['sw_pago_enviado'];
$metodo_de_pago = $data_registro['metodo_de_pago'];
$monto_de_pago = $data_registro['monto_deposito'];
$imagen_de_deposito = $data_registro['imagen_deposito'];
$razon_social_de_registro = $data_registro['razon_social'];
$nit_de_registro = $data_registro['nit'];


/* curso */
$rqdc1 = query("SELECT *,(select nombre from ciudades where id=c.id_ciudad)departamento FROM cursos c WHERE c.id='" . $participante['id_curso'] . "' LIMIT 1 ");
$curso = mysql_fetch_array($rqdc1);

/* certificados */
$id_certificado_1 = $curso['id_certificado'];
$id_certificado_2 = $curso['id_certificado_2'];

$id_emision_certificado_1 = $participante['id_emision_certificado'];
$id_emision_certificado_2 = $participante['id_emision_certificado_2'];

/* muestra mensaje */
echo $mensaje;
?>

<h4 class="btn btn-primary btn-block active">DATOS DE CURSO</h4>

<hr/>
<div class="row">
    <div class="col-md-8">
        CURSO: <?php echo $curso['titulo']; ?>
        <br/>
        CIUDAD: <?php echo $curso['departamento']; ?>
        <br/>
        FECHA: <?php echo date("d / M / Y", strtotime($curso['fecha'])); ?>
    </div>
    <div class="col-md-4">
        <?php
        if (file_exists("../../imagenes/paginas/" . $curso['imagen'])) {
            $url_imagen = "contenido/imagenes/paginas/" . $curso['imagen'];
        } else {
            $url_imagen = "https://www.infosicoes.com/contenido/imagenes/paginas/" . $curso['imagen'];
        }
        ?>
        <img src="<?php echo $url_imagen; ?>" style="width:100%;"/>
    </div>
</div>
<hr/>

<h4 class="btn btn-primary btn-block active">ESTADO DEL CURSO</h4>

<hr/>
<div class="row">
    <div class="col-md-6">
        <?php
        $sw_proceso_activado = false;
        if ($curso['estado'] == '1') {
            echo "<b class='btn btn-success'>ACTIVADO</b>";
            $sw_proceso_activado = true;
        } elseif ($curso['estado'] == '2') {
            echo "<b class='btn btn-danger'>TEMPORAL</b>";
            $sw_proceso_activado = true;
        } else {
            echo "<b class='btn btn-default'>DESACTIVADO</b>";
        }
        ?>
    </div>
    <div class="col-md-6 text-center">
        <?php
        if ((!$sw_proceso_activado) && acceso_cod('adm-cursos-estado')) {
            ?>
            <i>CAMBIAR ESTADO A TEMPORAL:</i>
            <br/><br/>
            <b class='btn btn-info btn-xs' onclick="proceso_habilitacion('<?php echo $id_participante; ?>', 'temporalizar');">CAMBIAR A TEMPORAL</b>
            <?php
        } elseif ($curso['estado'] == '2') {
            ?>
            <i>CAMBIAR ESTADO A DESACTIVADO:</i>
            <br/><br/>
            <b class='btn btn-danger active btn-xs' onclick="proceso_habilitacion('<?php echo $id_participante; ?>', 'desactivar');">CAMBIAR A DESACTIVADO</b>
            <?php
        }
        ?>
    </div>
</div>
<hr/>

<h4 class="btn btn-primary btn-block active">
    PARTICIPANTE
</h4>

<hr/>
<div class="row">
    <div class="col-md-12 text-left text-center" style="line-height: 0.2;">
        <h3 class="text-center" style="font-size: 20pt;
            text-transform: uppercase;
            color: #00789f;font-weight: bold;">
            <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </h3>
        <b style="font-size: 17pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-md-6">
        CORREO: <?php echo $participante['correo']; ?>
        <br/>
        CELULAR: <?php echo $participante['celular']; ?>
        <br/>
        <?php
        if ($participante['estado'] == '1') {
            echo "<b>HABILITADO</b>";
            $sw_participante_habilitado = true;
        } else {
            $sw_participante_habilitado = false;
            echo "<b>DESHABILITADO</b>";
        }
        ?>
    </div>
    <div class="col-md-6 text-center">
        <?php
        if ((!$sw_participante_habilitado) && ($sw_proceso_activado)) {
            ?>
            <i>Habilitaci&oacute;n de participante:</i>
            <br/><br/>
            <b class='btn btn-success active btn-sm' onclick="proceso_habilitacion('<?php echo $id_participante; ?>', 'habilitar');">HABILITAR</b>
            <?php
        }
        ?>
    </div>
</div>
<hr/>

<h4 class="btn btn-primary btn-block active">
    REGISTRO
</h4>

<hr/>
<div class="row">
    <div class="col-md-12">
        <b>Fecha de registro:</b> &nbsp; <?php echo $fecha_de_registro; ?>
        <br/>
        <!--                                                <b>CELULAR CONTACTO:</b> &nbsp; <?php echo $celular_de_registro; ?>
                                                        <br/>
                                                        <b>CORREO CONTACTO:</b> &nbsp; <?php echo $correo_de_registro; ?>
                                                        <br/>-->
        <b>Registro:</b> &nbsp; <?php echo $codigo_de_registro; ?>
        <br/>
        <?php
        if ($id_administrador_registro <> 0) {
            $rqda1 = query("SELECT nombre FROM administradores WHERE id='$id_administrador_registro' ");
            $rqda2 = mysql_fetch_array($rqda1);
            ?>
            <b>Administrador:</b> &nbsp; <?php echo $rqda2['nombre']; ?>
            <br/>
            <?php
        }
        ?>
        <b>Nro. de participantes:</b> &nbsp; <?php echo $nro_participantes_de_registro; ?>
        <br/>
        <?php
        if ($metodo_de_pago == 'NO-DEFINIDO') {
            ?>
            <b>Metodo de pago:</b> &nbsp; NO DEFINIDO
            <br/>
            <b>Monto pagado:</b> &nbsp; <?php echo (int) $monto_de_pago; ?> BS.
            <br/>
            <?php
        } elseif ($metodo_de_pago == 'deposito') {
            ?>
            <b>Metodo de pago:</b> &nbsp; DEPOSITO BANCARIO
            <br/>
            <?php
            if ($sw_pago_enviado == '0') {
                echo "PAGO NO ENVIADO";
                echo "<br/>";
            } else {
                $dir_img_deposito = '../../imagenes/depositos/' . $imagen_de_deposito;
                $aux_url_domain = '';
                if (!file_exists($dir_img_deposito)) {
                    $aux_url_domain = 'https://www.infosicoes.com/';
                    echo "<b>Registro efectuado desde:</b> <b style='color:#1b6596;'>INFOSICOES</b>";
                    echo "<br/>";
                }
                ?>
                <b>Monto pagado:</b> &nbsp; <?php echo (int)$monto_de_pago; ?> BS.
                <br/>
                <b>Imagen del deposito:</b> &nbsp; <a href="<?php echo $aux_url_domain; ?>depositos/<?php echo $imagen_de_deposito; ?>.img" target="_blank"><?php echo $imagen_de_deposito; ?></a>
                <br/>
                <br/>
                <img src="<?php echo $aux_url_domain; ?>depositos/<?php echo $imagen_de_deposito; ?>.size=3.img" style="width:100%;border:1px solid #DDD;padding:1px;">
                <?php
            }
        } elseif ($metodo_de_pago == 'tarjeta') {
            $id_cobro_khipu = $data_registro['id_cobro_khipu'];
            $rqck1 = query("SELECT payment_id,estado FROM khipu_cobros WHERE id='$id_cobro_khipu' ORDER BY id DESC limit 1 ");
            $rqck2 = mysql_fetch_array($rqck1);
            $enlace_khipu = "https://khipu.com/payment/info/" . $rqck2['payment_id'];
            ?>
            <b>Metodo de pago:</b> &nbsp; KHIPU
            <br/>
            <b>Monto ingresado:</b> &nbsp; <?php echo $monto_de_pago; ?>
            <br/>
            <b>Enlace de transferencia:</b> &nbsp; <a href='<?php echo $enlace_khipu; ?>' target='_blank'><?php echo $enlace_khipu; ?></a>
            <br/>
            <?php
            if ($rqck2['estado'] == '0') {
                ?>
                <b>Estado del pago:</b> &nbsp; <b style='color:red;'>TRANSFERENCIA NO REALIZADA</b>
                <?php
            } elseif ($rqck2['estado'] == '1') {
                ?>
                <b>Estado del pago:</b> &nbsp; <b style='color:green;'>TRANSFERENCIA EXITOSA</b>
                <?php
            } else {
                ?>
                <b>Estado del pago:</b> &nbsp; <b style='color:red;'>DATOS NO ENCONTRADOS</b>
                <?php
            }
            ?>
            <?php
        } else {
            ?>
            <b>Metodo de pago:</b> &nbsp; PAGO EN OFICINA
            <br/>
            <b>Monto pagado:</b> &nbsp; <?php echo (int)$monto_de_pago; ?> BS.
            <?php
        }
        ?>
    </div>
</div>
<hr/>

<h4 class="btn btn-primary btn-block active">
    CERTIFICADOS
</h4>

<hr/>

<!-- DIV CONTENT AJAX :: EMITE CERTIFICADO P1 -->
<div id="ajaxloading-emite_certificado_p1"></div>
<div id="ajaxbox-emite_certificado_p1">

    <div class="row">
        <div class="col-md-8">
            <?php
            if ($id_certificado_1 == '0') {
                echo "<b>El curso no tiene 1er certificado asociado</b>";
            } else {
                $rqdc1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado_1' ORDER BY id DESC limit 1 ");
                $rqdc2 = mysql_fetch_array($rqdc1);

                echo "1er CERTIFICADO: " . $rqdc2['codigo'];

                echo "<br/>";

                echo "TEXTO: " . $rqdc2['texto_qr'];
            }
            ?>
        </div>
        <div class="col-md-4">
            <?php
            if ($id_emision_certificado_1 == '0') {
                echo "<b>NO EMITIDO</b>";
                if ($sw_proceso_activado && $id_certificado_1 !== '0' && $sw_participante_habilitado) {
                    ?>
                    <br/>
                    <span id='box-modal_emision_certificado-button-<?php echo $participante['id']; ?>'>
                        <a onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 1);
                                $('#id_certificado').val('<?php echo $id_certificado_1; ?>');" class="btn btn-xs btn-primary active">EMITIR CERT. 1</a>
                    </span>
                    <?php
                }
            } else {
                ?>
                <b>EMITIDO <?php echo $id_emision_certificado_1; ?></b>
                <br/>
                <a onclick="imprimir_certificado_individual('<?php echo $id_emision_certificado_1; ?>');" class="btn btn-xs btn-warning active">IMPRIMIR</a>
                <?php
            }
            ?>
        </div>
    </div>



    <hr/>

    <div class="row">
        <div class="col-md-8">
            <?php
            if ($id_certificado_2 == '0') {
                echo "<b>El curso no tiene 2do certificado asociado</b>";
            } else {
                $rqdc1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado_2' ORDER BY id DESC limit 1 ");
                $rqdc2 = mysql_fetch_array($rqdc1);

                echo "2do CERTIFICADO: " . $rqdc2['codigo'];

                echo "<br/>";

                echo "TEXTO: " . $rqdc2['texto_qr'];
            }
            ?>
        </div>
        <div class="col-md-4 tex-center">
            <?php
            if ($id_emision_certificado_2 == '0') {
                echo "<b>NO EMITIDO</b>";
                if ($sw_proceso_activado && $id_certificado_2 !== '0' && $sw_participante_habilitado) {
                    ?>
                    <br/>
                    <span id='box-modal_emision_certificado-button-2-<?php echo $participante['id']; ?>'>
                        <a onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 2);
                                $('#id_certificado').val('<?php echo $id_certificado_2; ?>');" class="btn btn-xs btn-primary active">EMITIR CERT. 2</a>
                    </span>
                    <?php
                    if ($id_certificado_1 !== '0' && $sw_participante_habilitado && $id_emision_certificado_1 == '0' && $id_emision_certificado_2 == '0') {
                        ?>
                        <hr/>
                        <span id='box-modal_emision_certificado-button-12-<?php echo $participante['id']; ?>'>
                            <a onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 12);
                                    $('#id_certificado').val('<?php echo "$id_certificado_1|AND|$id_certificado_2"; ?>');" class="btn btn-xs btn-primary active">EMITIR CERT. 1 y 2</a>
                        </span>
                        <?php
                    }
                }
            } else {
                ?>
                <b>EMITIDO <?php echo $id_emision_certificado_2; ?></b>
                <br/>
                <a onclick="imprimir_certificado_individual('<?php echo $id_emision_certificado_2; ?>');" class="btn btn-xs btn-warning active">IMPRIMIR</a>
                <?php
            }
            ?>
        </div>
    </div>


</div>

<style>
    #ajaxbox-emite_certificado_p1 img{
        display: none;
    }
</style>


<hr/>

<input type='hidden' id='receptor_de_certificado' value='<?php echo $participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']; ?>'/>
<input type='hidden' id='id_certificado' value='<?php echo $id_certificado_1; ?>'/>
<input type='hidden' id='id_curso' value='<?php echo $curso['id']; ?>'/>
