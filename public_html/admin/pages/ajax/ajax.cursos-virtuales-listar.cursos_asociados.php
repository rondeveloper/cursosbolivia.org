<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);


if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$id_onlinecourse = post('id_curso');

/* registros */
$rqdco1 = query("SELECT * FROM cursos_rel_cursoonlinecourse WHERE id_onlinecourse='$id_onlinecourse' ");

/* mensjae sin regsitros */
if (num_rows($rqdco1) == 0) {
    echo '<div class="alert alert-warning">
  <strong>SIN CURSOS ASOCIADOS</strong> no se encontraron cursos asociados a este curso virtual.
</div>
';
} else {
    ?>
    <table class="table table-striped table-bordered">
        <tr>

            <th>ID</th>
            <th>CURSO</th>
            <th>PARTICIPANTES</th>
            <th>ACCIONES</th>

        </tr>
        <?php
        while ($rqdco2 = fetch($rqdco1)) {
            $id_rel_cursoonlinecourse = $rqdco2['id'];
            $id_curso = $rqdco2['id_curso'];
            $rqdc1 = query("SELECT *,(select count(*) from cursos_participantes where id_curso=cursos.id and estado='1')cnt_participantes FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
            $curso = fetch($rqdc1);
            ?>
            <tr>
                <td>
                    <?php echo $id_curso; ?>
                </td>
                <td>
                    <?php echo $curso['titulo']; ?>
                </td>
                <td class="text-center">
                    <?php echo $curso['cnt_participantes']; ?>
                </td>
                <td>
                    <a href="cursos-participantes/<?php echo $id_curso; ?>.adm" target="_blank" class="btn btn-xs btn-warning btn-block active"><i class='fa fa-users'></i> PANEL 1</a>
                    <br/>
                    <a href="cursos-virtuales-participantes/<?php echo $id_rel_cursoonlinecourse; ?>.adm" target="_blank" class="btn btn-xs btn-info btn-block active"><i class='fa fa-list'></i> PANEL 2</a>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}
