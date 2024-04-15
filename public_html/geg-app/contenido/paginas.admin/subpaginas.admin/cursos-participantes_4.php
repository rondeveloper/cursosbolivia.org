<?php
$mensaje = "";

/* id de curso */
$id_curso = 0;
if (isset($get[2])) {
    $id_curso = (int) $get[2];
}

/* id de turno */
$id_turno_curso = 0;
$qr_turno = '';
if (isset($get[3])) {
    $id_turno_curso = (int) $get[3];
    $qr_turno = " AND id_turno='$id_turno_curso' ";
}

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
if (isset_post('input-buscador')) {
    $busqueda = post('input-buscador');
    $qr_busqueda = " AND ( id='$busqueda' OR nombres LIKE '%$busqueda%' OR apellidos LIKE '%$busqueda%' OR correo LIKE '%$busqueda%' ) ";
    $vista = 1;
}

/* sw de habilitacion de procesos */
$rqvhc1 = query("SELECT estado FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqvhc2 = mysql_fetch_array($rqvhc1);
if ($rqvhc2['estado'] == '1' || $rqvhc2['estado'] == '2') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}

$resultado1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' $qr_busqueda $qr_turno ORDER BY id DESC ");

//datos del curso
$rqc1 = query("SELECT titulo,titulo_identificador,fecha,imagen,costo,id_certificado,id_certificado_2,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = mysql_fetch_array($rqc1);
$nombre_del_curso = $rqc2['titulo'];
$titulo_identificador_del_curso = $rqc2['titulo_identificador'];
$fecha_del_curso = $rqc2['fecha'];
$codigo_de_certificado_del_curso = $rqc2['codigo_certificado'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
if ($rqc2['imagen'] !== '') {
    $url_imagen_del_curso = "https://www.infosicoes.com/paginas/" . $rqc2['imagen'] . ".size=4.img";
} else {
    $url_imagen_del_curso = "https://www.infosicoes.com/images/banner-cursos.png.size=4.img";
}
$costo_curso = $rqc2['costo'];


$cnt = mysql_num_rows($resultado1);
?>
<style>
    .modal-header{
        background: #00789f;
    }
    .modal-title{
        color:#FFF;
    }
    .modal-footer .btn-default{
        background: #00789f;
        color: #FFF;
    }
</style>



<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="cursos-listar.adm">Cursos</a></li>
            <li class="active">Participantes</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <a href="cursos/<?php echo $titulo_identificador_del_curso; ?>.html" target="_blank" class="btn btn-sm btn-info active"><i class="fa fa-eye"></i> VISUALIZAR CURSO</a>
            &nbsp;|&nbsp;
            <a href="cursos-editar/<?php echo $id_curso; ?>.adm" class="btn btn-sm btn-info active"><i class="fa fa-edit"></i> EDITAR CURSO</a>
            &nbsp;|&nbsp;
            <a href="cursos-listar.adm" class="btn btn-sm btn-info active"><i class="fa fa-list"></i> LISTAR CURSOS</a>
        </div>
        <h4 class="page-header"> PARTICIPANTES DEL CURSO <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h4>
        <blockquote class="page-information hidden">
            <p>
                Listado de cursos de Cursos
            </p>
        </blockquote>

        <form action="" method="post">
            <div class="input-group col-sm-12">
                <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Buscador: </span>
                <input type="text" name="input-buscador" value="<?php echo $busqueda; ?>" class="form-control" placeholder="Nombres / Apellidos / Celular / Correo ..."/>
            </div>
        </form>
    </div>
</div>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <h4>
                <i class='btn btn-success active'><?php echo date("d  M  y", strtotime($fecha_del_curso)); ?></i> | 
                <?php echo $nombre_del_curso; ?>

                <?php
                $sw_turnos = false;
                $rqtc1 = query("SELECT id,titulo FROM cursos_turnos WHERE id_curso='$id_curso' ");
                if (mysql_num_rows($rqtc1) > 0) {
                    $aux_class_tc = "btn-info";
                    if ($id_turno_curso !== 0) {
                        $aux_class_tc = "btn-primary";
                    }
                    ?>
                    <span class="pull-right">
                        <a onclick="lista_participantes(<?php echo $id_curso; ?>, 0);
                                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);" class="btn btn-xs <?php echo $aux_class_tc; ?> active"><i class="fa fa-clock-o"></i> Todos los turnos</a>
                           <?php
                           $turno = array();
                           $turno[0] = 'Sin turno';
                           $sw_turnos = true;
                           while ($rqtc2 = mysql_fetch_array($rqtc1)) {
                               $turno[$rqtc2['id']] = $rqtc2['titulo'];
                               $aux_class_tc = "btn-primary";
                               if ($id_turno_curso == $rqtc2['id']) {
                                   $aux_class_tc = "btn-info";
                               }
                               ?>
                            <a onclick="lista_participantes(<?php echo $id_curso; ?>,<?php echo $rqtc2['id']; ?>);
                                    lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);" class="btn btn-xs <?php echo $aux_class_tc; ?> active"><i class="fa fa-clock-o"></i> <?php echo $rqtc2['titulo']; ?></a>
                               <?php
                           }
                           ?>
                    </span>
                    <?php
                }
                ?>
            </h4>

            <?php
            if ($sw_habilitacion_procesos) {
                ?>

                <div class="panel-body" style="padding-bottom: 0px;padding-top: 0px;">
                    <form id="form-agrega-participante" name="form_add_participante">
                        <table class="table" style="background:#dadada;border-radius:5px;padding-bottom: 0px;">
                            <input type='hidden' name='id_curso' value='<?php echo $id_curso; ?>'/>
                            <tr>
                                <td class="text-right" style="width: 120px;"><input type="text" name="prefijo" class="form-control" style="width:110px;" placeholder="Sr. / Dr. / Arq. / Ing. " /></td>
                                <td><input type="text" name="nombres" class="form-control" placeholder="Nombres" id="f-nom" required=""/></td>
                                <td><input type="text" name="apellidos" class="form-control" placeholder="Apellidos" id="f-ape" required=""/></td>
                                <td><input type="text" name="celular" class="form-control" placeholder="Celular" /></td>
                                <td><b class="btn btn-success active" onclick="agrega_participante();">AGREGAR PARTICIPANTE</b></td>
                            </tr>
                            <tr>
                                <td class="text-right" style="width: 120px;"><input type="number" name="monto_pago" class="form-control" style="width:110px;" placeholder="Monto..." required="" value="<?php echo $costo_curso; ?>"/></td>
                                <td><input type="text" name="razon_social" class="form-control" placeholder="Razon Social" id="f-raz" /></td>
                                <td><input type="text" name="nit" class="form-control" placeholder="NIT" /></td>
                                <td><input type="text" name="correo" class="form-control" placeholder="Correo" /></td>
                                <td>
                                    <?php
                                    $rqtc1 = query("SELECT * FROM cursos_turnos WHERE id_curso='$id_curso' ");
                                    if (mysql_num_rows($rqtc1) > 0) {
                                        ?>
                                        <select name="id_turno" class="form-control" style='max-width: 200px;'>
                                            <?php
                                            while ($rqtc2 = mysql_fetch_array($rqtc1)) {
                                                $aux_t_selected = '';
                                                if ($id_turno_curso == $rqtc2['id']) {
                                                    $aux_t_selected = ' selected="selected" ';
                                                }
                                                ?>
                                                <option value="<?php echo $rqtc2['id']; ?>" <?php echo $aux_t_selected; ?> ><?php echo $rqtc2['titulo']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <br/>
                                        <?php
                                    } else {
                                        echo "<input type='hidden' id='id_turno' value='0'/>";
                                    }
                                    ?>
                                    <b onclick="nombreFact();" class="btn btn-xs btn-default"><i class="fa fa-star"></i> NombreFact</b>
                                    &nbsp;&nbsp;
                                    <label for="impficha">
                                        <input type="checkbox" name="impficha" value="1" id="impficha"/> Imp. ficha
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>

                <!-- DIV CONTENT AJAX :: AGREGA PARTICIPANTE -->
                <div id="ajaxloading-agrega_participante"></div>
                <div id="ajaxbox-agrega_participante">
                    <!-- .... -->
                </div>

                <script>
                    function nombreFact() {
                        var nom = document.getElementById("f-nom").value;
                        var ape = document.getElementById("f-ape").value;
                        document.getElementById("f-raz").value = nom + " " + ape;
                    }
                </script>
                <script>
                    function nombreFact2(id) {
                        var nom = document.getElementById("f-nom-" + id).value;
                        var ape = document.getElementById("f-ape-" + id).value;
                        document.getElementById("f-raz-" + id).value = nom + " " + ape;
                    }
                </script>

                <?php
            }
            ?>



            <?php
            if (mysql_num_rows($resultado1) == 0) {
                echo "<p>No se registraron participantes para este curso</p>";
            }
            ?>

            <div class="panel-body" style="padding-top: 0px;">


                <!-- DIV CONTENT AJAX :: LISTADO DE PARTICIPANTES -->
                <div id="ajaxloading-lista_participantes"></div>
                <div id="ajaxbox-lista_participantes">
                    ....
                </div>


                <!-- DIV CONTENT AJAX :: LISTADO DE PARTICIPANTES ELIMINADOS -->
                <div id="ajaxloading-lista_participantes_eliminados"></div>
                <div id="ajaxbox-lista_participantes_eliminados">
                    ....
                </div>


            </div>
        </div>
    </div>
</div>

<script>
    function show_v_objeto(objeto, id) {
        $.ajax({
            url: 'contenido/cursos.admin/ajax/ajax.show_v_objeto.php',
            data: {id: id, objeto: objeto},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#show-v-div-" + objeto + "-" + id).html(data);
            }
        });
    }
