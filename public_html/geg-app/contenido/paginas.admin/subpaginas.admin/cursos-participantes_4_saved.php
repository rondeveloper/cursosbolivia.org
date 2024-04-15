<?php
$mensaje = "";

/* id de curso */
$id_curso = 0;
if (isset($get[2])) {
    $id_curso = (int) $get[2];
}

/* id de turno */
$id_turno_curso = 0;
$qr_turno = '';
if (isset($get[3])) {
    $id_turno_curso = (int) $get[3];
    $qr_turno = " AND id_turno='$id_turno_curso' ";
}

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
if (isset_post('input-buscador')) {
    $busqueda = post('input-buscador');
    $qr_busqueda = " AND ( id='$busqueda' OR nombres LIKE '%$busqueda%' OR apellidos LIKE '%$busqueda%' OR correo LIKE '%$busqueda%' ) ";
    $vista = 1;
}

/* sw de habilitacion de procesos */
$rqvhc1 = query("SELECT estado FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqvhc2 = mysql_fetch_array($rqvhc1);
if ($rqvhc2['estado'] == '1' || $rqvhc2['estado'] == '2') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}

/* agregado de participante */
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
    $id_turno = post('id_turno');

    if ((strlen(post('nombres')) > 3) && (strlen(post('apellidos')) > 3)) {


        /* verificacion de existencia */
        $rqpcv1 = query("SELECT id,estado FROM cursos_participantes WHERE nombres='$nombres' AND apellidos='$apellidos' AND id_curso='$id_curso' ORDER BY id DESC limit 1 ");
        if (mysql_num_rows($rqpcv1) > 0) {
            $rqpcv2 = mysql_fetch_array($rqpcv1);

            if ($rqpcv2['estado'] == 0) {
                $mensaje .= '<br/><div class="alert alert-info">
  <strong>PARTICIPANTE ELIMINADO!</strong> Desea habilitar al participante?.
  <br/>
  <b class="btn btn-xs btn-default" onclick="habilitar_participante(' . $rqpcv2['id'] . ');">HABILITAR PARTICIPANTE</b>
</div>';
            } else {
                $mensaje .= '<div class="alert alert-success">
  <strong>Alerta!</strong> nombre ya existe como participante en este curso.
</div>';
            }


            //echo "<script>alert('Registro ya existente!');history.back();</script>";
            //exit;
        } else {


            $cod_reg = substr("RM-$id_curso-" . str_replace(" ", "-", $nombres), 0, 14);
            $fecha_registro = date("Y-m-d H:i:s");

            query("INSERT INTO cursos_proceso_registro(
                      id_curso, 
                      id_modo_de_registro,
                      id_turno,
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
                      '$id_turno',
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
                      id_turno,
                      prefijo,
                      nombres,
                      apellidos,
                      celular,
                      correo,
                      observacion
                      ) VALUES (
                      '$id_curso',
                      '$id_proceso_registro',
                      '$id_turno',
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

            if (isset_post('imprimir-ficha')) {
                $mensaje .= "<script>window.open('https://www.infosicoes.com/" . encrypt('registro-participantes-curso/' . $id_proceso_registro . '') . ".impresion', 'popup', 'width=700,height=500');</script>";
            }
        }
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
    
    $id_turno = post('id_turno');

    /* edicion de datos de participante */
    query("UPDATE cursos_participantes SET 
            prefijo='$prefijo',
            nombres='$nombres',
            apellidos='$apellidos',
            celular='$celular',
            correo='$correo',
            id_turno='$id_turno' 
             WHERE id='$id_participante' ORDER BY id DESC limit 1 ");

    /* edicion de datos de registro */
    $rqdr1 = query("SELECT id_proceso_registro FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
    $rqdr2 = mysql_fetch_array($rqdr1);
    $id_proceso_registro = $rqdr2['id_proceso_registro'];

    query("UPDATE cursos_proceso_registro SET 
            razon_social='$razon_social',
            nit='$nit',
            monto_deposito='$monto_pago',
            id_turno='$id_turno' 
            WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

    $mensaje .= '<div class="alert alert-success">
  <strong>Exito!</strong> Participante editado correctamente.
</div>';
}


$resultado1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' $qr_busqueda $qr_turno ORDER BY id DESC ");

//datos del curso
$rqc1 = query("SELECT titulo,titulo_identificador,fecha,imagen,costo,id_certificado,id_certificado_2,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = mysql_fetch_array($rqc1);
$nombre_del_curso = $rqc2['titulo'];
$titulo_identificador_del_curso = $rqc2['titulo_identificador'];
$fecha_del_curso = $rqc2['fecha'];
$codigo_de_certificado_del_curso = $rqc2['codigo_certificado'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
if ($rqc2['imagen'] !== '') {
    $url_imagen_del_curso = "https://www.infosicoes.com/paginas/" . $rqc2['imagen'] . ".size=4.img";
} else {
    $url_imagen_del_curso = "https://www.infosicoes.com/images/banner-cursos.png.size=4.img";
}
$costo_curso = $rqc2['costo'];


$cnt = mysql_num_rows($resultado1);
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="cursos-listar.adm">Cursos</a></li>
            <li class="active">Participantes</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <a href="cursos/<?php echo $titulo_identificador_del_curso; ?>.html" target="_blank" class="btn btn-sm btn-info active"><i class="fa fa-eye"></i> VISUALIZAR CURSO</a>
            &nbsp;|&nbsp;
            <a href="cursos-editar/<?php echo $id_curso; ?>.adm" class="btn btn-sm btn-info active"><i class="fa fa-edit"></i> EDITAR CURSO</a>
            &nbsp;|&nbsp;
            <a href="cursos-listar.adm" class="btn btn-sm btn-info active"><i class="fa fa-list"></i> LISTAR CURSOS</a>
        </div>
        <h4 class="page-header"> PARTICIPANTES DEL CURSO <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h4>
        <blockquote class="page-information hidden">
            <p>
                Listado de cursos de Cursos
            </p>
        </blockquote>

        <form action="" method="post">
            <div class="input-group col-sm-12">
                <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Buscador: </span>
                <input type="text" name="input-buscador" value="<?php echo $busqueda; ?>" class="form-control" placeholder="Nombres / Apellidos / Celular / Correo ..."/>
            </div>
        </form>
    </div>
</div>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <h4>
                <i class='btn btn-success active'><?php echo date("d  M  y", strtotime($fecha_del_curso)); ?></i> | 
                <?php echo $nombre_del_curso; ?>

                <?php
                $sw_turnos = false;
                $rqtc1 = query("SELECT id,titulo FROM cursos_turnos WHERE id_curso='$id_curso' ");
                if (mysql_num_rows($rqtc1) > 0) {
                    $aux_class_tc = "btn-info";
                    if ($id_turno_curso !== 0) {
                        $aux_class_tc = "btn-primary";
                    }
                    ?>
                    <span class="pull-right">
                        <a href="cursos-participantes/<?php echo $id_curso; ?>.adm" class="btn btn-xs <?php echo $aux_class_tc; ?> active"><i class="fa fa-clock-o"></i> Todos los turnos</a>
                        <?php
                        $turno = array();
                        $turno[0] = 'Sin turno';
                        $sw_turnos = true;
                        while ($rqtc2 = mysql_fetch_array($rqtc1)) {
                            $turno[$rqtc2['id']] = $rqtc2['titulo'];
                            $aux_class_tc = "btn-primary";
                            if ($id_turno_curso == $rqtc2['id']) {
                                $aux_class_tc = "btn-info";
                            }
                            ?>
                            <a href="cursos-participantes/<?php echo $id_curso; ?>/<?php echo $rqtc2['id']; ?>.adm" class="btn btn-xs <?php echo $aux_class_tc; ?> active"><i class="fa fa-clock-o"></i> <?php echo $rqtc2['titulo']; ?></a>
                            <?php
                        }
                        ?>
                    </span>
                    <?php
                }
                ?>
            </h4>

            <?php
            if ($sw_habilitacion_procesos) {
                ?>

                <div class="panel-body" style="padding-bottom: 0px;padding-top: 0px;">
                    <table class="table" style="background:#EEE;border-radius:5px;padding-bottom: 0px;">
                        <form action="" method="post">
                            <tr>
                                <td class="text-right" style="width: 120px;"><input type="text" name="prefijo" class="form-control" style="width:110px;" placeholder="Sr. / Dr. / Arq. / Ing. " /></td>
                                <td><input type="text" name="nombres" class="form-control" placeholder="Nombres" id="f-nom" required=""/></td>
                                <td><input type="text" name="apellidos" class="form-control" placeholder="Apellidos" id="f-ape" required=""/></td>
                                <td><input type="text" name="celular" class="form-control" placeholder="Celular" /></td>
                                <td><input type="submit" name="agregar-participante" class="btn btn-success active" value="AGREGAR PARTICIPANTE"/></td>
                            </tr>
                            <tr>
                                <td class="text-right" style="width: 120px;"><input type="number" name="monto_pago" class="form-control" style="width:110px;" placeholder="Monto..." required="" value="<?php echo $costo_curso; ?>"/></td>
                                <td><input type="text" name="razon_social" class="form-control" placeholder="Razon Social" id="f-raz" /></td>
                                <td><input type="text" name="nit" class="form-control" placeholder="NIT" /></td>
                                <td><input type="text" name="correo" class="form-control" placeholder="Correo" /></td>
                                <td>
                                    <?php
                                    $rqtc1 = query("SELECT * FROM cursos_turnos WHERE id_curso='$id_curso' ");
                                    if (mysql_num_rows($rqtc1) > 0) {
                                        ?>
                                        <select name="id_turno" class="form-control" style='max-width: 200px;'>
                                            <?php
                                            while ($rqtc2 = mysql_fetch_array($rqtc1)) {
                                                $aux_t_selected = '';
                                                if($id_turno_curso==$rqtc2['id']){
                                                    $aux_t_selected = ' selected="selected" ';
                                                }
                                                ?>
                                                <option value="<?php echo $rqtc2['id']; ?>" <?php echo $aux_t_selected; ?> ><?php echo $rqtc2['titulo']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                        <br/>
                                        <?php
                                    } else {
                                        echo "<input type='hidden' id='id_turno' value='0'/>";
                                    }
                                    ?>



                                    <b onclick="nombreFact();" class="btn btn-xs btn-default"><i class="fa fa-star"></i> NombreFact</b>
                                    &nbsp;&nbsp;
                                    <input type="checkbox" name="imprimir-ficha" value="1"/> Imp. ficha
                                </td>
    <!--                            <td><input type="text" name="observacion" class="form-control" placeholder="Observaciones" /></td>-->
                            </tr>
                        </form>
                    </table>
                </div>

                <script>
                    function nombreFact() {
                        var nom = document.getElementById("f-nom").value;
                        var ape = document.getElementById("f-ape").value;
                        document.getElementById("f-raz").value = nom + " " + ape;
                    }
                </script>
                <script>
                    function nombreFact2(id) {
                        var nom = document.getElementById("f-nom-" + id).value;
                        var ape = document.getElementById("f-ape-" + id).value;
                        document.getElementById("f-raz-" + id).value = nom + " " + ape;
                    }
                </script>

                <?php
            }
            ?>



            <?php
            if (mysql_num_rows($resultado1) == 0) {
                echo "<p>No se registraron participantes para este curso</p>";
            }
            ?>

            <div class="panel-body" style="padding-top: 0px;">
                <table class="table users-table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">#</th>
                            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Prof.</th>
                            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Nombre</th>
                            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Apellidos</th>
                            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Facturaci&oacute;n</th>
                            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">MR</th>
                            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Registro</th>
                            <?php
                            if ($sw_turnos) {
                                echo '<th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Turno</th>';
                            }
                            ?>
                            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;">Contacto</th>
                            <th class="visible-lg" style="padding-top: 2px;padding-bottom: 2px;width: 270px;">
                                Acci&oacute;n 
                                &nbsp;&nbsp;
                                <a class='btn btn-xs btn-default' data-toggle="modal" data-target="#MODAL-generar-reporte">Generar Reporte</a>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $sw_existencia_certificado_uno = false;
                        $sw_existencia_certificado_dos = false;
                        $sw_existencia_facturas = false;

                        while ($participante = mysql_fetch_array($resultado1)) {

                            /* datos de registro */
                            $rqrp1 = query("SELECT id,codigo,fecha_registro,celular_contacto,correo_contacto,metodo_de_pago,id_modo_de_registro,id_emision_factura,monto_deposito,imagen_deposito,razon_social,nit,cnt_participantes,id_cobro_khipu FROM cursos_proceso_registro WHERE id='" . $participante['id_proceso_registro'] . "' ORDER BY id DESC limit 1 ");
                            $data_registro = mysql_fetch_array($rqrp1);
                            $id_proceso_de_registro = $data_registro['id'];
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
                                    echo trim($participante['prefijo']);
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo trim($participante['nombres']);
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo trim($participante['apellidos']);
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    if ($id_emision_factura != '0') {
                                        $sw_existencia_facturas = true;
                                        echo '<i class="btn btn-xs btn-success">Emitida</i>';
                                        if ($participante['correo'] !== '') {
                                            $rqefaux1 = query("SELECT nro_factura FROM facturas_emisiones WHERE id='$id_emision_factura' ORDER BY id DESC limit 1 ");
                                            $rqefaux2 = mysql_fetch_array($rqefaux1);
                                            echo '&nbsp;&nbsp;<span id="ffl-' . $rqefaux2['nro_factura'] . '"><i class="btn btn-xs btn-default" onclick="enviar_factura2(\'' . $rqefaux2['nro_factura'] . '\');"><b class="fa fa-envelope"></b></i></span>';
                                        }
                                        echo '</br>';
                                    } else {
                                        if (strlen(trim($razon_social_de_registro . $nit_de_registro)) <= 2) {
                                            echo '<i class="btn btn-xs btn-warning">No solicitada</i></br>';
                                        } else {
                                            echo '<i class="btn btn-xs btn-danger">No emitida</i></br>';
                                        }
                                    }



                                    echo $razon_social_de_registro;
                                    echo "<br/>";
                                    echo $nit_de_registro;
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    if ($id_modo_de_registro == '1' || $id_modo_de_registro == '0') {
                                        if ($metodo_de_pago == "deposito") {
                                            echo "Sis<br/><b style='color:green;'>Depo</b>";
                                        } else {
                                            echo "Sis<br/><b style='color:blue;'>Khip</b>";
                                        }
                                        //echo "Sis";
                                    } elseif ($id_modo_de_registro == '2') {
                                        echo "Adm";
                                    }
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo date("d / M H:i", strtotime($fecha_de_registro));
                                    ?>
                                </td>
                                <?php
                                if ($sw_turnos) {
                                    echo '<td class="visible-lg">' . $turno[$participante['id_turno']] . '</td>';
                                }
                                ?>
                                <td class="visible-lg">
                                    <?php
                                    echo $participante['celular'];
                                    echo "<br/>";
                                    echo $participante['correo'];
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <input type="checkbox" id="<?php echo $participante['id']; ?>" name="" checked=""/>
                                    <?php
                                    if ($sw_habilitacion_procesos) {
                                        ?>
                                        &nbsp;
                                        <a data-toggle="modal" data-target="#MODAL-edicion-<?php echo $participante['id']; ?>" onclick="" class="btn btn-xs btn-info"> <i class="fa fa-edit"></i> </a>
                                        <?php
                                    }
                                    ?>
                                    &nbsp;
                                    <a data-toggle="modal" data-target="#MODAL-datos-registro-<?php echo $participante['id']; ?>" onclick="" class="btn btn-xs btn-primary">R.</a>
                                    &nbsp;
                                    <a data-toggle="modal" data-target="#MODAL-facturacion-registro-<?php echo $participante['id']; ?>" onclick="" class="btn btn-xs btn-success">Fact</a>
                                    &nbsp;
                                    <?php
                                    /* primer certificado */
                                    if ($participante['id_emision_certificado'] == '0' && $sw_habilitacion_procesos && $id_certificado_curso !== '0') {
                                        ?>
                                        <span id='box-modal_emision_certificado-button-<?php echo $participante['id']; ?>'>
                                            <a data-toggle="modal" data-target="#MODAL-emite-certificado-p1-<?php echo $participante['id']; ?>" onclick="" class="btn btn-xs btn-info">Cert</a>
                                        </span>
                                        <?php
                                    } elseif ($participante['id_emision_certificado'] !== '0') {
                                        $sw_existencia_certificado_uno = true;
                                        ?>
                                        <a onclick="imprimir_certificado_individual('<?php echo $participante['id_emision_certificado']; ?>');" class="btn btn-xs btn-warning active">Cert</a>
                                        <?php
                                    }

                                    /* segundo certificado */
                                    if ($participante['id_emision_certificado_2'] == '0' && $sw_habilitacion_procesos && $id_certificado_2_curso !== '0') {
                                        ?>
                                        <span id='box-modal_emision_certificado-button-2-<?php echo $participante['id']; ?>'>
                                            <a data-toggle="modal" data-target="#MODAL-emite-certificado-2-p1-<?php echo $participante['id']; ?>" onclick="" class="btn btn-xs btn-info">C2</a>
                                        </span>
                                        <?php
                                    } elseif ($participante['id_emision_certificado_2'] !== '0') {
                                        $sw_existencia_certificado_dos = true;
                                        ?>
                                        <a onclick="imprimir_certificado_individual('<?php echo $participante['id_emision_certificado_2']; ?>');" class="btn btn-xs btn-warning active">C2</a>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                    if ($sw_habilitacion_procesos) {
                                        $rqmc1 = query("SELECT id FROM cursos_modelos_certificados WHERE id_curso='$id_curso' LIMIT 1 ");
                                        if (mysql_num_rows($rqmc1) > 0) {
                                            ?>
                                            &nbsp;           
                                            <a data-toggle="modal" data-target="#MODAL-certificados-secundarios" onclick="procesar_certificados_secundarios('<?php echo $participante['id']; ?>');" class="btn btn-xs btn-primary">CS</a>
                                            <?php
                                        }
                                    }
                                    ?>


                                    <?php
                                    if ($sw_habilitacion_procesos) {
                                        ?>
                                        &nbsp;&nbsp;|&nbsp;&nbsp;            
                                        <span id="ajaxbox-button-eliminar-participante-<?php echo $participante['id']; ?>">
                                            <a onclick="eliminar_participante(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-danger"> X </a>
                                        </span>
                                        <?php
                                    }
                                    ?>
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

                                        <input type="hidden" id="id_certificado-<?php echo $participante['id']; ?>" value="<?php echo $id_certificado_curso; ?>" />
                                        <input type="hidden" id="id_curso-<?php echo $participante['id']; ?>" value="<?php echo $id_curso; ?>" />
                                        <input type="hidden" id="id_participante-<?php echo $participante['id']; ?>" value="<?php echo $participante['id']; ?>" />


                                        <div class="text-center" id='box-modal_emision_certificado-<?php echo $participante['id']; ?>'>
                                            <h5 class="text-center">
                                                Emision de certificado para
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-12 text-left">
                                                    <input type="text" class="form-control text-center" value="<?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>" readonly=""/>
                                                    <input type="hidden" id="receptor_de_certificado-<?php echo $participante['id']; ?>" value="<?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>"/>
                                                </div>
                                            </div>
                                            <br/>
                                            <br/>

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
                                                <br/>
                                                <br/>
                                                <b class="btn btn-info active" onclick="window.open('https://www.infosicoes.com/<?php echo encrypt('registro-participantes-curso/' . $id_proceso_de_registro . ''); ?>.impresion', 'popup', 'width=700,height=500');">
                                                    <i class="fa fa-file"></i> &nbsp; FICHA DE REGISTRO
                                                </b>
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
                                                <b>Fecha de registro:</b> &nbsp; <?php echo $fecha_de_registro; ?>
                                                <br/>
                                                <!--                                                <b>CELULAR CONTACTO:</b> &nbsp; <?php echo $celular_de_registro; ?>
                                                                                                <br/>
                                                                                                <b>CORREO CONTACTO:</b> &nbsp; <?php echo $correo_de_registro; ?>
                                                                                                <br/>-->
                                                <b>Registro:</b> &nbsp; <?php echo $codigo_de_registro; ?>
                                                <br/>
                                                <b>Nro. de participantes:</b> &nbsp; <?php echo $nro_participantes_de_registro; ?>
                                                <br/>
                                                <?php
                                                if ($metodo_de_pago == 'deposito') {
                                                    ?>
                                                    <b>Metodo de pago:</b> &nbsp; DEPOSITO BANCARIO
                                                    <br/>
                                                    <b>Monto pagado:</b> &nbsp; <?php echo $monto_de_pago; ?>
                                                    <br/>
                                                    <b>Imagen del deposito:</b> &nbsp; <a href="depositos/<?php echo $imagen_de_deposito; ?>.img" target="_blank"><?php echo $imagen_de_deposito; ?></a>
                                                    <br/>
                                                    <br/>
                                                    <img src="depositos/<?php echo $imagen_de_deposito; ?>.size=3.img" style="width:100%;border:1px solid #DDD;padding:1px;">
                                                    <?php
                                                } elseif ($metodo_de_pago == 'tarjeta') {
                                                    $id_cobro_khipu = $data_registro['id_cobro_khipu'];
                                                    $rqck1 = query("SELECT payment_id,estado FROM khipu_cobros WHERE id='$id_cobro_khipu' ORDER BY id DESC limit 1 ");
                                                    $rqck2 = mysql_fetch_array($rqck1);
                                                    $enlace_khipu = "https://khipu.com/payment/info/" . $rqck2['payment_id'];
                                                    ?>
                                                    <b>Metodo de pago:</b> &nbsp; KHIPU
                                                    <br/>
                                                    <b>Monto ingresado:</b> &nbsp; <?php echo $monto_de_pago; ?>
                                                    <br/>
                                                    <b>Enlace de transferencia:</b> &nbsp; <a href='<?php echo $enlace_khipu; ?>' target='_blank'><?php echo $enlace_khipu; ?></a>
                                                    <br/>
                                                    <?php
                                                    if ($rqck2['estado'] == '0') {
                                                        ?>
                                                        <b>Estado del pago:</b> &nbsp; <b style='color:red;'>TRANSFERENCIA NO REALIZADA</b>
                                                        <?php
                                                    } elseif ($rqck2['estado'] == '1') {
                                                        ?>
                                                        <b>Estado del pago:</b> &nbsp; <b style='color:green;'>TRANSFERENCIA EXITOSA</b>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <b>Estado del pago:</b> &nbsp; <b style='color:red;'>DATOS NO ENCONTRADOS</b>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                } else {
                                                    tarjeta
                                                    ?>
                                                    <b>Metodo de pago:</b> &nbsp; PAGO EN OFICINA
                                                    <br/>
                                                    <b>Monto pagado:</b> &nbsp; <?php echo $monto_de_pago; ?>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>

                                        <hr/>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal-3 -->

                        <!-- Modal - 3b - facturacion -->
                        <div id="MODAL-facturacion-registro-<?php echo $participante['id']; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">DATOS DE FACTURACION</h4>
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
                                            <?php
                                            if ($sw_habilitacion_procesos) {
                                                ?>
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
                                            }
                                            ?>

                                            <?php
                                        } else {
                                            $rqef1 = query("SELECT nro_factura,nombre_receptor,nit_receptor,total FROM facturas_emisiones WHERE id='$id_emision_factura' ORDER BY id DESC limit 1 ");
                                            $rqef2 = mysql_fetch_array($rqef1);
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12 text-left">
                                                    <b>Solicitud de factura :</b> &nbsp; SI SOLICITO FACTURA
                                                    <br/>
                                                    <b>Proceso de emision :</b> &nbsp; FACTURA EMITIDA
                                                    <br/>
                                                    <br/>
                                                    <b>Facturado a nombre de :</b> &nbsp; <?php echo $rqef2['nombre_receptor']; ?>
                                                    <br/>
                                                    <b>N&uacute;mero de NIT:</b> &nbsp; <?php echo $rqef2['nit_receptor']; ?>
                                                    <br/>
                                                    <b>Monto facturado:</b> &nbsp; <?php echo $rqef2['total']; ?>
                                                    <br/>
                                                    <br/>
                                                    <b>Nro. de factura:</b> &nbsp; <?php echo $rqef2['nro_factura']; ?>
                                                    <br/>
                                                    <b>Visualizaci&oacute;n -> </b> <button type="button" class="btn btn-default btn-xs" onclick="window.open('http://www.infosicoes.com/contenido/librerias/fpdf/tutorial/factura-1.php?nro_factura=<?php echo $rqef2['nro_factura']; ?>', 'popup', 'width=700,height=500');">VISUALIZAR FACTURA</button>


                                                    <?php
                                                    if ($sw_habilitacion_procesos) {
                                                        ?>
                                                        <br/>
                                                        <hr/>
                                                        <h4 class="text-center">ENVIO DE FACTURA DIGITAL</h4>
                                                        <div class="text-center" id='box-modal_envia-factura-<?php echo $rqef2['nro_factura']; ?>'>
                                                            <h5 class="text-center">
                                                                Ingrese el correo al cual se hara el envio de la factura
                                                            </h5>
                                                            <div class="row">
                                                                <div class="col-md-8 text-right">
                                                                    <input type="text" id="correo-de-envio-<?php echo $rqef2['nro_factura']; ?>" class="form-control text-center" value="<?php echo $participante['correo']; ?>"/>
                                                                </div>
                                                                <div class="col-md-3 text-left">
                                                                    <button class="btn btn-success" onclick="enviar_factura('<?php echo $rqef2['nro_factura']; ?>');"><i class='fa fa-send'></i> ENVIAR</button>
                                                                </div>
                                                            </div>
                                                            <br/>
                                                            <br/>
                                                        </div>
                                                        <hr/>
                                                        <?php
                                                    }
                                                    ?>


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
                        <!-- Modal - 3b - facturacion -->


                        <!-- Modal-5 -->
                        <div id="MODAL-edicion-<?php echo $participante['id']; ?>" class="modal fade" role="dialog">
                            <form action="" method="post">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">EDICI&Oacute;N DE PARTICIPANTE</h4>
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
                                                        <input type="text" class="form-control text-center" name="nombres" value="<?php echo $participante['nombres']; ?>" placeholder="Nombres..." id="f-nom-<?php echo $participante['id']; ?>"/>
                                                    </div>
                                                    <div class="col-md-4 text-left">
                                                        <input type="text" class="form-control text-center" name="apellidos" value="<?php echo $participante['apellidos']; ?>" placeholder="Apellidos..." id="f-ape-<?php echo $participante['id']; ?>"/>
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
                                                        <input type="text" class="form-control text-center" name="razon_social" value="<?php echo $razon_social_de_registro; ?>" placeholder="Razon Social..." id="f-raz-<?php echo $participante['id']; ?>"/>
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

                                                <?php
                                                if ($sw_turnos) {
                                                    ?>
                                                    <h5 class="text-center">
                                                        Turno seleccionado
                                                    </h5>
                                                    <div class="row">
                                                        <div class="col-md-12 text-left">
                                                            <select class="form-control" name="id_turno" id="numero_participantes">
                                                                <?php
                                                                $rqttc1 = query("SELECT * FROM cursos_turnos WHERE id_curso='$id_curso' ");
                                                                while ($rqtc2 = mysql_fetch_array($rqttc1)) {
                                                                    $txt_selected = '';
                                                                    if($participante['id_turno']==$rqtc2['id']){
                                                                        $txt_selected = ' selected="selected" ';
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $rqtc2['id']; ?>" <?php echo $txt_selected; ?> ><?php echo $rqtc2['titulo'] . ' | ' . $rqtc2['descripcion']; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }else{
                                                    echo '<input type="hidden" name="id_turno" value="0"/>';
                                                }
                                                ?>

                                                <br/>

                                                <b onclick="nombreFact2(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-default"><i class="fa fa-star"></i> NombreFact</b>

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


                    <?php
                    if ($sw_habilitacion_procesos) {
                        ?>
                        <tr>
                            <td colspan="8">
                            </td>
                            <?php
                            if ($sw_turnos) {
                                echo '<td></td>';
                            }
                            ?>
                            <td>
                                <br/>
                                <b>EMISION / IMPRESION MULTIPLE</b>
                                <h4 style='background:#257990;color:#FFF;padding:5px 7px;'>PRIMER CERTIFICADO</h4>
                                <br/>
                                <?php
                                if ($sw_existencia_certificado_uno) {
                                    ?>
                                    <input type="checkbox" checked="" disabled=""/>
                                    &nbsp;&nbsp;&nbsp; 
                                    <a class="btn btn-xs btn-default" onclick="imprimir_certificados_multiple('1');"> <i class="fa fa-print"></i> Imprimir certificados </a> 
                                    <br/>
                                    <br/>
                                    <?php
                                }
                                ?>
                                <input type="checkbox" checked="" disabled=""/>
                                &nbsp;&nbsp;&nbsp; 
                                <a data-toggle="modal" data-target="#MODAL-emite-certificados-multiple" onclick="emision_multiple_certificados();" class="btn btn-xs btn-default"> <i class="fa fa-send"></i> Emitir certificados </a>
                                <br/>
                                <br/>

                                <?php
                                if ($id_certificado_2_curso !== '0' && $sw_habilitacion_procesos) {
                                    ?>
                                    <h4 style='background:#257990;color:#FFF;padding:5px 7px;'>SEGUNDO CERTIFICADO</h4>
                                    <br/>
                                    <?php
                                    if ($sw_existencia_certificado_dos) {
                                        ?>
                                        <input type="checkbox" checked="" disabled=""/>
                                        &nbsp;&nbsp;&nbsp; 
                                        <a class="btn btn-xs btn-default" onclick="imprimir_certificados_multiple('2');"> <i class="fa fa-print"></i> Imprimir certificados </a> 
                                        <br/>
                                        <br/>
                                        <?php
                                    }
                                    ?>
                                    <input type="checkbox" checked="" disabled=""/>
                                    &nbsp;&nbsp;&nbsp; 
                                    <a data-toggle="modal" data-target="#MODAL-emite-certificados-multiple" onclick="emision_multiple_c2_certificados();" class="btn btn-xs btn-default"> <i class="fa fa-send"></i> Emitir certificados </a>
                                    <br/>
                                    <br/>
                                    <?php
                                }
                                ?>

                                <h4 style='background:#257990;color:#FFF;padding:5px 7px;'>FACTURAS</h4>
                                <br/>
                                <?php
                                if ($sw_existencia_facturas) {
                                    ?>
                                    <input type="checkbox" checked="" disabled=""/>
                                    &nbsp;&nbsp;&nbsp; 
                                    <a class="btn btn-xs btn-default" onclick="imprimir_facturas();"> <i class="fa fa-print"></i> Imprimir facturas </a> 
                                    <br/>
                                    <br/>
                                    <?php
                                }
                                ?>
                                <input type="checkbox" checked="" disabled=""/>
                                &nbsp;&nbsp;&nbsp; 
                                <a data-toggle="modal" data-target="#MODAL-emite-facturas-multiple" onclick="emision_multiple_facturas();" class="btn btn-xs btn-default"> <i class="fa fa-send"></i> Emitir facturas </a>
                                <br/>
                                <br/>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>


                    <!-- Modal emitir certificados - multiple -->
                    <div id="MODAL-emite-certificados-multiple" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">EMISION MULTIPLE DE CERTIFICADOS</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-7 text-left">
                                            <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                                            <br/>
                                            <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                                        </div>
                                        <div class="col-md-5 text-right">
                                            <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                                        </div>
                                    </div>
                                    <hr/>
                                    <h5 class="text-center">
                                        Emisi&oacute;n de certificados para
                                    </h5>
                                    <div class="text-center" id='box-modal_emision_certificados-multiple'>
                                        <!-- ajax content -->
                                    </div>
                                    <hr/>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal emitir certificados - multiple -->

                    <!-- Modal emitir certificados a eleccion -->
                    <div id="MODAL-emite-certificados-a-eleccion" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">EMISION DE CERTIFICADOS A ELECCION</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-7 text-left">
                                            <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                                            <br/>
                                            <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                                        </div>
                                        <div class="col-md-5 text-right">
                                            <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="text-center" id='box-modal_emision_certificados-a-eleccion'>

                                        <!-- ajax content -->

                                    </div>
                                    <hr/>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal emitir certificados a eleccion -->

                    <!-- Modal imprime certificados a eleccion -->
                    <div id="MODAL-imprime-certificados-a-eleccion" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">IMRPESION DE CERTIFICADOS A ELECCION</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-7 text-left">
                                            <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                                            <br/>
                                            <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                                        </div>
                                        <div class="col-md-5 text-right">
                                            <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="text-center" id='box-modal_impresion_certificados-a-eleccion'>

                                        <!-- ajax content -->

                                    </div>
                                    <hr/>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal imprime certificados a eleccion -->

                    <!-- Modal emitir facturas - multiple -->
                    <div id="MODAL-emite-facturas-multiple" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">EMISION MULTIPLE DE FACTURAS</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-7 text-left">
                                            <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                                            <br/>
                                            <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                                        </div>
                                        <div class="col-md-5 text-right">
                                            <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                                        </div>
                                    </div>
                                    <hr/>
                                    <h5 class="text-center">
                                        Emisi&oacute;n de facturas para
                                    </h5>
                                    <div class="text-center" id='box-modal_emision_facturas-multiple'>
                                        <!-- ajax content -->
                                    </div>
                                    <hr/>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal emitir facturas - multiple -->

                    <!-- Modal-certificados-secundarios -->
                    <div id="MODAL-certificados-secundarios" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">EMISION DE CERTIFICADOS SECUNDARIOS</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6 text-left">
                                            <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                                            <br/>
                                            <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                                            <br/>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div id="BOX-AJAX-certificados-secundarios">
                                            <!-- ajax content -->

                                        </div>
                                    </div>
                                    <hr/>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal-certificados-secundarios -->


                    </tbody>
                </table>

                <table class="table users-table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="visible-lg">Participantes eliminados</th>
                            <th class="visible-lg">Facturaci&oacute;n</th>
                            <th class="visible-lg">MR</th>
                            <th class="visible-lg">Registro</th>
                            <th class="visible-lg">.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /* participantes eliminados */
                        $resultado1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='0' ORDER BY id DESC ");
                        if (mysql_num_rows($resultado1) == 0) {
                            echo "<tr><td colspan='4'>No existen participantes eliminados.</td></tr>";
                        }
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
                                <td class="visible-lg">
                                    <?php
                                    echo ' - ' . trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']);
                                    echo " | ";
                                    echo $participante['celular'] . ' ' . $participante['correo'];
                                    echo " | ";
                                    echo $razon_social_de_registro . ' ' . $nit_de_registro;
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    if ($id_emision_factura != '0') {
                                        echo '<b>Emitida</b></br>';
                                    } else {
                                        if (strlen(trim($razon_social_de_registro . $nit_de_registro)) <= 2) {
                                            echo '<b>No solicitada</b></br>';
                                        } else {
                                            echo '<b>No emitida</b></br>';
                                        }
                                    }
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    if ($id_modo_de_registro == '1' || $id_modo_de_registro == '0') {
                                        echo "Sis";
                                    } elseif ($id_modo_de_registro == '2') {
                                        echo "Adm";
                                    }
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo date("d / M H:i", strtotime($fecha_de_registro));
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <a data-toggle="modal" data-target="#MODAL-datos-registro-<?php echo $participante['id']; ?>" onclick="" class="btn btn-xs btn-primary">R.</a>
                                    <?php
                                    if ($sw_habilitacion_procesos) {
                                        ?>
                                        &nbsp;&nbsp;
                                        <a onclick="habilitar_participante(<?php echo $participante['id']; ?>);" class="btn btn-xs btn-warning"> Habilitar </a>
                                        <?php
                                    }
                                    ?>
                                </td>

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
                                                <b>Fecha de registro:</b> &nbsp; <?php echo $fecha_de_registro; ?>
                                                <br/>
                                                <!--                                                <b>CELULAR CONTACTO:</b> &nbsp; <?php echo $celular_de_registro; ?>
                                                                                                <br/>
                                                                                                <b>CORREO CONTACTO:</b> &nbsp; <?php echo $correo_de_registro; ?>
                                                                                                <br/>-->
                                                <b>Registro:</b> &nbsp; <?php echo $codigo_de_registro; ?>
                                                <br/>
                                                <b>Nro. de participantes:</b> &nbsp; <?php echo $nro_participantes_de_registro; ?>
                                                <br/>
                                                <?php
                                                if ($metodo_de_pago == 'deposito') {
                                                    ?>
                                                    <b>Metodo de pago:</b> &nbsp; DEPOSITO BANCARIO
                                                    <br/>
                                                    <b>Monto pagado:</b> &nbsp; <?php echo $monto_de_pago; ?>
                                                    <br/>
                                                    <b>Imagen del deposito:</b> &nbsp; <a href="depositos/<?php echo $imagen_de_deposito; ?>.img" target="_blank"><?php echo $imagen_de_deposito; ?></a>
                                                    <br/>
                                                    <br/>
                                                    <img src="depositos/<?php echo $imagen_de_deposito; ?>.size=3.img" style="width:100%;border:1px solid #DDD;padding:1px;">
                                                    <?php
                                                } else {
                                                    ?>
                                                    <b>Metodo de pago:</b> &nbsp; PAGO EN OFICINA
                                                    <br/>
                                                    <b>Monto pagado:</b> &nbsp; <?php echo $monto_de_pago; ?>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>

                                        <hr/>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal-3 -->

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

            $("#ajaxbox-button-eliminar-participante-" + dat).html('<td colspan="7" style="text-align:center;color:gray;">Eliminacion en proceso...</td>');
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

<!-- ajax habilitacion de participante -->
<script>
    function habilitar_participante(dat) {

        if (confirm("Desea habilitar nuevamente al participante?")) {

            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.instant.curso_habilitar_participante.php',
                data: {dat: dat},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    //$("#ajaxbox-tr-participante-" + dat).html(data);
                    alert('Participante-habilitado');
                    location.href = 'cursos-participantes/<?php echo $id_curso; ?>.adm';
                }
            });

        }

    }
</script>


<!-- ajax imprimir certificados multiple -->
<script>
    function imprimir_certificados_multiple(dat) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.aux.cursos-participantes.imprimir_certificados_multiple.php',
            data: {id_curso: <?php echo $id_curso; ?>, nro_certificado: dat, ids: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                window.open(data, 'popup', 'width=700,height=500');
            }
        });
    }
</script>

<!-- ajax imprimir certificado individual -->
<script>
    function imprimir_certificado_individual(dat) {

        if (dat > 0) {
            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.aux.cursos-participantes.imprimir_certificado_individual.php',
                data: {dat: dat},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    window.open(data, 'popup', 'width=700,height=500');
                }
            });
        } else {
            alert('Error en el ID de certificado');
        }
    }
</script>

<!-- ajax emision certificados masivamente -->
<script>
    function emision_multiple_certificados() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_certificados.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados masivamente Certificado 2 -->
<script>
    function emision_multiple_c2_certificados() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_c2_certificados.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion -->
<script>
    function emision_certificados_a_eleccion() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-a-eleccion").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion p2 -->
<script>
    function emision_certificados_a_eleccion_p2() {
        var ids;
        var id_modelo_certificado;
        id_modelo_certificado = $("#id-modelo-certificado").val();
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion_p2.php',
            data: {dat: ids.join(','), id_modelo_certificado: id_modelo_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-a-eleccion").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion p3 -->
<script>
    function emision_certificados_a_eleccion_p3() {
        var ids;
        var id_modelo_certificado;
        id_modelo_certificado = $("#id-modelo-certificado").val();
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion_p3.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso, id_modelo_certificado: id_modelo_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-a-eleccion").html(data);
            }
        });
    }
</script>



<!-- ajax emision certificados a eleccion -->
<script>
    function imprime_certificados_a_eleccion() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_impresion_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.impresion_certificados_a_eleccion.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_impresion_certificados-a-eleccion").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion p2 -->
<script>
    function imprime_certificados_a_eleccion_p2() {
        var ids;
        var id_modelo_certificado;
        id_modelo_certificado = $("#id-modelo-certificado-imp").val();
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_impresion_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.impresion_certificados_a_eleccion_p2.php',
            data: {dat: ids.join(','), id_modelo_certificado: id_modelo_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_impresion_certificados-a-eleccion").html(data);
            }
        });
    }
</script>




<!-- ajax emision certificados masivamente p2 -->
<script>
    function emision_multiple_certificados_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_certificados_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados masivamente p2 Certificado 2 -->
<script>
    function emision_multiple_c2_certificados_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_2_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_c2_certificados_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-multiple").html(data);
            }
        });
    }
