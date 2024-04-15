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
$mensaje = "";

/* id de curso */
$id_curso = 2321;

/* id de turno */
$id_turno_curso = 0;
$qr_turno = '';
if (isset($get[3]) && $get[3]!= 'no-turn' ) {
    $id_turno_curso = (int) $get[3];
    $qr_turno = " AND id_turno='$id_turno_curso' ";
}

/* filtro id especifico */
if (isset($get[4])) {
    $id_part_especifico = (int) $get[3];
}

/* sw de habilitacion de procesos */
$rqvhc1 = query("SELECT estado FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqvhc2 = fetch($rqvhc1);
if ($rqvhc2['estado'] == '1' || $rqvhc2['estado'] == '2') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}

$resultado1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' $qr_busqueda $qr_turno ORDER BY id DESC ");
/* res aux numeracion */
$resultado_aux_numeracion1 = query("SELECT numeracion FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' $qr_busqueda $qr_turno ORDER BY numeracion DESC LIMIT 1 ");
$resultado_aux_numeracion2 = fetch($resultado_aux_numeracion1);
$numeracion_por_participantes = $resultado_aux_numeracion2['numeracion'];

/* datos del curso */
$rqc1 = query("SELECT titulo,titulo_identificador,fecha,imagen,costo,costo2,costo3,costo_e,costo_e2,id_certificado,id_certificado_2,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado,inicio_numeracion,id_modalidad FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = fetch($rqc1);
$nombre_del_curso = $rqc2['titulo'];
$inicio_numeracion = $rqc2['inicio_numeracion'];
$titulo_identificador_del_curso = $rqc2['titulo_identificador'];
$fecha_del_curso = $rqc2['fecha'];
$codigo_de_certificado_del_curso = $rqc2['codigo_certificado'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
if ($rqc2['imagen'] !== '') {
    $url_imagen_del_curso = $dominio_www."contenido/imagenes/paginas/" . $rqc2['imagen'];
} else {
    $url_imagen_del_curso = "https://www.infosiscon.com/images/banner-cursos.png.size=4.img";
}
$costo_curso = $rqc2['costo'];
$costo2_curso = $rqc2['costo2'];
$costo3_curso = $rqc2['costo3'];
$costoe_curso = $rqc2['costo_e'];
$costoe2_curso = $rqc2['costo_e2'];
$id_modalidad_curso = $rqc2['id_modalidad'];

if ($numeracion_por_participantes > $inicio_numeracion) {
    $inicio_numeracion = (int) $numeracion_por_participantes + 1;
}


$cnt = num_rows($resultado1);
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
<script>
    var VAR_modo_de_pago = 'todos';
</script>


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
            <li class="active">Participantes</li>
        </ul>
        <h4 class="page-header"> ULTIMOS ACTIVADOS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h4>
    </div>
</div>
<?php
echo $mensaje;
?>
<div class="row">
    <div class="col-md-12NOT">
        <div class="panelNOT">

            <!-- DIV CONTENT AJAX :: AGREGA PARTICIPANTE -->
            <div id="ajaxloading-agrega_participante"></div>
            <div id="ajaxbox-agrega_participante">
                <!-- .... -->
            </div>

            <script>
                function nombreFact(dat) {
                    var nom = document.getElementById("f-nom").value;
                    var ape = document.getElementById("f-ape").value;
                    var nfact = "";

                    if (dat === 3) {
                        nfact = nom + " " + ape;
                    } else if (dat === 2) {
                        var araux1 = nom.split(' ');
                        var araux2 = ape.split(' ');
                        nfact = araux1[0] + " " + araux2[0];
                    } else {
                        var araux2 = ape.split(' ');
                        nfact = araux2[0];
                    }

                    document.getElementById("f-raz").value = nfact.toUpperCase();
                }
                function nitFact() {
                    var ci = document.getElementById("f-ci").value;
                    document.getElementById("f-nit").value = ci;
                }
            </script>
            <script>
                function nombreFact2(dat) {

                    var nom = document.getElementById("f-nom-p").value;
                    var ape = document.getElementById("f-ape-p").value;
                    var nfact = "";

                    if (dat === 3) {
                        nfact = nom + " " + ape;
                    } else if (dat === 2) {
                        var araux1 = nom.split(' ');
                        var araux2 = ape.split(' ');
                        nfact = araux1[0] + " " + araux2[0];
                    } else {
                        var araux2 = ape.split(' ');
                        nfact = araux2[0];
                    }

                    document.getElementById("f-raz-p").value = nfact.toUpperCase();
                }
                function nitFact2() {
                    var ci = document.getElementById("f-ci-p").value;
                    document.getElementById("f-nit-p").value = ci;
                }
            </script>


            <div class="panel-bodyNOT" style="padding-top: 0px;">


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

<!-- ajax de emision de certificados -->


<!-- ajax imprimir copia legalizada -->
<script>
    function imprimir_copia_legalizada(dat) {

        if (dat > 0) {
            $.ajax({
                url: 'pages/ajax/ajax.aux.cursos-participantes.imprimir_copia_legalizada.php',
                data: {dat: dat},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    window.open(data, 'popup', 'width=700,height=500');
                    /*
                     setTimeout(function() {
                     lista_participantes(<?php //echo $id_curso;                       ?>, 0);
                     }, 2000);
                     */
                }
            });
        } else {
            alert('Error en el ID de certificado');
        }
    }
</script>

<!-- ajax visualizar_certificado_digital -->
<script>
    function visualizar_certificado_digital(dat) {
        if (dat > 0) {
            $.ajax({
                url: 'pages/ajax/ajax.aux.cursos-participantes.visualizar_certificado_digital.php',
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
        $("#AJAXCONTENT-emite_certificados_multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_certificados.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-emite_certificados_multiple").html(data);
                //lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!-- ajax emision certificados masivamente Certificado 2 -->
<script>
    function emision_multiple_c2_certificados() {
        var ids_to_send;
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();

        var aux_idsalmacenador_2 = '0';
        var arraux1 = aux_idsalmacenador.split(',');
        for (var index = 0; index < arraux1.length; ++index) {
            if (array_check_participante[arraux1[index]] !== undefined) {
                //console.log(arraux1[index]);
                aux_idsalmacenador_2 = aux_idsalmacenador_2 + ',' + arraux1[index];
            }
        }
        if (aux_idsalmacenador_2 === '0') {
            ids_to_send = ids.join(',');
        } else {
            ids_to_send = aux_idsalmacenador_2;
        }

        //alert(ids_to_send);
        if (true) {

            $("#AJAXCONTENT-emite_certificados_multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
            $.ajax({
                url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_c2_certificados.php',
                data: {dat: ids_to_send},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-emite_certificados_multiple").html(data);
                    //lista_participantes(<?php echo $id_curso; ?>, 0);
                }
            });

        }
    }
</script>

<!-- ajax emision certificados a eleccion -->
<script>
    function emision_certificados_a_eleccion() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion.php',
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
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion_p2.php',
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
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion_p3.php',
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
        $("#box-modal_impresion_certificados-a-eleccion").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.impresion_certificados_a_eleccion.php',
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
        $("#box-modal_impresion_certificados-a-eleccion").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.impresion_certificados_a_eleccion_p2.php',
            data: {dat: ids.join(','), id_modelo_certificado: id_modelo_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_impresion_certificados-a-eleccion").html(data);
            }
        });
    }
</script>


<!-- AJAX emite_certificados_multiple -->
<script>
    function emite_certificados_multiple(id_certificado, id_curso, modo) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#AJAXCONTENT-emite_certificados_multiple").html('PROCESANDO...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_certificados_multiple.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso, modo: modo},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-emite_certificados_multiple").html(data);
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
        $("#AJAXCONTENT-emite_certificados_multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_certificados_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-emite_certificados_multiple").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
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
        $("#AJAXCONTENT-emite_certificados_multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_c2_certificados_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-emite_certificados_multiple").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
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

        window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/factura-1-masivo.php?id_participantes=' + ids.join(','), 'popup', 'width=700,height=500');


    }
</script>

<!-- ajax emision facturas masivamente -->
<script>
    function emision_multiple_facturas() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_facturas-multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_facturas.php',
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
        $("#box-modal_emision_facturas-multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_facturas_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_facturas-multiple").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!-- ajax procesar_certificados_secundarios -->
<script>
    function procesar_certificados_secundarios(id_participante) {
        var id_curso = '<?php echo $id_curso; ?>';
        $("#BOX-AJAX-certificados-secundarios").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.procesar_certificados_secundarios.php',
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
        $("#BOX-AJAX-certificados-secundarios").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.procesar_certificados_secundarios_p2.php',
            data: arraydata,
            type: 'POST',
            success: function(data) {
                $("#BOX-AJAX-certificados-secundarios").html(data);
            }
        });
    }
