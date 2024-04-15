<?php
/* mensaje */
$mensaje = '';

//EFECTUADO DESDE 13 DE FEBRERO
//vista
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

/* busqueda */
$qr_busqueda = "";
$busqueda = "";
$id_departamento = "0";
if (isset_post('input-buscador') || isset_post('id_departamento')) {
    $busqueda = post('input-buscador');
    $vista = 1;
    if (post('id_departamento') !== '0') {
        $id_departamento = post('id_departamento');
        $qr_busqueda = " WHERE (fecha LIKE '%$busqueda%' OR titulo LIKE '%$busqueda%' OR expositor LIKE '%$busqueda%' OR lugar LIKE '%$busqueda%') AND id_ciudad='$id_departamento' ";
    } else {
        $qr_busqueda = " WHERE fecha LIKE '%$busqueda%' OR titulo LIKE '%$busqueda%' OR expositor LIKE '%$busqueda%' OR lugar LIKE '%$busqueda%' ";
    }
}

$registros_a_mostrar = 40;
$start = ($vista - 1) * $registros_a_mostrar;

$sw_selec = false;


/* data admin */
$id_administrador = administrador('id');
$rqda1 = query("SELECT nivel FROM administradores WHERE id='$id_administrador' ");
$rqda2 = mysql_fetch_array($rqda1);
$nivel_administrador = $rqda2['nivel'];


/* eliminacion de curso */
if (isset_post('delete-course')) {
    if ($nivel_administrador == '1') {
        $id_curso_delete = post('id_curso');
        query("DELETE FROM cursos WHERE id='$id_curso_delete' ORDER BY id DESC limit 1 ");
        movimiento('Eliminacion de curso', 'eliminacion-curso', 'curso', $id_curso_delete);
        $mensaje = '<br/><div class="alert alert-success">
  <strong>Exito!</strong> registro eliminado.
</div>';
    }
}

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

$resultado1 = query("SELECT *,(select count(*) from cursos_participantes where id_curso=c.id and estado='1' order by id desc)cnt_participantes,(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and modo_pago='transferencia' order by id desc)cnt_participantes_2,(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and modo_pago='oficina' order by id desc)cnt_participantes_3,(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and modo_pago='khipu' order by id desc)cnt_participantes_4 FROM cursos c $qr_busqueda ORDER BY fecha DESC,id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT id FROM cursos $qr_busqueda ");

$total_registros = mysql_num_rows($resultado2);
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );



