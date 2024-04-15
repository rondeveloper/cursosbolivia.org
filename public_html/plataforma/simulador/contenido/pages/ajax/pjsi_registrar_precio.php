<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');

if($id_usuario==15980){
    echo "DENEGADO";
    exit;
}

if(isset_post('valor_ofertado')){
    $valor_ofertado = post('valor_ofertado');
    $num_item = post('num_item');
    query("INSERT INTO simulador_sigep_propuestas( id_usuario, monto, fecha, item) VALUES ('$id_usuario','$valor_ofertado',NOW(),'$num_item')");
}
?>


<table _ngcontent-vgu-c14="" class="table table-bordered table-sm table-hover table-striped table-responsive" id="tablaValues">
    <thead _ngcontent-vgu-c14="">
        <tr _ngcontent-vgu-c14="">
            <th _ngcontent-vgu-c14="" colspan="6" class="text-center">Definido por la Entidad</th>
            <th _ngcontent-vgu-c14="" colspan="2" class="text-center">Definido por el Proveedor</th>
            <th _ngcontent-vgu-c14="" colspan="2"></th>
        </tr>
        <tr _ngcontent-vgu-c14="">
            <th _ngcontent-vgu-c14="">#</th>
            <th _ngcontent-vgu-c14="">Descripci&oacute;n del Bien o Servicio</th>
            <th _ngcontent-vgu-c14="">Unidad de Medida</th>
            <th _ngcontent-vgu-c14="">Cantidad</th>
            <th _ngcontent-vgu-c14="">Precio Referencial Unitario</th>
            <th _ngcontent-vgu-c14="">Precio Referencial Total</th>
            <th _ngcontent-vgu-c14="">Precio Unitario Ofertado</th>
            <th _ngcontent-vgu-c14="">Precio Total Ofertado</th>
            <th _ngcontent-vgu-c14=""></th>
            <th _ngcontent-vgu-c14=""></th>
        </tr>
    </thead>
    <tbody _ngcontent-vgu-c14="">
        <?php
        $monto_total = 0;
        /* verrificacion de estado */
        $rqv1 = query("SELECT id_usuario FROM simulador_sigep_propuestas WHERE item=1 ORDER BY monto ASC,id ASC limit 1 ");
        $rqv2 = fetch($rqv1);
        $color_estado_1 = 'gray';
        $color_estado_2 = 'gray';
        if ($rqv2['id_usuario'] == $id_usuario) {
            $color_estado_1 = 'green';
        } else {
            $color_estado_2 = 'red';
        }
        $monto = 120;
        /* ultima propuesta */
        $rqulp1 = query("SELECT monto FROM simulador_sigep_propuestas WHERE item=1 AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
        if(num_rows($rqulp1)>0){
            $rqulp2 = fetch($rqulp1);
            $monto = $rqulp2['monto'];
        }
        $monto_total += $monto;
        ?>
        <tr _ngcontent-nhg-c28="">
            <td _ngcontent-nhg-c28="">1</td>
            <td _ngcontent-nhg-c28="">TRAJES DE BIOSEGURIDAD 1 PIEZA</td>
            <td _ngcontent-nhg-c28="">PIEZA</td>
            <td _ngcontent-nhg-c28="" class="text-right">3,200</td>
            <td _ngcontent-nhg-c28="" class="text-right">120.00</td>
            <td _ngcontent-nhg-c28="" class="text-right">384,000.00</td>
            <td _ngcontent-nhg-c28=""><input id="id-input-item-1" type="number" name="" value="<?php echo $monto; ?>" class="form-control" onkeyup="pjsi_calcular_precio(1,this.value,3200);" /></td>
            <td _ngcontent-nhg-c28="" class="text-right" id="id-ofert-item-1"><?php echo number_format($monto*3200,2,'.',','); ?></td>
            <td _ngcontent-nhg-c28=""><b class="btn btn-primary btn-block btn-xs" id="btn-aux-1" onclick="pjsi_registrar_precio(1);">Registrar precio</b></td>
            <td _ngcontent-nhg-c28="">
                <div style="background: <?php echo $color_estado_1; ?>;width: 30px;height: 30px;border-radius: 50%;float: left;margin-right: 3px;">&nbsp;</div>
                <div style="background: <?php echo $color_estado_2; ?>;width: 30px;height: 30px;border-radius: 50%;float: left;">&nbsp;</div>
            </td>
        </tr>
        <?php
        /* verrificacion de estado */
        $rqv1 = query("SELECT id_usuario FROM simulador_sigep_propuestas WHERE item=2 ORDER BY monto ASC,id ASC limit 1 ");
        $rqv2 = fetch($rqv1);
        $color_estado_1 = 'gray';
        $color_estado_2 = 'gray';
        if ($rqv2['id_usuario'] == $id_usuario) {
            $color_estado_1 = 'green';
        } else {
            $color_estado_2 = 'red';
        }
        $monto = 170;
        /* ultima propuesta */
        $rqulp1 = query("SELECT monto FROM simulador_sigep_propuestas WHERE item=2 AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
        if(num_rows($rqulp1)>0){
            $rqulp2 = fetch($rqulp1);
            $monto = $rqulp2['monto'];
        }
        $monto_total += $monto;
        ?>
        <tr _ngcontent-nhg-c28="">
            <td _ngcontent-nhg-c28="">2</td>
            <td _ngcontent-nhg-c28="">TRAJES DE BIOSEGURIDAD 2 PIEZAS</td>
            <td _ngcontent-nhg-c28="">PIEZA</td>
            <td _ngcontent-nhg-c28="" class="text-right">3,500</td>
            <td _ngcontent-nhg-c28="" class="text-right">170.00</td>
            <td _ngcontent-nhg-c28="" class="text-right">595,000.00</td>
            <td _ngcontent-nhg-c28=""><input id="id-input-item-2" type="number" name="" value="<?php echo $monto; ?>" class="form-control" onkeyup="pjsi_calcular_precio(2,this.value,3500);" /></td>
            <td _ngcontent-nhg-c28="" class="text-right" id="id-ofert-item-2"><?php echo number_format($monto*3500,2,'.',','); ?></td>
            <td _ngcontent-nhg-c28=""><b class="btn btn-primary btn-block btn-xs" id="btn-aux-2" onclick="pjsi_registrar_precio(2);">Registrar precio</b></td>
            <td _ngcontent-nhg-c28="">
                <div style="background: <?php echo $color_estado_1; ?>;width: 30px;height: 30px;border-radius: 50%;float: left;margin-right: 3px;">&nbsp;</div>
                <div style="background: <?php echo $color_estado_2; ?>;width: 30px;height: 30px;border-radius: 50%;float: left;">&nbsp;</div>
            </td>
        </tr>
        <?php
        /* verrificacion de estado */
        $rqv1 = query("SELECT id_usuario FROM simulador_sigep_propuestas WHERE item=3 ORDER BY monto ASC,id ASC limit 1 ");
        $rqv2 = fetch($rqv1);
        $color_estado_1 = 'gray';
        $color_estado_2 = 'gray';
        if ($rqv2['id_usuario'] == $id_usuario) {
            $color_estado_1 = 'green';
        } else {
            $color_estado_2 = 'red';
        }
        $monto = 40;
        /* ultima propuesta */
        $rqulp1 = query("SELECT monto FROM simulador_sigep_propuestas WHERE item=3 AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
        if(num_rows($rqulp1)>0){
            $rqulp2 = fetch($rqulp1);
            $monto = $rqulp2['monto'];
        }
        $monto_total += $monto;
        ?>
        <tr _ngcontent-nhg-c28="">
            <td _ngcontent-nhg-c28="">3</td>
            <td _ngcontent-nhg-c28="">GUANTES LATEX</td>
            <td _ngcontent-nhg-c28="">CAJA</td>
            <td _ngcontent-nhg-c28="" class="text-right">1,000</td>
            <td _ngcontent-nhg-c28="" class="text-right">40.00</td>
            <td _ngcontent-nhg-c28="" class="text-right">40,000.00</td>
            <td _ngcontent-nhg-c28=""><input id="id-input-item-3" type="number" name="" value="<?php echo $monto; ?>" class="form-control" onkeyup="pjsi_calcular_precio(3,this.value,1000);" /></td>
            <td _ngcontent-nhg-c28="" class="text-right" id="id-ofert-item-3"><?php echo number_format($monto*1000,2,'.',','); ?></td>
            <td _ngcontent-nhg-c28=""><b class="btn btn-primary btn-block btn-xs" id="btn-aux-3" onclick="pjsi_registrar_precio(3);">Registrar precio</b></td>
            <td _ngcontent-nhg-c28="">
                <div style="background: <?php echo $color_estado_1; ?>;width: 30px;height: 30px;border-radius: 50%;float: left;margin-right: 3px;">&nbsp;</div>
                <div style="background: <?php echo $color_estado_2; ?>;width: 30px;height: 30px;border-radius: 50%;float: left;">&nbsp;</div>
            </td>
        </tr>
        <?php
        /* verrificacion de estado */
        $rqv1 = query("SELECT id_usuario FROM simulador_sigep_propuestas WHERE item=4 ORDER BY monto ASC,id ASC limit 1 ");
        $rqv2 = fetch($rqv1);
        $color_estado_1 = 'gray';
        $color_estado_2 = 'gray';
        if ($rqv2['id_usuario'] == $id_usuario) {
            $color_estado_1 = 'green';
        } else {
            $color_estado_2 = 'red';
        }
        $monto = 20;
        /* ultima propuesta */
        $rqulp1 = query("SELECT monto FROM simulador_sigep_propuestas WHERE item=4 AND id_usuario='$id_usuario' ORDER BY id DESC limit 1 ");
        if(num_rows($rqulp1)>0){
            $rqulp2 = fetch($rqulp1);
            $monto = $rqulp2['monto'];
        }
        $monto_total += $monto;
        ?>
        <tr _ngcontent-nhg-c28="">
            <td _ngcontent-nhg-c28="">4</td>
            <td _ngcontent-nhg-c28="">ALCOHOL EN GEL</td>
            <td _ngcontent-nhg-c28="">PIEZA</td>
            <td _ngcontent-nhg-c28="" class="text-right">270</td>
            <td _ngcontent-nhg-c28="" class="text-right">20.00</td>
            <td _ngcontent-nhg-c28="" class="text-right">5,400.00</td>
            <td _ngcontent-nhg-c28=""><input id="id-input-item-4" type="number" name="" value="<?php echo $monto; ?>" class="form-control" onkeyup="pjsi_calcular_precio(4,this.value,270);" /></td>
            <td _ngcontent-nhg-c28="" class="text-right" id="id-ofert-item-4"><?php echo number_format($monto*270,2,'.',','); ?></td>
            <td _ngcontent-nhg-c28=""><b class="btn btn-primary btn-block btn-xs" id="btn-aux-4" onclick="pjsi_registrar_precio(4);">Registrar precio</b></td>
            <td _ngcontent-nhg-c28="">
                <div style="background: <?php echo $color_estado_1; ?>;width: 30px;height: 30px;border-radius: 50%;float: left;margin-right: 3px;">&nbsp;</div>
                <div style="background: <?php echo $color_estado_2; ?>;width: 30px;height: 30px;border-radius: 50%;float: left;">&nbsp;</div>
            </td>
        </tr>
        <tr _ngcontent-nhg-c28="">
            <td _ngcontent-nhg-c28="" class="text-right" colspan="5"><b>Total Referencial:</b></td>
            <td _ngcontent-nhg-c28="" class="text-right">1,024,400.00</td>
            <td _ngcontent-nhg-c28="" class="text-right"><b>Total Ofertado:</b></td>
            <td _ngcontent-nhg-c28="" class="text-right" id="id-total-ofertado"><?php echo number_format($monto_total*3200,2,'.',','); ?></td>
            <td _ngcontent-nhg-c28="" colspan="2"></td>
            </td>
        </tr>
    </tbody>
</table><br _ngcontent-vgu-c14="">