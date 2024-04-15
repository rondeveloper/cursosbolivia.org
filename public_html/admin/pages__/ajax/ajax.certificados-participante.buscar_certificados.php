<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$ci_participante = trim(post('ci_participante'));

/* participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE ci='$ci_participante' ORDER BY id DESC limit 1 ");
if(num_rows($rqdp1)==0){
    echo '<div class="alert alert-danger">
    No se encontro ningun participante con CI: '.$ci_participante.'
</div>';
}else{
    $rqdp2 = fetch($rqdp1);
    $nombre_participante = $rqdp2['nombres'].' '.$rqdp2['apellidos'];
    ?>
    <hr>
    <h3>PARTICIPANTE: <?= $nombre_participante ?></h3>
    <hr>
    <table class="table table-bordered table-striped">
    <?php
    /* cursos */
    $rqc1 = query("SELECT c.titulo,c.id,(p.id)dr_id_participante FROM cursos_participantes p INNER JOIN cursos c ON p.id_curso=c.id WHERE p.ci='$ci_participante' GROUP BY c.id ORDER BY c.fecha DESC ");
    while($rqc2 = fetch($rqc1)){
        $nombre_curso = $rqc2['titulo'];
        $id_curso = $rqc2['id'];
        $id_participante = $rqc2['dr_id_participante'];

        $rqvpca1 = query("SELECT e.certificado_id,e.texto_qr,e.fecha_qr,r.id_emision_certificado FROM cursos_rel_partcertadicional r INNER JOIN cursos_emisiones_certificados e ON e.id=r.id_emision_certificado WHERE r.id_participante='$id_participante' AND r.id_certificado IN (SELECT id_certificado FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso') ");
        if(num_rows($rqvpca1)>0){
        ?>
        <tr>
            <th style="font-size: 14pt;">CURSO <?= $id_curso ?>: <?= $nombre_curso ?></th>
        </tr>
        <tr>
            <td>
                <table class="table table-bordered" style="font-size: 12pt;">
                <?php
                while ($rqvpca2 = fetch($rqvpca1)) {
                    $id_emision_certificado = $rqvpca2['id_emision_certificado'];
                    $ids_emisiones_adicionales .= ',' . $id_emision_certificado;
                    ?>
                    <tr>
                        <td><input id="idcert-<?= $id_emision_certificado ?>" type="checkbox" checked style="width: 25px;height: 25px;"></td>
                        <td><b><?= $rqvpca2['certificado_id'] ?></b></td>
                        <td><?= $rqvpca2['texto_qr'] ?></td>
                        <td><i>Fecha: <b><?= $rqvpca2['fecha_qr'] ?></b></i></td>
                        <td><b class="btn btn-sm btn-success" onclick="imprimir_certificado_individual('<?php echo $id_emision_certificado; ?>');">VISUALIZAR PDF IMPRESION</b></td>
                    </tr>
                    <?php
                }
                ?>
                </table>
            </td>
        </tr>
        <?php
        }
    }
    ?>
    </table>

    <hr>

    <b class="btn btn-lg btn-success" onclick="imprimir_certificados_adicionales('<?php echo trim($ids_emisiones_adicionales, ','); ?>');">VISUALIZAR TODOS LOS CERTIFICADOS</b>

<?php
}
?>


<!-- ajax imprimir_certificado_individual -->
<script>
    function imprimir_certificado_individual(dat) {
        if(confirm('DESEA VISUALIZAR EL CERTIFICADO ?')){
            $("#TITLE-modgeneral").html('CERTIFICADO');
            $("#AJAXCONTENT-modgeneral").html('Cargando...');
            $("#MODAL-modgeneral").modal('show');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.pago_impresion_certificado.php',
                data: {
                    ids_emisiones: dat,
                    modimp: 'imp-fisico'
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
            });
        }
    }
</script>

<!-- ajax imprimir_certificados_adicionales -->
<script>
    function imprimir_certificados_adicionales(ids_emisiones) {
        if(confirm('DESEA VISUALIZAR LOS CERTIFICADOS ?')){
            let array = ids_emisiones.split(',');
            let ids = '0';
            array.forEach(id => {
                if(document.getElementById('idcert-'+id).checked){
                    ids += ','+id;
                }
            });
            $("#TITLE-modgeneral").html('CERTIFICADOS');
            $("#AJAXCONTENT-modgeneral").html('Cargando...');
            $("#MODAL-modgeneral").modal('show');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.pago_impresion_certificado.php',
                data: {
                    ids_emisiones: ids,
                    modimp: 'todos-imp-fisico'
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
            });
        }
    }
</script>