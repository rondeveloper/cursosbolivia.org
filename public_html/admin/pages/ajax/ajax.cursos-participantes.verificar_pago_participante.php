<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_participante = post('id_participante');
$id_administrador = administrador('id');

$rqcr1 = query("SELECT id_curso FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$rqcr2 = fetch($rqcr1);
$id_curso = $rqcr2['id_curso'];

if (isset_post('sw_verificar')) {
    $rqverif1 = query("SELECT id FROM rel_pagosverificados WHERE id_participante='$id_participante' ORDER BY id DESC limit 1 ");
    if(num_rows($rqverif1)>0){
        echo '<div class="alert alert-danger">
        <strong>AVISO</strong> otro administrador ya ha verificado este pago.
      </div>';
    }else{
        /* registro */
        query("INSERT INTO rel_pagosverificados (id_participante,id_administrador) VALUES ('$id_participante','$id_administrador') ");
        logcursos('Verificacion de reporte de pago', 'participante-edicion', 'participante', $id_participante);
    ?>
        <div style="background: #26c526;padding: 5px;margin-bottom: 15px;color: white;"><i class="fa fa-check"></i> VERIFICADO</div>
    <?php
    }
} else {
?>
    <div class="text-center" style="padding: 15px 0px;background: white;border-radius: 5px;border: 1px solid #dadada;">
        <b>&iquest; Este comprobante es valido ?</b>
        <br>
        <b class="btn btn-md btn-success" onclick="verificar_pago_participante_p2('<?php echo $id_participante; ?>');">SI</b> &nbsp; <b class="btn btn-md btn-default" onclick="pago_no_verificado('<?php echo $id_participante; ?>');">NO</b>
    </div>

    <!-- verificar_pago_participante_p2 -->
    <script>
        function verificar_pago_participante_p2(id_participante) {
            $("#ajaxcont-verifpago-" + id_participante).html('<h4>Procesando...</h4>');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.verificar_pago_participante.php',
                data: {
                    id_participante: id_participante,
                    sw_verificar: 1
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#ajaxcont-verifpago-" + id_participante).html(data);
                    lista_participantes('<?php echo $id_curso; ?>', 0);
                }
            });
        }
    </script>
    <script>
        function pago_no_verificado(id_participante) {
            const conttext = '<div style="background: #a0a0a0;padding: 5px;margin-bottom: 15px;color: white;cursor:pointer;" onclick="verificar_pago_participante('+id_participante+');"> NO VERIFICADO</div>';
            $("#ajaxcont-verifpago-"+id_participante).html(conttext);
        }
    </script>

<?php
}
?>