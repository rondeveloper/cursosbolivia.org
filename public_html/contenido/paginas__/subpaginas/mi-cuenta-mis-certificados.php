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
$rqdu2 = fetch($rqdu1);
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
                $rqcp1 = query("SELECT cert.texto_qr,ec.receptor_de_certificado,ec.certificado_id,ec.fecha_emision,c.titulo FROM cursos_emisiones_certificados ec INNER JOIN cursos_participantes cp ON ec.id_participante=cp.id INNER JOIN cursos c ON ec.id_curso=c.id INNER JOIN cursos_certificados cert ON ec.id_certificado=cert.id WHERE cp.id_usuario='$id_usuario' ");
                if (num_rows($rqcp1) == 0) {
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
                                Certificado
                            </th>
                            <th>
                                Receptor
                            </th>
                            <th>
                                Curso
                            </th>
                            <th>
                                Descarga
                            </th>
                        </tr>
                        <?php
                        while ($rqcu2 = fetch($rqcp1)) {
                            ?>
                            <tr>
                                <td>
                                    <b><?php echo $rqcu2['certificado_id']; ?></b>
                                    <br>
                                    <?php echo $rqcu2['texto_qr']; ?>
                                </td>
                                <td>
                                    <?php echo $rqcu2['receptor_de_certificado']; ?>
                                </td>
                                <td>
                                    <?php echo $rqcu2['titulo']; ?>
                                    <br>
                                    Emisi&oacute;n: <?php echo fecha_aux($rqcu2['fecha_emision']); ?>
                                </td>
                                <td>
                                    <a href="<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/certificado-digital-3.php?id_certificado=<?php echo $rqcu2['certificado_id']; ?>&download=true" class="btn btn-sm btn-danger"><i class="fa fa-download"></i> DESCARGAR</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>

                    <?php
                }
                ?>


                <br>
                <hr>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <hr>
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