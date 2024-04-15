<?php

$mensaje = '';

/* crear registro */
if (isset_post('emitir-recibo')) {

    $concepto = post('concepto');
    $total = post('total');
    $a_cuenta = post('a_cuenta');
    $saldo = post('saldo');
    $nombre_receptor = post('nombre_receptor');
    $nit_receptor = post('nit_receptor');
   
    
    $nombre_a_procesar = $nombre_receptor;
    $nit_a_procesar = $nit_receptor;
    $monto_a_procesar = $total;
    
    $id_administrador = administrador('id');
    
    $fecha_emision = date("Y-m-d");
    $fecha_registro = date("Y-m-d H:i");
    
    /* numero de recibo */
    $rqfea1 = query("SELECT nro_recibo FROM recibos ORDER BY nro_recibo DESC limit 1 ");
    $rqfea2 = fetch($rqfea1);
    $nro_recibo = (int)($rqfea2['nro_recibo']+1);
 
    query("INSERT INTO recibos(
           id_administrador, 
           nro_recibo, 
           nombre_receptor, 
           total, 
           a_cuenta, 
           saldo, 
           concepto, 
           fecha_emision, 
           ciudad_emision, 
           fecha_registro, 
           estado
           ) VALUES (
           '$id_administrador',
           '$nro_recibo',
           '$nombre_a_procesar',
           '$monto_a_procesar',
           '$a_cuenta',
           '$saldo',
           '$concepto',
           '$fecha_emision',
           'LA PAZ',
           '$fecha_registro',
           '1'
           )");
    
    /* id de emision de recibo */
    $rqef1 = query("SELECT id FROM recibos WHERE nro_recibo='$nro_recibo' ORDER BY id DESC limit 1 ");
    $rqef2 = fetch($rqef1);
    $id_emision_recibo = $rqef2['id'];
 

    $mensaje .= '<div class="alert alert-success">
        <strong>Exito!</strong> Recibo emitido exitosamente.
    </div>

    <table class="table table-striped">
        <tr>
            <td>Nro. de Recibo: </td>
            <td>'.$nro_recibo.'</td>
        </tr>
        <tr>
            <td>Recibo a nombre de: </td>
            <td>'.$nombre_a_procesar.'</td>
        </tr>
        <tr>
            <td>Monto total: </td>
            <td>'.$monto_a_procesar.'</td>
        </tr>
        <tr>
            <td>Monto a cuenta: </td>
            <td>'.$a_cuenta.'</td>
        </tr>
        <tr>
            <td>Monto saldo: </td>
            <td>'.$saldo.'</td>
        </tr>
        <tr>
            <td>Fecha de emision: </td>
            <td>'.$fecha_emision.'</td>
        </tr>
        <tr>
            <td colspan="2">
                <br/>
                <br/>
                <b>Impresi&oacute;n -> </b> <button onclick="window.open(\''.$dominio.'contenido/paginas/procesos/pdfs/recibo-1.php?nro_recibo='.$nro_recibo.'\', \'popup\', \'width=700,height=500\');" class="btn btn-default btn-xs">IMPRIMIR RECIBO</button>

            </td>
        </tr>
    </table>';
    
}

?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li class="active">Emisi&oacute;n de recibo</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <h3 class="page-header"> Emisi&oacute;n de recibo</h3>
        <blockquote class="page-information hidden">
            <p>
                Emisi&oacute;n de recibo
            </p>
        </blockquote>
    </div>
</div>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">

                <div class="panel panel-primary">
                    <div class="panel-heading">Datos para el recibo</div>
                    <div class="panel-body">


                        <form action="" method="post">
                            <table style="width:100%;" class="table table-striped">
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; Recib&iacute; de: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="nombre_receptor" value='' class="form-control" id="date" required="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; Por concepto de: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="concepto" value="" class="form-control" id="date" required="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; Total en Bs.: </span>
                                    </td>
                                    <td>
                                        <input type="number" name="total" value="" class="form-control" id="date" required="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; A cuenta: </span>
                                    </td>
                                    <td>
                                        <input type="number" name="a_cuenta" value="" class="form-control" id="date" required="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; Saldo: </span>
                                    </td>
                                    <td>
                                        <input type="number" name="saldo" value="" class="form-control" id="date" required="">
                                    </td>
                                </tr>
                                
<!--                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; N&uacute;mero de NIT: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="nit_receptor" value='<?php //echo $curso['cont_dos']; ?>' class="form-control" id="date" required="">
                                    </td>
                                </tr>-->

                                <tr>
                                    <td colspan="2">
                                        <div style="text-align: center;padding:20px;">
                                            <input type="submit" name="emitir-recibo" value="EMITIR RECIBO" class="btn btn-success btn-lg btn-animate-demo active"/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<?php


?>