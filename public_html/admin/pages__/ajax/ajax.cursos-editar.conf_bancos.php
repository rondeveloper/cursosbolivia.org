<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');

$mensaje = '';

/*
if($id_curso=='2448'){
    $rqcr1 = query("SELECT id FROM cursos WHERE estado IN (1,2) OR sw_ipelc=1 ");
    while($rqcr2 = fetch($rqcr1)){
        $id_cur = $rqcr2['id'];
        query("INSERT INTO rel_cursocuentabancaria 
        (id_curso, id_cuenta, sw_cprin, sw_transbancunion, estado) 
        VALUES
        ('$id_cur', 1, 1, 0, 1),
        ('$id_cur', 9, 0, 1, 1),
        ('$id_cur', 4, 0, 0, 1),
        ('$id_cur', 5, 0, 0, 1),
        ('$id_cur', 6, 0, 0, 1),
        ('$id_cur', 7, 0, 0, 1),
        ('$id_cur', 8, 0, 0, 1);
        ");
    }
    echo "<hr><hr>[ OK ]<hr><hr>";
}
*/

/*
if($id_curso=='2448'){
    $rqcr1 = query("SELECT id FROM cursos WHERE estado IN (1,2) OR sw_ipelc=1 ");
    while($rqcr2 = fetch($rqcr1)){
        $id_cur = $rqcr2['id'];
        query("INSERT INTO rel_cursonumtigomoney 
        (id_curso, id_numtigomoney, estado) 
        VALUES
        ('$id_cur', 1, 1);
        ");
    }
    echo "<hr><hr>[ OK ]<hr><hr>";
}
*/

