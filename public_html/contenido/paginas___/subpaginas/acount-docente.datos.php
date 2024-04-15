<?php
/* mensaje */
$mensaje = '';

/* usuario */
$id_docente = docente('id');

/* verif usuario */
if (!isset_docente()) {
    echo "<br/><br/><br/>Acceso denegado!";
    exit;
}

/* datos */
$rqdu1 = query("SELECT * FROM cursos_docentes WHERE id='$id_docente' ORDER BY id DESC limit 1 ");
$rqdu2 = fetch($rqdu1);
$nombre_usuario = $rqdu2['nombres'] . ' ' . $rqdu2['apellidos'];
$email_usuario = $rqdu2['email'];
$celular_usuario = $rqdu2['celular'];
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #f6f5f5;">
            <div class="col-md-2 hidden-xs">
                <?php
                include_once 'contenido/paginas/items/item.d.menu_docente.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">
                <div class="TituloArea">
                    <h3>DATOS DE LA CUENTA</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        En esta secci&oacute;n encontraras los datos relacionados a tu cuenta.
                    </p>
                </div>

                <?php echo $mensaje; ?>


                <div class="boxForm ajusta_form_contacto">
                    <h5><?php echo $nombre_usuario; ?></h5>
                    <hr/>
                    <div class="row">

                        <div style="background:#FFF;padding: 5px;">
                            <table class="table table-striped">
                                <tr>
                                    <td>NOMBRE</td>
                                    <td><?php echo $nombre_usuario; ?></td>
                                </tr>
                                <tr>
                                    <td>EMAIL</td>
                                    <td><?php echo $email_usuario==''?'Sin datos':$email_usuario; ?></td>
                                </tr>
                                <tr>
                                    <td>CELULAR</td>
                                    <td><?php echo $celular_usuario==''?'Sin datos':$celular_usuario; ?></td>
                                </tr>
                                <tr>
                                    <td>TIPO DE USUARIO</td>
                                    <td>DOCENTE</td>
                                </tr>
                                <tr>
                                    <td>TIPO DE CUENTA</td>
                                    <td>DOC-CV-100</td>
                                </tr>
                            </table>
                        </div>


                    </div>
                </div>

                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <hr/>

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