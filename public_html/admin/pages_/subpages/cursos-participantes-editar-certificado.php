<?php

$mensaje = '';

$certificado_id = $get[2];

if (isset_post('actualizar-datos')) {
    
    $nombre = trim(post('nombre'));
   
    query("UPDATE cursos_emisiones_certificados SET 
                 receptor_de_certificado='$nombre' WHERE certificado_id='$certificado_id' LIMIT 1");
    
    $mensaje .= "<h4>Datos actualizados!</h4>";
    
}


$recp1 = query("SELECT * FROM cursos_emisiones_certificados WHERE certificado_id='$certificado_id' LIMIT 1 ");
$certificado = fetch($recp1);

?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li class="active">Edici&oacute;n de certificado</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <form action="" method="post">
                <input type="text" name="buscar" class="form-control form-cascade-control " size="20" placeholder="Buscar en el Sitio">
                <span class="input-icon fui-search"></span>
            </form>
        </div>
        <h3 class="page-header"> Edici&oacute;n de certificado <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Edici&oacute;n de certificado.
            </p>
        </blockquote>
    </div>
</div>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            


            <div class="panel-body">

                <form enctype="multipart/form-data" action="" method="post">
                    <table style="margin:auto;width:70%;">
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Receptor de certificado: </span>
                                    <input type="text" name="nombre" value="<?php echo $certificado['receptor_de_certificado']; ?>" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        
                        
                        <tr>
                            <td colspan="2">
                                <div style="text-align: center;padding:20px;">
                                    <input type="submit" value="ACTUALIZAR DATOS" name="actualizar-datos" class="btn btn-success btn-lg btn-animate-demo"/>
                                </div>
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="2">
                                <br/>
                                <b>Receptor:</b> <?php echo $certificado['receptor_de_certificado']; ?>
                                <br/>
                                <br/>
                                <b>ID de certificado:</b> <?php echo $certificado_id; ?>
                                <br/>
                                <br/>
                                <b>Visualizacion:</b> 
                                
                                <a onclick="window.open('http://www.infosicoes.com/contenido/paginas/procesos/pdfs/certificado-2.php?id_certificado=<?php echo $certificado_id; ?>', 'popup', 'width=700,height=500');" class="btn btn-xs btn-warning">CERTIFICADO</a>
                                
                            </td>
                        </tr>
                        
                        
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>




