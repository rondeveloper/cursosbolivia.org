<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_emision_certificado = post('id_emision_certificado');

$rqdc1 = query("SELECT *,(select nombre from administradores where id=c.id_administrador_emisor)administrador FROM cursos_emisiones_certificados c WHERE id='$id_emision_certificado' ");
$rqdc2 = mysql_fetch_array($rqdc1);
?>
<p>El proceso de envio de certificado asigna ciertos datos de control de traking de envio de correspondencia para los respectivos certificados.</p>

<hr/>

<table class="table table-hover table-striped table-bordered">
    <tr>
        <td>ID de certificado</td>
        <td><?php echo $rqdc2['certificado_id']; ?></td>
    </tr>
    <tr>
        <td>Receptor de certificado</td>
        <td><?php echo $rqdc2['receptor_de_certificado']; ?></td>
    </tr>
    <tr>
        <td>Fecha de emision</td>
        <td><?php echo $rqdc2['fecha_emision']; ?></td>
    </tr>
    <tr>
        <td>Emitido por</td>
        <td><?php echo $rqdc2['administrador']; ?></td>
    </tr>
</table>

<hr/>

<?php
$rqvenvp1 = query("SELECT *,(select nombre from departamentos where id=e.id_departamento)departamento FROM cursos_envio_certificados e WHERE id_emision_certificado='$id_emision_certificado' ");
if (mysql_num_rows($rqvenvp1) > 0) {
    $rqvenvp2 = mysql_fetch_array($rqvenvp1);
    ?>
    <div class="text-center">
        <h4>PROCESO DE ENVIO PENDIENTE</h4>
        <br/>
        <table class="table table-hover table-striped table-bordered">
            <tr>
                <td>Estado</td>
                <td>
                    <?php
                    if ($rqvenvp2['sw_enviado'] == 1) {
                        echo "ENVIADO";
                    } else {
                        echo "A ENVIAR";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Departamento</td>
                <td><?php echo $rqvenvp2['departamento']; ?></td>
            </tr>
            <tr>
                <td>Direcci&oacute;n</td>
                <td><?php echo $rqvenvp2['direccion']; ?></td>
            </tr>
            <tr>
                <td>Destinatario</td>
                <td><?php echo $rqvenvp2['destinatario']; ?></td>
            </tr>
            <tr>
                <td>Celular destinatario</td>
                <td><?php echo $rqvenvp2['celular']; ?></td>
            </tr>
            <tr>
                <td>Enviador</td>
                <td><?php echo $rqvenvp2['enviador']; ?></td>
            </tr>
            <tr>
                <td>Envio mediante</td>
                <td><?php echo $rqvenvp2['enviado_mediante']; ?></td>
            </tr>
            <?php
            if ($rqvenvp2['sw_enviado'] == 1) {
                ?>
                <tr>
                    <td>Codigo de traking</td>
                    <td><?php echo $rqvenvp2['codigo_traking']; ?></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td>Observaciones</td>
                <td><?php echo $rqvenvp2['observaciones']; ?></td>
            </tr>
        </table>
    </div>
    <?php
    if ($rqvenvp2['sw_enviado'] == 0) {
        ?>
        <form id="FORM-proceso_envio_de_certificado_p3" action="" method="POST">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <td>Codigo de traking:</td>
                    <td><input type="text" class="form-control" name="codigo_traking" value=""/></td>
                </tr>
                <tr>
                    <td>Observaciones</td>
                    <td><textarea class="form-control" name="observaciones" style="height:67px;"><?php echo $rqvenvp2['observaciones']; ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="AJAXCONTENT-proceso_envio_de_certificado_p3">
                            <input type="hidden" name="id_emision_certificado" value="<?php echo $id_emision_certificado; ?>"/>
                            <input type="hidden" name="id_envio_certificado" value="<?php echo $rqvenvp2['id']; ?>"/>
                            <b class="btn btn-success btn-block" onclick="proceso_envio_de_certificado_p3();">MARCAR COMO ENVIADO</b>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
        <?php
    } elseif ($rqvenvp2['sw_recibido'] == 0) {
        ?>
        <form id="FORM-proceso_envio_de_certificado_p4" action="" method="POST">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <td>Observaciones</td>
                    <td><textarea class="form-control" name="observaciones" style="height:67px;"><?php echo $rqvenvp2['observaciones']; ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="AJAXCONTENT-proceso_envio_de_certificado_p4">
                            <input type="hidden" name="id_emision_certificado" value="<?php echo $id_emision_certificado; ?>"/>
                            <input type="hidden" name="id_envio_certificado" value="<?php echo $rqvenvp2['id']; ?>"/>
                            <b class="btn btn-info btn-block" onclick="proceso_envio_de_certificado_p4();">MARCAR COMO RECIBIDO</b>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
        <?php
    }
    ?>

    <?php
} else {
    ?>

    <div class="text-center">
        <h4>&iquest; Desea asignar un proceso de envio ?</h4>
        <br/>
        <?php
        $env_id_departamento = "";
        $env_direccion = "";
        $env_destinatario = "";
        $env_celular = "";
        $rqdenvc1 = query("SELECT * FROM cursos_proceso_registro_direnvio WHERE id_proceso_registro=(select id_proceso_registro from cursos_participantes where id='" . $rqdc2['administrador'] . "') ");
        if (mysql_num_rows($rqdenvc1) > 0) {
            $rqdenvc2 = mysql_fetch_array($rqdenvc1);
            $env_id_departamento = $rqdenvc2['id_departamento'];
            $env_direccion = $rqdenvc2['direccion'];
            $env_destinatario = $rqdenvc2['destinatario'];
            $env_celular = $rqdenvc2['celular'];
        }
        ?>
        <form id="FORM-proceso_envio_de_certificado_p2" action="" method="POST">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <td>Departamento</td>
                    <td>
                        <select class="form-control" name="id_departamento">
                            <?php
                            $rqdd1 = query("SELECT nombre,id FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                            while ($rqdd2 = mysql_fetch_array($rqdd1)) {
                                $selected = '';
                                if ($env_id_departamento == $rqdd2['id']) {
                                    $selected = ' selected="selected" ';
                                }
                                ?>
                                <option value="<?php echo $rqdd2['id']; ?>" <?php echo $selected; ?>><?php echo $rqdd2['nombre']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Direcci&oacute;n</td>
                    <td><textarea class="form-control" name="direccion" style="height:70px;"><?php echo $env_direccion; ?></textarea></td>
                </tr>
                <tr>
                    <td>Destinatario</td>
                    <td><input type="text" class="form-control" name="destinatario" value="<?php echo $env_destinatario; ?>"/></td>
                </tr>
                <tr>
                    <td>Celular destinatario</td>
                    <td><input type="text" class="form-control" name="celular_destinatario" value="<?php echo $env_celular; ?>"/></td>
                </tr>
                <tr>
                    <td>Enviador</td>
                    <td><input type="text" class="form-control" name="enviador" value=""/></td>
                </tr>
                <tr>
                    <td>Envio mediante</td>
                    <td><input type="text" class="form-control" name="envio_mediante" value=""/></td>
                </tr>
                <tr>
                    <td>Observaciones</td>
                    <td><textarea class="form-control" name="observaciones" style="height:75px;"></textarea></td>
                </tr>
            </table>
            <div id="AJAXCONTENT-proceso_envio_de_certificado_p2">
                <br/>
                <input type="hidden" name="id_emision_certificado" value="<?php echo $id_emision_certificado; ?>"/>
                <b class="btn btn-success" onclick="proceso_envio_de_certificado_p2();">ASIGNAR ENVIO</b>
                <br/>
            </div>
        </form>
    </div>

    <?php
}
?>

<hr/>

<script>
    function proceso_envio_de_certificado_p2() {
        var form = $("#FORM-proceso_envio_de_certificado_p2").serialize();
        $("#AJAXCONTENT-proceso_envio_de_certificado_p2").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.proceso_envio_de_certificado_p2.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-proceso_envio_de_certificado_p2").html(data);
            }
        });
    }
</script>

<script>
    function proceso_envio_de_certificado_p3() {
        var form = $("#FORM-proceso_envio_de_certificado_p3").serialize();
        $("#AJAXCONTENT-proceso_envio_de_certificado_p3").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.proceso_envio_de_certificado_p3.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-proceso_envio_de_certificado_p3").html(data);
            }
        });
    }
</script>


<script>
    function proceso_envio_de_certificado_p4() {
        var form = $("#FORM-proceso_envio_de_certificado_p4").serialize();
        $("#AJAXCONTENT-proceso_envio_de_certificado_p4").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.proceso_envio_de_certificado_p4.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-proceso_envio_de_certificado_p4").html(data);
            }
        });
    }
</script>

