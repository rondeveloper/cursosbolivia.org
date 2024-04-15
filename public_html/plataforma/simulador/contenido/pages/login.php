<?php

if(isset_post('user')){
    $user = post('user');
    $pasw = post('pasw');

    if($user=='admin' && $pasw=='c2bxp'){
        usuarioSet('id_adm',1);
        echo "<script>location.href='https://plataforma.cursosbolivia.org/simulador/admin.php';</script>";
        exit;
    }else{
        $rqdu1 = query("SELECT (u.id)dr_id_usuario,a.* FROM cursos_usuarios u INNER JOIN cursos_participantes p ON p.id_usuario=u.id INNER JOIN simulador_accesos a ON a.id_curso=p.id_curso  WHERE u.email='$user' AND u.password='$pasw' ");
        if(num_rows($rqdu1)>0){
            $rqdu2 = fetch($rqdu1);
            $id_usuario = $rqdu2['dr_id_usuario'];
            usuarioSet('id_sim',$id_usuario);
            usuarioSet('sw_acceso_envio_formularios',$rqdu2['sw_acceso_envio_formularios']);
            usuarioSet('sw_acceso_compras_menores',$rqdu2['sw_acceso_compras_menores']);
            usuarioSet('sw_acceso_anpe_lp',$rqdu2['sw_acceso_anpe_lp']);
            usuarioSet('sw_acceso_subastas',$rqdu2['sw_acceso_subastas']);
            include_once 'contenido/pages/home.php';
            exit;
        }else{
            echo "<script>alert('NO SE ENCOTRO USUARIO HABILITADO CON LOS DATOS INGRESADOS');history.back();</script>";
            exit;
        }
    }
}
?>
<!-- saved from url=(0014)about:internet -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, maximum-scale=1, user-scalable=no">
    
    <title>SIGEP</title>
    <link rel="stylesheet" href="contenido/alt/styles.css">
</head>

<body style="width: 320px; margin: 0 auto">
    <div class="block-center mt-4">
        <!-- <div class="panel panel-dark panel-flat"> -->
        <div class="card">
            <div class="card-header bg-dark text-center">
                <a alt="https://sigep.sigma.gob.bo/rsseguridad/login.html#">
                    <img alt="Image" class="block-center img-rounded" src="contenido/alt/logo.png">
                </a>
            </div>
            <div class="card-body">
                <p class="text-center py-2">AUTENTIFICATE PARA CONTINUAR</p>
                <form action="" name="login" id="login" method="POST" class="form-validate mb-lg ng-untouched ng-invalid ng-dirty">
                    <div class="input-group with-focus">
                        <input autocomplete="off" id="user" name="user" placeholder="Ingrese su Usuario" required="" type="text" class="form-control ng-untouched ng-pristine ng-invalid">
                        <div class="input-group-append">
                            <span class="input-group-text text-muted bg-transparent">
                                <em class="fa fa-user"></em>
                            </span>
                        </div>
                    </div>
                    <div class="input-group with-focus mt-3">
                        <input id="paswe" name="pasw" placeholder="Clave" required="" type="password" class="form-control ng-untouched ng-dirty ng-valid">
                        <div class="input-group-append">
                            <span class="input-group-text text-muted bg-transparent">
                                <em class="fa fa-lock"></em>
                            </span>
                        </div>
                    </div>
                    <button class="btn btn-block btn-primary mt-3" type="submit">ENTRAR</button>
                    <br>
                    <div class="panel-heading text-center">
                        <a id="hre" alt="https://sigep.sigma.gob.bo/rsseguridad/apiseg/ciudadaniaredirect?tipo=rest">
                            <img class="block-center img-rounded" src="contenido/alt/ciudadaniaDig.png"></a>
                    </div>
                    <div class="d-flex flex-row-reverse mt-5">
                        <a title="Desbloqueo de usuario" alt="https://sigep.sigma.gob.bo/rsseguridad/#/user-unlocking">¿Usuario bloqueado?</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="p-lg text-center">
            <span>©</span>
            <span>
                2021</span>
            <span>-</span>
            <span>SIGEP</span>
            <br>
            <span>Sistema de Gestión Pública</span>
            <br>
            <br>
            <br>
            <b>SITIO WEB NO OFICIAL</b>
            <br>
            <b>SITIO WEB SIMULADOR EXCLUSIVO DE CURSOSBOLIVIA.ORG</b>
        </div>
    </div>


<script>
    function getParameterByName(name, url) {
        if (!url)
            url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results)
            return null;
        if (!results[2])
            return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
    if (getParameterByName('redirect_uri') != null) {
        document.login.action = '/rsseguridad/apiseg/auth?redirect_uri=' + getParameterByName('redirect_uri') + '&client_id=0&response_type=code';
        document.getElementById('hre').href = '/rsseguridad/apiseg/ciudadaniaredirect?tipo=apirest&redirect_uri=' + getParameterByName('redirect_uri');
    }
</script>
<!--<script type="text/javascript" src="inline.bundle.js"></script>-->
<!--    <script type="text/javascript" src="polyfills.bundle.js"></script>-->
<!--<script type="text/javascript" src="scripts.bundle.js"></script>-->
<!--<script type="text/javascript" src="styles.bundle.js"></script>-->
<!--    <script type="text/javascript" src="vendor.bundle.js"></script>-->
<!--    <script type="text/javascript" src="main.bundle.js"></script>-->

</body></html>