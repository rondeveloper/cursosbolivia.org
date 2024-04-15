<?php
//id pagina
$id_registro = (int) $get[2];

$mensaje = '';

/* editar registro */
if (isset_post('actualizar')) {

    $descripcion = post('descripcion');
    $cont_titulo = post('cont_titulo');
    $cont_uno = post('cont_uno');
    $cont_dos = post('cont_dos');
    $id_firma1 = post('id_firma1');
    $id_firma2 = post('id_firma2');

    query("UPDATE cursos_modelos_certificados SET 
          descripcion='$descripcion', 
          cont_titulo='$cont_titulo', 
          cont_uno='$cont_uno', 
          cont_dos='$cont_dos', 
          id_firma1='$id_firma1', 
          id_firma2='$id_firma2' 
           WHERE id='$id_registro' LIMIT 1 ");
    
    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> Registro editado correctamente.
</div>';
}


$resultado_paginas = query("SELECT * FROM cursos_modelos_certificados WHERE id='$id_registro' ORDER BY id DESC limit 1 ");
$curso = fetch($resultado_paginas);

?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li class="active">Edici&oacute;n de modelo de certificado</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <h3 class="page-header"> Modelo de certificado - Edici&oacute;n de datos</h3>
        <blockquote class="page-information hidden">
            <p>
                Edici&oacute;n de modelo de certificado
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

                <div class="panel panel-primary">
                    <div class="panel-heading">Modelo de certificado - <?php echo $curso['descripcion']; ?></div>
                    <div class="panel-body">


                        <form enctype="multipart/form-data" action="" method="post">
                            <table style="width:100%;" class="table table-striped">
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; Codigo Certificado: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="" value="<?php echo $curso['codigo']; ?>" class="form-control" id="date" readonly="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; Descripci&oacute;n: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="descripcion" value="<?php echo $curso['descripcion']; ?>" class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; T&iacute;tulo principal: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="cont_titulo" value="<?php echo $curso['cont_titulo']; ?>" class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; Contenido Uno: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="cont_uno" value='<?php echo $curso['cont_uno']; ?>' class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; Contenido Dos: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="cont_dos" value='<?php echo $curso['cont_dos']; ?>' class="form-control" id="date">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; Firma 1 </span>
                                    </td>
                                    <td>
                                        <select name="id_firma1" class="form-control">
                                            <?php
                                            $rqfc1 = query("SELECT * FROM cursos_certificados_firmas WHERE estado='1' ");
                                            while ($rqfc2 = fetch($rqfc1)) {
                                                $selected = "";
                                                if($curso['id_firma1']==$rqfc2['id']){
                                                    $selected = " selected='selected' ";
                                                }
                                                ?>
                                                <option value="<?php echo $rqfc2['id']; ?>" <?php echo $selected; ?> ><?php echo $rqfc2['nombre'] . ' | ' . $rqfc2['cargo']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; Firma 2 </span>
                                    </td>
                                    <td>
                                        <select name="id_firma2" class="form-control">
                                            <?php
                                            $rqfc1 = query("SELECT * FROM cursos_certificados_firmas WHERE estado='1' ");
                                            while ($rqfc2 = fetch($rqfc1)) {
                                                $selected = "";
                                                if($curso['id_firma2']==$rqfc2['id']){
                                                    $selected = " selected='selected' ";
                                                }
                                                ?>
                                                <option value="<?php echo $rqfc2['id']; ?>" <?php echo $selected; ?> ><?php echo $rqfc2['nombre'] . ' | ' . $rqfc2['cargo']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td colspan="2">
                                        <div style="text-align: center;padding:20px;">
                                            <input type="submit" name="actualizar" value="ACTUALIZAR DATOS" class="btn btn-success btn-lg btn-animate-demo active"/>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<!-- Modal-1 -->
<div id="MODAL-asignar-certificado" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ASIGNACI&Oacute;N DE CERTIFICADO AL CURSO</h4>
            </div>
            <div class="modal-body">
                <p>
                    Una vez llene el siguiente formulario el curso '<?php echo $curso['titulo']; ?>' sera habilitado para emitir certificados a los participantes inscriptos.
                </p>
                <hr/>
                <form action='' method='post'>
                    <div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>TITULO:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="titulo_certificado" value="CERTIFICADO DE PARTICIPACION"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 1:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo utf8_encode("Por cuanto se reconoce que completo satisfactoriamente el curso de capacitación:"); ?>'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 2:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <input type="text" class="form-control" name="contenido_dos_certificado" value='"<?php echo $curso['titulo']; ?>", con una carga horaria de 8 horas.'/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <span class="input-group-addon"><b>CONT. 3:</b></span>
                            </div>
                            <div class="col-md-9 text-left">
                                <?php
                                $dia_curso = date("d",strtotime($curso['fecha']));
                                $mes_curso = date("m",strtotime($curso['fecha']));
                                $array_meses = array("None","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                                $rqcc1 = query("SELECT nombre FROM departamentos WHERE id='".$curso['id_ciudad']."' LIMIT 1 ");
                                $rqcc2 = fetch($rqcc1);
                                ?>
                                <input type="text" class="form-control" name="contenido_tres_certificado" value="Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int)$mes_curso]; ?> de 2017"/>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="text-center">
                        <input type='submit' name='habilitar-certificado' class="btn btn-success" value="HABILITAR CERTIFICADO"/>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                    </div>

                </form>

                <hr/>                                        

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-1 -->