</script>




<!-- ajax imprimir facturas masivamente -->
<script>
    function imprimir_facturas() {

        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();

        //alert('IDS: ' + ids.join(','));

        window.open('http://www.infosicoes.com/contenido/librerias/fpdf/tutorial/factura-1-masivo.php?id_participantes=' + ids.join(','), 'popup', 'width=700,height=500');


    }
</script>

<!-- ajax emision facturas masivamente -->
<script>
    function emision_multiple_facturas() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_facturas-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_facturas.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_facturas-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax emision facturas masivamente p2 -->
<script>
    function emision_multiple_facturas_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_facturas-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_facturas_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_facturas-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax procesar_certificados_secundarios -->
<script>
    function procesar_certificados_secundarios(id_participante) {
        var id_curso = '<?php echo $id_curso; ?>';
        $("#BOX-AJAX-certificados-secundarios").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.procesar_certificados_secundarios.php',
            data: {id_participante: id_participante, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#BOX-AJAX-certificados-secundarios").html(data);
            }
        });
    }
</script>

<!-- ajax procesar_certificados_secundarios p2 -->
<script>
    function procesar_certificados_secundarios_p2() {
        var arraydata = $("#formajax1").serialize();
        $("#BOX-AJAX-certificados-secundarios").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.procesar_certificados_secundarios_p2.php',
            data: arraydata,
            type: 'POST',
            success: function(data) {
                $("#BOX-AJAX-certificados-secundarios").html(data);
            }
        });
    }
