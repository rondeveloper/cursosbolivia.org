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

//$rqdu1 = query("SELECT id_usuario FROM emisiones_certificados WHERE id_certificado='2' ");
//while($rqdu2 = mysql_fetch_array($rqdu1)){
//    $id_usuario = $rqdu2['id_usuario'];
//    $id_cursoprograma = '2';
//    query("INSERT INTO rel_usuariocursopr (id_usuario,id_cursoprograma) VALUES ('$id_usuario','$id_cursoprograma')");
//}
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
                    <h3>MIS CURSOS</h3>
                </div>
                <?php
                $rqc1 = query("SELECT c.id,c.titulo,c.cod_youtube FROM cursos_programa c INNER JOIN rel_usuariocursopr r ON c.id=r.id_cursoprograma WHERE r.id_usuario='$id_usuario' ");
                if (mysql_num_rows($rqc1) == 0) {
                    ?>
                    <div class="alert alert-danger">
                        <strong>Aviso</strong> sin cursos registrados.
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="Titulo_texto1">
                        <p>
                            A continuaci&oacute;n se listan los cursos asociados a su cuenta.
                        </p>
                    </div>
                    <table class='table table-striped table-bordered'>
                        <tr>
                            <th>
                                C&Oacute;DIGO
                            </th>
                            <th>
                                Curso
                            </th>
                            <th>
                                Programa
                            </th>
                            <th>
                                Video
                            </th>
                        </tr>
                        <?php
                        while ($rqc2 = mysql_fetch_array($rqc1)) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo 'CV-GEG-' . $rqc2['id']; ?>
                                </td>
                                <td>
                                    <?php echo utf8_encode($rqc2['titulo']); ?>
                                </td>
                                <td>
                                    Habilidades digitales para el Siglo XXI
                                </td>
                                <td>
                                    <a class="btn btn-danger btn-sm btn-block" href="https://www.youtube.com/watch?v=<?php echo $rqc2['cod_youtube']; ?>" target="_blank"><i class="fa fa-play-circle"></i> VIDEO</a>
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
                <br/>
                <br/>
                <br/>
                <br/>
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