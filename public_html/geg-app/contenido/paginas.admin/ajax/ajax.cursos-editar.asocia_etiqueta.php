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
$id_tag = post('id_tag');
$id_curso = post('id_curso');

/* asociacion de etiqueta */
$rqverif1 = query("SELECT COUNT(1) AS total FROM cursos_rel_cursostags WHERE id_curso='$id_curso' AND id_tag='$id_tag' ");
$rqverif2 = mysql_fetch_array($rqverif1);
if ($rqverif2['total'] == 0) {
    query("INSERT INTO cursos_rel_cursostags (id_curso,id_tag) VALUES ('$id_curso','$id_tag') ");
    logcursos('Asociacion de etiqueta a curso [T:'.$id_tag.']', 'curso-edicion', 'curso', $id_curso);
}

/* registro */
$rqdcct1 = query("SELECT t.id,t.tag FROM cursos_rel_cursostags rt,cursos_tags t WHERE rt.id_tag=t.id AND rt.id_curso='$id_curso' ");
while ($rqdcct2 = mysql_fetch_array($rqdcct1)) {
    $id_tag = $rqdcct2['id'];
    $tag = $rqdcct2['tag'];
    ?>
    <tr id="tr-tag-<?php echo $id_tag; ?>">
        <td>
            <b class='btn btn-primary active'><?php echo $tag; ?></b>
        </td>
        <td>
            <a class='btn btn-default btn-xs' onclick="quitar_etiqueta('<?php echo $id_tag; ?>');">Quitar</a>
        </td>
    </tr>
    <?php
}
