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
            <li class="active">Registros por fechas</li>
        </ul>
        <h3 class="page-header"> ESTAD&Iacute;STICAS - REGISTROS POR FECHAS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
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

<!--                <div class="row">
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
                </div>-->

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
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Pago: </span>
                            <select class="form-control" name="pago">
                                <option value='con_sin'>CON o SIN pago</option>
                                <option value='con'>CON PAGO</option>
                                <option value='sin'>SIN PAGO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Modalidad: </span>
                            <select class="form-control" name="modalidad">
                                <option value='virtual_presencial'>VIRTUAL/PRESENCIAL</option>
                                <option value='virtual'>VIRTUAL</option>
                                <option value='presencial'>PRESENCIAL</option>
                            </select>
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
                            <select class="form-control" name="id_ciudad" id="select_ciudad">
                                <option value='0'>Todos las ciudades...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="padding-top: 2px;">
                        <b class="btn btn-info btn-block" onclick="listado();">EFECTUAR BUSQUEDA</b>
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
<!--                    <p style='text-align:center;'>El proceso de b&uacute;squeda demora dependiendo los filtros asignados y el rango de fechas, podria tardar desde unos cuantos segundos hasta varios minutos. Tenga en cuenta que el m&aacute;ximo de registros permitidos es de 500.</p>-->
                    <hr/>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- MODAL historial_participante -->
<div id="MODAL-historial_participante" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">LOG DE MOVIMIENTOS</h4>
            </div>
            <div class="modal-body">

                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-historial_participante"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<!-- historial_participante -->
<script>
    function historial_participante(id_participante) {
        $("#AJAXCONTENT-historial_participante").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.historial_participante.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-historial_participante").html(data);
            }
        });
    }
</script>


<!-- Modal pago-participante -->
<div id="MODAL-pago-participante" class="modal fade" role="dialog">
    <div class="modal-dialog modal-large">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PAGO CORRESPONDIENTE AL PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: EDCICION DE PARTICIPANTE P1 -->
                <div id="ajaxloading-pago_participante"></div>
                <div id="ajaxbox-pago_participante">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function pago_participante(id_participante) {
        $("#ajaxloading-pago_participante").html('Cargando...');
        $("#ajaxbox-pago_participante").html("");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.participantes-estadisticas.pago_participante.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-pago_participante").html("");
                $("#ajaxbox-pago_participante").html(data);
            }
        });
    }
</script>


<script>
    function listado() {
        $("#AJAXCONTENT-listado").html("<hr/><div style='text-align:center;'><div class='loader' style='margin:auto;'></div><b>CARGANDO...</b></div><hr/><p style='text-align:center;'>El proceso de b&uacute;squeda demora dependiendo los filtros asignados y el rango de fechas, podria tardar desde unos cuantos segundos hasta varios minutos. Tenga en cuenta que el m&aacute;ximo de registros permitidos es de 1500.</p><hr/>");
        var form = $("#FORM-listado").serialize();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.participantes-estadisticas.listado',
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



