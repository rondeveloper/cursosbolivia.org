<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


$atributo = post('atributo');
$valor = post('valor');

$id_usuario = usuario('id_sim');
$id_prod = $_SESSION['id_prod__CURRENTADD'];

query("INSERT INTO simulador_atributos 
(id_usuario, id_prod, atributo, valor) 
VALUES 
('$id_usuario','$id_prod','$atributo','$valor') ");

?>

<table _ngcontent-nyk-c19="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
    <thead _ngcontent-nyk-c19="">
        <tr _ngcontent-nyk-c19="">
            <th _ngcontent-nyk-c19="" class="w-cog">Opciones</th>
            <th _ngcontent-nyk-c19="" class="text-center">Atributo</th>
            <th _ngcontent-nyk-c19="" class="text-center">Valor</th>
            <th _ngcontent-nyk-c19="" class="text-center">Estado</th>
        </tr>
    </thead>
    <tbody _ngcontent-nyk-c19="">
        <!---->
        <?php
        $rqatr1 = query("SELECT * FROM simulador_atributos WHERE id_usuario='$id_usuario' AND id_prod='$id_prod' ");
        while($rqatr2 = fetch($rqatr1)){
        ?>
        <tr _ngcontent-nyk-c19="">
            <td _ngcontent-nyk-c19="" class="text-center">
                <div _ngcontent-nyk-c19="" class="btn-group" dropdown=""><button _ngcontent-nyk-c19="" class="btn btn-secondary btn-xs" dropdowntoggle="" type="button" aria-haspopup="true"><span _ngcontent-nyk-c19="" class="fa fa-cog text-primary"></span></button>
                    <!---->
                </div>
            </td>
            <td _ngcontent-nyk-c19=""><?php echo $rqatr2['atributo']; ?></td>
            <td _ngcontent-nyk-c19=""><?php echo $rqatr2['valor']; ?></td>
            <td _ngcontent-nyk-c19="">ELABORADO</td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>