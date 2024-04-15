<li><a <?php echo loadpage('cursos-listar'); ?> class="btn btn-xs btn-info active">CURSOS PRESENCIALES</a></li>
<?php
$rqdct1 = query("SELECT COUNT(*) AS total FROM cursos WHERE fecha=CURDATE() AND estado IN (1) ");
$rqdct2 = mysql_fetch_array($rqdct1);
if ($rqdct2['total'] > 0) {
    ?>
    <li><a <?php echo loadpage('cursos-listar/1/no-search/hoy'); ?> class="btn btn-xs btn-primary active">(<?php echo $rqdct2['total']; ?>) CURSOS HOY</a></li>
    <?php
}
?>
<?php
$rqdct1 = query("SELECT COUNT(*) AS total FROM cursos WHERE estado='1' ");
$rqdct2 = mysql_fetch_array($rqdct1);
if ($rqdct2['total'] > 0) {
    ?>
    <li><a <?php echo loadpage('cursos-listar/1/no-search/activos'); ?> class="btn btn-xs btn-success active">(<?php echo $rqdct2['total']; ?>) CURSOS ACTIVOS</a></li>
    <?php
}
?>
<?php
$rqdct1 = query("SELECT COUNT(*) AS total FROM cursos WHERE estado='2' ");
$rqdct2 = mysql_fetch_array($rqdct1);
if ($rqdct2['total'] > 0) {
    ?>
    <li><a <?php echo loadpage('cursos-listar/1/no-search/temporales'); ?> class="btn btn-xs btn-danger active">(<?php echo $rqdct2['total']; ?>) CURSOS TEMPORALES</a></li>
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
    <a href="cursos-listar/1/no-search/todos/10.adm" onclick="load_page('cursos-listar', '1/no-search/todos/10', '');
            return false;" class="btn btn-danger btn-xs" style="color: #FFF;background: #ce1921;">VIR</a>
    &nbsp;
</li>
<!--<li class="active"> | </li>-->