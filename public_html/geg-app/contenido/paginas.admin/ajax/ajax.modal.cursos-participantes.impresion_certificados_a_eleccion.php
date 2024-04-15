<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {
    ?>
    <h5 class="text-center">
        Selecciona el modelo de certificado deseado:
    </h5>

    <br/>

    <select class="form-control" id="id-modelo-certificado-imp">
        <?php
        $rqmc1 = query("SELECT * FROM cursos_modelos_certificados ORDER BY id DESC limit 25");
        while ($rqmc2 = mysql_fetch_array($rqmc1)) {
            ?>
            <option disabled></option>
            <option value="<?php echo $rqmc2['id']; ?>"><?php echo $rqmc2['codigo'] . " - " . $rqmc2['descripcion']; ?></option>
            <?php
        }
        ?>
    </select>

    <br/>
    <br/>

        <!--    <p class='text-center'><b>&iquest; Desea emitir estos certificados ?</b></p>-->

    <button class="btn btn-success" onclick="imprime_certificados_a_eleccion_p2();">PROCEDER CON LA EMISION</button>

    <?php
} else {
    echo "Denegado!";
}
?>
