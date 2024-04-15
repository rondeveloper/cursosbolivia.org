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
$busc = post('busc');
$id_grupo = post('id_grupo');

/* vacio */
if($busc==''){
    echo "Error";
    exit;
}

/* id, numero */
$rq_idnum = '';
if((int)$busc>0){
    $rq_idnum = " OR numero='".(int)$busc."' OR id='".(int)$busc."' ";
}

/* cursos */
$rqda1 = query("SELECT * FROM cursos WHERE estado IN (0,1,2) AND (titulo LIKE '%$busc%' $rq_idnum) AND id NOT IN (select id_curso from cursos_rel_agrupcursos where id_grupo='$id_grupo') ORDER BY fecha DESC limit 25 ");
if (mysql_num_rows($rqda1) == 0) {
    ?>
    <div class="alert alert-info">
        <strong>AVISO</strong> no se encontraron registros.
    </div>
    <?php
}
?>
<table class="table table-striped table-bordered">
    <?php
    $cnt = 1;
    while ($rqda2 = mysql_fetch_array($rqda1)) {
        ?>
        <tr>
            <td>
                <?php echo date("d/M/y", strtotime($rqda2['fecha'])); ?>
            </td>
            <td>
                <?php echo $rqda2['titulo']; ?>
                <div style="font-size: 8pt;
                     color: gray;
                     background: #FFF;
                     padding: 3px 10px;
                     border: 1px solid #e4e4e4;
                     margin-top: 5px;">
                    Numeraci&oacute;n: <?php echo $rqda2['numero']; ?>
                    <div class="pull-right">
                        ID: <?php echo $rqda2['id']; ?>
                    </div>
                </div>
            </td>
            <td>
                <b class="btn btn-info btn-sm" onclick="agregar_curso('<?php echo $rqda2['id']; ?>');">AGREGAR</b>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<script>
    function agregar_curso(id_curso) {
        $("#AJAXCONTENT-mostrar_cursos").html('Cargando...');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-grupos.mostrar_cursos.php',
            data: {id_grupo: '<?php echo $id_grupo; ?>', id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $("#AJAXCONTENT-mostrar_cursos").html(data);
            }
        });
    }
</script>

