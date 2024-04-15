<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* admisnitrador */
$id_administrador = administrador('id');

/* recepcion de datos POST */
$id_grupo = post('id_grupo');
$filtro = post('filtro');

$qr_estado = "";

switch ($filtro) {
    case 'conpago':
        $qr_filtro = " AND (id_proceso_registro IN (select id from cursos_proceso_registro where id_grupo='$id_grupo' and sw_pago_enviado='1') OR id_proceso_registro IN (select id from cursos_proceso_registro where id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') and sw_pago_enviado='1') ) ";
        $qr_estado = " AND estado='1' ";
        break;
    case 'sinpago':
        $qr_filtro = " AND (id_proceso_registro IN (select id from cursos_proceso_registro where id_grupo='$id_grupo' and sw_pago_enviado='0') OR id_proceso_registro IN (select id from cursos_proceso_registro where id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') and sw_pago_enviado='0'))";
        $qr_estado = " AND estado='1' ";
        break;
    case 'eliminados':
        $qr_filtro = '';
        $qr_estado = " AND estado='0' ";
        break;
    default:
        $qr_filtro = '';
        break;
}

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
if (isset_post('busc')) {
    $busqueda = post('busc');
    $qr_busqueda = " AND ( id='$busqueda' OR nombres LIKE '%$busqueda%' OR apellidos LIKE '%$busqueda%' OR correo LIKE '%$busqueda%' OR id_proceso_registro IN (select id from cursos_proceso_registro where codigo='$busqueda') ) ";
    $vista = 1;
}

/* query principal */
$resultado1 = query("SELECT DISTINCT nombres,apellidos FROM cursos_participantes WHERE id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') $qr_busqueda $qr_estado $qr_filtro ORDER BY id DESC ");

/* contador */
$cnt = mysql_num_rows($resultado1);
?>