</script>

<!-- ajax deshabilita participantes no seleccionados p1 -->
<script>
    function deshabilita_participantes_no_seleccionados() {

        var aux_idsalmacenador_2 = '0';

        var arraux1 = aux_idsalmacenador.split(',');
        for (var index = 0; index < arraux1.length; ++index) {
            if (array_check_participante[arraux1[index]] === undefined) {
                //console.log(arraux1[index]);
                aux_idsalmacenador_2 = aux_idsalmacenador_2 + ',' + arraux1[index];
            }
        }
        //alert(aux_idsalmacenador_2);

        $("#ajaxloading-deshabilita_participantes_no_seleccionados").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.deshabilita_participantes_no_seleccionados_p1.php',
            data: {dat: aux_idsalmacenador_2},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-deshabilita_participantes_no_seleccionados").html('');
                $("#ajaxbox-deshabilita_participantes_no_seleccionados").html(data);
            }
        });
    }
</script>

<!-- ajax deshabilita participantes no seleccionados p2 -->
<script>
    function deshabilita_participantes_no_seleccionados_p2() {
        var aux_idsalmacenador_2 = '0';
        var arraux1 = aux_idsalmacenador.split(',');
        for (var index = 0; index < arraux1.length; ++index) {
            if (array_check_participante[arraux1[index]] === undefined) {
                aux_idsalmacenador_2 = aux_idsalmacenador_2 + ',' + arraux1[index];
            }
        }
        $("#ajaxloading-deshabilita_participantes_no_seleccionados_p2").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.deshabilita_participantes_no_seleccionados_p2.php',
            data: {dat: aux_idsalmacenador_2},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-deshabilita_participantes_no_seleccionados_p2").html('');
                $("#ajaxbox-deshabilita_participantes_no_seleccionados_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!-- ajax emision cupones descuento -->
<script>
    function emision_cupones_infosicoes() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_curso = '<?php echo $id_curso; ?>';
        $("#AJAXCONTENT-emite_certificados_multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_cupones_infosicoes.php',
            data: {dat: ids.join(','), id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_cupones-descuento").html(data);
                //lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!-- ajax emision cupones descuento2 -->
<script>
    function emision_cupones_infosicoes_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_curso = '<?php echo $id_curso; ?>';
        $("#AJAXCONTENT-emite_certificados_multiple").html('<img src="<?php echo $dominio_www; ?>contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'pages/ajax/ajax.modal.cursos-participantes.emision_multiple_cupones_infosicoes_p2.php',
            data: {dat: ids.join(','), id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_cupones-descuento").html(data);
                //lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<style>
    .fila_seleccionada{
        background: #dadada;
    }
    .fila_seleccionada:hover td{
        background: #dadada !important;
    }
</style>
<script>
    function check_participante(dat) {
        if (array_check_participante[dat] === undefined) {
            //$("#ajaxbox-tr-participante-" + dat).css("background", "#dadada");
            $("#ajaxbox-tr-participante-" + dat).addClass("fila_seleccionada");
            array_check_participante[dat] = true;
        } else {
            array_check_participante[dat] = undefined;
            //alert(array_check_participante[dat]);
            //$("#ajaxbox-tr-participante-" + dat).css("background", "#ffffff");
            $("#ajaxbox-tr-participante-" + dat).removeClass("fila_seleccionada");
        }
    }

    function procesa_checked_participantes() {
        alert(JSON.stringify(array_check_participante));
    }

    function deshabilita_participantes_no_seleccionados_cero() {
        //alert("YEY2 -> "+aux_idsalmacenador;
        var aux_idsalmacenador_2 = '0';

        var arraux1 = aux_idsalmacenador.split(',');
        for (var index = 0; index < arraux1.length; ++index) {
            if (array_check_participante[arraux1[index]] === undefined) {
                //console.log(arraux1[index]);
                aux_idsalmacenador_2 = aux_idsalmacenador_2 + ',' + arraux1[index];
            }
        }
        alert(aux_idsalmacenador_2);
    }
</script>


<!-- MODALS -->


<!-- Modal deshabilita de participantes no seleccionados -->
<div id="MODAL-deshabilita_participantes_no_seleccionados" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DESHABILITACION DE PARTICIPANTES</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: DESHABILITACION DE PARTICIPANTES P1 -->
                <div id="ajaxloading-deshabilita_participantes_no_seleccionados"></div>
                <div id="ajaxbox-deshabilita_participantes_no_seleccionados">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>
<!-- END Modal deshabilita de participantes no seleccionados -->

<style>
    @media (min-width: 890px){
        .modal-large{
            width: 800px;
        }
    }
</style>

<!-- Modal edicion de participante -->
<div id="MODAL-edicion-participante" class="modal fade" role="dialog">
    <div class="modal-dialog modal-large">
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
                <div class="text-center" id='AJAXCONTENT-emite_certificados_multiple'>
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


<!-- Modal emitir cupones descuento -->
<div id="MODAL-emite-cupones-descuento" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION DE CUPONES DESCUENTO</h4>
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
                    Emisi&oacute;n de cupones para
                </h5>
                <div class="text-center" id='box-modal_emision_cupones-descuento'>
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
<!-- End Modal emitir cupones descuento -->

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
                    <div class="col-md-12">
                        <!-- AJAX CONTENT -->
                        <div id="AJAXBOX-reporte_cierre"></div>
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


<!-- Modal agregar participantes CSV --> 
<div id="MODAL-agregar_participantes_csv" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">AGREGADO DE PARTIPANTES MEDIANTE CSV (Excel)</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:50%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <p>Para hacer el cargado de participantes via hoja excel, se debe descargar la siguiente plantilla e ir agregando en ella los participantes correspondinetes.</p>
                <h5 class="text-center">
                    <a href="<?php echo $dominio_www; ?>contenido/archivos/cursos/plantilla-nuevos-participantes.csv" style="text-decoration:underline;"><b>Descarga de plantilla CSV <i class="fa fa-download"></i></b></a>
                </h5>
                <hr/>
                <div class="text-center" id='BOX-agregar_participantes_csv'>
                    <!-- ajax content -->
                    <p>El archivo con los participantes agregados mediante la plantilla debe ser guardado en formato <br/><b>CSV (delimitado por comas) (*.csv)</b> para evitar problemas de compatibilidad.</p>
                    <form id="FORM-agregar_participantes_csv" name="FORM-agregar_participantes_csv" method="post" enctype="multipart/form-data">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <td><b>Plantilla:</b></td>
                                <td><input type="file" class="form-control" name="plantilla"/></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>
                                    <b class="btn btn-success btn-block" onclick="agregar_participantes_csv();">SUBIR PARTICIPANTES</b>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal agregar participantes CSV -->


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


<!-- MODAL proceso_envio_de_certificado -->
<div id="MODAL-proceso_envio_de_certificado" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PROCESO ENVIO DE CERTIFICADO</h4>
            </div>
            <div class="modal-body">
                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-proceso_envio_de_certificado"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<!-- MODAL edita_certificado_individual -->
<div id="MODAL-edita_certificado_individual" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EDICI&Oacute;N DE CERTIFICADO INDIVIDUAL</h4>
            </div>
            <div class="modal-body">
                <!-- AJAXCONTENT -->
                <div id="AJAXCONTENT-edita_certificado_individual"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function acceso_cursos_virtuales(id_participante) {
        //$("#AJAXCONTENT-acceso_cursos_virtuales").html('Enviando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.acceso_cursos_virtuales.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-acceso_cursos_virtuales").html(data);
            }
        });
    }
</script>

<!-- Modal acceso_cursos_virtuales -->
<div id="MODAL-acceso_cursos_virtuales" class="modal fade" role="dialog">
    <div class="modal-dialog">
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


<!-- Modal avance-cvirtual -->
<div id="MODAL-avance-cvirtual" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PANEL DE CURSO VIRTUAL</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: HABILITACION PARTICIPANTE P1 -->
                <div id="ajaxloading-avance_cvirtual"></div>
                <div id="ajaxbox-avance_cvirtual">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal avance-cvirtual -->


<script>
    function edita_certificado_individual(id_emision_certificado) {
        $("#AJAXCONTENT-edita_certificado_individual").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.edita_certificado_individual.php',
            data: {id_emision_certificado: id_emision_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-edita_certificado_individual").html(data);
            }
        });
    }
</script>



<script>
    function proceso_envio_de_certificado(id_emision_certificado) {
        $("#AJAXCONTENT-proceso_envio_de_certificado").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.proceso_envio_de_certificado.php',
            data: {id_emision_certificado: id_emision_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-proceso_envio_de_certificado").html(data);
            }
        });
    }
