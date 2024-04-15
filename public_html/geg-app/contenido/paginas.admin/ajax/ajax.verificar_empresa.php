<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {
    $codigo = post('codigo');

    $r1 = query("SELECT * FROM empresas WHERE codigo_cliente='$codigo' ");
    
    if (mysql_num_rows($r1) > 0) {
        $r2 = mysql_fetch_array($r1);
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