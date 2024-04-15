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
$id_item = post('id_item');



if($id_usuario==15980){
    echo "DENEGADO";
    exit;
}


query("DELETE FROM simulador_marg_pref WHERE id_usuario='$id_usuario' AND cuce='$cuce' AND id_item='$id_item' ");

$item_a = '';
if (isset_post('mref-a')) {
    $item_a = post('mref-a');
    $rqdi1 = query("SELECT * FROM simulador_marg_pref WHERE id='$item_a' ");
    if (num_rows($rqdi1) > 0) {
        $rqdi2 = fetch($rqdi1);
        $margen_pref = $rqdi2['margen_pref'];
        $porcentaje = $rqdi2['porcentaje'];
        $observacion = $rqdi2['observacion'];
        query("INSERT INTO simulador_marg_pref 
        (id_usuario, id_item, cuce, margen_pref, porcentaje, observacion) 
        VALUES 
        ('$id_usuario','$id_item','$cuce','$margen_pref','$porcentaje','$observacion') ");
    }
}
$item_b = '';
if (isset_post('mref-b')) {
    $item_b = post('mref-b');
    $rqdi1 = query("SELECT * FROM simulador_marg_pref WHERE id='$item_b' ");
    if (num_rows($rqdi1) > 0) {
        $rqdi2 = fetch($rqdi1);
        $margen_pref = $rqdi2['margen_pref'];
        $porcentaje = $rqdi2['porcentaje'];
        $observacion = $rqdi2['observacion'];
        query("INSERT INTO simulador_marg_pref 
        (id_usuario, id_item, cuce, margen_pref, porcentaje, observacion) 
        VALUES 
        ('$id_usuario','$id_item','$cuce','$margen_pref','$porcentaje','$observacion') ");
    }
}

?>
<table _ngcontent-yfk-c39="" class="table table-bordered table-sm table-hover table-striped table-responsive">
    <thead _ngcontent-yfk-c39="">
        <!---->
        <tr _ngcontent-yfk-c39="">
            <!---->
            <th _ngcontent-yfk-c39="" class="text-center"></th>
            <!---->
            <th _ngcontent-yfk-c39="" class="text-center">#</th>
            <th _ngcontent-yfk-c39="" class="text-center">Descripción del Bien o Servicio</th>
            <th _ngcontent-yfk-c39="" class="text-center">Unidad de Medida</th>
            <th _ngcontent-yfk-c39="" class="text-center">Cantidad</th>
            <!---->
            <!---->
            <!---->
            <!---->
            <!---->
            <!---->
            <!---->
            <th _ngcontent-yfk-c39="" class="text-center">Márgenes de Preferencia</th>
            <!---->
        </tr>
    </thead>
    <tbody _ngcontent-yfk-c39="">
        <!---->
        <?php
        $rqdir1 = query("SELECT * FROM simulador_items WHERE cuce='$cuce' AND id_usuario='$id_usuario' ");
        $cnt = 1;
        if (num_rows($rqdir1) == 0) {
        ?>
            <!---->
            <tr _ngcontent-yfk-c39="">
                <td _ngcontent-yfk-c39="" colspan="20">No hay registro de Ítems</td>
            </tr>
        <?php
        }
        while ($rqdir2 = fetch($rqdir1)) {
        ?>
            <tr _ngcontent-pjb-c39="">
                <!---->
                <!---->
                <td _ngcontent-grk-c38="" class="text-center w-cog">
                    <div _ngcontent-grk-c38="" class="btn-group open show" dropdown="">
                        <button onclick="dropdown_prnd_item(<?php echo $rqdir2['id']; ?>);" _ngcontent-grk-c38="" class="btn btn-secondary btn-xs" dropdowntoggle="" tooltip="Opciones" type="button" aria-haspopup="true" aria-describedby="tooltip-10" aria-expanded="true"><span _ngcontent-grk-c38="" class="fa fa-cog text-primary"></span></button>
                        <!---->
                        <ul id="id-dropdown_prnd_item-<?php echo $rqdir2['id']; ?>" _ngcontent-grk-c38="" class="dropdown-menu show" role="menu" style="display:none;inset: 100% auto auto 0px; transform: translateY(0px);">
                            <!---->
                            <!---->
                            <a onclick="prnd_itemmargpref_new(<?php echo $rqdir2['id']; ?>);" _ngcontent-grk-c38="" class="dropdown-item text-dark"><b>m</b> Registrar Márgenes </a>
                            <!---->
                        </ul>
                    </div>
                </td>
                <td _ngcontent-pjb-c39="" class="text-center"><?php echo $cnt++; ?></td>
                <td _ngcontent-pjb-c39=""><?php echo $rqdir2['descripcion']; ?></td>
                <td _ngcontent-pjb-c39=""><?php echo $rqdir2['unidad_medida']; ?></td>
                <td _ngcontent-pjb-c39="" class="text-right"><?php echo $rqdir2['cantidad']; ?></td>
                <!---->
                <td _ngcontent-pjb-c39="">
                <?php
                $rqmrgpr1 = query("SELECT * FROM simulador_marg_pref WHERE id_usuario='$id_usuario' AND cuce='$cuce' AND id_item='".$rqdir2['id']."' ");
                if(num_rows($rqmrgpr1)==0){
                    echo 'No registró Márgenes de Preferencia';
                }
                while($rqmrgpr2 = fetch($rqmrgpr1)){
                    echo $rqmrgpr2['margen_pref']."<br>";
                }
                ?>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
    <tfoot _ngcontent-yfk-c39="">
        <!---->
        <!---->
    </tfoot>
</table>
<br>
<br>