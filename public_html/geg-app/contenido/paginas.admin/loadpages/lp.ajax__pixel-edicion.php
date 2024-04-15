<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);
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
if($postdata!==''){
    $_POST = json_decode(base64_decode($postdata),true);
}
?>

<!-- CONTENIDO DE PAGINA -->

<?php
$mensaje = '';

/* pixel */
if (isset_post('pixel')) {

    $pixel = str_replace('"',"'",post_html('pixel'));
    query('UPDATE cursos_webdata SET pixel_facebook="'.$pixel.'" ');
    
    logcursos('Edicion de pixel de facebook', 'web-edicion', 'web', 1);

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> actualizacion realizada correctamente.
    </div>';
}

/* registro */
$rq1 = query("SELECT pixel_facebook FROM cursos_webdata");
$rq2 = mysql_fetch_array($rq1);
$codigo_pixel = $rq2['pixel_facebook'];

?>
<style>
    .modal-dialog{
        width: 800px !important;
    }
    .panel-primary>.panel-heading {
        border-color: #428bca!important;
    }
</style>

<div class="hidden-lg">
    <?php
    include_once '../../paginas.admin/items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once '../../paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a <?php echo loadpage('inicio'); ?>>Panel Principal</a></li>
            <li><a <?php echo loadpage('cursos-listar'); ?>>Cursos</a></li>
            <li class="active">Creaci&oacute;n</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <h3 class="page-header"> PIXEL DE FACEBOOK </h3>
        <blockquote class="page-information hidden">
            <p>
                Edici&oacute;n de pixel facebook
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

            <div class="panel-body">

                <div class="panel panel-primary">
                    <div class="panel-heading">C&Oacute;DIGO DE PIXEL</div>
                    <form enctype="multipart/form-data" action="pixel-edicion.adm" method="post">
                        <div class="panel-body">

                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; C&oacute;digo: </span>
                                                <br/>
                                                <br/>
                                                <input type="reset" value="Resetear formulario" onClick="$('#contpixel').html('');"/>
                                            </td>
                                            <td>
                                                <textarea class="form-control" name="pixel" rows="15" style="resize: none;" id="contpixel"><?php echo $codigo_pixel; ?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div style="text-align: center;padding:20px;">
                                <input type="submit" value="ACTUALIZAR" name="formulario" class="btn btn-success btn-block active"/>
                            </div>
                        </div>
                    </form>
                </div>

                <br/>
                <hr/>
                <br/>

            </div>
        </div>
    </div>
</div>
