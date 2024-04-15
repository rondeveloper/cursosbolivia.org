<?php

/* ingreso a cuenta */
if (isset_post('ingresar-a-cuenta')) {

    /* mensaje */
    $mensaje = '';
    
    $sw_ingreso = false;

    $email = post('email');
    $password = post('password');

    $sw_ingresar = true;

    /* validacion de password */
    if (($email == '') || ($password == '')) {
        $sw_ingresar = false;
        $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> los datos estan vacios.
</div>';
    }

    /* BUSQUEDA EN USUARIOS */
    $rqvu1 = query("SELECT * FROM cursos_usuarios WHERE email='$email' AND password='$password' AND estado IN ('1','2') ORDER BY id DESC limit 1 ");
    if (num_rows($rqvu1) > 0) {

        if ($sw_ingresar) {
            $rqvu2 = fetch($rqvu1);

            if ($rqvu2['estado'] == '1') {
                $sw_ingreso = true;
                $id_usuario = $rqvu2['id'];
                $hash_usuario = $rqvu2['hash_usuario'];
                usuarioSet('id', $id_usuario);
                setcookie("id_usuario", $id_usuario, time() + 100 * 24 * 60 * 60);
                setcookie("hash_usuario", $hash_usuario, time() + 100 * 24 * 60 * 60);
                $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> proceso de ingreso realizado correctamente.
</div>';
                echo "<script>location.href='mi-cuenta.html';</script>";
                exit;
            } else {
                $mensaje .= '<div class="alert alert-warning">
  <strong>Aviso!</strong> su cuenta no fue autentificada, para autentificar su cuenta por favor ingrese al enlace de verificaci&oacute;n que enviamos a su correo electr&oacute;nico.
</div>';
            }
        }
    } else {

        /* BUSQUEDA EN DOCENTES */
        $rqvu1 = query("SELECT * FROM cursos_docentes WHERE sw_acceso_cuenta_cvir='1' AND nick='$email' AND password='$password' AND estado IN ('1') ORDER BY id DESC limit 1 ");
        if (num_rows($rqvu1) > 0) {
            if ($sw_ingresar) {
                $rqvu2 = fetch($rqvu1);
                if ($rqvu2['estado'] == '1') {
                    $sw_ingreso = true;
                    $id_usuario = $rqvu2['id'];
                    docenteSet('id', $id_usuario);
                    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> proceso de ingreso realizado correctamente.
</div>';
                    echo "<script>location.href='acount-docente.dashboard.html';</script>";
                    exit;
                } else {
                    $mensaje .= '<div class="alert alert-warning">
  <strong>Aviso!</strong> su cuenta se ecnuentra desactivada, comuniquese con un administrador.
</div>';
                }
            }
        } else {
            $sw_ingresar = false;
            $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> los datos son incorrectos.
</div>';
        }
    }
}