<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

/* DESACTIVACION DE CURSOS */
$rqdc1 = query("SELECT id,titulo_identificador FROM cursos WHERE estado IN ('1','2') AND id_modalidad NOT IN (2,3) AND DATE(fecha) < CURDATE() ORDER BY id DESC ");
if(mysql_num_rows($rqdc1)==0){
    echo "Sin registros";
}
/* last update */
$laststch_fecha = date("Y-m-d H:i");
$laststch_id_administrador = '0';

while ($rqdc2 = mysql_fetch_array($rqdc1)) {
    $id_curso = $rqdc2['id'];
    $titulo_identificador = str_replace('-tmp', '', $rqdc2['titulo_identificador']);
    query("UPDATE cursos SET estado='0',titulo_identificador='$titulo_identificador',laststch_fecha='$laststch_fecha',laststch_id_administrador='$laststch_id_administrador' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    logcursos('Desactivacion SISTEMATICA', 'curso-edicion', 'curso', $id_curso);

    echo "Curso: $titulo_identificador -> DESACTIVADO<br/>";
}


/* ELIMINACION DE REGISTROS DE APARTADO DE PARTICIPANTES */
query("DELETE FROM cursos_part_apartados WHERE fecha < CURDATE() ");