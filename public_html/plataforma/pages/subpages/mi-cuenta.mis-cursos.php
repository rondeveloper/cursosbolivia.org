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
                include_once 'pages/items/item.d.menu_usuario.php';
                ?>
            </div>
            <div class="col-md-10" style="background:#FFF;padding: 0px 15px;">

                <?php echo $mensaje; ?>

                <div class="TituloArea">
                    <h3>CURSOS ONLINE</h3>
                </div>
                <?php
                //$rqcu1 = query("SELECT * FROM cursos_onlinecourse WHERE id IN (SELECT id_onlinecourse FROM cursos_rel_cursoonlinecourse WHERE id_curso IN (SELECT cp.id_curso FROM cursos_participantes cp WHERE cp.id_usuario='$id_usuario') ) ");
                $rqcu1 = query("SELECT * FROM cursos_onlinecourse oc INNER JOIN cursos_onlinecourse_acceso a ON a.id_onlinecourse=oc.id WHERE a.id_usuario='$id_usuario' AND a.sw_acceso='1' AND a.estado='1' ");
                if (num_rows($rqcu1) == 0) {
                    ?>
                    <div class="Titulo_texto1">
                        <p>
                            No se registraron cursos realizados por esta cuenta.
                        </p>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="Titulo_texto1">
                        <p>
                            A continuaci&oacute;n se listan los cursos online a los que tienes acceso.
                        </p>
                    </div>
                    <table class='table table-striped table-bordered'>
                        <tr>
                            <th>
                                Curso
                            </th>
                            <th>
                                Ingreso
                            </th>
                        </tr>
                        <?php
                        while ($rqcu2 = fetch($rqcu1)) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $rqcu2['titulo']; ?>
                                </td>
                                <td>
                                    <a href="curso-online/<?php echo $rqcu2['urltag']; ?>.html" class="btn btn-xs btn-info">
                                        INGRESAR
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>

                    <?php
                }
                ?>

                <br />



                <?php
                if (false) {
                    ?>
                    <hr/>
                    <div class="TituloArea">
                        <h3>CURSOS REGISTRADOS</h3>
                    </div>
                    <?php
                    $rqcu1 = query("SELECT c.titulo,c.fecha,pr.cnt_participantes FROM cursos_proceso_registro pr INNER JOIN cursos c ON pr.id_curso=c.id WHERE id_usuario='$id_usuario' ");
                    if (num_rows($rqcu1) == 0) {
                        ?>
                        <div class="Titulo_texto1">
                            <p>
                                No se registraron cursos realizados por esta cuenta.
                            </p>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="Titulo_texto1">
                            <p>
                                A continuaci&oacute;n se listan los cursos a los cuales realizaste un registro.
                            </p>
                        </div>
                        <table class='table table-striped table-bordered'>
                            <tr>
                                <th>
                                    Curso
                                </th>
                                <th>
                                    Fecha
                                </th>
                                <th>
                                    Participantes
                                </th>
                            </tr>
                            <?php
                            while ($rqcu2 = fetch($rqcu1)) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $rqcu2['titulo']; ?>
                                    </td>
                                    <td>
                                        <?php echo fecha_aux($rqcu2['fecha']); ?>
                                    </td>
                                    <td>
                                        <?php echo $rqcu2['cnt_participantes']; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>

                        <?php
                    }
                    ?>
                    <?php
                }
                ?>


                <br />


                <?php
                if(false){
                ?>
                <hr/>

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
                        </tr>
                        <?php
                        while ($rqcu2 = fetch($rqcp1)) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $rqcu2['certificado_id']; ?>
                                </td>
                                <td>
                                    GSuite para la Creatividad y Colaboraci&oacute;n global (Drive y Docs)
                                    <?php //echo $rqcu2['texto_qr']; ?>
                                </td>
                                <td>
                                    <?php echo $rqcu2['receptor_de_certificado']; ?>
                                </td>
                                <td>
                                    Aplicando en el aula
                                    <?php //echo $rqcu2['titulo']; ?>
                                </td>
                                <td>
                                    <?php echo fecha_aux($rqcu2['fecha_emision']); ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>

                    <?php
                }
                ?>

                <?php
                }
                ?>

                <br />

                <hr/>

                <?php
                if (false) {
                    ?>
                    <div class="TituloArea">
                        <h3>FOROS CREADOS</h3>
                    </div>
                    <?php
                    $rqfc1 = query("SELECT *,(select titulo from cursos_categorias where id=f.id_categoria)categoria FROM cursos_foros f WHERE id_usuario='$id_usuario' ");
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
                    <a href="mi-cuenta.crear-foro.html" class="btn btn-info">
                        CREAR NUEVO FORO DE DISCUSI&Oacute;N
                    </a>
                    <?php
                }
                ?>

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