<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* recepcion de datos POST */
$id_banco = post('data');
$id_curso = post('cod');

/* curso */
$rqdc1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$nombre_curso = $rqdc2['titulo_identificador'];
?>
<style>
    .img-det-fpay{
        width: 90%;border: 1px solid #d2d2d2;padding: 4px;border-radius: 5px;
    }
    .titl-det-fpay{
        font-size: 17pt;background: #f7f7f7;padding: 10px;border: 1px solid gainsboro;
    }
    .num-det-fpay{
        text-align: center;font-size: 30pt;margin-bottom: 10px;padding: 15px 0px;color: #0951bd;font-weight: bold;
    }
    .link-det-fpay{
        color: #0066ff;text-decoration: underline;
    }
    .tabla-det-fpay th{
        font-size: 8pt !important;
        font-weight: bold !important;
    }
    .tabla-det-fpay td{
        padding: 10px !important;
    }
</style>
<?php
if ($id_banco == 'tigomoney') {
    ?>
    <div>
        <div class="row">
            <div class="col-md-4 col-xs-12 hidden-xs">
                <img src="contenido/imagenes/bancos/tigo-money.jpg" class="img-det-fpay"/>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="titl-det-fpay">
                    PAGO POR TIGOMONEY
                </div>
                <br>
                <p>Puede realizar el pago mediante TIGO MONEY al siguiente n&uacute;mero: </p>
                <div class="num-det-fpay">69714008</div>
                <p>El costo es sin recargo (Titular Edgar Aliaga) . Luego de haber realizado el pago debe subir una imagen del comprobante en el siguiente enlace:</p>
                <a href="registro-curso/<?php echo $nombre_curso; ?>.html" class="link-det-fpay">REPORTAR PAGO &gt;&gt;</a>
            </div>
        </div>
    </div>
    <?php
} elseif ($id_banco == 'visa') {
    ?>
    <div>
        <div class="row">
            <div class="col-md-4 col-xs-12 hidden-xs">
                <img src="contenido/imagenes/bancos/visa.jpg" class="img-det-fpay"/>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="titl-det-fpay">
                    PAGO POR TARJETA DE DEBITO / CREDITO
                </div>
                <br>
                <p>
                    Puede realizar el pago mediante tarjeta de credito o debito, su tarjeta debe estar habilitada para compras por internet,
                    <br>
                    <b>SOLICITE A SU ENTIDAD FINANCIERA LA HABILITACION DE BANCA POR INTERNET LLAMANDO POR TELEFONO</b>
                </p>
                <a href="registro-curso/<?php echo $nombre_curso; ?>.html" class="link-det-fpay">REGISTRARME Y REALIZAR PAGO &gt;&gt;</a>
            </div>
        </div>
    </div>
    <?php
} elseif ($id_banco == 'mastercard') {
    ?>
    <div>
        <div class="row">
            <div class="col-md-4 col-xs-12 hidden-xs">
                <img src="contenido/imagenes/bancos/mastercard.jpg" class="img-det-fpay"/>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="titl-det-fpay">
                    PAGO POR TARJETA DE DEBITO / CREDITO
                </div>
                <br>
                <p>
                    Puede realizar el pago mediante tarjeta de credito o debito, su tarjeta debe estar habilitada para compras por internet,
                    <br>
                    <b>SOLICITE A SU ENTIDAD FINANCIERA LA HABILITACION DE BANCA POR INTERNET LLAMANDO POR TELEFONO</b>
                </p>
                <a href="registro-curso/<?php echo $nombre_curso; ?>.html" class="link-det-fpay">REGISTRARME Y REALIZAR PAGO &gt;&gt;</a>
            </div>
        </div>
    </div>
    <?php
} else {
    /* banco */
    $rqdb1 = query("SELECT imagen,nombre FROM bancos WHERE id='$id_banco' AND estado=1 LIMIT 1 ");
    $rqdb2 = fetch($rqdb1);
    ?>
    <div>
        <div class="row">
            <div class="col-md-4 col-xs-12 hidden-xs">
                <img src="contenido/imagenes/bancos/<?php echo $rqdb2['imagen']; ?>" class="img-det-fpay"/>
            </div>
            <div class="col-md-8 col-xs-12">
                <div class="titl-det-fpay">
                    <?php echo strtoupper($rqdb2['nombre']); ?>
                </div>
                <br>
                <p>Puede realizar el pago a trav&eacute;s de <?php echo $rqdb2['nombre']; ?> a cualquiera de las siguientes cuentas:</p>
                <table class="table table-bordered tabla-det-fpay">
                    <tr>
                        <th>N&uacute;mero de cuenta</th>
                        <th>Titular</th>
                    </tr>
                    <?php
                    $rqc1 = query("SELECT * FROM cuentas_de_banco WHERE id_banco='$id_banco' AND estado=1 ");
                    while ($rqc2 = fetch($rqc1)) {
                        ?>
                        <tr>
                            <td style="font-size: 14pt;"><?php echo $rqc2['numero_cuenta']; ?></td>
                            <td><?php echo $rqc2['titular']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <br>
                <p>Luego de haber realizado el pago debe subir una imagen del comprobante en el siguiente enlace:</p>
                <a href="registro-curso/<?php echo $nombre_curso; ?>.html" class="link-det-fpay">REPORTAR PAGO &gt;&gt;</a>
            </div>
        </div>
    </div>
    <?php
}