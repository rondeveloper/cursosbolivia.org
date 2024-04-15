<?php

$mensaje = "<div style='background:#c8c8c8;width:100%;padding:0px;margin:0px;padding-top:30px;padding-bottom:30px;'>
    <div style='background:#FFF;width:80%;margin:auto;padding:30px;border:1px solid #56829c;border-radius:5px;color:#333;'><h2 style='text-align:center;background:#003e54;color:#FFF;border-radius:5px;padding:5px;'>InfoSICOES Datos de Ingreso</h2>
        <center><a href='http://www.infosicoes.com/'><img width='230px' src='http://www.infosicoes.com/contenido/imagenes/images/logo_infosicoes.png'/></a></center>
        <p style='font-style:italic;font-family:arial;font-size:10.5pt;line-height:2;'>
            Saludos -NOM-EMPRESA-
            <br/>
            Te informamos que para un mejor uso de los servicios que ofrece InfoSICOES es necesario que configures tu cuenta correctamente, para ello 
            debes ingresar a la siguiente URL cuando gustes y completar tus datos de usuario como tambien tu perfil de Empresa en InfoSICOES.
        </p>
        <div style='text-align:center;'><a style='background:green;color:#FFF;padding:10px;border-radius:5px;' href='http://www.infosicoes.com/ingreso-de-usuarios.html'>Ingresar a mi Cuenta en InfoSICOES</a></div>        
        <br/>
        <br/>
        <br/>
        <table>
        <tr>
        <td style='padding:5px;'><b>Nick de Usuario:</b></td>
        <td style='padding:5px;'>-NICK-</td>
        </tr>
        <tr>
        <td style='padding:5px;'><b>Contrase&ntilde;a:</b></td>
        <td style='padding:5px;'>-PASSWORD-</td>
        </tr>
        </table>
        <br/>
        <br/>
        Esperamos que nuestra aplicaci&oacute;n te sea de utilidad.<br/><br/>Gracias por tu Atencion.<br/></p>
        <hr/>
        <br/>
        <div style='margin:auto;width:80%;font-size:9.5pt;color:#333;line-height:1;'>
            <b>Comun&iacute;quese con nosotros:</b>
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

$caracteristicas = '<ul style="list-style: none;">
            <li><img src="http://www.infosicoes.com/contenido/imagenes/images/icon-11.png"/> Monitoreo constante de todas las licitaciones públicas en el SICOES</li>
            <li><img src="http://www.infosicoes.com/contenido/imagenes/images/icon-11.png"/> Envió hasta 10 licitaciones por dia</li>
            <li><img src="http://www.infosicoes.com/contenido/imagenes/images/icon-11.png"/> Envios durante 30 dias</li>
            <li><img src="http://www.infosicoes.com/contenido/imagenes/images/icon-11.png"/> 3 palabras clave</li>
        </ul>';

$asunto = "Datos de Ingreso - InfoSICOES";
$cabeceras = 'From:' . 'InfoSICOES@infosicoes.com' . "\r\n" .
        'Reply-To:' . 'info@nemabol.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion() .
        'Return-Path:' . 'InfoSICOES@infosicoes.com' . "\r\n" .
        'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=iso-8859-1' . "\r\n";

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset($_SESSION['login_esf_captcha']) && isset($_POST['id_empresa'])) {
    $id_empresa = post('id_empresa');

    $rr1 = mysql_query("SELECT * FROM empresas WHERE id='$id_empresa' LIMIT 1") or die(mysql_error());
    $rr2 = mysql_fetch_array($rr1);
    $nomempresa = $rr2['nombre_empresa'];
    $nick = $rr2['nick'];
    $password = $rr2['password'];
    
    if($nomempresa == 'consultor'){
        $nomempresa = $rr2['nombre_representante']." ".$rr2['ap_paterno_representante']." ".$rr2['ap_materno_representante'];
    }
    $correo_empresa = $rr2['correo_empresa'];
    
    $busc = array( '-NICK-', '-PASSWORD-', '-NOM-EMPRESA-');
    $remm = array( $nick, $password, $nomempresa);
    
    $msj = str_replace($busc, $remm, $mensaje);
    mail($correo_empresa, $asunto, $msj, $cabeceras);
    //mail('infosicoes@gmail.com', $asunto, $msj, $cabeceras);
    //mail('info@nemabol.com', $asunto, $msj, $cabeceras);

    echo "<img src='contenido/imagenes/images/bien.png' style='width:25px;'>  Datos de Ingreso enviados!";
} else {
    echo "Denegado!";
}
?>
