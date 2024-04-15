<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador()) {

    $receptor_de_certificado = post('receptor_de_certificado');
    $id_certificado = post('id_certificado');
    $id_curso = post('id_curso');
    $id_participante = post('id_participante');
    $id_administrador = administrador('id');
    $fecha_emision = date("Y-m-d H:i:s");
    
    if(!isset_get('segundo_certificado')){
        $limit_certificado = 1;
    }else{
        $limit_certificado = 2;
    }
    
    /* formato de certificado */
    $rqdfc1 = query("SELECT formato FROM cursos_certificados WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
    $rqdfc2 = mysql_fetch_array($rqdfc1);
    $formato_certificado = $rqdfc2['formato'];
    

    /* verificacion de emision anterior */
    $rqve1 = query("SELECT id FROM cursos_emisiones_certificados WHERE id_curso='$id_curso' AND id_participante='$id_participante' AND receptor_de_certificado='$receptor_de_certificado'  ORDER BY id DESC limit 1 ");
    if(mysql_num_rows($rqve1)>=$limit_certificado){
        echo '<div class="alert alert-danger">
        <strong>Error!</strong> receptor de certificado ya existente.
    </div>';
        exit;
    }

    $certificado_id = "11000";

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
    $rqcr1 = query("SELECT id FROM cursos_emisiones_certificados WHERE id_curso='$id_curso' AND id_participante='$id_participante' AND receptor_de_certificado='$receptor_de_certificado' ORDER BY id DESC limit 1 ");
    $rqcr2 = mysql_fetch_array($rqcr1);
    $id_emision_certificado = $rqcr2['id'];
    $certificado_id = "11000" . str_pad($id_emision_certificado, 7, '0', STR_PAD_LEFT);
    query("UPDATE cursos_emisiones_certificados SET certificado_id='$certificado_id' WHERE id='$id_emision_certificado' ORDER BY id DESC limit 1 ");
    
    if(!isset_get('segundo_certificado')){
        query("UPDATE cursos_participantes SET id_emision_certificado='$id_emision_certificado' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    }else{
        query("UPDATE cursos_participantes SET id_emision_certificado_2='$id_emision_certificado' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    }
    
    ?>
    <div class="alert alert-success">
        <strong>Exito!</strong> Certificado emitido exitosamente.
    </div>

    <table class="table table-striped">
        <tr>
            <td>ID de certificado: </td>
            <td><?php echo $certificado_id; ?></td>
        </tr>
        <tr>
            <td>Emitido a: </td>
            <td><?php echo $receptor_de_certificado; ?></td>
        </tr>
        <tr>
            <td>Fecha de emision: </td>
            <td><?php echo $fecha_emision; ?></td>
        </tr>
        <tr>
            <td colspan='2'>
                <br/>
                <br/>
                <b>Visualizaci&oacute;n / Impresi&oacute;n -> </b> <button onclick="window.open('http://www.infosicoes.com/contenido/librerias/fpdf/tutorial/certificado-<?php echo $formato_certificado; ?>.php?id_certificado=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');" class="btn btn-default btn-xs">VISUALIZAR CERTIFICADO</button>
                <br/>
                <br/>
            </td>
        </tr>
    </table>
    <?php
} else {
    echo "Denegado!";
}
?>
