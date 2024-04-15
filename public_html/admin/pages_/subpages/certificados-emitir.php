<?php
//control de pagina
if (acceso_cod('adm-paquetes') == false) {
    echo "Acceso denegado!";
    exit;
}


$id_empresa = $get[2];

$mensaje = '';

//asignacion de paquete
if (post('emitir-certificadoXXXX')) {

    $asignacion = post('asignacion');
    $duracion = post('duracion');
    $id_paquete = post('id_paquete');
    $monto_pago = post('monto_pago');
    $persona_pago = post('persona_pago');
    $observaciones = utf8_decode(post('observaciones'));
    $fecha_registro = date("Y-m-d H:i:s");
    $id_administrador = administrador('id');
    $nombre_administrador = administrador('nombre');

    $rpq1 = query("SELECT titulo,caracteristicas FROM paquetes WHERE id='$id_paquete' ");
    $rpq2 = fetch($rpq1);
    $nombre_paquete = $rpq2['titulo'];
    $caracteristicas_paquete = str_replace('<li>', '<li style="list-style:none;"><img src="https://www.infosicoes.com/contenido/imagenes/images/icon-11.png"/> ', $rpq2['caracteristicas']);

    $rpus1 = query("SELECT id_paquete,correo_empresa,correo_representante,nombre_empresa,nombre_representante,ap_paterno_representante FROM empresas WHERE id='$id_empresa' ");
    $rpus2 = fetch($rpus1);
    $correo_empresa = $rpus2['correo_empresa'];
    $correo_representante = $rpus2['correo_representante'];
    if ($rpus2['nombre_empresa'] !== 'consultor') {
        $nomempresa = $rpus2['nombre_empresa'];
    } else {
        $nomempresa = $rpus2['nombre_representante'] . ' ' . $rpus2['ap_paterno_representante'];
    }

    $rrpq1 = query("SELECT titulo FROM paquetes WHERE id='" . $rpus2['id_paquete'] . "' ");
    $rrpq2 = fetch($rrpq1);
    $nombre_paquete_actual = $rrpq2['titulo'];



    $mensaje_correo = "<div style='background:#c8c8c8;width:100%;padding:0px;margin:0px;padding-top:30px;padding-bottom:30px;'>
    <div style='background:#FFF;width:80%;margin:auto;padding:30px;border:1px solid #56829c;border-radius:5px;color:#333;'><h2 style='text-align:center;background:#003e54;color:#FFF;border-radius:5px;padding:5px;'>InfoSICOES Asignación de paquete -PAQUETE- InfoSICOES</h2>
        <center><a href='https://www.infosicoes.com/'><img width='230px' src='https://www.infosicoes.com/contenido/imagenes/images/logo_infosicoes.png'/></a></center>
        <p style='font-style:italic;font-family:arial;font-size:10.5pt;line-height:2;'>
            Saludos -NOM-EMPRESA-
            <br/>
            Te informamos que se te asigno el paquete de servicios -PAQUETE- de InfoSICOES. este cambio corre a partir de -FECHAINICIO- y es vigente durante -DURACION-, tiempo en el cual podras disfrutar de los siguientes servicios de InfoSICOES:
        </p>
        -CARACTERISTICAS-
        <p style='font-style:italic;font-family:arial;font-size:10.5pt;line-height:2;'>
        Nuestra aplicaci&oacute;n le permite estar al tanto de las nuevas licitaciones que sean de su inter&eacute;s y poder monitorearlas constantemente, se te enviaran correos con avisos de las nuevas contrataciones 
        publicadas en el SICOES, que son referentes a los servicios que ofrece tu empresa y as&iacute; no perder la oportunidad de postular a esa posible contrataci&oacute;n de tu Empresa.<br/>Nuestra Misi&oacute;n es ayudarle con las adjudicaciones de sus futuras licitaciones, pronto podra recibir 
        notificaciones de empresas privadas que requieren sus servicios, trabajar con una entidad financiera que ayude con la emisi&oacute;n de Boletas de seriedad de propuestas o Boletas de garant&iacute;a de forma r&aacute;pida y c&oacute;moda, pronto sacaremos nuevas herramientas que te 
        ayudaran de mayor manera en la adjudicaci&oacute;n de una licitaci&oacute;n o una compra menor, ya que tenemos gran aceptaci&oacute;n de muchas entidades de gobierno.
        <br/>
        <br/>
        <br/>
        Esperamos que nuestra aplicaci&oacute;n te sea de utilidad.<br/><br/>Gracias por tu atención.<br/></p>
        <hr/>
        <br/>
        <div style='margin:auto;width:80%;font-size:9.5pt;color:#333;line-height:2;'>
            <i>-ADMINISTRADOR-</i>
            <br/>
            contacto@infosicoes.com
            <br/>
            Calle Loayza #250 Edif. Castilla
            Piso 4 Oficina 410 - 79517817
            <br/>
            (591-2) 2118783 L&iacute;nea InfoSICOES
        </div>
    </div>
</div>";

    $asunto = "Asignación de paquete $nombre_paquete - InfoSICOES";
    $cabeceras = 'From:' . 'InfoSICOES@infosicoes.com' . "\r\n" .
            'Reply-To:' . 'contacto@infosicoes.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion() .
            'Return-Path:' . 'InfoSICOES@infosicoes.com' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' . "\r\n";



    if ($asignacion == '1') {

        $fecha_inicio = date("Y-m-d");
        $nuevafecha = strtotime('+' . $duracion . ' month', strtotime($fecha_inicio));
        $fecha_final = date('Y-m-d', $nuevafecha);

        query("INSERT INTO paquetes_asignados(
              id_usuario, 
              id_paquete, 
              duracion, 
              fecha_inicio,
              fecha_final,
              monto_pago, 
              persona_pago, 
              observaciones,
              id_administrador,
              fecha_registro
              ) VALUES (
              '$id_empresa',
              '$id_paquete',
              '$duracion',
              '$fecha_inicio',
              '$fecha_final',
              '$monto_pago',
              '$persona_pago',
              '$observaciones',
              '$id_administrador',
              '$fecha_registro'
              )");
        query("UPDATE empresas SET 
              id_paquete='$id_paquete',
              paquete_inicio='$fecha_inicio',
              paquete_duracion='$duracion' 
              WHERE id='$id_empresa' ");
        movimiento('ASIGNACION DE PAQUETE [duracion: ' . $duracion . ' meses]  [paquete: ' . $nombre_paquete . ']', 'asignacion-paquete', 'usuario', $id_empresa);
        movimiento('Cambio de paquete [' . $nombre_paquete_actual . ' -> ' . $nombre_paquete . '][' . $duracion . ' meses]', 'cambio-paquete', 'usuario', $user['id']);

        $text_mail = "Asignacion de paquete: $nombre_paquete a $nomempresa, asignacion dada por " . administrador('nombre') . " en " . date("d-m-Y H:i");
        $text_mail_2 = " | Duracion: $duracion meses, fecha de inicio: $fecha_inicio, monto de pago: $monto_pago, Persona ejecutante de pago: $persona_pago, observaciones: $observaciones ";
        mail("activacion@infosicoes.com", $text_mail, $text_mail . $text_mail_2);
        mail("brayan.desteco@gmail.com", $text_mail, $text_mail . $text_mail_2);

        $mensaje .= '<br/><br/><h2><i class="fa fa-check-circle-o" style="color:green;"></i> Se ha asignado el paquete correctamente!</h2>';
        $mensaje .= '<br/><p>Se le enviar&aacute; un correo electr&oacute;nico al usuario para constatarle de la agregaci&oacute;n del nuevo paquete.</p><br/><br/>';
    } else {

        $fecha_inicio = 'la finalizacion de tu actual paquete';

        query("INSERT INTO paquetes_en_espera(
             id_usuario, 
             id_paquete, 
             duracion, 
             monto_pago, 
             persona_pago, 
             observaciones, 
             id_administrador, 
             fecha_registro
             ) VALUES (
             '$id_empresa',
             '$id_paquete',
             '$duracion',
             '$monto_pago',
             '$persona_pago',
             '$observaciones',
             '$id_administrador',
             '$fecha_registro'
             )");

        movimiento('ASIGNACION DE PAQUETE -> Paquetes de Espera [duracion: ' . $duracion . ' meses]  [paquete: ' . $nombre_paquete . ']', 'asignacion-paquete', 'usuario', $id_empresa);

        $mensaje .= '<br/><br/><h2><i class="fa fa-check-circle-o" style="color:green;"></i> Se ha agregado el paquete correctamente!</h2>';
        $mensaje .= '<br/><p>Se le enviar&aacute; un correo electr&oacute;nico al usuario para constatarle de la agregaci&oacute;n del nuevo paquete.<br/>El paquete se agrego a la lista de paquetes de espera del usuario.</p><br/><br/>';
    }

    $busc = array('-PAQUETE-', '-FECHAINICIO-', '-DURACION-', '-NOM-EMPRESA-', '-CARACTERISTICAS-', '-ADMINISTRADOR-');
    $remm = array($nombre_paquete, $fecha_inicio, $duracion . ' Meses', $nomempresa, $caracteristicas_paquete, $nombre_administrador);
    $msj = str_replace($busc, $remm, $mensaje_correo);

    mail($correo_empresa, $asunto, $msj, $cabeceras);
    mail('infosicoes@gmail.com', $asunto, $msj, $cabeceras);
    mail('esardon@desteco.net', $asunto, $msj, $cabeceras);
    mail('contacto@infosicoes.com', $asunto, $msj, $cabeceras);
    mail('cursos@sicoes.com.bo', $asunto, $msj, $cabeceras);
    mail('brayan.desteco@gmail.com', $asunto, $msj, $cabeceras);

    if ($correo_empresa !== $correo_representante) {
        mail($correo_representante, $asunto, $msj, $cabeceras);
    }
}

$resultado_datos_empresa = query("SELECT * FROM empresas WHERE id='$id_empresa'");
$datos_empresa = fetch($resultado_datos_empresa);

//datos de paquete
$rp1 = query("SELECT * FROM paquetes WHERE id='" . $datos_empresa['id_paquete'] . "' ");
$rp2 = fetch($rp1);

$cantidad_palabras = $rp2['cnt_palabras_clave'];
$cnt_cuentas = $rp2['cnt_cuentas'];
?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>admin.php">Panel Principal</a></li>
            <li><a href="empresas-listar.adm">empresas</a></li>
            <li class="active">Administraci&oacute;n de paquetes</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <form action="" method="post">
                <input type="text" name="buscar" class="form-control form-cascade-control " size="20" placeholder="Buscar en el Sitio">
                <span class="input-icon fui-search"></span>
            </form>
        </div>
        <h3 class="page-header"> Emisi&oacute;n de certificado <?php //echo $datos_empresa['nombre_empresa'];   ?><a href="https://www.infosicoes.com/empresas-listar/<?php echo $datos_empresa['clase']; ?>/todos/q=<?php echo $datos_empresa['correo_empresa']; ?>/vista/1.adm"><?php echo $datos_empresa['nombre_empresa']; ?></a> <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Emisi&oacute;n de certificado
            </p>
        </blockquote>
    </div>
</div>

<div class="formulario_edicion_datos_empresa">

    <div class="tabla_panel_uno" id="box_reg_empresa">

        <h3>Asignar paquete a la empresa: <?php echo $datos_empresa['nombre_empresa']; ?></h3>
        <div class="" style="width:90%;margin:auto;text-align:left !important;">
            <?php
            if (strlen($mensaje) > 5) {
                echo $mensaje;
            } elseif ($datos_empresa['estado'] == '2') {
                echo "<h4>Empresa baneada del sistema!</h4>";
            } else {
                ?>
                <div class="" style="padding:2px 10px;">
                    <form action="" method="post">
                        <div class="panel-body">
                            <h4 class="page-header" style="margin-top:0px;padding-top:0px;"> Ingresa a continuaci&oacute;n los datos del participante a recibir el certificado. </h4>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">
                                            Curso
                                        </label>
                                        <div class="col-sm-10">
                                            <select name="id_curso" class="form-control form-cascade-control">
                                                <?php
                                                if ($datos_empresa['clase'] == 'consultor') {
                                                    $ids_paquetes = "9,10,11,12";
                                                } else {
                                                    $ids_paquetes = "1,2,3,4,5,6,7,8";
                                                }

                                                $r_paquete1 = query("SELECT id,titulo,fecha FROM cursos ORDER BY fecha DESC limit 15 ");
                                                while ($r_paquete2 = fetch($r_paquete1)) {
                                                    echo '<option value="' . $r_paquete2['id'] . '" style="padding:4px;"> ' . date("d / M", strtotime($r_paquete2['fecha'])) . ' - ' . $r_paquete2['titulo'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">
                                            Nombre del participante:
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" name="nombre_participante" value="" class="form-control form-cascade-control hasDatepicker" data-color-format="rgba">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">
                                            Correo electr&oacute;nico:
                                        </label>
                                        <div class="col-sm-4">
                                            <input type="text" name="correo_participante" value="" class="form-control form-cascade-control hasDatepicker" data-color-format="rgba">
                                        </div>
                                        <label for="inputEmail3" class="col-sm-2 control-label">
                                            Celular:
                                        </label>
                                        <div class="col-sm-4">
                                            <input type="text" name="celular_participante" value="" class="form-control form-cascade-control hasDatepicker" data-color-format="rgba">
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">
                                            Observaciones
                                        </label>
                                        <div class="col-sm-10">
                                            <textarea  name="observaciones" style="height:70px;" class="form-control form-cascade-control hasDatepicker" data-color-format="rgba"></textarea>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="panel-footer">
                                <div class="row">
                                    <div class="col-sm-12 col-sm-offset-4">
                                        <input type="submit" name="emitir-certificado" class="btn btn-success" value="EMITIR CERTIFICADO">
                                        <button type="button" class="btn btn-danger" onclick="location.href = 'admin.php';">CANCELAR</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>

        <h3>ULTIMAS EMISIONES DE CERTIFICADOS</h3>

        <div class="panel-body">
            <table class="table users-table table-condensed table-hover">
                <thead>
                    <tr>
                        <th class="visible-lg">#</th>
                        <th class="visible-lg">Certificante</th>
                        <th class="visible-lg">ID de certificado</th>
                        <th class="visible-lg">Certificado</th>                            
                    </tr>
                </thead>             
                <tbody>
                    <tr>
                        <td class="visible-lg">
                            1
                        </td>
                        <td class="visible-lg">
                            LUCIA ESTRADA ARTEAGA
                        </td>
                        <td class="visible-lg">
                            1100121515141
                        </td>
                        <td class="visible-lg">     
                            <a href="http://www.infosicoes.com/contenido/paginas/procesos/pdfs/certificado-2.php" target="_blank">Imprimir certificado</a>         
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12">
                    <ul class="pagination">

                        <li><a href="empresas-paquetes/<?php echo $id_empresa; ?>/1.adm">Primero</a></li>                           
                        <li class="active"><a href="productos/<?php echo $id_empresa; ?>/1.adm">1</a></li>
                        <li><a href="empresas-paquetes/<?php echo $id_empresa; ?>/1.adm">Ultimo</a></li>
                    </ul>								
                </div><!-- /col-md-12 -->	
            </div>

        </div>

    </div>
</div>

<div class="clear">.</div>
<br/>
<br/>
<div class="clear">.</div>

<script language="Javascript">
    function cambiar_paquete(id) {

        var doc = document.getElementById('paquete-box');
        var fecha_inicio = document.getElementById('fecha_inicio').value;
        var duracion = document.getElementById('duracion').value;
        var valor = document.getElementById('paquete-value').value;
        var ajax = new XMLHttpRequest();
        ajax.open("POST", "pages/ajax/ajax.cambiar_paquete.php?fecha_inicio=" + fecha_inicio + "&duracion=" + duracion, true);
        ajax.onreadystatechange = function() {

            if (ajax.readyState === 4 && ajax.status === 200) {

                doc.innerHTML = "<td colspan='9' style='background:#EEE;font-weight:bold;'>" + ajax.responseText + "</td>";

            }

        };

        var variables = "id_valor=" + id + "_separator_" + valor;

        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.send(variables);
    }

</script>







