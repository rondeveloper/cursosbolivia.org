<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {
    $codigo = post('codigo');

    $r1 = query("SELECT * FROM empresas WHERE codigo_cliente='$codigo' ");
    
    if (num_rows($r1) > 0) {
        $r2 = fetch($r1);
        ?>
        <h1>Empresa: <?php echo $r2['nombre_empresa']; ?></h1>
        <?php
    } else {
        echo "NO EXISTE! una empresa con el codigo de cliente: $codigo";
    }
} else {

    echo "Acceso denegado!";
}
?>


<br/>
<hr/>
<br/>