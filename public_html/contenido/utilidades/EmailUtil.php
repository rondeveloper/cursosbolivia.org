<?php

class EmailUtil
{
    public static function urlUnsubscribe($correo){
        global $dominio;
        return $dominio.'unsubscribe/' . $correo . '/0001/' . md5($correo . 'dardebaja') . '.html';
    }

    public static function generarContenidoEmailHTML($templateName, $data) {
        global $dominio_admin, $___path_raiz;
        if(!file_exists($___path_raiz.'/admin/pages/mails/mail.'.$templateName.'.php')){
            echo "<hr><b>LA PLANTILLA DE EMAIL mail.$templateName.php NO EXISTE</b>";
            exit;
        }
        $get_params = '';
        foreach ($data as $key => $value) {
            if(substr($key,0,1) != '_'){
                $get_params .= $key.'='.$value;
            }
        }
        $_subtitulo = $data['_subtitulo'];
        $_email_unsubscribe = $data['_email_unsubscribe'];
        $_nombre_referencia = $data['_nombre_referencia'];
        $contenido_correo = file_get_contents($dominio_admin.'pages/mails/mail.'.$templateName.'.php?'.$get_params);
        return EmailUtil::platillaEmailGeneral($contenido_correo,$_subtitulo,$_email_unsubscribe,urlUnsubscribe($_email_unsubscribe),$_nombre_referencia);
    }

    /* PLANTILLA DE ENVIO DE EMAIL 1 */
    public static function platillaEmailGeneral($bodyEmail,$tituloEmail,$enviarAEmail,$urlUnsubscribeEmail,$nomUsuarioEmail) {
        global $dominio,$___nombre_del_sitio,$___color_base, $__CONFIG_MANAGER;

        /* datos de configuracion */
        $img_logotipo_principal = $__CONFIG_MANAGER->getImg('img_logotipo_principal');

        $busc = array('class="img-pag-static"', 'font-size: 12pt', 'font-size: 13pt', 'font-size: 14pt', 'font-size: 15pt', 'font-size: 16pt');
        $remm = array(' style="width: 100%;" ', 'font-size: 10pt', 'font-size: 10pt', 'font-size: 10pt', 'font-size: 12pt', 'font-size: 12pt');
        $bodycont = str_replace($busc, $remm, $bodyEmail);
        $titulo_mensaje = $tituloEmail;
        $correo_a_enviar = $enviarAEmail;
        $url_unsubscribe = $urlUnsubscribeEmail;

    $cont = '<div bgcolor="#e6e6e6" style="width:100%;min-width:100%;background-color:#e6e6e6;margin:0px;padding:0px" align="center">
<table style="text-align:center;min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>

<tr>
<td align="center">
<div style="background-color:#e6e6e6">
<table style="background-color:#e6e6e6" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#e6e6e6">
<tbody>
<tr>
<td align="center">
<table style="width:612px" width="612" cellspacing="0" cellpadding="0" border="0" align="center">
<tbody>
<tr>
<td style="padding:15px 5px" valign="top" align="center">
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
<tbody>
<tr>
<td style="background-color:#869198;padding:1px;border-bottom: 1px solid #989898;" valign="top" bgcolor="#869198" align="center">
<table style="background-color:#869198" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#869198" align="center">
<tbody>
<tr>
<td style="background-color:#ffffff;padding:0px" valign="top" bgcolor="#ffffff" align="center">
<div>
<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td width="100%" valign="top" align="">
<div>
<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="padding-top:0px;padding-bottom:0px" valign="top" align="center">

<div style="background: '.$___color_base.';padding: 20px 180px;">
<img alt="" style="display:block;height:auto!important;max-width:100%!important;" width="599" vspace="0" hspace="0" border="0" src="'.$img_logotipo_principal.'">
</div>

</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>

<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="background-color:#e3f3fd;border-bottom: 1px solid #efefef;" width="100%" valign="top" bgcolor="BFBFBF" align="">
<div>
<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="font-family:Arial,Verdana,Helvetica,sans-serif;font-size:14px;color:#403f42;text-align:left;display:block;word-wrap:break-word;line-height:1.2;padding:10px 20px" valign="top" align="left">
<div></div>
<div>
<div>
<div style="text-align:center" align="center"><span style="font-size:20px;color: #458493;">' . $titulo_mensaje . '</span></div>
</div>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td width="100%" valign="top" align="">
<div>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td valign="top" align="center">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="padding-bottom:10px;height:1px;line-height:1px" width="100%" valign="top" align="center">
<div><img alt="" style="display:block;height:1px;width:5px" width="5" vspace="0" hspace="0" height="1" border="0" src="https://ci5.googleusercontent.com/proxy/prjVWi9agcvHo6wWwSY0NoWHiaFTUW1GFE88HIUk5LrHN5aeEIX3D6pJtDlEPNI6Dvf_Ou5XHLexQ1ajT_5sVXHMGfcLsqoinYvkNDmXc8HzvBff2Y637Q=s0-d-e1-ft#https://imgssl.constantcontact.com/letters/images/1101116784221/S.gif"></div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td width="100%" valign="top" align="">
<div>
<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="line-height: 1.4;font-family:Arial,Verdana,Helvetica,sans-serif;font-size:12px;color:#403f42;text-align:left;display:block;word-wrap:break-word;padding:10px 20px" valign="top" align="left">

<div>
' . $bodycont . '
</div>
<div style="text-align: center;border-top: 2px dashed gray;padding: 10px 0px;margin-top: 15px;line-height: 2;">
Ay&uacute;danos a superar los 100 mil likes en nuestra p&aacute;gina en facebook
<br>
https://www.facebook.com/cursoswebbolivia
<br>
&Uacute;nete a nuestro grupo https://www.facebook.com/groups/grupocursosbolivia
</div>

</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
<tr>
<td></td>
</tr>
</tbody>
</table>
<table style="background:#ffffff;margin-left:auto;margin-right:auto;table-layout:auto!important" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
<tbody>
<tr>
<td style="width:100%" width="100%" valign="top" align="center">
<div style="margin-left:auto;margin-right:auto;max-width:100%" align="center">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="padding:16px 0px" valign="top" align="center">
<table style="width:580px" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="color:#5d5d5d;font-family:Verdana,Geneva,sans-serif;font-size:12px;padding:4px 0px" valign="top" align="center">
<span>'.$___nombre_del_sitio.'<span> |
</span></span>
</span></span><span></span><span></span><span>Cursos y capacitaciones en Bolivia</span><span></span>
</td>
</tr>
<tr>
<td style="padding:10px 0px" valign="top" align="center">
<table cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr>
<td style="color:#5d5d5d;font-family:Verdana,Geneva,sans-serif;font-size:12px;padding:4px 0px" valign="top" align="center">
<a href="'.$dominio.'" style="color:#5d5d5d" target="_blank">Acerca de nosotros</a> | 
enviado a (' . $correo_a_enviar . ') | 
<a href="' . $url_unsubscribe . '" style="color:#5d5d5d" target="_blank">Dejar de recibir correos</a>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>
</div>';
        return $cont;
    }

}
