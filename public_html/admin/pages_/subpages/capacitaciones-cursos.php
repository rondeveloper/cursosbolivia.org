<?php
$mensaje = "";

//id de capacitacion
$id_capacitacion = 0;
if (isset($get[2])) {
    $id_capacitacion = (int) $get[2];
}

//vista
$vista = 1;
if (isset($get[3])) {
    $vista = (int) $get[3];
}

//limitado de registros
$registros_a_mostrar = 150;
$start = abs($vista - 1) * $registros_a_mostrar;

$sw_selec = false;

/* sw de habilitacion de procesos */
$rqvhc1 = query("SELECT estado FROM capacitaciones WHERE id='$id_capacitacion' ORDER BY id DESC limit 1 ");
$rqvhc2 = fetch($rqvhc1);
if ($rqvhc2['estado'] == '1' || $rqvhc2['estado'] == '2') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}


if (isset_post('buscarr') || isset($get[5])) {
    $sw_busqueda = true;
    if (isset_post('buscar')) {
        $buscar = post('buscar');
    } else {
        $buscar = $get[5];
    }
} else {
    $sw_busqueda = false;
}


//agregado de curso
if (isset_post('agregar-curso')) {

    $titulo = post('titulo');
    $horario = post('horario');
    
    if ((strlen($titulo) > 3)) {

        /* verificacion de existencia */
        $rqpcv1 = query("SELECT id FROM capacitaciones_cursos WHERE titulo_curso='$titulo' AND horario='$horario' AND id_capacitacion='$id_capacitacion' ORDER BY id DESC limit 1 ");
        if (num_rows($rqpcv1) > 0) {
            $mensaje .= '<div class="alert alert-alert">
  <strong>Alerta!</strong> nombre ya existe como participante en este capacitacion.
</div>';
        } else {
            $fecha_registro = date("Y-m-d H:i:s");
            query("INSERT INTO capacitaciones_cursos(
                      id_capacitacion, 
                      titulo_curso,
                      horario, 
                      fecha_registro, 
                      estado
                      ) VALUES (
                      '$id_capacitacion',
                      '$titulo',
                      '$horario',
                      '$fecha_registro',
                      '1'
                      )");
            movimiento('Agregado de curso a capacitacion [capacitacion: ' . $id_capacitacion . '] ', 'agregado-curso-capacitacion', 'capacitacion', $id_capacitacion);
            $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> curso agregado exitosamente.
</div>';
        }
    }
}