//echo $nivel_administrador."<hr/>";
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="cursos-listar.adm">Cursos</a></li>
            <li class="active">Listado</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <a href="cursos-crear.adm" class='btn btn-success active'> <i class='fa fa-plus'></i> AGREGAR CURSO</a>
        </div>
        <h3 class="page-header"> CURSOS INFOSICOES <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Listado de cursos de Cursos
            </p>
        </blockquote>

        <form action="" method="post">
            <div class="col-md-7">
                <div class="input-group col-sm-12">
                    <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Buscador: </span>
                    <input type="text" name="input-buscador" value="<?php echo $busqueda; ?>" class="form-control" placeholder="Titulo / Lugar / Expositor / Fecha ..."/>
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-control" name="id_departamento">
                    <?php
                    echo "<option value='0'>Todos los departamentos...</option>";
                    $rqd1 = query("SELECT id,nombre FROM departamentos WHERE tipo='1' OR tipo='2' ORDER BY orden ");
                    while ($rqd2 = mysql_fetch_array($rqd1)) {
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


<?php echo $mensaje; ?>

<hr/>

<div class="row">
    <div class="col-md-12NOT">
        <div class="panelNOT">

            <div class="panel-bodyNOT">
                <div class="table-responsive">
                    <table class="table users-table table-condensed table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="visible-lgNOT" style="font-size:10pt;">#</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Img.</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Depto.</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Fecha</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Costo</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Curso</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Vistas</th>
                                <?php if ($nivel_administrador == '1') { ?>
                                    <th class="visible-lgNOT" style="font-size:10pt;">Part.</th>
                                <?php } ?>
                                <th class="visible-lgNOT" style="font-size:10pt;">Cert.</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Lugar</th>
                                <th class="visible-lgNOT" style="font-size:10pt;">Estado</th>
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
                                        <?php
                                        $url_img_curso = "https://www.infosicoes.com/paginas/" . $producto['imagen'] . ".size=2.img";
                                        $url_img_curso = "contenido/imagenes/paginas/" . $rc2['imagen'];
                                        $url_img_curso = "paginas/" . $producto['imagen'] . ".size=2.img";
                                        if (!file_exists("contenido/imagenes/paginas/" . $producto['imagen'])) {
                                            $url_img_curso = "https://www.infosicoes.com/paginas/" . $producto['imagen'] . ".size=2.img";
                                        }
                                        ?>
                                        <img src="<?php echo $url_img_curso; ?>" style="height:50px;width:75px;overflow:hidden;border-radius: 7px;opacity: .8;"/>
                                    </td>
                                    <td class="visible-lgNOT">
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
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo my_date_curso($producto['fecha']);
                                        if ($producto['sw_fecha2'] == '1') {
                                            echo "<br/>";
                                            echo "<i style='color:gray;'>" . my_date_curso($producto['fecha2']) . "</span>";
                                        }
                                        if ($producto['sw_fecha3'] == '1') {
                                            echo "<br/>";
                                            echo "<i style='color:gray;'>" . my_date_curso($producto['fecha3']) . "</span>";
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo $producto['costo'] . ' Bs';
                                        if ($producto['sw_fecha2'] == '1') {
                                            echo "<br/>";
                                            echo "<i style='color:gray;'>" . $producto['costo2'] . " Bs</span>";
                                        }
                                        if ($producto['sw_fecha3'] == '1') {
                                            echo "<br/>";
                                            echo "<i style='color:gray;'>" . $producto['costo3'] . " Bs</span>";
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo ($producto['titulo']);
                                        echo "<br/>";
                                        echo "<i style='color:gray;'>Expositor: " . utf8_encode($producto['expositor']) . "</i>";
                                        echo "<br/>";
                                        echo "<i style='color:gray;'>" . $producto['short_link'] . "</i>";
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        echo $producto['cnt_reproducciones'];
                                        ?>
                                    </td>
                                    <?php if ($nivel_administrador == '1') { ?>
                                        <td class="visible-lgNOT">
                                            <?php
                                            echo $producto['cnt_participantes'] . " participantes";
                                            echo "<br/>";
                                            echo "<span style='font-size:7pt;color:gray;'>" . $producto['cnt_participantes_2'] . " transferencia</span>";
                                            echo "<br/>";
                                            echo "<span style='font-size:7pt;color:gray;'>" . $producto['cnt_participantes_3'] . " oficina</span>";
                                            echo "<br/>";
                                            echo "<span style='font-size:7pt;color:gray;'>" . $producto['cnt_participantes_4'] . " khipu</span>";
                                            echo "<br/>";
                                            echo "<span style='font-size:7pt;color:gray;'>" . ($producto['cnt_participantes'] - $producto['cnt_participantes_2'] - $producto['cnt_participantes_3'] - $producto['cnt_participantes_4']) . " sin pago</span>";
                                            ?>
                                        </td>
                                    <?php } ?>
                                    <td class="visible-lgNOT">
                                        <?php
                                        if ($producto['id_certificado'] == '0') {
                                            echo "No";
                                        } else {
                                            echo "<b class='text-success'>Si</b>";
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">
                                        <?php
                                        if ($producto['lugar'] == '') {
                                            echo "Sin dato";
                                        } else {
                                            echo ($producto['lugar']);
                                        }
                                        ?>
                                    </td>
                                    <td class="visible-lgNOT">   
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
                                    <td class="visible-lgNOT" style="width:120px;">
                                        <a href="<?php echo $producto['titulo_identificador']; ?>.html" target="_blank"><i class='fa fa-eye'></i> Visualizar</a>
                                        <br/>
                                        <a href="cursos-editar/<?php echo $producto['id']; ?>.adm"><i class='fa fa-edit'></i> Editar</a>
                                        <br/>
                                        <a href="cursos-participantes/<?php echo $producto['id']; ?>.adm"><i class='fa fa-users'></i> Inscritos</a>
                                        <br/>
                                        <a onclick="duplicar_curso('<?php echo $producto['id']; ?>', '<?php echo str_replace('"', '', str_replace("'", '', $producto['titulo'])); ?>');" style="cursor:pointer;"><i class='fa fa-random'></i> Duplicar</a>
                                        <?php
                                        if (($nivel_administrador == '1') && (int) $producto['cnt_participantes'] == 0) {
                                            ?>
                                            <br/>
                                            <form action="" method="post">
                                                <input type="hidden" name="id_curso" value="<?php echo $producto['id']; ?>"/>
                                                <input type="hidden" name="delete-course" value="true"/>
                                                <button type="submit" style="cursor:pointer;" class="btn btn-default btn-xs" onclick="return confirm('Desea eliminar el curso?');">
                                                    <i class='fa fa-ban text-danger'></i> Eliminar
                                                </button>
                                            </form>
                                            <?php
                                        }
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
                </div>

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

                            <li><a href="cursos-listar/1.adm">Primero</a></li>                           
                            <?php
                            $inicio_paginador = 1;
                            $fin_paginador = 15;
                            $total_cursos = ceil($total_registros / $registros_a_mostrar);

                            if ($vista > 10) {
                                $inicio_paginador = $vista - 5;
                                $fin_paginador = $vista + 10;
                            }
                            if ($fin_paginador > $total_cursos) {
                                $fin_paginador = $total_cursos;
                            }

                            if ($total_cursos > 1) {
                                for ($i = $inicio_paginador; $i <= $fin_paginador; $i++) {
                                    if ($vista == $i) {
                                        echo '<li class="active"><a href="productos/' . $i . '.adm">' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a href="cursos-listar/' . $i . '.adm">' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>                            
                            <li><a href="cursos-listar/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
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
        if (confirm('DUPLICACION DE CURSO - Desea duplicar el curso ' + nombre_curso + ' ?')) {
            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.cursos-listar.duplicar_curso.php',
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