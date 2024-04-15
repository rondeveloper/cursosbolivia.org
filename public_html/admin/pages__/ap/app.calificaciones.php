<?php

error_reporting(1);

session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    exit;
}


$id_curso = 2328;
$id_rel_cursoonlinecourse = 138;

$cnt_procesados = 0;

$linea = 0;
$archivo = fopen("calificaciones.csv", "r");
while (($datos = fgetcsv($archivo, ",")) == true) {
    $num = count($datos);
    $linea++;
    for ($columna = 0; $columna < $num; $columna++) {
        $data = utf8_encode($datos[$columna]);
        $ar1 = explode(';', $data);

        $dat_nom = str_replace(' ','%',trim($ar1[1]));
        $dat_ape = str_replace(' ','%',trim($ar1[2]));
        $dat_asistencia = (int)trim($ar1[3]);
        $dat_cuadernos = (int)trim($ar1[4]);
        $dat_practicas_y_evaluaciones = (int)trim($ar1[5]);
        $dat_examen_final = (int)trim($ar1[6]);
        $dat_nota_final = (int)trim($ar1[7]);

        if ($dat_nom != '' && $dat_nom != 'NOMBRES') {
            $cnt_procesados++;
            echo " $dat_nom $dat_ape $dat_nota_final <br>";
            $rqdp1 = query("SELECT id_usuario FROM cursos_participantes WHERE nombres LIKE '%$dat_nom%' AND apellidos LIKE '%$dat_ape%' AND id_curso='$id_curso' ");
            if (num_rows($rqdp1) > 0) {
                echo "<b style='color:green;'>YES</b><br>";
                $rqdp2 = fetch($rqdp1);
                $id_usuario = $rqdp2['id_usuario'];
                /*
                query("INSERT INTO cursos_onlinecourse_notas(
                id_rel_cursoonlinecourse, 
                id_usuario, 
                nota_asistencia, 
                nota_cuadernos, 
                nota_practicas_y_evaluaciones, 
                nota_examen_final, 
                nota_nota_final
                ) VALUES (
                '$id_rel_cursoonlinecourse',
                '$id_usuario',
                '$dat_asistencia',
                '$dat_cuadernos',
                '$dat_practicas_y_evaluaciones',
                '$dat_examen_final',
                '$dat_nota_final'
                )");
                
                echo $id_usuario." OK<br>";
                */
            } else {
                echo "<b style='color:red;'>NO</b> nombres LIKE '%$dat_nom%' AND apellidos LIKE '%$dat_ape%' <br>";
            }
        }
    }
}
fclose($archivo);


echo "<hr>Total procesados: $cnt_procesados";










