<div class="row" style="padding-bottom: 5px;border-bottom: 1px solid #e2e2e2;margin-bottom: 5px;">
    <div class="col-xs-3 col-sm-3">
        <a <?php echo loadpage('cursos-listar'); ?> class="btn btn-xs btn-block btn-info active">PRESENCIAL</a>
    </div>
    <?php
    $rqdct1 = query("SELECT COUNT(*) AS total FROM cursos WHERE fecha=CURDATE() AND estado IN (1) ");
    $rqdct2 = mysql_fetch_array($rqdct1);
    if ($rqdct2['total'] > 0) {
        ?>
        <div class="col-xs-3 col-sm-3">
            <a <?php echo loadpage('cursos-listar/1/no-search/hoy'); ?> class="btn btn-xs btn-primary btn-block active">(<?php echo $rqdct2['total']; ?>) HOY</a>
        </div>
        <?php
    }
    ?>
    <?php
    $rqdct1 = query("SELECT COUNT(*) AS total FROM cursos WHERE estado='1' ");
    $rqdct2 = mysql_fetch_array($rqdct1);
    if ($rqdct2['total'] > 0) {
        ?>
        <div class="col-xs-3 col-sm-3">
            <a <?php echo loadpage('cursos-listar/1/no-search/activos'); ?> class="btn btn-xs btn-success btn-block active">(<?php echo $rqdct2['total']; ?>) ACTIVO</a>
        </div>
        <?php
    }
    ?>
    <?php
    $rqdct1 = query("SELECT COUNT(*) AS total FROM cursos WHERE estado='2' ");
    $rqdct2 = mysql_fetch_array($rqdct1);
    if ($rqdct2['total'] > 0) {
        ?>
        <div class="col-xs-3 col-sm-3">
            <a <?php echo loadpage('cursos-listar/1/no-search/temporales'); ?> class="btn btn-xs btn-danger btn-block active">(<?php echo $rqdct2['total']; ?>) TEMPORAL</a>
        </div>
        <?php
    }
    ?>
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
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/10.adm" onclick="load_page('cursos-listar', '1/no-search/todos/10', '');
            return false;" class="btn btn-danger btn-xs" style="color: #FFF;background: #ce1921;">VIR</a>
    &nbsp;
</div>