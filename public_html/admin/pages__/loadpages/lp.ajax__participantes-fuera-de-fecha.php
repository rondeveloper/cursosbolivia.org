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

/* datos de formulario por get */
if (isset($get[3])) {
    $_POST = json_decode(base64_decode($get[3]), true);
}
$postdata_throuhget = base64_encode(json_encode($_POST));

$registros_a_mostrar = 100;
$start = ($vista - 1) * $registros_a_mostrar;

/* predata */
$qr_busqueda = "";
$busqueda = "";
$rq_departamento = '';
$qr_nombre = ' 1 ';
$qr_administrador = '';
$nro_semana = date("W");
$id_administrador = '99';
$id_departamento = '0';

/* busqueda */
if (isset_post('realizar-busqueda')) {
    $busqueda = trim(str_replace(' ', '%', post('input-buscador')));
    $id_departamento = post('id_departamento');
    $id_administrador = post('id_administrador');
    $nro_semana = post('nro_semana');

    if (strlen($busqueda) > 0) {
        $qr_nombre = " CONCAT(p.nombres,' ',p.apellidos) LIKE '%$busqueda%' ";
    }
    if ($id_administrador !== '99') {
        $qr_administrador = " AND r.id_administrador='$id_administrador' ";
    }
    if ($id_departamento !== '0') {
        $rq_departamento = " AND p.id_curso IN (select id from cursos where id_ciudad IN (select id from ciudades where id_departamento='$id_departamento') ) ";
    }
}

/* qr_semana */
$qr_semana = "";
if ($nro_semana != '0') {
    $f_inicio = date('Y-m-d', strtotime('01/01 +' . ($nro_semana - 1) . ' weeks first day -3 day')) . '<br />';
    $f_final = date('Y-m-d', strtotime('01/01 +' . ($nro_semana - 1) . ' weeks first day +3 day')) . '<br />';
    $qr_semana = " AND DATE(r.fecha_registro)>='$f_inicio' AND DATE(r.fecha_registro)<='$f_final' ";
}

$data_required = "*,(r.id_emision_factura)dr_id_emision_factura,(r.razon_social)dr_razon_social,(r.nit)dr_nit,(r.fecha_registro)dr_fecha_registro,(c.fecha)dr_fecha_curso,(c.titulo)dr_titulo_curso,(select nombre from ciudades where id=c.id_ciudad)dr_departamento_curso,(c.estado)dr_estado_curso,(p.estado)dr_estado_participante,(select nombre from administradores where id=r.id_administrador)dr_nombre_administrador,(c.numero)dr_numero_curso,(p.id)dr_id_participante,(c.id)dr_id_curso,(p.id_modo_pago)dr_modo_pago,(r.monto_deposito)dr_monto_pago";
$resultado1 = query("SELECT $data_required FROM cursos_participantes p INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id INNER JOIN cursos c ON r.id_curso=c.id WHERE DATE(r.fecha_registro)>c.fecha AND ( $qr_nombre ) $rq_departamento $qr_administrador $qr_semana ORDER BY r.fecha_registro DESC LIMIT $start,$registros_a_mostrar");
$resultado_b1 = query("SELECT count(*) AS total FROM cursos_participantes p INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id INNER JOIN cursos c ON r.id_curso=c.id WHERE DATE(r.fecha_registro)>c.fecha AND ( $qr_nombre ) $rq_departamento $qr_administrador $qr_semana ");
$resultado_b2 = fetch($resultado_b1);
$total_registros = $resultado_b2['total'];

