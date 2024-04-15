<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {
    ?>
    <html>
        <head>
            <title>Inpresion de CUPONES</title>
            <style>
                body{
                    font-family: arial;
                    font-size:9pt;
                }
                .ticket-t1{
                    color:#044a7e;
                    font-weight:bold;
                    font-size: 11pt;
                    padding: 5px;
                    text-align: center;
                    border:2px solid #044a7e;
                    margin:10px 0px;
                }
                .bx1-ticket{
                    width:330px;
                    margin:9px;
                    margin-right:18px;
                    margin-bottom:18px;
                    float:left;
                }
                .parraf{
                    font-size:10pt;
                    padding: 0px;
                    margin: 0px;
                    color:#222;
                    line-height: 1.7;
                }
                .btn-print{
                    display:block;
                    width:100%;
                    clear:both;
                    text-align: center;
                    font-weight: bold;
                }
            </style>
            <style type="text/css">
                @media print {
                    .btn-print{
                        display:none;
                    }
                }
            </style>
        </head>
        <body>

            <?php
            if (isset_get('cod_grupo')) {
                $cod_grupo = get('cod_grupo');
                
                $rrc1 = query("SELECT * FROM cupones WHERE cod_grupo='$cod_grupo'LIMIT 1 ");
                $rrc2 = mysql_fetch_array($rrc1);
                
                $tipo = $rrc2['tipo'];
                $arrt1 = explode("-meses-",$tipo);
                $cnt_meses = $arrt1[0];
                $paquete = $arrt1[1];
                $fecha_expiracion = $rrc2['fecha_expiracion'];
            } else {

                $cnt_meses = get('cnt_meses');
                $paquete = get('paquete');
                $tipo = "$cnt_meses-meses-$paquete";
                $cantidad = (int) get('cantidad');
                //$creacion = date("Y-m-d H:i:s");
                $fecha_registro = date("Y-m-d H:i");
                $fecha_expiracion = get('expiracion');
                $id_administracion = administrador('id');
                
                $cod_grupo = date("ymd") . rand(10, 99) . substr(md5(rand(100, 999)), 3, 5);
                
                for ($ii = 1; $ii <= ($cantidad); $ii++) {

                    do {
                        $codigo = '';
                        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
                        $max = strlen($pattern) - 1;
                        for ($i = 0; $i < 9; $i++) {
                            $codigo .= strtoupper($pattern{mt_rand(0, $max)});
                        }
                        $rqp1 = query("SELECT codigo FROM cupones WHERE codigo='$codigo' ");
                    } while (mysql_num_rows($rqp1) > 0);


                    $r1 = query("INSERT INTO cupones (
                    codigo,
                    tipo,
                    fecha_registro,
                    fecha_expiracion,
                    id_administrador,
                    cod_grupo
                    ) VALUES (
                    '$codigo',
                    '$tipo',
                    '$fecha_registro',
                    '$fecha_expiracion',
                    '$id_administracion',
                    '$cod_grupo'
                    ) ");
                }
                
                movimiento('Creacion de cupones [' . $cantidad . ']', 'creacion-cupones', 'administrador', $id_administracion);

            }
            
            $r1 = query("SELECT * FROM cupones WHERE cod_grupo='$cod_grupo' ORDER BY id DESC  ");
            $cantidad = mysql_num_rows($r1);
            $cnt_cupones = 0;
            $style_div = "height:1290px;width:750px;border:0px solid gray;padding-top:5px;margin-bottom:20px;";
            //echo "<div style='$style_div'>";
            while ($r2 = mysql_fetch_array($r1)) {

                $arr = explode('-meses-', $r2['tipo']);
                $cnt_meses = $arr[0];
                $paquete = $arr[1];

                $text_meses = 'meses';
                if ($cnt_meses == 1) {
                    $text_meses = 'mes';
                }
                ?>
                <div class="none">
                    <div class="bx1-ticket">
                        <div class="box-ticket" style="width:100%;">
                            <div class="ticket" style="color:#044a7e;border:3px solid #555;padding:10px;height:300px;background-color:#FFF;">
                                <div class="none">
                                    <div class="col-tk1" style="width:45%;float:left;font-size: 7pt;text-align: center;">
                                        <img src="http://www.infosicoes.com/contenido/imagenes/images/logo_infosicoes.png" style="width:85%;"/>
                                    </div>
                                    <div class="col-tk1" style="font-weight: bold;width:55%;float:left;font-size: 8.5pt;text-align: center;">
                                        MONITOREO Y SEGUIMIENTO DE
                                        <br/>
                                        LICITACIONES DE BOLIVIA
                                    </div>
                                    <div class="clear" style="clear:both;width:100%;height:0px;"></div>
                                    <div class="ticket-t1">
                                        <?php
                                        echo "Paq. " . strtoupper($paquete) . " - $cnt_meses $text_meses";
                                        ?>
                                    </div>
                                    <div class="parraf">
                                        Con este cup&oacute;n puedes adquirir <?php echo $cnt_meses; ?> <?php echo $text_meses; ?> del paquete <?php echo $paquete; ?> gratuitamente. 
                                        Solo ingresa el siguiente codigo en el momento de tu registro en Infosicoes 
                                        o agregalo en la secci&oacute;n de cup&oacute;nes en tu cuenta.
                                    </div>
                                    <div style="text-align: center;font-weight: bold;font-size:14pt;padding:5px 0px;">
                                        <?php echo $r2['codigo']; ?>
                                    </div>
                                    <br/>
                                    <div style="color:#222;">
                                        <?php
                                        $arrf1 = explode('-', $r2['fecha_expiracion']);
                                        $fecha_exp = $arrf1[2] . '-' . $arrf1[1] . '-' . $arrf1[0];
                                        ?>
                                        Este cup&oacute;n es valido hasta <?php echo $fecha_exp; ?>
                                    </div>
                                    <div class="ticket-t1">
                                        www.infosicoes.com
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
//                    $cnt_cupones++;
//                    if ($cnt_cupones >= 6) {
//                        $cnt_cupones = 0;
//                        echo "</div>";
//                        echo "<div style='$style_div'>";
//                    }
            }
            ?>

            <div class='btn-print'>
                <p>Desea volver a generar cupones con estas mismas caracteristicas?</p>
                <a href='imprimir.cupones.php?cnt_meses=<?php echo $cnt_meses; ?>&paquete=<?php echo $paquete; ?>&cantidad=<?php echo $cantidad; ?>&expiracion=<?php echo $fecha_expiracion; ?>'>
                    <button>VOLVER A GENERAR E IMPRIMIR </button>
                </a>
            </div>

            <script>
                print();
                //                if (confirm('Desea volver a generar e imprimir cupones con estas caracteristicas')) {
                //                    alert('print again');
                //                } else {
                //                    close();
                //                }
            </script>
        </body>
    </html>

    <?php
} else {
    echo "Acceso denegado";
}
?>