<?php
/* carrito */
$Carrito = new Carrito();

?>

<style>
    .buttonlink {
       background: #36bd15;
    }
    .rojo {
       background-color: #ff2a00;
    }
</style>

<div class="gris">
    <section class="container">

        <?php
        include_once __DIR__.'/includes/inc.tienda.box_carrito.php';
        ?>

        <br/>
        <br/>
        <br class="hidden-sm hidden-md hidden-lg">

        <div style="padding-right:300px;">
            <div class="col-md-12">
                <h2 class="titulo-second-f1" style="background: #98b1d0;">TIENDA</h2>
            </div>
            <br class="hidden-xs">
            <div style="font-size: 16pt;padding: 0 10px;">
                Bienvenido a la tienda de cursos, donde podr&aacute;s obtener beneficios y descuentos en cuanto m&aacute;s cursos part&iacute;cipes.
            </div>
        </div>

        <hr>

        <div class="row">
            <?php
            include_once __DIR__.'/includes/inc.tienda.tabla_informativa.php';
            ?>
        </div>

        <?php        
        
        $counter_aux = 0;
        $ids_a_ignorar = '3451';

        $rc1 = query("SELECT 
        (c.id)dr_id_curso,c.titulo,c.titulo_identificador,c.imagen,c.imagen_gif,(cd.nombre)ciudad,
        c.fecha,c.horarios,c.sw_fecha,c.id_modalidad,c.costo,c.costo2,c.costo3,c.fecha2,c.sw_fecha2,
        c.fecha3,c.sw_fecha3,fecha_e,sw_fecha_e,costo_e 
        FROM cursos c 
        INNER JOIN ciudades cd ON c.id_ciudad=cd.id 
        WHERE sw_flag_cursosbo='1' 
        AND c.sw_siguiente_semana='0' 
        AND c.flag_publicacion IN ('1','3') 
        AND c.columna='1' 
        AND c.id_modalidad IN (2,3,4) 
        AND c.sw_tienda=1
        AND c.id NOT IN ($ids_a_ignorar)  
        ORDER BY c.fecha DESC ");

        if (num_rows($rc1) == 0) {
            echo "<div class='col-md-12'>";
            echo "<div class=''>";
            echo "<div class='panel-body'>";
            echo "<p>No se tienen cursos virtuales disponibles.</p><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }

        while ($rc2 = fetch($rc1)) {
            $titulo_de_curso = $rc2['titulo'];
            $ciudad_curso = $rc2['ciudad'];
            $fecha_curso = Util::fechaFormatoLiteralCompleto($rc2['fecha']);
            if ($rc2['id_modalidad'] == '2') {
                $fecha_curso = 'DISPONIBLE AHORA';
            }
            $horarios = $rc2['horarios'];
            if ($rc2['imagen_gif'] == '') {
                $url_imagen_curso = "contenido/imagenes/paginas/" . $rc2['imagen'];
                if (!file_exists("contenido/imagenes/paginas/" . $rc2['imagen'])) {
                    $url_imagen_curso = "https://cursos.bo/contenido/imagenes/paginas/" . $rc2['imagen'];
                }
            } else {
                $url_imagen_curso = "contenido/imagenes/paginas/" . $rc2['imagen_gif'];
            }
            /* url curso */
            $url_pagina_curso = "" . $rc2['titulo_identificador'] . ".html";
            $rqenc1 = query("SELECT e.enlace FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON e.id=r.id_enlace WHERE r.id_curso='".$rc2['dr_id_curso']."' AND r.estado=1 ");
            if(num_rows($rqenc1)>0){
                $rqenc2 = fetch($rqenc1);
                $url_pagina_curso = $dominio.$rqenc2['enlace'] . "/";
            }
            $url_registro_curso = "registro-curso/" . $rc2['titulo_identificador'] . ".html";
            $modalidad_curso = "PRESENCIAL";
            $icon_modalidad_curso = "icon-bookmark";
            if ($rc2['id_modalidad'] == '2') {
                $modalidad_curso = "VIRTUAL";
                $icon_modalidad_curso = "fa fa-street-view";
                $ciudad_curso = 'TODA BOLIVIA';
            } elseif ($rc2['id_modalidad'] == '3' || $rc2['id_modalidad'] == '4') {
                $modalidad_curso = "VIRTUAL";
                $icon_modalidad_curso = "fa fa-street-view";
            }
            /* costo */
                $costo_curso = $rc2['costo'];
                $f_h = date("H:i", strtotime($rc2['fecha2']));
                if ($f_h !== '00:00') {
                    $f_actual = strtotime(date("Y-m-d H:i"));
                    $f_limite = strtotime($rc2['fecha2']);
                } else {
                    $f_actual = strtotime(date("Y-m-d"));
                    $f_limite = strtotime(substr($rc2['fecha2'], 0, 10));
                }
                $texto_descuento = 'TODA BOLIVIA';
                if ($rc2['sw_fecha2'] == '1' && ( $f_actual <= $f_limite )) {
                    $texto_descuento = '<b style="color: #006ede;font-weight: bold;">'.$rc2['costo2'].' Bs</b> hasta el '.date("d/m",strtotime($rc2['fecha2']));
                }
                if ($rc2['sw_fecha3'] == '1' && ( date("Y-m-d") <= $rc2['fecha3'])) {
                    $texto_descuento = '<b style="color: #006ede;font-weight: bold;">'.$rc2['costo3'].' Bs</b> hasta el '.date("d/m",strtotime($rc2['fecha3']));
                }
                
                $rqdwn1 = query("SELECT w.numero FROM whatsapp_numeros w INNER JOIN cursos_rel_cursowapnum r ON r.id_whats_numero=w.id WHERE r.id_curso='".$rc2['dr_id_curso']."' ORDER BY r.id ASC LIMIT 1 ");
                if(num_rows($rqdwn1)==0){
                    $num_whatsapp = "69714008";
                }else{
                    $rqdwn2 = fetch($rqdwn1);
                    $num_whatsapp = $rqdwn2['numero'];
                }

                /* existencia en carrito */
                $swCursoEncarrito = $Carrito->isItemExists($rc2['dr_id_curso']);
            ?>
            <div class="col-xs-12 col-sm-6 col-md-4" align="left">
                <div class="blog-post-short">
                    <div class="img-holder">
                        <div class="bg-img-holder bx-img-curso-f1">
                            <a style="cursor: pointer;" onclick="showDetalleCursoTienda(<?=$rc2['dr_id_curso']?>)">
                                <img src="<?php echo $url_imagen_curso; ?>" alt="<?php echo $titulo_de_curso; ?>" title="<?php echo $titulo_de_curso; ?>" class="img-responsive grafico img-curso-f1" />
                            </a>
                        </div>
                    </div>
                    <div class="box-label-costo"><?php echo $costo_curso; ?> Bs</div>
                </div>
                <!----->
                <div class="blog-post-short list-group-item">
                        <div class="row">
                            <div class="col-md-12  titulo-curso-f1">
                                <h2 class="h2-to-normal"><a style="cursor: pointer;" onclick="showDetalleCursoTienda(<?=$rc2['dr_id_curso']?>)" class="Enlace_Curso_Main_Titulo"><?php echo $titulo_de_curso; ?></a></h2>
                            </div>
                        </div>
                        <div class="row hi ">	  
                            <div class="col-xs-6 col-sx-6 hi "><i class="icon-money"></i> Inversi&oacute;n: <?php echo $costo_curso; ?> Bs</div>
                         
                            <div class="col-md-6 col-sx-6 col-md-offset-6 col-md-offset-6 hi " align="right"><b style="color:red;"><i class="<?php echo $icon_modalidad_curso; ?>"></i> <?php echo $modalidad_curso; ?></b></div>
                            
                            <a style="font-weight: 600;font-size: 18px;cursor: pointer; color:#75b14c;text-decoration: underline;" onclick="showDetalleCursoTienda(<?=$rc2['dr_id_curso']?>)" class="col-md-8 col-md-8">
                                Ver detalles
                            </a>

                            <div class=" col-md-4 col-sx-4 hi  text-right"><a style="color: #00c728;font-weight: bold;font-size: 11pt;text-decoration: none;" target="_blank" href="https://api.whatsapp.com/send?phone=591<?php echo $num_whatsapp; ?>&text=Hola%2C%20tengo%20interes%20en%20<?php echo str_replace(' ','%20',$titulo_de_curso); ?>"><img src="contenido/alt/icono_whatsap.png"/> <?php echo $num_whatsapp; ?></a></div>
                        </div>

                         <button onclick="agregarAlCarrito(<?= $rc2['dr_id_curso'] ?>)" id="add-button-curso-<?= $rc2['dr_id_curso'] ?>" class=" btn btn-success btn-md center-block mt-3" style="margin:9px auto;display:<?= ($swCursoEncarrito?'none':'block') ?>;">
                            <i class="icon-plus"></i> AGREGAR AL CARRITO
                         </button>
                         <button onclick="quitarDelCarrito(<?= $rc2['dr_id_curso'] ?>)" id="remove-button-curso-<?= $rc2['dr_id_curso'] ?>" class=" btn btn-danger btn-md center-block mt-3" style="margin:9px auto;display:<?= ($swCursoEncarrito?'block':'none') ?>;">
                            <i class="icon-plus"></i> QUITAR DEL CARRITO
                         </button>
                </div>
                <br>
            </div>
            
            <?php
            $counter_aux++;

            if ($counter_aux % 3 == 0) {
                echo "<div class='courses-three-devider hidden-xs hidden-sm'></div>";
            }
            if ($counter_aux % 2 == 0) {
                echo "<div class='courses-two-devider'></div>";
            }
            ?>

            <?php
        }
        ?>
        <br>
    </section>
    <hr/>
</div>


<!-- ajax detalle curso -->
<script>
    function showDetalleCursoTienda(id_curso){
        $("#title-MODAL-general").html('CURSO');
        $("#body-MODAL-general").html('Cargando...');
        $("#MODAL-general").modal('show');
        $.ajax({
                url: 'contenido/paginas/ajax/ajax.tienda.showDetalleCursoTienda.php',
                data: {id_curso: id_curso},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#body-MODAL-general").html(data);
                }
        });
    }
</script>

<!-- ajax agregar al carrito -->
<script>
    function agregarAlCarrito(id_curso){
        $.ajax({
                url: 'contenido/paginas/ajax/ajax.tienda.agregarAlCarrito.php',
                data: {id_curso: id_curso},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#add-button-curso-" + id_curso).css('display','none');
                    $("#remove-button-curso-" + id_curso).css('display','block');
                    console.log(data);
                    resumenCarrito();
                }
        });
    }
</script>

<!-- ajax quitar del carrito -->
<script>
    function quitarDelCarrito(id_curso){
        $.ajax({
                url: 'contenido/paginas/ajax/ajax.tienda.quitarDelCarrito.php',
                data: {id_curso: id_curso},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#add-button-curso-" + id_curso).css('display','block');
                    $("#remove-button-curso-" + id_curso).css('display','none');
                    console.log(data);
                    resumenCarrito();
                }
            });
    }
</script>

<!-- ajax resumen del carrito -->
<script>
    function resumenCarrito(){
        $.ajax({
                url: 'contenido/paginas/ajax/ajax.tienda.resumenCarrito.php',
                type: 'GET',
                dataType: 'html',
                success: function (data) {
                    $("#resumen-carrito").html(data);
                }
        });
    }
</script>


<!-- mostrar resumen inicial del carrito -->
<script>
    $(document).ready(function (){
        resumenCarrito()
    })
</script>
