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

$data_required = "*,(c.fecha)dr_fecha_curso,(c.titulo)dr_titulo_curso,(select nombre from ciudades where id=c.id_ciudad)dr_departamento_curso,(c.estado)dr_estado_curso,(c.numero)dr_numero_curso,(a.nombre)dr_nombre_administrador,(rp.fecha)dr_fecha_generado,(rp.nombre)dr_nombre_pdf";
$resultado1 = query("SELECT $data_required FROM cursos_pdf_generados rp INNER JOIN cursos c ON rp.id_curso=c.id INNER JOIN administradores a ON rp.id_administrador=a.id WHERE date(rp.fecha)>c.fecha ORDER BY rp.fecha DESC LIMIT $start,$registros_a_mostrar");
$resultado_b1 = query("SELECT count(*) AS total FROM cursos_pdf_generados rp INNER JOIN cursos c ON rp.id_curso=c.id WHERE date(rp.fecha)>c.fecha");
$resultado_b2 = mysql_fetch_array($resultado_b1);

$total_registros = $resultado_b2['total'];
$cnt = $total_registros - ( ($vista - 1) * $registros_a_mostrar );
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li class="active">PDF's de certificados generados</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
<!--            <a href="certificados-modelos-crear.adm" class='btn btn-success active'> <i class='fa fa-plus'></i> EMISIONES DE CERTIFICADOS</a>-->
        </div>
        <h3 class="page-header"> PDF's de certificados GENERADOS FUERA DE FECHA <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                PDF's de certificados generados
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
                            <th class="visible-lg" style="font-size:10pt;">Fecha</th>
                            <th class="visible-lg" style="font-size:10pt;">Curso</th>
                            <th class="visible-lg" style="font-size:10pt;">Cantidad</th>
                            <th class="visible-lg" style="font-size:10pt;">Nombre PDF</th>
                            <th class="visible-lg" style="font-size:10pt;">Administrador</th>
                            <th class="visible-lg" style="font-size:10pt;">Acci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($producto = mysql_fetch_array($resultado1)) {
                            $ar1 = explode("P__", $producto['dr_nombre_pdf']);
                            $ar2 = explode("__", $ar1[0]);
                            $cnt_certs = (int)end($ar2);
                            ?>
                            <tr>
                                <td class="visible-lg"><?php echo $cnt--; ?></td>
                                <td class="visible-lg">
                                    <?php
                                    echo date("d/m/Y H:i", strtotime($producto['dr_fecha_generado']));
                                    ?> 
                                </td>
                                <td class="visible-lgNOT">
                                    <span style="font-size:11pt;"><?php echo $producto['dr_titulo_curso']; ?></span>
                                    <br/>
                                    <b><?php echo $producto['dr_departamento_curso']; ?></b>
                                    <br/>
                                    <?php echo date("d / M / Y", strtotime($producto['dr_fecha_curso'])); ?> &nbsp;&nbsp;&nbsp; <b>[<?php echo $producto['dr_numero_curso']; ?>]</b>
                                </td>
                                <td class="visible-lg text-center">
                                    <b style="color:#1d6381;font-size:11pt;"><?php echo $cnt_certs; ?></b>
                                    <br/>
                                    certificados
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['dr_nombre_pdf'];
                                    ?> 
                                </td>
                                <td class="visible-lg">
                                    <?php
                                    echo $producto['dr_nombre_administrador'];
                                    ?> 
                                </td>
                                <td class="visible-lg" style="width:120px;">
                                    <a onclick="window.open('http://cursos.bo/contenido/archivos/pdfcursos/<?php echo $producto['dr_nombre_pdf']; ?>', 'popup', 'width=700,height=500');" style="cursor:pointer;"><i class='fa fa-file-pdf-o'></i> VISUALIZAR</a>
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

                            <li><a href="certificados-pdfs-generados/1.adm">Primero</a></li>                           
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
                                        echo '<li><a href="certificados-pdfs-generados/' . $i . '.adm">' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>                            
                            <li><a href="certificados-pdfs-generados/<?php echo $total_cursos; ?>.adm">Ultimo</a></li>
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