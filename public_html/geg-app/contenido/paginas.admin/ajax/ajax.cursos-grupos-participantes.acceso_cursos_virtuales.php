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

/* admisnitrador */
$id_administrador = administrador('id');

/* recepcion de datos POST */
$id_grupo = post('id_grupo');
$nombres_participante = post('nombres');
$apellidos_participante = post('apellidos');

/* ids_certificados */
$ids_certpart = '';
?>

<div class="text-center">
    <b>Participante</b>
    <h3><?php echo $nombres_participante . ' ' . $apellidos_participante; ?></h3>
</div>

<hr/>

<table class="table table-bordered" style="margin: 10px 0px;border: 2px solid #bccdd8;background: #FFF;">
    <tr>
        <th>Curso</th>
        <th>Habilitaci&oacute;n</th>
        <th>C-virtual</th>
        <th>Acceso</th>
        <th>Avance</th>
    </tr>
    <?php
    $rqccg1 = query("SELECT id,titulo,fecha,id_certificado FROM cursos WHERE id IN (SELECT id_curso FROM cursos_participantes WHERE nombres LIKE '$nombres_participante' AND apellidos LIKE '$apellidos_participante' AND id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') )");
    $cnt_certs_validos = 0;
    $cnt_certs_ya_emitidos = 0;
    $ids_participantes_ya_emitidos = '';
    $contenido_textarea = '';
    
    while ($curso = mysql_fetch_array($rqccg1)) {
        $id_curso = $curso['id'];
        /* curso virtual */
        $nombre_curso_virtual = 'Sin curso virtual asignado';
        $sw_curso_virtual = false;
        $rqdcv1 = query("SELECT titulo,urltag FROM cursos_onlinecourse WHERE id IN (select id_onlinecourse from cursos_rel_cursoonlinecourse where id_curso='$id_curso') ");
        if (mysql_num_rows($rqdcv1) > 0) {
            $sw_curso_virtual = true;
            $rqdcv2 = mysql_fetch_array($rqdcv1);
            $nombre_curso_virtual = $rqdcv2['titulo'];
            $urltag_curso_virtual = $rqdcv2['urltag'];
            $url_ingreso_cv = 'https://cursos.bo/curso-online/'.$urltag_curso_virtual.'.html';
            
            $contenido_textarea .= '
*'.$nombre_curso_virtual.'*
*URL:*  '.$url_ingreso_cv.'';
        }

        /* participante */
        $rqddp1 = query("SELECT id,nombres,apellidos,prefijo,id_proceso_registro,modo_pago,estado,sw_cvirtual,id_usuario FROM cursos_participantes WHERE nombres LIKE '$nombres_participante' AND apellidos LIKE '$apellidos_participante' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
        $participante = mysql_fetch_array($rqddp1);
        $id_participante = $participante['id'];
        $id_proceso_registro_participante = $participante['id_proceso_registro'];
        $estado_participante = $participante['estado'];
        $id_usuario_participante = $participante['id_usuario'];
        $modo_pago_participante = $participante['modo_pago'];
        $nom_para_certificado = trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']);
        
        /* usuario */
        $rqddu1 = query("SELECT email,password FROM cursos_usuarios WHERE id='$id_usuario_participante' ");
        $rqddu2 = mysql_fetch_array($rqddu1);
        $user_usuario = $rqddu2['email'];
        $password_usuario = $rqddu2['password'];
        $contenido_textarea .= '
*USUARIO:*  '.$user_usuario.'
*CONTRASE&Ntilde;A:*  '.$password_usuario.'

';

        /* registro */
        $rqdpr1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro_participante' ORDER BY id DESC limit 1 ");
        $proc_registro = mysql_fetch_array($rqdpr1);
        /* administrador */
        $nombre_administrador = 'Sistema';
        if ($proc_registro['id_administrador'] !== '0') {
            $rqda1 = query("SELECT nombre FROM administradores WHERE id='" . $proc_registro['id_administrador'] . "' LIMIT 1 ");
            $rqda2 = mysql_fetch_array($rqda1);
            $nombre_administrador = $rqda2['nombre'];
        }
        ?>
        <tr>
            <td>
                <?php echo $curso['titulo']; ?> 
            </td>
            <td>
                <?php
                if ($estado_participante == '1') {
                    echo 'Habilitado';
                    $sw_habilitado = true;
                } else {
                    echo 'No habilitado';
                    $sw_habilitado = false;
                }
                ?>
            </td>
            <td>
                <?php echo $nombre_curso_virtual; ?>
            </td>
            <td  id="ajaxloading-acceso_cvirtual-p<?php echo $id_participante; ?>">
                <?php
                if ($sw_habilitado) {
                    if ($sw_curso_virtual && $participante['sw_cvirtual'] == 1) {
                        ?>
                        <div style="color:green;background: #e3efd5;padding: 7px;text-align: center;border: 1px solid #9cbf73;">HABILITADO</div>
                        <div style="padding: 5px;text-align: center;border: 1px solid #EEE;">
                            Des-habilitar: <b class="btn btn-danger btn-xs" onclick="elimina_participante_cvirtual_p2(<?php echo $id_participante; ?>);">X</b>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div style="color:#FFF;background: #ef8a80;padding: 7px;text-align: center;border: 1px solid #e74c3c;">NO HABILITADO</div>
                        <div style="padding: 5px;text-align: center;border: 1px solid #EEE;">
                            Habilitar: &nbsp; <b class="btn btn-success btn-xs" onclick="habilita_participante_cvirtual_p2(<?php echo $id_participante; ?>);"><i class="fa fa-check"></i></b>
                        </div>
                        <?php
                    }
                }
                ?>
            </td>
            <td>
                <?php
                if ($sw_habilitado) {
                    if ($sw_curso_virtual && $participante['sw_cvirtual'] == 1) {
                        ?>
                        <b class="btn btn-info btn-xs" onclick="avance_cvirtual(<?php echo $id_participante; ?>);">AVANCE</b>
                        <?php
                    } else {
                        ?>
                        <b class="btn btn-default btn-xs" onclick="avance_cvirtual(<?php echo $id_participante; ?>);">AVANCE</b>
                        <?php
                    }
                }
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<hr>

<!-- DIV CONTENT AJAX :: HABILITACION PARTICIPANTE P1 -->
<div id="ajaxloading-avance_cvirtual"></div>
<div id="ajaxbox-avance_cvirtual"></div>

<div class="panel panel-success">
  <div class="panel-heading">ACCESO A CURSO EN FORMATO TEXTO</div>
  <div class="panel-body">
      <textarea class="form-control" style="height: 620px;"><?php echo $contenido_textarea; ?></textarea>
  </div>
</div>

<br/>
<br/>


<!-- END Modal avance-cvirtual -->
<script>
    function avance_cvirtual(id_participante) {
        $("#ajaxbox-avance_cvirtual").html("");
        $("#ajaxloading-avance_cvirtual").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.avance_cvirtual.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxloading-avance_cvirtual").html("");
                $("#ajaxbox-avance_cvirtual").html(data);
            }
        });
    }
</script>

<script>
    function habilita_participante_cvirtual_p2(id_participante) {
        $("#ajaxloading-acceso_cvirtual-p" + id_participante).html("");
        $("#ajaxloading-acceso_cvirtual-p" + id_participante).html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.habilita_participante_cvirtual_p2.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxloading-acceso_cvirtual-p" + id_participante).html("");
                $("#ajaxloading-acceso_cvirtual-p" + id_participante).html(data);
            }
        });
    }
    function elimina_participante_cvirtual_p2(id_participante) {
        $("#ajaxloading-acceso_cvirtual-p" + id_participante).html("");
        $("#ajaxloading-acceso_cvirtual-p" + id_participante).html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.elimina_participante_cvirtual_p2.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#ajaxloading-acceso_cvirtual-p" + id_participante).html("");
                $("#ajaxloading-acceso_cvirtual-p" + id_participante).html(data);
            }
        });
    }
</script>