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
$nombre_usuario = $rqdu2['nombres'].' '.$rqdu2['apellidos'];
$ci_usuario = $rqdu2['ci'];
?>
<style>
@media (max-width: 750px){
    .TituloArea h3 {
        margin-top: 35px;
        margin-bottom: -10px;
        padding: 20px 0px !important;
    }
}
</style>


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
                    /* certificado */
                    $id_certificado = '3';
                    $rqddc1 = query("SELECT texto_qr FROM certificados WHERE id='$id_certificado' ");
                    $rqddc2 = mysql_fetch_array($rqddc1);
                    $titulo_certificado = utf8_encode($rqddc2['texto_qr']);
                    $codigo_certificado = utf8_encode($rqddc2['codigo']);
                    
                    $rqv1 = query("SELECT certificado_id FROM emisiones_certificados WHERE id_usuario='$id_usuario' AND id_certificado='$id_certificado' ORDER BY id DESC limit 1 ");
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
                            <h5 style="font-size: 15pt;color: #5491af;"><?php echo $titulo_certificado; ?></h5>
                            <div class="alert alert-warning">
                                Certificado emitido anteriormente.
                            </div>
                            <p>Puedes imprimir y/o descargar tu certificado en PDF presionando el siguiente bot&oacute;n.</p>
                            <br>
                            <div class="text-center">
                                <b class="btn btn-danger btn-lg" onclick="window.open('https://gegbolivia.cursos.bo/contenido/librerias/fpdf/tutorial/certificado.php?data=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');">
                                    <i class="fa fa-file-pdf-o"></i> VISUALIZAR CERTIFCADO
                                </b>
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
                        <h5 style="font-size: 20pt;"><?php echo $titulo_certificado; ?></h5>
                        <hr/>
                        <div class="row">

                            <div style="background:#FFF;padding: 5px;">
                                <form action="" method="post">
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <td>C&Oacute;DIGO</td>
                                            <td>C-GEG-00<?php echo $id_certificado; ?></td>
                                        </tr>
                                        <tr>
                                            <td>CERTIFICADO</td>
                                            <td><?php echo $titulo_certificado; ?></td>
                                        </tr>
                                        <tr>
                                            <td>PROGRAMA</td>
                                            <td>Habilidades digitales para el Siglo XXI</td>
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
                                    <br>
                                    <p>
                                        &iquest; El nombre esta incorrecto ? &nbsp;&nbsp; -> &nbsp;&nbsp; 
                                        <a href="acount.configuracion.html" style="text-decoration: underline;">MODIFICAR NOMBRE</a>
                                    </p>
                                </form>
                            </div>

                        </div>
                    </div>
                    <?php
                } else {
                    $rqv1 = query("SELECT certificado_id FROM emisiones_certificados WHERE id_usuario='$id_usuario' AND id_certificado='$id_certificado' ORDER BY id DESC limit 1 ");
                    if (mysql_num_rows($rqv1) == 0) {

                        //$id_cursoprograma = '3';
                        $id_cursoprograma = $id_certificado;
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
                        '$id_certificado',
                        '1',
                        '1',
                        '$id_usuario',
                        '$certificado_id',
                        '$nombre_usuario',
                        '0',
                        NOW(),
                        '1'
                        )");
                        query("INSERT INTO rel_usuariocursopr (id_usuario,id_cursoprograma) VALUES ('$id_usuario','$id_cursoprograma')");
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
                                <b class="btn btn-danger btn-lg" onclick="window.open('https://gegbolivia.cursos.bo/contenido/librerias/fpdf/tutorial/certificado.php?data=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');">
                                    <i class="fa fa-file-pdf-o"></i> VISUALIZAR CERTIFCADO
                                </b>
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
                                <b class="btn btn-danger btn-lg" onclick="window.open('https://gegbolivia.cursos.bo/contenido/librerias/fpdf/tutorial/certificado.php?data=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');">
                                    <i class="fa fa-file-pdf-o"></i> VISUALIZAR CERTIFCADO
                                </b>
                            </div>
                            <br>
                        </div>
                        <?php
                    }
                }
                echo "<br><br><br><hr><br>";
                }
                ?>


                <?php
                /* cupon para curso */
                if (false) {
                    $rqv1 = query("SELECT codigo FROM emisiones_cupones WHERE id_usuario='$id_usuario' AND id_cupon='1' ORDER BY id DESC limit 1 ");
                    if (mysql_num_rows($rqv1) > 0) {
                        $rqv2 = mysql_fetch_array($rqv1);
                        $certificado_id = $rqv2['codigo'];
                        ?>
                        <div class="TituloArea">
                            <h3>Nuevo CUP&Oacute;N HABILITADO!</h3>
                        </div>
                        <div class="Titulo_texto1">
                            <div class="alert alert-info">
                                Buenas noticias hay un nuevo cup&oacute;n habilitado para su emisi&oacute;n.
                            </div>
                            <b>CUP&Oacute;N:</b>
                            <h5 style="font-size: 15pt;color: #5491af;">50% de descuento para cursos virtuales en Cursos.bo</h5>
                            <div class="alert alert-warning">
                                Cup&oacute;n emitido anteriormente.
                            </div>
                            <p>Puedes imprimir y/o descargar tu cup&oacute;n en PDF presionando el siguiente bot&oacute;n.</p>
                            <br>
                            <div class="text-center">
                                <b class="btn btn-danger btn-lg" onclick="window.open('https://gegbolivia.cursos.bo/contenido/librerias/fpdf/tutorial/cupon.php?data=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');">
                                    <i class="fa fa-file-pdf-o"></i> VISUALIZAR CUP&Oacute;N
                                </b>
                            </div>
                            <br>
                        </div>
                        <?php
                    } elseif (!isset_post('generar-cupon')) {
                        ?>
                        <div class="TituloArea">
                            <h3>Nuevo CUP&Oacute;N HABILITADO!</h3>
                        </div>
                        <div class="Titulo_texto1">
                            <div class="alert alert-info">
                                Buenas noticias hay un nuevo cup&oacute;n habilitado para su emisi&oacute;n.
                            </div>
                            <p>Para poder generar, imprimir y/o descargar tu cup&oacute;n en PDF verifica tus datos en el siguiente formulario y presiona el bot&oacute;n 'Generar cup&oacute;n'.</p>
                        </div>
                        <div class="boxForm ajusta_form_contacto">
                            <b>CUP&Oacute;N:</b>
                            <h5 style="font-size: 20pt;">50% de descuento para cursos virtuales en <a href="https://cursos.bo" target="_blank" style="text-decoration: underline;color: #4478ef;">Cursos.bo</a></h5>
                            <hr/>
                            <div class="row">

                                <div style="background:#FFF;padding: 5px;">
                                    <form action="" method="post">
                                        <table class="table table-striped table-bordered">
                                            <tr>
                                                <td>CUP&Oacute;N</td>
                                                <td>50% de descuento para cursos virtuales en Cursos.bo</td>
                                            </tr>
                                            <tr>
                                                <td>CURSOS HABILITADOS</td>
                                                <td>
                                                    Ver listado en la parte inferior
                                                </td>
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
                                                    <input type="submit" class="btn btn-success" value="GENERAR CUP&Oacute;N" name="generar-cupon"/>
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
                        $rqv1 = query("SELECT codigo FROM emisiones_cupones WHERE id_usuario='$id_usuario' AND id_cupon='1' ORDER BY id DESC limit 1 ");
                        if (mysql_num_rows($rqv1) == 0) {

                            //$id_cursoprograma = '3';
                            $id_cursoprograma = $id_certificado;
                            $codigo_cupon = 'CP' . strtoupper(substr(md5(md5($id_usuario.'-'.rand(9999, 9999999))), 7, 5));
                            query("INSERT INTO emisiones_cupones(
                        id_usuario, 
                        codigo, 
                        fecha_emision,
                        estado
                        ) VALUES (
                        '$id_usuario',
                        '$codigo_cupon',
                        NOW(),
                        '1'
                        )");
                            ?>
                            <div class="TituloArea">
                                <h3>CUP&Oacute;N EMITIDO</h3>
                            </div>
                            <div class="Titulo_texto1">
                                <div class="alert alert-success">
                                    Felicidades se ha emitido tu cup&oacute;n correctamente.
                                </div>
                                <p>Puedes imprimir y/o descargar tu cup&oacute;n en PDF presionando el siguiente bot&oacute;n.</p>
                                <br>
                                <div class="text-center">
                                    <b class="btn btn-danger btn-lg" onclick="window.open('https://gegbolivia.cursos.bo/contenido/librerias/fpdf/tutorial/cupon.php?data=<?php echo $codigo_cupon; ?>', 'popup', 'width=700,height=500');">
                                        <i class="fa fa-file-pdf-o"></i> VISUALIZAR CUP&Oacute;N
                                    </b>
                                </div>
                                <br>
                            </div>
                            <?php
                        } else {
                            echo "ERROR";
                            /*
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
                                    <b class="btn btn-danger btn-lg" onclick="window.open('https://gegbolivia.cursos.bo/contenido/librerias/fpdf/tutorial/certificado.php?data=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');">
                                        <i class="fa fa-file-pdf-o"></i> VISUALIZAR CERTIFCADO
                                    </b>
                                </div>
                                <br>
                            </div>
                            <?php
                            */
                        }
                    }
                    ?>
                <br>
                <hr>
                <br>
                    <div class="TituloArea">
                        <h3>CURSOS HABILITADOS PARA EL DESCUENTO</h3>
                    </div>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td style="padding-top: 10px;">
                                <a target="_blank" href="https://cursos.bo/curso-virtual-moodle-incluye-dominio-y-hosting-1-ano.html" style="font-size: 12pt;">
                                    Curso virtual Moodle incluye dominio y hosting 1 a&ntilde;o                                            </a>
                            </td>
                            <td>
                                <a target="_blank" href="https://cursos.bo/curso-virtual-moodle-incluye-dominio-y-hosting-1-ano.html" class="btn btn-xs btn-default" style="padding: 2px 10px;">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">
                                <a target="_blank" href="https://cursos.bo/curso-virtual-photoshop-desde-cero.html" style="font-size: 12pt;">
                                    Curso Virtual Photoshop desde cero                                            </a>
                            </td>
                            <td>
                                <a target="_blank" href="https://cursos.bo/curso-virtual-photoshop-desde-cero.html" class="btn btn-xs btn-default" style="padding: 2px 10px;">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">
                                <a target="_blank" href="https://cursos.bo/curso-virtual-de-primeros-auxilios.html" style="font-size: 12pt;">
                                    Curso Virtual de Primeros Auxilios                                            </a>
                            </td>
                            <td>
                                <a target="_blank" href="https://cursos.bo/curso-virtual-de-primeros-auxilios.html" class="btn btn-xs btn-default" style="padding: 2px 10px;">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">
                                <a target="_blank" href="https://cursos.bo/curso-virtual-de-lengua-de-senas.html" style="font-size: 12pt;">
                                    Curso Virtual de lengua de Se&ntilde;as                                            </a>
                            </td>
                            <td>
                                <a target="_blank" href="https://cursos.bo/curso-virtual-de-lengua-de-senas.html" class="btn btn-xs btn-default" style="padding: 2px 10px;">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">
                                <a target="_blank" href="https://cursos.bo/3-cursos-excel-basico-medio-avanzado-macros-con-vb.html" style="font-size: 12pt;">
                                    3 Cursos Excel Basico Medio Avanzado Macros con VB                                            </a>
                            </td>
                            <td>
                                <a target="_blank" href="https://cursos.bo/3-cursos-excel-basico-medio-avanzado-macros-con-vb.html" class="btn btn-xs btn-default" style="padding: 2px 10px;">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">
                                <a target="_blank" href="https://cursos.bo/9-cursos-windows-word-excel-powerpoint-acesss-outlook-visio-publisher-y-onenote.html" style="font-size: 12pt;">
                                    9 Cursos Windows Word Excel PowerPoint Acesss Outlook Visio Publisher y OneNote                                            </a>
                            </td>
                            <td>
                                <a target="_blank" href="https://cursos.bo/9-cursos-windows-word-excel-powerpoint-acesss-outlook-visio-publisher-y-onenote.html" class="btn btn-xs btn-default" style="padding: 2px 10px;">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">
                                <a target="_blank" href="https://cursos.bo/6-cursos-windows-word-excel-powerpoint-outlook-publisher.html" style="font-size: 12pt;">
                                    6 Cursos Windows Word Excel PowerPoint Outlook Publisher                                            </a>
                            </td>
                            <td>
                                <a target="_blank" href="https://cursos.bo/6-cursos-windows-word-excel-powerpoint-outlook-publisher.html" class="btn btn-xs btn-default" style="padding: 2px 10px;">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">
                                <a target="_blank" href="https://cursos.bo/4-cursos-windows-10-word-excel-y-power-point-2016.html" style="font-size: 12pt;">
                                    4 Cursos Windows 10, Word, Excel y Power Point 2016                                            </a>
                            </td>
                            <td>
                                <a target="_blank" href="https://cursos.bo/4-cursos-windows-10-word-excel-y-power-point-2016.html" class="btn btn-xs btn-default" style="padding: 2px 10px;">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">
                                <a target="_blank" href="https://cursos.bo/curso-virtual-ley-1178-safco-y-ds23318-a-doble-certificacion.html" style="font-size: 12pt;">
                                    Curso Virtual Ley 1178 SAFCO y DS23318-A doble certificaci&oacute;n                                            </a>
                            </td>
                            <td>
                                <a target="_blank" href="https://cursos.bo/curso-virtual-ley-1178-safco-y-ds23318-a-doble-certificacion.html" class="btn btn-xs btn-default" style="padding: 2px 10px;">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">
                                <a target="_blank" href="https://cursos.bo/curso-ds-0181-sicoes-empresas-y-consultores-.html" style="font-size: 12pt;">
                                    Curso DS 0181 SICOES Empresas y Consultores                                             </a>
                            </td>
                            <td>
                                <a target="_blank" href="https://cursos.bo/curso-ds-0181-sicoes-empresas-y-consultores-.html" class="btn btn-xs btn-default" style="padding: 2px 10px;">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">
                                <a target="_blank" href="https://cursos.bo/curso-virtual-de-plomeria-en-casa.html" style="font-size: 12pt;">
                                    Curso Virtual de Plomeria en Casa                                            </a>
                            </td>
                            <td>
                                <a target="_blank" href="https://cursos.bo/curso-virtual-de-plomeria-en-casa.html" class="btn btn-xs btn-default" style="padding: 2px 10px;">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">
                                <a target="_blank" href="https://cursos.bo/curso-virtual-de-albanileria-en-casa.html" style="font-size: 12pt;">
                                    Curso Virtual de Alba&ntilde;ileria en Casa                                            </a>
                            </td>
                            <td>
                                <a target="_blank" href="https://cursos.bo/curso-virtual-de-albanileria-en-casa.html" class="btn btn-xs btn-default" style="padding: 2px 10px;">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px;">
                                <a target="_blank" href="https://cursos.bo/curso-virtual-politicas-publicas.html" style="font-size: 12pt;">
                                    Curso virtual Pol&iacute;ticas P&uacute;blicas                                            </a>
                            </td>
                            <td>
                                <a target="_blank" href="https://cursos.bo/curso-virtual-politicas-publicas.html" class="btn btn-xs btn-default" style="padding: 2px 10px;">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
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
            </div>
        </div>
    </section>
</div>
