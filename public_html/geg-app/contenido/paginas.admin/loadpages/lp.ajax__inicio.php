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
?>

<!-- CONTENIDO DE PAGINA -->

<?php
/* mensaje */
$mensaje = "";
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
            <li><a href="<?php echo $dominio; ?>admin.php">Panel principal</a></li>
            <li><a>Inicio</a></li>
            <li class="active">Bienvenida</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <!--            <form action="" method="post">
                            <input type="text" name="buscar" class="form-control form-cascade-contro-l " size="20" placeholder="Buscar en el Sitio">
                            <span class="input-icon fui-search"></span>
                        </form>-->
        </div>
        <div class="text-center hidden-lg" style="border-bottom: 1px solid #dedede;padding-bottom: 10px;margin-bottom: 5px;padding-top: 5px;">
            <a href="cursos-infoact/1/no-search/todos/3.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/3', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">LP</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/1.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/1', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">CB</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/4.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/4', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">SC</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/6.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/6', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">CH</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/2.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/2', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">PT</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/8.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/8', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">OR</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/7.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/7', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">PD</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/9.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/9', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">BN</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/5.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/5', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">TJ</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/10.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/10', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #d6dae2;">VIR</a>
        </div>
        <span class="pull-right hidden-sm">
            <a href="cursos-infoact/1/no-search/todos/3.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/3', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">LP</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/1.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/1', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">CB</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/4.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/4', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">SC</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/6.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/6', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">CH</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/2.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/2', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">PT</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/8.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/8', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">OR</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/7.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/7', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">PD</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/9.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/9', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">BN</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/5.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/5', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #ebf1fb;">TJ</a>
            &nbsp;
            <a href="cursos-infoact/1/no-search/todos/10.adm" onclick="load_page('cursos-infoact', '1/no-search/todos/10', '');
            return false;" class="btn btn-default btn-xs" style="color: #1987ce;background: #d6dae2;">VIR</a>
        </span>
        <h3 class="page-header" style="padding: 5px 15px;margin: 0px;font-size: 12pt;"> PANEL DE ADMINISTRACI&Oacute;N | CURSOS.BO <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Bienvenido al panel de administraci&oacute;n
            </p>
        </blockquote>
    </div>
</div>

<?php echo $mensaje; ?>

<!-- data_participantes -->
<script>
    function data_participantes(id_curso) {
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.inicio.data_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-datapart-" + id_curso).html(data);
            }
        });
    }
</script>

