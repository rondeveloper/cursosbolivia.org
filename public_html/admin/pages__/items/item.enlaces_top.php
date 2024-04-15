<li><a <?php echo loadpage('cursos-listar'); ?> class="btn btn-xs btn-info active" style="background: #adadad;">TODOS</a></li>
<li><a <?php echo loadpage('cursos-listar/1/no-search/por-activar'); ?> class="btn btn-xs btn-default active" style="background: #FFF;border: 1px solid gray;"> POR ACTIVAR</a></li>
<li><a <?php echo loadpage('cursos-listar/1/no-search/hoy'); ?> class="btn btn-xs btn-success active" style="background: #55cfec;">(<?php echo $gl_cnt_hoy; ?>) HOY</a></li>
<li><a <?php echo loadpage('cursos-listar/1/no-search/mis-cursos'); ?> class="btn btn-xs btn-success active" style="background: #e2bd38;">(<?php echo $gl_cnt_miscursos; ?>) MIS CURSOS</a></li>
<?php
$rqdct1 = query("SELECT total FROM ids_bloques WHERE id='1' LIMIT 1 ");
$rqdct2 = fetch($rqdct1);
if ($rqdct2['total'] > 0) {
    ?>
    <li><a <?php echo loadpage('cursos-listar/1/no-search/activos'); ?> class="btn btn-xs btn-success active">(<?php echo $rqdct2['total']; ?>) ACTIVOS</a></li>
    <?php
}
?>
<?php
$rqdct1 = query("SELECT total FROM ids_bloques WHERE id='2' LIMIT 1 ");
$rqdct2 = fetch($rqdct1);
if ($rqdct2['total'] > 0) {
    ?>
    <li><a <?php echo loadpage('cursos-listar/1/no-search/temporales'); ?> class="btn btn-xs btn-danger active" style="background: #f75b2d;">(<?php echo $rqdct2['total']; ?>) TEMPORALES</a></li>
    <?php
}
?>
<?php
$rqdct1 = query("SELECT total FROM ids_bloques WHERE id='3' LIMIT 2 ");
$rqdct2 = fetch($rqdct1);
if ($rqdct2['total'] > 0) {
    ?>
    <li><a href="cursos-listar/1/no-search/todos/10.adm" <?php echo loadpage('cursos-listar/1/no-search/virtual'); ?> class="btn btn-warning btn-xs active" style="color: #FFF;background: #0043ff;">(<?php echo $rqdct2['total']; ?>) VIRTUALES</a></li>
    <?php
}
?>
<?php
$rqdct1 = query("SELECT total FROM ids_bloques WHERE id='4' LIMIT 1 ");
$rqdct2 = fetch($rqdct1);
if ($rqdct2['total'] > 0) {
    ?>
    <li><a <?php echo loadpage('cursos-listar/1/no-search/presencial'); ?> class="btn btn-warning btn-xs active" style="color: #FFF;background: #1987ce;">(<?php echo $rqdct2['total']; ?>) PRESENCIAL</a></li>
    <?php
}
?>
<?php
$rqdct1 = query("SELECT total FROM ids_bloques WHERE id='5' LIMIT 2 ");
$rqdct2 = fetch($rqdct1);
if ($rqdct2['total'] > 0) {
    ?>
    <li><a <?php echo loadpage('cursos-listar/1/no-search/pregrabado'); ?> class="btn btn-warning btn-xs active" style="color: #FFF;background: #1987ce;">(<?php echo $rqdct2['total']; ?>) PREGRABADO</a></li>
    <?php
}
?>
<?php
$rqdct1 = query("SELECT total FROM ids_bloques WHERE id='6' LIMIT 2 ");
$rqdct2 = fetch($rqdct1);
if ($rqdct2['total'] > 0) {
    ?>
    <li><a <?php echo loadpage('cursos-listar/1/no-search/en-vivo'); ?> class="btn btn-warning btn-xs active" style="color: #FFF;background: #1987ce;">(<?php echo $rqdct2['total']; ?>) EN VIVO</a></li>
    <?php
}
?>
<li class="pull-right">
    <a href="cursos-listar/1/no-search/todos/3.adm" onclick="load_page('cursos-listar', '1/no-search/todos/3', '');
        return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #1987ce;">LP</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/1.adm" onclick="load_page('cursos-listar', '1/no-search/todos/1', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #1987ce;">CB</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/4.adm" onclick="load_page('cursos-listar', '1/no-search/todos/4', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #1987ce;">SC</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/6.adm" onclick="load_page('cursos-listar', '1/no-search/todos/6', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #1987ce;">CH</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/2.adm" onclick="load_page('cursos-listar', '1/no-search/todos/2', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #1987ce;">PT</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/8.adm" onclick="load_page('cursos-listar', '1/no-search/todos/8', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #1987ce;">OR</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/7.adm" onclick="load_page('cursos-listar', '1/no-search/todos/7', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #1987ce;">PD</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/9.adm" onclick="load_page('cursos-listar', '1/no-search/todos/9', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #1987ce;">BN</a>
    &nbsp;
    <a href="cursos-listar/1/no-search/todos/5.adm" onclick="load_page('cursos-listar', '1/no-search/todos/5', '');
            return false;" class="btn btn-default btn-xs" style="color: #FFF;background: #1987ce;">TJ</a>
    &nbsp;
</li>
<!--<li class="active"> | </li>-->