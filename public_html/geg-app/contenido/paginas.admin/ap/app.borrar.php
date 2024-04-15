<?php
session_start();
include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);


/*
if (($fichero = fopen("datos-excel.csv", "r")) !== FALSE) {
    while (($datos = fgetcsv($fichero, 1000, ";")) !== FALSE) {
        $nombres = limp_data_g($datos[0]);
        $apellidos = limp_data_g($datos[1]);
        $correo = limp_data_g($datos[2]);
        $programa = limp_data_g($datos[3]);
        $nivel = limp_data_g($datos[4]);
        
        echo "$nombres | $apellidos | $correo | $programa | $nivel <br>";
        
        query("INSERT INTO participantes(nombres, apellidos, correo, programa, nivel) VALUES ('$nombres','$apellidos','$correo','$programa','$nivel')");
        echo "OK<br>";
    }
} else {
    echo "<b>Problemas en la lectura</b>";
}

function limp_data_g($dat){
    return str_replace("'",'',$dat);
}
 */


if (($fichero = fopen("datos-excel.csv", "r")) !== FALSE) {
    while (($datos = fgetcsv($fichero, 1000, ";")) !== FALSE) {
        $nombres = limp_data_g($datos[0]);
        $apellidos = limp_data_g($datos[1]);
        $correo = limp_data_g($datos[2]);
        $programa = limp_data_g($datos[3]);
        $nivel = limp_data_g($datos[4]);
        
        echo "$nombres | $apellidos | $correo | $programa | $nivel <br>";
        
        query("INSERT INTO usuarios(nombre, nombres, apellidos, email, programa, nivel, estado) VALUES ('$nombres $apellidos','$nombres','$apellidos','$correo','$programa','$nivel','1')");
        
        echo "OK<br>";
    }
} else {
    echo "<b>Problemas en la lectura</b>";
}

function limp_data_g($dat){
    return str_replace("'",'',$dat);
}