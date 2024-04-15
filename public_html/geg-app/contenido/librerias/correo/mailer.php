<?php

function envio_email($correo, $subject, $body){

	require 'class.phpmailer.php';
	try {
		$mail = new PHPMailer(true); //Nueva instancia, con las excepciones habilitadas
		
		$mail->IsSMTP();                           // Usamos el metodo SMTP de la clase PHPMailer
		$mail->SMTPAuth   = true;                  // habilitado SMTP autentificación
		$mail->SMTPSecure = ""; 
		$mail->Timeout   = 30;                  // habilitado SMTP autentificación
		
		$mail->Port       = 25;                // puerto del server SMTP
		$mail->Host       = "mail.infosicoes.com"; 	// SMTP server
		$mail->Username   = "infosicoes@infosicoes.com";     // SMTP server Usuario
		$mail->Password   = "Pw4w3BXpZ$5";            // SMTP server password
		$mail->From       = "infosicoes@infosicoes.com"; //Remitente de Correo
		$mail->FromName   = "InfoSICOES"; //Nombre del remitente
		$to = $correo; //Para quien se le va enviar
		$mail->AddAddress($to);
		$mail->Subject  = $subject; //Asunto del correo
		$mail->MsgHTML($body);
		$mail->IsHTML(true); // Enviar como HTML
		$mail->Send();//Enviar
		// echo 'El Mensaje a sido enviado.';
	} catch (phpmailerException $e) {
		echo $e->errorMessage();//Mensaje de error si se produciera.
	}
}
?>

