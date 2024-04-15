<?php
/* ingreso a cuenta */
if (isset_post('ingresar-a-cuenta')) {

    /* mensaje */
    $mensaje = '';

    $sw_ingreso = false;

    $email = post('email');
    $fecha_nac = post('fecha_nac');

    $sw_ingresar = true;

    /* validacion de password */
    if (($email == '') || ($fecha_nac == '')) {
        $sw_ingresar = false;
        $mensaje .= '<div class="alert alert-danger">
  <strong>Error!</strong> los datos estan vacios.
</div>';
    }

    /* BUSQUEDA EN USUARIOS */
    $rqvu1 = query("SELECT * FROM usuarios WHERE email='$email' AND fecha_nacimiento='$fecha_nac' AND estado IN ('1','2') ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rqvu1) > 0) {

        if ($sw_ingresar) {
            $rqvu2 = mysql_fetch_array($rqvu1);
            if ($rqvu2['estado'] == '1') {
                $sw_ingreso = true;
                $id_usuario = $rqvu2['id'];
                $datasesion_idtracking = $id_usuario . '-' . rand(9999, 9999999);
                usuarioSet('id', $id_usuario);
                $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> proceso de ingreso realizado correctamente.
</div>';
                ?>
                <script>
                    var idtracking = '<?php echo $datasesion_idtracking; ?>';
                    var d = new Date();
                    d.setTime(d.getTime() + (70 * 24 * 60 * 60 * 1000));
                    var expires = "expires=" + d.toUTCString();
                    document.cookie = "datasesion_idtracking=" + idtracking + "; " + expires;
                    location.href = "acount.html";
                </script>
                <?php
                exit;
            } else {
                $mensaje .= '<div class="alert alert-warning">
  <strong>Aviso!</strong> su cuenta no fue autentificada, para autentificar su cuenta por favor ingrese al enlace de verificaci&oacute;n que enviamos a su correo electr&oacute;nico.
</div>';
            }
        }
    } else {
        $mensaje .= '<div class="alert alert-warning">
  <strong>ERROR</strong> datos incorrectos.
</div>';
    }
}