<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

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

/* creacion de curso */
if (isset_post('formulario')) {

    $titulo = post('titulo');
    $id_categoria = post('id_categoria');
    $estado = '1';
    
    query("INSERT INTO cursos_examenes_generales (
              titulo,
              id_categoria,
              estado
              )
              VALUES ( 
              '$titulo',
              '$id_categoria',
              '$estado'
              )");
    $id_examen = insert_id();

    logcursos('Creacion de examen [' . $titulo . ']', 'examen-general-creacion', 'examen-general', $id_examen);

    $mensaje .= '<div class="alert alert-success">
      <strong>Exito!</strong> el curso fue creado correctamente.
    </div>
    <script>alert("Creacion de registro exitoso.");location.href="examenes-preguntas/'.$id_examen.'.adm";</script>';
}

$array_meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
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
        <h3 class="page-header"> CREACI&Oacute;N DE EXAMEN </h3>
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
                    <div class="panel-heading">DATOS DEL EXAMEN</div>
                    <form enctype="multipart/form-data" action="" method="post">
                        <div class="panel-body">
                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">
                                    <table style="width:100%;" class="table table-striped">
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; T&iacute;tulo del Examen: </span>
                                            </td>
                                            <td>
                                                <input type="text" name="titulo" value="" class="form-control" id="date">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Categoria: </span>
                                            </td>
                                            <td>
                                                <select class="form-control form-cascade-control" name="id_categoria">
                                                    <?php
                                                    $rqd1 = query("SELECT * FROM cursos_categorias WHERE estado='1' ");
                                                    while ($rqd2 = fetch($rqd1)) {
                                                        ?>
                                                        <option value="<?php echo $rqd2['id']; ?>" ><?php echo $rqd2['titulo']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Estado: </span>
                                            </td>
                                            <td>
                                                <p class="form-control text-center">
                                                    <input type="radio" name="estado" value="1" id="act" checked=""/> <label for="act">Activado</label> 
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <input type="radio" name="estado" value="0" id="dact"/> <label for="dact"> Desactivado</label>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <p class="text-center">[ Posterior a la creaci&oacute;n se te re-direccionar&aacute; al modulo de preguntas del examen ]</p>
                        </div>
                        <div class="panel-footer">
                            <div style="text-align: center;padding:20px;">
                                <input type="submit" value="CREAR EXAMEN" name="formulario" class="btn btn-success btn-block active"/>
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
