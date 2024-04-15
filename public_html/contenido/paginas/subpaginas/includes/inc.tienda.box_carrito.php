<style>
    .box-carrito {
        background: #98b1d0;
        width: 280px;
        float: right;
        height: 140px;
        position: fixed;
        z-index: 10;
        top: 58px;
        left: 50%;
        margin-left: 300px;
        border-radius: 0px 0px 15px 15px;
        box-shadow: -1px 0px 4px 0px #d3d3d3;
        border-bottom: 2px solid #258fad;
        padding-top: 15px;
        color: white;
    }
</style>

<div class="box-carrito text-center">
    <b style="font-size: 15pt;">RESUMEN CARRITO</b>
    <div style="padding-bottom: 5px;" id="resumen-carrito">
        Total costo: 0 BS
        <br>
        Total cursos: 0
    </div>
    <div  onclick="verCarrito()"class="btn btn-primary btn-lg">
        <i class="icon-shopping-cart"></i> Ver carrito
    </div>
</div>

<!-- ajax tienda carrito -->
<script>
    function verCarrito(id_carrito) {
        $("#title-MODAL-general").html('MI CARRITO');
        $("#body-MODAL-general").html('Cargando...');
        $("#MODAL-general").modal('show');
        $.ajax({
                url: 'contenido/paginas/ajax/ajax.tienda.verCarrito.php',
                data: {id_carrito: id_carrito},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#body-MODAL-general").html(data);
                }
        });
    }
</script>