</script>

<!-- ajax de emision de certificados -->





<!-- ajax habilitacion de participante -->
<script>
    function habilitar_participante(dat) {

        if (confirm("Desea habilitar nuevamente al participante?")) {

            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.instant.curso_habilitar_participante.php',
                data: {dat: dat},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    //$("#ajaxbox-tr-participante-" + dat).html(data);
                    alert('Participante-habilitado');
                    location.href = 'cursos-participantes/<?php echo $id_curso; ?>.adm';
                }
            });

        }

    }
</script>


<!-- ajax imprimir certificados multiple -->
<script>
    function imprimir_certificados_multiple(dat) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.aux.cursos-participantes.imprimir_certificados_multiple.php',
            data: {id_curso: <?php echo $id_curso; ?>, nro_certificado: dat, ids: ids.join(',')},
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

<!-- ajax emision certificados masivamente -->
<script>
    function emision_multiple_certificados() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_certificados.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados masivamente Certificado 2 -->
<script>
    function emision_multiple_c2_certificados() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_c2_certificados.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion -->
<script>
    function emision_certificados_a_eleccion() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-a-eleccion").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion p2 -->
<script>
    function emision_certificados_a_eleccion_p2() {
        var ids;
        var id_modelo_certificado;
        id_modelo_certificado = $("#id-modelo-certificado").val();
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion_p2.php',
            data: {dat: ids.join(','), id_modelo_certificado: id_modelo_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-a-eleccion").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion p3 -->
<script>
    function emision_certificados_a_eleccion_p3() {
        var ids;
        var id_modelo_certificado;
        id_modelo_certificado = $("#id-modelo-certificado").val();
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion_p3.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso, id_modelo_certificado: id_modelo_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-a-eleccion").html(data);
            }
        });
    }
