<?php

session_start();
// added in v4.0.0
require_once 'autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);


// init app with app id and secret
FacebookSession::setDefaultApplication('2070955676476906', '362eeeda29059ca4b41e518b6a0c38ea');
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper('https://cursos.bo/contenido/librerias/facebook-login/fbconfig.php');
try {
    $session = $helper->getSessionFromRedirect();
} catch (FacebookRequestException $ex) {
    // When Facebook returns an error
} catch (Exception $ex) {
    // When validation fails or other local issues
}
// see if we have a session
if (isset($session)) {
    // graph api request for user data
    $request = new FacebookRequest($session, 'GET', '/me?fields=name,email');
    $response = $request->execute();
    // get response
    $graphObject = $response->getGraphObject();
    $fbid = $graphObject->getProperty('id');              // To Get Facebook ID
    $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
    $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
    /* ---- Session Variables ----- */
    if (trim($femail) == '') {
        $femail = 'no-email-data';
    }
    $email_usuario = $femail;
    $id_user_rs = $fbid;
    $displayname_google = $fbfullname;
    $url_google = 'https://www.facebook.com/profile.php?id=' . $fbid;
    $image_usuario = 'https://graph.facebook.com/' . $fbid . '/picture';
//    $_SESSION['FBID'] = $fbid;
//    $_SESSION['FULLNAME'] = $fbfullname;
//    $_SESSION['EMAIL'] = $femail;
    /* ---- header location after session ---- */

    /*
      echo "data: $email_usuario <br/>";
      echo "data: $id_user_rs <br/>";
      echo "data: $displayname_google <br/>";
      echo "data: $url_google <br/>";
      echo "data: $image_usuario <br/>";
     */


    if (false) {

        /* VINCULAR CUENTA GOOGLE A CUENTA CURSO.BO */
        if (isset_administrador()) {
            $id_administrador = administrador('id');

            query("UPDATE administradores SET fb_id='$id_user_rs',fb_url='$url_google',fb_email='$email_usuario',fb_name='$displayname_google' WHERE id='$id_administrador' ");
            header('Location: ../../../mi-cuenta-vincular.adm');
            exit;
        } elseif (isset_usuario()) {
            $id_usuario = usuario('id');

            /*
              $nomimage = 'UGP2-' . $id_usuario . '.jpg';
              copy($url_google, "../../imagenes/usuarios/" . $nomimage);
             */

            query("UPDATE cursos_usuarios SET fb_id='$id_user_rs',fb_url='$url_google',fb_email='$email_usuario',fb_name='$displayname_google' WHERE id='$id_usuario' ");
            header('Location: ../../../mi-cuenta.html');
            exit;
        } else {

            /* administrador */
            $rqd1 = query("SELECT * FROM administradores WHERE fb_id='$id_user_rs' ORDER BY id DESC limit 1 ");
            if (mysql_num_rows($rqd1) > 0) {
                $rqd2 = mysql_fetch_array($rqd1);
                $id_administrador = $rqd2['id'];
                administradorSet('id', $id_administrador);
                header('Location: ../../../admin.php');
            } else {
                /* usuario */
                /*
                  $rqd1 = query("SELECT * FROM cursos_usuarios WHERE fb_id='$id_user_rs' ORDER BY id DESC limit 1 ");
                  if (mysql_num_rows($rqd1) > 0) {
                  $rqd2 = mysql_fetch_array($rqd1);
                  $id_usuario = $rqd2['id'];
                  usuarioSet('id', $id_usuario);
                  header('Location: ../../../mi-cuenta.html');
                  } else {
                  header('Location: ../../../ingreso-de-usuarios/cuenta-google-no-encontrada.html');
                  }
                 */
                header('Location: ../../../ingreso-de-usuarios/cuenta-google-no-encontrada.html');
            }
        }
    }


    /* USUARIO */
    $rqd1 = query("SELECT * FROM cursos_usuarios WHERE fb_id='$id_user_rs' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rqd1) > 0) {
        $rqd2 = mysql_fetch_array($rqd1);
        $id_usuario = $rqd2['id'];
        $hash_usuario = $rqd2['hash_usuario'];
        usuarioSet('id', $id_usuario);
        setcookie("id_usuario",$id_usuario,mktime(0, 0, 0, 12, 31, date("Y")));
        setcookie("hash_usuario",$hash_usuario,mktime(0, 0, 0, 12, 31, date("Y")));
        header('Location: ../../../mi-cuenta.html');
    } else {
        $password = rand(999,99999);
        $fecha_registro = date("Y-m-d");
        $hash_usuario = md5('Rsur5-'.rand(99,9999));
        query("INSERT INTO cursos_usuarios (nombres,email,password,fb_id,fb_url,fb_email,fb_name,hash_usuario,fecha_registro,estado) VALUES ('$displayname_google','$email_usuario','$password','$id_user_rs','$url_google','$email_usuario','$displayname_google','$hash_usuario','$fecha_registro','1') ");
        $id_usuario = mysql_insert_id();
        usuarioSet('id', $id_usuario);
        setcookie("id_usuario",$id_usuario,mktime(0, 0, 0, 12, 31, date("Y")));
        setcookie("hash_usuario",$hash_usuario,mktime(0, 0, 0, 12, 31, date("Y")));
        header('Location: ../../../mi-cuenta-preferencias/bienvenida.html');
    }


    //header("Location: index.php");
} else {

    $permissions = array(
        scope => 'public_profile',
        'email',
    );
    $loginUrl = $helper->getLoginUrl($permissions);
    header("Location: " . $loginUrl);
}
