<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
include_once '../../librerias/correo/class.phpmailer.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador() && isset_post('dat')) {

    $id_empresa = post('dat');

    $rqv1 = query("SELECT codigo_descuento FROM vent_paq_lista_uno WHERE id_empresa='$id_empresa' AND resultado='venta' ORDER BY id DESC limit 1 ");
    if (num_rows($rqv1) > 0) {

        //codigo descuento
        $rqv2 = fetch($rqv1);
        $codigo_descuento = $rqv2['codigo_descuento'];

        //generacion de correo
        $remp1 = query("SELECT * FROM empresas WHERE id='$id_empresa' ");
        $remp2 = fetch($remp1);

        if ($remp2['clase'] == 'empresa') {
            $nomempresa = $remp2['nombre_empresa'];
        } else {
            $nomempresa = trim($remp2['nombre_representante'] . ' ' . $remp2['ap_paterno_representante'] . ' ' . $remp2['ap_materno_representante']);
        }
        $correo_empresa = $remp2['correo_empresa'];
        $nombre_administrador = administrador('nombre');

        //fecha de corte
        $fechadecorte = date("d/m/Y", strtotime($remp2['paquete_inicio']));

        //numero whatsapp
        $numero_empresa = $remp2['tel_cel_representante'];

        $rpl1 = query("SELECT contenido FROM paginas WHERE id='29' LIMIT 1 ");
        $rpl2 = fetch($rpl1);
        $contenido_planes_infosicoes = str_replace('<li>', '<li style="list-style:none;"><img src="https://www.infosiscon.com/contenido/imagenes/images/icon-11.png"/> ', $rpl2['contenido']);


        $mensaje_correo = "<div style='background:#c8c8c8;width:100%;padding:0px;margin:0px;padding-top:30px;padding-bottom:30px;'>
    <div style='background:#FFF;width:80%;margin:auto;padding:30px;border:1px solid #56829c;border-radius:5px;color:#333;'><h2 style='text-align:center;background:#003e54;color:#FFF;border-radius:5px;padding:5px;'>Procedimiento de adquisici&oacute;n de paquete EMPRESARIAL - InfoSICOES</h2>
        <center><a href='https://www.infosiscon.com/'><img width='230px' src='https://www.infosiscon.com/contenido/imagenes/images/logo_infosicoes.png'/></a></center>
        <p style='font-style:italic;font-family:arial;font-size:10.5pt;line-height:2;'>
            Saludos $nomempresa
            <br/>
            El paquete GRATUITO asignado a su cuenta se corto el $fechadecorte, en agradecimiento al uso brindado a nuestra plataforma en fecha " . date("d/m/Y") . " se le ha otorgado el siguiente codigo de descuento <b>$codigo_descuento</b> en nuestro paquete EMPRESARIAL, otorgandole el 50% de descuento en la suscripci&oacute;n anual, donde solo deber&aacute; cancelar la suma de 400 Bs. en lugar de 800 Bs. que es el costo por 1 a&ntilde;o de servicio. Para hacer uso de este codigo de descuento debe seguir los siguientes pasos:
        </p>
        <div>
        <b>Procedimiento de pago mediante deposito bancario:</b>
           <ol>
           <li>Realizar el pago de 400 Bs. (CUATRO CIENTOS BOLIVIANOS) a la Cuenta <b>1-00000-21553173 NEMABOL</b> [CUENTA CORIENTE JURIDICA - NIT 2044323014 CI 2044323 LP]</li>
           <li>Realizar un escaneo &oacute; tomar una fotografia del comprobante de deposito</li>
           <li>Enviar la imagen del comprobante por e-mail a <b>ventas@infosicoes.com</b> &oacute; por WhatsApp al n&uacute;mero  <b>69713008</b></li>
           <li>Enviar junto al comprobante tambi&eacute;n el codigo de descuento: <b>$codigo_descuento</b> </li>
           <li>El paquete se le asignara inmediatamente al verificar el pago y el codigo de descuento</li>
           <li>Uno de nuestros operadores se comunicara con usted periodicamente para asesosarlo acerca de como hacer el mejor uso de su cuenta Infosicoes</li>
           </ol>
        <br/>
        <b>Procedimiento de pago mediante pago en oficina:</b>
           <ol>
           <li>Apersonarse a nuestras oficinas en: Calle Loayza #250 Edif. Castilla Piso 4 Oficina 410, La Paz - Bolivia</li>
           <li>Indicar al agente de ventas la adquisici&oacute;n del paquete EMPRESARIAL con el codigo de descuento: <b>$codigo_descuento</b> </li>
           <li>Realizar el pago de 400 Bs. (CUATRO CIENTOS BOLIVIANOS)</li>
           <li>El paquete se le asignara inmediatamente</li>
           <li>Uno de nuestros operadores se comunicara con usted periodicamente para asesosarlo acerca de como hacer el mejor uso de su cuenta Infosicoes</li>
           </ol>
        <br/>
        <p>
        <b>Para una mejor comunicaci&oacute;n entre nuestra Empresa y el Cliente, este mensaje tambi&eacute;n se envio via WhatsApp al n&uacute;mero $numero_empresa </b>
        </p>
        <p style='font-style:italic;font-family:arial;font-size:10.5pt;line-height:2;'>
        Nuestra plataforma le permite estar al tanto de las nuevas licitaciones que sean de su inter&eacute;s y poder monitorearlas constantemente, se te enviaran notificaciones de las nuevas contrataciones 
        publicadas en el SICOES que son referentes a los servicios que ofrece tu empresa, podras descargar directamente los archivos de convocatoria y documento base de contrataci&oacute;n de las distintas licitaciones, tendras acceso al PAC (programa anual de contraciones) de las distinatas entidades en distintos formatos como Excel, Word, Pdf como tambi&eacute;n en tu correo electr&oacute;nico para de esta manera no perder la oportunidad de postular a esa posible contrataci&oacute;n para tu Empresa.<br/>Nuestra misi&oacute;n es ayudarle con las adjudicaciones de sus futuras licitaciones, pronto podra recibir 
        notificaciones de empresas privadas que requieren sus servicios, trabajar con una entidad financiera que ayude con la emisi&oacute;n de Boletas de seriedad de propuestas o Boletas de garant&iacute;a de forma r&aacute;pida y c&oacute;moda, pronto sacaremos nuevas herramientas que te 
        ayudaran de mayor manera en la adjudicaci&oacute;n de una licitaci&oacute;n o una compra menor, ya que tenemos gran aceptaci&oacute;n de muchas entidades de gobierno.
        <br/>
        <br/>
        Esperamos que nuestra aplicaci&oacute;n le sea de utilidad.<br/><br/>Gracias por su atención.<br/></p>
        <hr/>
        <br/>
        <div style='margin:auto;width:80%;font-size:9.5pt;color:#333;line-height:2;'>
            <i>Infosicoes - Departamento de Ventas</i>
            <br/>
            ventas@infosicoes.com
            <br/>
            Calle Loayza #250 Edif. Castilla
            Piso 4 Oficina 410 - 69713008 - 79517817
            <br/>
            (591-2) 2118783 L&iacute;nea InfoSICOES
        </div>
    </div>
</div>";
        
        $htm_mensaje_wap = "Saludos $nomempresa somos Infosicoes"
                . "<br/>"
                . "<br/>"
                . "El paquete GRATUITO asignado a su cuenta se corto el $fechadecorte, en agradecimiento al uso brindado a nuestra plataforma en fecha " . date("d/m/Y") . " se le ha otorgado el siguiente codigo de descuento '$codigo_descuento' en nuestro paquete EMPRESARIAL, otorgandole el 50% de descuento en la suscripci&oacute;n anual, donde solo deber&aacute; cancelar la suma de 400 Bs. en lugar de 800 Bs. que es el costo por 1 a&ntilde;o de servicio. Para hacer uso de este codigo de descuento debe seguir los siguientes pasos:"
                . "<br/>"
                . "<br/>"
                . "Procedimiento de pago mediante deposito bancario:"
                . "<br/>"
                . "<br/>"
                . "1.- Realizar el pago de 400 Bs. (CUATRO CIENTOS BOLIVIANOS) a la Cuenta <b>1-00000-21553173 NEMABOL</b> [CUENTA CORIENTE JURIDICA - NIT 2044323014 CI 2044323 LP]"
                . "<br/>"
                . "<br/>"
                . "2.- Realizar un escaneo &oacute; tomar una fotografia del comprobante de deposito"
                . "<br/>"
                . "<br/>"
                . "3.- Enviar la imagen del comprobante por e-mail a <b>ventas@infosicoes.com</b> &oacute; por WhatsApp al n&uacute;mero 69713008"
                . "<br/>"
                . "<br/>"
                . "4.- Enviar junto al comprobante tambi&eacute;n el codigo de descuento: '$codigo_descuento'"
                . "<br/>"
                . "<br/>"
                . "5.- El paquete se le asignara inmediatamente al verificar el pago y el codigo de descuento"
                . "<br/>"
                . "<br/>"
                . "6.- Uno de nuestros operadores se comunicara con usted periodicamente para asesosarlo acerca de como hacer el mejor uso de su cuenta Infosicoes"
                . "<br/>"
                . "<br/>"
                . "<br/>"
                . "Procedimiento de pago mediante pago en oficina:"
                . "<br/>"
                . "<br/>"
                . "1.- Apersonarse a nuestras oficinas en: Calle Loayza #250 Edif. Castilla Piso 4 Oficina 410, La Paz - Bolivia"
                . "<br/>"
                . "<br/>"
                . "2.- Indicar al agente de ventas la adquisici&oacute;n del paquete EMPRESARIAL con el codigo de descuento: '$codigo_descuento'"
                . "<br/>"
                . "<br/>"
                . "3.- Realizar el pago de 400 Bs. (CUATRO CIENTOS BOLIVIANOS)"
                . "<br/>"
                . "<br/>"
                . "4.- El paquete se le asignara inmediatamente"
                . "<br/>"
                . "<br/>"
                . "5.- Uno de nuestros operadores se comunicara con usted periodicamente para asesosarlo acerca de como hacer el mejor uso de su cuenta Infosicoes"
                . "<br/>"
                . "<br/>"
                . "<br/>"
                . "Para una mejor comunicaci&oacute;n entre nuestra Empresa y el Cliente, este mensaje tambi&eacute;n se envio via E-mail a $correo_empresa "
                . "<br/>"
                . "<br/>"
                . "<br/>"
                . "Gracias por su atenci&oacute;n"
                . "<br/>"
                . "Para cualquier consulta no dude en comunicarnos por este medio"
                . "<br/>"
                . "<br/>"
                . "<br/>"
                . "Infosicoes - Departamento de Ventas"
                . "<br/>"
                . "ventas@infosicoes.com"
                . "<br/>"
                . "WhatsApp: 69713008"
                . "<br/>"
                . "Calle Loayza #250 Edif. Castilla Piso 4 Oficina 410 - 79517817 (591-2) 2118783 L&iacute;nea InfoSICOES"
                . "";
        

        $asunto = ("Procedimiento de adquisición de paquete EMPRESARIAL - InfoSICOES [$nomempresa]");

        $busc = array('-PLANES-INFOSICOES-', '-NOM-EMPRESA-', '-ADMINISTRADOR-');
        $remm = array($contenido_planes_infosicoes, $nomempresa, $nombre_administrador);
        $msj = str_replace($busc, $remm, $mensaje_correo);

        if (envio_email_ventas($correo_empresa, $asunto, $msj)) {
            
            envio_email_ventas("brayan.desteco@gmail.com", $asunto, $msj);
            envio_email_ventas("mrios@infosicoes.com", $asunto, $msj);
            envio_email_ventas("desteco@gmail.com", $asunto, $msj);
            
            movimiento("Se envio correo de procedimento de pago por Email a $correo_empresa", 'envcorreoproc-vent-paq', 'usuario', $id_empresa);
            query("UPDATE vent_paq_lista_uno SET sw_datos_enviados='1' WHERE id_empresa='$id_empresa' ORDER BY id DESC limit 1 ");
            echo " <img src='".$dominio_www."contenido/imagenes/images/bien.png' style='width:25px;'>  Correo de procedimiento enviado!<hr/>$htm_mensaje_wap<hr/>";
        } else {
            echo "<h2>No se envio el correo</h2>";
        }
    } else {
        echo "Error T487d4";
    }
} else {
    echo "Denegado!";
}
?>
