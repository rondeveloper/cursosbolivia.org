<?php
$mensaje = '';

/* crear registro */
if (isset_post('registrar')) {

    $descripcion = post('descripcion');
    $cont_titulo = post('cont_titulo');
    $cont_uno = post('cont_uno');
    $cont_dos = post('cont_dos');
    $id_firma1 = post('id_firma1');
    $id_firma2 = post('id_firma2');

    query("INSERT INTO cursos_modelos_certificados(
          descripcion, 
          cont_titulo, 
          cont_uno, 
          cont_dos, 
          id_firma1, 
          id_firma2, 
          estado
          ) VALUES (
          '$descripcion',
          '$cont_titulo',
          '$cont_uno',
          '$cont_dos',
          '$id_firma1',
          '$id_firma2',
          '1'
          )");

    $rqmci1 = query("SELECT id FROM cursos_modelos_certificados WHERE descripcion='$descripcion' ORDER BY id DESC limit 1 ");
    $rqmci2 = mysql_fetch_array($rqmci1);

    $codd = "MDC" . str_pad($rqmci2['id'], 3, "0", STR_PAD_LEFT);
    query("UPDATE cursos_modelos_certificados SET codigo='$codd' WHERE id='" . $rqmci2['id'] . "' ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> Registro agregado correctamente.
</div>';
}

$resultado_paginas = query("SELECT * FROM cursos_modelos_certificados WHERE id='$id_registro' ORDER BY id DESC limit 1 ");
$curso = mysql_fetch_array($resultado_paginas);

$array_meses = array('None', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li class="active">Registro de modelo de certificado</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">

        </div>
        <h3 class="page-header"> Modelo de certificado - Nuevo registro</h3>
        <blockquote class="page-information hidden">
            <p>
                Registro de modelo de certificado
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
                    <div class="panel-heading">Modelo de certificado</div>
                    <div class="panel-body">


                        <form action="" method="post">
                            <table style="width:100%;" class="table table-striped">
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; Descripci&oacute;n: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="descripcion" value="<?php echo $curso['descripcion']; ?>" class="form-control" id="date" required="" placeholder="Ingresa una descripci&oacute;n del certificado...">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; T&iacute;tulo principal: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="cont_titulo" value="CERTIFICADO DE PARTICIPACIÓN" class="form-control" id="date" required="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; Contenido Uno: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="cont_uno" value='Por cuanto se reconoce que completó satisfactoriamente el curso de capacitación:' class="form-control" id="date" required="">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="input-group-addon"><i class="fa fa-tags"></i> &nbsp; Contenido Dos: </span>
                                    </td>
                                    <td>
                                        <input type="text" name="cont_dos" value='<?php echo utf8_encode("\"Curso Ley 1178 en La Paz\", con una carga horaria de 10 horas.") ?>' class="form-control" id="date" required="">
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
                                            while ($rqfc2 = mysql_fetch_array($rqfc1)) {
                                                ?>
                                                <option value="<?php echo $rqfc2['id']; ?>"><?php echo $rqfc2['nombre'] . ' | ' . $rqfc2['cargo']; ?></option>
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
                                            while ($rqfc2 = mysql_fetch_array($rqfc1)) {
                                                ?>
                                                <option value="<?php echo $rqfc2['id']; ?>"><?php echo $rqfc2['nombre'] . ' | ' . $rqfc2['cargo']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2">
                                        <div style="text-align: center;padding:20px;">
                                            <input type="submit" name="registrar" value="REGISTRAR MODELO DE CERTIFICADO" class="btn btn-success btn-lg btn-animate-demo active"/>
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
                                <input type="text" class="form-control" name="contenido_uno_certificado" value='<?php echo utf8_encode("Por cuanto se reconoce que completo satisfactoriamente el curso de capacitaci�n:"); ?>'/>
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
                                $dia_curso = date("d", strtotime($curso['fecha']));
                                $mes_curso = date("m", strtotime($curso['fecha']));
                                $array_meses = array("None", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                                $rqcc1 = query("SELECT nombre FROM departamentos WHERE id='" . $curso['id_ciudad'] . "' LIMIT 1 ");
                                $rqcc2 = mysql_fetch_array($rqcc1);
                                ?>
                                <input type="text" class="form-control" name="contenido_tres_certificado" value="Realizado en <?php echo $rqcc2['nombre']; ?>, Bolivia a los <?php echo $dia_curso; ?> dias del mes de <?php echo $array_meses[(int) $mes_curso]; ?> de 2017"/>
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