</script>



<!-- ajax emision certificados a eleccion -->
<script>
    function imprime_certificados_a_eleccion() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_impresion_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.impresion_certificados_a_eleccion.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_impresion_certificados-a-eleccion").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion p2 -->
<script>
    function imprime_certificados_a_eleccion_p2() {
        var ids;
        var id_modelo_certificado;
        id_modelo_certificado = $("#id-modelo-certificado-imp").val();
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_impresion_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.impresion_certificados_a_eleccion_p2.php',
            data: {dat: ids.join(','), id_modelo_certificado: id_modelo_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_impresion_certificados-a-eleccion").html(data);
            }
        });
    }
</script>




<!-- ajax emision certificados masivamente p2 -->
<script>
    function emision_multiple_certificados_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_certificados_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados masivamente p2 Certificado 2 -->
<script>
    function emision_multiple_c2_certificados_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_2_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_c2_certificados_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-multiple").html(data);
            }
        });
    }
</script>




<!-- ajax imprimir facturas masivamente -->
<script>
    function imprimir_facturas() {

        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();

        //alert('IDS: ' + ids.join(','));

        window.open('http://www.infosicoes.com/contenido/librerias/fpdf/tutorial/factura-1-masivo.php?id_participantes=' + ids.join(','), 'popup', 'width=700,height=500');


    }
