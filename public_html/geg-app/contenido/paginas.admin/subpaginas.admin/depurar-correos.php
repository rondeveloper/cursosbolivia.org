<?php

/* mensaje */
$mensaje = "";

/* depurar-correos */
if (isset_post('depurar-correos')) {
    $busc = array(' ',',',';','\r\n','\r','\n');
    $correos = str_replace($busc,'___separator__',trim(post('correos')));
    $arr_correos = explode('___separator__',$correos);
    $cnt = 0;
    foreach ($arr_correos as $correo) {
        if(trim($correo)!=''){
            $cnt++;
            $mensaje .= $correo.' <b><i>DEPURADO</i></b><br/>';
            query("UPDATE cursos_participantes SET sw_notif='0' WHERE correo LIKE '$correo' ");
            query("UPDATE cursos_usuarios SET sw_notif='0' WHERE email LIKE '$correo' ");
        }
    }
    $mensaje = '<div class="alert alert-success">
  <strong>EXITO</strong> '.$cnt.' correos depurados.
</div>'.$mensaje;
    
}
?>


<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li class="active">DEPURACION DE CORREOS</li>
        </ul>       
        <h3 class="page-header">
            <i class="fa fa-indent"></i> DEPURACION DE CORREOS <i class="fa fa-info-circle animated bounceInDown show-info"></i>
        </h3>
    </div>
</div>

<?php echo $mensaje; ?>

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    DEPURACION DE CORREOS
                    <span class="pull-right">
                        <a class="panel-minimize"><i class="fa fa-info"></i></a>
                    </span>
                </h3>
            </div>
            <form action="" method="post">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nombre">Correos</label> *
                                        <textarea name="correos" class="form-control" placeholder="Correos separados por comas, espacios o saltos de linea" required="" style="height: 300px;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <input type="submit" name="depurar-correos" class="btn btn-success btn-sm btn-block" value="DEPURAR"/>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>





