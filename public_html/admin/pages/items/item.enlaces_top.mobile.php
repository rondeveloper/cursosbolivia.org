<?php
$id_administrador = administrador('id');
$rqmcqr_a1 = query("SELECT ids FROM ids_bloques WHERE id IN (1,2) ");
$rqmcqr_a2 = fetch($rqmcqr_a1);
$ids_a = $rqmcqr_a2['ids'];
$rqmcqr_a2 = fetch($rqmcqr_a1);
$ids_b = $rqmcqr_a2['ids'];
$rqmcqr_b1 = query("SELECT count(*) AS total FROM cursos_rel_cursowapnum WHERE ( id_curso IN ($ids_a) OR id_curso IN ($ids_b) ) AND id_whats_numero IN (select id from whatsapp_numeros where id_administrador='$id_administrador') ");
$rqmcqr_b2 = fetch($rqmcqr_b1);
$gl_cnt_miscursos = $rqmcqr_b2['total'];
?>
<div class="row" style="padding-bottom: 5px;border-bottom: 1px solid #e2e2e2;margin-bottom: 5px;">
    <div class="col-xs-4 col-sm-4">
        <a <?php echo loadpage('cursos-listar'); ?> class="btn btn-xs btn-warning btn-block active" style="background: #adadad;">TODOS</a>
    </div>
    <div class="col-xs-4 col-sm-4">
        <a <?php echo loadpage('cursos-listar/1/no-search/por-activar'); ?> class="btn btn-xs btn-default btn-block active" style="background: #FFF;border: 1px solid gray;">POR ACTIVAR</a>
    </div>
    <?php
    $rqdct1 = query("SELECT COUNT(*) AS total FROM cursos WHERE estado='1' AND fecha=CURDATE() ");
    $rqdct2 = fetch($rqdct1);
    $gl_cnt_hoy = $rqdct2['total'];
    if ($gl_cnt_hoy > 0) {
        ?>
        <div class="col-xs-4 col-sm-4">
            <a <?php echo loadpage('cursos-listar/1/no-search/hoy'); ?> class="btn btn-xs btn-success btn-block active" style="background: #55cfec;">(<?php echo $gl_cnt_hoy; ?>) HOY</a>
        </div>
        <?php
    }
    ?>
    <div class="col-xs-4 col-sm-4">
        <a <?php echo loadpage('cursos-listar/1/no-search/mis-cursos'); ?> class="btn btn-xs btn-success btn-block active" style="background: #e2bd38;">(<?php echo $gl_cnt_miscursos; ?>) MIS CURSOS</a>
    </div>
    <?php
    $rqdct1 = query("SELECT COUNT(*) AS total FROM cursos WHERE estado='1' ");
    $rqdct2 = fetch($rqdct1);
    if ($rqdct2['total'] > 0) {
        ?>
        <div class="col-xs-4 col-sm-4">
            <a <?php echo loadpage('cursos-listar/1/no-search/activos'); ?> class="btn btn-xs btn-success btn-block active">(<?php echo $rqdct2['total']; ?>) ACTIVO</a>
        </div>
        <?php
    }
    ?>
    <?php
    $rqdct1 = query("SELECT COUNT(*) AS total FROM cursos WHERE estado='2' ");
    $rqdct2 = fetch($rqdct1);
    if ($rqdct2['total'] > 0) {
        ?>
        <div class="col-xs-4 col-sm-4">
            <a <?php echo loadpage('cursos-listar/1/no-search/temporales'); ?> class="btn btn-xs btn-danger btn-block active" style="background: #f75b2d;">(<?php echo $rqdct2['total']; ?>) TEMPORAL</a>
        </div>
        <?php
    }
    ?>
    <div class="col-xs-4 col-sm-4">
        <a href="cursos-listar/1/no-search/todos/10.adm" <?php echo loadpage('cursos-listar/1/no-search/virtual'); ?> class="btn btn-warning btn-block btn-xs active" style="color: #FFF;background: #0043ff;">VIRTUAL</a>
    </div>
</div>
<div class="text-center" style="border-bottom: 1px solid #e2e2e2;padding-bottom: 5px;margin-bottom: 5px;">
    <a href="cursos-listar/1/no-search/todos/3.adm" onclick="load_page('cursos-listar', '1/no-search/todos/3', '');
        return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #11a1da;">LP</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/1.adm" onclick="load_page('cursos-listar', '1/no-search/todos/1', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #11a1da;">CB</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/4.adm" onclick="load_page('cursos-listar', '1/no-search/todos/4', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #11a1da;">SC</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/6.adm" onclick="load_page('cursos-listar', '1/no-search/todos/6', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #11a1da;">CH</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/2.adm" onclick="load_page('cursos-listar', '1/no-search/todos/2', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #11a1da;">PT</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/8.adm" onclick="load_page('cursos-listar', '1/no-search/todos/8', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #11a1da;">OR</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/7.adm" onclick="load_page('cursos-listar', '1/no-search/todos/7', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #11a1da;">PD</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/9.adm" onclick="load_page('cursos-listar', '1/no-search/todos/9', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #11a1da;">BN</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/5.adm" onclick="load_page('cursos-listar', '1/no-search/todos/5', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #11a1da;">TJ</a>
</div>