$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
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
            <li class="active">Participantes registrados fuera de fecha</li>
        </ul>
        <!--        <div class="form-group hiddn-minibar pull-right">
                    <a href="cursos-crear.adm" class='btn btn-success active'> <i class='fa fa-plus'></i> AGREGAR CURSO</a>
                </div>-->
        <h3 class="page-header">
            PARTICIPANTES REGISTRADOS FUERA DE FECHA <i class="fa fa-info-circle animated bounceInDown show-info"></i>
            <?php
            if( ($nro_semana<date("W")) && ($busqueda=='') && ($nro_semana!='0')){
                ?>
                <b class="btn btn-sm btn-primary pull-right" onclick="generar_reporte('<?php echo $postdata_throuhget; ?>');"><i class="fa fa-copy"></i> REPORTE</b>
                <?php
            }else{
                ?>
                <b class="btn btn-sm btn-default pull-right" onclick="alert('BUSQUEDA NO ACEPTADA PARA REPORTE');"><i class="fa fa-copy"></i> REPORTE</b>
                <?php
            }
            ?>
        </h3>
        <blockquote class="page-information hidden">
            <p>
                CURSOS - Busqueda de participante
            </p>
        </blockquote>


        <form action="participantes-fuera-de-fecha.adm" method="post">
            <div style="background: #d7f3f7;padding: 10px;border-radius: 5px;border: 3px solid #d8d8d8;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Administrador: </span>
                            <select class="form-control" name="id_administrador">
                                <?php
                                echo "<option value='99'>Todos los administradores...</option>";
                                $rqad1 = query("SELECT id,nombre FROM administradores WHERE estado='1' ORDER BY id ASC ");
                                while ($rqad2 = fetch($rqad1)) {
                                    $text_check = '';
                                    if ($id_administrador == $rqad2['id']) {
                                        $text_check = ' selected="selected" ';
                                    }
                                    echo "<option value='" . $rqad2['id'] . "' $text_check>" . $rqad2['nombre'] . "</option>";
                                }
                                $text_check = '';
                                if ($id_administrador == '0') {
                                    $text_check = ' selected="selected" ';
                                }
                                echo "<option value='0' $text_check>Sin administrador</option>";
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Semana: </span>
                            <select class="form-control" name="nro_semana">
                                <?php
                                $selected = '';
                                if ($nro_semana == '0') {
                                    $selected = ' selected="selected" ';
                                }
                                ?>
                                <option value='0' <?php echo $selected; ?>>TODO EL HISTORIAL</option>
                                <?php
                                for ($i = date("W"); $i > 0; $i--) {
                                    $selected = '';
                                    if ($nro_semana == $i) {
                                        $selected = ' selected="selected" ';
                                    }
                                    $primerDia = date('d/M/Y', strtotime('01/01 +' . ($i - 1) . ' weeks first day -3 day')) . '<br />';
                                    $ultimoDia = date('d/M/Y', strtotime('01/01 +' . ($i - 1) . ' weeks first day +3 day')) . '<br />';
                                    if($i==date("W")){
                                        echo "<option value='$i' $selected>ESTA SEMANA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ( $primerDia  -  $ultimoDia )</option>";
                                    }else{
                                        echo "<option value='$i' $selected>SEMANA $i &nbsp;&nbsp;&nbsp; ( $primerDia  -  $ultimoDia )</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Participante: </span>
                            <input type="text" name="input-buscador" value="<?php echo str_replace('%', ' ', $busqueda); ?>" class="form-control" placeholder="Ingrese nombre y/o apellidos ..."/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group col-sm-12">
                            <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Departamento: </span>
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
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <br>
                        <input type="submit" name="realizar-busqueda" value="BUSCAR" class="btn btn-primary btn-block active"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="rowNOT">
    <div class="col-md-12NOT">
        <div class="panelNOT">

            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="visible-lgNOT" style="font-size:10pt;">#</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Fecha registro</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Participante</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Curso</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Factura</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Pago</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Registrado por</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = fetch($resultado1)) {
                                ?>
                                <tr>
                                    <td class="visible-lgNOT">
                                        <?php echo $cnt--; ?>
                                        <br/>
                                        <br/>
                                        <b class="btn btn-default btn-xs" onclick="historial_participante('<?php echo $producto['dr_id_participante']; ?>');" data-toggle="modal" data-target="#MODAL-historial_participante">
                                            <i class="fa fa-list" style="color:#8f8f8f;"></i>
                                        </b>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo date("d/M/Y H:i", strtotime($producto['dr_fecha_registro'])); ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <span style="font-size:12pt;">
                                            <?php echo trim($producto['prefijo'] . ' ' . $producto['nombres'] . ' ' . $producto['apellidos']); ?>
                                        </span>
                                        <br/>
                                        <?php
                                        if ($producto['dr_estado_participante'] == '1') {
                                            echo "<b class='btn btn-xs btn-success active'>Habilitado</b>";
                                        } else {
                                            echo "<b class='btn btn-xs btn-danger active'>Des-habilitado</b>";
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <span style="font-size:11pt;"><?php echo $producto['dr_titulo_curso']; ?></span>
                                        <br/>
                                        <b><?php echo $producto['dr_departamento_curso']; ?></b>
                                        <br/>
                                        <?php echo date("d / M / Y", strtotime($producto['dr_fecha_curso'])); ?> &nbsp;&nbsp;&nbsp; <b>[<?php echo $producto['dr_numero_curso']; ?>]</b>
                                        &nbsp;&nbsp;&nbsp;
                                        <a href="cursos-participantes/<?php echo $producto['dr_id_curso']; ?>.adm" target="_blank" class="btn btn-info btn-xs">
                                            <i class='fa fa-list'></i>
                                        </a>
                                    </td>
                                    <td class="simple-td">
                                        <?php
                                        if ($producto['dr_id_emision_factura'] != '0') {
                                            $sw_existencia_facturas = true;
                                            echo '<i class="btn btn-xs btn-success" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(' . $producto['dr_id_participante'] . ');">Emitida</i>';
                                            echo '</br>';
                                        } else {
                                            if (strlen(trim($producto['dr_razon_social'] . $producto['dr_nit'])) <= 2) {
                                                echo '<i class="btn btn-xs btn-warning" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(' . $producto['dr_id_participante'] . ');">No solicitada</i></br>';
                                            } else {
                                                echo '<i class="btn btn-xs btn-danger" data-toggle="modal" data-target="#MODAL-emite-factura" onclick="emite_factura_p1(' . $producto['dr_id_participante'] . ');">No emitida</i></br>';
                                            }
                                        }
                                        echo $producto['dr_razon_social'];
                                        echo "<br/>";
                                        echo $producto['dr_nit'];
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        if ($producto['dr_monto_pago'] !== '' && $producto['dr_modo_pago'] != '0') {
                                            echo $producto['dr_monto_pago'];
                                            echo "<br/>";
                                            echo "<span style='color:gray;font-size:8pt;'>" . $producto['dr_modo_pago'] . "</span>";
                                            ?>
                                            <br/>
                                            <a data-toggle="modal" data-target="#MODAL-pago-participante" onclick="pago_participante('<?php echo $producto['dr_id_participante']; ?>');" class="btn btn-xs btn-default">
                                                <i class="fa fa-info"></i> INFO PAGO
                                            </a>
                                            <?php
                                        } else {
                                            ?>
                                            <a data-toggle="modal" data-target="#MODAL-pago-participante" onclick="pago_participante('<?php echo $producto['dr_id_participante']; ?>');" class="btn btn-xs btn-danger">
                                                <i class="fa fa-info"></i> SIN PAGO
                                            </a>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        if ($producto['dr_nombre_administrador'] == '') {
                                            echo "Sin administrador";
                                        } else {
                                            echo $producto['dr_nombre_administrador'];
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

                <div class="row">
                    <div class="col-md-12">
                        <ul class="pagination">
                            <?php
                            $urlget3 = '';
                            /* get 3 */
                            if (isset($get[3])) {
                                $urlget3 .= '/' . $get[3];
                            }
                            /* get 4 */
                            if (isset($get[4])) {
                                $urlget3 .= '/' . $get[4];
                            }
                            ?>

                            <li><a <?php echo loadpage('participantes-fuera-de-fecha/1/' . $postdata_throuhget); ?>>Primero</a></li>                           
                            <?php
                            $inicio_paginador = 1;
                            $fin_paginador = 15;
                            $total_cursos = ceil($total_registros / $registros_a_mostrar);

                            if ($vista > 10) {
                                $inicio_paginador = $vista - 5;
                                $fin_paginador = $vista + 10;
                            }
                            if ($fin_paginador > $total_cursos) {
                                $fin_paginador = $total_cursos;
                            }

                            if ($total_cursos > 1) {
                                for ($i = $inicio_paginador; $i <= $fin_paginador; $i++) {
                                    if ($vista == $i) {
                                        echo '<li class="active"><a ' . loadpage('participantes-fuera-de-fecha/' . $i . '/' . $postdata_throuhget) . '>' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a ' . loadpage('participantes-fuera-de-fecha/' . $i . '/' . $postdata_throuhget) . '>' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>                            
                            <li><a <?php echo loadpage('participantes-fuera-de-fecha/' . $total_cursos . '/' . $postdata_throuhget); ?>>Ultimo</a></li>
                        </ul>
                    </div><!-- /col-md-12 -->	
                </div>


            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="MODAL-proceso_habilitacion" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">HABILITACION DE PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <div id="AJAXBOX-proceso_habilitacion"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
<!-- END Modal edicion de participante -->

<!-- pago_participante -->
<script>
    function pago_participante(id_participante) {
        $("#ajaxloading-pago_participante").html('Cargando...');
        $("#ajaxbox-pago_participante").html("");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.pago_participante.php',
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


<!-- historial_participante -->
<script>
    function historial_participante(id_participante) {
        $("#AJAXCONTENT-historial_participante").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.historial_participante.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-historial_participante").html(data);
            }
        });
    }
</script>


<!-- Modal Facturacion -->
<div id="MODAL-emite-factura" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DATOS DE FACTURACION</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: EMITE FACTURA P1 -->
                <div id="ajaxloading-emite_factura_p1"></div>
                <div id="ajaxbox-emite_factura_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Facturacion -->

<script>
    function emite_factura_p1(id_participante) {
        $("#ajaxloading-emite_factura_p1").html('Cargando..');
        $("#ajaxbox-emite_factura_p1").html("");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_factura_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-emite_factura_p1").html("");
                $("#ajaxbox-emite_factura_p1").html(data);
            }
        });
    }
    function emite_factura_p2(id_participante) {
        var data_form = $("#form-emite-factura-" + id_participante).serialize();
        $("#ajaxloading-emite_factura_p2").html('Cargando..');
        $("#ajaxbox-emite_factura_p2").html("");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_factura_p2.php',
            data: data_form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-emite_factura_p2").html("");
                $("#ajaxbox-emite_factura_p2").html(data);
            }
        });
    }
