<?php
$certificado_id = $get[2];

/* PROCESO DE PERSISTENCIA DE SESION */
if (isset($_COOKIE['datasesion_idtracking']) && !isset_usuario()) {
    $datasesion_idtracking = $_COOKIE['datasesion_idtracking'];
    $rqdul1 = query("SELECT id FROM usuarios WHERE hash_usuario='$datasesion_idtracking' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rqdul1) > 0) {
        $rqdul2 = mysql_fetch_array($rqdul1);
        usuarioSet('id', $rqdul2['id']);
        echo "<script>location.href='acount.html';</script>";
    }
}



/* mensaje */
$mensaje = '';

/* crear-cuenta */
if (isset_post('crear-cuenta')) {

    $email = post('email');
    $nombre = post('nombre');
    $dia_nac = (int) post('user_dia_nac') < 10 ? '0' . post('user_dia_nac') : post('user_dia_nac');
    $mes_nac = post('user_mes_nac');
    $anio_nac = post('user_anio_nac');
    $fecha_nacimiento = $anio_nac . '-' . $mes_nac . '-' . $dia_nac;
    $ci = post('ci');
    $pais = post('pais');
    $ciudad = post('ciudad');
    $institucion = post('institucion');
    $celular = post('celular');
    $password = strtoupper(substr(md5(md5(rand(9999, 9999999999))), 7, 6));

    $rqv1 = query("SELECT id FROM usuarios WHERE ci LIKE '$ci' AND email LIKE '$email' ORDER BY id DESC limit 1 ");
    if (mysql_num_rows($rqv1) == 0) {
        query("INSERT INTO usuarios(
            email,
            nombre,
            fecha_nacimiento,
            ci,
            pais,
            ciudad,
            institucion,
            celular,
            password, 
            sw_notif, 
            fecha_registro, 
            estado
            ) VALUES (
            '$email',
            '$nombre',
            '$fecha_nacimiento',
            '$ci',
            '$pais',
            '$ciudad',
            '$institucion',
            '$celular',
            '$password',
            '1',
            NOW(),
            '1'
            )");
        $id_usuario = mysql_insert_id();
    } else {
        $rqv2 = mysql_fetch_array($rqv1);
        $id_usuario = $rqv2['id'];
    }
    $datasesion_idtracking = $id_usuario . '-' . strtoupper(substr(md5(md5(rand(9999, 9999999999))), 12, 8));
    query("UPDATE usuarios SET hash_usuario='$datasesion_idtracking' WHERE id='$id_usuario' ORDER BY id DESC limit 1 ");
    usuarioSet('id', $id_usuario);
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
}
?>


