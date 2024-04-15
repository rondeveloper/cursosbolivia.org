<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* data */
$id_asignacion_onlinecourse = post('id_asignacion_onlinecourse');

/* registro */
$rqmc1 = query("SELECT * FROM cursos_rel_cursoonlinecourse WHERE id='$id_asignacion_onlinecourse' ");
$rqmc2 = mysql_fetch_array($rqmc1);
$id_onlinecourse = $rqmc2['id_onlinecourse'];
$id_docente = $rqmc2['id_docente'];
$id_curso = $rqmc2['id_curso'];
$id_certificado = $rqmc2['id_certificado'];
$id_certificado_2 = $rqmc2['id_certificado_2'];

/* onlinecourse */
$rqdco1 = query("SELECT * FROM cursos_onlinecourse WHERE id='$id_onlinecourse' LIMIT 1 ");
$rqdco2 = mysql_fetch_array($rqdco1);
?>
<form method="post" id="FORM-editar_asignacion_onlinecourse">
    <table class="table table-striped table-bordered">
        <tr>
            <td>
                CURSO ASIGNADO:
            </td>
            <td>
                <select class="form-control" name="id_onlinecourse">
                    <?php
                    $rqdcs1 = query("SELECT id,titulo FROM cursos_onlinecourse WHERE (sw_asignacion='1' AND estado='1') OR id='$id_onlinecourse' ");
                    while ($rqdcs2 = mysql_fetch_array($rqdcs1)) {
                        $selec = '';
                        if ($rqdcs2['id'] == $id_onlinecourse) {
                            $selec = ' selected="selected" ';
                        }
                        ?>
                        <option value="<?php echo $rqdcs2['id']; ?>" <?php echo $selec; ?>><?php echo $rqdcs2['titulo']; ?></option>
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
                <?php
                if ($rqmc2['estado'] == '1') {
                    ?>
                    <label>
                        <input type="radio" value="1" name="estado" checked=""/> HABILITADO
                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="radio" value="0" name="estado"/> DESHABILITADO
                    </label>
                    <?php
                } else {
                    ?>
                    <label>
                        <input type="radio" value="1" name="estado"/> HABILITADO
                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="radio" value="0" name="estado" checked=""/> DESHABILITADO
                    </label>
                    <?php
                }
                ?>
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
                    while ($rqdcs2 = mysql_fetch_array($rqdcdc1)) {
                        $selec = '';
                        if ($rqdcs2['id'] == $id_docente) {
                            $selec = ' selected="selected" ';
                        }
                        ?>
                        <option value="<?php echo $rqdcs2['id']; ?>" <?php echo $selec; ?>><?php echo $rqdcs2['nombres']; ?></option>
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
                    while ($rqdcdcc2 = mysql_fetch_array($rqdcdcc1)) {
                        $selec = '';
                        if ($rqdcdcc2['id'] == $id_certificado) {
                            $selec = ' selected="selected" ';
                        }
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
                    while ($rqdcdcc2 = mysql_fetch_array($rqdcdcc1)) {
                        $selec = '';
                        if ($rqdcdcc2['id'] == $id_certificado_2) {
                            $selec = ' selected="selected" ';
                        }
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
                <input type="date" class="form-control" name="fecha_inicio" value="<?php echo $rqmc2['fecha_inicio']; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                FECHA FINAL: 
            </td>
            <td>
                <input type="date" class="form-control" name="fecha_final" value="<?php echo $rqmc2['fecha_final']; ?>"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="id_asignacion_onlinecourse" value="<?php echo $id_asignacion_onlinecourse; ?>"/>
                <a class="btn btn-primary active btn-block" onclick="modificar_asignacion_onlinecourse_p2();">
                    <i class="fa fa-edit"></i> MODIFICAR ASIGNACI&Oacute;N
                </a>
            </td>
        </tr>
    </table>
</form>