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
if ($postdata !== '') {
    $_POST = json_decode(base64_decode($postdata), true);
}
?>

<!-- CONTENIDO DE PAGINA -->

<?php
/* vista */
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

$registros_a_mostrar = 500;
$start = ($vista - 1) * $registros_a_mostrar;


/* qr organizador */
$qr_organizador = "";
if (isset_organizador()) {
    $id_organizador = organizador('id');
    $qr_organizador = " AND id_curso IN (select id from cursos where id_organizador='$id_organizador' ) ";
}

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
$qr_nombre = '';
$rq_departamento = '';
if (isset_post('id_departamento')) {

    $busqueda = str_replace(' ', '%', post('input-buscador'));
    $busqueda_id_departamento = post('id_departamento');

    if (strlen($busqueda) > 0) {
        $qr_nombre = " AND nombre LIKE '%$busqueda%' ";
    }

    if ($busqueda_id_departamento !== '0') {
        $rq_departamento = " AND id_curso IN (select id from cursos where id_ciudad IN (select id from ciudades where id_departamento='$busqueda_id_departamento') ) ";
    }
}

//$resultado1 = query("SELECT * FROM cursos_participantes WHERE ( $qr_nombre $qr_prefijo ) $rq_departamento $qr_organizador ORDER BY id DESC LIMIT $start,$registros_a_mostrar");
$resultado1 = query("SELECT * FROM cursos_solicitudes_de_certificado WHERE 1 $qr_nombre  $rq_departamento  ORDER BY id DESC LIMIT $start,$registros_a_mostrar");
$total_registros = num_rows($resultado1);

$sw_selec = false;


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


$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );


//echo administrador('nivel')."<hr/>";
?>
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
            <li><a <?php echo loadpage('inicio'); ?>>Panel Principal</a></li>
            <li><a <?php echo loadpage('cursos-listar'); ?>>Cursos</a></li>
            <li class="active">Reprogramaci&oacute;n se participantes</li>
        </ul>
        
        <h3 class="page-header"> SOLICITUDES DE CERTIFICADO <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                PARTICIPANTES - REPROGRAMACI&Oacute;N DE ASISTENCIA
            </p>
        </blockquote>

        <form action="cursos-reprogramacion-participantes.adm" method="post">
            <div class="col-md-8">
                <div class="input-group col-sm-12">
                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Buscador: </span>
                    <input type="text" name="input-buscador" value="<?php echo str_replace('%', ' ', $busqueda); ?>" class="form-control" placeholder="Ingrese nombre y/o apellidos ..."/>
                </div>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="id_departamento">
                    <?php
                    echo "<option value='0'>Todos los departamentos...</option>";
                    $rqd1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ");
                    while ($rqd2 = fetch($rqd1)) {
                        $text_check = '';
                        if ($id_departamento == $rqd2['id']) {
                            $text_check = ' selected="selected" ';
                        }
                        echo "<option value='" . $rqd2['id'] . "' $text_check>" . $rqd2['nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="submit" value="BUSCAR" class="btn btn-warning btn-block active"/>
            </div>
        </form>
    </div>
</div>

<div class="rowNOT">
    <hr/>
    <div class="col-md-12NOT">
        <div class="panelNOT">

            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="visible-lgNOT" style="font-size:10pt;">#</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Prefijo</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Nombres</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Apellidos</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Contacto</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Motivo solicitud</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Curso</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Registro</th>
<!--                                <th class="visible-lgNOT" style="font-size:10pt;">Certificado</th>-->
                                <th class="visible-lgNOT" style="font-size:10pt;">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = fetch($resultado1)) {
                                ?>
                                <tr>
                                    <td class="visible-lgNOT">
                                        <?php echo $cnt--; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo $producto['prefijo']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo $producto['nombre']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo $producto['apellidos']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo $producto['celular']; ?>
                                        <br/>
                                        <?php echo $producto['correo']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo $producto['motivo_solicitud']; ?>
                                        <br/>
                                        <?php echo $producto['motivo_especifico']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo $producto['nombre_curso']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo date("d/M/Y H:i", strtotime($producto['fecha_registro']));
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT" style="width:120px;">
                                        <?php
                                        if ($producto['estado'] == '0' || true) {
                                            ?>
                                            <b class="btn btn-info btn-xs btn-block active" data-toggle="modal" data-target="#MODAL-proceso_solicitud" onclick="proceso_solicitud('<?php echo $producto['id']; ?>');">
                                                <i class='fa fa-certificate'></i> PROCESAR
                                            </b> 
                                            <?php
                                        }
                                        ?>
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
</div>


<!-- Modal -->
<div id="MODAL-proceso_solicitud" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SOLICITUD DE CERTIFICADO</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXBOX-proceso_solicitud"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<!-- proceso_solicitud -->
<script>
    function proceso_solicitud(id_solicitud) {
        $("#AJAXBOX-proceso_solicitud").html("Procesando...");
        $.ajax({
            url: 'pages/ajax/ajax.solicitudes-certificados.proceso_solicitud.php',
            data: {id_solicitud: id_solicitud},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXBOX-proceso_solicitud").html(data);
            }
        });
    }
</script>


<!-- asignar_asistencia -->
<script>
    function asignar_asistencia(id_solicitud) {
        var id_curso_asignacion = $("#id_curso_asignacion").val();
        $("#AJAXBOX-asignar_asistencia").html("Procesando...");
        alert("->" + id_curso_asignacion);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-reprogramacion-participantes.asignar_asistencia.php',
            data: {id_solicitud: id_solicitud, id_curso_asignacion: id_curso_asignacion},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXBOX-asignar_asistencia").html(data);
            }
        });
    }
</script>


<?php
function my_date_curso($dat) {
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        //return $ar1[2] . " " . $arraymes[(int)$ar1[1]] . " " . substr($ar1[0],2,2);
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]];
    }
}
