<?php
//EFECTUADO DESDE 13 DE FEBRERO
//vista
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

$registros_a_mostrar = 40;
$start = ($vista - 1) * $registros_a_mostrar;

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

$resultado1 = query("SELECT *,(select count(*) from capacitaciones_participantes where id_capacitacion=c.id and estado='1' order by id desc)cnt_participantes FROM capacitaciones c ORDER BY fecha DESC,id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT id FROM capacitaciones ");

$total_registros = mysql_num_rows($resultado2);
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="capacitaciones-listar.adm">P&aacute;ginas de Cursos</a></li>
            <li class="active">Listado</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <a href="capacitaciones-crear.adm" class='btn btn-success active'> <i class='fa fa-plus'></i> AGREGAR CAPACITACION</a>
        </div>
        <h3 class="page-header"> Ciclos de Capacitaci&oacute;n <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de Ciclo de Capacitaci&oacute;n
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">
                <table class="table users-table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th class="visible-lg" style="font-size:10pt;">#</th>
                            <th class="visible-lg" style="font-size:10pt;">Depto.</th>
                            <th class="visible-lg" style="font-size:10pt;">Fecha</th>
                            <th class="visible-lg" style="font-size:10pt;">Curso</th>
                            <th class="visible-lg" style="font-size:10pt;">Vistas</th>
                            <th class="visible-lg" style="font-size:10pt;">Part.</th>
                            <th class="visible-lg" style="font-size:10pt;">Cert.</th>
                            <th class="visible-lg" style="font-size:10pt;">Lugar</th>
                            <th class="visible-lg" style="font-size:10pt;">Estado</th>
                            <th class="visible-lg" style="font-size:10pt;">Acci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($producto = mysql_fetch_array($resultado1)) {
                            ?>
                            <tr>
                                <td class="visible-lg"><?php echo $cnt--; ?></td>
                                <td class="visible-lg">
                                    <?php
                                    $rqciudad1 = query("SELECT nombre FROM departamentos WHERE id='" . $producto['id_ciudad'] . "' LIMIT 1 ");
                                    if (mysql_num_rows($rqciudad1) == 0) {
                                        echo "Sin dato registrado";
                                    } else {
                                        $rqciudad2 = mysql_fetch_array($rqciudad1);
                                        echo $rqciudad2['nombre'];
                                    }
                                    ?>         
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo my_date_curso($producto['fecha']);
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo ($producto['titulo']);
                                    echo "<br/>";
                                    echo "<i style='color:gray;'>Expositor: " . utf8_encode($producto['expositor']) . "</i>";
                                    echo "<br/>";
                                    echo "<i style='color:gray;'>" . $producto['short_link'] . "</i>";
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['cnt_reproducciones'];
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['cnt_participantes'] . " p.";
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    if ($producto['id_certificado'] == '0') {
                                        echo "No";
                                    } else {
                                        echo "<b class='text-success'>Si</b>";
                                    }
                                    ?>
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    if ($producto['lugar'] == '') {
                                        echo "Sin dato";
                                    } else {
                                        echo ($producto['lugar']);
                                    }
                                    ?>
                                </td>
                                <td class="visible-lg">   
                                    <?php
                                    if ($producto['estado'] == '1') {
                                        echo "<b style='color:green;'>Activado</b>";
                                    } elseif ($producto['estado'] == '2') {
                                        echo "<b style='color:red;'>Temporal</b>";
                                    } else {
                                        echo "Desactivado";
                                    }
                                    ?>         
                                </td>
                                <td class="visible-lg" style="width:120px;">
<!--                                    <a hhref="capacitaciones/<?php echo $producto['titulo_identificador']; ?>.html" target="_blank"><i class='fa fa-eye'></i> Visualizar</a>
                                    <br/>-->
                                    <a href="registro-capacitacion-infosicoes/<?php echo $producto['titulo_identificador']; ?>.html" target="_blank"><i class='fa fa-eye'></i> Registro</a>
                                    <br/>
                                    <a href="capacitaciones-editar/<?php echo $producto['id']; ?>.adm"><i class='fa fa-edit'></i> Editar</a>
                                    <br/>
                                    <a href="capacitaciones-cursos/<?php echo $producto['id']; ?>.adm"><i class='fa fa-desktop'></i> Cursos</a>
                                    <br/>
                                    <a href="capacitaciones-participantes/<?php echo $producto['id']; ?>.adm"><i class='fa fa-users'></i> Inscritos</a>
                                    <br/>
                                    <a onclick="duplicar_curso('<?php echo $producto['id']; ?>', '<?php echo str_replace('"', '', str_replace("'", '', $producto['titulo'])); ?>');" style="cursor:pointer;"><i class='fa fa-random'></i> Duplicar</a>
                                    <?php
                                    /*
                                      if ($producto['id_certificado'] !== '0') {
                                      ?>
                                      <br/>
                                      <a onclick="window.open('http://www.infosicoes.com/contenido/librerias/fpdf/tutorial/certificado-2-pre-impresion.php?id_certificado=<?php echo $producto['id_certificado']; ?>', 'popup', 'width=700,height=500');" style="cursor:pointer;"><i class='fa fa-file-pdf-o'></i> Pre-Certificado</a>
                                      <?php
                                      }
                                     */
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-md-12">
                        <ul class="pagination">
                            <?php
                            $urlget3 = '';
                            if (isset($get[3])) {
                                $urlget3 = '/' . $get[3];
                            }
                            $urlget4 = '';
                            if (isset($get[4])) {
                                $urlget4 = '/' . $get[4];
                            }
                            $urlget5 = '';
                            if (isset($buscar)) {
                                if ($urlget3 == '') {
                                    $urlget3 = '/--';
                                }
                                if ($urlget4 == '') {
                                    $urlget4 = '/--';
                                }
                                $urlget5 = '/' . $buscar;
                            }
                            ?>

                            <li><a href="capacitaciones-listar/1.adm">Primero</a></li>                           
                            <?php
                            $inicio_paginador = 1;
                            $fin_paginador = 15;
                            $total_capacitaciones = ceil($total_registros / $registros_a_mostrar);

                            if ($vista > 10) {
                                $inicio_paginador = $vista - 5;
                                $fin_paginador = $vista + 10;
                            }
                            if ($fin_paginador > $total_capacitaciones) {
                                $fin_paginador = $total_capacitaciones;
                            }

                            if ($total_capacitaciones > 1) {
                                for ($i = $inicio_paginador; $i <= $fin_paginador; $i++) {
                                    if ($vista == $i) {
                                        echo '<li class="active"><a href="productos/' . $i . '.adm">' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a href="capacitaciones-listar/' . $i . '.adm">' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>                            
                            <li><a href="capacitaciones-listar/<?php echo $total_capacitaciones; ?>.adm">Ultimo</a></li>
                        </ul>
                    </div><!-- /col-md-12 -->	
                </div>

            </div>
        </div>
    </div>
</div>

<!-- duplicar curso -->
<script>
    function duplicar_curso(id_curso, nombre_curso) {
        if (confirm('DUPLICACION DE CURSO - Desea duplicar el curso ' + nombre_curso+' ?')) {
            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.capacitaciones-listar.duplicar_curso.php',
                data: {id_curso: id_curso},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    location.href='capacitaciones-listar/1.adm';
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