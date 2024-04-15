<?php
session_start();
include_once '../../../contenido/configuracion/config.php';
include_once '../../../contenido/configuracion/funciones.php';
$mysqli = mysqli_connect($env_hostname, $env_username, $env_password, $env_database);


if (!isset_administrador()) {
    echo "ACCESO DENEGADO";
}

$id_curso = post('id_curso');

/* registrar accesos */
if (isset_post('sw_habilitar_p2')) {
    $costo_envio = post('costo_envio');
    
    $sw_presencial = false;
    if(isset_post('modalidad-presencial')){
        $sw_presencial = true;
    }
    $sw_vivo = false;
    if(isset_post('modalidad-virtual-vivo')){
        $sw_vivo = true;
    }
    $sw_grabado = false;
    if(isset_post('modalidad-virtual-grabado')){
        $sw_grabado = true;
    }

    $sw_proceder = false;
    if($sw_presencial || $sw_vivo || $sw_grabado){
        $sw_proceder = true;
    }

    if($sw_proceder){
        $rqvac1 = query("SELECT id FROM rel_curso_modalidades WHERE id_curso='$id_curso' AND estado=1 LIMIT 1 ");
        if (num_rows($rqvac1) == 0) {
            query("INSERT INTO rel_curso_modalidades 
            (id_curso, sw_mod_presencial, sw_mod_vivo, sw_mod_grabado) 
            VALUES 
            ('$id_curso','".($sw_presencial?'1':'0')."','".($sw_vivo?'1':'0')."','".($sw_grabado?'1':'0')."')");
            logcursos('Habilitacion de modalidades multiples', 'curso-edicion', 'curso', $id_curso);
            echo '<div class="alert alert-success">
        <strong>EXITO</strong> registro agregado correctamente.
      </div>';
        } else {
            $rqvac2 = fetch($rqvac1);
            $id_acc = $rqvac2['id'];
            query("UPDATE rel_curso_modalidades SET 
            sw_mod_presencial='".($sw_presencial?'1':'0')."', 
            sw_mod_vivo='".($sw_vivo?'1':'0')."', 
            sw_mod_grabado='".($sw_grabado?'1':'0')."' 
            WHERE id='$id_acc' LIMIT 1 ");
            logcursos('Actualizacion de envio de certificado fisico', 'curso-edicion', 'curso', $id_curso);
            echo '<div class="alert alert-success">
        <strong>EXITO</strong> registro modificado correctamente.
      </div>';
        }
    }else{
        echo '<div class="alert alert-warning">
        <strong>AVISO</strong> no se selecciono ninguna modalidad.
      </div>';
    }
}

if (isset_post('sw_habilitar')) {
?>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <h4 style="background: #f3f3f3;padding: 15px;text-align: center;border-bottom: 1px solid #00789f;font-weight: bold;">MODALIDADES</h4>
            <form id="FORM-modalidades_multiples">
                <table class="table table-striped table-bordered">
                    <tr>
                        <td>A) Curso Presencial</td>
                        <td><input type="checkbox" name="modalidad-presencial"  value="1" style="width:25px;height:25px;"/></td>
                    </tr>
                    <tr>
                        <td>B) Curso Virtual por Zoom en vivo</td>
                        <td><input type="checkbox" name="modalidad-virtual-vivo"  value="1" style="width:25px;height:25px;" /></td>
                    </tr>
                    <tr>
                        <td>C) Curso Virtual Grabado</td>
                        <td><input type="checkbox" name="modalidad-virtual-grabado"  value="1" style="width:25px;height:25px;" /></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 20px; text-align: center;">
                            <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                            <input type="hidden" name="sw_habilitar_p2" value="1" />
                            <b class="btn btn-success" onclick="habilitar_p2();">ASIGNAR</b>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <?php
} else {
    $rqva1 = query("SELECT * FROM rel_curso_modalidades WHERE id_curso='$id_curso' AND estado=1 LIMIT 1 ");
    if (num_rows($rqva1) == 0) {
    ?>
        <div class="alert alert-warning">
            <strong>MODALIDAD UNICA</strong>
            <br>
            Este curso no tiene modalidades multiples configurados.
        </div>
        <br>
        <b class="btn btn-primary" onclick="habilitar(<?php echo $id_curso; ?>);">HABILITAR MODALIDADES MULTIPLES</b>
    <?php
    } else {
        $rqva2 = fetch($rqva1);
        $sw_mod_presencial = $rqva2['sw_mod_presencial']=='1';
        $sw_mod_vivo = $rqva2['sw_mod_vivo']=='1';
        $sw_mod_grabado = $rqva2['sw_mod_grabado']=='1';
    ?>
        <h4 style="background: #f3f3f3;padding: 15px;text-align: center;border-bottom: 1px solid #00789f;font-weight: bold;">ENV&Iacute;O CERTIFICADO FISICO</h4>
        <form id="FORM-modalidades_multiples">
            <table class="table table-striped table-bordered">
                <tr>
                    <td>A) Curso Presencial</td>
                    <td><input type="checkbox" name="modalidad-presencial"  value="1" style="width:25px;height:25px;" <?= ($sw_mod_presencial?'checked':'') ?>/></td>
                </tr>
                <tr>
                    <td>B) Curso Virtual por Zoom en vivo</td>
                    <td><input type="checkbox" name="modalidad-virtual-vivo"  value="1" style="width:25px;height:25px;" <?= ($sw_mod_vivo?'checked':'') ?> /></td>
                </tr>
                <tr>
                    <td>C) Curso Virtual Grabado</td>
                    <td><input type="checkbox" name="modalidad-virtual-grabado"  value="1" style="width:25px;height:25px;" <?= ($sw_mod_grabado?'checked':'') ?> /></td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 20px; text-align: center;">
                        <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                        <input type="hidden" name="sw_habilitar_p2" value="1" />
                        <b class="btn btn-info" onclick="habilitar_p2();">ACTUALIZAR ASIGNACION</b>
                    </td>
                </tr>
            </table>
        </form>
<?php
    }
}
?>

<!--habilitar-->
<script>
    function habilitar(id_curso) {
        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.modalidades_multiples.php',
            data: {
                id_curso: id_curso,
                sw_habilitar: 1
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modalidades_multiples").html(data);
            }
        });
    }
</script>

<!-- habilitar_p2 -->
<script>
    function habilitar_p2() {
        var form = $("#FORM-modalidades_multiples").serialize();

        $.ajax({
            url: 'pages/ajax/ajax.cursos-editar.modalidades_multiples.php',
            data: form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#AJAXCONTENT-modalidades_multiples").html(data);
            }
        });
    }
</script>