</script>





<!-- envio de factura -->
<script>
    function enviar_factura(id) {

        var email = $("#correo-de-envio-" + id).val();
        $("#box-modal_envia-factura-" + id).html("Enviando correo...");
        $.ajax({
            url: 'pages/ajax/ajax.instant.enviar_factura.php?id_factura=' + id + '&email_a_enviar=' + email,
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
            url: 'pages/ajax/ajax.instant.enviar_factura.php?id_factura=' + id + '&email_a_enviar=' + email,
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
        $("#ajaxloading-lista_participantes").html(text__loading_tres);
        document.getElementById('inputbuscador').value = "";
        $.ajax({
            url: 'pages/ajax/ajax.participantes-ultimos-activados.lista_participantes.php',
            data: {id_curso: id_curso, id_turno: id_turno, pago: VAR_modo_de_pago},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes").html("");
                $("#ajaxbox-lista_participantes").html(data);

                $(".btnmodopago").removeClass("btn-info");
                $(".btnmodopago").addClass("btn-default");
                $("#btnmodopago-" + VAR_modo_de_pago).removeClass("btn-default");
                $("#btnmodopago-" + VAR_modo_de_pago).addClass("btn-info");

                $(".btnturno").removeClass("btn-success");
                $(".btnturno").addClass("btn-info");
                $("#btnturno-" + id_turno).removeClass("btn-info");
                $("#btnturno-" + id_turno).addClass("btn-success");

                document.getElementById("idturno").value = id_turno;
            }
        });
    }
    function lista_participantes_INICIO(id_curso, id_turno) {
        $("#ajaxloading-lista_participantes").html(text__loading_uno);
        $.ajax({
            url: 'pages/ajax/ajax.participantes-ultimos-activados.lista_participantes.php',
            data: {id_curso: id_curso, id_turno: id_turno, pago: VAR_modo_de_pago},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes").html("");
                $("#ajaxbox-lista_participantes").html(data);

                document.getElementById("idturno").value = id_turno;
            }
        });
    }
    function busca_participantes(id_curso, id_turno, busc) {
        $("#ajaxloading-lista_participantes").html(text__loading_tres);
        $.ajax({
            url: 'pages/ajax/ajax.participantes-ultimos-activados.lista_participantes.php',
            data: {id_curso: id_curso, id_turno: id_turno, busc: busc, pago: VAR_modo_de_pago},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes").html("");
                $("#ajaxbox-lista_participantes").html(data);

                $(".btnmodopago").removeClass("btn-success");
                $(".btnmodopago").addClass("btn-warning");
                $("#btnmodopago-" + id_turno).removeClass("btn-warning");
                $("#btnmodopago-" + id_turno).addClass("btn-success");

                $(".btnturno").removeClass("btn-success");
                $(".btnturno").addClass("btn-info");
                $("#btnturno-" + id_turno).removeClass("btn-info");
                $("#btnturno-" + id_turno).addClass("btn-success");
            }
        });

        $("#ajaxloading-lista_participantes_eliminados").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.lista_participantes_eliminados.php',
            data: {id_curso: id_curso, id_turno: id_turno, busc: busc, pago: VAR_modo_de_pago},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes_eliminados").html("");
                $("#ajaxbox-lista_participantes_eliminados").html(data);
            }
        });

        //document.getElementById("idturno").value = id_turno;

        return false;
    }
    function lista_participantes_eliminados(id_curso, id_turno) {
        $("#ajaxloading-lista_participantes_eliminados").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.lista_participantes_eliminados.php',
            data: {id_curso: id_curso, id_turno: id_turno, pago: VAR_modo_de_pago},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes_eliminados").html("");
                $("#ajaxbox-lista_participantes_eliminados").html(data);
            }
        });
    }
    function edita_participante_p1(id_participante, nro_lista) {
        $("#ajaxloading-edita_participante_p1").html(text__loading_dos);
        $("#ajaxbox-edita_participante_p1").html("");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.edita_participante_p1.php',
            data: {id_participante: id_participante, nro_lista: nro_lista},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-edita_participante_p1").html("");
                $("#ajaxbox-edita_participante_p1").html(data);
            }
        });
    }
    function pago_participante(id_participante) {
        $("#ajaxloading-pago_participante").html(text__loading_dos);
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
    /*
     function edita_participante_p2(id_participante) {
     $("#ajaxloading-edita_participante_p2").html(text__loading_dos);
     $.ajax({
     url: 'pages/ajax/ajax.cursos-participantes.edita_participante_p2.php',
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
     */
    function emite_certificado_p1(id_participante, nro_certificado) {
        /*if (nro_certificado === 2) {
         alert('PARA CERTIFICADO 2 USAR EMISION MULTIPLE (los botones de abajo)');
         $("#ajaxloading-emite_certificado_p1").html("");
         $("#ajaxbox-emite_certificado_p1").html("");
         } else {
         */

        $("#ajaxloading-emite_certificado_p1").html(text__loading_dos);
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

        /*}*/
    }
    function emite_certificado_p2(id_participante, nro_certificado) {

        var receptor_de_certificado = $("#receptor_de_certificado-" + id_participante).val();
        var id_certificado = $("#id_certificado-" + id_participante).val();
        var id_curso = $("#id_curso-" + id_participante).val();

        var cont_tres = $("#cont_tres").val();
        var fecha_qr = $("#fecha_qr").val();
        var cont_dos = $("#cont_dos").val();
        var texto_qr = $("#texto_qr").val();

        $("#ajaxloading-emite_certificado_p2").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_certificado_p2.php',
            data: {receptor_de_certificado: receptor_de_certificado, id_certificado: id_certificado, id_curso: id_curso, id_participante: id_participante, nro_certificado: nro_certificado, cont_tres: cont_tres, fecha_qr: fecha_qr, cont_dos: cont_dos, texto_qr: texto_qr},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                if (nro_certificado === 1) {
                    $("#box-modal_emision_certificado-button-" + id_participante).html('<i class="btn-sm btn-default active">Emitido</i>');
                } else {
                    $("#box-modal_emision_certificado-button-2-" + id_participante).html('<i class="btn-sm btn-default active">Emitido</i>');
                }
                lista_participantes(<?php echo $id_curso; ?>, 0);
                $("#ajaxloading-emite_certificado_p2").html("");
                $("#ajaxbox-emite_certificado_p2").html(data);
            }
        });
    }
    function datos_registro(id_participante) {
        $("#ajaxbox-datos_registro").html("");
        $("#ajaxloading-datos_registro").html(text__loading_uno);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.datos_registro.php',
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
        $("#ajaxloading-emite_factura_p2").html(text__loading_dos);
        $("#ajaxbox-emite_factura_p2").html("");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.emite_factura_p2.php',
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
            url: 'pages/ajax/ajax.cursos-participantes.elimina_participante_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-elimina_participante_p1").html("");
                $("#ajaxbox-elimina_participante_p1").html(data);
            }
        });
    }
    function elimina_participante_p2(id_participante, apartar) {
        $("#ajaxbox-elimina_participante_p2").html("");
        $("#ajaxloading-elimina_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.elimina_participante_p2.php',
            data: {id_participante: id_participante, apartar: apartar},
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
    function habilita_participante_p1(id_participante, apartar) {
        $("#ajaxbox-habilita_participante_p1").html("");
        $("#ajaxloading-habilita_participante_p1").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_p1.php',
            data: {id_participante: id_participante, apartar: apartar},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-habilita_participante_p1").html("");
                $("#ajaxbox-habilita_participante_p1").html(data);
            }
        });
    }
    function habilita_participante_p2(id_participante, apartar) {
        $("#ajaxbox-habilita_participante_p2").html("");
        $("#ajaxloading-habilita_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_p2.php',
            data: {id_participante: id_participante, apartar: apartar},
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
    function habilita_participante_cvirtual_p1(id_participante) {
        $("#ajaxbox-habilita_participante_p1").html("");
        $("#ajaxloading-habilita_participante_p1").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_cvirtual_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-habilita_participante_p1").html("");
                $("#ajaxbox-habilita_participante_p1").html(data);
            }
        });
    }
    function habilita_participante_cvirtual_p2(id_participante) {
        $("#ajaxbox-habilita_participante_p2").html("");
        $("#ajaxloading-habilita_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.habilita_participante_cvirtual_p2.php',
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
    function elimina_participante_cvirtual_p1(id_participante) {
        $("#ajaxbox-elimina_participante_p1").html("");
        $("#ajaxloading-elimina_participante_p1").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.elimina_participante_cvirtual_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-elimina_participante_p1").html("");
                $("#ajaxbox-elimina_participante_p1").html(data);
            }
        });
    }
    function elimina_participante_cvirtual_p2(id_participante) {
        $("#ajaxbox-elimina_participante_p2").html("");
        $("#ajaxloading-elimina_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.elimina_participante_cvirtual_p2.php',
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
    function agrega_participante() {
        var data_form = $("#form-agrega-participante").serialize();
        $("#ajaxloading-agrega_participante").html(text__loading_dos);
        $("#ajaxbox-agrega_participante").html("");
        var numeracionParticipante = parseInt($("#numeracion-nuevo-participante").val()) + 1;
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.agrega_participante.php',
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
                var data_mpago = $("#data_mpago").val();
                var data_fpago = $("#data_fpago").val();
                document.getElementById("form-agrega-participante").reset();
                $("#numeracion-nuevo-participante").val(numeracionParticipante);
                $("#data_mpago").val(data_mpago);
                $("#data_fpago").val(data_fpago);
            }
        });
    }
    function agregar_participantes_csv() {

        var myform = document.getElementById("FORM-agregar_participantes_csv");
        var fd = new FormData(myform);
        $.ajax({
            url: "pages/ajax/ajax.cursos-participantes.agregar_participantes_csv.php",
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(dataofconfirm) {
                // do something with the result
                $("#BOX-agregar_participantes_csv").html(dataofconfirm);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });


    }
    function avance_cvirtual(id_participante) {
        $("#ajaxbox-avance_cvirtual").html("");
        $("#ajaxloading-avance_cvirtual").html(text__loading_dos);
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.avance_cvirtual.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-avance_cvirtual").html("");
                $("#ajaxbox-avance_cvirtual").html(data);
            }
        });
    }
