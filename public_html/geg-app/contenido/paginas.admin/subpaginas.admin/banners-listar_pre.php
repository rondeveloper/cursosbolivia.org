<?php

if (isset($get[2])) {
    $vista = (int)$get[2];
} else {
    $vista = 1;
}

$registros_a_mostrar = 20;

$start = ($vista-1)*$registros_a_mostrar;

$rr1 = query("SELECT * FROM banners WHERE seccion='principal' ORDER BY estado DESC,id DESC LIMIT $start,$registros_a_mostrar ");
$rrt1 = query("SELECT COUNT(*) AS total FROM banners WHERE seccion='principal' ORDER BY id DESC ");
$rrt2 = mysql_fetch_array($rrt1);
$total_registros = $rrt2['total'];

?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="banners-listar.adm">Banners</a></li>
            <li class="active">listado todos</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <form action="" method="post">
                <input type="text" name="buscar" class="form-control form-cascade-control " size="20" placeholder="Buscar en el Sitio">
                <span class="input-icon fui-search"></span>
            </form>
        </div>
        <h3 class="page-header"> Banners <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                En esta seccion se muestran los banners de Infosicoes.
            </p>
        </blockquote>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <div class="panel-body">
                <table class="table users-table table-condensed table-hover">
<!--    <table width="95%" class="tabla_2">-->
                    <tr>
                        <th class="visible-lg">Nro</th>
                        <th class="visible-lg">IMAGEN</th>
                        <th class="visible-lg">DESCRIPCION</th>
                        <th class="visible-lg">MES</th>
                        <th class="visible-lg">DIA</th>
                        <th class="visible-lg">URL</th>
                        <th class="visible-lg">APERTURA</th>
                        <th class="visible-lg">ESTADO</th>
                        <th class="visible-lg">ACCION</th>
                    </tr>
                    <?php
                    $num = $total_registros - (($vista-1) * $registros_a_mostrar);
                    while ($datos = mysql_fetch_array($rr1)) {
                        ?>
                        <tr>
                            <td class="visible-lg">
                                <?php echo $num--; ?>
                            </td>
                            <td style="text-align: center;">
                                <img src="banners/<?php echo $datos['imagen']; ?>.size=2.img" style="width:220px;min-height:80px;background:#f5f5f5;"/>
                            </td>
                            <td class="visible-lg"><?php echo $datos['descripcion']; ?></td>
                            <td class="visible-lg"><?php echo $datos['mes_referencia']; ?></td>
                            <td class="visible-lg"><?php echo $datos['dia_referencia']; ?></td>
                            <td class="visible-lg"><?php echo $datos['url']; ?></td>
                            <td class="visible-lg"><?php
                                switch ($datos['target']) {
                                    case '_blank':echo 'Ventana Nueva';
                                        break;
                                    case '_self':echo 'Misma Ventana';
                                        break;
                                }
                                ?>
                            </td>
                            <td class="visible-lg"><?php
                                switch ($datos['estado']) {
                                    case '0':echo '<span style="color:red;">Oculto</span>';
                                        break;
                                    case '1':echo '<b style="color:green;">Publico</b>';
                                        break;
                                }
                                ?>
                            </td>
                            <td class="visible-lg" style="min-width: 100px;">
                                <i class="fa fa-edit"></i> <a href="banners-editar/<?php echo $datos['id']; ?>.adm"> Editar </a>
                                <br/>
                                <i class="fa fa-times-circle"></i> <a href="banners-eliminar/<?php echo $datos['id']; ?>.adm"> Eliminar</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
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
                            ?>

                            <li><a href="banners-listar/1<?php echo $urlget3 . $urlget4; ?>.adm">Primero</a></li>                           
                            <?php
                            $inicio_paginador = 1;
                            $fin_paginador = 15;
                            $total_paginas = ceil($total_registros / $registros_a_mostrar);

                            if ($vista > 10) {
                                $inicio_paginador = $vista - 5;
                                $fin_paginador = $vista + 10;
                            }
                            if ($fin_paginador > $total_paginas) {
                                $fin_paginador = $total_paginas;
                            }

                            if ($total_paginas > 1) {
                                for ($i = $inicio_paginador; $i <= $fin_paginador; $i++) {
                                    if ($vista == $i) {
                                        echo '<li class="active"><a href="productos/' . $i . $urlget3 . $urlget4 . '.adm">' . $i . '</a></li>';
                                    } else {
                                        echo '<li><a href="banners-listar/' . $i . $urlget3 . $urlget4 . '.adm">' . $i . '</a></li>';
                                    }
                                }
                            }
                            ?>                            
                            <li><a href="banners-listar/<?php echo $total_paginas; ?><?php echo $urlget3 . $urlget4; ?>.adm">Ultimo</a></li>
                        </ul>								
                    </div><!-- /col-md-12 -->	
                </div>


            </div>
        </div>
    </div>
</div>









