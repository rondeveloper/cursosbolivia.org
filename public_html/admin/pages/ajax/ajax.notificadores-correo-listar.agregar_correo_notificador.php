<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}
    ?>
    <div class="form-group">
        <p>
            A continuaci&oacute;n ingresa los datos del correo notificador
        </p>

        <div class="panel panel-default">
            <form action="" method="post" id="agregar-correo-notificador">
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">CORREO</label>
                            <div class="col-lg-9 col-md-9">
                                <input type="email" class="form-control form-cascade-control" name="correo" value="" required placeholder="Correo..." autocomplete="off"/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">USUARIO</label>
                            <div class="col-lg-9 col-md-9">
                                <input type="text" class="form-control form-cascade-control" name="user" value="" required placeholder="Usuario..." autocomplete="off"/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">CONTRASE&Ntilde;A</label>
                            <div class="col-lg-9 col-md-9">	
                            <input type="text" class="form-control form-cascade-control" name="password" value="" required placeholder="Contrase&ntilde;a..." autocomplete="off"/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE REMITENTE</label>
                            <div class="col-lg-9 col-md-9">
                                <input type="text" class="form-control form-cascade-control" name="nombre_remitente" value="" required placeholder="Nombre Remitente..." autocomplete="off"/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCION</label>
                            <div class="col-lg-9 col-md-9">
                                <textarea class="form-control form-cascade-control" name="descripcion" value="" required placeholder="Descripcion..." autocomplete="off"></textarea>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">CIFRADO</label>
                            <div class="col-lg-9 col-md-9">	
                            <input type="text" class="form-control form-cascade-control" name="cifrado" value="" required placeholder="Cifrado..." autocomplete="off"/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">PUERTO</label>
                            <div class="col-lg-9 col-md-9">
                                <input type="number" class="form-control form-cascade-control" name="puerto" value="" required placeholder="Puerto..." autocomplete="off"/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">HOST</label>
                            <div class="col-lg-9 col-md-9">
                                <input type="text" class="form-control form-cascade-control" name="host" value="" required placeholder="Host..." autocomplete="off"/>
                                <br/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <input type="submit" class="btn btn-success btn-sm btn-animate-demo" style="display:block;margin-right:auto !important;margin-left:auto !important;" value="AGREGAR CORREO NOTIFICADOR"/>
                </div>
            </form>
        </div>
    </div>
    <script>
        $('#agregar-correo-notificador').on('submit',function(e){
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('_token', $('input[name=_token]').val());
            $.ajax({
                type: 'POST',
                url: 'pages/ajax/ajax.notific-correo.agregar_correo_notificador.php',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    alert('Exito, correo notificador agregado')
                    window.location.href = "notificadores-correo-listar.adm"
                    }
                });
        })
    </script>