</script>

<!-- ajax emision facturas masivamente -->
<script>
    function emision_multiple_facturas() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_facturas-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_facturas.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_facturas-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax emision facturas masivamente p2 -->
<script>
    function emision_multiple_facturas_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_facturas-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_facturas_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_facturas-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax procesar_certificados_secundarios -->
<script>
    function procesar_certificados_secundarios(id_participante) {
        var id_curso = '<?php echo $id_curso; ?>';
        $("#BOX-AJAX-certificados-secundarios").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.procesar_certificados_secundarios.php',
            data: {id_participante: id_participante, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#BOX-AJAX-certificados-secundarios").html(data);
            }
        });
    }
</script>

<!-- ajax procesar_certificados_secundarios p2 -->
<script>
    function procesar_certificados_secundarios_p2() {
        var arraydata = $("#formajax1").serialize();
        $("#BOX-AJAX-certificados-secundarios").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.procesar_certificados_secundarios_p2.php',
            data: arraydata,
            type: 'POST',
            success: function(data) {
                $("#BOX-AJAX-certificados-secundarios").html(data);
            }
        });
    }
</script>




<!-- MODALS -->

<!-- Modal edicion de participante -->
<div id="MODAL-edicion-participante" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EDICI&Oacute;N DE PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: EDCICION DE PARTICIPANTE P1 -->
                <div id="ajaxloading-edita_participante_p1"></div>
                <div id="ajaxbox-edita_participante_p1">
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


<!-- Modal emision de Certificado -->
<div id="MODAL-emite-certificado" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION DE CERTIFICADO</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: EMITE CERTIFICADO P1 -->
                <div id="ajaxloading-emite_certificado_p1"></div>
                <div id="ajaxbox-emite_certificado_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal emision de Certificado -->


<!-- Modal Datos de registro -->
<div id="MODAL-datos-registro" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DATOS DE REGISTRO</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: DATOS DE REGISTRO -->
                <div id="ajaxloading-datos_registro"></div>
                <div id="ajaxbox-datos_registro">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Datos de registro -->


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

<!-- Modal Elimina participante -->
<div id="MODAL-elimina-participante" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content panel-danger">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ELIMINACION DE PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: ELIMINA PARTICIPANTE P1 -->
                <div id="ajaxloading-elimina_participante_p1"></div>
                <div id="ajaxbox-elimina_participante_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Elimina participante -->


