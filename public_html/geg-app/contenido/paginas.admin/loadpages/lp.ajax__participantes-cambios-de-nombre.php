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
/* vista */
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

$registros_a_mostrar = 100;
$start = ($vista - 1) * $registros_a_mostrar;

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
$rq_departamento = '';
$qr_nombre = ' 1 ';
$qr_administrador = '';
if (isset_post('id_departamento')) {
    $busqueda = str_replace(' ', '%', post('input-buscador'));
    $busqueda_id_departamento = post('id_departamento');
    $busqueda_prefijo = post('prefijo');
    $busqueda_id_administrador = post('id_administrador');

    if (strlen($busqueda) > 0) {
        $qr_nombre = " CONCAT(p.nombres,' ',p.apellidos) LIKE '%$busqueda%' ";
    }
    if ($busqueda_id_administrador!=='0') {
        $qr_administrador = " AND r.id_administrador='$busqueda_id_administrador' ";
    }
    if ($busqueda_id_departamento !== '0') {
        $rq_departamento = " AND p.id_curso IN (select id from cursos where id_ciudad IN (select id from ciudades where id_departamento='$busqueda_id_departamento') ) ";
    }
}

$data_required = "*,(pcn.fecha)dr_fecha_cambio,(r.fecha_registro)dr_fecha_registro,(c.fecha)dr_fecha_curso,(c.titulo)dr_titulo_curso,(select nombre from ciudades where id=c.id_ciudad)dr_departamento_curso,(c.estado)dr_estado_curso,(p.estado)dr_estado_participante,(select nombre from administradores where id=pcn.id_administrador)dr_nombre_administrador,(c.numero)dr_numero_curso,(p.id)dr_id_participante,(c.id)dr_id_curso";
$resultado1 = query("SELECT $data_required FROM participantes_cambios_de_nombre pcn INNER JOIN cursos_participantes p ON pcn.id_participante=p.id INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id INNER JOIN cursos c ON r.id_curso=c.id WHERE ( $qr_nombre ) $rq_departamento $qr_administrador ORDER BY pcn.fecha DESC LIMIT $start,$registros_a_mostrar");


//echo "SELECT $data_required FROM participantes_cambios_de_nombre pcn INNER JOIN cursos_participantes p ON pcn.id_participante=p.id INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id INNER JOIN cursos c ON r.id_curso=c.id WHERE DATE(r.fecha_registro)>c.fecha AND ( $qr_nombre $qr_prefijo ) $rq_departamento ORDER BY r.fecha_registro DESC LIMIT $start,$registros_a_mostrar";exit;
$resultado_b1 = query("SELECT count(*) AS total FROM participantes_cambios_de_nombre pcn INNER JOIN cursos_participantes p ON pcn.id_participante=p.id INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id INNER JOIN cursos c ON r.id_curso=c.id WHERE ( $qr_nombre ) $rq_departamento $qr_administrador ");
$resultado_b2 = mysql_fetch_array($resultado_b1);
$total_registros = $resultado_b2['total'];

