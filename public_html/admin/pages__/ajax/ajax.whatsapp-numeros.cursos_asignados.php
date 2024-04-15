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
$id_whats_numero = post('id_numero');
$id_curso = post('id_curso');

/* numero whatsapp */
$rqwn1 = query("SELECT * FROM whatsapp_numeros WHERE id='$id_whats_numero' LIMIT 1 ");
$rqwn2 = fetch($rqwn1);
?>
<table class="table table-bordered table-striped">
    <tr>
        <td><b>N&uacute;mero:</b> <?php echo $rqwn2['numero']; ?></td>
        <td><b>Responsable:</b> <?php echo $rqwn2['responsable']; ?></td>
    </tr>
</table>

<?php
if($id_curso!='0'){
    $rqvep1 = query("SELECT id FROM cursos_rel_cursowapnum WHERE id_curso='$id_curso' AND id_whats_numero='$id_whats_numero' ");
    if(num_rows($rqvep1)==0){
        query("INSERT INTO cursos_rel_cursowapnum(id_curso, id_whats_numero) VALUES ('$id_curso','$id_whats_numero')");
        logcursos('Asignacion de numero de whatsapp', 'curso-edicion', 'curso', $id_curso);
        echo '<div class="alert alert-success">
  <strong>EXITO</strong> el registro se agrego correctamente.
</div>';
    }
}

/* quitar numero */
if(isset_post('id_curso_quitar')){
    $id_curso_quitar = post('id_curso_quitar');
    query("DELETE FROM cursos_rel_cursowapnum WHERE id_whats_numero='$id_whats_numero' AND id_curso='$id_curso_quitar' ORDER BY id DESC limit 1 ");
    logcursos('Eliminacion de numero de whatsapp', 'curso-edicion', 'curso', $id_curso_quitar);
    echo '<div class="alert alert-info">
  <strong>AVISO</strong> el registro se elimino correctamente.
</div>';
}

?>

<div class="row">
    <div class="col-md-5">
        <div class="panel panel-warning">
            <div class="panel-heading">
                ASIGNADOS
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <?php
                    $rqdc1 = query("SELECT id,titulo,fecha,(select nombre from ciudades where id=cursos.id_ciudad)ciudad FROM cursos WHERE estado IN (0,1,2) AND id IN (select id_curso from cursos_rel_cursowapnum where id_whats_numero='$id_whats_numero') ORDER BY id_ciudad ASC, fecha ASC limit 1000 ");
                    $cur_ciudad= '';
                    while ($rqdc2 = fetch($rqdc1)) {
                        if($rqdc2['ciudad'] != $cur_ciudad){
                            $cur_ciudad = $rqdc2['ciudad'];
                            echo '<tr><th colspan="3" class="text-center">'.$cur_ciudad.'</th></tr>';
                        }
                        ?>
                        <tr>
                            <td><?php echo date("d/M/Y", strtotime($rqdc2['fecha'])); ?></td>
                            <td><?php echo getIdentCurso($rqdc2['titulo']); ?></td>
                            <td><b class="btn btn-xs btn-danger" onclick="quitar_numero_whatsapp('<?php echo $id_whats_numero; ?>','<?php echo $rqdc2['id']; ?>');">X</b></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="panel panel-info">
            <div class="panel-heading">
                OTROS
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped table-hover">
                    <?php
                    $rqdc1 = query("SELECT id,titulo,fecha,(select nombre from ciudades where id=cursos.id_ciudad)ciudad FROM cursos WHERE estado IN (1,2) AND id NOT IN (select id_curso from cursos_rel_cursowapnum where id_whats_numero='$id_whats_numero') ORDER BY id_ciudad ASC, fecha ASC limit 1000 ");
                    //$rqdc1 = query("SELECT id,titulo,fecha,(select nombre from ciudades where id=cursos.id_ciudad)ciudad FROM cursos WHERE titulo LIKE '%quech%' AND id NOT IN (select id_curso from cursos_rel_cursowapnum where id_whats_numero='$id_whats_numero') ORDER BY id_ciudad ASC, fecha ASC limit 100 ");
                    $cur_ciudad= '';
                    while ($rqdc2 = fetch($rqdc1)) {
                        if($rqdc2['ciudad'] != $cur_ciudad){
                            $cur_ciudad = $rqdc2['ciudad'];
                            echo '<tr><th colspan="3" class="text-center">'.$cur_ciudad.'</th></tr>';
                        }
                        ?>
                        <tr>
                            <td><?php echo date("d/M/Y", strtotime($rqdc2['fecha'])); ?></td>
                            <td><?php echo getIdentCurso($rqdc2['titulo']); ?></td>
                            <td><b class="btn btn-xs btn-success" onclick="cursos_asignados('<?php echo $id_whats_numero; ?>','<?php echo $rqdc2['id']; ?>');">ASIGNAR</b></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- AJAX quitar_numero_whatsapp -->
<script>
    function quitar_numero_whatsapp(id_whats_numero_quitar,id_curso_quitar) {
        $("#AJAXCONTENT-cursos_asignados").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.whatsapp-numeros.cursos_asignados.php',
            data: {id_curso: 0, id_curso_quitar: id_curso_quitar, id_numero: id_whats_numero_quitar},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-cursos_asignados").html(data);
            }
        });
    }
</script>

<?php
function getIdentCurso($dat) {
    $r1 = explode(' en ', $dat);
    return trim(str_replace('Curso ', '', $r1[0]));
}