<div class="table-responsive">
    <table class="table users-table table-condensed table-hover">
        <thead>
            <tr>
                <th style="padding-top: 2px;padding-bottom: 2px;">#</th>
                <th style="padding-top: 2px;padding-bottom: 2px;">Participante</th>
                <th style="padding-top: 2px;padding-bottom: 2px;" colspan="2">Cursos</th>
                <th style="padding-top: 2px;padding-bottom: 2px;">Acci&oacute;n</th>
            </tr>
        </thead>

        <tbody>
            <?php
            if (mysql_num_rows($resultado1) == 0) {
                echo "<p>No existen participantes registrados.</p>";
            }
            while ($participante = mysql_fetch_array($resultado1)) {
                $nombres_participante = $participante['nombres'];
                $apellidos_participante = $participante['apellidos'];
                ?>
                <tr id="ajaxbox-tr-participante-<?php echo $cnt; ?>">
                    <td>
                        <?php echo $cnt; ?>
                    </td>
                    <td onclick="check_participante('<?php echo $cnt; ?>');" style="cursor:pointer;">
                        <span style="font-size: 12pt;font-weight: bold;"><?php echo trim($nombres_participante . ' ' . $apellidos_participante); ?></span>
                        <br>
                        <?php
                        $rqcpacg1 = query("SELECT p.correo,p.celular,pr.imagen_deposito FROM cursos c INNER JOIN cursos_rel_agrupcursos r ON r.id_curso=c.id INNER JOIN cursos_participantes p ON c.id=p.id_curso INNER JOIN cursos_proceso_registro pr ON p.id_proceso_registro=pr.id WHERE p.nombres LIKE '$nombres_participante' AND p.apellidos LIKE '$apellidos_participante' AND r.id_grupo='$id_grupo' LIMIT 1 ");
                        $rqcpacg2 = mysql_fetch_array($rqcpacg1);
                        $imagen_deposito = $rqcpacg2['imagen_deposito'];
                        if ($imagen_deposito != '') {
                            ?>
                            <a onclick='window.open("https://cursos.bo/depositos/<?php echo $imagen_deposito; ?>.size=6.img", "ventana1", "width=800,height=800,scrollbars=NO");'>
                                <img src="depositos/<?php echo $imagen_deposito; ?>.size=3.img" style="height: 180px;
                                     border: 1px solid #7abce7;
                                     padding: 2px;
                                     margin: 5px;"/>
                            </a>
                            <?php
                        }
                        ?>
                    </td>
                    <td style="vertical-align: middle;">
                        <?php
                        $rqccg1 = query("SELECT c.id,c.titulo,c.fecha,pr.codigo,p.correo,p.celular,p.sw_cvirtual,pr.imagen_deposito,pr.sw_pago_enviado FROM cursos c INNER JOIN cursos_rel_agrupcursos r ON r.id_curso=c.id INNER JOIN cursos_participantes p ON c.id=p.id_curso INNER JOIN cursos_proceso_registro pr ON p.id_proceso_registro=pr.id WHERE p.nombres LIKE '$nombres_participante' AND p.apellidos LIKE '$apellidos_participante' AND r.id_grupo='$id_grupo' ");
                        //$rqccg1 = query("SELECT id,titulo,fecha FROM cursos WHERE id IN (SELECT id_curso FROM cursos_participantes WHERE (nombres LIKE '$nombres_participante' AND apellidos LIKE '$apellidos_participante') AND id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') )");
                        $nct_cursos = mysql_num_rows($rqccg1);
                        ?>
                        <b style="color: #3393d4;font-size: 17pt;"><?php echo $nct_cursos; ?></b>
                    </td>
                    <td>
                        <table class="table table-bordered table-hover" style="margin: 10px 0px;border: 2px solid #bccdd8;background: #FFF;">
                            <?php
                            while ($curso = mysql_fetch_array($rqccg1)) {
                                ?>
                                <tr>
                                    <?php
                                    if ($curso['sw_pago_enviado'] == '1') {
                                        $style_aux1 = 'border-right:3px solid #aad178;';
                                    } else {
                                        $style_aux1 = '';
                                    }
                                    ?>
                                    <td style="<?php echo $style_aux1; ?>">
                                        <?php echo $curso['codigo']; ?> 
                                    </td>
                                    <td>
                                        <?php echo $curso['titulo']; ?> 
                                    </td>
                                    <?php
                                    if ($curso['sw_cvirtual'] == 1) {
                                        ?>
                                        <td style="background: #62bb62;color: white;font-weight: bold;text-align: center;">
                                            C-Virtual
                                        </td>
                                        <?php
                                    } else {
                                        ?>
                                        <td style="background: #EEE;color: gray;font-weight: bold;text-align: center;">
                                            Sin acceso
                                        </td>
                                        <?php
                                    }
                                    ?>
                                    <td>
                                        <?php echo $curso['correo']; ?> 
                                    </td>
                                    <td>
                                        <?php echo $curso['celular']; ?> 
                                    </td>
                                    <td>
                                        <?php
                                        if ($curso['imagen_deposito'] != '') {
                                            ?>
                                            <a onclick='window.open("https://cursos.bo/depositos/<?php echo $curso['imagen_deposito']; ?>.size=6.img", "ventana1", "width=800,height=800,scrollbars=NO");' class="btn btn-default">IMAGEN</a>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <b class="btn btn-sm btn-info" onclick="cerificados_grupo('<?php echo $nombres_participante; ?>', '<?php echo $apellidos_participante; ?>');" data-toggle="modal" data-target="#MODAL-cerificados_grupo">
                            CERTIFICADOS
                        </b>
                        <br/>
                        <b class="btn btn-sm btn-warning" onclick="procesos_registro('<?php echo $nombres_participante; ?>', '<?php echo $apellidos_participante; ?>');" data-toggle="modal" data-target="#MODAL-procesos_registro">
                            REGISTRO
                        </b>
                        <br/>
                        <b class="btn btn-sm btn-success" onclick="acceso_cursos_virtuales('<?php echo $nombres_participante; ?>', '<?php echo $apellidos_participante; ?>');" data-toggle="modal" data-target="#MODAL-acceso_cursos_virtuales">
                            C-VIRTUAL
                        </b>
                        <br/>
                        <b class="btn btn-sm btn-default" onclick="edita_participante('<?php echo $nombres_participante; ?>', '<?php echo $apellidos_participante; ?>');" data-toggle="modal" data-target="#MODAL-edita_participante">
                            EDICI&Oacute;N PART.
                        </b>
                    </td>
                </tr>
                <?php
                $cnt--;
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function cerificados_grupo(nombres, apellidos) {
        $("#AJAXCONTENT-cerificados_grupo").html('Enviando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos-participantes.cerificados_grupo.php',
            data: {id_grupo: '<?php echo $id_grupo; ?>', nombres: nombres, apellidos: apellidos},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-cerificados_grupo").html(data);
            }
        });
    }
</script>

<script>
    function procesos_registro(nombres, apellidos) {
        $("#AJAXCONTENT-procesos_registro").html('Enviando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos-participantes.procesos_registro.php',
            data: {id_grupo: '<?php echo $id_grupo; ?>', nombres: nombres, apellidos: apellidos},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-procesos_registro").html(data);
            }
        });
    }
</script>


<script>
    function acceso_cursos_virtuales(nombres, apellidos) {
        $("#AJAXCONTENT-acceso_cursos_virtuales").html('Enviando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos-participantes.acceso_cursos_virtuales.php',
            data: {id_grupo: '<?php echo $id_grupo; ?>', nombres: nombres, apellidos: apellidos},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-acceso_cursos_virtuales").html(data);
            }
        });
    }
</script>

<script>
    function edita_participante(nombres, apellidos) {
        $("#AJAXCONTENT-edita_participante").html('Enviando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos-participantes.edita_participante.php',
            data: {id_grupo: '<?php echo $id_grupo; ?>', nombres: nombres, apellidos: apellidos},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-edita_participante").html(data);
            }
        });
    }
</script>


<!-- Modal edita_participante -->
<div id="MODAL-edita_participante" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 1000px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EDICI&Oacute;N DE PARTICIPANTE</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-edita_participante"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="MODAL-cerificados_grupo" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 1000px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CERTIFICADOS</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-cerificados_grupo"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal procesos_registro -->
<div id="MODAL-procesos_registro" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 1000px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PROCESOS DE REGISTRO</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-procesos_registro"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal acceso_cursos_virtuales -->
<div id="MODAL-acceso_cursos_virtuales" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 1000px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CURSOS VIRTUALES</h4>
            </div>
            <div class="modal-body">
                <div id="AJAXCONTENT-acceso_cursos_virtuales"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>