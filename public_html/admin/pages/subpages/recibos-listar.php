
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li class="active">Facturas emitidas</li>
        </ul>

        <div class="form-group hiddn-minibar pull-right">
            <a href="recibos-emitir.adm" class='btn btn-success active'> <i class='fa fa-plus'></i> EMITIR NUEVO RECIBO</a>
        </div>        

        <h3 class="page-header"> LISTADO DE RECIBOS <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Recibos emitidos
            </p>
        </blockquote>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">MODULO DE RECIBOS</div>
            <div class="panel-body">
                <div style="background: #f7f7f7;
             padding: 15px 10px;
             border-radius: 10px;
             border: 1px solid #ffffff;
             box-shadow: 0px 1px 3px 0px #b5b5b5;">
                    <form action="" method="post" id="FORM-listado">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Desde: </span>
                                    <input type="date" name="fecha_inicio" value="2018-01-01" class="form-control" placeholder="Fecha de inicio..." />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Hasta: </span>
                                    <input type="date" name="fecha_fin" value="<?php echo date("Y-m-d"); ?>" class="form-control" placeholder="Fecha de inicio..." />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Concepto: </span>
                                    <input type="text" name="concepto" value="" class="form-control" placeholder="Concepto / Detalle / Nro recibo / A nombre..." />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Administrador: </span>
                                    <select class="form-control" name="id_administrador">
                                        <option value='0'>TODOS</option>
                                        <?php
                                        $rqdr1 = query("SELECT * FROM administradores WHERE estado=1 ");
                                        while($rqdr2 = fetch($rqdr1)){
                                            ?>
                                            <option value='<?php echo $rqdr2['id']; ?>'><?php echo $rqdr2['nombre']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="padding-top: 2px;">
                                <b class="btn btn-primary btn-block" onclick="listado(1);">EFECTUAR BUSQUEDA</b>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <div class="table-responsive" id="AJAXCONTENT-listado">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- listado -->
<script>
    function listado(page) {
        $("#AJAXCONTENT-listado").html("<hr/><div style='text-align:center;'><div class='loader' style='margin:auto;'></div><b>CARGANDO...</b></div><hr/><p style='text-align:center;'>El proceso de b&uacute;squeda demora dependiendo los filtros asignados y el rango de fechas, podria tardar desde unos cuantos segundos hasta varios minutos.</p><hr/>");
        var form = $("#FORM-listado").serialize();
        $.ajax({
            url: 'pages/ajax/ajax.recibos-listar.listado.php?page='+page,
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-listado").html(data);
            }
        });
    }
</script>
<script>
    listado(1);
</script>



