<?php
session_start();

include_once '../../../contenido/configuracion/config.php';

include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);

$objeto = post('objeto');
$id_objeto = post('id');

if ($objeto == 'usuario') {
    $rqb1 = query("SELECT * FROM empresas WHERE id='$id_objeto' ");
    $datos = fetch($rqb1);
    echo "<div style='position:absolute;margin-top:-170px;height:160px;margin-left:-45px;width:320px;padding:7px;background:#FFF;border:1px solid gray;box-shadow:2px 2px 2px #AAA;border-radius:5px;'>";
    echo "<span onclick='$(\"#show-v-div-$objeto-$id_objeto\").html(\"\");' style='float:right;padding:1px 3px;color:red;border:1px solid red;border-radius:3px;cursor:pointer;'>x</span>";
    echo "<b>" . $datos['nombre_empresa'] . "</b>";
    echo "<br/>";
    echo "" . str_replace('-', ' ', $datos['ciudad']) . " - " . $datos['nombre_representante'] . ' ' . $datos['ap_paterno_representante'];
    echo "<br/>";
    echo "<i class='fa fa-tasks'></i> <a href='movimiento/1/usuario/" . $datos['id'] . ".adm'>Historial</a>";
    echo "<br/>";
    echo "<i class='fa fa-reply-all'></i> <a href='envios/" . $datos['id'] . ".adm'>Envios</a>";
    echo "<br/>";
    echo "<i class='fa fa fa-archive'></i> <a href='empresas-paquetes/" . $datos['id'] . ".adm'>Paquetes</a>";
    echo "<br/>";
    echo "<i class='fa fa-gears'></i> <a href='editar-datos/" . $datos['clave'] . ".adm'>Editar / ver</a>";

?>
    <br>
    <div class="sdgs" style="width:10px;">
        dns_get_recordsdg
        sys_get_temp_dirsdg
        <div>
            adg
        </div>
        sdg
    </div>

<?php
    echo "</div>";
}