</script>




<!-- Modals auxiliares -->

<!-- Modal-generar reporte -->
<div id="MODAL-generar-reporte" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">GENERAR REPORTE</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 text-left">
                        <b>CURSO:</b> &nbsp; <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> &nbsp; <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-4 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-12 text-left">
                        <p>Selecciona los campos necesarios para el reporte.</p>

                        <table class="table table-striped">
                            <tr>
                                <td>Prefijo</td>
                                <td class="text-center">
                                    <label for="f0a"><input name="data_report_prefijo" type="radio" value="1" id="f0a"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f0b"><input name="data_report_prefijo" type="radio" value="0" id="f0b" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Nombres</td>
                                <td class="text-center">
                                    <label for="f1"><input name="data_report_nombres" type="radio" value="1" id="f1" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f2"><input name="data_report_nombres" type="radio" value="0" id="f2"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Apellidos</td>
                                <td class="text-center">
                                    <label for="f3"><input name="data_report_apellidos" type="radio" value="1" id="f3" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f4"><input name="data_report_apellidos" type="radio" value="0" id="f4"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Datos de Facturaci&oacute;n</td>
                                <td class="text-center">
                                    <label for="f5"><input name="data_report_datosfacturacion" type="radio" value="1" id="f5" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f6"><input name="data_report_datosfacturacion" type="radio" value="0" id="f6"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Datos de Contacto</td>
                                <td class="text-center">
                                    <label for="f7"><input name="data_report_datoscontacto" type="radio" value="1" id="f7" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f8"><input name="data_report_datoscontacto" type="radio" value="0" id="f8"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Modo de registro</td>
                                <td class="text-center">
                                    <label for="f7a"><input name="data_report_modoregistro" type="radio" value="1" id="f7a"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f8a"><input name="data_report_modoregistro" type="radio" value="0" id="f8a" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Fecha de registro</td>
                                <td class="text-center">
                                    <label for="f7b"><input name="data_report_fecharegistro" type="radio" value="1" id="f7b"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f8b"><input name="data_report_fecharegistro" type="radio" value="0" id="f8b" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Firma</td>
                                <td class="text-center">
                                    <label for="f9"><input name="data_report_firma" type="radio" value="1" id="f9" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f10"><input name="data_report_firma" type="radio" value="0" id="f10"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Participantes eliminados</td>
                                <td class="text-center">
                                    <label for="f9a"><input name="data_report_eliminados" type="radio" value="1" id="f9a"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f10a"><input name="data_report_eliminados" type="radio" value="0" id="f10a" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                        </table>

                        <hr/>

                        <div class="panel-footer text-center">
                            <button class="btn btn-default active" onclick="generar_reporte('impresion');">IMPRIMIR</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="btn btn-success active" onclick="generar_reporte('excel');">EXCEL</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="btn btn-info active" onclick="generar_reporte('word');">WORD</button>
                        </div>

                    </div>
                </div>
                <hr/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-generar reporte -->