<!-- Modal Habilita participante -->
<div id="MODAL-habilita-participante" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">HABILITACION DE PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: HABILITACION PARTICIPANTE P1 -->
                <div id="ajaxloading-habilita_participante_p1"></div>
                <div id="ajaxbox-habilita_participante_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Elimina participante -->



<!-- Modal emitir certificados - multiple -->
<div id="MODAL-emite-certificados-multiple" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION MULTIPLE DE CERTIFICADOS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <h5 class="text-center">
                    Emisi&oacute;n de certificados para
                </h5>
                <div class="text-center" id='box-modal_emision_certificados-multiple'>
                    <!-- ajax content -->
                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal emitir certificados - multiple -->

<!-- Modal emitir certificados a eleccion -->
<div id="MODAL-emite-certificados-a-eleccion" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION DE CERTIFICADOS A ELECCION</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="text-center" id='box-modal_emision_certificados-a-eleccion'>

                    <!-- ajax content -->

                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal emitir certificados a eleccion -->

<!-- Modal imprime certificados a eleccion -->
<div id="MODAL-imprime-certificados-a-eleccion" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">IMRPESION DE CERTIFICADOS A ELECCION</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="text-center" id='box-modal_impresion_certificados-a-eleccion'>

                    <!-- ajax content -->

                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal imprime certificados a eleccion -->

<!-- Modal emitir facturas - multiple -->
<div id="MODAL-emite-facturas-multiple" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION MULTIPLE DE FACTURAS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <h5 class="text-center">
                    Emisi&oacute;n de facturas para
                </h5>
                <div class="text-center" id='box-modal_emision_facturas-multiple'>
                    <!-- ajax content -->
                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal emitir facturas - multiple -->


<!-- Modal-certificados-secundarios -->
<div id="MODAL-certificados-secundarios" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION DE CERTIFICADOS SECUNDARIOS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                        <br/>
                    </div>
                    <div class="col-md-6 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div id="BOX-AJAX-certificados-secundarios">
                        <!-- ajax content -->

                    </div>
                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-certificados-secundarios -->

<!-- Modal-generar reporte -->
<div id="MODAL-generar-reporte" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">GENERAR REPORTE</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 text-left">
                        <b>CURSO:</b> &nbsp; <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> &nbsp; <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-4 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-12 text-left">
                        <p>Selecciona los campos necesarios para el reporte.</p>

                        <table class="table table-striped">
                            <tr>
                                <td>Prefijo</td>
                                <td class="text-center">
                                    <label for="f0a"><input name="data_report_prefijo" type="radio" value="1" id="f0a"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f0b"><input name="data_report_prefijo" type="radio" value="0" id="f0b" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Nombres</td>
                                <td class="text-center">
                                    <label for="f1"><input name="data_report_nombres" type="radio" value="1" id="f1" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f2"><input name="data_report_nombres" type="radio" value="0" id="f2"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Apellidos</td>
                                <td class="text-center">
                                    <label for="f3"><input name="data_report_apellidos" type="radio" value="1" id="f3" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f4"><input name="data_report_apellidos" type="radio" value="0" id="f4"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Datos de Facturaci&oacute;n</td>
                                <td class="text-center">
                                    <label for="f5"><input name="data_report_datosfacturacion" type="radio" value="1" id="f5" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f6"><input name="data_report_datosfacturacion" type="radio" value="0" id="f6"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Datos de Contacto</td>
                                <td class="text-center">
                                    <label for="f7"><input name="data_report_datoscontacto" type="radio" value="1" id="f7" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f8"><input name="data_report_datoscontacto" type="radio" value="0" id="f8"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Modo de registro</td>
                                <td class="text-center">
                                    <label for="f7a"><input name="data_report_modoregistro" type="radio" value="1" id="f7a"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f8a"><input name="data_report_modoregistro" type="radio" value="0" id="f8a" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Fecha de registro</td>
                                <td class="text-center">
                                    <label for="f7b"><input name="data_report_fecharegistro" type="radio" value="1" id="f7b"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f8b"><input name="data_report_fecharegistro" type="radio" value="0" id="f8b" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Firma</td>
                                <td class="text-center">
                                    <label for="f9"><input name="data_report_firma" type="radio" value="1" id="f9" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f10"><input name="data_report_firma" type="radio" value="0" id="f10"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Participantes eliminados</td>
                                <td class="text-center">
                                    <label for="f9a"><input name="data_report_eliminados" type="radio" value="1" id="f9a"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f10a"><input name="data_report_eliminados" type="radio" value="0" id="f10a" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                        </table>

                        <hr/>

                        <div class="panel-footer text-center">
                            <button class="btn btn-default active" onclick="generar_reporte('impresion');">IMPRIMIR</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="btn btn-success active" onclick="generar_reporte('excel');">EXCEL</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="btn btn-info active" onclick="generar_reporte('word');">WORD</button>
                        </div>

                    </div>
                </div>
                <hr/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-generar reporte -->

