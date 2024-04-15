<?php
/* mensaje */
$mensaje = '';

/* usuario */
$id_usuario = usuario('id');

/* verif usuario */
if (!isset_usuario()) {
    echo "<br/><br/><br/>Acceso denegado!";
    exit;
}

/* datos */
$rqdu1 = query("SELECT * FROM cursos_usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
$rqdu2 = mysql_fetch_array($rqdu1);
$nombre_usuario = $rqdu2['nombres'] . ' ' . $rqdu2['apellidos'];
$email_usuario = $rqdu2['email'];
$celular_usuario = $rqdu2['celular'];
$id_departamento_usuario = $rqdu2['id_departamento'];
?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div class="row" style="background: #f6f5f5;">
            <div class="col-md-2 hidden-xs">
                <?php
                include_once 'contenido/paginas/items/item.d.menu_usuario.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">

                <?php echo $mensaje; ?>

                <div class="TituloArea">
                    <h3>CERTIFICADOS OBTENIDOS</h3>
                </div>
                <?php
                $rqcp1 = query("SELECT c.texto_qr,c.codigo,e.certificado_id,e.receptor_de_certificado,e.fecha_emision FROM emisiones_certificados e INNER JOIN certificados c ON e.id_certificado=c.id WHERE e.id_usuario='$id_usuario' ");
                if (mysql_num_rows($rqcp1) == 0) {
                    ?>
                    <div class="Titulo_texto1">
                        <p>
                            No se registraron certificados asociados a su cuenta.
                        </p>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="Titulo_texto1">
                        <p>
                            A continuaci&oacute;n se listan los certificados asociados a su cuenta.
                        </p>
                    </div>
                    <table class='table table-striped table-bordered'>
                        <tr>
                            <th>
                                ID
                            </th>
                            <th>
                                Certificado
                            </th>
                            <th>
                                Receptor
                            </th>
                            <th>
                                Programa
                            </th>
                            <th>
                                Fecha de emisi&oacute;n
                            </th>
                            <th>
                                PDF
                            </th>
                        </tr>
                        <?php
                        while ($rqcu2 = mysql_fetch_array($rqcp1)) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $rqcu2['certificado_id']; ?>
                                </td>
                                <td>
                                    <?php echo $rqcu2['codigo'].' | '.utf8_encode($rqcu2['texto_qr']); ?>
                                </td>
                                <td>
                                    <?php echo $rqcu2['receptor_de_certificado']; ?>
                                </td>
                                <td>
                                    Habilidades digitales para el Siglo XXI
                                    <?php //echo $rqcu2['titulo']; ?>
                                </td>
                                <td>
                                    <?php echo fecha_aux($rqcu2['fecha_emision']); ?>
                                </td>
                                <td>
                                    <b class="btn btn-danger btn-xs" onclick="window.open('https://gegbolivia.cursos.bo/contenido/librerias/fpdf/tutorial/certificado.php?data=<?php echo $rqcu2['certificado_id']; ?>', 'popup', 'width=700,height=500');">
                                        <i class="fa fa-file-pdf-o"></i> Visualizar
                                    </b>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>

                    <?php
                }
                ?>


                <br/>
                <hr/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
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