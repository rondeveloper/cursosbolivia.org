<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


$id_empresa = (int) post('id');

//$id_empresa = 1151;

$re1 = query("SELECT id_paquete,palabras_clave,convocatorias_descartadas,convocatorias_revisadas,departamentos_de_envio FROM empresas WHERE id='$id_empresa' ");
$re2 = fetch($re1);

$palabras_clave = $re2['palabras_clave'];
$arrwords = explode(',', $palabras_clave);
$cnt_words = count($arrwords);

$descartados = trim($re2['convocatorias_descartadas']);
$revisados = trim($re2['convocatorias_revisadas']);
$departamentos_de_envio = '0' . trim($re2['departamentos_de_envio']);

if ($departamentos_de_envio == '0') {
    $departamentos_de_envio = '0,1,2,3,4,5,6,7,8,9';
}

if (substr($revisados, -1) == ',') {
    $revisados .= '0';
}

if (substr($descartados, -1) == ',') {
    $descartados .= '0';
}

//datos de paquete
$rqpaq1 = query("SELECT ids_tipo_de_contratacion FROM paquetes WHERE id='".$re2['id_paquete']."' limit 1 ");
$rqpaq2 = fetch($rqpaq1);

$ids_tipo_de_contratacion = $rqpaq2['ids_tipo_de_contratacion'];

//echo $cnt_words . "<hr/>" . $palabras_clave . "<hr/>Descartados: $descartados<hr/>Revisados: $revisados<hr/>Deps: $departamentos_de_envio";

$busc = array(',de,', ',un,', ',una,', ',con,', ',a,', ',,');
$remm = ',';
$palabras = explode(",", str_replace($busc, $remm, $palabras_clave));

$sw_tiene_palabras = false;

//ids vigentes
$rv1 = query("SELECT ids FROM ids_convocatorias WHERE seccion='vigente' LIMIT 1 ");
$rv2 = fetch($rv1);
$ids_vigentes = $rv2['ids'];

$sql = "SELECT COUNT(*) AS total FROM convocatorias_nacionales WHERE (";
foreach ($palabras as $palabra) {
    if ($palabra !== '') {
        $sw_tiene_palabras = true;
        if ($cnt_words >= 10 && (strlen($palabra) <= 3)) {
            $sql .= "objeto LIKE '% $palabra %' OR ";
            $sql .= "objeto LIKE '% $palabra,%' OR ";
            $sql .= "objeto LIKE '%,$palabra %' OR ";
            $sql .= "objeto LIKE '% $palabra.%' OR ";
            $sql .= "objeto LIKE '%.$palabra %' OR ";
            $sql .= "objeto LIKE '% $palabra-%' OR ";
            $sql .= "objeto LIKE '$palabra %' OR ";
            $sql .= "objeto LIKE '% $palabra' OR ";
        } else {
            $sql .= "objeto LIKE '%$palabra%' OR ";
        }
    }
}

if ($sw_tiene_palabras) {
    $sql = substr($sql, 0, (strlen($sql) - 4)) . ")";
} else {
    $sql .= "objeto='none000')";
}

$sql .= " AND id IN ($ids_vigentes) AND id_tipo_de_contratacion IN ($ids_tipo_de_contratacion) AND departamento IN ($departamentos_de_envio) AND id NOT IN (0$descartados) AND id NOT IN (0$revisados) LIMIT 80 ";

//echo $sql;

$r_licitaciones_disponibles = query($sql);
$r2_licitaciones_disponibles = fetch($r_licitaciones_disponibles);


echo '<span id="av-' . $id_empresa . '"><button onclick="enviar_correo_de_interes(' . $id_empresa . ');" style="border-radius:4px;background:green;color:#FFF;padding:2px 7px;" title="Enviar Licitaciones">' . $r2_licitaciones_disponibles['total'] . '</button></span>';

//echo $sql;
//echo '<a href="http://mayego.com/ap-infosicoes/ap-infosicoes.enviar_correos_de_interes.php?emp='.$id_empresa.'" target="_blank"><span id="av-' . $id_empresa . '"><button style="border-radius:4px;background:green;color:#FFF;padding:2px 7px;" title="Enviar Licitaciones">' . $r2_licitaciones_disponibles['total'] . '</button></span></a>';
//envio total
//$cadena .= '<span id="avtotal-' . $id_empresa . '"><button onclick="enviar_correo_de_interes_total(' . $id_empresa . ',\'INTERMEDIO\');" style="border-radius:4px;background:orange;color:#FFF;padding:1px 5px;font-size:8pt;" title="Enviar Licitaciones">t</button></span>';
?>