$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
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
            <li><a <?php echo loadpage('cursos-listar'); ?>>Cursos</a></li>
            <li class="active">Cambios de nombre</li>
        </ul>
        <!--        <div class="form-group hiddn-minibar pull-right">
                    <a href="cursos-crear.adm" class='btn btn-success active'> <i class='fa fa-plus'></i> AGREGAR CURSO</a>
                </div>-->
        <h3 class="page-header"> CAMBIOS DE NOMBRE <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                CURSOS - Cambios de nombre
            </p>
        </blockquote>

        <form action="" method="post">
            <div class="col-md-6">
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
                    while ($rqd2 = mysql_fetch_array($rqd1)) {
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
                <select class="form-control" name="id_administrador">
                    <?php
                    echo "<option value='0'>Todos los administradores...</option>";
                    $rqad1 = query("SELECT id,nombre FROM administradores WHERE estado='1' ORDER BY id ASC ");
                    while ($rqad2 = mysql_fetch_array($rqad1)) {
                        $text_check = '';
                        if ($id_administrador == $rqad2['id']) {
                            $text_check = ' selected="selected" ';
                        }
                        echo "<option value='" . $rqad2['id'] . "' $text_check>" . $rqad2['nombre'] . "</option>";
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
    <div class="col-md-12NOT">
        <div class="panelNOT">

            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="visible-lgNOT" style="font-size:10pt;">#</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Participante</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Curso</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Cambio</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Administrador</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = mysql_fetch_array($resultado1)) {
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
                                        <br/>
                                        <?php echo date("d/M/Y H:i", strtotime($producto['dr_fecha_registro'])); ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <span style="font-size:11pt;"><?php echo $producto['dr_titulo_curso']; ?></span>
                                        <br/>
                                        <b><?php echo $producto['dr_departamento_curso']; ?></b>
                                        <br/>
                                        <?php echo date("d / M / Y", strtotime($producto['dr_fecha_curso'])); ?> &nbsp;&nbsp;&nbsp; <b>[<?php echo $producto['dr_numero_curso']; ?>]</b>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <i style="font-size:8pt;">ANTERIOR:<i/>
                                        <br/>
                                        <span style="font-size:11pt;"><?php echo $producto['nombre_anterior']; ?></span>
                                        <br/>
                                        <i style="font-size:8pt;">ACTUAL:<i/>
                                        <br/>
                                        <span style="font-size:11pt;"><?php echo $producto['nombre_actual']; ?></span>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        if ($producto['dr_nombre_administrador'] == '') {
                                            echo "Sin administrador";
                                        } else {
                                            echo $producto['dr_nombre_administrador'];
                                        }
                                        ?>
                                        <br/>
                                        <i style="font-size:8pt;">En fecha:<i/>
                                        <br/>
                                        <span style="font-size:11pt;"><?php echo date("d/M/Y",strtotime($producto['dr_fecha_cambio'])); ?></span>
                                    </td>
                                    <td class="visible-lgNOT" style="width:120px;">
                                        <?php
                                        if ($producto['dr_estado_curso'] == '1' || $producto['dr_estado_curso'] == '2') {
                                            ?>
                                            <a <?php echo loadpage('cursos-participantes/' . $producto['dr_id_curso']); ?> target="_blank" class="btn btn-warning btn-sm active">
                                                <i class='fa fa-eye'></i> PANEL DE CURSO
                                            </a>
                                            <br/>
                                            <br/>
                                            <?php
                                        }
                                        ?>
                                        <?php
                                        if ($producto['dr_estado_participante'] == '0' || true) {
                                            ?>
                                            <b class="btn btn-info btn-sm btn-block active" data-toggle="modal" data-target="#MODAL-proceso_habilitacion" onclick="proceso_habilitacion('<?php echo $producto['dr_id_participante']; ?>', 'view');">
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

                            <li><a <?php echo loadpage('participantes-cambios-de-nombre/1' . $urlget3); ?>>Primero</a></li>                           
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
                                        echo '<li class="active"><a ' . loadpage('participantes-cambios-de-nombre/' . $i . $urlget3) . '>' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a ' . loadpage('participantes-cambios-de-nombre/' . $i . $urlget3) . '>' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>                            
                            <li><a <?php echo loadpage('participantes-cambios-de-nombre/' . $total_cursos . $urlget3); ?>>Ultimo</a></li>
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




<!-- proceso_habilitacion -->
<script>
    function proceso_habilitacion(id_participante, proceso) {
        $("#AJAXBOX-proceso_habilitacion").html("Procesando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-busca-participante.proceso_habilitacion.php',
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
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.emite_certificado_p1.php',
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
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.emite_certificado_p2.php',
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
                url: 'contenido/paginas.admin/ajax/ajax.aux.cursos-participantes.imprimir_certificado_individual.php',
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
            url: 'contenido/paginas.admin/ajax/ajax.aux.cursos-participantes.imprimir_dos_certificados.php',
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
                url: 'contenido/paginas.admin/ajax/ajax.aux.cursos-participantes.imprimir_certificado_individual.php',
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
