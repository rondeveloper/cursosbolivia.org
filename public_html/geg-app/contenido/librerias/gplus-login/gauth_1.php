<?php

session_start();

require_once('settings.php');
require_once('google-login-api.php');


include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

// Google passes a parameter 'code' in the Redirect Url
if (isset($_GET['code'])) {
    try {
        $gapi = new GoogleLoginApi();
        // Get the access token 
        $data = $gapi->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);
        // Get user information
        $user_info = $gapi->GetUserProfileInfo($data['access_token']);
        /*
          echo '<pre>';
          print_r($user_info);
          echo '</pre>';
         */

        /* LOGIN DE USUARIO */
        $email_usuario = $user_info['emails'][0]['value'];
        $id_gplus = $user_info['id'];
        $displayname_google = $user_info['displayName'];
        $url_google = str_replace('?sz=50', '?sz=150', $user_info['url']);
        $image_usuario = $user_info['image']['url'];

        /*
          echo "data: $email_usuario <br/>";
          echo "data: $id_gplus <br/>";
          echo "data: $displayname_google <br/>";
          echo "data: $url_google <br/>";
          echo "data: $image_usuario <br/>";
         */


        /* VINCULAR CUENTA GOOGLE A CUENTA CURSO.BO */
        if (isset_administrador()) {
            $id_administrador = administrador('id');

            query("UPDATE administradores SET gplus_id='$id_gplus',gplus_url='$url_google',gplus_email='$email_usuario',gplus_name='$displayname_google' WHERE id='$id_administrador' ");
            header('Location: ../../../mi-cuenta-vincular.adm');
            exit;
        } elseif (isset_usuario()) {
            $id_usuario = usuario('id');

            /*
              $nomimage = 'UGP2-' . $id_usuario . '.jpg';
              copy($url_google, "../../imagenes/usuarios/" . $nomimage);
             */

            query("UPDATE cursos_usuarios SET gplus_id='$id_gplus',gplus_url='$url_google',gplus_email='$email_usuario',gplus_name='$displayname_google' WHERE id='$id_usuario' ");
            header('Location: ../../../mi-cuenta.html');
            exit;
        } else {

            /* administrador */
            $rqd1 = query("SELECT * FROM administradores WHERE gplus_id='$id_gplus' ORDER BY id DESC limit 1 ");
            if (mysql_num_rows($rqd1) > 0) {
                $rqd2 = mysql_fetch_array($rqd1);
                $id_administrador = $rqd2['id'];
                administradorSet('id', $id_administrador);
                header('Location: ../../../admin.php');
            } else {
                /* usuario */
                $rqd1 = query("SELECT * FROM cursos_usuarios WHERE gplus_id='$id_gplus' ORDER BY id DESC limit 1 ");
                if (mysql_num_rows($rqd1) > 0) {
                    $rqd2 = mysql_fetch_array($rqd1);
                    $id_usuario = $rqd2['id'];
                    usuarioSet('id', $id_usuario);
                    header('Location: ../../../mi-cuenta.html');
                } else {
                    header('Location: ../../../ingreso-de-usuarios/cuenta-google-no-encontrada.html');
                }
            }
        }


        // Now that the user is logged in you may want to start some session variables
        $_SESSION['logged_in'] = 1;

        // You may now want to redirect the user to the home page of your website
        // header('Location: home.php');
    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }
}
