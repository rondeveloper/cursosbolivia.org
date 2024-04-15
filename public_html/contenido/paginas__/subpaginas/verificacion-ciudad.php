<?php
/* mensaje */
$mensaje = '';

/* data */
$id_participante = $get[2];
$hash = $get[3];

/* verificacion */
if (md5($id_participante . '54215') != $hash) {
    echo "<script>alert('DENEGADO');location.href='$dominio';</script>";
    exit;
}

/* participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$participante = fetch($rqdp1);
$nombre_participante = $participante['nombres'] . ' ' . $participante['apellidos'];
$correo_participante = $participante['correo'];
$id_proceso_registro = $participante['id_proceso_registro'];
$id_departamento = $participante['id_departamento'];

$sw_registrado = false;

/* confirmar-registro */
if (isset_post('confirmar-registro')) {

    $id_departamento_post = post('id_departamento');

    query("UPDATE cursos_participantes SET id_departamento='$id_departamento_post' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    query("INSERT INTO auxtabla_confimacionciudad(id_participante, id_departamento) VALUES ('$id_participante','$id_departamento_post')");

    $mensaje = '<br><br><div class="alert alert-success">
  <strong>CONFIRMACI&Oacute;N EXITOSA</strong>
se ha registrado sus datos.
</div>';
    $sw_registrado = true;
}

?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <?php echo $mensaje; ?>
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">

                <div class="TituloArea">
                    <h3>CONFIRMACI&Oacute;N DE CIUDAD</h3>
                </div>

                <div class="Titulo_texto1">
                    <?php if (!$sw_registrado) { ?>
                        <div style="background: #ffefd3;padding: 20px;font-size: 12pt;text-align: justify;">
                            Hola <?php echo $nombre_participante; ?> necesitamos confirmar la ciudad donde se encuentra para poder mandar los certificados f&iacute;sicos IPELC.
                            <br>
                            <br>
                            Por favor seleciona la ciudad donde te encuentras y presiona el boton CONFIRMAR.
                            <br>
                            <br>

                            <div style="text-align:center;padding: 20px 0px;">
                                <form action="" method="post">
                                    <b>Ciudad:</b>
                                    <br>
                                    <select name="id_departamento" class="form-control">
                                        <?php
                                        $rqdd1 = query("SELECT * FROM departamentos ");
                                        while ($rqdd2 = fetch($rqdd1)) {
                                        ?>
                                            <option value="<?php echo $rqdd2['id']; ?>" <?php echo $id_departamento == $rqdd2['id'] ? ' selected="selected" ' : ''; ?>><?php echo $rqdd2['nombre']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <br>
                                    <input type="submit" name="confirmar-registro" value="CONFIRMAR" class="btn btn-success" style="width: auto;border-radius: 5px;padding: 10px 20px;">
                                </form>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div style="background: #ffefd3;padding: 20px;font-size: 12pt;text-align: justify;">
                            Gracias por realizar su confirmaci&oacute;n.
                        </div>
                    <?php } ?>
                </div>
                <hr />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
            </div>
        </div>
    </section>
</div>