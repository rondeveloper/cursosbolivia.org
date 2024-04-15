<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$dat = str_replace(array(' ','.',',','-'),'%',trim(post('dat')));
$modcourse = post('modcourse');

$qr_modalidad_curso = " c.id_modalidad IN (1) ";
if($modcourse=='virtual'){
    $qr_modalidad_curso = " c.id_modalidad IN (2,3) ";
}

if(strpos('---'.$dat,'R00')>0){
    $qr_modalidad_curso = " 1 ";
}

$qr_modalidad_curso = " 1 ";

if ($dat != '') {
    ?>
    <!-- envio_todos_los_certificados -->
    <script>
        function buscar_participante_procesos(id_participante) {
            $("#busc-part-proc-"+id_participante).html('Cargando...');
            $.ajax({
                    url: '<?php echo $dominio_procesamiento; ?>admin/process.cursos-listar.buscar_participante_procesos.php',
                    data: {id_participante: id_participante},
                    type: 'POST',
                    dataType: 'html',
                    success: function (data) {
                        $("#busc-part-proc-"+id_participante).html(data);
                    }
            });
        }
    </script>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>Participante</th>
                <th></th>
                <th></th>
                <th>Curso</th>
                <!-- <th>Procesos</th> -->
            </tr>
            <?php
            $rqdp1 = query("SELECT p.id,p.nombres,p.apellidos,c.titulo,c.id,(p.id)dr_id_participante,r.fecha_registro,p.estado FROM cursos_participantes p INNER JOIN cursos_proceso_registro r ON p.id_proceso_registro=r.id INNER JOIN cursos c ON c.id=p.id_curso WHERE $qr_modalidad_curso AND ( CONCAT(p.nombres,' ',p.apellidos) LIKE '%$dat%' OR p.correo LIKE '%$dat%' OR p.ci='$dat' OR p.celular='$dat' OR r.codigo LIKE '%$dat' ) ORDER BY p.id DESC limit 10 ");
            while ($rqdp2 = fetch($rqdp1)) {
                $rqccrt1 = query("SELECT COUNT(*) AS total FROM cursos_emisiones_certificados WHERE id_participante='".$rqdp2['id']."' ORDER BY id DESC LIMIT 1 ");
                $rqccrt2 = fetch($rqccrt1);
                ?>
                <tr>
                <td style="line-height: 2;">
                        <span style="font-size: 11pt;text-transform:uppercase;"><?php echo $rqdp2['nombres'].' '.$rqdp2['apellidos']; ?></span>
                    </td>
                    <td style="line-height: 2;">
                        <a href="cursos-participantes/<?php echo $rqdp2['id']; ?>/no-turn/<?php echo $rqdp2['dr_id_participante']; ?>.adm" class="btn btn-xs btn-primary">Panel</a>
                    </td>
                    <td style="line-height: 2;">
                        <b><?php echo date("M / Y",strtotime($rqdp2['fecha_registro'])); ?></b> &nbsp;
                        <?php 
                        if($rqdp2['estado']=='1'){
                            echo '<b class="label label-success">Habilitado</b> &nbsp;';
                        }else{
                            echo '<b class="label label-danger">Eliminado</b> &nbsp;';
                        }
                        if($rqccrt2['total']=='0'){
                            echo '<b class="label label-default">Sin certificado</b>';
                        }else{
                            echo '<b class="label label-warning">Con certificado</b>';
                        }
                        ?>
                    </td>
                    <td style="line-height: 2;">
                        <span style="font-size: 10pt;color: #1080ca;"><?php echo $rqdp2['titulo']; ?></span>
                    </td>
                    <!-- <td id="busc-part-proc-<?php echo $rqdp2['dr_id_participante']; ?>">
                        <script>buscar_participante_procesos('<?php echo $rqdp2['dr_id_participante']; ?>');</script>
                    </td> -->
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
}
