<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador() && !isset_organizador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_participante = post('id_participante');

/* participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqdp2 = fetch($rqdp1);
?>
<td colspan="2">
<form id="FORM-reprogramacion_de_curso" method="post">
    <table class="table table-striped table-bordered">
        <tr>
            <td style="width: 170px;">
                Participante:
            </td>
            <td>
                <input type="text" class="form-control" value="<?php echo $rqdp2['nombres'].' '.$rqdp2['apellidos']; ?>" placeholder="Motivo de reprogramaci&oacute;n..." disabled=""/>
            </td>
        </tr>
        <tr>
            <td style="width: 170px;">
                CI:
            </td>
            <td>
                <input type="text" class="form-control" value="<?php echo $rqdp2['ci']; ?>" placeholder="Motivo de reprogramaci&oacute;n..." disabled=""/>
            </td>
        </tr>
        <tr>
            <td style="width: 170px;">
                Motivo de reprogramaci&oacute;n:
            </td>
            <td>
                <input type="text" class="form-control" name="motivo_reprogramacion" placeholder="Motivo de reprogramaci&oacute;n..."/>
            </td>
        </tr>
        <tr>
            <td>
                Fecha tentativa a tomar el curso:
            </td>
            <td>
                <input type="text" class="form-control" name="fecha_tentativa" placeholder="Se plantea tomar el curso en..."/>
            </td>
        </tr>
        <tr>
            <td>
                Correo de referencia:
            </td>
            <td>
                <input type="text" class="form-control" name="correo" value="<?php echo $rqdp2['correo']; ?>" placeholder="Correo referencia..."/>
            </td>
        </tr>
        <tr>
            <td>
                Celular de referencia:
            </td>
            <td>
                <input type="text" class="form-control" name="celular" value="<?php echo $rqdp2['celular']; ?>" placeholder="Celular referencia..."/>
            </td>
        </tr>
        <tr>
            <td>
                Observaci&oacute;n:
            </td>
            <td>
                <input type="text" class="form-control" name="observacion"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="id_participante" value="<?php echo $id_participante; ?>"/>
                <button class="btn btn-success btn-block active" onclick="reprogramacion_de_curso_p2();">REPROGRAMAR ASISTENCIA</button>
            </td>
        </tr>
    </table>
</form>
</td>



