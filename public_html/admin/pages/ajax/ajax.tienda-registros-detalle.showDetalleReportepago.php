<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_tienda_registro = post('id_tienda_registro');

$rq_reporte_pago1 = query("SELECT c.titulo FROM rel_curso_tienda_registro AS rctr LEFT JOIN cursos AS c ON rctr.id_curso = c.id WHERE rctr.id_registro='$id_tienda_registro' ORDER BY rctr.id DESC");

?>

<?php
$cnt = 1;
?>
<table class="table table-striped table-bordered">
   <thead>
    <tr>
        <th>Nro</th>
        <th>CURSO</th>
    </tr>
   </thead>
   <tbody>
    <?php while($rq_reporte_pago2 = fetch($rq_reporte_pago1)){ ?>
        <tr>
            <td><?= $cnt ?></td>
            <td><?= $rq_reporte_pago2['titulo'] ?></td>
        </tr>
    <?php 
    $cnt ++ ;
      }; 
      ?>
   </tbody>
</table>

