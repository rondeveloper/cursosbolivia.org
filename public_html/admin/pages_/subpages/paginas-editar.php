<?php
/* acceso */
if (!acceso_cod('adm-paginas')) {
    echo "DENEGADO";
    exit;
}

/* id pagina */
$id_pagina = (int)$get[2];

/* mensaje */
$mensaje = '';

if (isset_post('formulario')) {
    
    $contenido = post_html('formulario');
    $titulo = post('titulo');
    

    query("UPDATE cursos_paginas SET contenido='$contenido',titulo='$titulo' WHERE id='$id_pagina' ORDER BY id DESC limit 1 ");
    movimiento('Edicion de pagina [cursos]', 'edicion-pagina-c', 'pagina-c', $id);
    
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> registro modificado exitosamente.
</div>';
    
    //echo "<script>location.href='pagina-editar/$titulo.adm';</script>";
}

$resultado_paginas = query("SELECT * FROM cursos_paginas WHERE id='$id_pagina'");
$pagina = fetch($resultado_paginas);

editorTinyMCE('editor1');
?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li><a href="paginas-listar.adm">Paginas</a></li>
            <li class="active">Edici&oacute;n de pagina</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
<!--            <form action="" method="post">
                <input type="text" name="buscar" class="form-control form-cascade-control " size="20" placeholder="Buscar en el Sitio">
                <span class="input-icon fui-search"></span>
            </form>-->
        </div>
        <h3 class="page-header"> Edici&oacute;n de pagina <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Edici&oacute;n de pagina.
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $mensaje; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                EDICI&Oacute;N DE PAGINA - <?php echo $pagina['nombre']; ?>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <form enctype="multipart/form-data" action="" method="post">
                    <table>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Titulo de la pagina: </span>
                                    <input type="text" name="titulo" value="<?php echo $pagina['titulo']; ?>" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <br/>
                                <textarea name="formulario" id="editor1" style="width:100%;margin:auto;" rows="25"><?php echo $pagina['contenido']; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="text-align: center;padding:20px;">
                                    <input type="submit" value="ACTUALIZAR PAGINA" class="btn btn-success btn-lg btn-animate-demo active"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>

    </div>
</div>

