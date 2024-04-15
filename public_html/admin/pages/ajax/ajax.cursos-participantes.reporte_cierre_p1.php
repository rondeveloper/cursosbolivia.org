<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* datos de configuracion */
$sw_facturacion_modulos = $__CONFIG_MANAGER->getSw('sw_facturacion_modulos');

/* data */
$id_curso = post('id_curso');
$id_turno = (int)post('id_turno');

/* curso */
$rqdc1 = query("SELECT sw_cierre,id_cierre,fecha,sw_ipelc FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);

$sw_cierre = $rqdc2['sw_cierre'];
$id_cierre = $rqdc2['id_cierre'];
$fecha_curso = $rqdc2['fecha'];
$sw_ipelc = $rqdc2['sw_ipelc'];
?>
<p>Seleccione los campos necesarios para el reporte.</p>
<form id="FORM-reporte_cierre" name="FORM-reporte_cierre" action="" method="post">
    <table class="table table-striped table-bordered">
        <tr>
            <td class="text-center">
                <input type="checkbox" name="data_report_nombres" style="width:20px;height:20px;" checked=""/>
            </td>
            <td>Nombres</td>
            <td class="text-center">
                <input type="checkbox" name="data_report_apellidos" style="width:20px;height:20px;" checked=""/>
            </td>
            <td>Apellidos</td>
        </tr>
        <tr>
            <td class="text-center">
                <input type="checkbox" name="data_report_prefijo" style="width:20px;height:20px;"/>
            </td>
            <td>Prefijo</td>
            <td class="text-center">
                <input type="checkbox" name="data_report_departamento" style="width:20px;height:20px;" checked=""/>
            </td>
            <td>Departamento</td>
        </tr>
        <tr>
            <td class="text-center">
                <input type="checkbox" name="data_report_celular" style="width:20px;height:20px;" checked=""/>
            </td>
            <td>Celular</td>
            <td class="text-center">
                <input type="checkbox" name="data_report_correo" style="width:20px;height:20px;" checked=""/>
            </td>
            <td>Correo</td>
        </tr>
        <tr>
            <td class="text-center">
                <input type="checkbox" name="data_report_modoregistro" style="width:20px;height:20px;"/>
            </td>
            <td>Modo de registro</td>
            <td class="text-center">
                <input type="checkbox" name="data_report_fecharegistro" style="width:20px;height:20px;"/>
            </td>
            <td>Fecha de registro</td>
        </tr>
        <tr>
            <td class="text-center">
                <input type="checkbox" name="data_report_datosfacturacion" style="width:20px;height:20px;" <?php echo (!$sw_facturacion_modulos)?'disabled=""':''; ?> />
            </td>
            <td>Datos de Facturaci&oacute;n</td>
            <td class="text-center">
                <input type="checkbox" name="data_report_firma" style="width:20px;height:20px;" />
            </td>
            <td>Firma</td>
        </tr>
        <tr>
            <td class="text-center">
                <input type="checkbox" name="data_report_montopago" style="width:20px;height:20px;"/>
            </td>
            <td>Monto de pago</td>
            <td class="text-center">
                <input type="checkbox" name="data_report_regpago" style="width:20px;height:20px;"/>
            </td>
            <td>Registro de pago</td>
        </tr>
        <tr>
            <td class="text-center">
                <input type="checkbox" name="data_numeracion_certificado" style="width:20px;height:20px;"/>
            </td>
            <td>Numeraci&oacute;n de certificado</td>
            <td class="text-center">
                <input type="checkbox" name="data_report_eliminados" style="width:20px;height:20px;"/>
            </td>
            <td>Participantes eliminados</td>
        </tr>
        <tr>
            <td class="text-center">
                <input type="checkbox" name="data_solo_facturados" style="width:20px;height:20px;" id="checkbox_data_solo_facturados" onchange="verifica_r_solofacturados();" <?php echo (!$sw_facturacion_modulos)?'disabled=""':''; ?>/>
            </td>
            <td>Solo facturados</td>
            <td class="text-center">
                <input type="checkbox" name="data_ci" style="width:20px;height:20px;"/>
            </td>
            <td>C.I.</td>
        </tr>
        <tr>
            <td class="text-center">
                <input type="checkbox" name="data_incluir_conpago" style="width:20px;height:20px;" checked=""/>
            </td>
            <td>Con pago</td>
            <td class="text-center">
                <input type="checkbox" name="data_incluir_sinpago" style="width:20px;height:20px;"/>
            </td>
            <td>Sin pago.</td>
        </tr>
        <tr>
            <td class="text-center">
                <input type="checkbox" name="data_incluir_notas" style="width:20px;height:20px;"/>
            </td>
            <td>Incluir notas</td>
        </tr>
    </table>

    <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>
    <input type="hidden" name="id_turno" value="<?php echo $id_turno; ?>"/>
</form>

<hr/>

<input type="hidden" id="idturno" value="0"/>

<?php
if ((int) $sw_cierre == 1) {
    echo '<div class="alert alert-info">
  <strong>Aviso!</strong> el curso ya se encuentra cerrado y no es necesario generar otro cierre.
</div>';
}

if ((int) $sw_cierre == 0 && $id_cierre !== '0') {
    echo '<div class="alert alert-warning">
  <strong>CIERRE DE CURSO REQUERIDO!</strong> el curso tuvo modificaciones desde el ultimo cierre, por lo cual se requiere realizar un nuevo cierre de curso.
</div>';
}


$id_administrador = administrador('id');
$rqdna1 = query("SELECT nivel FROM administradores WHERE id='$id_administrador' LIMIT 1 ");
$rqdna2 = fetch($rqdna1);
$nivel_administrador = $rqdna2['nivel'];


if (acceso_cod('adm-cursos-cierre') && false) {
    if (strtotime($fecha_curso) < strtotime(date("Y-m-d"))) {
        ?>

        <div class="panel-footer text-center">
            <button class="btn btn-info active" onclick="reporte_cierre_p2('cierre');" id="boton-cierre">CIERRE DE CURSO / REPORTE</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button class="btn btn-primary active" onclick="reporte_cierre_p2('soloreporte');">SOLO REPORTE</button>
        </div>

        <?php
    } else {
        ?>

        <div class="panel-footer text-center">
            <button class="btn btn-primary active" onclick="reporte_cierre_p2('soloreporte');">GENERAR REPORTE</button>
            <br/>
            <hr/>
            <button class="btn btn-default  btn-xs" onclick="reporte_cierre_p2('cierre');" id="boton-cierre">CIERRE DE CURSO / REPORTE</button>
        </div>

        <?php
    }
} else {
    ?>

    <div class="panel-footer text-center">
        <button class="btn btn-primary active" onclick="reporte_cierre_p2('soloreporte');">GENERAR REPORTE</button>
    </div>

    <?php
}


/* reporte IPELC */
if ($sw_ipelc=='1') {
    ?>
    <br>
    <hr>
    <br>
    <b>GENERAR REPORTE IPELC</b>
    <br>
    <button class="btn btn-warning active" onclick="reporte_ipelc();">REPORTE IPELC</button>
    <?php
}

?>
<script>
    function verifica_r_solofacturados(){
        if(document.getElementById("checkbox_data_solo_facturados").checked === true){
            document.getElementById("boton-cierre").disabled = true;
        }else{
            document.getElementById("boton-cierre").disabled = false;
        }
    }
</script>

<script>
    function reporte_ipelc() {
        window.open('<?php echo $dominio_admin; ?>pages/ajax/ajax.impresion.cursos-participantes.reporte-ipelc.php?id_curso=<?php echo $id_curso; ?>&excel=true', 'popup', 'width=700,height=500');
    }
</script>