<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">

                <?php echo $mensaje; ?>

                <br/>

                <div class="boxForm ajusta_form_contacto" style="background: #f7f7f7;border: 1px solid #5bc0de;box-shadow: 1px 1px 3px #d0d0d0;">

                    <div class="row">
                        <div class="cont-regg col-md-12">
                            <div class="corp">
                                <div class="text-center" style="padding-top:40px;padding-bottom:20px;">
                                    <a  style="font-size: 27pt;color: red;font-weight: bold;">
                                        CERTIFICADO
                                    </a>
                                    <br>
                                </div>
                                <p style="font-size: 1.8rem;line-height: 1.5;text-align: center;padding: 0px 30px;color: #555;">
                                    Esta p&aacute;gina valida la emisi&oacute;n del certificado <?php echo $certificado_id; ?> emitido por GEG Bolivia, a continuaci&oacute;n se muestra los datos de la emision correspondiente. 
                                </p>
                            </div>
                            <div class="modal-body">
                                <div class="modal-inner">
                                    <div class="credentials-box">
                                        <div class="simple-credentials js-simple-credentials" style="margin-top: 10px;padding-top: 20px;
                                             padding-bottom: 30px;
                                             background-color: #F7F7F7;">
                                            <div class="credentials-or" style="text-align: center;background: white;border: 1px solid #3369e8;padding: 5px;margin-bottom: 15px;">
                                                <span style="color: #3369e8;font-weight: bold;">VALIDACI&Oacute;N DE CERTIFICADO</span>
                                            </div>

                                            <?php
                                            $rqde1 = query("SELECT e.receptor_de_certificado,c.texto_qr FROM emisiones_certificados e INNER JOIN certificados c ON e.id_certificado=c.id WHERE e.certificado_id='$certificado_id' LIMIT 1 ");
                                            $rqde2 = mysql_fetch_array($rqde1);
                                            $receptor_certificado = $rqde2['receptor_de_certificado'];
                                            $texto_qr_cert = $rqde2['texto_qr'];
                                            ?>

                                            <table class="table table-bordered" style="background: #FFF;">
                                                <tr>
                                                    <td>
                                                        Receptor:
                                                    </td>
                                                    <td>
                                                        <?php echo $receptor_certificado; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        ID:
                                                    </td>
                                                    <td>
                                                        <?php echo $certificado_id; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        PROGRAMA:
                                                    </td>
                                                    <td>
                                                        <?php echo $texto_qr_cert; ?>
                                                    </td>
                                                </tr>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br />
                <br />

            </div>
            <div class="col-md-2">
                <style>
                    .wtt-aux{
                        padding-left: 50px;
                    }
                </style>
                <div class="">
                    <!--                    <h4>BENEFICIOS DE TENER UNA CUENTA</h4>
                                        <hr/>
                                        <div class="wtt-aux whyus_icon1"><a class="whyus_link ">Registro a los cursos publicados en Cursos.BO</a></div>
                                        <div class="wtt-aux whyus_icon2"><a class="whyus_link ">Gestion de certificados de cursos</a></div>
                                        <div class="wtt-aux whyus_icon3"><a class="whyus_link ">Participaci&oacute;n en foros y consultas</a></div>
                                        <div class="wtt-aux whyus_icon4"><a class="whyus_link ">Acceso a material didactico</a></div>
                                        <div class="wtt-aux whyus_icon5"><a class="whyus_link ">Brinda cursos con nuestra plataforma</a></div>
                                        <div class="wtt-aux whyus_icon6"><a class="whyus_link ">Alianzas coorporativas</a></div>
                                        <div class="wtt-aux whyus_icon7"><a class="whyus_link ">Configuraci&oacute;n de cuentas</a></div>
                                        <div class="wtt-aux whyus_icon8"><a class="whyus_link ">Directorio de profesionales</a></div>-->
                </div>
            </div>
        </div>

    </section>
</div>                     

<script>
    /*
     $("#FORM").submit(function (e) {
     $("#nom0431").html($("#user_nombre").val());
     $("#form-g1").css('display', 'none');
     $("#form-g2").css('display', 'block');
     return false;
     });
     */
</script>

<script>
    function onSignIn(googleUser) {
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();
        var user_nombre = $("#user_nombre").val();
        var user_email = $("#user_email").val();
        var user_celular = $("#user_celular").val();

        var profile_id = profile.getId(); // Don't send this directly to your server!
        var profile_name = profile.getName();
        var profile_givenname = profile.getGivenName();
        var profile_familyname = profile.getFamilyName();
        var profile_imageurl = profile.getImageUrl();
        var profile_email = profile.getEmail();


        console.log("ID: " + profile.getId());
        console.log('Full Name: ' + profile.getName());
        console.log('Given Name: ' + profile.getGivenName());
        console.log('Family Name: ' + profile.getFamilyName());
        console.log("Image URL: " + profile.getImageUrl());
        console.log("Email: " + profile.getEmail());

        // The ID token you need to pass to your backend:
        var id_token = googleUser.getAuthResponse().id_token;
        console.log("ID Token: " + id_token);
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.registro.validar.php',
            data: {
                user_nombre: user_nombre,
                user_email: user_email,
                user_celular: user_celular,
                id_token: id_token,
                profile_id: profile_id,
                profile_name: profile_name,
                profile_givenname: profile_givenname,
                profile_familyname: profile_familyname,
                profile_imageurl: profile_imageurl,
                profile_email: profile_email
            },
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                //alert(data);
            }
        });
    }
</script>

<?php

function fecha_curso($fecha) {
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    $anio = date("Y", strtotime($fecha));
    return "$nombredia, $dia de $nombremes de $anio";
}