<script>
    function generar_reporte(formato) {

        var data_report_prefijo = "0";
        var array_data_report_prefijo = document.getElementsByName("data_report_prefijo");
        for (var i = 0; i < array_data_report_prefijo.length; i++) {
            if (array_data_report_prefijo[i].checked)
                data_report_prefijo = array_data_report_prefijo[i].value;
        }

        var data_report_nombres = "0";
        var array_data_report_nombres = document.getElementsByName("data_report_nombres");
        for (var i = 0; i < array_data_report_nombres.length; i++) {
            if (array_data_report_nombres[i].checked)
                data_report_nombres = array_data_report_nombres[i].value;
        }

        var data_report_apellidos = "0";
        var array_data_report_apellidos = document.getElementsByName("data_report_apellidos");
        for (var i = 0; i < array_data_report_apellidos.length; i++) {
            if (array_data_report_apellidos[i].checked)
                data_report_apellidos = array_data_report_apellidos[i].value;
        }

        var data_report_datosfacturacion = "0";
        var array_data_report_datosfacturacion = document.getElementsByName("data_report_datosfacturacion");
        for (var i = 0; i < array_data_report_datosfacturacion.length; i++) {
            if (array_data_report_datosfacturacion[i].checked)
                data_report_datosfacturacion = array_data_report_datosfacturacion[i].value;
        }

        var data_report_datoscontacto = "0";
        var array_data_report_datoscontacto = document.getElementsByName("data_report_datoscontacto");
        for (var i = 0; i < array_data_report_datoscontacto.length; i++) {
            if (array_data_report_datoscontacto[i].checked)
                data_report_datoscontacto = array_data_report_datoscontacto[i].value;
        }

        var data_report_modoregistro = "0";
        var array_data_report_modoregistro = document.getElementsByName("data_report_modoregistro");
        for (var i = 0; i < array_data_report_modoregistro.length; i++) {
            if (array_data_report_modoregistro[i].checked)
                data_report_modoregistro = array_data_report_modoregistro[i].value;
        }

        var data_report_fecharegistro = "0";
        var array_data_report_fecharegistro = document.getElementsByName("data_report_fecharegistro");
        for (var i = 0; i < array_data_report_fecharegistro.length; i++) {
            if (array_data_report_fecharegistro[i].checked)
                data_report_fecharegistro = array_data_report_fecharegistro[i].value;
        }

        var data_report_firma = "0";
        var array_data_report_firma = document.getElementsByName("data_report_firma");
        for (var i = 0; i < array_data_report_firma.length; i++) {
            if (array_data_report_firma[i].checked)
                data_report_firma = array_data_report_firma[i].value;
        }

        var data_report_eliminados = "0";
        var array_data_report_eliminados = document.getElementsByName("data_report_eliminados");
        for (var i = 0; i < array_data_report_eliminados.length; i++) {
            if (array_data_report_eliminados[i].checked)
                data_report_eliminados = array_data_report_eliminados[i].value;
        }

        var data_required = 'data_report_nombres=' + data_report_nombres + '&data_report_apellidos=' + data_report_apellidos + '&data_report_datosfacturacion=' + data_report_datosfacturacion + '&data_report_datoscontacto=' + data_report_datoscontacto + '&data_report_firma=' + data_report_firma + '&data_report_prefijo=' + data_report_prefijo + '&data_report_fecharegistro=' + data_report_fecharegistro + '&data_report_modoregistro=' + data_report_modoregistro + '&data_report_eliminados=' + data_report_eliminados;

        window.open('http://www.infosicoes.com/contenido/paginas.admin/ajax/ajax.impresion.cursos-participantes.exportar-lista.php?id_curso=<?php echo $id_curso; ?>&' + formato + '=true&' + data_required, 'popup', 'width=700,height=500');

    }
