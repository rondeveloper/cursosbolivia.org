<?php
/* mensaje */
$mensaje = '';

if (isset_usuario()) {
    $id_usuario = usuario('id');
    $sw_docente = '0';
} elseif (isset_docente()) {
    $id_usuario = docente('id');
    $sw_docente = '1';
} else {
    echo "<br/><br/><br/>Acceso denegado!";
    exit;
}

/* datos */


/* creacion de foro */
if (isset_post('crear-foro')) {
    $tema = post('tema');
    $descripcion = post('descripcion');
    $id_categoria = post('id_categoria');
    $fecha_registro = date("Y-m-d H:i");
    query("INSERT INTO cursos_foros (
           id_categoria,
           id_usuario,
           sw_docente,
           tema,
           descripcion,
           fecha_registro,
           estado
           ) VALUES (
           '$id_categoria',
           '$id_usuario',
           '$sw_docente',
           '$tema',
           '$descripcion',
           '$fecha_registro',
           '1'
           ) ");
    $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> el registro fue creado correctamente.
</div>';
}
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #f6f5f5;">
            <div class="col-md-2 hidden-xs">
                <?php
                if (isset_usuario()) {
                    include_once 'contenido/paginas/items/item.d.menu_usuario.php';
                } else {
                    include_once 'contenido/paginas/items/item.d.menu_docente.php';
                }
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">
                <div class="TituloArea">
                    <h3>CREACI&Oacute;N DE NUEVO FORO</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        En esta secci&oacute;n podras crear un nuevo foro de discusi&oacute;n para la plataforma <?php echo $___nombre_del_sitio; ?>.
                    </p>
                </div>

                <?php echo $mensaje; ?>


                <div class="boxForm ajusta_form_contacto">
                    <h5>INGRESA LOS DATOS CORRESPONDIENTES</h5>
                    <hr/>
                    <div class="row">

                        <div style="background:#FFF;padding: 5px;">
                            <form action="" method="post">
                                <table class="table table-striped">
                                    <tr>
                                        <td>Categoria</td>
                                        <td>
                                            <select name="id_categoria" class="form-control">
                                                <?php
                                                $rqc1 = query("SELECT id,titulo FROM cursos_categorias WHERE estado='1' ORDER BY id ASC ");
                                                while ($rqc2 = fetch($rqc1)) {
                                                    ?>
                                                    <option value="<?php echo $rqc2['id']; ?>"><?php echo $rqc2['titulo']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tema de discusi&oacute;n</td>
                                        <td>
                                            <input type="text" name="tema" class="form-control" required=""/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Descripci&oacute;n</td>
                                        <td>
                                            <textarea name="descripcion" class="form-control" required="" style="height: 70px;"></textarea>
                                        </td>
                                    </tr>
                                </table>
                                <div class="text-center">
                                    <input type="submit" name="crear-foro" class="btn btn-info" value="CREAR FORO"/>
                                </div>
                                <hr/>
                            </form>
                        </div>


                    </div>
                </div>

                <br />


                <hr/>


                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <hr/>




            </div>

        </div>

    </section>
</div>                     



<?php

function fecha_aux($dat) {
    $meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $d1 = date("d", strtotime($dat));
    $d2 = $meses[(int) (date("m", strtotime($dat)))];
    $d3 = date("Y", strtotime($dat));
    return "$d1 de $d2 de $d3";
}
?>