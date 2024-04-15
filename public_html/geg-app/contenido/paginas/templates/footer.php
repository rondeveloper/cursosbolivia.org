

<?php
if (!isset_usuario()) {
    ?>
    <style>
        .wrapsemibox {
            z-index: auto !important;
        }
        @media (min-width: 768px){
            .modal-dialog {
                width: 900px;
                margin: 30px auto;
            }
        }
        .hook{
            background: url('https://cursos.bo/contenido/imagenes/images/back_reg_popup.jpg') #121e2c;
            height: 640px;
            float: left;
        }
        .cont-regg{
            float: left;
            min-height:610px;
        }
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .hook{
                background-image: url('https://d2ycj993f2qxkw.cloudfront.net/assets/onboard/domestika-onboard-6@2x-074842a7e1a94854ccd5614eb7e3f2ab659452713930a76f3434f985a63e2e07.jpg');
            }
        }
        .btn-facebook{
            color: #FFF;
            background-color: #3B5998;
            border-color: #FFF;
        }
        .btn-google{
            margin-top: 10px;
            color: #FFF;
            background-color: #dd4c39;
            border-color: #FFF;
        }
        .btn-facebook:hover,.btn-google:hover{
            color: #EEE;
            border-color: gray;
        }
    </style>
    <?php
}
?>

<!-- Modal -->
<div id="MODAL_registerpopup" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="row">
                <div class="hook hidden-xs hidden-sm col-md-3"></div>

                <div class="cont-regg col-md-9">

                    <div class="corp">
                        <button aria-hidden="true" class="close" data-dismiss="modal" type="button">&times;</button>
                        <div class="text-center" style="padding-top:40px;padding-bottom:20px;">
                            <a href="<?php echo $dominio; ?>" style="font-size: 27pt;
                               color: red;
                               font-weight: bold;">CURSOS.BO</a>
                        </div>
                        <p style="font-size: 1.8rem;line-height: 1.5;text-align: center;padding: 0px 30px;color: #555;">
                            Aprende con los mejores profesionales y forma parte de la mayor comunidad de capacitaci&oacute;n en Bolivia
                        </p>
                    </div>

                    <div class="modal-body">
                        <div class="modal-inner">
                            <div class="credentials-box">
                                <div class="facebook-credentials js-facebook-credentials" style="width: 90%;margin: auto;">
                                    <form action="" accept-charset="UTF-8" method="get"><input name="utf8" type="hidden" value="?">
                                        <a href="https://cursos.bo/contenido/librerias/facebook-login/fbconfig.php"  name="button" type="submit" class="btn btn-facebook btn-block btn-lg"><i class="fa fa-facebook"></i> Reg&iacute;strate con Facebook</a>
                                    </form>
                                    <form action="" accept-charset="UTF-8" method="get">
                                        <?php
                                        require_once('contenido/librerias/gplus-login/settings.php');
                                        $enalce_login_gplus = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
                                        ?>
                                        <a href="<?php echo $enalce_login_gplus; ?>" name="button" type="submit" class="btn btn-google btn-block btn-lg"><i class="fa fa-google"></i> Reg&iacute;strate con Google</a>
                                    </form>
                                </div>
                                <div class="simple-credentials js-simple-credentials" style="margin-top: 20px;padding-top: 20px;
                                     padding-bottom: 30px;
                                     background-color: #F7F7F7;">
                                    <div style="width: 90%;margin: auto;">
                                        <div class="credentials-or">
                                            <span>o reg&iacute;strate con tu email</span>
                                        </div>
                                        <form class="simple_form" novalidate="novalidate" id="new_user" action="https://cursos.bo/registro-de-usuarios.html" accept-charset="UTF-8" method="post">
                                            <fieldset>
                                                <div class="form-group email optional user_email"><input class="form-control string email optional input-lg" autocomplete="off" placeholder="Correo de tu nueva cuenta" type="email" value="" name="email" id="user_email"></div>
                                                <div class="form-group password optional user_password"><input class="form-control password optional input-lg" autocomplete="off" placeholder="Tu contrase&ntilde;a" type="password" name="password" id="user_password"></div>
                                            </fieldset>
                                            <div class="form-actions t-signup-button">
                                                <input name="fast-user-register" type="submit" class="btn btn-success btn-lg btn-block-xs-down" value="Crear cuenta"/>
                                            </div>
                                        </form>
                                        <div class="info info--sm">
                                            <span class="ab-visible">
                                                Al hacer clic en "Crear cuenta" acepto las condiciones de uso y recibir novedades y promociones.
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            &iquest;Ya tienes cuenta?
                            <a data-toggle="modal" data-target="#login-modal" data-dismiss="modal" class="link-primary" href="https://cursos.bo/ingreso-de-usuarios.html">Entrar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>




