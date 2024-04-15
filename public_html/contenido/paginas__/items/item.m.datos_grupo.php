<?php
/* var $curso needed */

/* datos lugar */
$rqdl1 = query("SELECT * FROM cursos_lugares WHERE id='" . $curso['id_lugar'] . "' ");
$rqdl2 = fetch($rqdl1);
$lugar_nombre = $rqdl2['nombre'];
$lugar_salon = $rqdl2['salon'];
$lugar_direccion = $rqdl2['direccion'];
$lugar_google_maps = $rqdl2['google_maps'];

/* ciudad departemento */
$curso_id_ciudad = $curso['id_ciudad'];
$rqdcd1 = query("SELECT d.nombre AS departamento, c.nombre AS ciudad FROM departamentos d INNER JOIN ciudades c ON c.id_departamento=d.id WHERE c.id='$curso_id_ciudad' ");
$rqdcd2 = fetch($rqdcd1);
$curso_nombre_departamento = $rqdcd2['departamento'];
$curso_nombre_ciudad = $rqdcd2['ciudad'];
$curso_text_ciudad = $curso_nombre_ciudad;
if($curso_nombre_departamento!==$curso_nombre_ciudad){
    $curso_text_ciudad = $curso_nombre_ciudad.' - '.$curso_nombre_departamento;
}
?>
<div class="panel">
    <div class="panel-body">

        <div class="col-md-3 col-xs-5 text-center">
            <?php
            $url_imagen = "https://www.infosicoes.com/contenido/imagenes/images/banner-cursos.png";
            if ($curso['imagen'] !== '') {
                $url_imagen = $domino.'paginas/' . $curso['imagen'] . '.size=4.img';
            }
            ?>
            <img src="<?php echo $url_imagen; ?>" style="width: 70%;
                 border: 2px solid orange;
                 padding: 1px;
                 border-radius: 7px;"/>
            <br/>
            <br/>
        </div>
        <div class="col-md-9 col-xs-7">

            <b><?php echo $curso['titulo']; ?></b>
            <br/>
            <br/>

            <div class="row">
                <div class="col-md-6">
                    <b style="color:gray;">Fecha:</b> &nbsp; <?php echo fecha_curso($curso['fecha']); ?>
                </div>
                <?php if($curso['id_modalidad']!=='3' && $curso['id_modalidad']!=='2'){ ?>
                <div class="col-md-6">
                    <b style="color:gray;">Lugar:</b> &nbsp; <?php echo $lugar_nombre; ?>
                </div>
                <?php }else{ ?>
                <div class="col-md-6">
                    <b style="color:gray;">Modalidad:</b> &nbsp; VIRTUAL
                </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <b style="color:gray;">Horarios:</b> &nbsp; 
                    <?php
                    if (isset($id_turno) && $id_turno!=='' && $id_turno!=='0') {
                        $rqdtt1 = query("SELECT titulo FROM cursos_turnos WHERE id='$id_turno' LIMIT 1 ");
                        $rqdtt2 = fetch($rqdtt1);
                        echo 'Turno ' . $rqdtt2['titulo'];
                    } elseif ($curso['horarios'] !== '') {
                        echo $curso['horarios'];
                    } else {
                        echo 'Consulte en detalles';
                    }
                    ?>
                </div>
                <?php if($curso['id_modalidad']!=='3' && $curso['id_modalidad']!=='2'){ ?>
                <div class="col-md-6">
                    <b style="color:gray;">Sal&oacute;n:</b> &nbsp; 
                    <?php
                    if ($lugar_salon !== '') {
                        echo $lugar_salon;
                    } else {
                        echo 'Consulte en detalles';
                    }
                    ?>
                </div>
                <?php } ?>
<!--            </div>
            <div class="row">-->
                <?php
                $costo = $curso['costo'];
                if ($curso['sw_fecha2'] == '1' && (date("Y-m-d") <= $curso['fecha2'])) {
                    $costo = $curso['costo2'];
                }
                if ($curso['sw_fecha3'] == '1' && (date("Y-m-d") <= $curso['fecha3'])) {
                    $costo = $curso['costo3'];
                }
                ?>
                <div class="col-md-6">
                    <b style="color:gray;">Costo por participante:</b> 
                    <?php
                    if ($costo > 0) {
                        echo $costo . ' Bs.';
                    } else {
                        echo 'Consulte en detalles';
                    }
                    ?>
                </div>
                <?php if($curso['id_modalidad']!=='3' && $curso['id_modalidad']!=='2'){ ?>
                <div class="col-md-6">
                    <b style="color:gray;">Ciudad:</b> &nbsp; <?php echo $curso_text_ciudad; ?>
                </div>
                <?php } ?>
            </div>
            <br/>
            <a href='grupo/<?php echo $curso['titulo_identificador']; ?>.html' target='_blank' style='text-decoration:underline;'>Ver detalles del curso >></a>
        </div>

    </div>
</div>
