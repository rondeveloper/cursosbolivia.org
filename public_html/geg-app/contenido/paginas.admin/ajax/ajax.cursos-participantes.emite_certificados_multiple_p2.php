<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* acceso */
if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* datos recibidos */
$ids_participantes_dat = post('ids_participantes');
$id_certificado = post('id_certificado');
$id_curso = post('id_curso');
$modo = post('modo');

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

/* get regsitros */
switch ($modo) {
    case '0':
        $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id NOT IN(select id_participante from cursos_rel_partcertadicional where id_certificado='$id_certificado') ORDER BY id DESC ");
        break;
    case '1':
        $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id_emision_certificado='0' ORDER BY id DESC ");
        break;
    case '2':
        $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id_emision_certificado_2='0' ORDER BY id DESC ");
        break;
    case '3':
        $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id_emision_certificado_3='0' ORDER BY id DESC ");
        break;
    default:
        echo "ERROR";
        exit;
        break;
}

/* sw cierre */
query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

/* datos curso */
$rqdcf1 = query("SELECT fecha,id_ciudad FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdcf2 = mysql_fetch_array($rqdcf1);
$fecha_curso = $rqdcf2['fecha'];
$id_ciudad = $rqdcf2['id_ciudad'];

/* datos ciudad */
$rqdcd1 = query("SELECT cod FROM ciudades WHERE id='$id_ciudad' ORDER BY id DESC limit 1 ");
$rqdcd2 = mysql_fetch_array($rqdcd1);
$cod_i_ciudad = $rqdcd2['cod'];

/* formato de certificado */
$rqdfc1 = query("SELECT formato FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
$rqdfc2 = mysql_fetch_array($rqdfc1);
$formato_certificado = $rqdfc2['formato'];
?>
<table class="table table-striped table-bordered">
    <?php
    while ($rqcp2 = mysql_fetch_array($rqcp1)) {

        $receptor_de_certificado = addslashes(trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']));
        $id_participante = $rqcp2['id'];

        $certificado_id = getIDcert($cod_i_ciudad);

        query("INSERT INTO cursos_emisiones_certificados(
           id_certificado, 
           id_curso, 
           id_participante, 
           certificado_id, 
           receptor_de_certificado, 
           id_administrador_emisor, 
           fecha_emision, 
           estado
           ) VALUES (
           '$id_certificado',
           '$id_curso',
           '$id_participante',
           '$certificado_id',
           '$receptor_de_certificado',
           '$id_administrador',
           '$fecha_emision',
           '1'
           )");
        $id_emision_certificado = mysql_insert_id();

        /* actualizacion de participante */
        switch ($modo) {
            case '0':
                query("INSERT INTO cursos_rel_partcertadicional (id_participante,id_certificado,id_emision_certificado) VALUES ('$id_participante','$id_certificado','$id_emision_certificado') ");
                logcursos('Emision de certificado adicional [' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
                if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
                    logcursos('Emision de certificado adicional [fuera de fecha][' . $id_emision_certificado . ']', 'curso-edicion', 'curso', $id_curso);
                }
                break;
            case '1':
                query("UPDATE cursos_participantes SET id_emision_certificado='$id_emision_certificado' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
                logcursos('Emision de 1er certificado [' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
                if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
                    logcursos('Emision de 1er certificado [fuera de fecha][' . $id_emision_certificado . ']', 'curso-edicion', 'curso', $id_curso);
                }
                break;
            case '2':
                query("UPDATE cursos_participantes SET id_emision_certificado_2='$id_emision_certificado' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
                logcursos('Emision de 2do certificado [' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
                if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
                    logcursos('Emision de 2do certificado [fuera de fecha][' . $id_emision_certificado . ']', 'curso-edicion', 'curso', $id_curso);
                }
                break;
            case '3':
                query("UPDATE cursos_participantes SET id_emision_certificado_3='$id_emision_certificado' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
                logcursos('Emision de 3er certificado [' . $id_emision_certificado . ']', 'partipante-certificados', 'participante', $id_participante);
                if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
                    logcursos('Emision de 3er certificado [fuera de fecha][' . $id_emision_certificado . ']', 'curso-edicion', 'curso', $id_curso);
                }
                break;
            default:
                break;
        }
        ?>
        <tr>
            <td>
                <b style='font-size: 15pt !important;padding-bottom: 7pt;'>
                    <?php echo $receptor_de_certificado; ?>
                </b> 
            </td>
            <td>
                <button onclick="window.open('<?php echo $dominio; ?>contenido/librerias/fpdf/tutorial/certificado-<?php echo $formato_certificado; ?>.php?id_certificado=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');" class="btn btn-default btn-xs">
                    IMPRIMIR CERTIFICADO
                </button>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<br/>

<div class="alert alert-success">
  <strong>EXITO!</strong> certificados emitidos correctamente.
</div>
