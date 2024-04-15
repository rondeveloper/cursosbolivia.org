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
if ($postdata !== '') {
    $_POST = json_decode(base64_decode($postdata), true);
}
?>

<!-- CONTENIDO DE PAGINA -->

<?php
if (!acceso_cod('adm-estadisticas')) {
    echo "DENEGADO";
    exit;
}
?>
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
            <li class="active">Estadisticas</li>
        </ul>
        <!--        <div class="form-group hiddn-minibar pull-right">
                    <a href="cursos-crear.adm" class='btn btn-success active'> <i class='fa fa-plus'></i> AGREGAR CURSO</a>
                </div>-->
        <h3 class="page-header"> ESTAD&Iacute;STICAS - REPORTE PRINCIPAL <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                CURSOS - Estadisticas cursos
            </p>
        </blockquote>

        <div style="background: #eaeaea;
             padding: 15px 10px;
             border-radius: 10px;
             border: 1px solid #ffffff;
             box-shadow: 0px 1px 3px 0px #b5b5b5;">
            <form action="" method="post" id="FORM-listado">

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Buscador: </span>
                            <input type="text" name="nombre" value="" class="form-control" placeholder="Ingrese nombre de curso"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; N&uacute;mero: </span>
                            <input type="number" name="numero" value="" class="form-control" placeholder="N&uacute;mero..."/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Departamento: </span>
                            <select class="form-control" name="id_departamento" id="select_departamento" onchange="actualiza_ciudades();
                                    actualiza_lugares();">
                                <?php
                                echo "<option value='0'>Todos los departamentos...</option>";
                                $rqd1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ");
                                while ($rqd2 = mysql_fetch_array($rqd1)) {
                                    echo "<option value='" . $rqd2['id'] . "'>" . $rqd2['nombre'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Ciudad: </span>
                            <select class="form-control" name="id_ciudad" id="select_ciudad" onchange="actualiza_lugares();">
                                <option value='0'>Todos las ciudades...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Lugar: </span>
                            <select class="form-control" name="id_lugar" id="select_lugar">
                                <option value='0'>Todos los lugares...</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Docente: </span>
                            <select class="form-control" name="id_docente">
                                <?php
                                echo "<option value='0'>Todos los docentes...</option>";
                                $rqd1 = query("SELECT id,nombres,prefijo FROM cursos_docentes WHERE estado='1' ORDER BY nombres ASC ");
                                while ($rqd2 = mysql_fetch_array($rqd1)) {
                                    echo "<option value='" . $rqd2['id'] . "'>" . $rqd2['prefijo'] . ' ' . $rqd2['nombres'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Desde: </span>
                            <input type="date" name="fecha_inicio" value="<?php echo date("Y-m-d"); ?>" class="form-control" placeholder="Fecha de inicio..."/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Hasta: </span>
                            <input type="date" name="fecha_fin" value="<?php echo date("Y-m-d"); ?>" class="form-control" placeholder="Fecha de inicio..."/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="padding-top: 2px;">
                        <b class="btn btn-success btn-block" onclick="listado();">EFECTUAR BUSQUEDA</b>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="rowNOT">
    <div class="col-md-12NOT">
        <div class="panelNOT">
            <div class="panel-body">

                <div id="AJAXCONTENT-listado">
                    <hr/>
                    <p style='text-align:center;'>El proceso de b&uacute;squeda demora dependiendo los filtros asignados y el rango de fechas, podria tardar desde unos cuantos segundos hasta varios minutos. Tenga en cuenta que el m&aacute;ximo de registros permitidos es de 500.</p>
                    <hr/>
                </div>

            </div>
        </div>
    </div>
</div>




<script>
    function listado() {
        $("#AJAXCONTENT-listado").html("<hr/><div style='text-align:center;'><div class='loader' style='margin:auto;'></div><b>CARGANDO...</b></div><hr/><p style='text-align:center;'>El proceso de b&uacute;squeda demora dependiendo los filtros asignados y el rango de fechas, podria tardar desde unos cuantos segundos hasta varios minutos. Tenga en cuenta que el m&aacute;ximo de registros permitidos es de 500.</p><hr/>");
        var form = $("#FORM-listado").serialize();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.estadisticas-cursos.listado.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-listado").html(data);
            }
        });
    }
</script>
<script>
    function actualiza_ciudades() {
        $("#select_ciudad").html('<option>Cargando...</option>');
        var id_departamento = $("#select_departamento").val();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-editar.actualiza_ciudades.php',
            data: {id_departamento: id_departamento, current_id_ciudad: '0', sw_option_todos: '1'},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#select_ciudad").html(data);
            }
        });
    }
</script>
<script>
    function actualiza_lugares() {
        $("#select_lugar").html('<option>Cargando...</option>');
        var id_ciudad = $("#select_ciudad").val();
        var id_departamento = $("#select_departamento").val();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.estadisticas-cursos.actualiza_lugares.php',
            data: {id_ciudad: id_ciudad, id_departamento: id_departamento},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#select_lugar").html(data);
            }
        });
    }
</script>



