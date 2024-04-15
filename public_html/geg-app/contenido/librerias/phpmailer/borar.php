<?php


require("class.phpmailer.php");
$mail = new PHPMailer();

//Luego tenemos que iniciar la validación por SMTP:
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Host = "mail.infosicoes.com"; // SMTP a utilizar. Por ej. smtp.elserver.com
$mail->Username = "infosicoes@infosicoes.com"; // Correo completo a utilizar
$mail->Password = "Pw4w3BXpZ$5"; // Contraseña
$mail->Port = 25; // Puerto a utilizar
//Con estas pocas líneas iniciamos una conexión con el SMTP. Lo que ahora deberíamos hacer, es configurar el mensaje a enviar, el //From, etc.
$mail->From = "infosicoes@infosicoes.com"; // Desde donde enviamos (Para mostrar)
$mail->FromName = "Información";

//Estas dos líneas, cumplirían la función de encabezado (En mail() usado de esta forma: “From: Nombre <correo@dominio.com>”) de //correo.
$mail->AddAddress("brayan.desteco@gmail.com"); // Esta es la dirección a donde enviamos
$mail->IsHTML(true); // El correo se envía como HTML
$mail->Subject = "Estracto del informe anterior"; // Este es el titulo del email.
$body = "<h2>Información no detallada</h2><p>Hola mundo. Esta es la primer línea</p>";
$body .= "Acá continuo el <strong>mensaje</strong>";
$mail->Body = $body; // Mensaje a enviar
$exito = $mail->Send(); // Envía el correo.
//También podríamos agregar simples verificaciones para saber si se envió:
if ($exito) {
    echo 'El correo fue enviado correctamente.';
} else {
    echo "Hubo un inconveniente. Contacta a un administrador.";
}
?>

