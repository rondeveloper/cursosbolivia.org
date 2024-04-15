<?php
$id_examen = $get[2];
$resultado1 = query("SELECT * FROM cursos_examenes_generales_intentos WHERE id_examen='$id_examen' ");
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
        </div>
        <h3 class="page-header"> Intentos del examen <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">
                <table class="table table-hover table-responsive table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="visible-lg" style="font-size:10pt;">#</th>
                            <th class="visible-lg" style="font-size:10pt;">Receptor</th>
                            <th class="visible-lg" style="font-size:10pt;">ID de certificado</th>
                            <th class="visible-lg" style="font-size:10pt;">Curso</th>
                            <th class="visible-lg" style="font-size:10pt;">Emision</th>
                            <th class="visible-lg" style="font-size:10pt;">Acci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($producto = fetch($resultado1)) {
                            ?>
                            <tr>
                                <td class="visible-lg"><?php echo $cnt--; ?></td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['id'];
                                    ?>         
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['id_participante'];
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo utf8_encode($producto['curso']);
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo date("d / M - H:i",  strtotime($producto['fecha_emision']));
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['administrador'];
                                    ?> 
                                </td>
                                <td class="visible-lg" style="width:120px;">
                                    <a onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/certificado-<?php echo $producto['formato_certificado']; ?>.php?id_certificado=<?php echo $producto['certificado_id']; ?>', 'popup', 'width=700,height=500');" style="cursor:pointer;"><i class='fa fa-file-pdf-o'></i> Visualizar</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
