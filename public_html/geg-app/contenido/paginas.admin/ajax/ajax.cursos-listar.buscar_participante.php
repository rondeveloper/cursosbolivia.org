<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$dat = post('dat');
$modcourse = post('modcourse');

$qr_modalidad_curso = " c.id_modalidad IN (1) ";
if($modcourse=='virtual'){
    $qr_modalidad_curso = " c.id_modalidad IN (2,3) ";
}

if ($dat != '') {
    ?>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <tr>
            <th>Nombre</th>
            <th></th>
            <th>Curso</th>
            <th>Celular</th>
            <th>Correo</th>
        </tr>
        <?php
        $rqdp1 = query("SELECT p.nombres,p.apellidos,p.correo,p.celular,c.titulo,c.id,(p.id)dr_id_participante FROM cursos_participantes p INNER JOIN cursos c ON c.id=p.id_curso WHERE $qr_modalidad_curso AND ( CONCAT(p.nombres,' ',p.apellidos) LIKE '%$dat%' OR p.correo LIKE '%$dat%' OR p.ci='$dat' OR p.celular='$dat' ) ORDER BY p.id DESC limit 10 ");
        while ($rqdp2 = mysql_fetch_array($rqdp1)) {
            ?>
            <tr>
                <td><?php echo $rqdp2['nombres'].' '.$rqdp2['apellidos']; ?></td>
                <td><a href="cursos-participantes/<?php echo $rqdp2['id']; ?>/no-turn/<?php echo $rqdp2['dr_id_participante']; ?>.adm" class="btn btn-xs btn-primary">Panel</a></td>
                <td><?php echo $rqdp2['titulo']; ?></td>
                <td><?php echo $rqdp2['celular']; ?></td>
                <td><?php echo $rqdp2['correo']; ?> </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
    <?php
}
