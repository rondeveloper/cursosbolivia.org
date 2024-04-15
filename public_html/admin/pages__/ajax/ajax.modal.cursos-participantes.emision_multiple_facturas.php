<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {

    $ids_participantes = post('dat');
    if ($ids_participantes == '') {
        $ids_participantes = '0';
    }
    
/* limpia datos de id participante*/
$ar_exp_aux = explode(",",$ids_participantes);
$ids_participantes = '0';
foreach ($ar_exp_aux as $value) {
    $ids_participantes .= ",".(int)$value;
}
    
    
    $rqcp1 = query("SELECT *,(select concat(nombres,' ',apellidos) from cursos_participantes where id_proceso_registro=c.id limit 1)participante FROM cursos_proceso_registro c WHERE id IN ( select id_proceso_registro from cursos_participantes where id in($ids_participantes) and estado='1' and id_emision_factura='0' ) AND monto_deposito<>'' AND razon_social<>'' AND nit<>'' ORDER BY id DESC limit 100 ");
    if (num_rows($rqcp1) == 0) {
        echo "<br/><p>No se encontraron registros disponibles para la emision de facturas.</p><br/><br/>";
    } else {
        ?>
        <div class="row">
            <table class="table">
                <tr>
                    <th>Nombre</th>
                    <th>NIT</th>
                    <th>Monto</th>
                    <th>Participante</th>
                </tr>
                <?php
                while ($rqcp2 = fetch($rqcp1)) {
                    ?>
                    <tr>
                        <td class="text-left">
                            <?php echo $rqcp2['razon_social']; ?>
                        </td>
                        <td class="text-left">
                            <?php echo $rqcp2['nit']; ?>
                        </td>
                        <td class="text-left">
                            <?php echo $rqcp2['monto_deposito']; ?>
                        </td>
                        <td class="text-left">
                            <?php echo $rqcp2['participante']; ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <br/>
        <p class='text-center'><b>&iquest; Desea emitir estas facturas ?</b></p>

        <button class="btn btn-success" onclick="emision_multiple_facturas_p2();">EMITIR FACTURAS</button>
        &nbsp;&nbsp;&nbsp;
        <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>

        <?php
    }
} else {
    echo "Denegado!";
}
?>
