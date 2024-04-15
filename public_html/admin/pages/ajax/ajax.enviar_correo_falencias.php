<?php

$mensaje = "<div style='background:#c8c8c8;width:100%;padding:0px;margin:0px;padding-top:30px;padding-bottom:30px;'>
    <div style='background:#FFF;width:80%;margin:auto;padding:30px;border:1px solid #56829c;border-radius:5px;color:#333;'><h2 style='text-align:center;background:#003e54;color:#FFF;border-radius:5px;padding:5px;'>InfoSICOES Falencias en palaras Clave</h2>
        <center><a href='http://www.infosicoes.com/'><img width='230px' src='http://www.infosicoes.com/contenido/imagenes/images/logo_infosicoes.png'/></a></center>
        <p style='font-style:italic;font-family:arial;font-size:10.5pt;line-height:2;'>
            Saludos -NOM-EMPRESA-
            <br/>
            Le informamos que para un mejor uso de los servicios que ofrece InfoSICOES es necesario que configures tu cuenta correctamente, 
            Hemos detectado ciertas ciertas falencias en sus palabras clave:
        </p>
        -PALABRAS-CLAVE-
        <p style='font-style:italic;font-family:arial;font-size:10.5pt;line-height:2;'>
            (Al parecer no son las palabras clave adecuadas para los productos/servicios <b>especificos</b> que ofrece su empresa)
            <br/>
            Le sugerimos que ingrese a su panel de control y pueda modificar sus palabras CLAVE aconsejamos no poner tildes(construcción, lo correcto: contruccion), No poner palabras en plural(construcciones, lo correcto: contruccion), también puede abreviar palabras como ser: const en lugar de construcción. 
            <br/>Aconsejamos tambien No poner las palabras: venta,ventas,consultor,servicio,servicios,ingenieria,empresa.
            <br/><b>Es importante que almenos tenga una palabra clave, el aviso de licitaciones que realizaremos a su correo, dependera de que las palabras clave de su empresa sean las adecuadas</b>
        </p>
        <p style='font-style:italic;font-family:arial;font-size:10.5pt;line-height:2;'>
            Puedes ingresar a tu cuenta en la siguiente direccion URL:
        </p>
        <div style='text-align:center;'><a style='background:green;color:#FFF;padding:10px;border-radius:5px;' href='http://www.infosicoes.com/ingreso-de-usuarios.html'>http://www.infosicoes.com/ingreso-de-usuarios.html</a></div>        
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
        <p>Esperamos que nuestra aplicaci&oacute;n te sea de utilidad.<br/><br/>Gracias por tu Atencion.</p>
        <br/>
        <br/>
        InfoSICOES
        <br/>
        Dpto. de Sistemas
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
            <li><img src="http://www.infosicoes.com/contenido/imagenes/images/icon-11.png"/> Envío hasta 10 licitaciones por dia</li>
            <li><img src="http://www.infosicoes.com/contenido/imagenes/images/icon-11.png"/> Envios durante 30 dias</li>
            <li><img src="http://www.infosicoes.com/contenido/imagenes/images/icon-11.png"/> 3 palabras clave</li>
        </ul>';

$asunto = "Hemos detectado FALENCIAS en sus palabras clave - InfoSICOES";
$cabeceras = 'From:' . 'InfoSICOES@infosicoes.com' . "\r\n" .
        'Reply-To:' . 'info@nemabol.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion() .
        'Return-Path:' . 'InfoSICOES@infosicoes.com' . "\r\n" .
        'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=iso-8859-1' . "\r\n";

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset($_SESSION['login_esf_captcha']) && isset($_POST['id_empresa'])) {
    $id_empresa = post('id_empresa');

    $rr1 = query("SELECT * FROM empresas WHERE id='$id_empresa' LIMIT 1") or die(mysqli_error($mysqli));
    $rr2 = fetch($rr1);
    $nomempresa = $rr2['nombre_empresa'];
    $nick = $rr2['nick'];
    $password = $rr2['password'];
    
    $arr1 = explode(',',$rr2['palabras_clave']);
    $palabras_clave = '';
    foreach ($arr1 as $palabra_clave){
        if($palabra_clave !==''){
        $palabras_clave .= "<b> - $palabra_clave</b><br/>";
        }
    }
    
    
    if($nomempresa == 'consultor'){
        $nomempresa = $rr2['nombre_representante']." ".$rr2['ap_paterno_representante']." ".$rr2['ap_materno_representante'];
    }
    $correo_empresa = $rr2['correo_empresa'];
    
    $busc = array( '-NICK-', '-PASSWORD-', '-NOM-EMPRESA-','-PALABRAS-CLAVE-');
    $remm = array( $nick, $password, $nomempresa,$palabras_clave);
    
    $msj = str_replace($busc, $remm, $mensaje);
    mail($correo_empresa, $asunto, $msj, $cabeceras);
    //mail('infosicoes@gmail.com', $asunto, $msj, $cabeceras);
    //mail('info@nemabol.com', $asunto, $msj, $cabeceras);

    echo "<img src='".$dominio_www."contenido/imagenes/images/bien.png' style='width:25px;'>  Correo falencias enviado!";
} else {
    echo "Denegado!";
}
?>
