<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');
$cuce = '21-0513-00-1114217-1-1';

if($id_usuario==15980){
    echo "DENEGADO";
    exit;
}

if(isset_post('checkbox-margpref')){
    $margen_pref = 'Tipo de Proponente (MyPE, OECA, APP)';
    $porcentaje = '20';
    $observacion = 'Seleccionar si cuenta con la certificación';
    $rqvemp1 = query("SELECT id FROM simulador_marg_pref WHERE id_usuario='$id_usuario' AND cuce='$cuce' ");
    if(num_rows($rqvemp1)==0){
        query("INSERT INTO simulador_marg_pref 
        (id_usuario, cuce, margen_pref, porcentaje, observacion) VALUES 
        ('$id_usuario','$cuce','$margen_pref','$porcentaje','$observacion')
        ");
    }
}else{
    query("DELETE FROM simulador_marg_pref WHERE id_usuario='$id_usuario' AND cuce='$cuce' ");
}
?>

<table _ngcontent-woi-c36="" class="table table-bordered table-sm table-hover table-striped table-responsive">
    <thead _ngcontent-woi-c36="">
        <th _ngcontent-woi-c36="" class="text-center w-cog" style="width: 80px;">Opciones</th>
        <th _ngcontent-woi-c36="" class="text-center">Márgenes de Preferencia</th>
        <th _ngcontent-woi-c36="" class="text-center">Porcentaje</th>
    </thead>
    <tbody _ngcontent-woi-c36="">
        <!---->
        <?php
        if(isset_post('checkbox-margpref')){
        ?>
        <tr _ngcontent-woi-c36="">
            <td _ngcontent-woi-c36="" class="text-center">
                <!---->
                <div _ngcontent-woi-c36="" class="btn-group" dropdown=""><button _ngcontent-woi-c36="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-12"><span _ngcontent-woi-c36="" class="fa fa-cog text-primary"></span></button>
                    <!---->
                </div>
                <!---->
            </td>
            <td _ngcontent-woi-c36="">Tipo de Proponente (MyPE, OECA, APP)</td>
            <td _ngcontent-woi-c36="">20</td>
        </tr>
        <?php
        }else{
        ?>
        <tr _ngcontent-yfk-c48="">
            <td _ngcontent-yfk-c48="" colspan="3">No hay registro de márgenes de preferencia</td>
        </tr>
        <?php
        }
        ?>
        <!---->
    </tbody>
</table>