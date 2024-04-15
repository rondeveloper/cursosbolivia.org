<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

if (!acceso_cod('adm-estadisticas')) {
    echo "DENEGADO";
    exit;
}

/* data post */
$id_curso = post('id_curso');

/* datos en forma de grupo */
$data_required = "
*,
(count(*))dr_cantidad,
(c.id)dr_id_curso,
(c.id_modalidad)dr_modalidad_curso,
(p.id)dr_id_participante,
(d.nombre)dr_departamento_curso,
(r.fecha_registro)dr_fecha_registro_participante,
(CONCAT(p.nombres,' ',p.apellidos))dr_nombre_participante,
(SUM(r.monto_deposito))dr_monto_pago
";
    $query = "
SELECT $data_required FROM 
cursos_participantes p 
INNER JOIN cursos_proceso_registro r ON r.id=p.id_proceso_registro 
INNER JOIN cursos c ON c.id=p.id_curso 
LEFT JOIN ciudades cd ON c.id_ciudad=cd.id 
LEFT JOIN departamentos d ON cd.id_departamento=d.id 
WHERE p.estado=1 AND c.estado IN (0,1,2) 
AND c.id='$id_curso' AND r.sw_pago_enviado='1' 
GROUP BY c.id  
";

$resultado1 = query($query);
$producto = fetch($resultado1);
echo '<b>Recaudaci&oacute;n:</b> '.(int)($producto['dr_monto_pago']).' BS';
