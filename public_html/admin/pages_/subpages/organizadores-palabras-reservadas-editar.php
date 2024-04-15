<?php
/* mensaje */
$mensaje = '';

/* id_organizador */
$id_organizador_palabra_reservada = $get[2];


/* editar-organizador */
if (isset_post('editar-contenido')) {
    $titulo = post('palabra_reservada');
    $contenido = post_html('contenido');
    $id_organizador = post('id_organizador');
    
    query("UPDATE cursos_organizadores_cont_pr SET "
            . "titulo='$titulo',"
            . "contenido='$contenido',"
            . "id_organizador='$id_organizador'"
            . " WHERE id='$id_organizador_palabra_reservada' LIMIT 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro editado correctamente.
</div>';
}

/* editor nuevo contenido */
editorTinyMCE('editor1');
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="organizadores-listar.adm">Organizadores</a></li>
            <li class="active">Palabras recervadas</li>
        </ul>

        <div class="form-group pull-right">
            <!--            <button class="btn btn-success" data-toggle="modal" data-target="#MODAL-agregar-organizador">
                            <i class="fa fa-plus"></i> 
                            AGREGAR CONTENIDO
                        </button> &nbsp;&nbsp;-->
        </div>
        <h3 class="page-header"> CONTENIDO DE ORGANIZADOR <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Edici&oacute;n de contenido de organizador.
            </p>
        </blockquote>

    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                Edici&oacute;n de contenido de organizador.
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="panel panel-default">
                    <?php
                    /* datos */
                    $rqdo1 = query("SELECT * FROM cursos_organizadores_cont_pr WHERE id='$id_organizador_palabra_reservada' ORDER BY id DESC limit 1 ");
                    $rqdo2 = fetch($rqdo1);
                    ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label text-primary">PALABRA RESERVADA</label>
                                    <div class="">
                                        <input type="text" class="form-control form-cascade-control" name="palabra_reservada" value="<?php echo $rqdo2['titulo']; ?>" required placeholder="[IDENTIFICADOR-DE-CONTENIDO]" autocomplete="off"/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label text-primary">CONTENIDO</label>
                                    <div class="">
                                        <textarea name="contenido" id="editor1" style="width:100% !important;margin:auto;height:400px;" rows="25"><?php echo $rqdo2['contenido']; ?></textarea>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <input type="hidden" name="id_organizador" value="<?php echo $rqdo2['id_organizador']; ?>"/>
                            <input type="submit" name="editar-contenido" class="btn btn-success btn-sm btn-animate-demo" value="EDITAR CONTENIDO"/>
                        </div>
                    </form>
                </div>

                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>





    </div>
</div>
