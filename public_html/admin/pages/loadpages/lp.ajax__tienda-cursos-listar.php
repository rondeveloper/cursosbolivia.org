<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


$resultado1 = query("SELECT 
COUNT(cp.id) AS total, c.id, c.titulo, c.costo 
FROM cursos AS c 
LEFT JOIN cursos_participantes AS cp ON c.id = cp.id_curso 
WHERE c.sw_tienda = 1  
GROUP BY c.id 
ORDER BY c.id DESC ");

?>

<div class="hidden-lg">
    <?php
    include_once '../items/item.enlaces_top.mobile.php';
    ?>
</div>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once '../items/item.enlaces_top.php';
            ?>
        </ul>
     
        <h3 class="page-header"> CURSOS DE LA TIENDA <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Listado de cursos mostrados en la tienda
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>CURSO</th>
                                <th>PARTICIPANTES</th>
                                <th>PRECIO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;
                            while ($cursos = fetch($resultado1)) {
                                $id_enlace = $cursos['id'];
                                ?>
                                <tr>
                                    <td><?php echo $cnt++; ?>
                                        <b title="Historial De Curso" class="btn btn-default btn-xs" onclick="historial_curso('<?php echo $cursos['id']; ?>');">
                                            <i class="fa fa-list" style="color:#8f8f8f;"></i></b>
                                    </td>
                                    <td>
                                        <b style="font-size: 15pt;color:#3b69a2;"><?php echo $cursos['id']; ?></b>
                                    </td>
                                    <td>
                                        <b style="font-size: 14pt;color:#73b123;"><?php echo $cursos['titulo']; ?></b>
                                        <br>
                                        Curso virtual:
                                        <br>
                                        <div style="background: white;border: 1px solid #cdcdcd;padding: 5px;">
                                            <?= showListadoCursosVirtualesAsignados($cursos['id']) ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <b style="font-size: 14pt;color:#3b69a2;"><?php echo $cursos['total']; ?></b>
                                    </td>
                                    <td>
                                        <b style="font-size: 14pt;color:#73b123;"><?php echo $cursos['costo']; ?> BS</b>
                                    </td>
                                    <td>                               
                                        <a <?= loadpage('tienda-participantes-listar/'.$cursos["id"])?> title="VER PARTICIPANTES" class="btn btn-default btn-sm btn-block" style="color: #0089b5;">
                                            <i class="fa fa-group"></i> Participantes
                                        </a>
                                        <br>
                                        <a href="cursos-editar/<?= $cursos["id"] ?>.adm" title="EDITAR" class="btn btn-default btn-sm btn-block" style="color: #0089b5;">
                                            <i class="fa fa-edit"></i> Editar
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>

    </div>
</div>

<?php
function showListadoCursosVirtualesAsignados($id_curso) {
    $data_return  = "";

    $rqd1 = query("SELECT 
    cv.id,cv.titulo  
    FROM cursos_rel_cursoonlinecourse r 
    INNER JOIN cursos_onlinecourse cv ON r.id_onlinecourse=cv.id 
    WHERE r.id_curso='$id_curso' ");
    while($rqd2 = fetch($rqd1)){
        $data_return .= "CV ".$rqd2['id']." : ".$rqd2['titulo']."<br>";
    }

    return $data_return;
}
?>

<!-- historial_curso -->
<script>
    function historial_curso(id_curso) {
        $("#TITLE-modgeneral").html('LOG DE MOVIMIENTOS');
        $("#AJAXCONTENT-modgeneral").html('Cargando...');
        $("#MODAL-modgeneral").modal('show');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-listar.historial_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    }
</script>
