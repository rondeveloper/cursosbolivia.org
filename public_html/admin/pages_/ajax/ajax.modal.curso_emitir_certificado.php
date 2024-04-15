<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


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
    $rqdfc2 = fetch($rqdfc1);
    $formato_certificado = $rqdfc2['formato'];
    

    /* verificacion de emision anterior */
    $rqve1 = query("SELECT id FROM cursos_emisiones_certificados WHERE id_curso='$id_curso' AND id_participante='$id_participante' AND receptor_de_certificado='$receptor_de_certificado'  ORDER BY id DESC limit 1 ");
    if(num_rows($rqve1)>=$limit_certificado){
        echo '<div class="alert alert-danger">
        <strong>Error!</strong> receptor de certificado ya existente.
    </div>';
        exit;
    }

    $certificado_id = "11000";
    
    /* data de certificado */
    $rqddcpe1 = query("SELECT c.cont_titulo,c.cont_uno,c.cont_dos,c.cont_tres,c.texto_qr,c.fecha_qr,c.fecha2_qr,c.id_fondo_digital,c.id_fondo_fisico,c.sw_solo_nombre FROM cursos_certificados c WHERE id='$id_certificado' ORDER BY id DESC limit 1 ");
    $rqddcpe2 = fetch($rqddcpe1);
    $dc_cont_titulo = $rqddcpe2['cont_titulo'];
    $dc_cont_uno = $rqddcpe2['cont_uno'];
    $dc_cont_dos = $rqddcpe2['cont_dos'];
    $dc_cont_tres = $rqddcpe2['cont_tres'];
    $dc_texto_qr = $rqddcpe2['texto_qr'];
    $dc_fecha_qr = $rqddcpe2['fecha_qr'];
    $dc_fecha2_qr = $rqddcpe2['fecha2_qr'];
    $dc_id_fondo_digital = $rqddcpe2['id_fondo_digital'];
    $dc_id_fondo_fisico = $rqddcpe2['id_fondo_fisico'];
    $dc_sw_solo_nombre = $rqddcpe2['sw_solo_nombre'];

    query("INSERT INTO cursos_emisiones_certificados(
           id_certificado, 
           id_curso, 
           id_participante, 
           certificado_id, 
           receptor_de_certificado, 
           cont_titulo, 
           cont_uno, 
           cont_dos, 
           cont_tres, 
           texto_qr, 
           fecha_qr, 
           fecha2_qr, 
           id_fondo_digital, 
           id_fondo_fisico, 
           sw_solo_nombre, 
           id_administrador_emisor, 
           fecha_emision, 
           estado
           ) VALUES (
           '$id_certificado',
           '$id_curso',
           '$id_participante',
           '$certificado_id',
           '$receptor_de_certificado',
           '$dc_cont_titulo',
           '$dc_cont_uno',
           '$dc_cont_dos',
           '$dc_cont_tres',
           '$dc_texto_qr',
           '$dc_fecha_qr',
           '$dc_fecha2_qr',
           '$dc_id_fondo_digital',
           '$dc_id_fondo_fisico',
           '$dc_sw_solo_nombre',
           '$id_administrador',
           '$fecha_emision',
           '1'
           )");
    $rqcr1 = query("SELECT id FROM cursos_emisiones_certificados WHERE id_curso='$id_curso' AND id_participante='$id_participante' AND receptor_de_certificado='$receptor_de_certificado' ORDER BY id DESC limit 1 ");
    $rqcr2 = fetch($rqcr1);
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
                <b>Visualizaci&oacute;n / Impresi&oacute;n -> </b> <button onclick="window.open('http://www.infosicoes.com/contenido/paginas/procesos/pdfs/certificado-<?php echo $formato_certificado; ?>.php?id_certificado=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');" class="btn btn-default btn-xs">VISUALIZAR CERTIFICADO</button>
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