</script>



<!-- envio de factura -->
<script>
    function enviar_factura(id) {

        var email = $("#correo-de-envio-" + id).val();
        $("#box-modal_envia-factura-" + id).html("Enviando correo...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.instant.enviar_factura.php?nro_factura=' + id + '&email_a_enviar=' + email,
            data: {id: id},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_envia-factura-" + id).html(data);
            }
        });
    }
</script>
<script>
    function enviar_factura2(id) {

        var email = $("#correo-de-envio-" + id).val();
        $("#ffl-" + id).html("Enviando correo...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.instant.enviar_factura.php?nro_factura=' + id + '&email_a_enviar=' + email,
            data: {id: id},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ffl-" + id).html('<i class="btn btn-xs btn-default"><b class="fa fa-send"></b> Enviado!</i>');
            }
        });
    }
</script>





<!--FUNCIONES AJAX DE CONTEXTO-->
<script>
    function lista_participantes(id_curso, id_turno) {
        $("#ajaxloading-lista_participantes").html("Actualizando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.lista_participantes.php',
            data: {id_curso: id_curso, id_turno: id_turno},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes").html("");
                $("#ajaxbox-lista_participantes").html(data);
            }
        });
    }
    function lista_participantes_INICIO(id_curso, id_turno) {
        $("#ajaxloading-lista_participantes").html(text__loading_uno);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.lista_participantes.php',
            data: {id_curso: id_curso, id_turno: id_turno},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes").html("");
                $("#ajaxbox-lista_participantes").html(data);
            }
        });
    }
    function lista_participantes_eliminados(id_curso, id_turno) {
        $("#ajaxloading-lista_participantes_eliminados").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.lista_participantes_eliminados.php',
            data: {id_curso: id_curso, id_turno: id_turno},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes_eliminados").html("");
                $("#ajaxbox-lista_participantes_eliminados").html(data);
            }
        });
    }
    function edita_participante_p1(id_participante) {
        $("#ajaxloading-edita_participante_p1").html(text__loading_dos);
        $("#ajaxbox-edita_participante_p1").html("");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.edita_participante_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-edita_participante_p1").html("");
                $("#ajaxbox-edita_participante_p1").html(data);
            }
        });
    }
    function edita_participante_p2(id_participante) {
        $("#ajaxloading-edita_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.edita_participante_p2.php',
            data: $("#form-participante-" + id_participante).serialize(),
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-edita_participante_p2").html("");
                $("#ajaxbox-edita_participante_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
    function emite_certificado_p1(id_participante, nro_certificado) {
        $("#ajaxloading-emite_certificado_p1").html(text__loading_dos);
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

        var receptor_de_certificado = $("#receptor_de_certificado-" + id_participante).val();
        var id_certificado = $("#id_certificado-" + id_participante).val();
        var id_curso = $("#id_curso-" + id_participante).val();

        $("#ajaxloading-emite_certificado_p2").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.emite_certificado_p2.php',
            data: {receptor_de_certificado: receptor_de_certificado, id_certificado: id_certificado, id_curso: id_curso, id_participante: id_participante, nro_certificado: nro_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                if (nro_certificado === 1) {
                    $("#box-modal_emision_certificado-button-" + id_participante).html('<i class="btn-sm btn-default active">Emitido</i>');
                } else {
                    $("#box-modal_emision_certificado-button-2-" + id_participante).html('<i class="btn-sm btn-default active">Emitido</i>');
                }
                $("#ajaxloading-emite_certificado_p2").html("");
                $("#ajaxbox-emite_certificado_p2").html(data);
            }
        });
    }
    function datos_registro(id_participante) {
        $("#ajaxbox-datos_registro").html("");
        $("#ajaxloading-datos_registro").html(text__loading_uno);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.datos_registro.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-datos_registro").html("");
                $("#ajaxbox-datos_registro").html(data);
            }
        });
    }
    function emite_factura_p1(id_participante) {
        $("#ajaxloading-emite_factura_p1").html(text__loading_dos);
        $("#ajaxbox-emite_factura_p1").html("");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.emite_factura_p1.php',
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
        $("#ajaxloading-emite_factura_p2").html(text__loading_dos);
        $("#ajaxbox-emite_factura_p2").html("");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.emite_factura_p2.php',
            data: data_form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-emite_factura_p2").html("");
                $("#ajaxbox-emite_factura_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
    function elimina_participante_p1(id_participante) {
        $("#ajaxbox-elimina_participante_p1").html("");
        $("#ajaxloading-elimina_participante_p1").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.elimina_participante_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-elimina_participante_p1").html("");
                $("#ajaxbox-elimina_participante_p1").html(data);
            }
        });
    }
    function elimina_participante_p2(id_participante) {
        $("#ajaxbox-elimina_participante_p2").html("");
        $("#ajaxloading-elimina_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.elimina_participante_p2.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-elimina_participante_p2").html("");
                $("#ajaxbox-elimina_participante_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
    function habilita_participante_p1(id_participante) {
        $("#ajaxbox-habilita_participante_p1").html("");
        $("#ajaxloading-habilita_participante_p1").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.habilita_participante_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-habilita_participante_p1").html("");
                $("#ajaxbox-habilita_participante_p1").html(data);
            }
        });
    }
    function habilita_participante_p2(id_participante) {
        $("#ajaxbox-habilita_participante_p2").html("");
        $("#ajaxloading-habilita_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.habilita_participante_p2.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-habilita_participante_p2").html("");
                $("#ajaxbox-habilita_participante_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
    function agrega_participante() {
        var data_form = $("#form-agrega-participante").serialize();
        $("#ajaxloading-agrega_participante").html(text__loading_dos);
        $("#ajaxbox-agrega_participante").html("");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.agrega_participante.php',
            data: data_form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                var data_json_parsed = JSON.parse(data);
                $("#ajaxloading-agrega_participante").html("");
                $("#ajaxbox-agrega_participante").html(data_json_parsed['mensaje']);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                if (document.getElementById('impficha').checked === true) {
                    if (data_json_parsed['estado'] === 1) {
                        window.open(data_json_parsed['url_ficha'], 'popup', 'width=700,height=500');
                    }
                }
                document.getElementById("form-agrega-participante").reset();
            }
        });
    }

</script>

<!--FUNCIONES DE INICIO DE PAGINA-->
<script>
    var text__loading_uno = "<div style='text-align:center;'><img src='http://datainflow.com/wp-content/uploads/2017/09/loader.gif'/></div>";
    var text__loading_dos = "Cargando... <img src='contenido/imagenes/images/load_ajax.gif'/>";

    lista_participantes_INICIO(<?php echo $id_curso; ?>, 0);
    lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);

</script>


<?php

function my_date_curso($dat) {
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]] . " " . substr($ar1[0], 2, 2);
    }
}
?>