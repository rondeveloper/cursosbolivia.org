<?php
//acceso('1');

$mensaje = '';

$r1 = query("SELECT * FROM administradores WHERE id='" . administrador('id') . "'");
$r2 = fetch($r1);
?>



<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a >Configuraci&oacute;n</a></li>
            <li class="active">Mi Cuenta</li>
        </ul>       
        <h3 class="page-header">
            <i class="fa fa-indent"></i> Datos de la cuenta <i class="fa fa-info-circle animated bounceInDown show-info"></i>
        </h3>
    </div>
</div>

<?php echo $mensaje; ?>

<?php //editorTinyMCE('editor');   ?>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-cascade">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Cuenta de <?php echo administrador('empresa'); ?>
                    <span class="pull-right">
                        <a href="#" class="panel-minimize"><i class="fa fa-chevron-up"></i></a>
                    </span>
                </h3>
            </div>
            <div class="panel-body">
                <div class="form-group has-success">
                    <label class="col-lg-2 col-md-3 control-label  text-success">Nombres</label>
                    <div class="col-lg-10 col-md-9">
                        <input type="text" class="form-control form-cascade-control" name="nombre" value="<?php echo $r2['nombre']; ?>" id="datepicker" disabled>
                        <br/>
                    </div>
                </div>
                <div style="clear: both;"></div>
                <div class="form-group has-success">
                    <label class="col-lg-2 col-md-3 control-label  text-success">Correo Electr&oacute;nico</label>
                    <div class="col-lg-10 col-md-9">
                        <input type="text" class="form-control form-cascade-control" name="email" value="<?php echo $r2['email']; ?>" id="datepicker" disabled>
                        <br/>
                    </div>
                </div>
                <div style="clear: both;"></div>
                <div class="form-group has-success">
                    <label class="col-lg-2 col-md-3 control-label  text-success">Nickname</label>
                    <div class="col-lg-10 col-md-9">
                        <input type="text" class="form-control form-cascade-control" name="nick" value="<?php echo $r2['nick']; ?>" id="datepicker" disabled>
                        <br/>
                    </div>
                </div>
                <div style="clear: both;"></div>
                <div class="form-group has-success">
                    <label class="col-lg-2 col-md-3 control-label  text-success">Contrase&ntilde;a</label>
                    <div class="col-lg-10 col-md-9">
                        <input type="password" class="form-control form-cascade-control" name="password" value="<?php echo $r2['password']; ?>" id="datepicker" disabled>
                        <br/>
                    </div>
                </div>
                <div style="clear: both;"></div>

            </div>
        </div>
    </div>
</div>

<div class="panel-footer">
    <div class="row">
        <div class="col-sm-12 col-sm-offset-4">
            <a href="mi-cuenta-editar.adm">
                EDITAR DATOS DE ACCESO
            </a>
        </div>
    </div>
</div>