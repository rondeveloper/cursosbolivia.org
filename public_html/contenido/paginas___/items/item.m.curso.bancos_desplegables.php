<?php
/* vars required: $id_curso */

/* variables de configuracion del sistema */
$sw_show_bancmod_curso = $__CONFIG_MANAGER->getSw('sw_show_bancmod_curso');
?>
<?php if ($sw_show_bancmod_curso) { ?>
    <style>
        .img-mod-pay {
            width: 80px;
            height: 70px;
            cursor: pointer;
            transition: .3s;
        }
        .img-mod-pay:hover {
            width: 70px;
            height: 60px;
            padding: 5px;
            background: #8bdc77;
            transition: .3s;
        }
    </style>
    <script>
        function show_form_pay(data) {
            $('#CONTENT-formpay').html('Cargando...');
            $('#COLLAPSE-formpay').collapse();
            $.ajax({
                url: '<?php echo $dominio; ?>contenido/paginas/ajax/ajax.curso.show_form_pay.php',
                data: {
                    cod: '<?php echo $id_curso; ?>',
                    data: data
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $('#CONTENT-formpay').html(data);
                }
            });
        }
    </script>
    <div class="text-center" style="font-size:9pt;border:1px solid #c7c7c7;">
        FORMAS DE PAGO:
    </div>
    <div class="text-center" style="background: #f0f0f0;padding: 10px 0px;border: 1px solid #d8d8d8;border-top: 0px;">
        <img src="contenido/imagenes/bancos/tigo-money.jpg" class="img-mod-pay" onclick="show_form_pay('tigomoney');" />
        <!--                            <img src="contenido/imagenes/bancos/visa.jpg" class="img-mod-pay" onclick="show_form_pay('visa');"/>
                            <img src="contenido/imagenes/bancos/mastercard.jpg" class="img-mod-pay" onclick="show_form_pay('mastercard');"/>-->
        <?php
        /* metodos de pago */
        $rqb1 = query("SELECT * FROM bancos WHERE estado=1 ");
        while ($rqb2 = fetch($rqb1)) {
        ?>
            <img src="contenido/imagenes/bancos/<?php echo $rqb2['imagen']; ?>" class="img-mod-pay" onclick="show_form_pay('<?php echo $rqb2['id']; ?>');" />
        <?php
        }
        ?>
    </div>
    <div style="font-size:10pt;border: 1px solid #c7c7c7;border-top: 0px;" class="text-center">
        (haz clic en la imagen para ver detalles)
    </div>
    <div id="COLLAPSE-formpay" class="collapse">
        <div id="CONTENT-formpay" style="border: 1px solid #cbcbcb;border-top: 0px;padding: 20px 30px;"></div>
    </div>
    <br>
    <br>
<?php } ?>