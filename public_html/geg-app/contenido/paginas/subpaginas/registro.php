<?php

if(isset_usuario()){
    echo "<script>location.href='acount.html';</script>";
}
        
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
    if(mysql_num_rows($rqv1)==0){
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
    }else{
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
        document.cookie = "datasesion_idtracking="+idtracking+"; " + expires;
        location.href="acount.html";
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
                                    <a href="<?php echo $dominio; ?>" style="font-size: 27pt;color: red;font-weight: bold;">
                                        GEG Bolivia
                                    </a>
                                </div>
                                <p style="font-size: 1.8rem;line-height: 1.5;text-align: center;padding: 0px 30px;color: #555;">
                                    GEG Bolivia, es la plataforma donde nuestros docentes tendr&aacute;n la oportunidad de conectarse entre ellos a nivel nacional y con sus pares de distintas partes del mundo, brind&aacute;ndoles la oportunidad de expandir sus contactos tanto personales como profesionales.
                                </p>
                            </div>
                            <div class="modal-body">
                                <div class="modal-inner">
                                    <div class="credentials-box">
                                        <div class="simple-credentials js-simple-credentials" style="margin-top: 20px;padding-top: 20px;
                                             padding-bottom: 30px;
                                             background-color: #F7F7F7;">
                                            <div style="width: 90%;margin: auto;">
                                                <div class="credentials-or" style="text-align: center;background: white;border: 1px solid #3369e8;padding: 5px;margin-bottom: 15px;">
                                                    <span style="color: #3369e8;font-weight: bold;">FORMULARIO DE REGISTRO</span>
                                                </div>
                                                
                                                <?php if(false){ ?>
                                                <div id="form-g1">
                                                    <form id="FORM" action="" accept-charset="UTF-8" method="post" onsubmit="return confirm('Esta seguro que los datos son correctos?');">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <label class="col-md-12 col-form-label" for="user_email">
                                                                    Correo electr&oacute;nico:
                                                                </label>
                                                                <input class="form-control string email optional input-lg" autocomplete="email" placeholder="Correo electr&oacute;nico..." type="email" value="" name="email" id="user_email" required="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-12 col-form-label" for="user_nombre">
                                                                    Nombre completo:
                                                                </label>
                                                                <input class="form-control password optional input-lg" autocomplete="on" placeholder="Nombre completo..." type="text" name="nombre" id="user_nombre" required="" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-md-12 col-form-label" for="dia_nac">
                                                                    Fecha de nacimiento:
                                                                </label>
                                                                <div class="col-md-4">
                                                                    <input class="form-control" type="number" id="dia_nac" name="user_dia_nac" placeholder="Dia..." required="" autocomplete="off" min="1" max="31" maxlength="2">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <select class="form-control" name="user_mes_nac">
                                                                        <option value="01">Enero</option>
                                                                        <option value="02">Febrero</option>
                                                                        <option value="03">Marzo</option>
                                                                        <option value="04">Abril</option>
                                                                        <option value="05">Mayo</option>
                                                                        <option value="06">Junio</option>
                                                                        <option value="07">Julio</option>
                                                                        <option value="08">Agosto</option>
                                                                        <option value="09">Septiembre</option>
                                                                        <option value="10">Octubre</option>
                                                                        <option value="11">Noviembre</option>
                                                                        <option value="12">Diciembre</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input class="form-control" type="number" name="user_anio_nac" placeholder="A&ntilde;o..." required="" autocomplete="off" min="1950" max="2020" maxlength="4">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-12 col-form-label" for="ci">
                                                                    C.I. / N&uacute;mero de documento:
                                                                </label>
                                                                <input class="form-control password optional input-lg" autocomplete="on" placeholder="C.I. / N&uacute;mero de documento..." type="number" name="ci" id="ci" required="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-12 col-form-label" for="user_pais">
                                                                    Pa&iacute;s:
                                                                </label>
                                                                <input class="form-control password optional input-lg" autocomplete="on" placeholder="Pa&iacute;s..." type="text" name="pais" id="user_pais" required="" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-12 col-form-label" for="user_ciudad">
                                                                    Ciudad:
                                                                </label>
                                                                <input class="form-control password optional input-lg" autocomplete="on" placeholder="Ciudad..." type="text" name="ciudad" id="user_ciudad" required="" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-12 col-form-label" for="user_institucion">
                                                                    Instituci&oacute;n Educativa a la que pertenece:
                                                                </label>
                                                                <input class="form-control password optional input-lg" autocomplete="on" placeholder="Instituci&oacute;n Educativa..." type="text" name="institucion" id="user_institucion" required="" onkeyup="this.value = this.value.toUpperCase();">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-12 col-form-label" for="user_celular">
                                                                    N&uacute;mero de Celular/Whatsapp:
                                                                </label>
                                                                <input class="form-control number optional input-lg" autocomplete="on" placeholder="N&uacute;mero de Celular/Whatsapp..." type="number" name="celular" id="user_celular" required="">
                                                            </div>
                                                        </fieldset>
                                                        <hr>
                                                        <div class="form-actions t-signup-button">
                                                            <input type="submit" class="btn btn-info btn-lg btn-block-xs-down" name="crear-cuenta" value="Crear cuenta"/>
                                                        </div>
                                                    </form>
                                                    <div class="info info--sm">
<!--                                                        <span class="ab-visible">
                                                            Al hacer clic en "Crear cuenta" acepto las condiciones de uso y recibir novedades y promociones.
                                                        </span>-->
                                                    </div>
                                                </div>
                                                <?php }else{ echo '<div class="alert alert-info">
  <strong>AVISO</strong> formulario de registro deshabilitado.
</div>'; } ?>
                                                <div id="form-g2" style="display: none;">
                                                    <br>
                                                    <div class="alert alert-success">
                                                        <strong>Saludos <span id="nom0431"></span></strong> para poder completar tu registro es necesario acceder mediante una cuenta Google, puedes hacerlo con el siguiete bot&oacute;n.
                                                    </div>
                                                    <div style="line-height: 3;width: 122px;margin: auto;padding: 20px 0px;">
                                                        <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    &iquest;Ya tienes cuenta?
                                    <a class="link-primary" href="ingreso-de-usuarios.html">Entrar</a>
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
