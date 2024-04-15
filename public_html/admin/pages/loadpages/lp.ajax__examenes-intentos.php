<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

/* verificacion de sesion */
if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}
/* manejo de parametros */
$data = 'nonedata/' . post('data');
$get = explode('/', $data);
if ($get[count($get) - 1] == '') {
    array_splice($get, (count($get) - 1), 1);
}
/* parametros post */
$postdata = post('postdata');
if ($postdata !== '') {
    $_POST = json_decode(base64_decode($postdata), true);
}
?>

<!-- CONTENIDO DE PAGINA -->

<?php
$id_examen = $get[2];
$resultado1 = query("SELECT i.*,p.nombres,p.apellidos FROM cursos_examenes_generales_intentos i INNER JOIN cursos_participantes p ON p.id=i.id_participante WHERE i.id_examen='$id_examen' ");
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once '../items/item.enlaces_top.php';
            ?>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <b class="btn btn-success" onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/certificado-camara-senadores.php?aprobados=true', 'popup', 'width=700,height=500');">
                IMPRIMIR TODOS LOS APROBADOS
            </b>
        </div>
        <h3 class="page-header"> Intentos del examen <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">
                <table class="table table-hover table-responsive table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="visible-lg" style="font-size:10pt;">#</th>
                            <th class="visible-lg" style="font-size:10pt;">Participante</th>
                            <th class="visible-lg" style="font-size:10pt;">Nota</th>
                            <th class="visible-lg" style="font-size:10pt;">Certificado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 1;
                        while ($producto = fetch($resultado1)) {
                            ?>
                            <tr>
                                <td class="visible-lg"><?php echo $cnt++; ?></td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['nombres'].' '.$producto['apellidos'];
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo round($producto['total_correctas']/$producto['total_preguntas']*100);
                                    ?>
                                </td>
                                <td class="visible-lg" style="width:120px;">
                                    <a onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/certificado-camara-senadores.php?id_intento=<?php echo $producto['id']; ?>', 'popup', 'width=700,height=500');" style="cursor:pointer;" class="btn btn-default"><i class='fa fa-file-pdf-o'></i> Visualizar certificado</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
