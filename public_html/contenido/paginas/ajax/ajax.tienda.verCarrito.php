<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$Carrito = new Carrito();
/* eliminacion de curso POST */
if (isset_post('quitar')) {
    $id_curso = post('quitar');
    $Carrito->remove($id_curso);
}

if ($Carrito->isEmpty()) {
?>
    <div class="alert alert-warning">
        <strong>Carrito vacio</strong> no hay cursos en el carrito.
    </div>
<?php
exit;
}

$ids_cursos_del_carrito = implode(',', $Carrito->getIDsDelCarrito());
$rq_carrito_cursos = query("SELECT id, titulo, costo, imagen FROM cursos WHERE id IN ($ids_cursos_del_carrito)");

?>
<style>
    .content-tbody-modal-tienda i {
        color: #f00;
    }

    .content-thead-modal-tienda th {
        font-size: 21px;
        background: initial;
        text-align: center;
    }

    .content-thead-modal-tienda th:first-of-type {
        padding-right: 50px;
    }

    .content-tfooter-modal-tienda th {
        font-size: 18px;
        background: initial;
    }
</style>

<div style="padding: 5px 30px;">
    <table class="table table-bordered table-striped" style="font-size:17px;">
        <thead class="content-thead-modal-tienda">
            <tr>
                <th>#</th>
                <th colspan="2">CURSO</th>
                <th>PRECIO</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="text-center content-tbody-modal-tienda">
            <?php
            $cnt = 1;
            while ($datos_carrito_curso = fetch($rq_carrito_cursos)) {
            ?>
                <tr>
                    <td><?= $cnt++ ?></td>
                    <td><?= $datos_carrito_curso['titulo'] ?></td>
                    <td><img width="50" height="80" class="pull-right" src="contenido/imagenes/paginas/<?= $datos_carrito_curso['imagen'] ?>"></td>
                    <td><?= $datos_carrito_curso['costo'] ?></td>
                    <td>
                        <a onclick="quitarCursoDelCarrito(<?= $datos_carrito_curso['id'] ?>)" style="cursor:pointer;text-decoration:none !important" title="ELIMINAR">
                            <i class="icon-remove icon-2x"></i>
                        </a>
                    </td>
                </tr>
            <?php
            }
            ?>
            <?php
            ?>
            <tr class="content-tfooter-modal-tienda">
                <th colspan="3" class="text-right">Sub Total:</th>
                <td class="text-center"><?= $Carrito->getCostoTotalSinDescuento() ?> Bs</td>
                <td></td>
            </tr>
            <tr class="content-tfooter-modal-tienda">
                <th colspan="3" class="text-right">Descuento:</th>
                <td class="text-center"><?= $Carrito->getCostoTotalSinDescuento() - $Carrito->getCostoTotal() ?> Bs</td>
                <td></td>
            </tr>
            <tr class="content-tfooter-modal-tienda">
                <th colspan="3" class="text-right" style="font-size: 14pt;"><b>Total:</b></th>
                <td class="text-center" style="font-size: 15pt;"><b><?= $Carrito->getCostoTotal() ?> Bs</b></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div style="padding: 50px;">
        <a style="width: 58%;margin-top:10px" href="registro-cursos-tienda.html" class="center-block btn btn-primary btn-block">
            Registro cursos tienda
        </a>
    </div>
</div>

<hr>

<!-- ajax tienda quitar carrito -->
<script>
    function quitarCursoDelCarrito(id_curso) {
        $("#title-MODAL-general").html('MI CARRITO');
        $("#MODAL-general").modal('show');
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.tienda.verCarrito.php',
            data: {
                quitar: id_curso
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#body-MODAL-general").html(data);

                $("#add-button-curso-" + id_curso).css('display', 'block');
                $("#remove-button-curso-" + id_curso).css('display', 'none');
            }
        });
    }
</script>