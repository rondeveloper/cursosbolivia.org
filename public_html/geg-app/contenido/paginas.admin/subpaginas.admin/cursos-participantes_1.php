<?php
$mensaje = "";

//id de curso
$id_curso = 0;
if (isset($get[2])) {
    $id_curso = (int) $get[2];
}

//vista
$vista = 1;
if (isset($get[3])) {
    $vista = (int) $get[3];
}

//limitado de registros
$registros_a_mostrar = 150;
$start = abs($vista - 1) * $registros_a_mostrar;

$sw_selec = false;


if (isset_post('buscarr') || isset($get[5])) {
    $sw_busqueda = true;
    if (isset_post('buscar')) {
        $buscar = post('buscar');
    } else {
        $buscar = $get[5];
    }
} else {
    $sw_busqueda = false;
}


//agregado de participante
if (isset_post('agregar-participante')) {

    $prefijo = post('prefijo');
    $nombres = post('nombres');
    $apellidos = post('apellidos');
    $celular = post('celular');
    $correo = post('correo');
    $observacion = post('observacion');
    $razon_social = post('razon_social');
    $nit = post('nit');
    $monto_pago = post('monto_pago');

    if ((strlen(post('nombres')) > 3) && (strlen(post('apellidos')) > 3)) {


        /* verificacion de existencia */
        $rqpcv1 = query("SELECT id FROM cursos_participantes WHERE nombres='$nombres' AND apellidos='$apellidos' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
        if (mysql_num_rows($rqpcv1) > 0) {
            echo "<script>alert('Registro ya existente!');history.back();</script>";
            exit;
        }


        $cod_reg = substr("RM-$id_curso-" . str_replace(" ", "-", $nombres), 0, 14);
        $fecha_registro = date("Y-m-d H:i:s");

        query("INSERT INTO cursos_proceso_registro(
                      id_curso, 
                      id_modo_de_registro,
                      cod_reg, 
                      metodo_de_pago, 
                      cnt_participantes, 
                      razon_social, 
                      nit, 
                      monto_deposito, 
                      fecha_registro, 
                      estado
                      ) VALUES (
                      '$id_curso',
                      '2',
                      '$cod_reg',
                      'pago-en-oficina',
                      '1',
                      '$razon_social',
                      '$nit',
                      '$monto_pago',
                      '$fecha_registro',
                      '1'
                      )");
        $rqcr1 = query("SELECT id FROM cursos_proceso_registro WHERE cod_reg='$cod_reg' ORDER BY id DESC limit 1 ");
        $rqcr2 = mysql_fetch_array($rqcr1);
        $id_proceso_registro = $rqcr2['id'];
        $codigo_registro = "RM00" . $id_proceso_registro;
        query("UPDATE cursos_proceso_registro SET codigo='$codigo_registro' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

        query("INSERT INTO cursos_participantes (
                      id_curso,
                      id_proceso_registro,
                      prefijo,
                      nombres,
                      apellidos,
                      celular,
                      correo,
                      observacion
                      ) VALUES (
                      '$id_curso',
                      '$id_proceso_registro',
                      '$prefijo',
                      '$nombres',
                      '$apellidos',
                      '$celular',
                      '$correo',
                      '$observacion'
                      ) ");

        $rqpc1 = query("SELECT id FROM cursos_participantes WHERE nombres='$nombres' AND apellidos='$apellidos' ORDER BY id DESC limit 1 ");
        $rqpc2 = mysql_fetch_array($rqpc1);
        movimiento('Agregado de participante a curso [curso: ' . $id_curso . '] ', 'agregado-participante-curso', 'participante-curso', $rqpc2['id']);
    }
}

/* edicion de participante */
if (isset_post('editar-participante')) {

    $prefijo = post('prefijo');
    $nombres = ucfirst(trim(post('nombres')));
    $apellidos = ucfirst(trim(post('apellidos')));

    $celular = post('celular');
    $correo = post('correo');

    $razon_social = post('razon_social');
    $nit = post('nit');
    $monto_pago = post('monto_pago');

    $id_curso = post('id_curso');
    $id_participante = post('id_participante');

    /* edicion de datos de participante */
    query("UPDATE cursos_participantes SET 
            prefijo='$prefijo',
            nombres='$nombres',
            apellidos='$apellidos',
            celular='$celular',
            correo='$correo'
             WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

    /* edicion de datos de registro */
    $rqdr1 = query("SELECT id_proceso_registro FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdr2 = mysql_fetch_array($rqdr1);
    $id_proceso_registro = $rqdr2['id_proceso_registro'];

    query("UPDATE cursos_proceso_registro SET 
            razon_social='$razon_social',
            nit='$nit',
            monto_deposito='$monto_pago' 
            WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> Participante editado correctamente.
</div>';
}




$resultado1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' ORDER BY id DESC ");

//datos del curso
$rqc1 = query("SELECT titulo,fecha,imagen,id_certificado,id_certificado_2,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = mysql_fetch_array($rqc1);
$nombre_del_curso = $rqc2['titulo'];
$fecha_del_curso = $rqc2['fecha'];
$codigo_de_certificado_del_curso = $rqc2['codigo_certificado'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
if ($rqc2['imagen'] !== '') {
    $url_imagen_del_curso = "https://www.infosicoes.com/paginas/" . $rqc2['imagen'] . ".size=4.img";
} else {
    $url_imagen_del_curso = "https://www.infosicoes.com/images/banner-cursos.png.size=4.img";
}


$cnt = mysql_num_rows($resultado1);
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="cursos-listar.adm">P&aacute;ginas de Cursos</a></li>
            <li class="active">Participantes</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <form action="" method="post">
                <input type="text" name="buscar" class="form-control form-cascade-control " size="20" placeholder="Buscar en el Sitio">
                <span class="input-icon fui-search"></span>
            </form>
        </div>
        <h3 class="page-header"> Participantes del Curso <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de cursos de Cursos
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
                <table class="table" style="background:#EEE;border-radius:5px;">
                    <form action="" method="post">
                        <tr>
                            <td class="text-right" style="width: 120px;"><input type="text" name="prefijo" class="form-control" style="width:110px;" placeholder="Sr. / Dr. / Arq. / Ing. " /></td>
                            <td><input type="text" name="nombres" class="form-control" placeholder="Nombres" /></td>
                            <td><input type="text" name="apellidos" class="form-control" placeholder="Apellidos" /></td>
                            <td><input type="text" name="celular" class="form-control" placeholder="Celular" /></td>
                            <td><input type="submit" name="agregar-participante" class="btn btn-success active" value="AGREGAR PARTICIPANTE"/></td>
                        </tr>
                        <tr>
                            <td class="text-right" style="width: 120px;"><input type="number" name="monto_pago" class="form-control" style="width:110px;" placeholder="Monto..." /></td>
                            <td><input type="text" name="razon_social" class="form-control" placeholder="Razon Social" /></td>
                            <td><input type="text" name="nit" class="form-control" placeholder="NIT" /></td>
                            <td><input type="text" name="correo" class="form-control" placeholder="Correo" /></td>
                            <td></td>
<!--                            <td><input type="text" name="observacion" class="form-control" placeholder="Observaciones" /></td>-->
                        </tr>
                    </form>
                </table>
            </div>

            <?php echo "<h5>$nombre_del_curso | $fecha_del_curso</h5>"; ?>

            <?php
            if (mysql_num_rows($resultado1) == 0) {
                echo "<p>No se registraron participantes para este curso</p>";
            }
            ?>

            <div class="panel-body">
                <table class="table users-table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th class="visible-lg">#</th>
                            <th class="visible-lg">Participante</th>
                            <th class="visible-lg">Facturaci&oacute;n</th>
                            <th class="visible-lg">Modo registro</th>
                            <th class="visible-lg">Fecha registro</th>
                            <th class="visible-lg">Contacto</th>
                            <th class="visible-lg">Acci&oacute;n</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        while ($participante = mysql_fetch_array($resultado1)) {

                            /* datos de registro */
                            $rqrp1 = query("SELECT codigo,fecha_registro,celular_contacto,correo_contacto,metodo_de_pago,id_modo_de_registro,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
                            $data_registro = mysql_fetch_array($rqrp1);
                            $codigo_de_registro = $data_registro['codigo'];
                            $fecha_de_registro = $data_registro['fecha_registro'];
                            $celular_de_registro = $data_registro['celular_contacto'];
                            $correo_de_registro = $data_registro['correo_contacto'];
                            $nro_participantes_de_registro = $data_registro['cnt_participantes'];
                            $id_modo_de_registro = $data_registro['id_modo_de_registro'];
                            $id_emision_factura = $data_registro['id_emision_factura'];

                            $metodo_de_pago = $data_registro['metodo_de_pago'];
                            $monto_de_pago = $data_registro['monto_deposito'];
                            $imagen_de_deposito = $data_registro['imagen_deposito'];

                            $razon_social_de_registro = $data_registro['razon_social'];
                            $nit_de_registro = $data_registro['nit'];
                            ?>
                            <tr id="ajaxbox-tr-participante-<?php echo $participante['id']; ?>">
                                <td class="visible-lg"><?php echo $cnt--; ?></td>
                                <td class="visible-lg">
                                    <?php
                                    echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']);
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo $razon_social_de_registro;
                                    echo "<br/>";
                                    echo $nit_de_registro;
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    if ($id_modo_de_registro == '1' || $id_modo_de_registro == '0') {
                                        echo "Sistema";
                                    } elseif ($id_modo_de_registro == '2') {
                                        echo "Administrador";
                                    }
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo date("d / M H:i", strtotime($fecha_de_registro));
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo $participante['celular'];
                                    echo "<br/>";
                                    echo $participante['correo'];
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <a data-toggle="modal" data-target="#MODAL-edicion-<?php echo $participante['id']; ?>" onclick="" class="btn btn-xs btn-info"> <i class="fa fa-edit"></i> </a>
                                    &nbsp;
                                    <a data-toggle="modal" data-target="#MODAL-datos-registro-<?php echo $participante['id']; ?>" onclick="" class="btn btn-xs btn-success">Inscripci&oacute;n</a>
                                    &nbsp;
                                    <?php
                                    if ($id_certificado_curso == '0') {
                                        echo "<a onclick='alert(\"Curso no habilitado para emitir certificados!\")' class='btn btn-xs btn-info'>Emitir certificado</a>";
                                    } else {
                                        if ($participante['id_emision_certificado'] == '0') {
                                            ?>
                                            <span id='box-modal_emision_certificado-button-<?php echo $participante['id']; ?>'>
                                                <a data-toggle="modal" data-target="#MODAL-emite-certificado-p1-<?php echo $participante['id']; ?>" onclick="" class="btn btn-xs btn-info">Certificado</a>
                                            </span>
                                            <?php
                                        } else {
                                            ?>
                                            <a onclick="window.open('http://www.infosicoes.com/contenido/librerias/fpdf/tutorial/certificado-2.php?id_certificado=<?php echo $rqidc2['certificado_id']; ?>', 'popup', 'width=700,height=500');" class="btn btn-xs btn-warning">Certificado</a>
                                            <?php
                                        }

                                        /* segundo certificado */
                                        if ($id_certificado_2_curso !== '0') {
                                            if ($participante['id_emision_certificado_2'] == '0') {
                                                ?>
                                                <span id='box-modal_emision_certificado-button-2-<?php echo $participante['id']; ?>'>
                                                    <a data-toggle="modal" data-target="#MODAL-emite-certificado-2-p1-<?php echo $participante['id']; ?>" onclick="" class="btn btn-xs btn-info">2do certificado</a>
                                                </span>
                                                <?php
                                            } else {
                                                ?>
                                                <a onclick="window.open('http://www.infosicoes.com/contenido/librerias/fpdf/tutorial/certificado-2.php?id_certificado=<?php echo $rqidcc2['certificado_id']; ?>', 'popup', 'width=700,height=500');" class="btn btn-xs btn-warning">2do certificado</a>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                    &nbsp;&nbsp;|&nbsp;&nbsp;            
                                    <span id="ajaxbox-button-eliminar-participante-<?php echo $participante['id']; ?>">
                                        <a onclick="eliminar_participante(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-danger"> X </a>
                                    </span>
                                </td>

                                <!-- Modal-1 -->
                        <div id="MODAL-emite-certificado-p1-<?php echo $participante['id']; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">EMISION DE CERTIFICADO</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                                                <br/>
                                                <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                                                <br/>
                                                <b>CERTIFICADO:</b> <?php echo $codigo_de_certificado_del_curso; ?>
                                                <br/>
                                                <b>PARTICIPANTE:</b> <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-md-12 text-left">
                                                <h3 class="text-center">
                                                    <?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>
                                                </h3>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="text-center" id='box-modal_emision_certificado-<?php echo $participante['id']; ?>'>
                                            <h5 class="text-center">
                                                Emision de certificado para
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-12 text-left">
                                                    <input type="text" class="form-control text-center" id="receptor_de_certificado-<?php echo $participante['id']; ?>" value="<?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>"/>
                                                </div>
                                            </div>
                                            <br/>
                                            <br/>
                                            <input type="hidden" id="id_certificado-<?php echo $participante['id']; ?>" value="<?php echo $id_certificado_curso; ?>" />
                                            <input type="hidden" id="id_curso-<?php echo $participante['id']; ?>" value="<?php echo $id_curso; ?>" />
                                            <input type="hidden" id="id_participante-<?php echo $participante['id']; ?>" value="<?php echo $participante['id']; ?>" />

                                            <button class="btn btn-success" onclick="curso_emitir_certificado('<?php echo $participante['id']; ?>');">EMITIR CERTIFICADO</button>
                                            &nbsp;&nbsp;&nbsp;
                                            <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                                        </div>
                                        <hr/>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal-1 -->

                        <?php
                        /* segundo certificado */
                        if ($id_certificado_2_curso !== '0') {

                            //datos del curso
                            $rqc_c2_1 = query("select codigo from cursos_certificados where id='$id_certificado_2_curso' ");
                            $rqc_c2_2 = mysql_fetch_array($rqc_c2_1);
                            $codigo_de_certificado_2_del_curso = $rqc_c2_2['codigo'];
                            ?>
                            <!-- Modal-2 -->
                            <div id="MODAL-emite-certificado-2-p1-<?php echo $participante['id']; ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">EMISION DE 2do CERTIFICADO</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 text-left">
                                                    <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                                                    <br/>
                                                    <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                                                    <br/>
                                                    <b>CERTIFICADO:</b> <?php echo $codigo_de_certificado_2_del_curso; ?>
                                                    <br/>
                                                    <b>PARTICIPANTE:</b> <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="row">
                                                <div class="col-md-12 text-left">
                                                    <h3 class="text-center">
                                                        <?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>
                                                    </h3>
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="text-center" id='box-modal_emision_certificado-2-<?php echo $participante['id']; ?>'>
                                                <h5 class="text-center">
                                                    Emision de certificado para
                                                </h5>
                                                <div class="row">
                                                    <div class="col-md-12 text-left">
                                                        <input type="text" class="form-control text-center" id="receptor_de_certificado-2-<?php echo $participante['id']; ?>" value="<?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>"/>
                                                    </div>
                                                </div>
                                                <br/>
                                                <br/>
                                                <input type="hidden" id="id_certificado-2-<?php echo $participante['id']; ?>" value="<?php echo $id_certificado_2_curso; ?>" />

                                                <button class="btn btn-success" onclick="curso_emitir_certificado_2('<?php echo $participante['id']; ?>');">EMITIR 2do CERTIFICADO</button>
                                                &nbsp;&nbsp;&nbsp;
                                                <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                                            </div>
                                            <hr/>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal-2 -->
                            <?php
                        }
                        ?>

                        <!-- Modal-3 -->
                        <div id="MODAL-datos-registro-<?php echo $participante['id']; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">DATOS DE REGISTRO</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <b>CURSO:</b> &nbsp; <?php echo $nombre_del_curso; ?>
                                                <br/>
                                                <b>FECHA:</b> &nbsp; <?php echo $fecha_del_curso; ?>
                                                <br/>
                                                <b>REGISTRO:</b> &nbsp; <?php echo $codigo_de_registro; ?>
                                                <br/>
                                                <b>PARTICIPANTE:</b> &nbsp; <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-md-12 text-left">
                                                <h3 class="text-center">
                                                    <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
                                                </h3>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-md-12 text-left">
                                                <b>FECHA DE REGISTRO:</b> &nbsp; <?php echo $fecha_de_registro; ?>
                                                <br/>
                                                <b>CELULAR CONTACTO:</b> &nbsp; <?php echo $celular_de_registro; ?>
                                                <br/>
                                                <b>CORREO CONTACTO:</b> &nbsp; <?php echo $correo_de_registro; ?>
                                                <br/>
                                                <b>REGISTRO:</b> &nbsp; <?php echo $codigo_de_registro; ?>
                                                <br/>
                                                <b>NRO. PARTICIPANTES:</b> &nbsp; <?php echo $nro_participantes_de_registro; ?>
                                                <br/>
                                                <?php
                                                if ($metodo_de_pago == 'deposito') {
                                                    ?>
                                                    <b>METODO DE PAGO:</b> &nbsp; DEPOSITO BANCARIO
                                                    <br/>
                                                    <b>MONTO PAGADO:</b> &nbsp; <?php echo $monto_de_pago; ?>
                                                    <br/>
                                                    <b>IMAGEN DEL DEPOSITO:</b> &nbsp; <a href="depositos/<?php echo $imagen_de_deposito; ?>.img" target="_blank"><?php echo $imagen_de_deposito; ?></a>
                                                    <br/>
                                                    <br/>
                                                    <img src="depositos/<?php echo $imagen_de_deposito; ?>.size=3.img" style="width:100%;border:1px solid #DDD;padding:1px;">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <b>METODO DE PAGO:</b> &nbsp; PAGO EN OFICINA
                                                    <br/>
                                                    <b>MONTO PAGADO:</b> &nbsp; <?php echo $monto_de_pago; ?>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <hr/>
                                        <?php
                                        if ($id_emision_factura == '0') {
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12 text-left">
                                                    <?php
                                                    $text_aux_solictud_factura = "NO SOLICITO FACTURA";
                                                    if (($razon_social_de_registro !== '') && ($nit_de_registro !== '')) {
                                                        $text_aux_solictud_factura = "SI SOLICITO FACTURA";
                                                    }
                                                    ?>
                                                    <b>Solicitud de factura :</b> &nbsp; <?php echo $text_aux_solictud_factura; ?>
                                                    <br/>
                                                    <b>Proceso de emision :</b> &nbsp; FACTURA NO EMITIDA
                                                    <br/>
                                                    <b>Factura a nombre de :</b> &nbsp; <?php echo $razon_social_de_registro; ?>
                                                    <br/>
                                                    <b>N&uacute;mero de NIT:</b> &nbsp; <?php echo $nit_de_registro; ?>
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="text-center" id='box-modal_emision_factura-<?php echo $participante['id']; ?>'>
                                                <div class="row">
                                                    <div class="col-md-4 text-right">
                                                        <span class="input-group-addon"><b>Facturar a nombre de :</b></span>
                                                    </div>
                                                    <div class="col-md-8 text-left">
                                                        <input type="text" class="form-control" id="nombre_a_facturar-<?php echo $participante['id']; ?>" value="<?php echo $razon_social_de_registro; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 text-right">
                                                        <span class="input-group-addon"><b>N&uacute;mero de NIT:</b></span>
                                                    </div>
                                                    <div class="col-md-8 text-left">
                                                        <input type="text" class="form-control" id="nit_a_facturar-<?php echo $participante['id']; ?>" value="<?php echo $nit_de_registro; ?>"/>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 text-right">
                                                        <span class="input-group-addon"><b>Monto a facturar:</b></span>
                                                    </div>
                                                    <div class="col-md-8 text-left">
                                                        <input type="text" class="form-control" id="monto_a_facturar-<?php echo $participante['id']; ?>" value="<?php echo $monto_de_pago; ?>"/>
                                                    </div>
                                                </div>

                                                <br/>
                                                <div class="text-center">
                                                    <button class="btn btn-success" onclick="curso_emitir_factura('<?php echo $participante['id']; ?>');">EMITIR FACTURA</button>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                                                </div>
                                            </div>

                                            <?php
                                        } else {
                                            $rqef1 = query("SELECT nro_factura,nombre_receptor,nit_receptor,total FROM facturas_emisiones WHERE id='$id_emision_factura' ORDER BY id DESC limit 1 ");
                                            $rqef2 = mysql_fetch_array($rqef1);
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12 text-left">
                                                    <b>Solicitud de factura :</b> &nbsp; SI SOLICITO FACTURA <?php echo $id_emision_factura; ?><-
                                                    <br/>
                                                    <b>Proceso de emision :</b> &nbsp; FACTURA EMITIDA
                                                    <br/>
                                                    <b>Facturado a nombre de :</b> &nbsp; <?php echo $rqef2['nombre_receptor']; ?>
                                                    <br/>
                                                    <b>N&uacute;mero de NIT:</b> &nbsp; <?php echo $rqef2['nit_receptor']; ?>
                                                    <br/>
                                                    <b>Monto facturado:</b> &nbsp; <?php echo $rqef2['total']; ?>
                                                    <br/>
                                                    <b>Visualizaci&oacute;n -> </b> <button type="button" class="btn btn-default btn-xs" onclick="window.open('http://www.infosicoes.com/contenido/librerias/fpdf/tutorial/factura-1.php?nro_factura=<?php echo $rqef2['nro_factura']; ?>', 'popup', 'width=700,height=500');">VISUALIZAR FACTURA</button>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>

                                        <hr/>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal-2 -->


                        <!-- Modal-5 -->
                        <div id="MODAL-edicion-<?php echo $participante['id']; ?>" class="modal fade" role="dialog">
                            <form action="" method="post">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">EDICION DE PARTICIPANTE</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 text-left">
                                                    <h3 class="text-center">
                                                        <?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>
                                                    </h3>
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="text-center" id='box-modal_emision_certificado-<?php echo $participante['id']; ?>'>
                                                <h5 class="text-center">
                                                    Participante Prefijo / Nombres / Apellidos
                                                </h5>
                                                <div class="row">
                                                    <div class="col-md-4 text-left">
                                                        <input type="text" class="form-control text-center" name="prefijo" value="<?php echo $participante['prefijo']; ?>" placeholder="Prefijo..."/>
                                                    </div>
                                                    <div class="col-md-4 text-left">
                                                        <input type="text" class="form-control text-center" name="nombres" value="<?php echo $participante['nombres']; ?>" placeholder="Nombres..."/>
                                                    </div>
                                                    <div class="col-md-4 text-left">
                                                        <input type="text" class="form-control text-center" name="apellidos" value="<?php echo $participante['apellidos']; ?>" placeholder="Apellidos..."/>
                                                    </div>
                                                </div>
                                                <h5 class="text-center">
                                                    Datos de facturaci&oacute;n Monto / Razon Social / NIT
                                                </h5>
                                                <div class="row">
                                                    <div class="col-md-4 text-left">
                                                        <input type="text" class="form-control text-center" name="monto_pago" value="<?php echo $monto_de_pago; ?>" placeholder="Monto..."/>
                                                    </div>
                                                    <div class="col-md-4 text-left">
                                                        <input type="text" class="form-control text-center" name="razon_social" value="<?php echo $razon_social_de_registro; ?>" placeholder="Razon Social..."/>
                                                    </div>
                                                    <div class="col-md-4 text-left">
                                                        <input type="text" class="form-control text-center" name="nit" value="<?php echo $nit_de_registro; ?>" placeholder="NIT..."/>
                                                    </div>
                                                </div>
                                                <h5 class="text-center">
                                                    Datos de contacto Celular / Correo
                                                </h5>
                                                <div class="row">
                                                    <div class="col-md-6 text-left">
                                                        <input type="text" class="form-control text-center" name="celular" value="<?php echo $participante['celular']; ?>" placeholder="Celular..."/>
                                                    </div>
                                                    <div class="col-md-6 text-left">
                                                        <input type="text" class="form-control text-center" name="correo" value="<?php echo $participante['correo']; ?>" placeholder="Correo..."/>
                                                    </div>
                                                </div>
                                                <br/>
                                                <br/>

                                                <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                                                <input type="hidden" name="id_participante" value="<?php echo $participante['id']; ?>" />

                                                <input type="submit" class="btn btn-success" name="editar-participante" value="ACTUALIZAR DATOS"/>
                                                &nbsp;&nbsp;&nbsp;
                                                <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
                                            </div>
                                            <hr/>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- End Modal-5 -->


                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<script>
    function show_v_objeto(objeto, id) {
        $.ajax({
            url: 'contenido/cursos.admin/ajax/ajax.show_v_objeto.php',
            data: {id: id, objeto: objeto},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#show-v-div-" + objeto + "-" + id).html(data);
            }
        });
    }
</script>

<!-- ajax de emision de certificados -->
<script>
    function curso_emitir_certificado(dat) {

        var receptor_de_certificado = $("#receptor_de_certificado-" + dat).val();
        var id_certificado = $("#id_certificado-" + dat).val();
        var id_curso = $("#id_curso-" + dat).val();
        var id_participante = $("#id_participante-" + dat).val();

        $("#box-modal_emision_certificado-" + dat).html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.curso_emitir_certificado.php',
            data: {receptor_de_certificado: receptor_de_certificado, id_certificado: id_certificado, id_curso: id_curso, id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificado-" + dat).html(data);
                $("#box-modal_emision_certificado-button-" + dat).html('<i class="btn-sm btn-default active">Emitido</i>');
            }
        });
    }
</script>


<!-- ajax de emision de certificados - segundo certificado -->
<script>
    function curso_emitir_certificado_2(dat) {

        var receptor_de_certificado = $("#receptor_de_certificado-2-" + dat).val();
        var id_certificado = $("#id_certificado-2-" + dat).val();
        var id_curso = $("#id_curso-" + dat).val();
        var id_participante = $("#id_participante-" + dat).val();

        $("#box-modal_emision_certificado-2-" + dat).html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.curso_emitir_certificado.php?segundo_certificado=true',
            data: {receptor_de_certificado: receptor_de_certificado, id_certificado: id_certificado, id_curso: id_curso, id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificado-2-" + dat).html(data);
                $("#box-modal_emision_certificado-button-2-" + dat).html('<i class="btn-sm btn-default active">Emitido</i>');
            }
        });
    }
</script>

<!-- ajax de emision de facturas -->
<script>
    function curso_emitir_factura(dat) {

        var nombre_a_facturar = $("#nombre_a_facturar-" + dat).val();
        var nit_a_facturar = $("#nit_a_facturar-" + dat).val();
        var monto_a_facturar = $("#monto_a_facturar-" + dat).val();
        var id_certificado = $("#id_certificado-" + dat).val();
        var id_curso = $("#id_curso-" + dat).val();
        var id_participante = $("#id_participante-" + dat).val();

        $("#box-modal_emision_factura-" + dat).html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.curso_emitir_factura.php',
            data: {monto_a_facturar: monto_a_facturar, nit_a_facturar: nit_a_facturar, nombre_a_facturar: nombre_a_facturar, id_certificado: id_certificado, id_curso: id_curso, id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_factura-" + dat).html(data);
            }
        });
    }
</script>


<!-- ajax eliminacion de participante -->
<script>
    function eliminar_participante(dat) {


        if (confirm("Desea eliminar al participante?")) {

            $("#ajaxbox-button-eliminar-participante-" + dat).html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.instant.curso_eliminar_participante.php',
                data: {dat: dat},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#ajaxbox-tr-participante-" + dat).html(data);
                }
            });

        }

    }
</script>

<?php

function my_date_curso($dat) {
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]] . " " . substr($ar1[0], 2, 2);
    }
}
?>