<script>
    function generar_reporte(formato) {

        var data_report_prefijo = "0";
        var array_data_report_prefijo = document.getElementsByName("data_report_prefijo");
        for (var i = 0; i < array_data_report_prefijo.length; i++) {
            if (array_data_report_prefijo[i].checked)
                data_report_prefijo = array_data_report_prefijo[i].value;
        }

        var data_report_nombres = "0";
        var array_data_report_nombres = document.getElementsByName("data_report_nombres");
        for (var i = 0; i < array_data_report_nombres.length; i++) {
            if (array_data_report_nombres[i].checked)
                data_report_nombres = array_data_report_nombres[i].value;
        }

        var data_report_apellidos = "0";
        var array_data_report_apellidos = document.getElementsByName("data_report_apellidos");
        for (var i = 0; i < array_data_report_apellidos.length; i++) {
            if (array_data_report_apellidos[i].checked)
                data_report_apellidos = array_data_report_apellidos[i].value;
        }

        var data_report_datosfacturacion = "0";
        var array_data_report_datosfacturacion = document.getElementsByName("data_report_datosfacturacion");
        for (var i = 0; i < array_data_report_datosfacturacion.length; i++) {
            if (array_data_report_datosfacturacion[i].checked)
                data_report_datosfacturacion = array_data_report_datosfacturacion[i].value;
        }

        var data_report_datoscontacto = "0";
        var array_data_report_datoscontacto = document.getElementsByName("data_report_datoscontacto");
        for (var i = 0; i < array_data_report_datoscontacto.length; i++) {
            if (array_data_report_datoscontacto[i].checked)
                data_report_datoscontacto = array_data_report_datoscontacto[i].value;
        }

        var data_report_modoregistro = "0";
        var array_data_report_modoregistro = document.getElementsByName("data_report_modoregistro");
        for (var i = 0; i < array_data_report_modoregistro.length; i++) {
            if (array_data_report_modoregistro[i].checked)
                data_report_modoregistro = array_data_report_modoregistro[i].value;
        }

        var data_report_fecharegistro = "0";
        var array_data_report_fecharegistro = document.getElementsByName("data_report_fecharegistro");
        for (var i = 0; i < array_data_report_fecharegistro.length; i++) {
            if (array_data_report_fecharegistro[i].checked)
                data_report_fecharegistro = array_data_report_fecharegistro[i].value;
        }

        var data_report_firma = "0";
        var array_data_report_firma = document.getElementsByName("data_report_firma");
        for (var i = 0; i < array_data_report_firma.length; i++) {
            if (array_data_report_firma[i].checked)
                data_report_firma = array_data_report_firma[i].value;
        }

        var data_report_eliminados = "0";
        var array_data_report_eliminados = document.getElementsByName("data_report_eliminados");
        for (var i = 0; i < array_data_report_eliminados.length; i++) {
            if (array_data_report_eliminados[i].checked)
                data_report_eliminados = array_data_report_eliminados[i].value;
        }

        var data_required = 'data_report_nombres=' + data_report_nombres + '&data_report_apellidos=' + data_report_apellidos + '&data_report_datosfacturacion=' + data_report_datosfacturacion + '&data_report_datoscontacto=' + data_report_datoscontacto + '&data_report_firma=' + data_report_firma + '&data_report_prefijo=' + data_report_prefijo + '&data_report_fecharegistro=' + data_report_fecharegistro + '&data_report_modoregistro=' + data_report_modoregistro + '&data_report_eliminados=' + data_report_eliminados;

        window.open('http://www.infosicoes.com/contenido/paginas.admin/ajax/ajax.impresion.cursos-participantes.exportar-lista.php?id_curso=<?php echo $id_curso; ?>&' + formato + '=true&' + data_required, 'popup', 'width=700,height=500');

    }
</script>



<!-- envio de factura -->
<script>
    function enviar_factura(id) {

        var email = $("#correo-de-envio-" + id).val();
        $("#box-modal_envia-factura-" + id).html("Enviando correo...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.instant.enviar_factura.php?nro_factura=' + id + '&email_a_enviar=' + email,
            data: {id: id},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_envia-factura-" + id).html(data);
            }
        });
    }
</script>
<script>
    function enviar_factura2(id) {

        var email = $("#correo-de-envio-" + id).val();
        $("#ffl-" + id).html("Enviando correo...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.instant.enviar_factura.php?nro_factura=' + id + '&email_a_enviar=' + email,
            data: {id: id},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ffl-" + id).html('<i class="btn btn-xs btn-default"><b class="fa fa-send"></b> Enviado!</i>');
            }
        });
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