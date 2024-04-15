<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);



if (isset_administrador()) {

    $ids_participantes = post('dat');
    if ($ids_participantes == '') {
        $ids_participantes = '0';
    }

    $id_certificado = post('id_certificado');
    $id_curso = post('id_curso');
    $id_administrador = administrador('id');
    $fecha_emision = date("Y-m-d H:i:s");
    $limit_certificado = 2;
    
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
    
    /* verficacion de id de certificado */
    if( ((int)$id_certificado) <= 0){
        echo "<br/><b>No se habilito un certificado para este curso!</b><br/>";exit;
    }
    
    $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id_emision_certificado_2='0' ORDER BY id DESC ");
    
    /* sw cierre */
    query("UPDATE cursos SET sw_cierre='0' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    /* datos curso */
    $rqdcf1 = query("SELECT fecha,id_ciudad FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqdcf2 = fetch($rqdcf1);
    $fecha_curso = $rqdcf2['fecha'];
    $id_ciudad = $rqdcf2['id_ciudad'];
    
    /* datos ciudad */
    $rqdcd1 = query("SELECT cod FROM ciudades WHERE id='$id_ciudad' ORDER BY id DESC limit 1 ");
    $rqdcd2 = fetch($rqdcd1);
    $cod_i_ciudad = $rqdcd2['cod'];
    ?>
    <div class="row">
        <ul>
            <?php
            while ($rqcp2 = fetch($rqcp1)) {

                $receptor_de_certificado = trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']);
                $id_participante = $rqcp2['id'];

                /* verificacion de emision anterior */
                $rqve1 = query("SELECT id FROM cursos_emisiones_certificados WHERE id_curso='$id_curso' AND id_participante='$id_participante' AND receptor_de_certificado='$receptor_de_certificado'  ORDER BY id DESC limit 1 ");
                if (num_rows($rqve1) >= $limit_certificado) {
                    echo '<div class="alert alert-danger">
        <strong>Error!</strong> receptor de certificado ya existente.
    </div>';
                    exit;
                }

                $certificado_id = getIDcert($cod_i_ciudad);

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
                $id_emision_certificado_2 = insert_id();
                //$certificado_id = "11000" . str_pad($id_emision_certificado_2, 7, '0', STR_PAD_LEFT);
                //query("UPDATE cursos_emisiones_certificados SET certificado_id='$certificado_id' WHERE id='$id_emision_certificado_2' ORDER BY id DESC limit 1 ");

                query("UPDATE cursos_participantes SET id_emision_certificado_2='$id_emision_certificado_2' WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
                
                logcursos('Emision de 2do certificado ['.$id_emision_certificado_2.']', 'partipante-certificados', 'participante', $id_participante);
                
                if (strtotime(date("Y-m-d")) > strtotime($fecha_curso)) {
                    logcursos('Emision de 2do certificado [fuera de fecha][' . $id_emision_certificado_2 . ']', 'curso-edicion', 'curso', $id_curso);
                }
                ?>
                <li style='font-size: 15pt !important;padding-bottom: 7pt;'>
                    <?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?> -> </b> <button onclick="imprimir_certificado_individual('<?php echo $id_emision_certificado_2; ?>');"  class="btn btn-default btn-xs">IMPRIMIR CERTIFICADO</button>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>

    <br/>
    
    <p class='text-center' style='color:green;'><b>CERTIFICADOS EMITIDOS EXITOSAMENTE!</b></p>

    <?php
} else {
    echo "Denegado!";
}

