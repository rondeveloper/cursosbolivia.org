

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
            background: url('<?php echo $dominio_www; ?>contenido/imagenes/images/back_reg_popup.jpg') #121e2c;
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


<?php
/* desde variables de configuracion del sistema */
$facebook_page = $__CONFIG_MANAGER->getData('facebook_page');
$whatsapp_footer = $__CONFIG_MANAGER->getData('whatsapp_footer');
$copysection_footer = $__CONFIG_MANAGER->getData('copysection_footer');
$leyenda_footer = $__CONFIG_MANAGER->getData('leyenda_footer');
$sw_show_autoria = $__CONFIG_MANAGER->getSw('sw_show_autoria');
?>
<section id="TextoConsultasPie" class="TextoConsultasPie"> 
    <div class="container">
        <div class="row">
            <div class="col-md-12 hidden-xs hidden-sm">
                <br>                
                Informes y consultas : &nbsp;<i class="icon-phone"></i> &nbsp;
                &nbsp;       | &nbsp; 
                <img src="<?php echo $dominio_www; ?>contenido/alt/icono_whatsap.png"> 
                &nbsp; <?php echo $whatsapp_footer; ?>                     | &nbsp;  &nbsp; 
                <a href="<?php echo $facebook_page; ?>" target="_blank" title="<?php echo $___nombre_del_sitio; ?> / Facebook Cursos y capacitaciones en Bolivia">
                    <img src="<?php echo $dominio_www; ?>contenido/alt/icono-facebook.png"> <span style="color:#FFF;font-size: 11pt;">Facebook</span>
                </a>
                <br><br>            
            </div>
            <div class="col-md-12 hidden-md hidden-lg">
            </div>
            <div class="col-md-12 hidden-md hidden-lg">
                <br>
                <img src="contenido/alt/icono_whatsap.png"> <?php echo $whatsapp_footer; ?>
            </div>
            <div class="col-md-12 hidden-md hidden-lg">
                <br>
                <a href="<?php echo $facebook_page; ?>" target="_blank" title="<?php echo $___nombre_del_sitio; ?> / Facebook Cursos y capacitaciones en Bolivia">
                    <img src="<?php echo $dominio_www; ?>contenido/alt/icono-facebook.png">
                </a>
                <br>
                <br>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="footer">
        <div class="container">
            <div class="row">
                <p class="text-center">
                    <?php echo $leyenda_footer; ?>
                </p>
                <?php if($sw_show_autoria){ ?>
                    <div class="text-center sec-autoria-nemabol">
                        <?php echo $___nombre_del_sitio; ?> es parte del <a href="https://www.nemabol.com" style="color: orange;" target="_blank">Grupo Empresarial Nemabol</a>
                    </div>
                <?php } ?>
            </div>
            <?php if(false){ ?>
            <hr style="border-color: #ec8528;">
            <div class="row">
                <div class="col-md-4">
                    <b style="color: orange;">SUCURSAL LA PAZ</b>
                    <br>
                    Pago en Oficina CENTRAL LA PAZ: Av camacho Edif. Saenz N° 1377 Piso 3 Of. 301 esq. Loayza
                    <br>
                    <br>
                    <b style="color: orange;">Horario</b>
                    <br>
                    Lunes a Viernes de 09:00 a 13:00 / 14:30 a 18:30
                    <br>
                    S&aacute;bados de 09:00 a 13:00
                    <br>
                    <br>
                    <b style="color: orange;">Tel&eacute;fonos </b>
                    <br>
                    69713008 62360090
                    <br>
                    <br>
                </div>
                <div class="col-md-4">
                    <b style="color: orange;">SUCURSAL COCHABAMBA</b>
                    <br>
                    Pago en Oficina Cochabamba: Av. Ayacucho Nro 250 Edificio El Clan Mezzanine Of 204 Entre Gral Acha y Santiva&ntilde;ez Frente a la CNS
                    <br>
                    <br>
                    <b style="color: orange;">Horario</b>
                    <br>
                    Lunes a Viernes de 09:00 a 13:00 / 13:30 a 17:30
                    <br>
                    S&aacute;bados de 09:00 a 13:00
                    <br>
                    <br>
                    <b style="color: orange;">Tel&eacute;fonos </b>
                    <br>
                    62360040
                    <br>
                    <br>
                </div>
                <div class="col-md-4">
                    <b style="color: orange;">SUCURSAL ORURO</b>
                    <br>
                    Pago en Oficina ORURO: Calle Potosi 1564 entre Bolivar y Adolfo Mier Edificio SIRIUS 1er Piso of 4
                    <br>
                    <br>
                    <b style="color: orange;">Horario</b>
                    <br>
                    Lunes a Viernes de 08:00 a 12:00 / 14:30 a 18:30
                    <br>
                    S&aacute;bados de 09:00 a 13:00
                    <br>
                    <br>
                    <b style="color: orange;">Tel&eacute;fonos </b>
                    <br>
                    77000691
                    <br>
                    <br>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <p id="back-top" style="display: none;">
        <a href="<?php echo $dominio; ?>#top"><span></span></a>
    </p>
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-sm-5 col-md-5 hidden-sm hidden-xs">
                    <p class="pull-left">&copy; <?php echo $___nombre_del_sitio; ?> <?php echo date("Y"); ?>   <?php echo $copysection_footer; ?></p>
                </div>
                <div class="col-xs-12 hidden-sm col-md-7 hidden-xs ">
                    <ul class="footermenu pull-right">
                        <li><a href="https://licitaciones.com.bo/">Licitaciones</a></li>
                        <li><a href="https://infosiscon.com/consultorias-bolivia.html">Consultorias</a></li>
                        <li><a href="https://cursosbolivia.org/">Cursos Bolivia</a></li>
                        <li><a href="https://www.vpshostingbolivia.com/">VPS Hosting Bolivia</a></li>
                        <li><a target="_blank" href="https://infosiscon.com/noticias.html">NOTICIAS</a></li>
                    </ul>
                </div>
                <div class="col-xs-12  hidden-md hidden-lg">
                    <p class="pull-left">&copy; <?php echo $___nombre_del_sitio; ?> <?php echo date("Y"); ?>   <?php echo $copysection_footer; ?></p>
                </div>
                <div class="col-xs-12  hidden-md hidden-lg" style="padding-top: 5px;margin-top: 5px;border-top:1px solid #FFF;">
                    <div class="col-xs-6 text-center"> <a href="contacto.html" class="EnlacePoliticas">Contacto</a></div>
                    <div class="col-xs-6 text-center"> <a href="quienes-somos.html" class="EnlacePoliticas">Quienes Somos</a></div>
                    <br/>
                </div>
            </div>
        </div>
        <?php
        /* backlink bolivian store */
        if($dominio=='https://cursos.bo/' && false){
            $url_backlinks = "https://www.bolivianstore.com.bo/contenido/paginas/procesos/rss-links.footer.php";
            $options = stream_context_create(array('http' => array('timeout' => 3)));
            echo file_get_contents($url_backlinks, false, $options);
        }
        ?>
    </div>            
</section>
</div>

<!--previous scripts-->
<?php
if ($get[1] !== 'registro-curso' && (!isset_usuario())) {
    ?>
    <script>
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ')
                    c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0)
                    return c.substring(nameEQ.length, c.length);
            }
            return null;
        }
    </script>
    <?php
}
?>

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


