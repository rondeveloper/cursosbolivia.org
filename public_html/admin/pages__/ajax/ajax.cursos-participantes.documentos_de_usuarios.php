<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_curso = post('id_curso');
$ids = post('ids') == '' ? '0' : post('ids');


/* curso */
$rqdcc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdcc2 = fetch($rqdcc1);
$nombre_curso = $rqdcc2['titulo'];
?>
<div>
    <h2 class="text-center"><?php echo $nombre_curso; ?></h2>
    <br>
    <div id="AJAXBOX-comprimir_documentos">
        <p class="text-center">Presione el siguiente boton para comprimir los documentos.</p>
        <b class="btn btn-lg btn-info btn-block" onclick="comprimir_documentos('1');"><i class="fa fa-file-zip-o"></i> COMPRIMIR DOCUMENTOS (con deposito)</b>
        <hr>
        <b class="btn btn-lg btn-info btn-block" onclick="comprimir_documentos('2');"><i class="fa fa-file-zip-o"></i> COMPRIMIR DOCUMENTOS (sin deposito)</b>
        <hr>
        <b class="btn btn-lg btn-info btn-block" onclick="comprimir_documentos('3');"><i class="fa fa-file-zip-o"></i> COMPRIMIR FOTOS (solo foto)</b>
    </div>
    <br>
</div>
<hr>
<table class="table table-striped table-bordered">
    <tr>
        <th>USUARIO</th>
        <th>DOCUMENTOS</th>
    </tr>
    <?php
    $arr_busc = array('ci-anverso','ci-reverso','titulo','dep-iplc');
    $arr_remm = array('C.I. anverso','C.I. reverso','TITULO','DEPOSITO');
    //$rqdl1 = query("SELECT p.nombres,p.apellidos,u.email FROM cursos_participantes p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id WHERE p.id_usuario<>'0' AND p.id IN ($ids) ORDER BY p.id DESC ");
    //$rqdl1 = query("SELECT p.nombres,p.apellidos,p.correo FROM cursos_participantes p WHERE p.id IN ($ids) ORDER BY p.id DESC ");
    $rqdl1 = query("SELECT u.id,u.nombres,u.apellidos,(p.id)p_id FROM cursos_participantes p INNER JOIN cursos_usuarios u ON p.id_usuario=u.id INNER JOIN documentos_usuario d ON d.id_usuario=u.id WHERE p.id IN ($ids) GROUP BY u.id ORDER BY p.id DESC ");
    $ids_p_enviados = '0';
    while ($rqdl2 = fetch($rqdl1)) {
        $ids_p_enviados .= ','.$rqdl2['p_id'];
        ?>
        <tr>
            <td>
                <?php echo $rqdl2['nombres'] . ' ' . $rqdl2['apellidos']; ?>
            </td>
            <td>
                <?php 
                $rqddu1 = query("SELECT * FROM documentos_usuario WHERE id_usuario='".$rqdl2['id']."' ");
                while($rqddu2 = fetch($rqddu1)){
                    echo str_replace($arr_busc,$arr_remm,$rqddu2['codigo']).' | <a href="'.$dominio_www.'contenido/imagenes/doc-usuarios/'.$rqddu2['nombre'].'" target="_blank">'.$rqddu2['nombre'].'</a><br><br>';
                }
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<hr>

<b class="label label-danger">PARTICIPANTES QUE NO ENVIARON DOCUMENTOS</b>
<br>
<br>
<table class="table table-striped table-bordered">
    <tr>
        <th>NOMBRE</th>
    </tr>
    <?php
    $rqdl1 = query("SELECT p.nombres,p.apellidos,p.id FROM cursos_participantes p WHERE id_curso='$id_curso' AND id NOT IN ($ids_p_enviados) ");
    while ($rqdl2 = fetch($rqdl1)) {
        ?>
        <tr>
            <td>
                <?php echo $rqdl2['nombres'] . ' ' . $rqdl2['apellidos']; ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>




<script>
    function comprimir_documentos(mod) {
        $("#AJAXBOX-comprimir_documentos").html('PROCESANDO...');
        $.ajax({
            url: 'pages/ap/app.comprimir-docs.php',
            data: {id_curso: '<?php echo $id_curso; ?>', ids: '<?php echo $ids; ?>', mod: mod},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXBOX-comprimir_documentos").html(data);
            }
        });
    }
</script>

