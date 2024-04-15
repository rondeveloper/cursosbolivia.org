<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$id_curso = post('id_curso');

/* data curso */
$rqdc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$titulo_curso = $rqdc2['titulo'];

/* agregar numero */
if(isset_post('id_whats_numero')){
    $id_whats_numero = post('id_whats_numero');
    $rqvep1 = query("SELECT id FROM cursos_rel_cursowapnum WHERE id_curso='$id_curso' AND id_whats_numero='$id_whats_numero' ");
    if(num_rows($rqvep1)==0){
        query("INSERT INTO cursos_rel_cursowapnum(id_curso, id_whats_numero) VALUES ('$id_curso','$id_whats_numero')");
        logcursos('Asignacion de numero de whatsapp', 'curso-edicion', 'curso', $id_curso);
    }
    echo '<div class="alert alert-success">
  <strong>AVISO</strong> el registro se agrego correctamente.
</div>';
}

/* quitar numero */
if(isset_post('id_whats_numero_quitar')){
    $id_rel = post('id_whats_numero_quitar');
    query("DELETE FROM cursos_rel_cursowapnum WHERE id='$id_rel' ORDER BY id DESC limit 1 ");
    logcursos('Eliminacion de numero de whatsapp', 'curso-edicion', 'curso', $id_curso);
    echo '<div class="alert alert-info">
  <strong>AVISO</strong> el registro se elimino correctamente.
</div>';
}

$rqnw1 = query("SELECT r.id,w.numero,w.responsable FROM cursos_rel_cursowapnum r INNER JOIN whatsapp_numeros w ON r.id_whats_numero=w.id WHERE r.id_curso='$id_curso' ");
if (num_rows($rqnw1) == 0) {
    echo '<div class="alert alert-warning">
  <strong>AVISO</strong> no hay numeros asignados.
</div>';
}

$mensaje_wamsm_predeternimado = 'Hola, tengo interes en el Curso ' . trim(str_replace(array('curso','Curso','CURSO'), '', $titulo_curso));
$numero_wamsm_predeternimado = '69714008';
$nombre_wamsm_predeternimado = 'Edgar Aliaga';

/*
  $rqc1 = query("SELECT id,id_whats_numero,whats_mensaje FROM cursos WHERE id>2000 ORDER BY id ASC ");
  while($rqc2 = fetch($rqc1)){
  $id_curso = $rqc2['id'];
  $id_whats_numero = $rqc2['id_whats_numero'];
  $whats_mensaje = $rqc2['whats_mensaje'];
  echo "$id_curso | $id_whats_numero | $whats_mensaje<br>";

  //query("INSERT INTO cursos_rel_cursowapnum(id_curso, id_whats_numero, whats_mensaje) VALUES ('$id_curso','$id_whats_numero','$whats_mensaje')");
  echo "OK<br>";
  }
 */
?>
<table class="table table-bordered table-striped">
    <tr>
        <th class="text-center">
            AGREGAR NUMERO DE WHATSAPP
        </th>
    </tr>
    <tr>
        <td>
            <div class="col-md-3">
                <select class="form-control" id="id_whats_numero">
                    <?php
                    $rqwn1 = query("SELECT id,numero,responsable FROM whatsapp_numeros WHERE estado='1' AND id NOT IN (SELECT id_whats_numero FROM cursos_rel_cursowapnum WHERE id_curso='$id_curso') ORDER BY id ASC ");
                    while ($rqwn2 = fetch($rqwn1)) {
                        echo '<option value="' . $rqwn2['id'] . '">' . $rqwn2['numero'] . ' | ' . $rqwn2['responsable'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" value="<?php echo $mensaje_wamsm_predeternimado; ?>" class="form-control" readonly=""/>
            </div>
            <div class="col-md-3">
                <b class="btn btn-block btn-success" onclick="agregar_numero_whatsapp();">AGREGAR</b>
            </div>
        </td>
    </tr>
</table>

<table class="table table-bordered table-striped">
    <tr>
        <th>N&uacute;mero</th>
        <th>Responsable</th>
        <th>Mensaje</th>
        <th>Acciones</th>
    </tr>
    <?php
    if (num_rows($rqnw1) == 0) {
        ?>
        <tr>
            <td><?php echo $numero_wamsm_predeternimado; ?></td>
            <td><?php echo $nombre_wamsm_predeternimado; ?></td>
            <td><?php echo $mensaje_wamsm_predeternimado; ?></td>
            <td><b class="label label-warning">PREDETERMINADO</b></td>
        </tr>
        <?php
    }
    while ($rqnw2 = fetch($rqnw1)) {
        ?>
        <tr>
            <td><?php echo $rqnw2['numero']; ?></td>
            <td><?php echo $rqnw2['responsable']; ?></td>
            <td><?php echo $mensaje_wamsm_predeternimado; ?></td>
            <td><b class="btn btn-xs btn-danger btn-block" onclick="quitar_numero_whatsapp('<?php echo $rqnw2['id']; ?>');">X Quitar</b></td>
        </tr>
        <?php
    }
    ?>
</table>


<!-- AJAX whatsapp_numeros -->
<script>
    function agregar_numero_whatsapp() {
        var id_whats_numero = $("#id_whats_numero").val();
        $("#AJAXCONTENT-whatsapp_numeros").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.whatsapp_numeros.php',
            data: {id_curso: '<?php echo $id_curso?>', id_whats_numero: id_whats_numero},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-whatsapp_numeros").html(data);
            }
        });
    }
</script>


<!-- AJAX quitar_numero_whatsapp -->
<script>
    function quitar_numero_whatsapp(id_whats_numero_quitar) {
        $("#AJAXCONTENT-whatsapp_numeros").html("Cargando...");
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.whatsapp_numeros.php',
            data: {id_curso: '<?php echo $id_curso?>', id_whats_numero_quitar: id_whats_numero_quitar},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-whatsapp_numeros").html(data);
            }
        });
    }
</script>

