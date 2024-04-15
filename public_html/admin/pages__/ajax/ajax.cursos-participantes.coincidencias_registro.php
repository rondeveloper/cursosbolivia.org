<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$id_participante = post('id_participante');


$qrdp1 = query("SELECT nombres,apellidos FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$qrdp2 = fetch($qrdp1);
$nombres = $qrdp2['nombres'];
$apellidos = $qrdp2['apellidos'];

$dat = str_replace(array(' ','.',',','-'),'%',trim($nombres.' '.$apellidos));

$rqcr1 = query("SELECT r.codigo,r.fecha_registro,r.imagen_deposito,c.titulo,(c.id)dr_id_curso,(p.id)dr_id_participante FROM cursos_proceso_registro r INNER JOIN cursos_participantes p ON p.id_proceso_registro=r.id INNER JOIN cursos c ON r.id_curso=c.id WHERE CONCAT(p.nombres,' ',p.apellidos) LIKE '%$dat%' ");
$num_registros = num_rows($rqcr1);
?>
<div class="text-center">
    <b>Participante:</b>
    <h3><?php echo $nombres.' '.$apellidos;?></h3>
    <h4><?php echo $num_registros; ?> registros</h4>
</div>
<hr>
<table class="table table-bordered">
<?php
$cnt = 1;
while($rqcr2 = fetch($rqcr1)){
    ?>
    <tr>
        <td><?php echo $cnt++; ?></td>
        <td><b><?php echo $rqcr2['codigo']; ?></b></td>
        <td>
            <?php echo $rqcr2['titulo']; ?>
            <br>
            <br>
            <a href="cursos-participantes/<?php echo $rqcr2['dr_id_curso']; ?>/no-turn/<?php echo $rqcr2['dr_id_participante']; ?>.adm" class="btn btn-xs btn-primary" target="_blank">
                Panel de curso
            </a>
        </td>
        <td><?php echo $rqcr2['fecha_registro']; ?></td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="2" class="text-center">
            <?php
            if($rqcr2['imagen_deposito']!=''){
                echo '<img src="'.$dominio_www.'contenido/imagenes/depositos/'.$rqcr2['imagen_deposito'].'" style="width:100%;"/>';
                echo '<br><a href="'.$dominio_www.'contenido/imagenes/depositos/'.$rqcr2['imagen_deposito'].'" target="_blank">Ver imagen</a>';
            }else{
                echo '<b>SIN COMPROBANTE DE PAGO</b>';
            }
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="4"><br><br></td>
    </tr>
    <?php
}
?>
</table>