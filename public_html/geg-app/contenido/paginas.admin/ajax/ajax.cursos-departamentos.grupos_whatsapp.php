<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

$id_departamento = post('id_departamento');

$resultado1 = query("SELECT * FROM departamentos WHERE id='$id_departamento' LIMIT 1 ");
$departamento = mysql_fetch_array($resultado1);
$id_whatsapp_grupo = $departamento['id_whatsapp_grupo'];

/* grupo whatsapp */
$sw_grupo_whatsapp = false;
if ($id_whatsapp_grupo !== '0') {
    $sw_grupo_whatsapp = true;
    $rqgw1 = query("SELECT * FROM whatsapp_grupos WHERE id='$id_whatsapp_grupo' LIMIT 1 ");
    $rqgw2 = mysql_fetch_array($rqgw1);
    $nombre_gw = $rqgw2['nombre'];
    $enlace_ingreso_gw = $rqgw2['enlace_ingreso'];
}
?>
<div class="row">
    <div class="col-md-10">
        <b style="font-size:15pt;font-weight:bold;color:#00789f;"><?php echo $departamento['nombre']; ?></b>
        <br/>
        <?php
        if ($sw_grupo_whatsapp) {
            ?>
            Whatsapp: SI
            <?php
        } else {
            ?>
            Whatsapp: NO
            <?php
        }
        ?>
    </div>
    <div class="col-md-2 text-center">
        <img src="<?php echo "contenido/imagenes/departamentos/" . $departamento['imagen']; ?>" style="height:65px;width:65px;overflow:hidden;border-radius: 7px;opacity: .8;"/>
    </div>
</div>
<?php
if ($sw_grupo_whatsapp) {
    ?>
    <hr/>
    <b class="btn btn-block btn-success btn-xs active">GRUPO ACTUAL</b>
    <table class="table table-striped table-bordered">
        <tr>
            <td>NOMBRE:</td>
            <td><?php echo $nombre_gw; ?></td>
        </tr>
        <tr>
            <td>ENLACE DE INGRESO:</td>
            <td><?php echo $enlace_ingreso_gw; ?></td>
        </tr>
    </table>
    <?php
}
?>
<hr/>
<h4>GRUPOS DE ESTE DEPARTAMENTO</h4>
<table class="table table-bordered">
    <?php
    $rqdctob1 = query("SELECT * FROM whatsapp_grupos WHERE id_departamento='$id_departamento' ORDER BY id ASC ");
    if (mysql_num_rows($rqdctob1) == 0) {
        echo "No se tienen grupos registrados para este departamento.";
    }
    $cnt = 1;
    while ($rqdctob2 = mysql_fetch_array($rqdctob1)) {
        ?>
        <tr>
            <td><?php echo $cnt++; ?></td>
            <td>
                <?php echo $rqdctob2['nombre']; ?>
            </td>
            <td>
                <?php echo substr($rqdctob2['enlace_ingreso'], 0, 25) . '...'; ?>
            </td>
            <td>
                <b class="btn btn-xs btn-default pull-right" onclick="edita_grupo_whatsapp('<?php echo $rqdctob2['nombre']; ?>','<?php echo $rqdctob2['enlace_ingreso']; ?>','<?php echo $rqdctob2['id']; ?>');"><i class="fa fa-edit"></i></b>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<div id="boxedit_gw" style="display:none;">
    <hr/>
    <b class="btn btn-block btn-warning btn-xs active">EDITAR GRUPO</b>
    <form action="" method="post">
        <table class="table table-striped table-bordered">
            <tr>
                <td>NOMBRE:</td>
                <td><input type="text" name="nombre_gw" class="form-control" required="" id="edit_gw_nombre"/></td>
            </tr>
            <tr>
                <td>ENLACE DE INGRESO:</td>
                <td><input type="text" name="enlace_ingreso_gw" class="form-control" required="" id="edit_gw_enlace"/></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="hidden" name="id_grupo" value="0" id="edit_gw_idgrupo"/>
                    <input type="hidden" name="id_departamento" value="<?php echo $id_departamento; ?>"/>
                    <input type="submit" name="editar-grupo" class="btn btn-success btn-block" value="ACTUALIZAR GRUPO"/>
                </td>
            </tr>
        </table>
    </form>
    <script>
        function edita_grupo_whatsapp(edit_gw_nombre,edit_gw_enlace,edit_gw_idgrupo){
            $("#boxedit_gw").css('display','block');
            $("#edit_gw_nombre").val(edit_gw_nombre);
            $("#edit_gw_enlace").val(edit_gw_enlace);
            $("#edit_gw_idgrupo").val(edit_gw_idgrupo);
        }
    </script>
</div>
<hr/>
<b class="btn btn-block btn-primary btn-xs active">AGREGAR NUEVO GRUPO</b>
<form action="" method="post">
    <table class="table table-striped table-bordered">
        <tr>
            <td>NOMBRE:</td>
            <td><input type="text" name="nombre_gw" class="form-control" required=""/></td>
        </tr>
        <tr>
            <td>ENLACE DE INGRESO:</td>
            <td><input type="text" name="enlace_ingreso_gw" class="form-control" required=""/></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="id_departamento" value="<?php echo $id_departamento; ?>"/>
                <input type="submit" name="agregar-grupo" class="btn btn-success btn-block" value="AGREGAR GRUPO"/>
            </td>
        </tr>
    </table>
</form>

<div id="AJAXCONTENT-show_metrica_curso"></div>

<!-- historial_curso -->
<script>
    function show_metrica_curso(id_curso) {
        $("#AJAXCONTENT-show_metrica_curso").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-departamentos.show_metrica_curso.php',
            data: {id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-show_metrica_curso").html(data);
            }
        });
    }
</script>