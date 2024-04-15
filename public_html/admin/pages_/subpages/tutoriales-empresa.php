<?php
/* REQUERIDO PHP MAILER */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';

/* MENSAJE */
$mensaje = '';

/* editar-tutorial */
if (isset_post('editar-tutorial')) {

    $id_tutorial = post('id_tutorial');
    $titulo = post('titulo');
    $minutos = post('minutos');
    $localvideofile = '';
    $video = post('video');
    $sw_vimeo = post('sw_vimeo');

    if ($sw_vimeo == '0' && isset_archivo('localvideofile')) {
        $localvideofile = 'TT' . archivoName('localvideofile');
        move_uploaded_file(archivo('localvideofile'), $___path_raiz . "contenido/videos/cursos/$localvideofile");
    }

    query("UPDATE tutoriales_empresa SET 
              titulo='$titulo',  
              minutos='$minutos',  
              localvideofile='$localvideofile',  
              video='$video',  
              sw_vimeo='$sw_vimeo'  
               WHERE id='$id_tutorial' ORDER BY id DESC limit 1 ");

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> datos actualizados correctamente.
    </div>';
}

/* editar-tutorial-contenido-VIDEO */
if (isset_post('editar-tutorial-contenido-VIDEO')) {
    $id_tutorial = post('id_tutorial');
    $video = post('video');
    query("UPDATE tutoriales_empresa SET 
              video='$video' 
               WHERE id='$id_tutorial' ORDER BY id DESC limit 1 ");
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> datos actualizados correctamente.
    </div>';
}

/* editar-tutorial-contenido-VIDEO-local */
if (isset_post('editar-tutorial-contenido-VIDEO-local')) {
    if (isset_archivo('localvideofile')) {
        $id_tutorial = post('id_tutorial');
        $arch_vid = 'TT' . '-' . archivoName('localvideofile');
        move_uploaded_file(archivo('localvideofile'), $___path_raiz . "contenido/videos/cursos/$arch_vid");
        query("UPDATE tutoriales_empresa SET 
                  sw_vimeo='0', 
                  localvideofile='$arch_vid' 
                   WHERE id='$id_tutorial' ORDER BY id DESC limit 1 ");
        $mensaje .= '<div class="alert alert-success">
          <strong>Exito!</strong> datos actualizados correctamente.
        </div>';
    }
}

/* editar-tutorial-contenido-VIDEO-sw_vimeo */
if (isset_post('editar-tutorial-contenido-VIDEO-sw_vimeo')) {
    $id_tutorial = post('id_tutorial');
    $sw_vimeo = post('sw_vimeo');
    query("UPDATE tutoriales_empresa SET 
              sw_vimeo='$sw_vimeo' 
               WHERE id='$id_tutorial' ORDER BY id DESC limit 1 ");
    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> datos actualizados correctamente.
    </div>';
}


/* crear-tutorial-onlinecourse */
if (isset_post('crear-tutorial-onlinecourse')) {
    $titulo = post('titulo');
    $minutos = post('minutos');
    $localvideofile = '';
    $video = post('video');
    $sw_vimeo = post('sw_vimeo');

    if ($sw_vimeo == '0' && isset_archivo('localvideofile')) {
        $localvideofile = 'TT' . '-' . archivoName('localvideofile');
        move_uploaded_file(archivo('localvideofile'), $___path_raiz . "contenido/videos/cursos/$localvideofile");
    }

    $urltag = 'tut-emp-' . md5(md5(rand(99, 99999)));
    query("INSERT INTO tutoriales_empresa
    (urltag,titulo,sw_vimeo,minutos,video,localvideofile,estado) 
    VALUES 
    ('$urltag','$titulo','$sw_vimeo','$minutos','$video','$localvideofile','1')");


    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el regsitro se creo correctamente.
    </div>';
}



$array_meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');


/* videos */
$rqlc1 = query("SELECT * FROM tutoriales_empresa WHERE estado=1 ");
$cnt_tutoriales = num_rows($rqlc1);
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
        <h3 class="titulo-head-principal"> <i class="btn btn-warning active btn-sm">VIDEOS</i> TUTORIALES DE LA EMPRESA </h3>
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
            <b>TUTORIALES EXISTENTES: </b> &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $cnt_tutoriales; ?> video(s) 
            &nbsp;&nbsp;&nbsp;&nbsp;
            <?php if (acceso_cod('adm-tut-emp')) { ?>
            <a class="btn btn-info active" data-toggle="modal" data-target="#MODAL-crear-tutorial-onlinecourse"><i class="fa fa-plus"></i> AGREGAR TUTORIAL</a>
            <?php } ?>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">TUTORIALES DISPONIBLES</div>
            <div class="panel-body">
                <?php
                if ($cnt_tutoriales == 0) {
                ?>
                    <div class="alert alert-info">
                        <strong>AVISO</strong> no se regitro ningun tutorial.
                    </div>
                <?php
                }
                ?>
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>#</th>
                        <th>T&iacute;tulo del tutorial</th>
                        <th>Duraci&oacute;n</th>
                        <th colspan="2">Video</th>
                        <th>Acciones</th>
                    </tr>
                    <?php
                    $cnt=1;
                    while ($leccion = fetch($rqlc1)) {
                    ?>
                        <tr>
                            <td># <?php echo $cnt++; ?></td>
                            <td style="font-weight: bold;color: #1c79b7;font-size: 14pt;"><?php echo $leccion['titulo']; ?></td>
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
                                <b class="btn btn-lg btn-danger active" onclick="ver_video_leccion('<?php echo $leccion['id']; ?>');"><i class="fa fa-play-circle"></i> VER VIDEO</b>
                            </td>
                            <td>
                                <?php if (acceso_cod('adm-tut-emp')) { ?>
                                    <b class="btn btn-xs btn-info" onclick="editar_tutorial('<?php echo $leccion['id']; ?>');"><i class="fa fa-edit"></i> Editar</b>
                                    &nbsp;
                                    <b class="btn btn-xs btn-default" onclick="eliminar_tutorial('<?php echo $leccion['id']; ?>');"><i class="fa fa-trash-o"></i> Eliminar</b>
                                <?php } ?>
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
<div id="MODAL-crear-tutorial-onlinecourse" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CREAR TUTORIAL</h4>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td><b>T&iacute;tulo de tutorial:</b></td>
                            <td><textarea name="titulo" class="form-control" rows="2" required="" placeholder="Descripci&oacute;n del tutorial..."></textarea></td>
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
                                <div style="text-align: center;padding:20px;">
                                    <input type="submit" name="crear-tutorial-onlinecourse" value="CREAR TUTORIAL" class="btn btn-success btn-lg btn-animate-demo active" />
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
    function ver_video_leccion(id_tutorial) {
        $("#TITLE-modgeneral").html('VER VIDEO');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.tutoriales-empresa.ver_video_leccion.php',
            data: {
                id_tutorial: id_tutorial
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
<!-- editar_tutorial -->
<script>
    function editar_tutorial(id_tutorial) {
        $("#TITLE-modgeneral").html('EDITAR TUTORIAL');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.tutoriales-empresa.editar_tutorial.php',
            data: {
                id_tutorial: id_tutorial
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
<!-- eliminar_tutorial -->
<script>
    function eliminar_tutorial(id_tutorial) {
        if (confirm('DESEA ELIMINAR ESTE TUTORIAL ?')) {
            $("#TITLE-modgeneral").html('ELIMINAR TUTORIAL');
            $("#AJAXCONTENT-modgeneral").html('Cargando...');
            $("#MODAL-modgeneral").modal('show');
            $.ajax({
                url: 'pages/ajax/ajax.tutoriales-empresa.eliminar_tutorial.php',
                data: {
                    id_tutorial: id_tutorial
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    console.log(data);
                    location.href = 'tutoriales-empresa.adm';
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