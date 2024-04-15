<?php
//vista
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

$registros_a_mostrar = 500;
$start = ($vista - 1) * $registros_a_mostrar;

$total_registros = 0;

/* qr organizador */
$qr_organizador = "";
if (isset_organizador()) {
    $id_organizador = organizador('id');
    $qr_organizador = " AND id_curso IN (select id from cursos where id_organizador='$id_organizador' ) ";
}

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
if (isset_post('id_departamento')) {

    $busqueda = str_replace(' ', '%', post('input-buscador'));
    $busqueda_id_departamento = post('id_departamento');
    $busqueda_prefijo = post('prefijo');

    $qr_nombre = ' 1 ';
    if (strlen($busqueda) > 0) {
        $qr_nombre = " CONCAT(nombres,' ',apellidos) LIKE '%$busqueda%' ";
    }

    $qr_prefijo = '';
    if (strlen($busqueda_prefijo) > 0) {
        $qr_prefijo = " AND prefijo LIKE '%$busqueda_prefijo%' ";
    }

    $rq_departamento = '';
    if ($busqueda_id_departamento !== '0') {
        $rq_departamento = " AND id_curso IN (select id from cursos where id_ciudad='$busqueda_id_departamento' ) ";
    }

    $resultado1 = query("SELECT * FROM cursos_participantes WHERE ( $qr_nombre $qr_prefijo ) $rq_departamento $qr_organizador ORDER BY id DESC LIMIT $start,$registros_a_mostrar");
    $total_registros = num_rows($resultado1);

    /*
      $rq_departamento = '';
      if ($busqueda_id_departamento !== '0') {
      $rq_departamento = " AND c.id_ciudad='$busqueda_id_departamento' ";
      }
      $qr_busqueda = " WHERE ( CONCAT(cp.nombres,' ',cp.apellidos) LIKE '%$busqueda%' OR id_proceso_registro IN (select id from cursos_proceso_registro where codigo='$busqueda') ) $rq_departamento ";
      $vista = 1;
      $resultado1 = query("SELECT cp.prefijo,cp.nombres,cp.apellidos,cp.celular,cp.correo,(c.titulo)curso,(c.id)id_curso,(c.fecha)fecha_curso,(select count(*) from cursos_emisiones_certificados where id_participante=cp.id)cnt_certificados,(select nombre from departamentos dp where dp.id=c.id_ciudad)departamento FROM cursos_participantes cp INNER JOIN cursos c ON cp.id_curso=c.id $qr_busqueda ORDER BY cp.id DESC LIMIT $start,$registros_a_mostrar");
      $total_registros = num_rows($resultado1);
     */
}

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


$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );


