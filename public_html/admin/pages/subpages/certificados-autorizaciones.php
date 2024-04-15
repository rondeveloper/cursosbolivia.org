<?php

$sw_aprobador = false;
if(isset($get[2])){
    $id_administrador_solicitante = $get[2];
}else{
    $id_administrador_solicitante = administrador('id');
    $sw_aprobador = true;
}


/* administrador */
$rqadm1 = query("SELECT nombre FROM administradores WHERE id='$id_administrador_solicitante' ORDER BY id DESC limit 1 ");
if(num_rows($rqadm1)==0){
    echo "<script>alert('Error');location.href='$dominio_admin';</script>";
    exit;
}
$rqadm2 = fetch($rqadm1);
$nombre_adminsitrador_solicitante = strtoupper($rqadm2['nombre']);

$res_hoy = query("SELECT 
a.*,(adm.nombre)dr_nombre_admin_solicitante,(clog.costo)dr_costo_registrado 
FROM certificados_autorizaciones a 
LEFT JOIN administradores adm ON adm.id=a.id_administrador_solicitante 
LEFT JOIN certsgenimp_log clog ON clog.id=a.id_certsgenimp_log  
WHERE DATE(a.fecha_solicitud)=CURDATE() OR DATE(a.fecha_solicitud)='2023-04-24'
ORDER BY a.id DESC ");

$cnt_solicitudes_hoy = num_rows($res_hoy);
?>
<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'pages/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li class="active">Certificados autorizaciones</li>
        </ul>
        <h3 class="page-header"> Certificados autorizaciones digitales <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Certificados autorizaciones
            </p>
        </blockquote>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel">

            <h3>Administrador solicitante: <?= $nombre_adminsitrador_solicitante ?></h3>

            <hr>

            <b>Impresiones solicitadas el dia de hoy:</b> <?= $cnt_solicitudes_hoy ?>

            <br>

            <div class="panel-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="font-size:10pt;">#</th>
                            <th style="font-size:10pt;">SOLICITANTE</th>
                            <th style="font-size:10pt;">CERTIFICADO</th>
                            <th style="font-size:10pt;">FECHAS</th>
                            <th style="font-size:10pt;">ESTADO</th>
                            <th style="font-size:10pt;">ACCI&Oacute;N</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 1;
                        while ($row = fetch($res_hoy)) {
                        ?>
                            <tr>
                                <td><?php echo $cnt++; ?></td>
                                <td>
                                    <span style="font-size: 14pt;color:blue;"><?= $row['dr_nombre_admin_solicitante'] ?></span>
                                    <br>
                                    <br>
                                    <b>Fecha Solicitud:</b>
                                    <br>
                                    <?= $row['fecha_solicitud'] ?> 
                                </td>
                                <td>
                                    <b>Id Emisi&oacute;n Certificado:</b> <?= $row['id_emision_certificado'] ?>
                                    <br> 
                                    <b>Costo registrado:</b>  <b style="font-size: 14pt;color:blue;"><?= (int)$row['dr_costo_registrado'] ?> BS</b>
                                </td>
                                <td>
                                    <b>Fecha Solicitud:</b> <?= $row['fecha_solicitud'] ?><br> 
                                    <b>Fecha Aprobaci&oacute;n:</b> <?= $row['fecha_aprobacion'] ?><br> 
                                    <b>Fecha Registro:</b> <?= $row['fecha_registro'] ?><br>
                                    <b>Observacion:</b> <?= $row['observacion'] ?>
                                </td>
                                <td>
                                    <?php 
                                    switch($row['estado']){
                                        case 0:
                                            echo '<b style="color:blue;">EN ESPERA</b>';
                                        break;
                                        case 1:
                                            $id_administrador_autorizador = $row['id_administrador_autorizador'];
                                            $rqadmapr1 = query("SELECT nombre FROM administradores WHERE id='$id_administrador_autorizador' LIMIT 1");
                                            $rqadmapr2 = fetch($rqadmapr1);
                                            $nombre_admin_autorizador = $rqadmapr2['nombre'];
                                            echo '<b style="color:green;">AUTORIZADO</b>';
                                            echo '<br>';
                                            echo '<b onclick="imprimir_certificado_individual('.$row["id_emision_certificado"].')" class="btn btn-success btn-sm">IMPRIMIR</b>';
                                            echo '<br>';
                                            echo '<br>';
                                            echo '<b>Autorizado por:</b><br>'.$nombre_admin_autorizador;
                                            echo '<br>';
                                            echo '<b>En fecha:</b><br>'.$row['fecha_aprobacion'];
                                        break;
                                        case 2:
                                            echo '<b style="color:red;">DENEGADO</b>';
                                        break;
                                        default:
                                            echo '';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if($row['estado'] == 0){ ?>
                                        <b onclick="aprobar(<?=$row['id'] ?>)" class="btn btn-success active">APROBAR</b>
                                    <br>
                                        <b onclick="denegar(<?=$row['id'] ?>)" class="btn btn-danger active">DENEGAR</b>      
                                    <?php } ?>
                                  
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
<!-- imprimir certificado individual -->
<script>
    function imprimir_certificado_individual(dat) {
        if(confirm('DESEA VISUALIZAR EL CERTIFICADO ?')){
            $("#MODAL-modgeneral").modal('show');
            $('#TITLE-modgeneral').html('NUMERO DE SERIE DE CERTIFICADO');
            $("#AJAXCONTENT-modgeneral").html('Procesando...');
            $.ajax({
                url: 'pages/ajax/ajax.cursos-participantes.pago_impresion_certificado.php',
                data: {
                    ids_emisiones: dat,
                    modimp: 'imp-fisico'
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                }
            });
        }
    }
</script>
<!-- aprobar -->
<script>
    function aprobar(id_certificado_autorizacion) {
        if (confirm('ESTA SEGURO DE APROBAR ?')) {
            $("#TITLE-modgeneral").html('APROBAR CERTIFICADO AUTORIZACI&Oacute;N');
            $("#AJAXCONTENT-modgeneral").html('Cargando...');
            $("#MODAL-modgeneral").modal('show');
            $.ajax({
                url: 'pages/ajax/ajax.certificados-autorizaciones.aprobar-denegar.php',
                data: {
                    id_certificado_autorizacion: id_certificado_autorizacion,
                    aprobar:'aprobar'
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                    setTimeout(function(){
                        window.location.href='certificados-autorizaciones.adm';
                    },1500)
                }
            });
        }
    }
</script>
<!-- denegar -->
<script>
    function denegar(id_certificado_autorizacion) {
        if (confirm('ESTA SEGURO DE DENEGAR ?')) {
            $("#TITLE-modgeneral").html('DENEGAR CERTIFICADO AUTORIZACI&Oacute;N');
            $("#AJAXCONTENT-modgeneral").html('Cargando...');
            $("#MODAL-modgeneral").modal('show');
            $.ajax({
                url: 'pages/ajax/ajax.certificados-autorizaciones.aprobar-denegar.php',
                data: {
                    id_certificado_autorizacion: id_certificado_autorizacion,
                    denegar:'denegar'
                },
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#AJAXCONTENT-modgeneral").html(data);
                    setTimeout(function(){
                    window.location.href='certificados-autorizaciones.adm'
                    },1500)
                }
            });
        }
    }
</script>

