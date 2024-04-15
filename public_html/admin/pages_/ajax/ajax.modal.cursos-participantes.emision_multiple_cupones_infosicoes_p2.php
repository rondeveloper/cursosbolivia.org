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
    /* limpia datos de id participante */
    $ar_exp_aux = explode(",", $ids_participantes);
    $ids_participantes = '0';
    foreach ($ar_exp_aux as $value) {
        $ids_participantes .= "," . (int) $value;
    }

    $id_curso = post('id_curso');
    $id_administrador = administrador('id');
    $fecha_emision = date("Y-m-d H:i:s");

    /* verficacion de cupon */
    $rqdcd1 = query("SELECT * FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' LIMIT 1 ");
    if (num_rows($rqdcd1) == 0) {
        echo "<br/><b>No se habilito cupones para este curso!</b><br/>";
        exit;
    }
    /* cupon */
    $rqdcd2 = fetch($rqdcd1);
    $id_cupon = $rqdcd2['id'];
    $id_paquete = $rqdcd2['id_paquete'];
    $duracion = $rqdcd2['duracion'];
    $fecha_expiracion = $rqdcd2['fecha_expiracion'];
 
    $rqcp1 = query("SELECT * FROM cursos_participantes WHERE estado='1' AND id_curso='$id_curso' AND id IN ($ids_participantes) AND sw_pago='1' ORDER BY id DESC ");
    $cnt_participantes = num_rows($rqcp1);
    
    /* dosificacion de cupones */
    $cupones = obtiene_cupones($id_paquete,$duracion,$fecha_expiracion,$cnt_participantes);
        
    "M8R3K36K8,Y23UAO53L,completo";
    $array_cupones = explode(',', str_replace(',completo', '', $cupones));
    
    ?>
    <div class="row">
        <ul>
            <?php
            while ($rqcp2 = fetch($rqcp1)) {

                $receptor_de_certificado = addslashes(trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']));
                $id_participante = $rqcp2['id'];

                /* verificacion de emision anterior */
                $rqve1 = query("SELECT id FROM cursos_emisiones_cupones_infosicoes WHERE id_cupon='$id_cupon' AND id_curso='$id_curso' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
                if (num_rows($rqve1) > 0) {
                    continue;
                }

                $codigo = array_pop($array_cupones);

                query("INSERT INTO cursos_emisiones_cupones_infosicoes(
           id_cupon, 
           id_curso, 
           id_participante, 
           codigo, 
           id_administrador, 
           fecha_registro, 
           estado
           ) VALUES (
           '$id_cupon',
           '$id_curso',
           '$id_participante',
           '$codigo',
           '$id_administrador',
           '$fecha_emision',
           '1'
           )");
                ?>
                <li style='font-size: 15pt !important;padding-bottom: 7pt;'>
                    <?php echo trim($rqcp2['prefijo'] . ' ' . $rqcp2['nombres'] . ' ' . $rqcp2['apellidos']); ?> -> </b> <button onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/cupon-infosicoes.php?id_cupon=<?php echo $id_cupon; ?>&id_participante=<?php echo $id_participante; ?>', 'popup', 'width=700,height=500');" class="btn btn-default btn-xs">IMPRIMIR CUP&Oacute;N</button>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>

    <br/>

    <p class='text-center' style='color:green;'><b>CUPONES EMITIDOS EXITOSAMENTE!</b></p>
    
    <button onclick="window.open('<?php echo $dominio; ?>contenido/paginas/procesos/pdfs/cupon-infosicoes.php?id_cupon=<?php echo $id_cupon; ?>&id_curso=<?php echo $id_curso; ?>', 'popup', 'width=700,height=500');" class="btn btn-info active btn-block">IMPRIMIR CUP&Oacute;NES</button>

    <?php
} else {
    echo "Denegado!";
}


function obtiene_cupones($id_paquete,$duracion,$fecha_expiracion,$cnt_participantes){
    $cont = file_get_contents("https://www.infosicoes.com/contenido/paginas/procesos/externos/webservice.cursosbo.cupones.php?id_paquete=$id_paquete&duracion=$duracion&fecha_expiracion=$fecha_expiracion&cnt_participantes=$cnt_participantes");
    return $cont;
}