//echo administrador('nivel')."<hr/>";
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="cursos-listar.adm">Cursos</a></li>
            <li class="active">Busqueda de participante</li>
        </ul>
        <!--        <div class="form-group hiddn-minibar pull-right">
                    <a href="cursos-crear.adm" class='btn btn-success active'> <i class='fa fa-plus'></i> AGREGAR CURSO</a>
                </div>-->
        <h3 class="page-header"> CURSOS - Busqueda de participante <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                CURSOS - Busqueda de participante
            </p>
        </blockquote>

        <form action="" method="post">
            <div class="col-md-6">
                <div class="input-group col-sm-12">
                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Buscador: </span>
                    <input type="text" name="input-buscador" value="<?php echo str_replace('%', ' ', $busqueda); ?>" class="form-control" placeholder="Ingrese nombre y/o apellidos ..."/>
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group col-sm-12">
                    <input type="text" name="prefijo" value="<?php echo str_replace('%', ' ', $busqueda_prefijo); ?>" class="form-control" placeholder="Prefijo..."/>
                </div>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="id_departamento">
                    <?php
                    echo "<option value='0'>Todos los departamentos...</option>";
                    $rqd1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' OR tipo='2' ORDER BY orden ");
                    while ($rqd2 = fetch($rqd1)) {
                        $text_check = '';
                        if ($id_departamento == $rqd2['id']) {
                            $text_check = ' selected="selected" ';
                        }
                        echo "<option value='" . $rqd2['id'] . "' $text_check>" . $rqd2['nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="submit" value="BUSCAR" class="btn btn-warning btn-block active"/>
            </div>
        </form>
    </div>
</div>

<div class="rowNOT">
    <hr/>
    <div class="col-md-12NOT">
        <div class="panelNOT">

            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table users-table table-condensed table-hover">
                        <thead>
                            <tr>
                                <th class="visible-lgNOT" style="font-size:10pt;">#</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Pref.</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Nombres</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Apellidos</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Contacto</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Curso</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Fecha de curso</th>
<!--                                <th class="visible-lgNOT" style="font-size:10pt;">Certificado</th>-->
                                <th class="visible-lgNOT" style="font-size:10pt;">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = fetch($resultado1)) {
                                /*
                                  $busqueda = str_replace(' ', '%', post('input-buscador'));
                                  $busqueda_id_departamento = post('id_departamento');
                                  $rq_departamento = '';
                                  if ($busqueda_id_departamento !== '0') {
                                  $rq_departamento = " AND c.id_ciudad='$busqueda_id_departamento' ";
                                  }
                                  $qr_busqueda = " WHERE ( CONCAT(cp.nombres,' ',cp.apellidos) LIKE '%$busqueda%' OR id_proceso_registro IN (select id from cursos_proceso_registro where codigo='$busqueda') ) $rq_departamento ";
                                  $vista = 1;
                                  $resultado1 = query("SELECT cp.prefijo,cp.nombres,cp.apellidos,cp.celular,cp.correo,(c.titulo)curso,(c.id)id_curso,(c.fecha)fecha_curso,(select count(*) from cursos_emisiones_certificados where id_participante=cp.id)cnt_certificados,(select nombre from departamentos dp where dp.id=c.id_ciudad)departamento FROM cursos_participantes cp INNER JOIN cursos c ON cp.id_curso=c.id $qr_busqueda ORDER BY cp.id DESC LIMIT $start,$registros_a_mostrar");
                                  $total_registros = num_rows($resultado1);
                                 */
                                $rqdc1 = query("SELECT titulo,fecha,(select nombre from departamentos where id=c.id_ciudad)departamento FROM cursos c WHERE c.id='".$producto['id_curso']."' LIMIT 1 ");
                                $curso = fetch($rqdc1);
                                ?>
                                <tr>
                                    <td class="visible-lgNOT"><?php echo $cnt--; ?></td>
                                    <td class="visible-lgNOT">
                                        <?php echo $producto['prefijo']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo $producto['nombres']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo $producto['apellidos']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo $producto['celular']; ?>
                                        <br/>
                                        <?php echo $producto['correo']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo $curso['titulo']; ?>
                                        <br/>
                                        <?php echo $curso['departamento']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo date("d / M / Y", strtotime($curso['fecha'])); ?>
                                    </td>
<!--                                    <td class="visible-lgNOT">
                                        <?php
                                        /*
                                        if ((int) $producto['cnt_certificados'] > 0) {
                                            echo "<i class='btn btn-xs btn-info active'>" . $producto['cnt_certificados'] . " certificado(s)</i>";
                                        } else {
                                            echo "<i class='btn btn-xs btn-default'>Sin certificados</i>";
                                        }
                                         */
                                        ?> 
                                    </td>-->
                                    <td class="visible-lgNOT" style="width:120px;">
                                        <a href="cursos-participantes/<?php echo $producto['id_curso']; ?>.adm" target="_blank"><i class='fa fa-eye'></i> Panel de curso</a>
                                    </td>
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
</div>

<!-- duplicar curso -->
<script>
    function duplicar_curso(id_curso, nombre_curso) {
        if (confirm('DUPLICACION DE CURSO - Desea duplicar el curso ' + nombre_curso + ' ?')) {
            $.ajax({
                url: 'pages/ajax/ajax.cursos-listar.duplicar_curso.php',
                data: {id_curso: id_curso},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    location.href = 'cursos-listar/1.adm';
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
        //return $ar1[2] . " " . $arraymes[(int)$ar1[1]] . " " . substr($ar1[0],2,2);
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]];
    }
}
?>