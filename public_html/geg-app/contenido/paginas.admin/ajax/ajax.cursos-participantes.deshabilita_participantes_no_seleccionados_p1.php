<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* verificador de acceso */
if (!isset_administrador()) {
    echo "Acceso denegado!";
    exit;
}

/* recepcion de datos POST */
$dat = post('dat');

/* limpia datos de id participante */
$ar_exp_aux = explode(",", str_replace('SL_BBL_locer', '0', $dat));
$ids_participantes = '0';
foreach ($ar_exp_aux as $value) {
    $ids_participantes .= "," . (int) $value;
}
?>

<h4 class='text-center'>&iquest; Desea deshabilitar a los siguientes participantes que no fueron seleccionados?</h4>

<?php
$rqp1 = query("SELECT nombres,apellidos FROM cursos_participantes WHERE id IN($ids_participantes) ORDER BY id DESC ");
while ($rqp2 = mysql_fetch_array($rqp1)) {
    echo $rqp2['nombres'] . ' ' . $rqp2['apellidos'];
    echo "<br/>";
}
?>

<br/>

<!-- DIV CONTENT AJAX :: DESHABILITACION DE PARTICIPANTES P2 -->
<div id="ajaxloading-deshabilita_participantes_no_seleccionados_p2"></div>
<div id="ajaxbox-deshabilita_participantes_no_seleccionados_p2">


    <div class="text-center">
        <b class="btn btn-danger active" onclick="deshabilita_participantes_no_seleccionados_p2();">DES-HABILITAR PARTICIPANTES</b>
        &nbsp;&nbsp;&nbsp;
        <b class="btn btn-warning active" onclick="" data-dismiss="modal">CANCELAR</b>
    </div>


</div>