/* actualizar-conf */
if (isset_post('actualizar-conf')) {
    query("DELETE FROM rel_cursocuentabancaria WHERE id_curso='$id_curso' ");
    query("DELETE FROM rel_cursonumtigomoney WHERE id_curso='$id_curso' ");
    $rqdcb1 = query("SELECT c.* FROM cuentas_de_banco c INNER JOIN bancos b ON c.id_banco=b.id WHERE c.estado=1 ORDER BY c.id_banco ASC, c.id ASC ");
    while ($rqdcb2 = fetch($rqdcb1)) {
        $id_cuenta = $rqdcb2['id'];
        if(isset_post('cbanc-selec-'.$id_cuenta)){
            $sw_transfbunion = '0';
            if(isset_post('cbanc-transbancunion-'.$id_cuenta)){
                $sw_transfbunion = '1';
            }
            $sw_cuentaprin = '0';
            if(post('cbanc-principal') == $id_cuenta){
                $sw_cuentaprin = '1';
            }
            query("INSERT INTO rel_cursocuentabancaria 
            (id_curso,id_cuenta,sw_cprin,sw_transbancunion,estado) 
            VALUES 
            ('$id_curso','$id_cuenta','$sw_cuentaprin','$sw_transfbunion','1') ");
        }
    }
    $rqdntm1 = query("SELECT * FROM tigomoney_numeros WHERE estado=1 ORDER BY id ASC ");
    while ($rqdntm2 = fetch($rqdntm1)) {
        $id_numtigomoney = $rqdntm2['id'];
        if(isset_post('tigomoney-selec-'.$id_numtigomoney)){
            $sw_numprin = '0';
            if(post('num-principal') == $id_numtigomoney){
                $sw_numprin = '1';
            }
            query("INSERT INTO rel_cursonumtigomoney 
            (id_curso,id_numtigomoney,sw_numprin,estado) 
            VALUES 
            ('$id_curso','$id_numtigomoney','$sw_numprin','1') ");
        }
    }
    logcursos('Edicion de asignacion de cuentas bancarias y tigomoney', 'curso-edicion', 'curso', $id_curso);
    $mensaje .= '<br><div class="alert alert-success">
  <strong>EXITO</strong> el registro se actualizo correctamente.
</div>';
}

?>

<div class="row" style="min-height: 300px;background: #ececec;padding: 25px 0px;padding-bottom: 70px;">
    <div class="col-md-2"></div>
    <div class="col-md-8" style="background:#FFF;border: 1px solid #dbe5ef;">
        <?php echo $mensaje; ?>
        <form id="FORM-actualizar_conf_bancos">
        <hr>
            <b>BANCOS SELECCIONADOS COMO FORMA DE PAGO</b>
            <hr>
            <table class="table table-striped table-hover table-bordered table-responsive">
                <tr>
                    <th>Banco</th>
                    <th>N&uacute;mero de cuenta</th>
                    <th>Titular</th>
                    <th>Selecci&oacute;n</th>
                    <th>Principal</th>
                    <th>Tranferencia<br>desde cajero<br>banco union</th>
                </tr>
                <?php
                $rqdcb1 = query("SELECT c.*,b.nombre FROM cuentas_de_banco c INNER JOIN bancos b ON c.id_banco=b.id WHERE c.estado=1 ORDER BY c.id_banco ASC, c.id ASC ");
                while ($rqdcb2 = fetch($rqdcb1)) {
                    $id_cuenta = $rqdcb2['id'];
                    $rqveas1 = query("SELECT sw_cprin,sw_transbancunion FROM rel_cursocuentabancaria WHERE id_curso='$id_curso' AND id_cuenta='$id_cuenta' ORDER BY id DESC limit 1 ");
                    $htm_check_sel = '';
                    $htm_check_bprin = '';
                    $htm_check_transbancunion = '';
                    if(num_rows($rqveas1)>0){
                        $rqveas2 = fetch($rqveas1);
                        $htm_check_sel = ' checked="checked" ';
                        if($rqveas2['sw_cprin']=='1'){
                            $htm_check_bprin = ' checked="checked" ';
                        }
                        if($rqveas2['sw_transbancunion']=='1'){
                            $htm_check_transbancunion = ' checked="checked" ';
                        }
                    }
                ?>
                    <tr>
                        <td>
                            <?php echo $rqdcb2['nombre']; ?>
                        </td>
                        <td>
                            <?php echo $rqdcb2['numero_cuenta']; ?>
                        </td>
                        <td>
                            <?php echo $rqdcb2['titular']; ?>
                        </td>
                        <td>
                            <input type="checkbox" name="cbanc-selec-<?php echo $id_cuenta; ?>" value="1" style="width:21px;height:21px;" <?php echo $htm_check_sel; ?> />
                        </td>
                        <td>
                            <input type="radio" name="cbanc-principal" value="<?php echo $id_cuenta; ?>" style="width:21px;height:21px;" <?php echo $htm_check_bprin; ?> />
                        </td>
                        <td>
                            <?php if ($rqdcb2['id_banco'] == '1') { ?>
                                <input type="checkbox" name="cbanc-transbancunion-<?php echo $id_cuenta; ?>" value="1" style="width:21px;height:21px;" <?php echo $htm_check_transbancunion; ?> />
                            <?php } ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
            <hr>
            <b>N&Uacute;MEROS TIGOMONEY COMO FORMA DE PAGO</b>
            <hr>
            <table class="table table-striped table-hover table-bordered table-responsive">
                <tr>
                    <th>N&uacute;mero</th>
                    <th>Titular</th>
                    <th>Selecci&oacute;n</th>
                    <th>Principal</th>
                </tr>
                <?php
                $rqdntm1 = query("SELECT * FROM tigomoney_numeros WHERE estado=1 ORDER BY id ASC ");
                while ($rqdntm2 = fetch($rqdntm1)) {
                    $id_numtigomoney = $rqdntm2['id'];
                    $rqveas1 = query("SELECT sw_numprin FROM rel_cursonumtigomoney WHERE id_curso='$id_curso' AND id_numtigomoney='$id_numtigomoney' ORDER BY id DESC limit 1 ");
                    $htm_check_sel = '';
                    if(num_rows($rqveas1)>0){
                        $rqveas2 = fetch($rqveas1);
                        $htm_check_sel = ' checked="checked" ';
                        $htm_check_nprin = '';
                        if($rqveas2['sw_numprin']=='1'){
                            $htm_check_nprin = ' checked="checked" ';
                        }
                        
                    }
                ?>
                    <tr>
                        <td>
                            <?php echo $rqdntm2['numero']; ?>
                        </td>
                        <td>
                            <?php echo $rqdntm2['titular']; ?>
                        </td>
                        <td>
                            <input type="checkbox" name="tigomoney-selec-<?php echo $id_numtigomoney; ?>" value="1" style="width:21px;height:21px;" <?php echo $htm_check_sel; ?> />
                        </td>
                        <td>
                            <input type="radio" name="num-principal" value="<?php echo $id_numtigomoney; ?>" style="width:21px;height:21px;" <?php echo $htm_check_nprin; ?> />
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
            <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
            <input type="hidden" name="actualizar-conf" value="1" />
        </form>
        <b class="btn btn-primary" onclick="actualizar_conf_bancos();">ACTUALIZAR</b>
        <br>
        &nbsp;
    </div>
</div>


<!-- actualizar_conf_bancos -->
<script>
    function actualizar_conf_bancos() {
        var form = $("#FORM-actualizar_conf_bancos").serialize();
        $("#AJAXCONTENT-panel-m5").html('Cargando...');
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.conf_bancos.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-panel-m5").html(data);
            }
        });
    }
</script>