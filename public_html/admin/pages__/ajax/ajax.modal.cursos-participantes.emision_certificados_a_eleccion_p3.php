<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);



if (isset_administrador()) {

    $id_modelo_certificado = post('id_modelo_certificado');
    
    $ids_participantes = post('dat');
    if ($ids_participantes == '') {
        $ids_participantes = '0';
    }

    $id_curso = post('id_curso');
    $id_administrador = administrador('id');
    $fecha_emision = date("Y-m-d H:i:s");
    
    $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id NOT IN (select id_participante from rel_participante_modelocertificado where id_modelo_certificado='$id_modelo_certificado' ) ORDER BY id DESC ");
    ?>
    <div class="row">
        <ul>
            <?php
            $ids_participantes_env = "0";
            while ($rqcp2 = fetch($rqcp1)) {

                $receptor_de_certificado = trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']);
                $id_participante = $rqcp2['id'];

                $certificado_id = "11000";

                query("INSERT INTO cursos_emisiones_certificados(
           id_modelo_certificado, 
           id_curso, 
           id_participante, 
           md,
           certificado_id, 
           receptor_de_certificado, 
           id_administrador_emisor, 
           fecha_emision, 
           estado
           ) VALUES (
           '$id_modelo_certificado',
           '$id_curso',
           '$id_participante',
           '3',
           '$certificado_id',
           '$receptor_de_certificado',
           '$id_administrador',
           '$fecha_emision',
           '1'
           )");
                $rqcr1 = query("SELECT id FROM cursos_emisiones_certificados WHERE id_curso='$id_curso' AND id_participante='$id_participante' AND receptor_de_certificado='$receptor_de_certificado' ORDER BY id DESC limit 1 ");
                $rqcr2 = fetch($rqcr1);
                $id_emision_certificado = $rqcr2['id'];
                $certificado_id = "11000" . str_pad($id_emision_certificado, 7, '0', STR_PAD_LEFT);
                query("UPDATE cursos_emisiones_certificados SET certificado_id='$certificado_id' WHERE id='$id_emision_certificado' ORDER BY id DESC limit 1 ");

                query("INSERT INTO rel_participante_modelocertificado(
                       id_curso, 
                       id_participante, 
                       id_modelo_certificado,
                       id_emision_certificado
                       ) VALUES (
                       '$id_curso',
                       '$id_participante',
                       '$id_modelo_certificado',
                       '$id_emision_certificado'
                       )");
                
                $ids_participantes_env .= ",".$rqcp2['id'];
                ?>
                <li style='font-size: 15pt !important;padding-bottom: 7pt;'>
                    <?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?> -> </b> <button onclick="window.open('http://www.infosicoes.com/contenido/paginas/procesos/pdfs/certificado-3.php?id_certificado=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');" class="btn btn-default btn-xs">IMPRIMIR CERTIFICADO</button>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>

    <br/>
    
    <p class='text-center' style='color:green;'><b>CERTIFICADOS EMITIDOS EXITOSAMENTE!</b></p>
    
    <br/>

    <button class="btn btn-success" onclick="window.open('http://www.infosicoes.com/contenido/paginas/procesos/pdfs/certificado-3-masivo.php?id_participantes=<?php echo $ids_participantes_env; ?>&id_modelo_certificado=<?php echo $id_modelo_certificado; ?>', 'popup', 'width=700,height=500');">IMPRIMIR EMITIDOS</button>
    

    <?php
} else {
    echo "Denegado!";
}
?>
