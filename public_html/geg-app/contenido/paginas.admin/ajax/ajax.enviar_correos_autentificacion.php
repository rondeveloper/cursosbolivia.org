<?php

session_start();

include_once '../../configuracion/config.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset($_SESSION['login_esf_captcha']) && isset($_POST['pagina'])) {

    $vista = $_POST['pagina'];
    
    if($_GET['empresa'] == 'consultor'){$operador_uno = '=';}else{$operador_uno = '<>';}

    $resultado_empresas = mysql_query("SELECT correo_empresa,clave FROM empresas WHERE nombre_empresa $operador_uno 'consultor' AND estado='0' ORDER BY id DESC LIMIT " . (($vista - 1) * 20) . ",20")or die(mysql_error());
    while($empresa = mysql_fetch_array($resultado_empresas)){
        $correo_empresa = $empresa['correo_empresa'];
        $clave = $empresa['clave'];
        
        $cabeceras = 'From: InfoSICOES@infosicoes.com' . "\r\n" .
                'Reply-To: ' . "\r\n" .
                'X-Mailer: PHP/' . phpversion() .
                'Return-Path: ' . $correo_empresa . "\r\n" .
                'MIME-Version: 1.0' . "\r\n" .
                'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
        $contenido_email = "<br/><h2>InfoSICOES te da la Bienvenida!</h2>";
        $contenido_email .= "<p>InfoSICOES es un portal web Boliviano que difunde contrataciones (consultor&iacute;as, licitaciones) estatales y/o privadas de Bolivia, permite que los usuarios registrados puedan recibir en su correo electr&oacute;nico informaci&oacute;n diaria, esperamos que disfrutes de tu experiencia con nuestra Aplicaci&oacute;n.</p>";
        $contenido_email .= "<br/><p>Para activar la cuenta de tu Empresa haz <a href='http://infosicoes.com/bienvenida-empresa/" . $clave . ".html' target='_blank'>click aqui (ACTIVAR MI CUENTA)</a></p><br/><br/><br/>";
        $contenido_email .= "<a href='http://www.infosicoes.com' target='_blank'>Visita Nuestra Pagina</a>";
        $contenido_email .= "<a href='http://www.infosicoes.com' target='_blank'><div style='width:50%;margin:auto;'><img style='width:100%;border:1px solid gray;border-radius:5px;padding:1px;' title='Visita Nuestra Pagina WEB' src='http://www.infosicoes.com/contenido/imagenes/images/logo_infosicoes.png' alt='INFOSICOES'/></div></a>";
        mail($correo_empresa, 'Bienvenido a INFOSICOES', $contenido_email, $cabeceras);
    }
    
    echo "Envios realizados Correctamente! <img src='contenido/imagenes/images/bien.png' style='width:25px;'>";
} else {
    echo "Denegado!";
}
?>