<section id="TextoConsultasPie" class="TextoConsultasPie" style="display: none;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 hidden-xs hidden-sm">
                <br>                
                Informes y consultas : &nbsp; &nbsp;
                &nbsp;       | &nbsp; 
                <i class="icon-envelope"></i> 
                &nbsp; www.gegbolivia.org                 | &nbsp;  
                <a  target="_blank" title="GEG BOLIVIA">
                    <img src="contenido/alt/icono-facebook.png">
                </a>
                &nbsp;&nbsp;&nbsp;
                <a target="_blank" title="Cursos.Bo">
                    <img src="contenido/alt/icono-twitter.png">
                </a>
                &nbsp;&nbsp;&nbsp;
                <img src="contenido/alt/icono-youtube.png">
                <br><br>            
            </div>
            <div class="col-md-12 hidden-md hidden-lg">
            </div>
            <div class="col-md-12 hidden-md hidden-lg">
                <br>
                <i class="icon-envelope"></i> www.gegbolivia.org 
            </div>
            <div class="col-md-12 hidden-md hidden-lg">
                <br>
                <a  target="_blank" title="CURSOS.BO / Conocimiento para el desarrollo">
                    <img src="contenido/alt/icono-facebook.png">
                </a>
                &nbsp;&nbsp;&nbsp;
                <a  target="_blank" title="Cursos.Bo">
                    <img src="contenido/alt/icono-twitter.png">
                </a>
                &nbsp;&nbsp;&nbsp;
                <img src="contenido/alt/icono-youtube.png">
                <br>
                <br>
            </div>
        </div>
    </div>
</section>
<section  style="display: none;">
    <div class="footer">
        <div class="container">
            <div class="row">
                <p class="text-center">
                    Google Educator Groups Bolivia.
                </p>
            </div>
        </div>
    </div>
    <p id="back-top" style="display: none;">
        <a href="https://cursos.bo/#top"><span></span></a>
    </p>
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-sm-5 col-md-5 hidden-sm hidden-xs">
                    <p class="pull-left">&copy; CURSOS.BO 2020   La Paz - Bolivia </p>
<!--                    <p class="pull-left" data-toggle="modal" data-target="#MODAL_registerpopup">&copy; CURSOS.BO 2020   La Paz - Bolivia </p>-->
                </div>
                <div class="col-xs-12 hidden-sm col-md-7 hidden-xs ">
                    <ul class="footermenu pull-right">
                        <li class=""><a href="https://www.gegbolivia.org/" target="_blank">Quienes Somos</a></li>
                        <li class=""><a href="https://programa.gegbolivia.org/" target="_blank">Programa EDUCADOR DIGITAL</a></li>
                    </ul>
                </div>
                <div class="col-xs-12  hidden-md hidden-lg">
                    <p class="pull-left">&copy; CURSOS.BO 2020   La Paz - Bolivia</p>
                </div>
                <div class="col-xs-12  hidden-md hidden-lg" style="padding-top: 5px;margin-top: 5px;border-top:1px solid #FFF;">
                    <div class="col-xs-6 text-center"> <a href="https://www.gegbolivia.org/" target="_blank">Quienes Somos</a></div>
                    <div class="col-xs-6 text-center"> <a href="https://programa.gegbolivia.org/" target="_blank">Programa EDUCADOR DIGITAL</a></div>
                    <br/>
                </div>
            </div>
        </div>
        <?php
        /* backlink bolivian store */
        /*
        $url_backlinks = "https://www.bolivianstore.com.bo/contenido/paginas/procesos/rss-links.footer.php";
        $options = stream_context_create(array('http' => array('timeout' => 3)));
        echo file_get_contents($url_backlinks, false, $options);
        */
        ?>
    </div>            
</section>
</div>






<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-117245747-1"></script>
<script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-117245747-1');
</script>


<?php
/* proceso show whatsapp box */
//include_once 'contenido/paginas/items/item.proceso.show-whatsapp-box.php';
?>

</body>
</html>
