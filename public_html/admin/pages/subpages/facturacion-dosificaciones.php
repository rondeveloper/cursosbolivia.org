
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
        </ul>

        <h3 class="page-header"> LISTADO DE DOSIFICACIONES <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Dosificaciones
            </p>
        </blockquote>
    </div>
</div>
<br>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th style="font-size:10pt;">#</th>
            <th style="font-size:10pt;">Ids</th>
            <th style="font-size:10pt;">Numeros</th>
            <th style="font-size:10pt;">Fecha Limite De Emision</th>
            <th style="font-size:10pt;">Nit De Emisor</th>
            <th style="font-size:10pt;">Fecha De Registro</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $ctn = '1';
        $__name_table_facturas_dosificaciones = "facturas_dosificaciones";
        $db_request_facturas_dosificaciones = query("SELECT * FROM $__name_table_facturas_dosificaciones WHERE estado = '1' ORDER BY id DESC");
        while($dato_request_facturas_dosificaciones = fetch($db_request_facturas_dosificaciones)){ ?>
        <tr>
            <td><?= $ctn++ ?></td>
            <td>
                Id Actividad: <?= $dato_request_facturas_dosificaciones['id_actividad'] ? $dato_request_facturas_dosificaciones['id_actividad'] : 'sin dato' ?> <br>
                Id Datos Emisor: <?= $dato_request_facturas_dosificaciones['id_datos_emisor'] ? $dato_request_facturas_dosificaciones['id_datos_emisor'] : 'sin dato' ?>
            </td>
            <td>
                Nro Tramite: <?= $dato_request_facturas_dosificaciones['nro_tramite'] ? $dato_request_facturas_dosificaciones['nro_tramite'] : 'sin dato' ?> <br>
                Nro Autorizacion: <?= $dato_request_facturas_dosificaciones['nro_autorizacion'] ? $dato_request_facturas_dosificaciones['nro_autorizacion'] : 'sin dato' ?>
            </td>
            <td><?= $dato_request_facturas_dosificaciones['fecha_limite_emision'] ? $dato_request_facturas_dosificaciones['fecha_limite_emision'] : 'sin dato' ?></td>
            <td><?= $dato_request_facturas_dosificaciones['nit_emisor'] ? $dato_request_facturas_dosificaciones['nit_emisor'] : 'sin dato' ?></td>
            <td><?= $dato_request_facturas_dosificaciones['fecha_registro'] ? $dato_request_facturas_dosificaciones['fecha_registro'] : 'sin dato' ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
