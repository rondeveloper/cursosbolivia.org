<?php
session_start();

include_once '../../../configuracion/config.php';
include_once '../../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

header('Content-Type: application/javascript');


$id_onlinecourse = get('dat');

$array_principal = array();

$array_info = array(
    "name" => "Evaluaci&oacute;n de conocimientos adquiridos en el curso",
    "main" => "<p>Presiona en boton: 'Empezar!' para dar inicio a la evaluaci&oacute;n de aprendizaje.</p>",
    "results" => "<h5>EVALUACION CONCLUIDA</h5><p>Los resutados fueron guardados para su registro de avance.</p>",
    "level1" => "Conocimientos adquiridos adecuadamente",
    "level2" => "Buenos conocimientos",
    "level3" => "Desempe&ntilde;o regular",
    "level4" => "Resultados poco satisfactorios",
    "level5" => "Necesita repasar el contenido nuevamente"
);
$array_principal['info'] = $array_info;

$array_questions = array();

$rqp1 = query("SELECT * FROM cursos_onlinecourse_preguntas WHERE id_onlinecourse='$id_onlinecourse' ");
while ($rqp2 = mysql_fetch_array($rqp1)) {
    $pregunta = str_replace('"', '', $rqp2['pregunta']);
    $id_pregunta = $rqp2['id'];
    $rqr1 = query("SELECT * FROM cursos_onlinecourse_respuestas WHERE id_pregunta='$id_pregunta' ");
    $total_rqr1 = mysql_num_rows($rqr1);
    $array_respuestas = array();
    while ($rqr2 = mysql_fetch_array($rqr1)) {
        $respuesta = $rqr2['respuesta'];
        if ($rqr2['sw_correcto'] == '1') {
            $sw_correcto = true;
        } else {
            $sw_correcto = false;
        }
        $array_respuesta = array("option" => $respuesta, "correct" => $sw_correcto);
        array_push($array_respuestas, $array_respuesta);
    }
    $array_pregunta = array("q" => $pregunta, "a" => $array_respuestas, "correct" => "Correcto", "incorrect" => "La respuesta esta incorrecta");
    array_push($array_questions, $array_pregunta);
}
$array_principal['questions'] = $array_questions;
?>
var quizJSON = <?php echo json_encode($array_principal); ?>;