<?php


require("class.phpmailer.php");
$mail = new PHPMailer();

//Luego tenemos que iniciar la validaci�n por SMTP:
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->Host = "mail.infosicoes.com"; // SMTP a utilizar. Por ej. smtp.elserver.com
$mail->Username = "infosicoes@infosicoes.com"; // Correo completo a utilizar
$mail->Password = "Pw4w3BXpZ$5"; // Contrase�a
$mail->Port = 25; // Puerto a utilizar
//Con estas pocas l�neas iniciamos una conexi�n con el SMTP. Lo que ahora deber�amos hacer, es configurar el mensaje a enviar, el //From, etc.
$mail->From = "infosicoes@infosicoes.com"; // Desde donde enviamos (Para mostrar)
$mail->FromName = "Informaci�n";

//Estas dos l�neas, cumplir�an la funci�n de encabezado (En mail() usado de esta forma: �From: Nombre <correo@dominio.com>�) de //correo.
$mail->AddAddress("brayan.desteco@gmail.com"); // Esta es la direcci�n a donde enviamos
$mail->IsHTML(true); // El correo se env�a como HTML
$mail->Subject = "Estracto del informe anterior"; // Este es el titulo del email.
$body = "<h2>Informaci�n no detallada</h2><p>Hola mundo. Esta es la primer l�nea</p>";
$body .= "Ac� continuo el <strong>mensaje</strong>";
$mail->Body = $body; // Mensaje a enviar
$exito = $mail->Send(); // Env�a el correo.
//Tambi�n podr�amos agregar simples verificaciones para saber si se envi�:
if ($exito) {
    echo 'El correo fue enviado correctamente.';
} else {
    echo "Hubo un inconveniente. Contacta a un administrador.";
}
?>

