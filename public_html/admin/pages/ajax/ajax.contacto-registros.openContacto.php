<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_tienda_registro = post('id_tienda_registro');

?>
    <div class="container">  
        <a data-toggle="modal" data-target="#MODAL-edicion-participante" onclick="edita_participante_p1(61557, '17');" class="btn btn-xs btn-info active"> <i class="fa fa-edit"></i></a>
        &nbsp;&nbsp; 
        <b class="btn btn-xs btn-default" onclick="copyToClipboard('c75817179')">C</b> 
        &nbsp;&nbsp; 
        <a class="btn btn-xs btn-default" onclick="cvirtual_send_mailto_accesos(61557)"><i class="fa fa-envelope"></i></a>
        <br>

        <span id="clic_action-msj_hola-61557" onclick="clic_action_wap(61557,'msj_hola');"><img src="http://localhost/Legacy/cursos.bo/public_html/contenido/imagenes/wapicons/wap-init-0.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"> Simple Hola</span>
        <br>
        <span id="clic_action-msj_accesos-61557" onclick="clic_action_wap(61557,'msj_accesos');"><img src="http://localhost/Legacy/cursos.bo/public_html/contenido/imagenes/wapicons/wap-llave-0.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"> Envio Accesos</span>
        <br>
        <span id="clic_action-msj_verif-61557" onclick="clic_action_wap(61557,'msj_verif');"><img src="http://localhost/Legacy/cursos.bo/public_html/contenido/imagenes/wapicons/wap-money-0.jpg" style="height: 25px;border-radius: 20%;curor:pointer;"> Verificar pago</span>
        <br>
        <span id="clic_action-msj_verif-61557" onclick="clic_action_wap(61557,'msj_verif');" class="btn btn-xs btn-default"><i class="fa fa-envelope"></i> Solicitar pago</span>
    </div>  

