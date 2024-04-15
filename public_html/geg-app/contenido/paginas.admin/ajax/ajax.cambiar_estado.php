<?php

session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (isset_administrador() && isset_post('id_valor')) {
    $valores = explode('_separator_', post('id_valor'));

    $id_empresa = (int) $valores[0];
    $valor = $valores[1];


    $estado_muestra = '';
    switch ($valor) {
        case '0': $estado_muestra = 'No-Autentificado';
            break;
        case '1': $estado_muestra = 'Autentificado';
            break;
        case '2': $estado_muestra = 'Oculto';
            break;
        case '3': $estado_muestra = 'Dado-de-baja';
            break;
        default : $estado_muestra = 'Estado alterado';
            break;
    }

    $r1 = query("UPDATE empresas SET estado='$valor' WHERE id='$id_empresa' ORDER BY id DESC limit 1 ");
    if ($r1) {
        movimiento('Edicion de empresa [cambio de estado] [' . $estado_muestra . ']', 'edicion-empresa', 'usuario', $id_empresa);
        $rqpaq1 = query("SELECT id_paquete FROM empresas WHERE id='$id_empresa' ORDER BY id DESC limit 1 ");
        $rqpaq2 = mysql_fetch_array($rqpaq1);
        
        $rqpap1 = query("SELECT id FROM paquetes_asignados WHERE id_usuario='$id_empresa' ORDER BY id DESC limit 1 ");
        $numero_de_paquetes_asignados_previamente = mysql_num_rows($rqpap1);
        
        if ( ($rqpaq1['id_paquete'] == '1' || $rqpaq1['id_paquete'] == '') && ($numero_de_paquetes_asignados_previamente==0) ) {
            query("UPDATE empresas SET id_paquete='8',paquete_inicio='" . date("Y-m-d") . "' WHERE id='$id_empresa' ORDER BY id DESC limit 1 ");
            movimiento('Cambio de paquete [Sin-paquete -> GRATUITO]', 'cambio-paquete', 'usuario', $id_empresa);
            query("INSERT INTO paquetes_asignados(
              id_usuario, 
              id_paquete, 
              duracion, 
              fecha_inicio,
              fecha_final,
              monto_pago, 
              persona_pago, 
              observaciones,
              id_administrador,
              fecha_registro
              ) VALUES (
              '$id_empresa',
              '8',
              '0',
              '".date("Y-m-d")."',
              '".date("Y-m-d",  strtotime('+1 month',strtotime(date("Y-m-d"))))."',
              '0',
              'Sistema',
              'Asignacion de paquete gratuito desde el modulo de autenticacion [desde panel de administracion]',
              '".administrador('id')."',
              '".date("Y-m-d H:i:s")."'
              )");
        }


        echo " " . $estado_muestra . " <img src='contenido/imagenes/images/bien.png' style='width:25px;'>  Estado Actualizado!";
    } else {
        echo "Error";
    }
} else {
    echo "Denegado!";
}
?>
