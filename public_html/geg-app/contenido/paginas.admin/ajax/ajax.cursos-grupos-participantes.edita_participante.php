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
        <th>Registro</th>
        <th>Curso</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Correo</th>
        <th>Celular</th>
    </tr>
    <?php
    $rqccg1 = query("SELECT id,titulo,fecha,id_certificado FROM cursos WHERE id IN (SELECT id_curso FROM cursos_participantes WHERE nombres LIKE '$nombres_participante' AND apellidos LIKE '$apellidos_participante' AND id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') )");
    $cnt_certs_validos = 0;
    $cnt_certs_ya_emitidos = 0;
    $ids_participantes_ya_emitidos = '';
    $ids_participantes = '';
    $ids_pr_registros = '';
    while ($curso = mysql_fetch_array($rqccg1)) {
        $id_curso = $curso['id'];

        /* participante */
        $rqddp1 = query("SELECT id,nombres,apellidos,prefijo,id_proceso_registro,modo_pago,estado,sw_cvirtual,correo,celular FROM cursos_participantes WHERE nombres LIKE '$nombres_participante' AND apellidos LIKE '$apellidos_participante' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
        $participante = mysql_fetch_array($rqddp1);
        $id_participante = $participante['id'];
        $id_proceso_registro_participante = $participante['id_proceso_registro'];
        $estado_participante = $participante['estado'];
        $modo_pago_participante = $participante['modo_pago'];
        $nom_para_certificado = trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']);
        /* registro */
        $rqdpr1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro_participante' ORDER BY id DESC limit 1 ");
        $proc_registro = mysql_fetch_array($rqdpr1);

        $ids_participantes .= ',' . $id_participante;
        $ids_pr_registros .= ',' . $proc_registro['id'];
        ?>
        <tr>
            <td>
                <?php echo $proc_registro['codigo']; ?> 
            </td>
            <td>
                <?php echo $curso['titulo']; ?> 
            </td>
            <td>
                <?php echo $participante['nombres']; ?> 
            </td>
            <td>
                <?php echo $participante['apellidos']; ?> 
            </td>
            <td>
                <?php echo $participante['correo']; ?> 
            </td>
            <td>
                <?php echo $participante['celular']; ?> 
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel panel-heading">
                FORMULARIO DE EDICI&Oacute;N
            </div>
            <div class="panel panel-body">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="nombres">Nombres del participante:</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo $participante['nombres']; ?>" required="">
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Apellidos del participante:</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $participante['apellidos']; ?>" required="">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo del participante:</label>
                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $participante['correo']; ?>" required="">
                    </div>
                    <div class="form-group">
                        <label for="celular">Celular del participante:</label>
                        <input type="number" class="form-control" id="celular" name="celular" value="<?php echo $participante['celular']; ?>">
                    </div>
                    <br>
                    <input type="hidden" name="id_grupo" value="<?php echo $id_grupo; ?>">
                    <input type="hidden" name="ids_participantes" value="<?php echo trim($ids_participantes, ','); ?>">
                    <input type="hidden" name="ids_pr_registros" value="<?php echo trim($ids_pr_registros, ','); ?>">
                    <input type="submit" class="btn btn-success active" name="editar-participante" value="ACTUALIZAR DATOS">
                </form>
            </div>
        </div>
    </div>
</div>


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