/* edicion de participante */
if (isset_post('editar-participante')) {

    $prefijo = post('prefijo');
    $nombres = ucfirst(trim(post('nombres')));
    $apellidos = ucfirst(trim(post('apellidos')));

    $celular = post('celular');
    $correo = post('correo');

    $razon_social = post('razon_social');
    $nit = post('nit');
    $monto_pago = post('monto_pago');

    $id_capacitacion = post('id_capacitacion');
    $id_participante = post('id_participante');

    /* edicion de datos de participante */
    query("UPDATE capacitaciones_participantes SET 
            prefijo='$prefijo',
            nombres='$nombres',
            apellidos='$apellidos',
            celular='$celular',
            correo='$correo'
             WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

    /* edicion de datos de registro */
    $rqdr1 = query("SELECT id_proceso_registro FROM capacitaciones_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdr2 = fetch($rqdr1);
    $id_proceso_registro = $rqdr2['id_proceso_registro'];

    query("UPDATE capacitaciones_proceso_registro SET 
            razon_social='$razon_social',
            nit='$nit',
            monto_deposito='$monto_pago' 
            WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> Participante editado correctamente.
</div>';
}




$resultado1 = query("SELECT * FROM capacitaciones_cursos WHERE id_capacitacion='$id_capacitacion' AND estado='1' ORDER BY id DESC ");

//datos del capacitacion
$rqc1 = query("SELECT titulo,fecha,imagen,id_certificado,id_certificado_2 FROM capacitaciones c WHERE id='$id_capacitacion' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$nombre_del_capacitacion = $rqc2['titulo'];
$fecha_del_capacitacion = $rqc2['fecha'];
$codigo_de_certificado_del_capacitacion = "none";
$id_certificado_capacitacion = $rqc2['id_certificado'];
$id_certificado_2_capacitacion = $rqc2['id_certificado_2'];
if ($rqc2['imagen'] !== '') {
    $url_imagen_del_capacitacion = "https://www.infosicoes.com/paginas/" . $rqc2['imagen'] . ".size=4.img";
} else {
    $url_imagen_del_capacitacion = "https://www.infosicoes.com/images/banner-capacitaciones.png.size=4.img";
}


$cnt = num_rows($resultado1);
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="capacitaciones-listar.adm">Capacitaciones</a></li>
            <li class="active">Cursos</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <form action="" method="post">
                <input type="text" name="buscar" class="form-control form-cascade-control " size="20" placeholder="Buscar en el Sitio">
                <span class="input-icon fui-search"></span>
            </form>
        </div>
        <h3 class="page-header"> Cursos del Ciclo de Capacitaci&oacute;n <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de cursos del Ciclo de Capacitaci&oacute;n
            </p>
        </blockquote>
    </div>
</div>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <?php
            if ($sw_habilitacion_procesos) {
                ?>

                <div class="panel-body">
                    <table class="table" style="background:#EEE;border-radius:5px;">
                        <form action="" method="post">
                            <tr>
                                <td><input type="text" name="titulo" class="form-control" placeholder="Titulo del curso..." /></td>
                                <td><input type="text" name="horario" class="form-control" placeholder="Horarios..." /></td>
                                <td><input type="submit" name="agregar-curso" class="btn btn-success active" value="AGREGAR CURSO"/></td>
                            </tr>
                        </form>
                    </table>
                </div>

                <?php
            }
            ?>

            <h3>
                <i class='btn btn-success active'><?php echo date("d  M  y", strtotime($fecha_del_capacitacion)); ?></i> | 
                <?php echo $nombre_del_capacitacion; ?>
            </h3>

            <?php
            if (num_rows($resultado1) == 0) {
                echo "<p>No se registraron cursos para esta capacitaci&oacute;n</p>";
            }
            ?>

            <div class="panel-body">
                <table class="table users-table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th class="visible-lg">#</th>
                            <th class="visible-lg">Titulo del curso</th>
                            <th class="visible-lg">Horario</th>
                            <th class="visible-lg" style="width: 270px;">
                                Acci&oacute;n
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        while ($participante = fetch($resultado1)) {
                            
                            ?>
                            <tr id="ajaxbox-tr-participante-<?php echo $participante['id']; ?>">
                                <td class="visible-lg"><?php echo $cnt--; ?></td>
                                <td class="visible-lg">
                                    <?php
                                    echo trim($participante['titulo_curso']);
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo trim($participante['horario']);
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    ...
                                </td>
                        </tr>
                        <?php
                    }
                    ?>
    
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>

<!-- ajax de emision de certificados -->
<script>
    function capacitacion_emitir_certificado(dat) {

        var receptor_de_certificado = $("#receptor_de_certificado-" + dat).val();
        var id_certificado = $("#id_certificado-" + dat).val();
        var id_capacitacion = $("#id_capacitacion-" + dat).val();
        var id_participante = $("#id_participante-" + dat).val();

        $("#box-modal_emision_certificado-" + dat).html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.capacitacion_emitir_certificado.php',
            data: {receptor_de_certificado: receptor_de_certificado, id_certificado: id_certificado, id_capacitacion: id_capacitacion, id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificado-" + dat).html(data);
                $("#box-modal_emision_certificado-button-" + dat).html('<i class="btn-sm btn-default active">Emitido</i>');
            }
        });
    }
</script>


<!-- ajax de emision de certificados - segundo certificado -->
<script>
    function capacitacion_emitir_certificado_2(dat) {

        var receptor_de_certificado = $("#receptor_de_certificado-2-" + dat).val();
        var id_certificado = $("#id_certificado-2-" + dat).val();
        var id_capacitacion = $("#id_capacitacion-" + dat).val();
        var id_participante = $("#id_participante-" + dat).val();

        $("#box-modal_emision_certificado-2-" + dat).html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.capacitacion_emitir_certificado.php?segundo_certificado=true',
            data: {receptor_de_certificado: receptor_de_certificado, id_certificado: id_certificado, id_capacitacion: id_capacitacion, id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificado-2-" + dat).html(data);
                $("#box-modal_emision_certificado-button-2-" + dat).html('<i class="btn-sm btn-default active">Emitido</i>');
            }
        });
    }
