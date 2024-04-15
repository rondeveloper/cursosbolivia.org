<?php
//EFECTUADO DESDE 13 DE FEBRERO
//vista
$vista = 1;
if (isset($get[2])) {
    $vista = $get[2];
}

$registros_a_mostrar = 150;
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

$resultado1 = query("SELECT *,(select titulo from cursos where id=e.id_curso order by id desc limit 1)curso,(select nombre from administradores where id=e.id_administrador_emisor)administrador,(select formato from cursos_certificados where id=e.id_certificado order by id desc limit 1)formato_certificado FROM cursos_emisiones_certificados e ORDER BY id DESC LIMIT $start,$registros_a_mostrar");
$resultado2 = query("SELECT id FROM cursos_emisiones_certificados ");

$total_registros = mysql_num_rows($resultado2);
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li class="active">Emisiones de certificados</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
<!--            <a href="certificados-modelos-crear.adm" class='btn btn-success active'> <i class='fa fa-plus'></i> EMISIONES DE CERTIFICADOS</a>-->
        </div>
        <h3 class="page-header"> Emisiones de certificados <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Emisiones de certificados
            </p>
        </blockquote>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">
                <table class="table table-hover table-responsive table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="visible-lg" style="font-size:10pt;">#</th>
                            <th class="visible-lg" style="font-size:10pt;">Receptor</th>
                            <th class="visible-lg" style="font-size:10pt;">ID de certificado</th>
                            <th class="visible-lg" style="font-size:10pt;">Curso</th>
                            <th class="visible-lg" style="font-size:10pt;">Emision</th>
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
                                    echo $producto['receptor_de_certificado'];
                                    ?>         
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['certificado_id'];
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo utf8_encode($producto['curso']);
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo date("d / M - H:i",  strtotime($producto['fecha_emision']));
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['administrador'];
                                    ?> 
                                </td>
                                <td class="visible-lg" style="width:120px;">
                                    <a onclick="window.open('http://cursos.bo/contenido/librerias/fpdf/tutorial/certificado-<?php echo $producto['formato_certificado']; ?>.php?id_certificado=<?php echo $producto['certificado_id']; ?>', 'popup', 'width=700,height=500');" style="cursor:pointer;"><i class='fa fa-file-pdf-o'></i> Visualizar</a>
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

                            <li><a href="certificados-emisiones/1.adm">Primero</a></li>                           
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
                                        echo '<li><a href="certificados-emisiones/' . $i . '.adm">' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>                            
                            <li><a href="certificados-emisiones/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
                        </ul>
                    </div><!-- /col-md-12 -->	
                </div>

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