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
        
          echo '<pre>';
          print_r($user_info);
          echo '</pre>';
          exit;
         

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
        if (isset_usuario() && (!isset($_SESSION['ask_login']))) {
            $id_usuario = usuario('id');

            /*
              $nomimage = 'UGP2-' . $id_usuario . '.jpg';
              copy($url_google, "../../imagenes/usuarios/" . $nomimage);
             */

            query("UPDATE cursos_usuarios SET gplus_id='$id_gplus',gplus_url='$url_google',gplus_email='$email_usuario',gplus_name='$displayname_google' WHERE id='$id_usuario' ");
            header('Location: ../../../mi-cuenta.html');
            //echo "END A";exit;
            exit;
        } else {

            /* administrador */
            $rqd1 = query("SELECT * FROM administradores WHERE gplus_id='$id_gplus' ORDER BY id DESC limit 1 ");
            if (mysql_num_rows($rqd1) > 0 ) {
                $rqd2 = mysql_fetch_array($rqd1);
                $id_administrador = $rqd2['id'];
                administradorSet('id', $id_administrador);
                //**logcursos('Ingreso de administrador [GOOGLE]', 'ingreso-administrador', 'administrador', $id_administrador);
                
                //$_SESSION['ask_login'] = 'false';
                
                header('Location: ../../../admin.php');
                //echo "END B";exit;
            } elseif(isset($_SESSION['ask_vincular']) && isset_administrador()) {
                $id_administrador = administrador('id');
                query("UPDATE administradores SET gplus_id='$id_gplus',gplus_url='$url_google',gplus_email='$email_usuario',gplus_name='$displayname_google' WHERE id='$id_administrador' ");
                header('Location: https://cursos.bo/mi-cuenta-vincular.adm');
            } elseif(!isset($_SESSION['ask_login'])) {
                /* usuario */
                $rqd1 = query("SELECT * FROM cursos_usuarios WHERE gplus_id='$id_gplus' ORDER BY id DESC limit 1 ");
                if (mysql_num_rows($rqd1) > 0) {
                    $rqd2 = mysql_fetch_array($rqd1);
                    $id_usuario = $rqd2['id'];
                    $hash_usuario = $rqd2['hash_usuario'];
                    usuarioSet('id', $id_usuario);
                    setcookie("id_usuario",$id_usuario,mktime(0, 0, 0, 12, 31, date("Y")));
                    setcookie("hash_usuario",$hash_usuario,mktime(0, 0, 0, 12, 31, date("Y")));
                    header('Location: ../../../mi-cuenta.html');
                    //echo "END C";exit;
                } else {

                    $password = rand(999, 99999);
                    $fecha_registro = date("Y-m-d");
                    $hash_usuario = md5('Rsur5-'.rand(99,9999));
                    query("INSERT INTO cursos_usuarios (nombres,email,password,gplus_id,gplus_url,gplus_email,gplus_name,hash_usuario,fecha_registro,estado) VALUES ('$displayname_google','$email_usuario','$password','$id_gplus','$url_google','$email_usuario','$displayname_google','$hash_usuario','$fecha_registro','1') ");
                    $id_usuario = mysql_insert_id();
                    usuarioSet('id', $id_usuario);
                    setcookie("id_usuario",$id_usuario,mktime(0, 0, 0, 12, 31, date("Y")));
                    setcookie("hash_usuario",$hash_usuario,mktime(0, 0, 0, 12, 31, date("Y")));
                    header('Location: ../../../mi-cuenta-preferencias/bienvenida.html');
                    //echo "END D";exit;
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