</script>

<!-- ajax de emision de facturas -->
<script>
    function capacitacion_emitir_factura(dat) {

        var nombre_a_facturar = $("#nombre_a_facturar-" + dat).val();
        var nit_a_facturar = $("#nit_a_facturar-" + dat).val();
        var monto_a_facturar = $("#monto_a_facturar-" + dat).val();
        var id_certificado = $("#id_certificado-" + dat).val();
        var id_capacitacion = $("#id_capacitacion-" + dat).val();
        var id_participante = $("#id_participante-" + dat).val();

        $("#box-modal_emision_factura-" + dat).html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.capacitacion_emitir_factura.php',
            data: {monto_a_facturar: monto_a_facturar, nit_a_facturar: nit_a_facturar, nombre_a_facturar: nombre_a_facturar, id_certificado: id_certificado, id_capacitacion: id_capacitacion, id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_factura-" + dat).html(data);
            }
        });
    }
</script>


<!-- ajax eliminacion de participante -->
<script>
    function eliminar_participante(dat) {

        if (confirm("Desea eliminar al participante?")) {

            $("#ajaxbox-button-eliminar-participante-" + dat).html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
            $.ajax({
                url: 'pages/ajax/ajax.instant.capacitacion_eliminar_participante.php',
                data: {dat: dat},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#ajaxbox-tr-participante-" + dat).html(data);
                }
            });

        }

    }
</script>

<!-- ajax habilitacion de participante -->
<script>
    function habilitar_participante(dat) {

        if (confirm("Desea habilitar nuevamente al participante?")) {

            $.ajax({
                url: 'pages/ajax/ajax.instant.capacitacion_habilitar_participante.php',
                data: {dat: dat},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    //$("#ajaxbox-tr-participante-" + dat).html(data);
                    alert('Participante-habilitado');
                    location.href = 'capacitaciones-participantes/<?php echo $id_capacitacion; ?>.adm';
                }
            });

        }

    }
</script>

<!-- ajax imprimir certificados masivamente -->
<script>
    function imprimir_certificados() {

        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();

        //alert('IDS: ' + ids.join(','));

        window.open('http://www.infosicoes.com/contenido/paginas/procesos/pdfs/certificado-2-masivo.php?id_participantes=' + ids.join(','), 'popup', 'width=700,height=500');


    }
</script>

<!-- ajax emision certificados masivamente -->
<script>
    function emision_multiple_certificados() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.capacitaciones-participantes.emision_multiple_certificados.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados masivamente p2 -->
<script>
    function emision_multiple_certificados_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_capacitacion; ?>';
        var id_capacitacion = '<?php echo $id_capacitacion; ?>';
        $("#box-modal_emision_certificados-multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.capacitaciones-participantes.emision_multiple_certificados_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_capacitacion: id_capacitacion},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-multiple").html(data);
            }
        });
    }
</script>




<!-- ajax imprimir facturas masivamente -->
<script>
    function imprimir_facturas() {

        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();

        //alert('IDS: ' + ids.join(','));

        window.open('http://www.infosicoes.com/contenido/paginas/procesos/pdfs/factura-1-masivo.php?id_participantes=' + ids.join(','), 'popup', 'width=700,height=500');


    }
