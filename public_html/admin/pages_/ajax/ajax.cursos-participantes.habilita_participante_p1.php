<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* datos recibidos */
$id_participante = post('id_participante');
$apartar = (int) post('apartar');

/* query principal */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");


/* datos */
$participante = fetch($resultado1);


/* datos de registro */
$rqrp1 = query("SELECT codigo,fecha_registro,celular_contacto,correo_contacto,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
$data_registro = fetch($rqrp1);
$codigo_de_registro = $data_registro['codigo'];
$fecha_de_registro = $data_registro['fecha_registro'];
$celular_de_registro = $data_registro['celular_contacto'];
$correo_de_registro = $data_registro['correo_contacto'];
$nro_participantes_de_registro = $data_registro['cnt_participantes'];
$id_emision_factura = $data_registro['id_emision_factura'];

$monto_de_pago = $data_registro['monto_deposito'];
$imagen_de_deposito = $data_registro['imagen_deposito'];

$razon_social_de_registro = $data_registro['razon_social'];
$nit_de_registro = $data_registro['nit'];

/* id_curso */
$id_curso = $participante['id_curso'];

/* turnos */
$rqtc1 = query("SELECT id,titulo FROM cursos_turnos WHERE id_curso='$id_curso' ORDER BY id DESC limit 1 ");
$sw_turnos = false;
if (num_rows($rqtc1) > 0) {
    $sw_turnos = true;
}
?>

<div class="row">
    <div class="col-md-12 text-left text-center" style="line-height: 0.2;">
        <h3 class="text-center" style="font-size: 20pt;
            text-transform: uppercase;
            color: #00789f;font-weight: bold;">
            <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </h3>
        <b style="font-size: 17pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
    </div>
</div>
<hr/>
<div class="text-center">

    <!-- DIV CONTENT AJAX :: HABILITA PARTICIPANTE P2 -->
    <div id="ajaxloading-habilita_participante_p2"></div>
    <div id="ajaxbox-habilita_participante_p2">
        <?php
        if ($apartar == 1) {
            ?>
            <b class="btn btn-success active" onclick="habilita_participante_p2(<?php echo $participante['id']; ?>, 1);" >QUITAR DE LISTA DE APARTADOS</b>
            <?php
        } else {
            ?>
            <b class="btn btn-success active" onclick="habilita_participante_p2(<?php echo $participante['id']; ?>, 0);" >HABILITAR PARTICIPANTE</b>
            <?php
        }
        ?>
        <hr/>
    </div>

</div>

