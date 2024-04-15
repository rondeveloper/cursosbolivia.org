<?php
session_start();
include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
//header("Access-Control-Allow-Origin: ".trim($dominio_admin,'/'));


$id_curso = post('id_curso');

$resultado1 = query("SELECT "
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' order by id desc)cnt_participantes,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='4' order by id desc)cnt_participantes_2,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='1' order by id desc)cnt_participantes_3,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='6' order by id desc)cnt_participantes_4,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='1' order by id desc)cnt_participantes_dia_curso,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='3' order by id desc)cnt_participantes_deposito,"
        . "(select count(*) from cursos_participantes where id_curso=c.id and estado='1' and id_modo_pago='5' order by id desc)cnt_participantes_tigomoney"
        . " FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$producto = fetch($resultado1);

echo "<div style='width:100%;margin-top:5px;'>";

echo "<div style='float:left;width:30%;;text-align:center;padding-top:4px;'><br/>";
if($producto['cnt_participantes']>0){
    echo '<b style="font-size:14pt;color:#00789f;">'.$producto['cnt_participantes'] . '</b>';
}else{
    echo '<span style="font-size:10pt;">'.$producto['cnt_participantes'] . '</span>';
}
echo "</div>";

echo "<div style='float:left;width:35%;'>";

echo "<span style='font-size:8pt;color:gray;'>" . $producto['cnt_participantes_2'] . " transferencia</span>";
echo "<br/>";
echo "<span style='font-size:8pt;color:gray;'>" . $producto['cnt_participantes_3'] . " oficina</span>";
echo "<br/>";
echo "<span style='font-size:8pt;color:gray;'>" . $producto['cnt_participantes_dia_curso'] . " dia del curso</span>";
echo "<br/>";
echo "<span style='font-size:8pt;color:gray;'>" . $producto['cnt_participantes_tigomoney'] . " tigomoney</span>";

echo "</div>";

echo "<div style='float:left;width:35%;'>";

echo "<span style='font-size:8pt;color:gray;'>" . $producto['cnt_participantes_4'] . " khipu</span>";
echo "<br/>";
echo "<span style='font-size:8pt;color:gray;'>" . $producto['cnt_participantes_deposito'] . " deposito</span>";
echo "<br/>";
echo "<span style='font-size:8pt;color:gray;'>" . ($producto['cnt_participantes'] - $producto['cnt_participantes_2'] - $producto['cnt_participantes_3'] - $producto['cnt_participantes_4'] - $producto['cnt_participantes_dia_curso'] - $producto['cnt_participantes_deposito'] - $producto['cnt_participantes_tigomoney']) . " sin pago</span>";

echo "</div>";

echo "<div style='clear:both;'></div>";

echo "</div>";

