<?php
/* usuario */
$id_usuario = usuario('id');

/* verif usuario */
if (!isset_usuario()) {
    echo "<br/><br/><br/>Acceso denegado!";
    exit;
}

/* datos */
$rqdu1 = query("SELECT * FROM usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
$rqdu2 = mysql_fetch_array($rqdu1);
$nombre_usuario = $rqdu2['nombre'];
$ci_usuario = $rqdu2['ci'];
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
                <div class="TituloArea">
                    <h3>USUARIO REGISTRADO</h3>
                </div>
                <div class="Titulo_texto1">
                    <div class="alert alert-success">
                        Saludos <strong><?php echo $nombre_usuario; ?></strong>, te damos la bienvenida a tu cuenta de usuario.
                    </div>
                </div>
                <br>

                <hr>

                <?php
                
                if(false){
                    
                    
                    $rqv1 = query("SELECT certificado_id FROM emisiones_certificados WHERE id_usuario='$id_usuario' AND id_certificado='1' ORDER BY id DESC limit 1 ");
                    if (mysql_num_rows($rqv1)>0) {
                        $rqv2 = mysql_fetch_array($rqv1);
                        $certificado_id = $rqv2['certificado_id'];
                        ?>
                        <div class="TituloArea">
                        <h3>Nuevo CERTIFICADO HABILITADO!</h3>
                        </div>
                        <div class="Titulo_texto1">
                            <div class="alert alert-info">
                                Buenas noticias hay un nuevo certificado habilitado para su emisi&oacute;n.
                            </div>
                            <b>CERTIFICADO:</b>
                            <h5 style="font-size: 15pt;color: #5491af;">GSuite para la Creatividad y Colaboraci&oacute;n global (Drive y Docs)</h5>
                            <div class="alert alert-warning">
                                Certificado emitido anteriormente.
                            </div>
                            <p>Puedes imprimir y/o descargar tu certificado en PDF presionando el siguiente bot&oacute;n.</p>
                            <br>
                            <div class="text-center">
                                <b class="btn btn-danger btn-lg" onclick="window.open('https://gegbolivia.cursos.bo/contenido/librerias/fpdf/tutorial/certificado.php?data=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');">VISUALIZAR CERTIFCADO</b>
                            </div>
                            <br>
                        </div>
                        <?php
                    }elseif (!isset_post('generar-certificado')) {
                    ?>

                    <div class="TituloArea">
                        <h3>Nuevo CERTIFICADO HABILITADO!</h3>
                    </div>
                    <div class="Titulo_texto1">
                        <div class="alert alert-info">
                            Buenas noticias hay un nuevo certificado habilitado para su emisi&oacute;n.
                        </div>
                        <p>Para poder generar, imprimir y/o descargar tu certificado en PDF verifica tus datos en el siguiente formulario y presiona el bot&oacute;n 'Generar certificado'.</p>
                    </div>
                    <div class="boxForm ajusta_form_contacto">
                        <b>CERTIFICADO:</b>
                        <h5 style="font-size: 20pt;">GSuite para la Creatividad y Colaboraci&oacute;n global (Drive y Docs)</h5>
                        <hr/>
                        <div class="row">

                            <div style="background:#FFF;padding: 5px;">
                                <form action="" method="post">
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <td>C&Oacute;DIGO</td>
                                            <td>C-GEG-001</td>
                                        </tr>
                                        <tr>
                                            <td>CERTIFICADO</td>
                                            <td>GSuite para la Creatividad y Colaboraci&oacute;n global (Drive y Docs)</td>
                                        </tr>
                                        <tr>
                                            <td>PROGRAMA</td>
                                            <td>APLICANDO EN EL AULA</td>
                                        </tr>
                                        <tr>
                                            <td>FECHA DE EMISI&Oacute;N</td>
                                            <td><?php echo date("d/m/Y"); ?></td>
                                        </tr>
                                        <tr>
                                            <td>NOMBRE RECEPTOR</td>
                                            <td>
                                                <?php echo $nombre_usuario; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>C.I. RECEPTOR</td>
                                            <td>
                                                <?php echo $ci_usuario; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <br>
                                                <input type="submit" class="btn btn-success" value="GENERAR CERTIFICADO" name="generar-certificado"/>
                                                <br>
                                                &nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>

                        </div>
                    </div>
                    <?php
                } else {
                    $rqv1 = query("SELECT certificado_id FROM emisiones_certificados WHERE id_usuario='$id_usuario' AND id_certificado='1' ORDER BY id DESC limit 1 ");
                    if (mysql_num_rows($rqv1) == 0) {

                        $certificado_id = 'G0' . strtoupper(substr(md5(md5(rand(9999, 9999999))), 7, 5));
                        query("INSERT INTO emisiones_certificados(
                        id_certificado, 
                        id_modelo_certificado, 
                        id_curso, 
                        id_usuario, 
                        certificado_id, 
                        receptor_de_certificado, 
                        id_administrador_emisor, 
                        fecha_emision, 
                        estado
                        ) VALUES (
                        '1',
                        '1',
                        '1',
                        '$id_usuario',
                        '$certificado_id',
                        '$nombre_usuario',
                        '0',
                        NOW(),
                        '1'
                        )");
                        ?>
                        <div class="TituloArea">
                            <h3>CERTIFICADO EMITIDO</h3>
                        </div>
                        <div class="Titulo_texto1">
                            <div class="alert alert-success">
                                Felicidades se ha emitido tu certificado correctamente.
                            </div>
                            <p>Puedes imprimir y/o descargar tu certificado en PDF presionando el siguiente bot&oacute;n.</p>
                            <br>
                            <div class="text-center">
                                <b class="btn btn-danger btn-lg" onclick="window.open('https://gegbolivia.cursos.bo/contenido/librerias/fpdf/tutorial/certificado.php?data=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');">VISUALIZAR CERTIFCADO</b>
                            </div>
                            <br>
                        </div>
                        <?php
                    } else {
                        $rqv2 = mysql_fetch_array($rqv1);
                        $certificado_id = $rqv2['certificado_id'];
                        ?>
                        <div class="TituloArea">
                            <h3>CERTIFICADO EMITIDO</h3>
                        </div>
                        <div class="Titulo_texto1">
                            <div class="alert alert-warning">
                                Certificado emitido anteriormente.
                            </div>
                            <p>Puedes imprimir y/o descargar tu certificado en PDF presionando el siguiente bot&oacute;n.</p>
                            <br>
                            <div class="text-center">
                                <b class="btn btn-danger btn-lg" onclick="window.open('https://gegbolivia.cursos.bo/contenido/librerias/fpdf/tutorial/certificado.php?data=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');">VISUALIZAR CERTIFCADO</b>
                            </div>
                            <br>
                        </div>
                        <?php
                    }
                }
                
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