</script>

<!-- ajax emision facturas masivamente -->
<script>
    function emision_multiple_facturas() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_facturas-multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.capacitaciones-participantes.emision_multiple_facturas.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_facturas-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax emision facturas masivamente p2 -->
<script>
    function emision_multiple_facturas_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_capacitacion; ?>';
        var id_capacitacion = '<?php echo $id_capacitacion; ?>';
        $("#box-modal_emision_facturas-multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.capacitaciones-participantes.emision_multiple_facturas_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_capacitacion: id_capacitacion},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_facturas-multiple").html(data);
            }
        });
    }
</script>


<!-- Modals auxiliares -->

<!-- Modal-generar reporte -->
<div id="MODAL-generar-reporte" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">GENERAR REPORTE</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 text-left">
                        <b>CURSO:</b> &nbsp; <?php echo $nombre_del_capacitacion; ?>
                        <br/>
                        <b>FECHA:</b> &nbsp; <?php echo $fecha_del_capacitacion; ?>
                    </div>
                    <div class="col-md-4 text-right">
                        <img src="<?php echo $url_imagen_del_capacitacion; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-12 text-left">
                        <p>Selecciona los campos necesarios para el reporte.</p>

                        <table class="table table-striped">
                            <tr>
                                <td>Prefijo</td>
                                <td class="text-center">
                                    <label for="f0a"><input name="data_report_prefijo" type="radio" value="1" id="f0a"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f0b"><input name="data_report_prefijo" type="radio" value="0" id="f0b" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Nombres</td>
                                <td class="text-center">
                                    <label for="f1"><input name="data_report_nombres" type="radio" value="1" id="f1" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f2"><input name="data_report_nombres" type="radio" value="0" id="f2"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Apellidos</td>
                                <td class="text-center">
                                    <label for="f3"><input name="data_report_apellidos" type="radio" value="1" id="f3" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f4"><input name="data_report_apellidos" type="radio" value="0" id="f4"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Datos de Facturaci&oacute;n</td>
                                <td class="text-center">
                                    <label for="f5"><input name="data_report_datosfacturacion" type="radio" value="1" id="f5" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f6"><input name="data_report_datosfacturacion" type="radio" value="0" id="f6"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Datos de Contacto</td>
                                <td class="text-center">
                                    <label for="f7"><input name="data_report_datoscontacto" type="radio" value="1" id="f7" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f8"><input name="data_report_datoscontacto" type="radio" value="0" id="f8"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Modo de registro</td>
                                <td class="text-center">
                                    <label for="f7a"><input name="data_report_modoregistro" type="radio" value="1" id="f7a"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f8a"><input name="data_report_modoregistro" type="radio" value="0" id="f8a" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Fecha de registro</td>
                                <td class="text-center">
                                    <label for="f7b"><input name="data_report_fecharegistro" type="radio" value="1" id="f7b"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f8b"><input name="data_report_fecharegistro" type="radio" value="0" id="f8b" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Firma</td>
                                <td class="text-center">
                                    <label for="f9"><input name="data_report_firma" type="radio" value="1" id="f9" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f10"><input name="data_report_firma" type="radio" value="0" id="f10"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Participantes eliminados</td>
                                <td class="text-center">
                                    <label for="f9a"><input name="data_report_eliminados" type="radio" value="1" id="f9a"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f10a"><input name="data_report_eliminados" type="radio" value="0" id="f10a" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                        </table>

                        <hr/>

                        <div class="panel-footer text-center">
                            <button class="btn btn-default active" onclick="generar_reporte('impresion');">IMPRIMIR</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="btn btn-success active" onclick="generar_reporte('excel');">EXCEL</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="btn btn-info active" onclick="generar_reporte('word');">WORD</button>
                        </div>

                    </div>
                </div>
                <hr/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-generar reporte -->

