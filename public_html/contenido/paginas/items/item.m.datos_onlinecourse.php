<?php
/* var $onlinecourse,$sw_acceso_a_curso needed */
?>
<style>
    .box-datauser{
        border: 1px solid #2e6c88;
        border-radius: 5px;
        background: #eff9fd;
        color: #1b5b77;
        padding: 7px 10px;
    }
</style>
<div class="panel" style="border: 1px solid #bdbdbd;border-radius: 5px;">
    <div class="panel-body" style="padding: 5px;">
        <div class="col-md-3 col-xs-6 text-left">
            <?php
            $url_imagen = "https://www.infosiscon.com/contenido/imagenes/images/banner-cursos.png";
            if ($onlinecourse['imagen'] !== '') {
                $url_imagen = $domino.'cursos/' . $onlinecourse['imagen'] . '.size=4.img';
            }
            ?>
            <img src="<?php echo $url_imagen; ?>" style="width: 50%;
                 border: 2px solid orange;
                 padding: 1px;
                 border-radius: 7px;"/>
        </div>
        <?php
        if (isset_usuario()) {
            $id_usuario = usuario('id');
            $rqdu1 = query("SELECT nombres,apellidos FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
            $rqdu2 = fetch($rqdu1);
            $nombre_usuario = trim($rqdu2['nombres'] . ' ' . $rqdu2['apellidos']);
            ?>
            <div class="col-md-5 col-xs-6">
                <b><?php echo $onlinecourse['titulo']; ?></b>
                <br/>
                <br/>
                <div class="row">
                    <div class="col-md-6">
                        <b style="color:gray;">CURSO VIRTUAL</b>
                    </div>
                    <div class="col-md-6">
                        <b style="color:gray;">Organizador:</b> &nbsp; NEMABOL
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-12">
                <div class="box-datauser">
                    <img src="<?php echo $domino; ?>contenido/imagenes/images/user.png" style="width:30px;height: 30px;"/>
                    <b><?php echo $nombre_usuario; ?></b>
                    <b class="pull-right">U000<?php echo $id_usuario; ?></b>
                    <?php
                    $rqdvpi1 = query("SELECT fecha_inicio,fecha_final FROM cursos_onlinecourse_acceso WHERE id_usuario='$id_usuario' AND id_onlinecourse='" . $onlinecourse['id'] . "' AND sw_acceso='1' ");
                    if (num_rows($rqdvpi1) > 0) {
                        $rqdvpi2 = fetch($rqdvpi1);
                        $fecha_inicio_cv = $rqdvpi2['fecha_inicio'];
                        //$fecha_final_cv = $rqdvpi2['fecha_final'];
                        $fecha_final_cv = date("Y-m-d", strtotime("+2 month", strtotime($rqdvpi2['fecha_inicio'])));
                        /* aux */
                        $rqdadcv1 = query("SELECT r.fecha_inicio,r.fecha_final FROM cursos_participantes p INNER JOIN cursos_rel_cursoonlinecourse r ON r.id_curso=p.id_curso INNER JOIN cursos c ON c.id=p.id_curso WHERE p.id_usuario='$id_usuario' AND r.id_onlinecourse='" . $onlinecourse['id'] . "' AND p.estado='1' ");
                        $rqdadcv2 = fetch($rqdadcv1);
                        $fecha_inicio_cv = $rqdadcv2['fecha_inicio'];
                        $fecha_final_cv = $rqdadcv2['fecha_final'];
                        /* END aux */
                        ?>
                        <div style="text-align:center;color:gray;">
                            Inicia: <?php echo date("d/m/Y", strtotime($fecha_inicio_cv)); ?>, finaliza: <?php echo date("d/m/Y", strtotime($fecha_final_cv)); ?>
                        </div>
                    <?php } else { ?>
                        <br/><br/>
                        <?php
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <b style="color:gray;">[ ESTUDIANTE ]</b>
                        </div>
                        <div class="col-md-6">
                            <?php
                            if ($sw_acceso_a_curso) {
                                echo "ACCESO PERMITIDO";
                            } else {
                                echo "ACCESO DENEGADO";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } elseif (isset_docente()) {
            $id_docente = docente('id');
            $rqdu1 = query("SELECT * FROM cursos_docentes WHERE id='$id_docente' ORDER BY id DESC limit 1 ");
            $rqdu2 = fetch($rqdu1);
            $nombre_docente = trim($rqdu2['prefijo'] . ' ' . $rqdu2['nombres'] . ' ' . $rqdu2['apellidos']);
            ?>
            <div class="col-md-5 col-xs-6">
                <b><?php echo $onlinecourse['titulo']; ?></b>
                <br/>
                <br/>
                <div class="row">
                    <div class="col-md-6">
                        <b style="color:gray;">CURSO VIRTUAL</b>
                    </div>
                    <div class="col-md-6">
                        <b style="color:gray;">Organizador:</b> &nbsp; NEMABOL
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-12">
                <div class="box-datauser">
                    <img src="<?php echo $domino; ?>contenido/imagenes/images/user.png" style="width:30px;height: 30px;"/>
                    <b><?php echo $nombre_docente; ?></b>
                    <b class="pull-right">D000<?php echo $id_docente; ?></b>

                    <br/><br/>

                    <div class="row">
                        <div class="col-md-6">
                            <b style="color:gray;">[ DOCENTE ]</b>
                        </div>
                        <div class="col-md-6">
                            <?php
                            if ($sw_acceso_a_curso) {
                                echo "ACCESO PERMITIDO";
                            } else {
                                echo "ACCESO DENEGADO";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="col-md-9 col-xs-6">
                <b><?php echo $onlinecourse['titulo']; ?></b>
                <br/>
                <br/>

                <div class="row">
                    <div class="col-md-6">
                        <b style="color:gray;">CURSO VIRTUAL</b>
                    </div>
                    <div class="col-md-6">
                        <b style="color:gray;">Organizador:</b> &nbsp; NEMABOL
                    </div>
                </div>            
            </div>
            <?php
        }
        ?>
    </div>
</div>
