<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


/* acceso */
if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* datos recibidos */
$ids_participantes_dat = post('ids_participantes');
$id_certificado = post('id_certificado');
$id_administrador = administrador('id');
$fecha_emision = date("Y-m-d H:i:s");

/* existencia post de id participante */
$ar_exp_aux = explode(",", $ids_participantes_dat);
$ids_participantes = '0';
foreach ($ar_exp_aux as $value) {
    if(isset_post('idpart-'.$value)){
        $ids_participantes .= "," . (int) $value;
    }
}
/* END existencia post de id participante */

/* verficacion de id de certificado */
if (((int) $id_certificado) <= 0) {
    echo "<br/><b>No se habilito un certificado para este curso!</b><br/>";
    exit;
}

/* certificado */
$rqdfc1 = query("SELECT * FROM certificados_culminacion WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
$rqdfc2 = fetch($rqdfc1);
$formato_certificado = $rqdfc2['formato'];
$id_curso = $rqdfc2['id_curso'];

/* get regsitros */
$rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id NOT IN(select id_participante from certificados_culminacion_emisiones where id_certificado_culminacion='$id_certificado') ORDER BY id DESC ");
?>
<table class="table table-striped table-bordered">
    <?php
    while ($rqcp2 = fetch($rqcp1)) {
        $id_participante = $rqcp2['id'];
        $nota = 97;
        $rqvrfe1 = query("SELECT id FROM certificados_culminacion_emisiones WHERE id_certificado_culminacion='$id_certificado' AND id_participante='$id_participante' ");
        if (num_rows($rqvrfe1) == 0) {
            query("INSERT INTO certificados_culminacion_emisiones(id_certificado_culminacion,id_participante,nota,fecha_emision,estado) VALUES ('$id_certificado','$id_participante','$nota',NOW(),'1')");
            $id_emision = insert_id();
            $hash = md5(md5($id_emision . 'cce5616'));
            logcursos('Emision de certificado de culminacion', 'participante-edicion', 'participante', $id_participante);
            ?>
            <tr>
                <td>
                    <b style='font-size: 15pt !important;padding-bottom: 7pt;'>
                        <?php echo $rqcp2['nombres'].' '.$rqcp2['apellidos']; ?>
                    </b> 
                </td>
                <td>
                    <button onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/certificado-culminacion-ipelc-digital.php?id_emision=<?php echo $id_emision; ?>&hash=<?php echo $hash; ?>', 'popup', 'width=700,height=500');" class="btn btn-default btn-xs">
                        VISUALIZAR CERTIFICADO
                    </button>
                </td>
            </tr>
            <?php
        }
    }
    ?>
</table>

<br/>

<div class="alert alert-success">
  <strong>EXITO!</strong> certificados emitidos correctamente.
</div>
