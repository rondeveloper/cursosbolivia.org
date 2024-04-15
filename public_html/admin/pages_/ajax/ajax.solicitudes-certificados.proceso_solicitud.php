<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* recepcion de datos POST */
$id_solicitud = post('id_solicitud');

/* registro reprogramacion */
$resultado1 = query("SELECT * FROM cursos_solicitudes_de_certificado WHERE id='$id_solicitud' LIMIT 1 ");
$solicitud = fetch($resultado1);
?>

<h4 class="btn btn-primary btn-block active">DATOS DE CURSO</h4>

<div class="row">
    <div class="col-md-12">
        <h3 class="text-center" style="font-size: 20pt;
            text-transform: uppercase;
            color: #3db720;font-weight: bold;">
            <?php echo $solicitud['nombre_curso']; ?>
        </h3>
    </div>
</div>

<h4 class="btn btn-primary btn-block active">
    PARTICIPANTE
</h4>

<div class="row">
    <div class="col-md-12 text-left text-center" style="line-height: 0.2;">
        <h3 class="text-center" style="font-size: 20pt;
            text-transform: uppercase;
            color: #00789f;font-weight: bold;">
            <?php echo trim($solicitud['prefijo'] . ' ' . $solicitud['nombre'] . ' ' . $solicitud['apellidos']); ?>
        </h3>
        <b style="font-size: 17pt;color: gray;">
            CI: &nbsp; <?php echo trim($solicitud['ci'] . ' ' . $solicitud['ci_expedido']); ?>
        </b>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-md-6">
        CELULAR: <?php echo $solicitud['celular']; ?>
        <br/>
        CORREO: <?php echo $solicitud['correo']; ?>
    </div>
    <div class="col-md-6 text-center">
        <?php
        if ($solicitud['estado'] == '1') {
            ?>
            <b class="btn btn-default btn-lg active">EN ESPERA</b>
            <?php
        } elseif ($solicitud['estado'] == '2') {
            ?>
            <b class="btn btn-success btn-lg active">REVISADO</b>
            <?php
        }
        ?>
    </div>
</div>
<hr/>

<h4 class="btn btn-primary btn-block active">
    CERTIFICADOS
</h4>

<hr/>

<!-- DIV CONTENT AJAX :: EMITE CERTIFICADO P1 -->
<div id="ajaxloading-emite_certificado_p1"></div>
<div id="ajaxbox-emite_certificado_p1">

    <div class="row">
        <div class="col-md-8">
            <?php
            if ($id_certificado_1 == '0') {
                echo "<b>El curso no tiene 1er certificado asociado</b>";
            } else {
                $rqdc1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado_1' ORDER BY id DESC limit 1 ");
                $rqdc2 = fetch($rqdc1);

                echo "1er CERTIFICADO: " . $rqdc2['codigo'];

                echo "<br/>";

                echo "TEXTO: " . $rqdc2['texto_qr'];
            }
            ?>
        </div>
        <div class="col-md-4">
            <?php
            if ($id_emision_certificado_1 == '0') {
                echo "<b>NO EMITIDO</b>";
                /*
                  if ($sw_proceso_activado && $id_certificado_1 !== '0' && $sw_participante_habilitado) {
                  ?>
                  <br/>
                  <span id='box-modal_emision_certificado-button-<?php echo $participante['id']; ?>'>
                  <a onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 1);
                  $('#id_certificado').val('<?php echo $id_certificado_1; ?>');" class="btn btn-xs btn-primary active">EMITIR CERT. 1</a>
                  </span>
                  <?php
                  }
                 */
            } else {
                ?>
                <b>EMITIDO <?php echo $id_emision_certificado_1; ?></b>
                <br/>
                <a onclick="imprimir_certificado_individual('<?php echo $id_emision_certificado_1; ?>');" class="btn btn-xs btn-warning active">IMPRIMIR</a>
                <?php
            }
            ?>
        </div>
    </div>



    <hr/>

    <div class="row">
        <div class="col-md-8">
            <?php
            if ($id_certificado_2 == '0') {
                echo "<b>El curso no tiene 2do certificado asociado</b>";
            } else {
                $rqdc1 = query("SELECT * FROM cursos_certificados WHERE id='$id_certificado_2' ORDER BY id DESC limit 1 ");
                $rqdc2 = fetch($rqdc1);

                echo "2do CERTIFICADO: " . $rqdc2['codigo'];

                echo "<br/>";

                echo "TEXTO: " . $rqdc2['texto_qr'];
            }
            ?>
        </div>
        <div class="col-md-4 tex-center">
            <?php
            if ($id_emision_certificado_2 == '0') {
                echo "<b>NO EMITIDO</b>";
                /*
                  if ($sw_proceso_activado && $id_certificado_2 !== '0' && $sw_participante_habilitado) {
                  ?>
                  <br/>
                  <span id='box-modal_emision_certificado-button-2-<?php echo $participante['id']; ?>'>
                  <a onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 2);
                  $('#id_certificado').val('<?php echo $id_certificado_2; ?>');" class="btn btn-xs btn-primary active">EMITIR CERT. 2</a>
                  </span>
                  <?php
                  if ($id_certificado_1 !== '0' && $sw_participante_habilitado && $id_emision_certificado_1=='0'&& $id_emision_certificado_2=='0') {
                  ?>
                  <hr/>
                  <span id='box-modal_emision_certificado-button-12-<?php echo $participante['id']; ?>'>
                  <a onclick="emite_certificado_p1(<?php echo $participante['id']; ?>, 12);
                  $('#id_certificado').val('<?php echo "$id_certificado_1|AND|$id_certificado_2"; ?>');" class="btn btn-xs btn-primary active">EMITIR CERT. 1 y 2</a>
                  </span>
                  <?php
                  }
                  }
                 */
            } else {
                ?>
                <b>EMITIDO <?php echo $id_emision_certificado_2; ?></b>
                <br/>
                <a onclick="imprimir_certificado_individual('<?php echo $id_emision_certificado_2; ?>');" class="btn btn-xs btn-warning active">IMPRIMIR</a>
                <?php
            }
            ?>
        </div>
    </div>


