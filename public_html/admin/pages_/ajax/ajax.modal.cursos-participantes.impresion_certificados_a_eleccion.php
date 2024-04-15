<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {
    ?>
    <h5 class="text-center">
        Selecciona el modelo de certificado deseado:
    </h5>

    <br/>

    <select class="form-control" id="id-modelo-certificado-imp">
        <?php
        $rqmc1 = query("SELECT * FROM cursos_modelos_certificados ORDER BY id DESC limit 25");
        while ($rqmc2 = fetch($rqmc1)) {
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
