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

if ($dat != '') {
    ?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th></th>
                <th>Fecha</th>
                <th>Curso</th>
                <th>Estado</th>
            </tr>
            <?php
            $rqdp1 = query("SELECT c.id,c.estado,c.titulo,c.fecha FROM cursos c WHERE estado IN (0,1,2) AND (c.titulo LIKE '%$dat%'  OR c.id LIKE '$dat') AND c.sw_tienda=0 ORDER BY c.fecha DESC, c.id DESC limit 10 ");
            if(num_rows($rqdp1)==0){
                ?>
                <div class='alert alert-info'>
                    <strong>INFO</strong> Sin resultados en la busqueda.
                </div>
                <?php
            }
            while ($rqdp2 = fetch($rqdp1)) {
                ?>
                <tr>
                    <td style="line-height: 2;">
                        <span style="font-size: 11pt;text-transform:uppercase;"><b><?php echo $rqdp2['id']; ?></b></span>
                    </td>
                    <td style="line-height: 2;">
                        <a  <?php echo loadpage('cursos-listar/1/no-search/ID'.$rqdp2['id']); ?> class="btn btn-xs btn-warning">Panel</a>
                    </td>
                    <td style="line-height: 2;">
                        <span style="font-size: 10pt;color: #1080ca;"><?php echo date("d / M / Y",strtotime($rqdp2['fecha'])); ?></span>
                    </td>
                    <td style="line-height: 2;">
                        <span style="font-size: 11pt;text-transform:uppercase;"><?php echo $rqdp2['titulo']; ?></span>
                    </td>
                    <td style="line-height: 2;">
                        <?php 
                        if($rqdp2['estado']=='1'){
                            echo '<b class="label label-success">Activo</b>';
                        }elseif($rqdp2['estado']=='2'){
                            echo '<b class="label label-danger">Temporal</b>';
                        }else{
                            echo '<b class="label label-default">Desactivado</b>';
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
}
