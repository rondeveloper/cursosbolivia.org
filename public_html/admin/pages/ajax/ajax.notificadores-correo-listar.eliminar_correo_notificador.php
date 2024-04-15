<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}
$id_correo_notificador = post('id_correo_notificador');
$resultado1 = query("SELECT * FROM notificadores_de_correo WHERE id = '$id_correo_notificador' LIMIT 1");
$editar_correo_notificador = fetch($resultado1);
    ?>
    <div class="form-group">
        <p>
            A continuaci&oacute;n los datos del correo notificador a eliminar
        </p>

        <div class="panel panel-default">
            <form action="" method="post" id="eliminar-correo-notificador">
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">CORREO</label>
                            <div class="col-lg-9 col-md-9">
                                <input type="email" class="form-control form-cascade-control" name="correo" value="<?= $editar_correo_notificador['correo'] ?>" required placeholder="Correo..." autocomplete="off" readonly/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">USUARIO</label>
                            <div class="col-lg-9 col-md-9">
                                <input type="text" class="form-control form-cascade-control" name="user" value="<?= $editar_correo_notificador['user'] ?>" required placeholder="Usuario..." autocomplete="off" readonly/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">CONTRASE&Ntilde;A</label>
                            <div class="col-lg-9 col-md-9">	
                            <input type="text" class="form-control form-cascade-control" name="password" value="<?= $editar_correo_notificador['password'] ?>" required placeholder="Contrase&ntilde;a..." autocomplete="off" readonly/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">NOMBRE REMITENTE</label>
                            <div class="col-lg-9 col-md-9">
                                <input type="text" class="form-control form-cascade-control" name="nombre_remitente" value="<?= $editar_correo_notificador['nombre_remitente'] ?>" required placeholder="Nombre Remitente..." autocomplete="off" readonly/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">DESCRIPCION</label>
                            <div class="col-lg-9 col-md-9">
                                <textarea class="form-control form-cascade-control" name="descripcion"  required placeholder="Descripcion..." autocomplete="off" readonly><?= $editar_correo_notificador['descripcion'] ?></textarea>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">CIFRADO</label>
                            <div class="col-lg-9 col-md-9">	
                            <input type="text" class="form-control form-cascade-control" name="cifrado" value="<?= $editar_correo_notificador['cifrado'] ?>" required placeholder="Cifrado..." autocomplete="off" readonly/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">PUERTO</label>
                            <div class="col-lg-9 col-md-9">
                                <input type="number" class="form-control form-cascade-control" name="puerto" value="<?= $editar_correo_notificador['puerto'] ?>" required placeholder="Puerto..." autocomplete="off" readonly/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label text-primary">HOST</label>
                            <div class="col-lg-9 col-md-9">
                                <input type="text" class="form-control form-cascade-control" name="host" value="<?= $editar_correo_notificador['host'] ?>" required placeholder="Host..." autocomplete="off" readonly/>
                                <br/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <input type="hidden" value="<?= $id_correo_notificador ?>" name="id_correo_notificador">
                    <input type="submit" class="btn btn-danger btn-sm btn-animate-demo" style="display:block;margin-right:auto !important;margin-left:auto !important;" value="ELIMINAR CORREO NOTIFICADOR"/>
                </div>
            </form>
        </div>
    </div>
    <script>
        $('#eliminar-correo-notificador').on('submit',function(e){
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('_token', $('input[name=_token]').val());
            $.ajax({
                type: 'POST',
                url: 'pages/ajax/ajax.notific-correo.eliminar_correo_notificador.php',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    alert('Exito, correo notificador eliminado')
                    window.location.href = "notificadores-correo-listar.adm"
                    }
                });
        })
    </script>