</script>

<script>
    function cambiar_estado_curso(id_curso, estado) {
        $("#box-desactivar-curso").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.cambiar_estado_curso.php',
            data: {id_curso: id_curso, estado: estado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                if (estado === 'desactivado') {
                    $("#div-add-participante").css('display', 'none');
                } else {
                    $("#div-add-participante").css('display', 'block');
                }
                $("#box-desactivar-curso").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!--FUNCIONES DE INICIO DE PAGINA-->
<script>
    var text__loading_uno = "<div style='text-align:center;'><img src='<?php echo $dominio_www; ?>contenido/imagenes/images/loader.gif'/></div>";
    var text__loading_dos = "Cargando...";
    var text__loading_tres = "<div style='background: #FFF;padding: 10px;border: 1px solid gray;border-radius: 5px;position: absolute;box-shadow: 2px 2px 8px 0px #80808087;'>Actualizando...</div>";

    <?php
    if (isset($get[4])) {
        ?>
        busca_participantes(<?php echo $id_curso; ?>, 0, '<?php echo $get[4]; ?>');
        document.getElementById("inputbuscador").value = '<?php echo $get[4]; ?>';
        <?php
    }else{
        ?>
        lista_participantes_INICIO(<?php echo $id_curso; ?>, 0);
        lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
        <?php
    }
    ?>
</script>

<!-- AUTOCOMPLETADO DE PARTICIPANTE POR CI -->
<script>
    function checkParticipante(dat) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.checkParticipante.php',
            data: {dat: dat},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                var data_json_parsed = JSON.parse(data);
                if (data_json_parsed['estado'] === 1) {
                    if ($("#f-nom").val() === '') {
                        $("#f-nom").val((data_json_parsed['nombres']).toUpperCase());
                    }
                    if ($("#f-ape").val() === '') {
                        $("#f-ape").val((data_json_parsed['apellidos']).toUpperCase());
                    }
                    if ($("#f-email").val() === '') {
                        $("#f-email").val((data_json_parsed['correo']).toLowerCase());
                    }
                    if ($("#f-celular").val() === '') {
                        $("#f-celular").val((data_json_parsed['celular']).toLowerCase());
                    }
                    if ($("#f-pref").val() === '') {
                        $("#f-pref").val((data_json_parsed['prefijo']).toUpperCase());
                    }
                    if ($("#f-exp").val() === '') {
                        $("#f-exp").val(data_json_parsed['ci_expedido']).change();
                    }
                    if ($("#f-raz").val() === '') {
                        $("#f-raz").val((data_json_parsed['razon_social']).toUpperCase());
                    }
                    if ($("#f-nit").val() === '') {
                        $("#f-nit").val((data_json_parsed['nit']).toUpperCase());
                    }
                } else {
                    $("#f-nom").val('');
                    $("#f-ape").val('');
                    $("#f-email").val('');
                    $("#f-celular").val('');
                    $("#f-pref").val('');
                    $("#f-exp").val('').change();
                    $("#f-raz").val('');
                    $("#f-nit").val('');
                }
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


<!-- reprogramacion_de_curso -->
<script>
    function reprogramacion_de_curso(id_participante) {
        $("#TR-AJAXBOX-reprogramacion_de_curso").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.reprogramacion_de_curso.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#TR-AJAXBOX-reprogramacion_de_curso").html(data);
            }
        });
    }
</script>

<!-- reprogramacion_de_curso_p2 -->
<script>
    function reprogramacion_de_curso_p2() {
        var form = $("#FORM-reprogramacion_de_curso").serialize();
        $("#TR-AJAXBOX-reprogramacion_de_curso").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-participantes.reprogramacion_de_curso_p2.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#TR-AJAXBOX-reprogramacion_de_curso").html(data);
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
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]] . " " . substr($ar1[0], 2, 2);
    }
}
