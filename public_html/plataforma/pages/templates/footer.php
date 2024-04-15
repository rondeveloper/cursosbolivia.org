
<section id="TextoConsultasPie" class="TextoConsultasPie"> 
    <div class="container">
        <div class="row">
            <div class="col-md-12 hidden-xs hidden-sm">
                <br>                
                Informes y consultas : &nbsp;<i class="icon-phone"></i> &nbsp;
                &nbsp;       | &nbsp; 
                <img src="<?php echo $dominio_www; ?>contenido/alt/icono_whatsap.png"> 
                &nbsp; <?php echo $___numero_whatsapp; ?>                     | &nbsp;  &nbsp; 
                <a href="https://www.facebook.com/cursoswebbolivia" target="_blank" title="<?php echo $___nombre_del_sitio; ?> / Cursos y capacitaciones en Bolivia">
                    <img src="<?php echo $dominio_www; ?>contenido/alt/icono-facebook.png"> <span style="color:#FFF;font-size: 11pt;">Facebook</span>
                </a>
                <br><br>            
            </div>
            <div class="col-md-12 hidden-md hidden-lg">
            </div>
            <div class="col-md-12 hidden-md hidden-lg">
                <br>
                <img src="<?php echo $dominio_www; ?>contenido/alt/icono_whatsap.png"> <?php echo $___numero_whatsapp; ?>
            </div>
            <div class="col-md-12 hidden-md hidden-lg">
                <br>
                <a href="https://www.facebook.com/cursoswebbolivia" target="_blank" title="<?php echo $___nombre_del_sitio; ?> / Cursos y capacitaciones en Bolivia">
                    <img src="<?php echo $dominio_www; ?>contenido/alt/icono-facebook.png"> <span style="color:#FFF;font-size: 11pt;">Facebook</span>
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
                    Cursos y capacitaciones en toda Bolivia.
                    <?php if($dominio=='https://cursos.bo/'){ ?>
                    <br><br>
                    <?php echo $___nombre_del_sitio; ?> es parte del <a href="https://www.nemabol.com" style="color: orange;" target="_blank">Grupo Empresarial Nemabol</a>
                    <?php } ?>
                </p>
            </div>
        </div>
    </div>
    <p id="back-top" style="display: none;">
        <a href="<?php echo $dominio; ?>#top"><span></span></a>
    </p>
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-sm-5 col-md-5 hidden-sm hidden-xs">
                    <p class="pull-left" data-toggle="modal" data-target="#MODAL_registerpopup">&copy; <?php echo $___nombre_del_sitio; ?> <?php echo date("Y"); ?>   La Paz - Bolivia </p>
                </div>
                <div class="col-xs-12 hidden-sm col-md-7 hidden-xs ">
                    <ul class="footermenu pull-right">
                        <li><a href="<?php echo $dominio; ?>contacto.html">Contacto</a></li>
                        <li><a href="<?php echo $dominio; ?>quienes-somos.html">Quienes Somos</a></li>
                    </ul>
                </div>
                <div class="col-xs-12  hidden-md hidden-lg">
                    <p class="pull-left">&copy; <?php echo $___nombre_del_sitio; ?> <?php echo date("Y"); ?>   La Paz - Bolivia</p>
                </div>
                <div class="col-xs-12  hidden-md hidden-lg" style="padding-top: 5px;margin-top: 5px;border-top:1px solid #FFF;">
                    <div class="col-xs-6 text-center"> <a href="<?php echo $dominio; ?>contacto.html" class="EnlacePoliticas">Contacto</a></div>
                    <div class="col-xs-6 text-center"> <a href="<?php echo $dominio; ?>quienes-somos.html" class="EnlacePoliticas">Quienes Somos</a></div>
                    <br/>
                </div>
            </div>
        </div>
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

<!-- AJAX cerrar_sesion -->
<script>
    function cerrar_sesion() {
        $.ajax({
            url: 'pages/ajax/ajax.cerrar_sesion.php',
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                location.href='<?php echo $dominio_plataforma; ?>';
            }
        });
    }
</script>

</body>
</html>
