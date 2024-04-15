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
$rqdu1 = query("SELECT * FROM usuarios WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
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
                
                 <?php 
                if(false){
                   $rqdd1 = query("SELECT aux_id_cert,email,id FROM usuarios ");
                   while ($rqdd2 = mysql_fetch_array($rqdd1)){
                       $aux_id_cert = $rqdd2['aux_id_cert'];
                       $email = $rqdd2['email'];

                       /* id usuario */
                       $rqdu1 = query("SELECT id,nombre FROM usuarios WHERE email LIKE '$email' ORDER BY id ASC limit 1 ");
                       $rqdu2 = mysql_fetch_array($rqdu1);
                       $id_usuario = $rqdu2['id'];
                       $receptor_de_certificado = $rqdu2['nombre'];

                       /* id certificado */
                       $rqddcrt1 = query("SELECT id FROM certificados WHERE aux_id_cert='$aux_id_cert' ORDER BY id ASC limit 1 ");
                       $rqddcrt2 = mysql_fetch_array($rqddcrt1);
                       $id_certificado = $rqddcrt2['id'];

                       $id_modelo_certificado = 10 + (int)$aux_id_cert;
                       $id_curso = 10;
                       $md = 10;
                       $certificado_id = 'C'.$rqdd2['id'].strtoupper(substr(md5(rand(9999,9999999)),15,3));

                       query("INSERT INTO emisiones_certificados(id_certificado, id_modelo_certificado, id_curso, id_usuario, md, certificado_id, receptor_de_certificado, id_administrador_emisor, fecha_emision, estado) VALUES ('$id_certificado','$id_modelo_certificado','$id_curso','$id_usuario','$md','$certificado_id','$receptor_de_certificado','0',NOW(),'1')");
                       echo $rqdd2['id']."OK<br>";
                   }
                
                }
                
                
                if(true){
                    echo "<h2>TEST 954136465</h2>";
                    
                    
                    $rqdd1 = query("SELECT email,id,nombre FROM usuarios WHERE estado='1' LIMIT 10000 ");
                    while ($rqdd2 = mysql_fetch_array($rqdd1)){
                       $email = $rqdd2['email'];
                       $id = $rqdd2['id'];
                       $nombre = $rqdd2['nombre'];

                       /* id usuario */
                       $rqdu1 = query("SELECT nombres,apellidos FROM participantes WHERE email LIKE '$email' ORDER BY id ASC limit 5 ");
                       $nom_f = '';
                       while($rqdu2 = mysql_fetch_array($rqdu1)){
                           $nombre_part = trim(trim($rqdu2['nombres']).' '.trim($rqdu2['apellidos']));
                           if(strlen($nombre_part)>strlen($nom_f)){
                               $nom_f = $nombre_part;
                           }
                       }
                       if($nombre!=$nom_f || true){
                           query("UPDATE usuarios SET nombre='$nom_f' WHERE id='$id' LIMIT 1 ");
                           query("UPDATE emisiones_certificados SET receptor_de_certificado='$nom_f' WHERE id_usuario='$id' ORDER BY id ASC limit 5 ");
                       }
                       
                       echo $nom_f."OK<br>";
                   }
                
                }
                
                
                ?>

                <?php echo $mensaje; ?>

                <div class="TituloArea">
                    <h3>CERTIFICADOS OBTENIDOS</h3>
                </div>
                <?php
                $rqcp1 = query("SELECT c.texto_qr,c.codigo,u.id,u.nombre FROM usuarios u INNER JOIN certificados c ON u.aux_id_cert=c.aux_id_cert WHERE u.email='$email_usuario' ");
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
                                    <?php echo 'C-'.$rqcu2['id']; ?>
                                </td>
                                <td>
                                    <?php echo $rqcu2['codigo'].' | '.utf8_encode($rqcu2['texto_qr']); ?>
                                </td>
                                <td>
                                    <?php echo $rqcu2['nombre']; ?>
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