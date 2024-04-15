<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

/* verificacion de sesion */
if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}
/* manejo de parametros */
$data = 'nonedata/' . post('data');
$get = explode('/', $data);
if ($get[count($get) - 1] == '') {
    array_splice($get, (count($get) - 1), 1);
}
/* parametros post */
$postdata = post('postdata');
if ($postdata !== '') {
    $_POST = json_decode(base64_decode($postdata), true);
}
?>

<!-- CONTENIDO DE PAGINA -->

<?php
$id_curso = $get[2];

if (isset_post('id_curso')) {
    $id_curso = post('id_curso');
}

$no_data = '<i style="color:#AAA;">Sin dato</i>';

/* registros */
$resultado_paginas = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($resultado_paginas);

/* departamento */
$curso_id_ciudad = $curso['id_ciudad'];
if ($curso_id_ciudad == '24') {
    $curso_id_departamento = '0';
} else {
    $rqdd1 = query("SELECT id_departamento FROM ciudades WHERE id='$curso_id_ciudad' LIMIT 1 ");
    $rqdd2 = fetch($rqdd1);
    $curso_id_departamento = $rqdd2['id_departamento'];
}
$curso_departamento = $no_data;
$rqd1 = query("SELECT id,nombre FROM departamentos WHERE id='$curso_id_departamento' ");
while ($rqd2 = fetch($rqd1)) {
    $curso_departamento = $rqd2['nombre'];
}
$txt_lugar_curso = $no_data;
$txt_lugar_salon_curso = $no_data;
$txt_direccion_lugar_curso = $no_data;
$rqdl1 = query("SELECT id,nombre,salon,direccion FROM cursos_lugares WHERE id='" . $curso['id_lugar'] . "' ");
while ($rqdl2 = fetch($rqdl1)) {
    $selected = ' selected="selected" ';
    $txt_lugar_curso = $rqdl2['nombre'];
    $txt_lugar_salon_curso = $rqdl2['nombre'] . ' - ' . $rqdl2['salon'];
    $txt_direccion_lugar_curso = $rqdl2['direccion'];
}


if (isset_post('agregar-tag')) {
    $tag = post('tag');
    $contenido_tag = post_html('contenido-tag');
    query("INSERT INTO cursos_tags_contenido(titulo, contenido) VALUES ('$tag','$contenido_tag')");
    $id_tag = insert_id();
    logcursos('Creacion de TAG personalizado', 'tag-creacion', 'tag', $id_tag);
    echo '<div class="alert alert-success">
    <strong>EXITO</strong> registro agregado correctamente.
  </div>';
}

if (isset_post('editar-tag')) {
    $id_tag = post('id_tag');
    $tag = post('tag');
    $contenido_tag = post_html('contenido-tag');
    query("UPDATE cursos_tags_contenido SET titulo='$tag', contenido='$contenido_tag' WHERE id='$id_tag' ");
    logcursos('Edicion de TAG personalizado', 'tag-edicion', 'tag', $id_tag);
    echo '<div class="alert alert-success">
    <strong>EXITO</strong> registro actualizado correctamente.
  </div>';
}

if (isset_post('sw_delete_tag')) {
    $id_tag = post('id_tag');
    query("DELETE FROM cursos_tags_contenido WHERE id='$id_tag' ORDER BY id DESC limit 1 ");
    logcursos('Eliminacion de TAG personalizado', 'tag-edicion', 'tag', $id_tag);
    echo '<div class="alert alert-success">
    <strong>EXITO</strong> registro eliminado correctamente.
  </div>';
}
?>
<script src="<?php echo $dominio_www; ?>contenido/librerias/tinymce/js/tinymce/tinymce.min.js"></script>
<style>
    .modal-dialog {
        width: 800px !important;
    }

    .panel-primary>.panel-heading {
        border-color: #428bca !important;
    }
</style>

<div class="hidden-lg">
    <?php
    include_once '../items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once '../items/item.enlaces_top.php';
            ?>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <h3 class="page-header"> TAGs DE CONTENIDO DINAMICO </h3>
    </div>
</div>

