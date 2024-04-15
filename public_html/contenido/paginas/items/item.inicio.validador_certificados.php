<div style="background-color:#FFF">
    <section class="container">
        <hr/>

        <style>
            .box-valida-cert{
                background: #f7f7f7;
                padding: 5px 20px;
                border: 1px solid #258fad;
                margin-bottom: 20px;
            }
        </style>
        <div class="box-valida-cert">
            <div class="row">
                <form action="validacion-de-certificado.html" method="post" id="form-valida-cert">
                    <h2 class="titulo-second-f1-a" style="clear: both;">VALIDACI&Oacute;N DE CERTIFICADOS</h2>

                    <h4 class="text-center" style="color:#258fad;">M&aacute;s de 350 instituciones p&uacute;blicas han verificado nuestros cerficados.</h4>

                    <p>
                        Mediante el sistema de validaci&oacute;n de certificados, se podr&aacute; verificar la autenticidad del certificado correspondiente emitido para cada uno de los cursos realizados, estos pueden ser solicitados por la instituci&oacute;n o persona que lo requiera.
                        <br/>
                        Para ello ingrese el <b>'ID de certificado'</b> ubicado en la parte inferior del c&oacute;digo QR del certificado emitido. IMPORTANTE: La informaci&oacute;n v&aacute;lida es la generada en la pantalla.
                    </p>
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <hr/>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                            <input type="text" name="id_certificado" id="id_certificado" value="" class="form-control" placeholder="Ingrese el ID de certificado..." aria-describedby="basic-addon1" required=""/>
                            <span class="input-group-addon" onclick="send_form_valid();" style="cursor:pointer;"><i class="fa fa-eye"></i> VALIDAR</span>
                        </div>
                        <script>
                            function send_form_valid() {
                                if (document.getElementById('id_certificado').value !== '') {
                                    document.getElementById('form-valida-cert').submit();
                                }
                            }
                        </script>
                        <hr/>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>