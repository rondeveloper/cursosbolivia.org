<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador()) {
?>
<html>
    <head>
        <title>Inpresion de Tarjetas</title>
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
        </style>
    </head>
    <body>

        <?php
        if (isset_administrador()) {

            $cod_grupo = get('cod_grupo');

            
            for($i=1;$i<=9;$i++){
                ?>
                <div class="none">
                    <div class="bx1-ticket">
                        <div class="box-ticket" style="width:100%;">
                            <div class="ticket" style="color:#044a7e;border:3px solid #01508b;padding:10px;height:150px;background-color:#FFF;border-radius:5px;">
                                <div class="none">
                                    <div class="col-tk1" style="width:45%;float:left;font-size: 7pt;text-align: center;">
                                        <img src="http://www.infosicoes.com/contenido/imagenes/images/logo_infosicoes.png" style="width:85%;"/>
                                    </div>
                                    <div class="col-tk1" style="font-weight: bold;width:55%;float:left;font-size: 8.5pt;text-align: center;">
                                        Ing. Edgar Aliaga Chipana
                                        <br/>
                                        Gerente General
                                    </div>
                                    <div class="clear" style="clear:both;width:100%;height:0px;"></div>
                                    <div class="parraf">
                                        Infosicoes - monitoreo y seguimiento de licitaciones estatales de Bolivia.
                                    </div>
                                    <div style="text-align: center;font-weight: bold;font-size:8pt;padding:5px 0px;">
                                        Cel. 79517817 &nbsp;&nbsp;&nbsp;&nbsp; contacto@infosicoes.com
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
            }
            
            movimiento('Creacion de Tarjetas [9]', 'creacion-tarjetas', 'administrador', $id_administracion);
            
            
        } else {
            echo "Acceso denegado!";
        }
        ?>
        <script>
            print();
            //close();
        </script>
    </body>
</html>

    <?php
} else {
    echo "Acceso denegado";
}
?>