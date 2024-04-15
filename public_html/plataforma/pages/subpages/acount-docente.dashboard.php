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
                include_once 'pages/items/item.d.menu_docente.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">
                
                <?php echo $mensaje; ?>
                
                <div class="TituloArea">
                    <h3>DASHBOARD - Cuenta docente</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        Este es el panel inicial de la cuenta.
                    </p>
                </div>

                <div class="alert alert-success">
                    <strong>TE DAMOS LA BIENVENIDA</strong> sea bienvenid@ a su cuenta de usuario.
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


                <?php if (false) { ?>
                    <div class="TituloArea">
                        <h3>FOROS CREADOS</h3>
                    </div>
                    <?php
                    $rqfc1 = query("SELECT *,(select titulo from cursos_categorias where id=f.id_categoria)categoria FROM cursos_foros f WHERE id_usuario='$id_docente' AND sw_docente='1' ");
                    if (num_rows($rqfc1) == 0) {
                        ?>
                        <div class="Titulo_texto1">
                            <p>
                                No se registraron foros creados por su cuenta.
                            </p>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="Titulo_texto1">
                            <p>
                                A continuaci&oacute;n se listan los foros asociados a su cuenta.
                            </p>
                        </div>
                        <table class='table table-striped table-bordered'>
                            <tr>
                                <th>
                                    Tema de discusi&oacute;n
                                </th>
                                <th>
                                    Categoria
                                </th>
                                <th>
                                    Respuestas 
                                </th>
                            </tr>
                            <?php
                            while ($rqcu2 = fetch($rqfc1)) {
                                ?>
                                <tr>
                                    <td>
                                        <a href="foro/<?php echo substr(limpiar_enlace(str_replace('�', '', $rqcu2['tema'])), 0, 75); ?>/<?php echo $rqcu2['id']; ?>.html">
                                            <?php echo $rqcu2['tema']; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo $rqcu2['categoria']; ?>
                                    </td>
                                    <td>
                                        <?php echo $rqcu2['cnt_respuestas']; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>

                        <?php
                    }
                    ?>
                    <hr/>
                    <a href="mi-cuenta-crear-foro.html" class="btn btn-info">
                        CREAR NUEVO FORO DE DISCUSI&Oacute;N
                    </a>
                <?php } ?>


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