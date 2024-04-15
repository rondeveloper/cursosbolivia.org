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
        <th>C&oacute;digo</th>
        <th>Fecha registro</th>
        <th>Habilitaci&oacute;n</th>
        <th>Monto</th>
        <th>Modo pago</th>
        <th>Respaldo</th>
        <th>Administrador</th>
    </tr>
    <?php
    $rqccg1 = query("SELECT id,titulo,fecha,id_certificado FROM cursos WHERE id IN (SELECT id_curso FROM cursos_participantes WHERE nombres LIKE '$nombres_participante' AND apellidos LIKE '$apellidos_participante' AND id_curso IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') )");
    $cnt_certs_validos = 0;
    $cnt_certs_ya_emitidos = 0;
    $ids_participantes_ya_emitidos = '';
    while ($curso = mysql_fetch_array($rqccg1)) {
        $id_curso = $curso['id'];
        /* participante */
        $rqddp1 = query("SELECT id,nombres,apellidos,prefijo,id_proceso_registro,modo_pago,estado FROM cursos_participantes WHERE nombres LIKE '$nombres_participante' AND apellidos LIKE '$apellidos_participante' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
        $rqddp2 = mysql_fetch_array($rqddp1);
        $id_participante = $rqddp2['id'];
        $id_proceso_registro_participante = $rqddp2['id_proceso_registro'];
        $estado_participante = $rqddp2['estado'];
        $modo_pago_participante = $rqddp2['modo_pago'];
        $nom_para_certificado = trim($rqddp2['prefijo'] . ' ' . $rqddp2['nombres'] . ' ' . $rqddp2['apellidos']);
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
                <?php echo $proc_registro['codigo']; ?>
            </td>
            <td>
                <?php echo date("d/M/y H:i", strtotime($proc_registro['fecha_registro'])); ?>
            </td>
            <td>
                <label class="switch">
                    <?php
                    $check = '';
                    if($estado_participante=='1'){
                        $check = ' checked="" ';
                    }
                    ?>
                    <input type="checkbox" <?php echo $check; ?> onclick="cambia_estado_participante('<?php echo $id_participante; ?>');">
                    <div class="slider round"></div>
                </label>
            </td>
            <td>
                <?php echo (int) $proc_registro['monto_deposito']; ?>
            </td>
            <td>
                <?php echo $modo_pago_participante; ?>
            </td>
            <td>
                <?php
                if ($proc_registro['imagen_deposito'] !== '') {
                    ?>
                    <b class="btn btn-success btn-xs" onclick='window.open("https://cursos.bo/depositos/<?php echo $proc_registro['imagen_deposito']; ?>.size=6.img", "ventana1", "width=800,height=800,scrollbars=NO");'>VER IMAGEN</b>
                    <?php
                } elseif ($proc_registro['id_cobro_khipu'] !== '0') {
                    $rqdck1 = query("SELECT payment_id,estado FROM khipu_cobros WHERE id='".$proc_registro['id_cobro_khipu']."' ");
                    $rqdck2 = mysql_fetch_array($rqdck1);
                    $url_cobro = 'https://khipu.com/payment/info/'.$rqdck2['payment_id'];
                    if($rqdck2['estado']=='1'){
                        echo "<b style='color:green;'>PAGO VERIFICADO</b><br>";
                        echo "<a href='$url_cobro' target='_blank'>$url_cobro</a>";
                    }else{
                        echo "<b style='color:gray;'>PAGO NO VERIFICADO</b><br>";
                        echo "<a href='$url_cobro' target='_blank'>$url_cobro</a>";
                    }
                } else {
                    echo 'Sin respaldo';
                }
                ?>
            </td>
            <td>
                <?php echo $nombre_administrador; ?>
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
    <br/>
    <hr/>
    <b class="btn btn-default btn-xs" onclick="imprimir_certificados_grupo('<?php echo trim($ids_participantes_ya_emitidos, ','); ?>');">Imprimir certificados emitidos anteriormente</b>
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


<!-- cambia_estado_participante -->
<script>
    function cambia_estado_participante(id_participante) {
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos-participantes.cambia_estado_participante.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                console.log(data);
            }
        });
    }
</script>