<?php echo $mensaje; ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">

                <div class="panel panel-primary">
                    <div class="panel-heading">TAGs DE CONTENIDO DINAMICO</div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    TAGs PERSONALIZADOS
                                    <b class="pull-right btn btn-success btn-xs" onclick="agregar_tag_personalizado();"> + AGREGAR</b>
                                </h4>
                            </div>
                            <div class="panel-collapse">
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered table-hover">
                                        <?php
                                        $rqdpc1 = query("SELECT id,titulo,contenido FROM cursos_tags_contenido ORDER BY id ASC ");
                                        while ($rqdpc2 = fetch($rqdpc1)) {
                                        ?>
                                            <tr>
                                                <td style="font-size:12pt;"><?php echo $rqdpc2['titulo']; ?></td>
                                                <td><?php echo substr(strip_tags($rqdpc2['contenido']), 0, 70); ?>...</td>
                                                <td>
                                                    <b class="btn btn-info btn-xs" onclick="editar_tag_personalizado(<?php echo $rqdpc2['id']; ?>);">Editar</b>
                                                    <b class="btn btn-danger btn-xs" onclick="eliminar_tag_personalizado(<?php echo $rqdpc2['id']; ?>);">Eliminar</b>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    DATOS DE CURSO
                                </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered table-hover">
                                        <tr>
                                            <td style="font-size:12pt;width: 35%;">[NOMBRE-CURSO]</td>
                                            <td><?php echo $curso['titulo']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[CIUDAD-CURSO]</td>
                                            <td><?php echo $curso_departamento; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[FECHA-A1-CURSO]</td>
                                            <td><?php echo fecha_curso_D_d_m($curso['fecha']); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[HORARIOS]</td>
                                            <td><?php echo $curso['horarios']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[LUGAR-CURSO]</td>
                                            <td><?php echo $txt_lugar_curso; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[LUGAR-SALON-CURSO]</td>
                                            <td><?php echo $txt_lugar_salon_curso; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[DIRECCION-LUGAR]</td>
                                            <td><?php echo $txt_direccion_lugar_curso; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[COSTO-BS]</td>
                                            <td><?php echo $curso['costo'] . ' Bs'; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[COSTO-LITERAL]</td>
                                            <td><?php echo numToLiteral($curso['costo']); ?></td>
                                        </tr>
                                        <?php
                                        $txt_descuento_uno_curso = $no_data;
                                        $txt_descuento_dos_curso = $no_data;
                                        $txt_descuento_est_curso = $no_data;
                                        $txt_descuento_est_pre_curso = '';
                                        if ($curso['sw_fecha2'] == '1') {
                                            $txt_descuento_uno_curso = '<b style="color: #ff0000;">DESCUENTO :</b> ' . $curso['costo2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha2']) . hora_descuento($curso['fecha2']);
                                        }
                                        if ($curso['sw_fecha3'] == '1') {
                                            $txt_descuento_dos_curso = '<b style="color: #ff0000;">DESCUENTO :</b> ' . $curso['costo3'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha3']) . hora_descuento($curso['fecha3']);
                                        }
                                        if ($curso['sw_fecha_e'] == '1') {
                                            $txt_descuento_est_curso = '<b style="color: #ff0000;">ESTUDIANTES :</b> ' . $curso['costo_e'] . ' Bs ' . numToLiteral($curso['costo_e']) . ' (Asistir con original y fotocopia de su carnet universitario o instituto)';
                                        }
                                        if ($curso['sw_fecha_e2'] == '1') {
                                            $txt_descuento_est_pre_curso = '<b style="color: #ff0000;">DESCUENTO ESTUDIANTES :</b> ' . $curso['costo_e2'] . ' Bs. hasta el ' . fecha_curso_D_d_m($curso['fecha_e2']) . hora_descuento($curso['fecha_e2']) . ' para estudiantes. (Asistir con original y fotocopia de su carnet universitario o instituto)';
                                        }
                                        ?>
                                        <tr>
                                            <td style="font-size:12pt;">[DESCUENTO-UNO]</td>
                                            <td><?php echo $txt_descuento_uno_curso; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[DESCUENTO-DOS]</td>
                                            <td><?php echo $txt_descuento_dos_curso; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[COSTO-ESTUDIANTES]</td>
                                            <td><?php echo $txt_descuento_est_curso; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[DESCUENTO-ESTUDIANTES]</td>
                                            <td><?php echo $txt_descuento_est_pre_curso; ?></td>
                                        </tr>
                                        <?php
                                        $rqdrd1 = query("SELECT prefijo,nombres,curriculum FROM cursos_docentes WHERE id='" . $curso['id_docente'] . "' ORDER BY id ASC ");
                                        $rqdrd2 = fetch($rqdrd1);
                                        ?>
                                        <tr>
                                            <td style="font-size:12pt;">[DOCENTE-NOMBRE]</td>
                                            <td><?php echo trim($rqdrd2['prefijo'] . ' ' . $rqdrd2['nombres']); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[DOCENTE-CURRICULUM]</td>
                                            <td><?php echo $rqdrd2['curriculum']; ?></td>
                                        </tr>
                                        <?php
                                        $rqdtcb1 = query("SELECT c.*,(b.nombre)dr_nombre_banco FROM rel_cursocuentabancaria r INNER JOIN cuentas_de_banco c ON r.id_cuenta=c.id INNER JOIN bancos b ON c.id_banco=b.id WHERE r.id_curso='$id_curso' AND r.sw_cprin=1 AND r.estado=1 ORDER BY c.id ASC ");
                                        $rqdtcb2 = fetch($rqdtcb1);
                                        ?>
                                        <tr>
                                            <td style="font-size:12pt;">[NOMBRE-BANCO]</td>
                                            <td><?php echo $rqdtcb2['dr_nombre_banco']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[CUENTA-BANCO]</td>
                                            <td><?php echo $rqdtcb2['numero_cuenta']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[TITULAR-BANCO]</td>
                                            <td><?php echo $rqdtcb2['titular']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[INFO-PAGO-CUENTAS-BANCARIAS]</td>
                                            <td> (Info configurado de cuentas bancarias)... <?php //echo $rqdtcb2['titular']; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    Enlaces
                                </h4>
                            </div>
                            <div class="panel-collapse">
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered table-hover">
                                        <tr>
                                            <td><span style="font-size:12pt;color:#7a54da;">[REPORTE-SU-PAGO]</span></td>
                                            <td>( Enlace de reporte de pago )</td>
                                        </tr>
                                        <tr>
                                            <td><span style="font-size:12pt;color:#428bca;">[INSCRIPCION]</span></td>
                                            <td>( Enlace de inscripci&oacute;n )</td>
                                        </tr>
                                        <tr>
                                            <td><span style="font-size:12pt;color:green;">[WHATSAPP]</span></td>
                                            <td>( Numeros de WhatsApp asignados )</td>
                                        </tr>
                                        <tr>
                                            <td><span style="font-size:12pt;color:#222;">[NUMERO-CELULAR]</span></td>
                                            <td>( N&uacute;mero de celular asignado )</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    Imagenes
                                </h4>
                            </div>
                            <div class="panel-collapse">
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered table-hover">
                                        <tr>
                                            <td><span style="font-size:12pt;">[imagen-1]</span></td>
                                            <td>( Primera imagen asignada )</td>
                                        </tr>
                                        <tr>
                                            <td><span style="font-size:12pt;">[imagen-2]</span></td>
                                            <td>( Segunda imagen asignada )</td>
                                        </tr>
                                        <tr>
                                            <td><span style="font-size:12pt;">[imagen-3]</span></td>
                                            <td>( Tercera imagen asignada )</td>
                                        </tr>
                                        <tr>
                                            <td><span style="font-size:12pt;">[imagen-4]</span></td>
                                            <td>( Cuarta imagen asignada )</td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    Datos de Organizador
                                </h4>
                            </div>
                            <div class="panel-collapse">
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered table-hover">
                                        <?php
                                        $rqd1 = query("SELECT titulo,nit,telefono FROM cursos_organizadores WHERE estado='1' OR id='" . $curso['id_organizador'] . "' ");
                                        $rqd2 = fetch($rqd1);
                                        ?>
                                        <tr>
                                            <td style="font-size:12pt;">[NOMBRE-ORGANIZADOR]</td>
                                            <td style="font-size:12pt;"><?php echo $rqd2['titulo']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[NIT-ORGANIZADOR]</td>
                                            <td><?php echo $rqd2['nit']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12pt;">[TELEFONO-ORGANIZADOR]</td>
                                            <td><?php echo $rqd2['telefono']; ?></td>
                                        </tr>
                                        <?php
                                        $rqdpc1 = query("SELECT titulo FROM cursos_organizadores_cont_pr WHERE id_organizador='" . $curso['id_organizador'] . "' ORDER BY id ASC ");
                                        while ($rqdpc2 = fetch($rqdpc1)) {
                                        ?>
                                            <tr>
                                                <td style="font-size:12pt;"><?php echo $rqdpc2['titulo']; ?></td>
                                                <td><?php echo $rqdpc2['contenido']; ?></td>
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

                <br />
                <hr />
                <br />

            </div>
        </div>
    </div>
</div>



<!-- agregar_tag_personalizado -->
<script>
    function agregar_tag_personalizado() {
        $("#TITLE-modgeneral").html('AGREGAR TAG');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.agregar_tag_personalizado.php',
            data: {
                id_curso: '<?php echo $id_curso; ?>'
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>

<!-- editar_tag_personalizado -->
<script>
    function editar_tag_personalizado(id_tag) {
        $("#TITLE-modgeneral").html('EDITAR TAG');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.editar_tag_personalizado.php',
            data: {
                id_curso: '<?php echo $id_curso; ?>',
                id_tag: id_tag
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>


<!-- eliminar_tag_personalizado -->
<script>
    function eliminar_tag_personalizado(id_tag) {
        if (confirm('DESEA ELIMINAR EL TAG')) {
            $("#AJAXCONTENT-modgeneral").html('Cargando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-editar.editar_tag_personalizado.php',
                data: {
                    id_curso: '<?php echo $id_curso; ?>',
                    id_tag: id_tag,
                    sw_delete_tag: 1
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    location.reload();
                }
            });
        }
    }
</script>