</div>

<style>
    #ajaxbox-emite_certificado_p1 img{
        display: none;
    }
</style>


<hr/>

<input type='hidden' id='receptor_de_certificado' value='<?php echo $participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']; ?>'/>
<input type='hidden' id='id_certificado' value='<?php echo $id_certificado_1; ?>'/>
<input type='hidden' id='id_curso' value='<?php echo $curso['id']; ?>'/>




<h4 class="btn btn-primary btn-block active">ASIGNACI&Oacute;N DE CURSO</h4>

<hr/>
<div class="row">
    <div class="col-md-12 text-center" id="AJAXBOX-asignar_asistencia">
        <table class="table table-bordered">
            <?php
            if ($estado_reprogramacion == '1') {
                ?>
                <tr>
                    <td>
                        <select id="id_curso_asignacion" class="form-control">
                            <?php
                            $rqdcv1 = query("SELECT id,titulo,fecha FROM cursos WHERE estado IN ('1','2') ORDER BY fecha ASC ");
                            while ($rqdcv2 = fetch($rqdcv1)) {
                                ?>
                                <option value="<?php echo $rqdcv2['id']; ?>"><?php echo date("d/m/Y", strtotime($rqdcv2['fecha'])); ?> | <?php echo $rqdcv2['titulo']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <b class='btn btn-success active btn-block' onclick="asignar_asistencia('<?php echo $id_solicitud; ?>');"> &nbsp;ASIGNAR ASISTENCIA&nbsp; </b>
                    </td>
                </tr>
                <?php
            } elseif ($estado_reprogramacion == '2') {
                $id_curso_asignado = $solicitud['id_curso_asignado'];
                /* curso */
                $rqdca1 = query("SELECT titulo,fecha,(select nombre from departamentos where id=c.id_ciudad)departamento FROM cursos c WHERE c.id='$id_curso_asignado' LIMIT 1 ");
                $curso_a = fetch($rqdca1);
                ?>
                <tr>
                    <td>
                        Curso asignado
                    </td>
                    <td>
                        <?php echo $curso_a['titulo']; ?>
                        <br/>
                        <?php echo date("d / M / Y", strtotime($curso_a['fecha'])) . ' - ' . $curso_a['departamento']; ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>
<hr/>