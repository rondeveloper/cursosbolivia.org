<?php
error_reporting(1);
session_start();
/* datos de configuracion */
include_once '../../../../../contenido/configuracion/config.php';
include_once '../../../../../contenido/configuracion/funciones.php';

/* coneccion a base de datos */
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$id_usuario = usuario('id_sim');

?>



<div _ngcontent-vgu-c12="" class="row">
    <div _ngcontent-vgu-c12="" class="col-lg-12 col-sm-12 col-md-12 col-12">
        <prouns-list-fragment _ngcontent-vgu-c12="" _nghost-vgu-c14="">
            <div _ngcontent-vgu-c14="" class="row">
                <div _ngcontent-vgu-c14="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <div _ngcontent-vgu-c14="" class="card card-default">
                        <div _ngcontent-vgu-c14="" class="card-header">
                            <div _ngcontent-vgu-c14="" class="row">
                                <div _ngcontent-vgu-c14="" class="col-lg-7 col-sm-6">
                                    <div _ngcontent-vgu-c14="" class="card-title">Historial</div>
                                </div>
                                <div _ngcontent-vgu-c14="" class="col-lg-5 col-sm-6 col-xs-12">
                                    <div _ngcontent-vgu-c14="" class="row">
                                        <div _ngcontent-vgu-c14="" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                            <form onsubmit="return false" _ngcontent-vgu-c14="" name="formBusquedaDocumento" novalidate="" class="ng-untouched ng-pristine ng-valid">
                                                <div _ngcontent-vgu-c14="" class="input-group"><input _ngcontent-vgu-c14="" class="form-control ng-untouched ng-pristine ng-valid" name="descripcionBusqueda" placeholder="Buscar" type="text"><span _ngcontent-vgu-c14="" class="input-group-btn"><button _ngcontent-vgu-c14="" class="btn btn-primary" type="submit"><span _ngcontent-vgu-c14="" class="fa fa-search"></span></button></span></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div _ngcontent-vgu-c14="" class="card-body">
                            <div _ngcontent-vgu-c14="" class="row">
                                <div _ngcontent-vgu-c14="" class="col-lg-12 col-md-12">

                                    <table class="table table-bordered table-striped table-responsive">
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Nro Documento</th>
                                            <th>Tipo Operacion</th>
                                            <th>Descripcion</th>
                                            <th>Fecha y Hora</th>
                                            <th>Precio Total Ofertado</th>
                                        </tr>
                                        <?php
                                        $cnt = 1;
                                        $rqde1 = query("SELECT p.id,(u.id)dr_id_usuario,p.monto,u.nombres,u.apellidos,p.fecha,p.item FROM simulador_sigep_propuestas p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE p.item<>0 AND p.id_usuario='$id_usuario' ORDER BY p.id ASC ");
                                        while ($rqde2 = fetch($rqde1)) {
                                        ?>
                                            <tr>
                                                <td>
                                                    <button onclick="pjs_info_propuesta(<?php echo $rqde2['id']; ?>);" _ngcontent-nhg-c28="" aria-controls="dropdown-autoclose1" class="btn btn-secondary btn-xs" dropdowntoggle="" type="button" aria-haspopup="true"><span _ngcontent-nhg-c28="" class="fa fa-cog text-primary"></span></button>
                                                </td>
                                                <td>25271.2</td>
                                                <td>Oferta</td>
                                                <td>
                                                    <?php
                                                    $cantidad = 1;
                                                    switch ($rqde2['item']) {
                                                        case '1':
                                                            echo 'TRAJES DE BIOSEGURIDAD 1 PIEZA';
                                                            $cantidad = 3200;
                                                            break;
                                                        case '2':
                                                            echo 'TRAJES DE BIOSEGURIDAD 2 PIEZAS';
                                                            $cantidad = 3500;
                                                            break;
                                                        case '3':
                                                            echo 'GUANTES LATEX';
                                                            $cantidad = 1000;
                                                            break;
                                                        case '4':
                                                            echo 'ALCOHOL EN GEL';
                                                            $cantidad = 270;
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo date("d/m/Y H:i:s", strtotime($rqde2['fecha'])); ?></td>
                                                <td><?php echo number_format($rqde2['monto'] * $cantidad, 2, '.', ','); ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </prouns-list-fragment>
    </div>
</div>

