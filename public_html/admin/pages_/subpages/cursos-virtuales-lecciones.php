<?php
/* REQUERIDO PHP MAILER */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

/* ID ONLINE COURSE */
$id_onlinecourse = (int) $get[2];

/* ONLINE COURSE  */
$qrdc1 = query("SELECT titulo,urltag FROM cursos_onlinecourse WHERE id='$id_onlinecourse' LIMIT 1 ");
$qrdc2 = fetch($qrdc1);
$titulo_onlinecourse = $qrdc2['titulo'];
$urltag_onlinecourse = $qrdc2['urltag'];

/* MENSAJE */
$mensaje = '';


/* editar-leccion */
if (isset_post('editar-leccion')) {

    $id_leccion = post('id_leccion');
    $titulo = post('titulo');
    $nro_leccion = post('nro_leccion');
    $minutos = post('minutos');
    $localvideofile = '';
    $video = post('video');
    $sw_vimeo = post('sw_vimeo');

    if ($sw_vimeo == '0' && isset_archivo('localvideofile')) {
        $localvideofile = 'CV' . $id_onlinecourse . '-' . archivoName('localvideofile');
        move_uploaded_file(archivo('localvideofile'), $___path_raiz . "contenido/videos/cursos/$localvideofile");
    }

    query("UPDATE cursos_onlinecourse_lecciones SET 
              titulo='$titulo',  
              nro_leccion='$nro_leccion',  
              minutos='$minutos',  
              localvideofile='$localvideofile',  
              video='$video',  
              sw_vimeo='$sw_vimeo'  
               WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");

    logcursos('Edicion de datos de leccion [L:' . $id_leccion . ']', 'curso-virtual-edicion', 'curso-virtual', $id_onlinecourse);

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> datos actualizados correctamente.
    </div>';
}

/* editar-leccion-contenido-VIDEO */
if (isset_post('editar-leccion-contenido-VIDEO')) {
    $id_leccion = post('id_leccion');
    $video = post('video');
    query("UPDATE cursos_onlinecourse_lecciones SET 
              video='$video' 
               WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");
    logcursos('Edicion de video de leccion [L:' . $id_leccion . ']', 'curso-virtual-edicion', 'curso-virtual', $id_onlinecourse);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> datos actualizados correctamente.
    </div>';
}

/* editar-leccion-contenido-VIDEO-local */
if (isset_post('editar-leccion-contenido-VIDEO-local')) {
    if (isset_archivo('localvideofile')) {
        $id_leccion = post('id_leccion');
        $arch_vid = 'CV' . $id_onlinecourse . '-' . archivoName('localvideofile');
        move_uploaded_file(archivo('localvideofile'), $___path_raiz . "contenido/videos/cursos/$arch_vid");
        query("UPDATE cursos_onlinecourse_lecciones SET 
                  sw_vimeo='0', 
                  localvideofile='$arch_vid' 
                   WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");
        logcursos('Edicion de video de leccion, agregado de video [L:' . $id_leccion . ']', 'curso-virtual-edicion', 'curso-virtual', $id_onlinecourse);
        $mensaje .= '<div class="alert alert-success">
          <strong>Exito!</strong> datos actualizados correctamente.
        </div>';
    }
}

/* editar-leccion-contenido-VIDEO-sw_vimeo */
if (isset_post('editar-leccion-contenido-VIDEO-sw_vimeo')) {
    $id_leccion = post('id_leccion');
    $sw_vimeo = post('sw_vimeo');
    query("UPDATE cursos_onlinecourse_lecciones SET 
              sw_vimeo='$sw_vimeo' 
               WHERE id='$id_leccion' ORDER BY id DESC limit 1 ");
    logcursos('Edicion de video de leccion [L:' . $id_leccion . '][vimeo/local]', 'curso-virtual-edicion', 'curso-virtual', $id_onlinecourse);
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> datos actualizados correctamente.
    </div>';
}


/* crear-leccion-onlinecourse */
if (isset_post('crear-leccion-onlinecourse')) {
    $titulo = post('titulo');
    $nro_leccion = post('nro_leccion');
    $minutos = post('minutos');
    $localvideofile = '';
    $video = post('video');
    $sw_vimeo = post('sw_vimeo');

    if ($sw_vimeo == '0' && isset_archivo('localvideofile')) {
        $localvideofile = 'CV' . $id_onlinecourse . '-' . archivoName('localvideofile');
        move_uploaded_file(archivo('localvideofile'), $___path_raiz . "contenido/videos/cursos/$localvideofile");
    }

    $urltag = $id_onlinecourse . '-' . $nro_leccion . '-' . md5(md5(rand(9999, 999999)));
    query("INSERT INTO cursos_onlinecourse_lecciones
    (id_onlinecourse,nro_leccion,urltag,titulo,sw_vimeo,minutos,video,localvideofile,estado) 
    VALUES 
    ('$id_onlinecourse','$nro_leccion','$urltag','$titulo','$sw_vimeo','$minutos','$video','$localvideofile','1')");

    /* notificacion a participantes */
    if(isset_post('sw_notificar_participantes')){
        $mensaje .= '<div class="alert alert-warning">';
        $rqdp1 = query("SELECT p.nombres,p.apellidos,p.correo,p.id_usuario FROM cursos_rel_cursoonlinecourse r INNER JOIN cursos c ON r.id_curso=c.id INNER JOIN cursos_participantes p ON p.id_curso=c.id INNER JOIN cursos_onlinecourse_acceso a ON a.id_usuario=p.id_usuario WHERE r.id_onlinecourse='$id_onlinecourse' AND r.estado=1 AND c.estado IN (1,2) AND p.estado=1 AND a.estado=1 AND p.sw_pago=1 GROUP BY p.id "); 
        while($rqdp2 = fetch($rqdp1)){
            $nombre_participante = $rqdp2['nombres'].' '.$rqdp2['apellidos'];
            $correo_participante = $rqdp2['correo'];
            $id_usuario_participante = $rqdp2['id_usuario'];
            $busc = array('\r\n','[NOMBRE-PARTICIPANTE]','[NOMBRE-CURSO-VIRTUAL]','[NUMERO-LECCION]','[NOMBRE-LECCION]');
            $remm = array('<br>',$nombre_participante,$titulo_onlinecourse,$nro_leccion,$titulo);
            $notifmensaje = str_replace($busc,$remm,post('text-notif')).'<br><br>';
            $notifmensaje .= '<br><b style="color: #096d09;">DATOS DE ACCESO AL CURSO:</b>';
            $notifmensaje .= '<br><br><b>Link de ingreso:</b> '.$dominio_plataforma.'ingreso/'.$urltag_onlinecourse.'.html';
            $rqdus1 = query("SELECT u.email,u.password FROM cursos_usuarios u WHERE id='$id_usuario_participante' ORDER BY id DESC limit 1 ");
            $rqdus2 = fetch($rqdus1);
            $notifmensaje .= '<br><b>Usuario:</b> '.$rqdus2['email'];
            $notifmensaje .= '<br><b>Contrase&ntilde;a:</b> '.$rqdus2['password'];
            $notifmensaje .= '<br><br>';
            $tituloEmail = 'NUEVO VIDEO AGREGADO';
            $contenido_correo = platillaEmailUno($notifmensaje,$tituloEmail,$correo_participante,urlUnsubscribe($correo_participante),$nombre_participante);
            $asunto = 'Nuevo video agregado - LECCION '.$nro_leccion.' - '.$titulo_onlinecourse;
            SISTsendEmail($correo_participante, $asunto, $contenido_correo);
            $mensaje .= '<strong>Notificaci&oacute;n enviada</strong> '.$nombre_participante.' ['.$correo_participante.']<br>';
        }
        $mensaje .= '</div>';
    }

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el regsitro se creo correctamente.
    </div>';
}



$array_meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

$rqdco1 = query("SELECT * FROM cursos_onlinecourse WHERE id='$id_onlinecourse' LIMIT 1 ");
$curso = fetch($rqdco1);


/* lecciones */
$rqlc1 = query("SELECT * FROM cursos_onlinecourse_lecciones WHERE id_onlinecourse='$id_onlinecourse' ORDER BY nro_leccion ASC ");
$cnt_lecciones = num_rows($rqlc1);
?>
<div class="hidden-lg">
    <?php
    include_once 'pages/items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <h3 class="titulo-head-principal"> <i class="btn btn-info active btn-sm">CURSO VIRTUAL</i> LECCIONES : <?php echo $curso['titulo']; ?> </h3>
    </div>
</div>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4 text-center">
        <div style="    margin: 20px;
    border: 1px solid #00789f;
    padding: 20px;
    border-radius: 10px;
    background: #f9f9f9;">
            <b>LECCIONES DEL CURSO: </b> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $cnt_lecciones; ?> lecciones &nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-info active" data-toggle="modal" data-target="#MODAL-crear-leccion-onlinecourse"><i class="fa fa-plus"></i> AGREGAR LECCION</a>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">LECCIONES AGREGADAS</div>
            <div class="panel-body">
                <?php
                if ($cnt_lecciones == 0) {
                ?>
                    <div class="alert alert-info">
                        <strong>AVISO</strong> el curso no tiene asignado ninguna lecci&oacute;n.
                    </div>
                <?php
                }
                ?>
                <table class="table table-striped table-bordered">
                    <tr>
                        <th># Lecci&oacute;n</th>
                        <th>T&iacute;tulo de la lecci&oacute;n</th>
                        <th>Duraci&oacute;n</th>
                        <th colspan="2">Video</th>
                        <th>Acciones</th>
                    </tr>
                    <?php
                    $current_num_leccion = 0;
                    while ($leccion = fetch($rqlc1)) {
                        $current_num_leccion = $leccion['nro_leccion'];
                    ?>
                        <tr>
                            <td># <?php echo $leccion['nro_leccion']; ?></td>
                            <td style="font-weight: bold;color: #1c79b7;font-size: 10pt;"><?php echo $leccion['titulo']; ?></td>
                            <td><?php echo $leccion['minutos']; ?> min.</td>
                            <td>
                                <?php
                                if ($leccion['sw_vimeo'] == '1') {
                                ?>
                                    <b>Servidor:</b> &nbsp;&nbsp;&nbsp; <b style="color: skyblue;">VIMEO</b>
                                    <br>
                                    <b>ID de video:</b> <?php echo $leccion['video']; ?>
                                <?php
                                } elseif ($leccion['sw_vimeo'] == '2') {
                                ?>
                                    <b>Servidor:</b> &nbsp;&nbsp;&nbsp; <b style="color: red;">YOUTUBE</b>
                                    <br>
                                    <b>ID de video:</b> <?php echo $leccion['video']; ?>
                                <?php
                                } else {
                                ?>
                                    <b>Servidor:</b> &nbsp;&nbsp;&nbsp; <b style="color: green;">LOCAL</b>
                                    <br>
                                    <b>Archivo:</b> <?php echo $leccion['localvideofile']; ?>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <b class="btn btn-xs btn-danger active" onclick="ver_video_leccion('<?php echo $leccion['id']; ?>');"><i class="fa fa-play-circle"></i> VER VIDEO</b>
                            </td>
                            <td>
                                <b class="btn btn-xs btn-info" onclick="editar_leccion('<?php echo $leccion['id']; ?>');"><i class="fa fa-edit"></i> Editar</b>
                                &nbsp;
                                <b class="btn btn-xs btn-default" onclick="eliminar_leccion('<?php echo $leccion['id']; ?>');"><i class="fa fa-trash-o"></i> Eliminar</b>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal - crear leccion -->
<div id="MODAL-crear-leccion-onlinecourse" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CREAR LECCI&Oacute;N</h4>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td><b>T&iacute;tulo de lecci&oacute;n:</b></td>
                            <td><textarea name="titulo" class="form-control" rows="2" required="" placeholder="Descripci&oacute;n dada a los participantes del curso."></textarea></td>
                        </tr>
                        <tr>
                            <td><b>N&uacute;mero de lecci&oacute;n:</b></td>
                            <td><input type="number" name="nro_leccion" class="form-control" value="<?php echo ++$current_num_leccion; ?>"></td>
                        </tr>
                        <tr>
                            <td><b>Duraci&oacute;n en minutos:</b></td>
                            <td><input type="number" name="minutos" value="0" class="form-control"></td>
                        </tr>
                        <tr>
                            <td><b>Servidor:</b></td>
                            <td>
                                <select name="sw_vimeo" class="form-control" onchange="selec_servidor(this.value);">
                                    <option value="1">VIMEO</option>
                                    <option value="2">YOUTUBE</option>
                                    <option value="0">LOCAL</option>
                                </select>
                            </td>
                        </tr>
                        <tr id="tr-lf" style="display: none;">
                            <td><b>Archivo:</b></td>
                            <td><input type="file" name="localvideofile" class="form-control" /></td>
                        </tr>
                        <tr id="tr-vm" style="display: table-row;">
                            <td><b>ID de Video:</b></td>
                            <td><input type="text" name="video" value="" class="form-control"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="panel panel-info" style="margin: 10px;">
                                    <a data-toggle="collapse" data-target="#cont-notif-lec">
                                        <div class="panel-heading" style="cursor: pointer;padding: 5px 10px;">
                                            <h4 class="panel-title text-center">
                                                <i class="fa fa-angle-down"></i> Notificar participantes
                                            </h4>
                                        </div>
                                    </a>
                                    <div id="cont-notif-lec" class="collapse">
                                        <div class="panel-body">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <td style="width: 45px;text-align:center;">
                                                        <input type="checkbox" name="sw_notificar_participantes" value="1" style="height: 18px;width: 18px;" />
                                                    </td>
                                                    <td>
                                                        <b>Notificar a los participantes de cursos activos/temporales</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <textarea name="text-notif" class="form-control" style="height: 130px;">Hola [NOMBRE-PARTICIPANTE]

Acabamos de añadir el video [NUMERO-LECCION] ( [NOMBRE-LECCION] ) del curso [NOMBRE-CURSO-VIRTUAL].
El cual ya esta disponible en nuestra plataforma, te invitamos a revisarlo.

Saludos cordiales</textarea>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="text-align: center;padding:20px;">
                                    <input type="submit" name="crear-leccion-onlinecourse" value="CREAR LECCI&Oacute;N" class="btn btn-success btn-lg btn-animate-demo active" />
                                </div>
                            </td>
                        </tr>
                    </table>


                </form>
                <hr />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal - crear leccion -->


<!-- ver_video_leccion -->
<script>
    function ver_video_leccion(id_leccion) {
        $("#TITLE-modgeneral").html('VER VIDEO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-virtuales-lecciones.ver_video_leccion.php',
            data: {
                id_leccion: id_leccion
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
<!-- editar_leccion -->
<script>
    function editar_leccion(id_leccion) {
        $("#TITLE-modgeneral").html('EDITAR LECCION');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-virtuales-lecciones.editar_leccion.php',
            data: {
                id_leccion: id_leccion
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
<!-- eliminar_leccion -->
<script>
    function eliminar_leccion(id_leccion) {
        if (confirm('DESEA ELIMINAR ESTA LECCION ?')) {
            $("#TITLE-modgeneral").html('ELIMINAR LECCION');
            $("#AJAXCONTENT-modgeneral").html('Cargando...');
            $("#MODAL-modgeneral").modal('show');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-virtuales-lecciones.eliminar_leccion.php',
                data: {
                    id_leccion: id_leccion,
                    id_onlinecourse: '<?php echo $id_onlinecourse; ?>'
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    console.log(data);
                    location.href = 'cursos-virtuales-lecciones/<?php echo $id_onlinecourse; ?>.adm';
                }
            });
        }
    }
</script>

<script>
    function selec_servidor(dat) {
        if (dat == '0') {
            $("#tr-lf").css('display', 'table-row');
            $("#tr-vm").css('display', 'none');
        } else {
            $("#tr-lf").css('display', 'none');
            $("#tr-vm").css('display', 'table-row');
        }
    }
</script>