<div class="row">
    <?php if (acceso_cod('adm-visibilidad-cursos')) { ?>
        <div class="col-md-12 text-center">
            <form id="FORM-actualiza_departamentos_visibles">
                <div style="padding:10px 0px;">
                    <?php
                    $rqddv1 = query("SELECT ids_departamentos_visibles FROM cursos_webdata WHERE id='1' ");
                    $rqddv2 = mysql_fetch_array($rqddv1);
                    $ids_departamentos_visibles = $rqddv2['ids_departamentos_visibles'];
                    $checked = '';
                    if ($ids_departamentos_visibles == '') {
                        $checked = ' checked="checked" ';
                    }
                    ?>
                    <label class="btn btn-xs btn-success">
                        <input type="checkbox" name="d-0" id="tcheck" onchange="actualiza_departamentos_visibles(0);" <?php echo $checked; ?>/>&nbsp;Todos
                    </label>
                    <?php
                    $rqddac1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                    while ($rqddac2 = mysql_fetch_array($rqddac1)) {
                        $checked = '';
                        if ($ids_departamentos_visibles == '' || (strpos(",$ids_departamentos_visibles,", "," . $rqddac2['id'] . ",") > 0)) {
                            $checked = ' checked="checked" ';
                        }
                        ?>
                        <label class="btn btn-xs btn-success">
                            <input type="checkbox" name="d-<?php echo $rqddac2['id']; ?>" class="dcheck" onchange="actualiza_departamentos_visibles(1);" <?php echo $checked; ?>/>&nbsp;
                            <?php echo $rqddac2['nombre']; ?>
                        </label>
                        <?php
                    }
                    ?>
                </div>
            </form>
        </div>
    <?php } ?>
    <div class="col-md-12">
        <?php
        /* qr organizador */
        $qr_organizador = "";
        if (isset_organizador()) {
            $id_organizador = organizador('id');
            $qr_organizador = " AND id_organizador='$id_organizador' ";
        }

        $rqc1 = query("SELECT id,titulo,titulo_identificador,id_ciudad,(select nombre from cursos_lugares where id=cursos.id_lugar)lugar,fecha,cnt_reproducciones,horarios FROM cursos WHERE estado='1' AND sw_siguiente_semana='0' AND fecha>='" . date("Y-m-d") . "' AND flag_publicacion IN ('1','2') $qr_organizador ORDER BY fecha ASC ");
        if (mysql_num_rows($rqc1) > 0) {
            ?>
            <div class="">
                <h2 style="background: #028c1d;
                    color: #FFF;
                    padding: 7px;
                    margin-top: 10px;
                    margin-bottom: 10px;
                    text-align: left;
                    font-weight: bold;
                    font-size: 10pt;
                    border-radius: 7px;"><a style="color:#FFF;">CURSOS ESTA SEMANA</a></h2>
                <div class="row">
                    <?php
                    while ($rqc2 = mysql_fetch_array($rqc1)) {
                        $img_departamento = 'bolivia.jpg';
                        $nombre_departamento = 'NIVEL NACIONAL';
                        $rqdep1 = query("SELECT c.nombre,d.imagen FROM ciudades c,departamentos d WHERE c.id_departamento=d.id AND c.id='" . $rqc2['id_ciudad'] . "' LIMIT 1 ");
                        if(mysql_num_rows($rqdep1)>0){
                            $rqdep2 = mysql_fetch_array($rqdep1);
                            $img_departamento = $rqdep2['imagen'];
                            $nombre_departamento = $rqdep2['nombre'];
                        }
                        ?>
                        <div class="col-md-6 col-lg-4" style="overflow:hidden;min-height: 120px;">
                            <div style="overflow:hidden;height: auto;
                                 border: 1px solid #c0c0c0;
                                 border-radius: 5px;
                                 background: #FFF;
                                 margin: 10px 0px;">
                                <div class="row">
                                    <div class="col-md-4 col-xs-3 text-center">
                                        <a>
                                            <img src='contenido/imagenes/departamentos/<?php echo $img_departamento; ?>' style="width:auto;max-width:100%;margin-top: 5px;"/>
                                        </a>
                                    </div>
                                    <div class="col-md-8 col-xs-9">
                                        <div style="height:35px;overflow: hidden;">
                                            <a href="<?php echo $rqc2['titulo_identificador']; ?>.html" style="color:#1f7491;font-size:9pt;line-height:0.5;padding-bottom:5px;" target="_blank">
                                                <?php echo $rqc2['titulo']; ?>
                                            </a>
                                        </div>

                                        <b style="color:#f98100;font-size:8pt;"><?php echo strtoupper($nombre_departamento); ?> - BOLIVIA</b>
                                        <br/>
                                        <span style='color:#6b737a;font-size:8pt;font-weight:bold;'>
                                            <?php echo mydatefechacurso($rqc2['fecha']); ?>
                                        </span>
                                        <br/>
                                        <div style="margin:1px 0px 8px 0px;">
                                            <span style='color:#6b737a;font-size:8pt;font-weight:bold;'>
                                                <?php echo $rqc2['lugar']; ?>
                                            </span>
                                            <span class="pull-right" style="background: orange;color:#FFF;border-radius: 5px;padding: 0px 5px;">
                                                <?php echo $rqc2['cnt_reproducciones']; ?> vistas
                                            </span>
                                        </div>
                                        <div class="hidden-sm">
                                            <a href="<?php echo $rqc2['titulo_identificador']; ?>.html" target="_blank" class="btn btn-xs btn-default"><i class='fa fa-eye'></i> Visualizar</a>
                                            &nbsp;&nbsp;
                                            <a href="cursos-editar/<?php echo $rqc2['id']; ?>.adm" class="btn btn-xs btn-default"><i class='fa fa-edit'></i> Editar</a>
                                            &nbsp;&nbsp;
                                            <a <?php echo loadpage('cursos-participantes/' . $rqc2['id']); ?> class="btn btn-xs btn-default"><i class='fa fa-users'></i> Inscritos</a>
                                        </div>
                                        <div class="hidden-md">
                                            <a href="<?php echo $rqc2['titulo_identificador']; ?>.html" target="_blank" class="btn btn-xs btn-default"><i class='fa fa-eye'></i></a>
                                            &nbsp;&nbsp;
                                            <a href="cursos-editar/<?php echo $rqc2['id']; ?>.adm" class="btn btn-xs btn-default"><i class='fa fa-edit'></i></a>
                                            &nbsp;&nbsp;
                                            <a <?php echo loadpage('cursos-participantes/' . $rqc2['id']); ?> class="btn btn-xs btn-default"><i class='fa fa-users'></i></a>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12 text-center" style="background: #ebf1fb;padding: 3px 0px;max-height: 23px;">
                                        Horarios: <?php echo $rqc2['horarios']; ?>
                                    </div>
                                    <div class="col-md-12 col-xs-12">
                                        <div id="box-datapart-<?php echo $rqc2['id']; ?>" style="min-height: 65px;">
                                            <div style="width:100%;margin-top:5px;"><div style="float:left;width:30%;;text-align:center;padding-top:4px;"><b style="font-size:14pt;color:#00789f;">..</b></div><div style="float:left;width:35%;"><span style="font-size:8pt;color:gray;">.. transferencia</span><br><span style="font-size:8pt;color:gray;">.. oficina</span><br><span style="font-size:8pt;color:gray;">.. dia del curso</span><br><span style="font-size:8pt;color:gray;">.. tigomoney</span></div><div style="float:left;width:35%;"><span style="font-size:8pt;color:gray;">.. khipu</span><br><span style="font-size:8pt;color:gray;">.. deposito</span><br><span style="font-size:8pt;color:gray;">.. sin pago</span></div><div style="clear:both;"></div></div>
                                            <script>data_participantes('<?php echo $rqc2['id']; ?>');</script>
                                        </div>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>

        <?php
        $rqc1 = query("SELECT id,titulo,titulo_identificador,id_ciudad,(select nombre from cursos_lugares where id=cursos.id_lugar)lugar,fecha FROM cursos WHERE estado='1' AND sw_siguiente_semana='1' AND fecha>='" . date("Y-m-d") . "' AND flag_publicacion IN ('1','2') $qr_organizador ORDER BY fecha ASC ");
        if (mysql_num_rows($rqc1) > 0) {
            ?>
            <div class="none">
                <h3 style="    background: #028c1d;
                    color: #FFF;
                    padding: 7px;
                    margin-top: 10px;
                    margin-bottom: 10px;
                    text-align: left;
                    font-weight: bold;
                    font-size: 10pt;
                    border-radius: 7px;">CURSOS INFOSICOES DE LA SIGUIENTE SEMANA</h3>
                    <?php
                    while ($rqc2 = mysql_fetch_array($rqc1)) {
                        $rqdep1 = query("SELECT c.nombre,d.imagen FROM ciudades c,departamentos d WHERE c.id_departamento=d.id AND c.id='" . $rqc2['id_ciudad'] . "' LIMIT 1");
                        $rqdep2 = mysql_fetch_array($rqdep1);
                        ?>
                    <div class="col-md-6 col-lg-4" style="overflow:hidden;min-height: 120px;">
                        <div style="overflow:hidden;height: auto;
                             border: 1px solid #c0c0c0;
                             border-radius: 5px;
                             background: #FFF;
                             margin: 10px 0px;">
                            <div class="row">
                                <div class="col-md-4 col-xs-3">
                                    <a>
                                        <img src='https://www.infosicoes.com/contenido/imagenes/ciudades/<?php echo $rqdep2['imagen']; ?>' style="width:auto;max-width:100%;margin-top: 5px;"/>
                                    </a>
                                </div>
                                <div class="col-md-8 col-xs-9">
                                    <div style="height:35px;overflow: hidden;">
                                        <a href="<?php echo $rqc2['titulo_identificador']; ?>.html" style="color:#1f7491;font-size:9pt;line-height:0.5;padding-bottom:5px;" target="_blank">
                                            <?php echo $rqc2['titulo']; ?>
                                        </a>
                                    </div>

                                    <b style="color:#f98100;font-size:8pt;"><?php echo strtoupper($rqdep2['nombre']); ?> - BOLIVIA</b>
                                    <br/>
                                    <span style='color:#6b737a;font-size:8pt;font-weight:bold;'>
                                        <?php echo mydatefechacurso($rqc2['fecha']); ?>
                                    </span>
                                    <br/>
                                    <div style="margin:1px 0px 8px 0px;">
                                        <span style='color:#6b737a;font-size:8pt;font-weight:bold;'>
                                            <?php echo $rqc2['lugar']; ?>
                                        </span>
                                        <span class="pull-right" style="background: orange;color:#FFF;border-radius: 5px;padding: 0px 5px;">
                                            <?php echo $rqc2['cnt_reproducciones']; ?> vistas
                                        </span>
                                    </div>
                                    <div class="hidden-sm">
                                        <a href="<?php echo $rqc2['titulo_identificador']; ?>.html" target="_blank" class="btn btn-xs btn-default"><i class='fa fa-eye'></i> Visualizar</a>
                                        &nbsp;&nbsp;
                                        <a href="cursos-editar/<?php echo $rqc2['id']; ?>.adm" class="btn btn-xs btn-default"><i class='fa fa-edit'></i> Editar</a>
                                        &nbsp;&nbsp;
                                        <a href="cursos-participantes/<?php echo $rqc2['id']; ?>.adm" class="btn btn-xs btn-default"><i class='fa fa-users'></i> Inscritos</a>
                                    </div>
                                    <div class="hidden-md">
                                        <a href="<?php echo $rqc2['titulo_identificador']; ?>.html" target="_blank" class="btn btn-xs btn-default"><i class='fa fa-eye'></i></a>
                                        &nbsp;&nbsp;
                                        <a href="cursos-editar/<?php echo $rqc2['id']; ?>.adm" class="btn btn-xs btn-default"><i class='fa fa-edit'></i></a>
                                        &nbsp;&nbsp;
                                        <a href="cursos-participantes/<?php echo $rqc2['id']; ?>.adm" class="btn btn-xs btn-default"><i class='fa fa-users'></i></a>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 text-center" style="background: #ebf1fb;padding: 3px 0px;max-height: 23px;">
                                    Horarios: <?php echo $rqc2['horarios']; ?>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div id="box-datapart-<?php echo $rqc2['id']; ?>">
                                        ...
                                        <script>data_participantes('<?php echo $rqc2['id']; ?>');</script>
                                    </div>
                                    <br/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>

            </div>
            <div class="clear"></div>
            <?php
        }
        ?>
    </div>
</div>

<hr/>

<!-- actualiza_departamentos_visibles -->
<script>
    function actualiza_departamentos_visibles(dat) {
        if (dat === 0) {
            $(".dcheck").prop("checked", true);
        } else if (dat === 1) {
            $("#tcheck").prop("checked", false);
        }
        var form = $("#FORM-actualiza_departamentos_visibles").serialize();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.inicio.actualiza_departamentos_visibles.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                /*$("#TR-AJAXBOX-reprogramacion_de_curso").html(data);*/
            }
        });
    }
</script>

<?php

function mydatefechacurso($dat) {
    $day = date("w", strtotime($dat));
    $arf1 = explode("-", $dat);
    $array_meses = array('NONE', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
    $array_dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
    return $array_dias[(int) $day] . " " . $arf1[2] . " de " . ucfirst($array_meses[(int) ($arf1[1])]);
}