</script>


<!-- proceso_habilitacion -->
<script>
    function proceso_habilitacion(id_participante, proceso) {
        $("#AJAXBOX-proceso_habilitacion").html("Procesando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-busca-participante.proceso_habilitacion.php',
            data: {id_participante: id_participante, proceso: proceso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXBOX-proceso_habilitacion").html(data);
            }
        });
    }
    function emite_certificado_p1(id_participante, nro_certificado) {
        $("#ajaxloading-emite_certificado_p1").html("Cargando..");
        $("#ajaxbox-emite_certificado_p1").html("");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_certificado_p1.php',
            data: {id_participante: id_participante, nro_certificado: nro_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-emite_certificado_p1").html("");
                $("#ajaxbox-emite_certificado_p1").html(data);
            }
        });
    }
    function emite_certificado_p2(id_participante, nro_certificado) {
        var receptor_de_certificado = $("#receptor_de_certificado").val();
        var id_certificado = $("#id_certificado").val();
        var id_curso = $("#id_curso").val();
        $("#ajaxloading-emite_certificado_p2").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_certificado_p2.php',
            data: {receptor_de_certificado: receptor_de_certificado, id_certificado: id_certificado, id_curso: id_curso, id_participante: id_participante, nro_certificado: nro_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-emite_certificado_p2").html("");
                $("#ajaxbox-emite_certificado_p2").html(data);
            }
        });
    }