<script>
    function generar_reporte(formato) {

        var data_report_prefijo = "0";
        var array_data_report_prefijo = document.getElementsByName("data_report_prefijo");
        for (var i = 0; i < array_data_report_prefijo.length; i++) {
            if (array_data_report_prefijo[i].checked)
                data_report_prefijo = array_data_report_prefijo[i].value;
        }

        var data_report_nombres = "0";
        var array_data_report_nombres = document.getElementsByName("data_report_nombres");
        for (var i = 0; i < array_data_report_nombres.length; i++) {
            if (array_data_report_nombres[i].checked)
                data_report_nombres = array_data_report_nombres[i].value;
        }

        var data_report_apellidos = "0";
        var array_data_report_apellidos = document.getElementsByName("data_report_apellidos");
        for (var i = 0; i < array_data_report_apellidos.length; i++) {
            if (array_data_report_apellidos[i].checked)
                data_report_apellidos = array_data_report_apellidos[i].value;
        }

        var data_report_datosfacturacion = "0";
        var array_data_report_datosfacturacion = document.getElementsByName("data_report_datosfacturacion");
        for (var i = 0; i < array_data_report_datosfacturacion.length; i++) {
            if (array_data_report_datosfacturacion[i].checked)
                data_report_datosfacturacion = array_data_report_datosfacturacion[i].value;
        }

        var data_report_datoscontacto = "0";
        var array_data_report_datoscontacto = document.getElementsByName("data_report_datoscontacto");
        for (var i = 0; i < array_data_report_datoscontacto.length; i++) {
            if (array_data_report_datoscontacto[i].checked)
                data_report_datoscontacto = array_data_report_datoscontacto[i].value;
        }

        var data_report_modoregistro = "0";
        var array_data_report_modoregistro = document.getElementsByName("data_report_modoregistro");
        for (var i = 0; i < array_data_report_modoregistro.length; i++) {
            if (array_data_report_modoregistro[i].checked)
                data_report_modoregistro = array_data_report_modoregistro[i].value;
        }

        var data_report_fecharegistro = "0";
        var array_data_report_fecharegistro = document.getElementsByName("data_report_fecharegistro");
        for (var i = 0; i < array_data_report_fecharegistro.length; i++) {
            if (array_data_report_fecharegistro[i].checked)
                data_report_fecharegistro = array_data_report_fecharegistro[i].value;
        }

        var data_report_firma = "0";
        var array_data_report_firma = document.getElementsByName("data_report_firma");
        for (var i = 0; i < array_data_report_firma.length; i++) {
            if (array_data_report_firma[i].checked)
                data_report_firma = array_data_report_firma[i].value;
        }

        var data_report_eliminados = "0";
        var array_data_report_eliminados = document.getElementsByName("data_report_eliminados");
        for (var i = 0; i < array_data_report_eliminados.length; i++) {
            if (array_data_report_eliminados[i].checked)
                data_report_eliminados = array_data_report_eliminados[i].value;
        }

        var data_required = 'data_report_nombres=' + data_report_nombres + '&data_report_apellidos=' + data_report_apellidos + '&data_report_datosfacturacion=' + data_report_datosfacturacion + '&data_report_datoscontacto=' + data_report_datoscontacto + '&data_report_firma=' + data_report_firma + '&data_report_prefijo=' + data_report_prefijo + '&data_report_fecharegistro=' + data_report_fecharegistro + '&data_report_modoregistro=' + data_report_modoregistro + '&data_report_eliminados=' + data_report_eliminados;

        window.open('http://www.infosicoes.com/pages/ajax/ajax.impresion.capacitaciones-participantes.exportar-lista.php?id_capacitacion=<?php echo $id_capacitacion; ?>&' + formato + '=true&' + data_required, 'popup', 'width=700,height=500');

    }
</script>


<?php

function my_date_capacitacion($dat) {
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]] . " " . substr($ar1[0], 2, 2);
    }
}
?>