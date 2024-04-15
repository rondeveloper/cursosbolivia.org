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
                <div class="TituloArea">
                    <h3>DATOS DE USUARIO</h3>
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
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <td>NOMBRE</td>
                                    <td><?php echo $nombre_usuario; ?></td>
                                </tr>
                                <tr>
                                    <td>EMAIL</td>
                                    <td>
                                        <?php
                                        if ($email_usuario !== '') {
                                            echo $email_usuario;
                                        } else {
                                            echo "Sin dato";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>CELULAR</td>
                                    <td>
                                        <?php
                                        if ($celular_usuario !== '') {
                                            echo $celular_usuario;
                                        } else {
                                            echo "Sin dato";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DEPARTAMENTO</td>
                                    <td>
                                        <?php
                                        if ($id_departamento_usuario !== '0') {
                                            $rqdd1 = query("SELECT nombre FROM departamentos WHERE id='$id_departamento_usuario' LIMIT 1 ");
                                            $rqdd2 = fetch($rqdd1);
                                            echo $rqdd2['nombre'];
                                        } else {
                                            echo "Sin dato";
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TIPO DE USUARIO</td>
                                    <td>PARTICIPANTE</td>
                                </tr>
                                <tr>
                                    <td>TIPO DE CUENTA</td>
                                    <td>FREE</td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
                <br/>
                <hr/>                

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
