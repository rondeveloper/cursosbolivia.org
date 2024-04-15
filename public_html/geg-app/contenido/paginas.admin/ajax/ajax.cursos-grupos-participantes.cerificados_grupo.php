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
        <th>Fecha</th>
        <th colspan="2">Certificado</th>
    </tr>
    <?php
    $rqccg1 = query("SELECT id,titulo,fecha,id_certificado FROM cursos WHERE id IN (SELECT id_curso FROM cursos_participantes WHERE nombres LIKE '$nombres_participante' AND apellidos LIKE '$apellidos_participante' AND estado='1' AND id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') )");
    $cnt_certs_validos = 0;
    $cnt_certs_ya_emitidos = 0;
    $ids_participantes_ya_emitidos = '';
    while ($curso = mysql_fetch_array($rqccg1)) {
        $id_curso = $curso['id'];
        /* participante */
        $rqddp1 = query("SELECT id,nombres,apellidos,prefijo FROM cursos_participantes WHERE nombres LIKE '$nombres_participante' AND apellidos LIKE '$apellidos_participante' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
        $rqddp2 = mysql_fetch_array($rqddp1);
        $id_participante = $rqddp2['id'];
        $nom_para_certificado = trim($rqddp2['prefijo'] . ' ' . $rqddp2['nombres'] . ' ' . $rqddp2['apellidos']);
        ?>
        <tr>
            <td>
                <?php echo $curso['titulo']; ?> 
            </td>
            <td>
                <?php echo date("d/M/y", strtotime($curso['fecha'])); ?>
            </td>
            <td>
                <?php
                if ($curso['id_certificado'] == '0') {
                    echo "<b style='color:red;'>Sin certificado</b>";
                } else {
                    /* certificado */
                    $rqdc1 = query("SELECT id,texto_qr FROM cursos_certificados WHERE id='" . $curso['id_certificado'] . "' ORDER BY id DESC limit 1 ");
                    $rqdc2 = mysql_fetch_array($rqdc1);
                    echo $rqdc2['texto_qr'];
                    $id_certificado = $rqdc2['id'];
                    $ids_certpart .= ',' . $id_certificado . '__' . $id_participante;
                }
                ?> 
            </td>
            <td>
                <?php
                if ($curso['id_certificado'] != '0') {
                    /* emision certificado */
                    $rqdc1 = query("SELECT id FROM cursos_emisiones_certificados WHERE id_certificado='" . $curso['id_certificado'] . "' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
                    if (mysql_num_rows($rqdc1) > 0) {
                        echo "<b class='btn btn-xs btn-success active'>Emitido</b>";
                        $cnt_certs_ya_emitidos++;
                        $ids_participantes_ya_emitidos .= ',' . $id_participante;
                    } else {
                        echo "<b class='btn btn-xs btn-default active'>No emitido</b>";
                        $cnt_certs_validos++;
                    }
                } else {
                    echo "<b class='btn btn-xs btn-danger active'>Sin certificado</b>";
                }
                ?> 
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<hr/>

<?php
if ($cnt_certs_validos > 0) {
    ?>
    <table class="table table-bordered table-striped text-center" style="margin: 10px 0px;border: 2px solid #bccdd8;background: #FFF;">
        <tr>
            <td>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td>
                &iquest; Desea emitir los <?php echo $cnt_certs_validos; ?> certificado(s) a este participante ?
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" class="form-control" value="<?php echo $nom_para_certificado; ?>" readonly=""/>
                <input type="hidden" id="nom_participante" value="<?php echo $nom_para_certificado; ?>"/>
            </td>
        </tr>
        <tr>
            <td id="AJAXCONTENT-emitir_certificados">
                <b class="btn btn-success btn-sm" onclick="emitir_certificados();">EMITIR CERTIFICADOS</b>
            </td>
        </tr>
    </table>
    <?php
} else {
    echo "<p>No hay certificados para emitir.</p>";
}

if ($cnt_certs_ya_emitidos > 0) {
    ?>
    <hr/>
    <b class="btn btn-success btn-lg" onclick="imprimir_certificados_grupo('<?php echo trim($ids_participantes_ya_emitidos, ','); ?>');">Imprimir certificados emitidos anteriormente</b>
    <?php
}
?>


<script>
    function emitir_certificados() {
        $("#AJAXCONTENT-emitir_certificados").html('Procesando...');
        var receptor_de_certificado = $("#nom_participante").val();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos-participantes.emitir_certificados.php',
            data: {ids_certpart: '<?php echo trim($ids_certpart, ','); ?>', receptor_de_certificado: receptor_de_certificado},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-emitir_certificados").html(data);
            }
        });
    }
</script>

<!-- ajax imprimir certificados grupo -->
<script>
    function imprimir_certificados_grupo(ids_participantes) {
        window.open('http://cursos.bo/contenido/librerias/fpdf/tutorial/certificado-3-masivo.php?id_participantes=' + ids_participantes, 'popup', 'width=700,height=500');
    }
</script>