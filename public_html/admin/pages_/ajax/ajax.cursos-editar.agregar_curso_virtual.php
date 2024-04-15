<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');
?>


<form method="post" id="FORM-modificar_asignacion_onlinecourse">
    <table class="table table-striped table-bordered">
        <tr>
            <td>
                CURSO A ASIGNAR:
            </td>
            <td>
                <select class="form-control" name="id_onlinecourse">
                    <?php
                    $rqdcs1 = query("SELECT id,titulo FROM cursos_onlinecourse WHERE sw_asignacion='1' AND estado='1' AND id NOT IN (SELECT id_onlinecourse FROM cursos_rel_cursoonlinecourse WHERE id_curso='$id_curso') ");
                    while ($rqdcs2 = fetch($rqdcs1)) {
                    ?>
                        <option value="<?php echo $rqdcs2['id']; ?>"><?php echo $rqdcs2['titulo']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                INGRESO DE PARTICIPANTES:
            </td>
            <td class="text-center">

                <label>
                    <input type="radio" value="1" name="estado" checked="" /> HABILITADO
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                    <input type="radio" value="0" name="estado" /> DESHABILITADO
                </label>
            </td>
        </tr>
        <tr>
            <td>
                DOCENTE ASIGNADO:
            </td>
            <td>
                <select class="form-control" name="id_docente">
                    <option value="0">Sin docente asignado</option>
                    <?php
                    $rqdcdc1 = query("SELECT id,nombres FROM cursos_docentes WHERE sw_cursosvirtuales='1' ");
                    while ($rqdcs2 = fetch($rqdcdc1)) {
                    ?>
                        <option value="<?php echo $rqdcs2['id']; ?>"><?php echo $rqdcs2['nombres']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                1er CERTIFICADO ASIGNADO:
            </td>
            <td>
                <select class="form-control" name="id_certificado">
                    <option value="0">Sin 1er certificado asignado</option>
                    <?php
                    $rqdcdcc1 = query("SELECT id,texto_qr FROM cursos_certificados WHERE id_curso='$id_curso' AND estado='1' ");
                    while ($rqdcdcc2 = fetch($rqdcdcc1)) {
                        $selec = '';
                    ?>
                        <option value="<?php echo $rqdcdcc2['id']; ?>" <?php echo $selec; ?>><?php echo $rqdcdcc2['texto_qr']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                2do CERTIFICADO ASIGNADO:
            </td>
            <td>
                <select class="form-control" name="id_certificado_2">
                    <option value="0">Sin 2do certificado asignado</option>
                    <?php
                    $rqdcdcc1 = query("SELECT id,texto_qr FROM cursos_certificados WHERE id_curso='$id_curso' AND estado='1' ");
                    while ($rqdcdcc2 = fetch($rqdcdcc1)) {
                        $selec = '';
                    ?>
                        <option value="<?php echo $rqdcdcc2['id']; ?>" <?php echo $selec; ?>><?php echo $rqdcdcc2['texto_qr']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                FECHA INICIO:
            </td>
            <td>
                <input type="date" class="form-control" name="fecha_inicio" value="<?php echo date("Y-m-d"); ?>" />
            </td>
        </tr>
        <tr>
            <td>
                FECHA FINAL:
            </td>
            <td>
                <input type="date" class="form-control" name="fecha_final" value="<?php echo date("Y-m-d", strtotime("+1 month", time())); ?>" />
            </td>
        </tr>
        <tr>
            <td>
                C&Oacute;DIGO DE ASISTENCIA:
            </td>
            <td class="text-center">

                <label>
                    <input type="radio" value="1" name="sw_cod_asistencia"/> SI
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                    <input type="radio" value="0" name="sw_cod_asistencia" checked=""/> NO
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
                <input type="submit" name="habilitar-onlinecourse" value="ASIGNAR CURSO VIRTUAL" class="btn btn-success active btn-block" />
                <br>
                &nbsp;
            </td>
        </tr>
    </table>
</form>

<hr />