</script>
<!-- ajax imprimir certificado individual -->
<script>
    function imprimir_certificado_individual(dat) {

        if (dat > 0) {
            $.ajax({
                url: 'pages/ajax/ajax.aux.cursos-participantes.imprimir_certificado_individual.php',
                data: {dat: dat},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    window.open(data, 'popup', 'width=700,height=500');
                }
            });
        } else {
            alert('Error en el ID de certificado');
        }
    }
</script>
<!-- ajax imprimir dos_certificados -->
<script>
    function imprimir_dos_certificados(dat) {
        var id_curso = $("#id_curso").val();
        $.ajax({
            url: 'pages/ajax/ajax.aux.cursos-participantes.imprimir_dos_certificados.php',
            data: {id_curso: id_curso, ids: dat},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                window.open(data, 'popup', 'width=700,height=500');
            }
        });
    }
</script>
<!-- ajax imprimir certificado individual -->
<script>
    function imprimir_certificado_individual(dat) {

        if (dat > 0) {
            $.ajax({
                url: 'pages/ajax/ajax.aux.cursos-participantes.imprimir_certificado_individual.php',
                data: {dat: dat},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    window.open(data, 'popup', 'width=700,height=500');
                }
            });
        } else {
            alert('Error en el ID de certificado');
        }
    }
</script>

<!--generar_reporte-->
<script>
function generar_reporte(data){
    var url = '<?php echo $dominio; ?>pages/ajax/ajax.impresion.participantes-fuera-de-fecha.exportar-lista.php?data='+data;
    window.open(url, 'popup', 'width=700,height=500');
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
