<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

if (isset_administrador()) {

    $id_modelo_certificado = post('id_modelo_certificado');
    $rqmc1 = query("SELECT descripcion,codigo FROM cursos_modelos_certificados WHERE id='$id_modelo_certificado' LIMIT 1 ");
    $rqmc2 = fetch($rqmc1);

    $ids_participantes = post('dat');
    if ($ids_participantes == '') {
        $ids_participantes = '0';
    }
    $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id IN ($ids_participantes) AND id IN (select id_participante from rel_participante_modelocertificado where id_modelo_certificado='$id_modelo_certificado' ) ORDER BY id DESC ");
    
    ?>

    <h4><?php echo $rqmc2['descripcion']; ?></h4>
    <p>Modelo de certificado: <b><?php echo $rqmc2['codigo']; ?></b></p>
    <hr/>

    <div class="row">
        <ul>
            <?php
            while ($rqcp2 = fetch($rqcp1)) {
                
                $rqic1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id=(select id_emision_certificado from rel_participante_modelocertificado where id_participante='".$rqcp2['id']."' AND id_modelo_certificado='".$id_modelo_certificado."' ) ORDER BY id DESC limit 1 ");
                $rqic2 = fetch($rqic1);
                
                $certificado_id = $rqic2['certificado_id'];
                
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
    <p class='text-center'><b>&iquest; Desea imprimir estos certificados ?</b></p>

    <button class="btn btn-success" onclick="window.open('http://www.infosicoes.com/contenido/paginas/procesos/pdfs/certificado-3-masivo.php?id_participantes=<?php echo $ids_participantes; ?>&id_modelo_certificado=<?php echo $id_modelo_certificado; ?>', 'popup', 'width=700,height=500');">IMPRIMIR TODOS</button>
    
    <input type="hidden" id="id-modelo-certificado" value="<?php echo $id_modelo_certificado; ?>"/>

    <?php
} else {
    echo "Denegado!";
}
?>