<!--Notificaciones-->
<script>
    function guardaToken(token) {
        //alert('Token:' + token);
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.instant.saveToken.php',
            data: {token: token},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                //alert('Token: guardado : ' + token);
            }
        });
    }
</script>
<script>
    function cambia_estado_notificaciones(token, state) {
        $("#ajaxbox-notification-msm").html("Cargando...");
        if (token === '') {
            location.href='notificaciones.html';
        } else {
            $.ajax({
                url: 'contenido/paginas/ajax/ajax.instant.cambia_estado_notificaciones.php',
                data: {token: token, state: state},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#ajaxbox-notification-state").html(data);
                    $("#ajaxbox-notification-msm").html("");
                }
            });
        }
    }
</script>
<script src="https://www.gstatic.com/firebasejs/4.6.2/firebase.js"></script>
<script>
    var config = {
        apiKey: "AIzaSyDVaOYZYo_6QzuWqu9LHkgROukGGdlDL70",
        authDomain: "cursosbo-220916.firebaseapp.com",
        messagingSenderId: "1057058715628"
    };
    firebase.initializeApp(config);

    const messaging = firebase.messaging();
    messaging.requestPermission()
            .then(function() {
                //MsgElem.innerHTML = "Notification permission granted.";
                console.log("Notification permission granted.");

                // get the token in the form of promise
                return messaging.getToken();
            })
            .then(function(token) {
                //TokenElem.innerHTML = "token is : " + token;
                guardaToken(token);
            })
            .catch(function(err) {
                //ErrElem.innerHTML = ErrElem.innerHTML + "; " + err;
                console.log("Unable to get permission to notify.", err);
            });

    messaging.onMessage(function(payload) {
        console.log("Message received. ", payload);
        //NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload);
        displayNotification(payload);
    });
</script>
<script>
    function displayNotification(payload) {
        if (Notification.permission === 'granted') {
            var options = {
                body: payload["data"]["gcm.notification.text"],
                icon: payload["data"]["gcm.notification.image"]
            };
            var notif = new Notification(payload["data"]["gcm.notification.text"], options);
            notif.onclick = function(event) {
                event.preventDefault(); // Previene al buscador de mover el foco a la pesta�a del Notification
                window.open(payload["data"]["gcm.notification.url"], '_blank');
                notif.close();
            };
            notif.show();
        }
    }
</script>


<?php
/* proceso ask selec departamento pushnav */
include_once 'contenido/paginas/items/item.proceso.ask-selec-departamento.php';
?>

<?php
/* proceso show whatsapp box */
include_once 'contenido/paginas/items/item.proceso.show-whatsapp-box.php';
?>


<!-- Modal general -->
<div id="MODAL-general" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="title-MODAL-general"></h4>
      </div>
      <div class="modal-body" id="body-MODAL-general">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


</body>
</html>

<?php
/*
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
$ip_upload = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
$ip_upload = $_SERVER['REMOTE_ADDR'];
}
$response = file_get_contents('http://ipgeolocationapi.org/q/1ce2695250f6d075a370679b4253885cfa1eb8f75444?h='.$ip_upload);
query("INSERT INTO `ipgeolocationapi`
(`ip`, `contenido`, `fecha`) 
VALUES 
('$ip_upload','$response',NOW())");
*/

/* cierre de conexion */
mysqli_close($mysqli);