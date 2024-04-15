<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="TituloArea">
                    <h3>FORMAS DE PAGO</h3>
                </div>
                <div class="Titulo_texto1" style="min-height: 570px;padding-bottom: 40px;line-height: 2;">
                    <p>Puede realizar el pago de los cursos a trav&eacute;s de TigoMoney o Deposito / Transferencia bancaria a los siguientes datos:</p>
                    <div>
                        <h3 style="background: #efefef;padding: 10px;border: 1px solid #d4d4d4;">TIGO MONEY</h3> 
                        <div style="font-size: 17pt;border: 1px dashed #c7c7c7;padding: 30px;margin-bottom: 40px;line-height: 1.5;">
                            <?php 
                            $rqntm1 = query("SELECT numero FROM tigomoney_numeros WHERE estado=1 ");
                            while($rqntm2 = fetch($rqntm1)){
                                ?>
                                A la linea <b style="color: #2371e4;"><?php echo $rqntm2['numero']; ?></b> el costo sin recargo
                                <br>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                        $rqbn1 = query("SELECT id,nombre FROM bancos WHERE estado=1 ORDER BY id ASC ");
                        while ($rqbn2 = fetch($rqbn1)) {
                            $id_banco = $rqbn2['id'];
                            ?>
                            <h3 style="background: #efefef;padding: 10px;border: 1px solid #d4d4d4;">
                                <?php echo $rqbn2['nombre']; ?>
                            </h3>
                            <div style="margin-bottom: 40px;">
                                <?php
                                $rqdcb1 = query("SELECT * FROM cuentas_de_banco WHERE id_banco='$id_banco' AND estado=1 ");
                                while ($rqdcb2 = fetch($rqdcb1)) {
                                    ?>
                                    <div style="font-size: 17pt;
                                         border: 1px dashed #c7c7c7;
                                         padding: 30px;margin-bottom: 5px;line-height: 1.5;">
                                         <?php echo $rqdcb2['numero_cuenta'] . ' &nbsp; ' . $rqdcb2['titular']; ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                        <br>
                        <h3 style="background: #efefef;padding: 10px;border: 1px solid #d4d4d4;">PAGO EN OFICINAS</h3>
                        <div style="font-size: 17pt;
                             border: 1px dashed #c7c7c7;
                             padding: 30px;
                             margin-bottom: 40px;line-height: 1.5;">
                            Direcci&oacute;n: Av camacho Edif. Saenz N° 1377 Piso 3 Of. 301 esq. Loayza
                            <br>
                            La Paz - Bolivia
                        </div>
                        <hr>
                        Consultas Whatsapp : <a href="https://wa.me/59169794724" style="color: #0095ff;text-decoration: underline;">https://wa.me/59169794724</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

