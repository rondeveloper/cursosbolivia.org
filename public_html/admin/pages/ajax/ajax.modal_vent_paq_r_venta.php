<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (isset_administrador() && isset_post('dat')) {

    $id_empresa = post('dat');
    $type = post('type');
    $id_administrador_interactor = administrador('id');
    $fecha_interaccion = date("Y-m-d H:i");

    switch ($type) {
        case 'venta':
            $movimiento = "Venta realizada -> enviar datos -> asesorar en el pago";
            $resultado = "venta";
            $codigo_descuento = substr(md5(md5(rand(999, 9999999))), rand(0, 7), 7);
            break;
        case 'duda':
            $movimiento = "Cliente en dudas -> enviar datos -> volver a ofrecer el servicio";
            $resultado = "dudas";
            $codigo_descuento = "D-" . substr(md5(md5(rand(999, 9999999))), rand(0, 7), 5);
            break;
        case 'negativa':
            $movimiento = "Cliente no interesado en adquirir el servicio";
            $resultado = "negativa";
            $codigo_descuento = "";
            break;
        default:
            break;
    }

    //verif pre existencia
    $rqv1 = query("SELECT id FROM vent_paq_lista_uno WHERE id_empresa='$id_empresa' AND resultado='$resultado' ORDER BY id DESC limit 1 ");
    if (num_rows($rqv1) == 0) {

        query("INSERT INTO vent_paq_lista_uno (
        id_empresa,
        resultado,
        codigo_descuento,
        id_administrador_interactor,
        fecha_interaccion
        ) VALUES (
        '$id_empresa',
        '$resultado',
        '$codigo_descuento',
        '$id_administrador_interactor',
        '$fecha_interaccion'
        ) ");
        movimiento($movimiento, 'registro-v-vent-paq', 'usuario', $id_empresa);
    }

    $rqmemp1 = query("SELECT * FROM movimiento WHERE id_objeto='$id_empresa' AND proceso LIKE '%-vent-paq' ORDER BY id DESC limit 50 ");
    echo "<table class='table'>";
    if (num_rows($rqmemp1) == 0) {
        echo "<tr><td>No se tiene regsitros para este cliente.</td></tr>";
    }
    while ($rqmemp2 = fetch($rqmemp1)) {
        echo "<tr>";
        echo "<td class='text-left'>" . $rqmemp2['movimiento'] . "</td>";
        echo "<td>" . $rqmemp2['fecha'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Denegado!";
}

?>
