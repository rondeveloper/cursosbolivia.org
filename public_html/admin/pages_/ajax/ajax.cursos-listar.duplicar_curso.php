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

/* curso */
$rqdc1 = query("SELECT titulo,fecha,sw_ipelc FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqdc2 = fetch($rqdc1);
$titulo_curso = $rqdc2['titulo'];
$fecha_curso = $rqdc2['fecha'];
$sw_ipelc_curso = $rqdc2['sw_ipelc'];
?>
<style>
    .label-msj{
        background: #f3f3f3;
        text-align: center;
        padding: 10px;
        border: 1px solid #7bc7f7;
    }
</style>
<?php
if (isset_post('realizar-duplicacion')) {

    $rqc1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqc2 = fetch($rqc1);

    $titulo_curso = $rqc2['titulo'];

    $fecha = post('fecha');
    $fecha_registro = date("Y-m-d H:i");

    /* DUPLICACION DE CURSO */
    query("INSERT INTO cursos(
           id_departamento, 
           id_ciudad, 
           id_categoria, 
           id_modalidad, 
           id_organizador, 
           id_lugar, 
           id_docente, 
           id_material, 
           id_abreviacion, 
           fecha, 
           costo, 
           sw_fecha, 
           fecha2, 
           costo2, 
           sw_fecha2,
           fecha3, 
           costo3, 
           sw_fecha3,
           fecha_e,
           costo_e,
           sw_fecha_e,
           titulo_identificador, 
           titulo, 
           contenido, 
           horarios, 
           duracion, 
           imagen, 
           imagen2, 
           imagen3, 
           imagen4, 
           imagen_gif, 
           google_maps, 
           expositor, 
           colaborador, 
           id_whats_numero, 
           whats_numero, 
           whats_mensaje, 
           fb_txt_requisitos, 
           fb_txt_dirigido, 
           fb_hashtags, 
           mailto_subject, 
           mailto_content, 
           tagmgr_body, 
           rec_limitdesc, 
           sw_recomendaciones, 
           observaciones, 
           incrustacion, 
           seccion, 
           pixelcode, 
           texto_qr, 
           sw_siguiente_semana, 
           sw_askfactura, 
           sw_ipelc, 
           short_link, 
           fecha_registro, 
           estado
           ) VALUES (
           '" . $rqc2['id_departamento'] . "',
           '" . $rqc2['id_ciudad'] . "',
           '" . $rqc2['id_categoria'] . "',
           '" . $rqc2['id_modalidad'] . "',
           '" . $rqc2['id_organizador'] . "',
           '" . $rqc2['id_lugar'] . "',
           '" . $rqc2['id_docente'] . "',
           '" . $rqc2['id_material'] . "',
           '" . $rqc2['id_abreviacion'] . "',
           '" . $fecha . "',
           '" . $rqc2['costo'] . "',
           '" . $rqc2['sw_fecha'] . "',
           '" . $rqc2['fecha2'] . "',
           '" . $rqc2['costo2'] . "',
           '" . $rqc2['sw_fecha2'] . "',
           '" . $rqc2['fecha3'] . "',
           '" . $rqc2['costo3'] . "',
           '" . $rqc2['sw_fecha3'] . "',
           '" . $rqc2['fecha_e'] . "',
           '" . $rqc2['costo_e'] . "',
           '" . $rqc2['sw_fecha_e'] . "',
           '" . str_replace('-tmp', '', $rqc2['titulo_identificador']) . '-tmp' . "',
           '" . $rqc2['titulo'] . "',
           '" . $rqc2['contenido'] . "',
           '" . $rqc2['horarios'] . "',
           '" . $rqc2['duracion'] . "',
           '" . $rqc2['imagen'] . "',
           '" . $rqc2['imagen2'] . "',
           '" . $rqc2['imagen3'] . "',
           '" . $rqc2['imagen4'] . "',
           '" . $rqc2['imagen_gif'] . "',
           '" . $rqc2['google_maps'] . "',
           '" . $rqc2['expositor'] . "',
           '" . $rqc2['colaborador'] . "',
           '" . $rqc2['id_whats_numero'] . "',
           '" . $rqc2['whats_numero'] . "',
           '" . $rqc2['whats_mensaje'] . "',
           '" . $rqc2['fb_txt_requisitos'] . "',
           '" . $rqc2['fb_txt_dirigido'] . "',
           '" . $rqc2['fb_hashtags'] . "',
           '" . $rqc2['mailto_subject'] . "',
           '" . $rqc2['mailto_content'] . "',
           '" . $rqc2['tagmgr_body'] . "',
           '" . $rqc2['rec_limitdesc'] . "',
           '" . $rqc2['sw_recomendaciones'] . "',
           '" . $rqc2['observaciones'] . "',
           '" . $rqc2['incrustacion'] . "',
           '" . $rqc2['seccion'] . "',
           '" . addslashes($rqc2['pixelcode']) . "',
           '" . $rqc2['texto_qr'] . "',
           '" . $rqc2['sw_siguiente_semana'] . "',
           '" . $rqc2['sw_askfactura'] . "',
           '" . $rqc2['sw_ipelc'] . "',
           '" . $rqc2['short_link'] . "',
           '" . $fecha_registro . "',
           '2'
           )");
    $id_curso_nuevo = insert_id();

    /* actualiza bloque ids */
    /* bloque temporal */
    $rqbids1 = query("SELECT id FROM cursos WHERE estado=2 ");
    $cnt = num_rows($rqbids1);
    $ids = '0';
    while($rqbids2 = fetch($rqbids1)){
        $ids .= ','.$rqbids2['id'];
    }
    query("UPDATE ids_bloques SET ids='$ids',total='$cnt' WHERE id='2' ");

    /* bloque virtual */
    if($rqc2['id_modalidad']!='1'){
        $rqbids1 = query("SELECT id FROM cursos WHERE estado IN (1,2) AND id_modalidad!='1' ");
        $cnt = num_rows($rqbids1);
        $ids = '0';
        while($rqbids2 = fetch($rqbids1)){
            $ids .= ','.$rqbids2['id'];
        }
        query("UPDATE ids_bloques SET ids='$ids',total='$cnt' WHERE id='3' ");
    }
    
    /* aux */
    $array_idscerts_a = array();
    $array_idscerts_b = array();
    
    /* DUPLICACION DE CERTIFICADOS */
    if(isset_post('sw_certificados') &&  post('sw_certificados')=='1'){
        $rqdcad1 = query("SELECT * FROM cursos_certificados WHERE id_curso='$id_curso' AND estado='1' ");
        while($rqdcad2 = fetch($rqdcad1)){
            $codigo_certificado = "CERT-$id_curso_nuevo";
            query("INSERT INTO cursos_certificados(
                id_curso, 
                codigo, 
                modelo, 
                cont_titulo, 
                cont_uno, 
                cont_dos, 
                cont_tres, 
                texto_qr, 
                fecha_qr, 
                fecha2_qr, 
                id_fondo_digital, 
                id_fondo_fisico, 
                id_firma1, 
                id_firma2, 
                sw_solo_nombre, 
                formato, 
                estado
                ) VALUES (
                '" . $id_curso_nuevo . "',
                '" . $codigo_certificado . "',
                '" . $rqdcad2['modelo'] . "',
                '" . $rqdcad2['cont_titulo'] . "',
                '" . $rqdcad2['cont_uno'] . "',
                '" . $rqdcad2['cont_dos'] . "',
                '" . $rqdcad2['cont_tres'] . "',
                '" . $rqdcad2['texto_qr'] . "',
                '" . $rqdcad2['fecha_qr'] . "',
                '" . $rqdcad2['fecha2_qr'] . "',
                '" . $rqdcad2['id_fondo_digital'] . "',
                '" . $rqdcad2['id_fondo_fisico'] . "',
                '" . $rqdcad2['id_firma1'] . "',
                '" . $rqdcad2['id_firma2'] . "',
                '" . $rqdcad2['sw_solo_nombre'] . "',
                '" . $rqdcad2['formato'] . "',
                '1'
                )");
            $id_certificado_nuevo = insert_id();
            array_push($array_idscerts_a, $rqdcad2['id']);
            array_push($array_idscerts_b, $id_certificado_nuevo);
            query("INSERT INTO cursos_rel_cursocertificado (id_curso,id_certificado) VALUES ('$id_curso_nuevo','$id_certificado_nuevo') ");
            query("UPDATE cursos_certificados SET codigo='$codigo_certificado-$id_certificado_nuevo' WHERE id='$id_certificado_nuevo' ORDER BY id DESC limit 1 ");
            logcursos('Asignacion de certificado [' . $codigo_certificado . '-' . $id_certificado_nuevo . '][por-duplicacion]', 'curso-edicion', 'curso', $id_curso_nuevo);
            echo '<div class="alert alert-info">
        <strong>CERTIFICADO CREADO</strong> se asigno el scertificado: '.$codigo_certificado . '-' . $id_certificado_nuevo .'.
    </div>';
        }
    }
    
    /* DUPLICACION DE TURNOS */
    if(isset_post('sw_turnos') &&  post('sw_turnos')=='1'){
        $rqdcadtr1 = query("SELECT * FROM cursos_turnos WHERE id_curso='$id_curso' AND estado='1' ");
        while($rqdcadtr2 = fetch($rqdcadtr1)){
            query("INSERT INTO cursos_turnos(
                id_curso, 
                titulo, 
                descripcion, 
                estado
                ) VALUES (
                '" . $id_curso_nuevo . "',
                '" . $rqdcadtr2['titulo'] . "',
                '" . $rqdcadtr2['descripcion'] . "',
                '1'
                )");
            logcursos('Asignacion de turno', 'turno-creacion', 'curso', $id_curso_nuevo);
            echo '<div class="alert alert-danger">
        <strong>TURNO CREADO</strong> se asigno turno.
    </div>';
        }
    }
    
    
    /* DUPLICACION DE CURSOS VIRTUALES */
    if(isset_post('sw_cvirtuales') &&  post('sw_cvirtuales')=='1'){
        $rqdcadcv1 = query("SELECT * FROM cursos_rel_cursoonlinecourse WHERE id_curso='$id_curso' AND estado='1' ");
        while($rqdcadcv2 = fetch($rqdcadcv1)){
            
            //INSERT INTO `cursos_rel_cursoonlinecourse`(``, ``, ``, ``, ``, ``, ``, ``, ``, `estado`)
            $new_id_certificado = '0';
            if(in_array($rqdcadcv2['id_certificado'], $array_idscerts_a)){
                $new_id_certificado = str_replace($array_idscerts_a,$array_idscerts_b,$rqdcadcv2['id_certificado']);
            }
            $new_id_certificado_2 = '0';
            if(in_array($rqdcadcv2['id_certificado_2'], $array_idscerts_a)){
                $new_id_certificado_2 = str_replace($array_idscerts_a,$array_idscerts_b,$rqdcadcv2['id_certificado_2']);
            }
            query("INSERT INTO cursos_rel_cursoonlinecourse(
                id_curso, 
                id_onlinecourse, 
                id_docente, 
                id_certificado, 
                id_certificado_2, 
                fecha_inicio, 
                fecha_final, 
                estado
                ) VALUES (
                '" . $id_curso_nuevo . "',
                '" . $rqdcadcv2['id_onlinecourse'] . "',
                '" . $rqdcadcv2['id_docente'] . "',
                '" . $new_id_certificado . "',
                '" . $new_id_certificado_2 . "',
                '" . $rqdcadcv2['fecha_inicio'] . "',
                '" . $rqdcadcv2['fecha_final'] . "',
                '1'
                )");
            logcursos('ASIGNACION DE CURSO VIRTUAL [CV:' . $rqdcadcv2['id_onlinecourse'] . ']', 'curso-edicion', 'curso', $id_curso_nuevo);
            echo '<div class="alert alert-warning">
        <strong>CURSO VIRTUAL ASIGNADO</strong> se asigno el curso virtual correctamente.
    </div>';
        }
    }
    
    /* DUPLICACION DE CUPONES */
    if(isset_post('sw_cupones') &&  post('sw_cupones')=='1'){
        $rqdcpn1 = query("SELECT * FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' AND estado='1' ");
        while($rqdcpn2 = fetch($rqdcpn1)){
            query("INSERT INTO cursos_cupones_infosicoes(
                id_curso, 
                id_administrador, 
                id_paquete, 
                duracion, 
                fecha_expiracion, 
                fecha_registro, 
                estado
                ) VALUES (
                '" . $id_curso_nuevo . "',
                '" . administrador('id') . "',
                '" . $rqdcpn2['id_paquete'] . "',
                '" . $rqdcpn2['duracion'] . "',
                '" . $rqdcpn2['fecha_expiracion'] . "',
                NOW(),
                '1'
                )");
            logcursos('Asignacion de cupon Infosicoes [P' . $rqdcpn2['id_paquete'] . ']', 'cupon-asignacion', 'curso', $id_curso_nuevo);
            echo '<div class="alert alert-info">
        <strong>CUPON INFOSICOES ASIGNADO</strong> se asigno cupon.
    </div>';
        }
    }
    
    /* DUPLICACION DE CURSOS GRATUITOS */
    if(isset_post('sw_gratuitos') &&  post('sw_gratuitos')=='1'){
        $rqdcpncg1 = query("SELECT * FROM cursos_rel_cursofreecur WHERE id_curso='$id_curso' AND estado='1' ");
        while($rqdcpncg2 = fetch($rqdcpncg1)){
            query("INSERT INTO cursos_rel_cursofreecur(
                id_curso, 
                id_curso_free, 
                estado
                ) VALUES (
                '" . $id_curso_nuevo . "',
                '" . $rqdcpncg2['id_curso_free'] . "',
                '1'
                )");
            logcursos('Asignacion de curso gratuito asociado [id:'.$rqdcpncg2['id_curso_free'].']', 'curso-edicion', 'curso', $id_curso_nuevo);
            echo '<div class="alert alert-danger">
        <strong>CURSO GRATUITO ASIGNADO</strong> se asigno curso gratuito.
    </div>';
        }
    }
      
    /* DUPLICACION DE MATERIAL */
    if(isset_post('sw_material') &&  post('sw_material')=='1'){
        $rqdm1 = query("SELECT * FROM materiales_curso WHERE id_curso='$id_curso' AND estado='1' ");
        while($rqdm2 = fetch($rqdm1)){
            query("INSERT INTO materiales_curso(
                id_curso, 
                nombre, 
                nombre_fisico, 
                estado
                ) VALUES (
                '" . $id_curso_nuevo . "',
                '" . $rqdm2['nombre'] . "',
                '" . $rqdm2['nombre_fisico'] . "',
                '1'
                )");
            logcursos('Asignacion de material digital', 'curso-edicion', 'curso', $id_curso_nuevo);
            echo '<div class="alert alert-warning">
        <strong>MATERIAL ASIGNADO</strong> se asigno material digital.
    </div>';
        }
    }

    /* DUPLICACION DE CUENTAS BANCARIAS */
    $rqdcadtr1 = query("SELECT * FROM rel_cursocuentabancaria WHERE id_curso='$id_curso' AND estado='1' ");
    while($rqdcadtr2 = fetch($rqdcadtr1)){
        query("INSERT INTO rel_cursocuentabancaria(
            id_curso, 
            id_cuenta,
            sw_cprin,
            sw_transbancunion,
            estado
            ) VALUES (
            '" . $id_curso_nuevo . "',
            '" . $rqdcadtr2['id_cuenta'] . "',
            '" . $rqdcadtr2['sw_cprin'] . "',
            '" . $rqdcadtr2['sw_transbancunion'] . "',
            '1'
            )");
    }
    
    /* numeros whatsapp */
    $rqdm1 = query("SELECT * FROM cursos_rel_cursowapnum WHERE id_curso='$id_curso' ");
    while($rqdm2 = fetch($rqdm1)){
        query("INSERT INTO cursos_rel_cursowapnum(
            id_curso, 
            id_whats_numero 
            ) VALUES (
            '" . $id_curso_nuevo . "',
            '" . $rqdm2['id_whats_numero'] . "'
            )");
    }

    /* tags */
    $rqdcct1 = query("SELECT id_tag FROM cursos_rel_cursostags WHERE id_curso='$id_curso' ");
    while ($rqdcct2 = fetch($rqdcct1)) {
        $id_tag = $rqdcct2['id_tag'];
        $rqverif1 = query("SELECT COUNT(1) AS total FROM cursos_rel_cursostags WHERE id_curso='$id_curso_nuevo' AND id_tag='$id_tag' ");
        $rqverif2 = fetch($rqverif1);
        if ($rqverif2['total'] == 0) {
            query("INSERT INTO cursos_rel_cursostags (id_curso,id_tag) VALUES ('$id_curso_nuevo','$id_tag') ");
        }
    }

    /* ENLACE FIJO */
    if(isset_post('sw_enlace_fijo') &&  post('sw_enlace_fijo')=='1'){
        $rqenc1 = query("SELECT e.id FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON e.id=r.id_enlace WHERE r.id_curso='".$id_curso."' AND r.estado=1 ");
        if(num_rows($rqenc1)>0){
            $rqenc2 = fetch($rqenc1);
            $id_enlace = $rqenc2['id'];
            query("UPDATE rel_cursoenlace SET estado=0 WHERE id_curso='$id_curso' ");
            query("INSERT INTO rel_cursoenlace(id_curso, id_enlace, estado) VALUES ('$id_curso_nuevo','$id_enlace',1)");
            logcursos('Asignacion de enlace', 'curso-ediccion', 'curso', $id_curso_nuevo);
            echo '<div class="alert alert-warning">
        <strong>ENLACE FIJO ASIGNADO</strong> se asigno enlace fijo.
    </div>';
        }
    }


//movimiento('Duplicacion de curso [' . $titulo_curso . '][new:' . $id_curso_nuevo . ']', 'duplicacion-curso', 'curso', $id_curso);
    logcursos('Creacion de curso por duplicacion[' . $id_curso . ':' . $titulo_curso . ']', 'curso-creacion', 'curso', $id_curso_nuevo);

//echo "Curso duplicado.";
    ?>
    <div class="alert alert-success">
        <strong>CURSO DUPLICADO EXITOSAMENTE</strong> se ha duplicado el curso correctamente y se genero el curso: <?php echo $id_curso_nuevo; ?>.
    </div>

    <br>
    <div class="text-center">
        <a href="cursos-editar/<?php echo $id_curso_nuevo; ?>.adm" target="_blank" class="btn btn-info btn-lg">
            <i class="fa fa-edit">EDITAR CURSO DUPLICADO</i>
        </a>
    </div>
    <hr>

    <!-- duplicar curso -- >
    <script>
        window.open('<?php echo $dominio; ?>cursos-editar/' + parseInt(data) + '.adm', '_blank');
        window.focus();
    </script>
    -->
    <?php
    exit;
}
?>

<h3 class="label-msj">PARAMETROS DE DUPLICACI&Oacute;N</h3>

<form id="FORM-realizar-duplicacion" action="" method="post">
    <table class="table table-striped table-bordered">
        <tr>
            <td><b>Curso:</b></td>
            <td colspan="3"><?php echo $titulo_curso; ?></td>
        </tr>
        <tr>
            <td><b>Fecha:</b></td>
            <td><?php echo $fecha_curso; ?></td>
            <td colspan="2"><input type="date" name="fecha" value="<?php echo date("Y-m-") . '30'; ?>" class="form-control"/></td>
        </tr>
        <tr>
            <td><b>Enlace fijo:</b></td>
            <td colspan="2">
                <?php
                $rqenc1 = query("SELECT e.enlace FROM rel_cursoenlace r INNER JOIN enlaces_cursos e ON e.id=r.id_enlace WHERE r.id_curso='".$id_curso."' AND r.estado=1 ");
                if(num_rows($rqenc1)>0){
                    $rqenc2 = fetch($rqenc1);
                    $url_corta = $dominio.$rqenc2['enlace'] . "/";
                    echo "<b style='color:green;font-size:14pt;'>CON ENLACE</b><br>";
                    echo $url_corta;
                    $htm_enalce_disabled = ' checked="" ';
                }else{
                    echo "<b style='color:gray;font-size:14pt;'>SIN ENLACE FIJO</b><br>";
                    $htm_enalce_disabled = ' disabled="" ';
                }
                ?>
            </td>
            <td style="width: 50px;text-align: center;"><input type="checkbox" name="sw_enlace_fijo" value="1" class="form-control" style="width:25px;height:25px;" <?php echo $htm_enalce_disabled; ?>/></td>
        </tr>
        <tr>
            <td><b>Certificados:</b></td>
            <td colspan="2">
                <?php
                $rqdcrt1 = query("SELECT cert.texto_qr,cert.codigo FROM cursos_certificados cert WHERE cert.id_curso='$id_curso' ");
                if (num_rows($rqdcrt1) == 0) {
                    echo "Sin registros";
                }
                while ($rqdcrt2 = fetch($rqdcrt1)) {
                    echo "<i style='color:skyblue;font-size:8pt;'>" . $rqdcrt2['codigo'] . "</i><br>" . $rqdcrt2['texto_qr'] . "<br>";
                }
                ?>
            </td>
            <td style="width: 50px;text-align: center;"><input type="checkbox" checked="" name="sw_certificados" value="1" class="form-control" style="width:25px;height:25px;"/></td>
        </tr>
        <tr>
            <td><b>Turnos:</b></td>
            <td colspan="2">
                <?php
                $rqdcrtr1 = query("SELECT * FROM cursos_turnos WHERE id_curso='$id_curso' ");
                if (num_rows($rqdcrtr1) == 0) {
                    echo "Sin registros";
                }
                while ($rqdcrtr2 = fetch($rqdcrtr1)) {
                    echo "<b style='color:#1698ca;font-size:8pt;'>" . $rqdcrtr2['titulo'] . "</b><br>" . $rqdcrtr2['descripcion'] . "<br>";
                }
                ?>
            </td>
            <td style="width: 50px;text-align: center;"><input type="checkbox" checked=""name="sw_turnos" value="1" class="form-control" style="width:25px;height:25px;"/></td>
        </tr>
        <tr>
            <td><b>Cursos virtuales:</b></td>
            <td colspan="2">
                <?php
                $rqdcrcv1 = query("SELECT oc.titulo,r.fecha_inicio,r.fecha_final FROM cursos_rel_cursoonlinecourse r INNER JOIN cursos_onlinecourse oc ON oc.id=r.id_onlinecourse WHERE r.id_curso='$id_curso' ");
                if (num_rows($rqdcrcv1) == 0) {
                    echo "Sin registros";
                }
                while ($rqdcrcv2 = fetch($rqdcrcv1)) {
                    echo "<b style='color:#1698ca;font-size:8pt;'>" . $rqdcrcv2['titulo'] . "</b><br>Inicio: " . $rqdcrcv2['fecha_inicio'] . " | Final: " . $rqdcrcv2['fecha_final'] . "<br>";
                }
                $aux_mod_cvirt = ' checked="" ';
                if($sw_ipelc_curso){
                    $aux_mod_cvirt = ' disabled ';
                }
                ?>
            </td>
            <td style="width: 50px;text-align: center;"><input type="checkbox" <?php echo $aux_mod_cvirt; ?> name="sw_cvirtuales" value="1" class="form-control" style="width:25px;height:25px;"/></td>
        </tr>
        <tr>
            <td><b>Cupones Infosicoes:</b></td>
            <td colspan="2">
                <?php
                $rqdcrci1 = query("SELECT * FROM cursos_cupones_infosicoes WHERE id_curso='$id_curso' ");
                if (num_rows($rqdcrci1) == 0) {
                    echo "Sin registros";
                }
                while ($rqdcrci2 = fetch($rqdcrci1)) {
                    switch ($rqdcrci2['id_paquete']) {
                        case '2':
                            $txt_nompaq = "PAQUETE PyME";
                            break;
                        case '3':
                            $txt_nompaq = "PAQUETE BASICO";
                            break;
                        case '4':
                            $txt_nompaq = "PAQUETE MEDIO";
                            break;
                        case '5':
                            $txt_nompaq = "PAQUETE INTERMEDIO";
                            break;
                        case '6':
                            $txt_nompaq = "PAQUETE EMPRESARIAL";
                            break;
                        case '7':
                            $txt_nompaq = "PAQUETE COORPORATIVO";
                            break;
                        case '10':
                            $txt_nompaq = "PAQUETE Consultor - BASICO";
                            break;
                        case '11':
                            $txt_nompaq = "PAQUETE Consultor - GOLD";
                            break;
                        case '12':
                            $txt_nompaq = "PAQUETE Consultor - PREMIUM";
                            break;
                        default:
                            $txt_nompaq = '';
                            break;
                    }
                    echo "<b style='color:#1698ca;font-size:8pt;'>" . $txt_nompaq . "</b><br>" . $rqdcrci2['duracion'] . " MESES | Expira: " . $rqdcrci2['fecha_expiracion'] . " MESES<br>";
                }
                ?>
            </td>
            <td style="width: 50px;text-align: center;"><input type="checkbox" checked="" name="sw_cupones" value="1" class="form-control" style="width:25px;height:25px;"/></td>
        </tr>
        <tr>
            <td><b>Cursos Gratuitos:</b></td>
            <td colspan="2">
                <?php
                $rqcg1 = query("SELECT id,titulo,fecha,estado FROM cursos WHERE id IN (select id_curso_free from cursos_rel_cursofreecur where id_curso='$id_curso' and estado='1') ");
                if (num_rows($rqcg1) == 0) {
                    echo "Sin registros";
                }
                while ($rqcg2 = fetch($rqcg1)) {
                    echo "<b style='color:#1698ca;font-size:8pt;'>" . $rqcg2['titulo'] . "</b><br>Fecha: " . $rqcg2['fecha'] . "<br>";
                }
                ?>
            </td>
            <td style="width: 50px;text-align: center;"><input type="checkbox" checked="" name="sw_gratuitos" value="1" class="form-control" style="width:25px;height:25px;"/></td>
        </tr>
        <tr>
            <td><b>Material del curso:</b></td>
            <td colspan="2">
                <?php
                $rqda1 = query("SELECT * FROM materiales_curso WHERE id_curso='$id_curso' AND estado='1' ");
                if (num_rows($rqda1) == 0) {
                    echo "Sin registros";
                }
                while ($rqda2 = fetch($rqda1)) {
                    echo "<b style='color:#1698ca;font-size:8pt;'>" . $rqda2['nombre'] . "</b><br>" . $rqda2['nombre_fisico'] . "<br>";
                }
                ?>
            </td>
            <td style="width: 50px;text-align: center;"><input type="checkbox" checked="" name="sw_material" value="1" class="form-control" style="width:25px;height:25px;"/></td>
        </tr>
    </table>
    <div class="text-center">
        <input type="hidden" value="<?php echo $id_curso; ?>" name="id_curso"/>
        <input type="submit" value="DUPLICAR CURSO" class="btn btn-success btn-lg"/>
    </div>
</form>

<script>
    $('#FORM-realizar-duplicacion').on('submit', function(e) {
        e.preventDefault();
        $("#AJAXCONTENT-modgeneral").html('Procesando...');
        var formData = new FormData(this);
        formData.append('realizar-duplicacion', 1);
        $.ajax({
            type: 'POST',
            url: 'pages/ajax/ajax.cursos-listar.duplicar_curso.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#AJAXCONTENT-modgeneral").html(data);
            }
        });
    });
</script>