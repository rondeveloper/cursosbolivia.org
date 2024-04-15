<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
include_once '../../librerias/correo/class.phpmailer.php';

$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador() && post('dat')) {

    $id_empresa = post('dat');
    
    $remp1 = query("SELECT * FROM empresas WHERE id='$id_empresa' ");
    $remp2 = fetch($remp1);
    
    $nomempresa = $remp2['nombre_empresa'];
    $correo_empresa = $remp2['correo_empresa'];
    $nombre_administrador = administrador('nombre');
    
    $rpl1 = query("SELECT contenido FROM paginas WHERE id='29' LIMIT 1 ");
    $rpl2 = fetch($rpl1);
    $contenido_planes_infosicoes = str_replace('<li>','<li style="list-style:none;"><img src="https://www.infosiscon.com/contenido/imagenes/images/icon-11.png"/> ',$rpl2['contenido']);
    
    
    $mensaje_correo = "<div style='background:#c8c8c8;width:100%;padding:0px;margin:0px;padding-top:30px;padding-bottom:30px;'>
    <div style='background:#FFF;width:80%;margin:auto;padding:30px;border:1px solid #56829c;border-radius:5px;color:#333;'><h2 style='text-align:center;background:#003e54;color:#FFF;border-radius:5px;padding:5px;'>Nuestros paquetes - PLANES INFOSICOES</h2>
        <center><a href='https://www.infosiscon.com/'><img width='230px' src='https://www.infosiscon.com/contenido/imagenes/images/logo_infosicoes.png'/></a></center>
        <p style='font-style:italic;font-family:arial;font-size:10.5pt;line-height:2;'>
            Saludos -NOM-EMPRESA-
            <br/>
            A continuación te mostramos las características de nuestros paquetes, actualizados hasta la fecha actual:
        </p>
        <p>
           <br/>
           -PLANES-INFOSICOES-
           <br/>
        </p>
        <p style='font-style:italic;font-family:arial;font-size:10.5pt;line-height:2;'>
        Nuestra aplicaci&oacute;n le permite estar al tanto de las nuevas licitaciones que sean de su inter&eacute;s y poder monitorearlas constantemente, se te enviaran correos con avisos de las nuevas contrataciones 
        publicadas en el SICOES, que son referentes a los servicios que ofrece tu empresa y as&iacute; no perder la oportunidad de postular a esa posible contrataci&oacute;n de tu Empresa.<br/>Nuestra Misi&oacute;n es ayudarle con las adjudicaciones de sus futuras licitaciones, pronto podra recibir 
        notificaciones de empresas privadas que requieren sus servicios, trabajar con una entidad financiera que ayude con la emisi&oacute;n de Boletas de seriedad de propuestas o Boletas de garant&iacute;a de forma r&aacute;pida y c&oacute;moda, pronto sacaremos nuevas herramientas que te 
        ayudaran de mayor manera en la adjudicaci&oacute;n de una licitaci&oacute;n o una compra menor, ya que tenemos gran aceptaci&oacute;n de muchas entidades de gobierno.
        <br/>
        <br/>
        Esperamos que nuestra aplicaci&oacute;n te sea de utilidad.<br/><br/>Gracias por tu atención.<br/></p>
        <hr/>
        <br/>
        <div style='margin:auto;width:80%;font-size:9.5pt;color:#333;line-height:2;'>
            <i>-ADMINISTRADOR-</i>
            <br/>
            info@nemabol.com
            <br/>
            Calle Loayza #250 Edif. Castilla
            Piso 4 Oficina 410 - 79517817
            <br/>
            (591-2) 2118783 L&iacute;nea InfoSICOES
        </div>
    </div>
</div>";

    $asunto = "Nuestros paquetes - Planes InfoSICOES";
    $cabeceras = 'From:' . 'InfoSICOES@infosicoes.com' . "\r\n" .
            'Reply-To:' . 'info@nemabol.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion() .
            'Return-Path:' . 'InfoSICOES@infosicoes.com' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    $busc = array('-PLANES-INFOSICOES-', '-NOM-EMPRESA-', '-ADMINISTRADOR-');
    $remm = array($contenido_planes_infosicoes, $nomempresa, $nombre_administrador);
    $msj = str_replace($busc, $remm, $mensaje_correo);

    //if (mail($correo_empresa, $asunto, $msj, $cabeceras)) {
    if (envio_email($correo_empresa, $asunto, $msj)) {
        movimiento('Envio de datos [nick/usuario] [contraseña]', 'envio-datos', 'usuario', $id_empresa);
        echo " <img src='".$dominio_www."contenido/imagenes/images/bien.png' style='width:25px;'>  Datos enviados!";
    } else {
        echo "Error";
    }
} else {
    echo "Denegado!";
}
?>
