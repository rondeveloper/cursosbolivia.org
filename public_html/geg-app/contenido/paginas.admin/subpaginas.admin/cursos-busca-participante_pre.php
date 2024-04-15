<?php
//EFECTUADO DESDE 13 DE FEBRERO
//vista
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}


$registros_a_mostrar = 50;
$start = ($vista - 1) * $registros_a_mostrar;

$resultado1 = query("SELECT id FROM cursos_participantes WHERE 0 ");

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
if (isset_post('input-buscador')) {
    $busqueda = str_replace(' ','%',post('input-buscador'));
    $qr_busqueda = " WHERE CONCAT(cp.nombres,' ',cp.apellidos) LIKE '%$busqueda%' OR id_proceso_registro IN (select id from cursos_proceso_registro where codigo='$busqueda') ";
    $vista = 1;
    $resultado1 = query("SELECT cp.prefijo,cp.nombres,cp.apellidos,cp.celular,cp.correo,(c.titulo)curso,(c.id)id_curso,(c.fecha)fecha_curso,(select count(*) from cursos_emisiones_certificados where id_participante=cp.id)cnt_certificados FROM cursos_participantes cp INNER JOIN cursos c ON cp.id_curso=c.id $qr_busqueda ORDER BY cp.id DESC LIMIT $start,$registros_a_mostrar");
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

$total_registros = mysql_num_rows($resultado1);
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );


//echo administrador('nivel')."<hr/>";
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
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
            <div class="input-group col-sm-12">
                <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Buscador: </span>
                <input type="text" name="input-buscador" value="<?php echo str_replace('%',' ',$busqueda); ?>" class="form-control" placeholder="Ingrese nombre y/o apellidos ..."/>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12NOT">
        <div class="panelNOT">

            <div class="panel-bodyNOT">
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
                                <th class="visible-lgNOT" style="font-size:10pt;">Certificado</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Acci&oacute;n</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($producto = mysql_fetch_array($resultado1)) {
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
                                        Cel: <?php echo $producto['celular']; ?>
                                        <br/>
                                        Email: <?php echo $producto['correo']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo $producto['curso']; ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php echo date("d / M / Y",strtotime($producto['fecha_curso'])); ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        if ((int)$producto['cnt_certificados']>0) {
                                            echo "<i class='btn btn-xs btn-info active'>".$producto['cnt_certificados']." certificado(s)</i>";
                                        } else {
                                            echo "<i class='btn btn-xs btn-default'>Sin certificados</i>";
                                        }
                                        ?> 
                                    </td>
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
                url: 'contenido/paginas.admin/ajax/ajax.cursos-listar.duplicar_curso.php',
                data: {id_curso: id_curso},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
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