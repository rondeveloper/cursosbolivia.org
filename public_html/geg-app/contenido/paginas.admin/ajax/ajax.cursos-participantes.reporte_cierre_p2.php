<?php
session_start();

include_once '../../configuracion/config.php';
include_once '../../configuracion/funciones.php';
mysql_connect($hostname, $username, $password);
mysql_select_db($database);

use Dompdf\Dompdf;

if (!isset_administrador()) {
    echo "DENEGADO";
    exit;
}

/* data */
$proceso = get('dat');
$id_curso = post('id_curso');
$id_administrador = administrador('id');
$fecha = date("Y-m-d H:i");

$cnt_campos = 0;

$data_id_turno = post('id_turno');
$data_report_prefijo = '0';
if (isset_post('data_report_prefijo')) {
    $data_report_prefijo = '1';
    $cnt_campos++;
}
$data_report_nombres = '0';
if (isset_post('data_report_nombres')) {
    $data_report_nombres = '1';
    $cnt_campos++;
}
$data_report_apellidos = '0';
if (isset_post('data_report_apellidos')) {
    $data_report_apellidos = '1';
    $cnt_campos++;
}
$data_report_datosfacturacion = '0';
if (isset_post('data_report_datosfacturacion')) {
    $data_report_datosfacturacion = '1';
    $cnt_campos++;
}
$data_report_datoscontacto = '0';
if (isset_post('data_report_datoscontacto')) {
    $data_report_datoscontacto = '1';
    $cnt_campos++;
}
$data_report_modoregistro = '0';
if (isset_post('data_report_modoregistro')) {
    $data_report_modoregistro = '1';
    $cnt_campos++;
}
$data_report_montopago = '0';
if (isset_post('data_report_montopago')) {
    $data_report_montopago = '1';
    $cnt_campos++;
}
$data_report_regpago = '0';
if (isset_post('data_report_regpago')) {
    $data_report_regpago = '1';
    $cnt_campos++;
}
$data_report_fecharegistro = '0';
if (isset_post('data_report_fecharegistro')) {
    $data_report_fecharegistro = '1';
    $cnt_campos++;
}
$data_report_firma = '0';
if (isset_post('data_report_firma')) {
    $data_report_firma = '1';
    $cnt_campos++;
}
$data_report_eliminados = '0';
if (isset_post('data_report_eliminados')) {
    $data_report_eliminados = '1';
    $cnt_campos++;
}
$data_numeracion_certificado = '0';
if (isset_post('data_numeracion_certificado')) {
    $data_numeracion_certificado = '1';
    $cnt_campos++;
}
$data_ci = '0';
if (isset_post('data_ci')) {
    $data_ci = '1';
    $cnt_campos++;
}
$data_solo_facturados = '0';
if (isset_post('data_solo_facturados')) {
    $data_solo_facturados = '1';
    $cnt_campos++;
}
$data_required = 'data_report_nombres=' . $data_report_nombres . '&data_report_apellidos=' . $data_report_apellidos . '&data_report_datosfacturacion=' . $data_report_datosfacturacion . '&data_report_datoscontacto=' . $data_report_datoscontacto . '&data_report_firma=' . $data_report_firma . '&data_report_prefijo=' . $data_report_prefijo . '&data_report_fecharegistro=' . $data_report_fecharegistro . '&data_report_modoregistro=' . $data_report_modoregistro . '&data_report_eliminados=' . $data_report_eliminados . '&data_numeracion_certificado=' . $data_numeracion_certificado . '&data_report_montopago=' . $data_report_montopago . '&data_report_regpago=' . $data_report_regpago . '&data_id_turno=' . $data_id_turno . '&data_ci=' . $data_ci. '&data_solo_facturados=' . $data_solo_facturados;



if ($proceso == 'cierre') {

    /* curso */
    $rqdc1 = query("SELECT sw_cierre,id_cierre FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
    $rqdc2 = mysql_fetch_array($rqdc1);

    $sw_cierre = $rqdc2['sw_cierre'];
    $id_cierre = $rqdc2['id_cierre'];


    /* obtiene numeracion de cierre */
    if ($id_cierre == 0) {
        $rqgnm1 = query("SELECT numeracion FROM cursos_cierres ORDER BY numeracion DESC limit 1 ");
        $rqgnm2 = mysql_fetch_array($rqgnm1);
        $numeracion = ((int) $rqgnm2['numeracion']) + 1;
    } else {
        $rqgnm1 = query("SELECT numeracion FROM cursos_cierres WHERE id='$id_cierre' ORDER BY id DESC limit 1 ");
        $rqgnm2 = mysql_fetch_array($rqgnm1);
        $numeracion = $rqgnm2['numeracion'];
    }


    /* numero de documento */
    $cod_documento = $id_curso . $numeracion . time();

    /* obtiene apartado */
    if ($id_cierre == 0) {
        $apartado = 'A';
    } else {
        $rqgnm1 = query("SELECT count(*) AS total FROM cursos_cierres_documentos WHERE id_cierre='$id_cierre' ORDER BY id DESC limit 1 ");
        $rqgnm2 = mysql_fetch_array($rqgnm1);
        $docs_existentes = $rqgnm2['total'];
        $array_apartados = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        $apartado = $array_apartados[(int) $docs_existentes];
    }

    /* url de generacion de documento */
    $url_content = 'http://cursos.bo/contenido/paginas.admin/ajax/ajax.impresion.cursos-participantes.exportar-lista.php?id_curso=' . $id_curso . '&html=true&' . $data_required . '&k3y74ue8=true&k3y74ue8_numeracion=' . $numeracion . '&k3y74ue8_cod_documento=' . $cod_documento . '&k3y74ue8_apartado=' . $apartado;


    $varrr = file_get_contents($url_content);

    //echo "EN CONTRUCCION: <br/> realizar cierre [$id_curso]<br/>$url_content";

    require_once '../../librerias/dompdf/autoload.inc.php';
    define("DOMPDF_ENABLE_CSS_FLOAT", true);

    $busc_imagenes = array('https://www.sicoes.gob.bo/img/logo_mefp_forms.gif');
    $remm_imagenes = array('/home/infosico/public_html/contenido/imagenes/images/logo_infosicoes.png');

// instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $dompdf->loadHtml(utf8_encode(str_replace($busc_imagenes, $remm_imagenes, $varrr)));

// (Optional) Setup the paper size and orientation
    if ($cnt_campos <= 7) {
        $orientation = 'portrait';
    } else {
        $orientation = 'landscape';
    }
    $dompdf->setPaper('A4', $orientation);

// Render the HTML as PDF
    $dompdf->render();

// Output the generated PDF to Browser
    //$dompdf->stream();

    $output = $dompdf->output();




    /* registro */
    if ($id_cierre == 0) {
        query("INSERT INTO cursos_cierres(
          id_curso, 
          id_administrador, 
          numeracion, 
          fecha, 
          estado
          ) VALUES (
          '$id_curso',
          '$id_administrador',
          '$numeracion',
          '$fecha',
          '1'
          )");
        $id_cierre = mysql_insert_id();
        
        logcursos('Cierre de curso ['.$numeracion.']', 'curso-cierre', 'curso', $id_curso);
    }

    $nombre_archivo = 'doc' . $cod_documento . '.pdf';
    file_put_contents("../../archivos/documentos/" . $nombre_archivo, $output);

    query("INSERT INTO cursos_cierres_documentos(
                id_cierre, 
                id_administrador, 
                codigo, 
                apartado, 
                nombre_archivo, 
                fecha
                ) VALUES (
                '$id_cierre',
                '$id_administrador',
                '$cod_documento',
                '$apartado',
                '$nombre_archivo',
                '$fecha'
                )");
    
    logcursos('Generacion de documento de cierre ['.$cod_documento.']', 'curso-cierre', 'curso', $id_curso);

    query("UPDATE cursos SET id_cierre='$id_cierre',sw_cierre='1' WHERE id='$id_curso' ORDER BY id DESC limit 1 ");

    $url_documento = "https://cursos.bo/contenido/archivos/documentos/$nombre_archivo";
    ?>
    <div class="alert alert-success">
        <strong>Exito!</strong> el registro se agrego correctamente.
    </div>
    <div class="text-center">
        <p>Puede visualizar el documento de cierre en el siguiente enlace:</p>
        <b>Documento: <?php echo $cod_documento; ?></b>
        <br/>
        <a href="<?php echo $url_documento; ?>" target='_blank' class="btn btn-danger active">PDF - DOCUMENTO DE CIERRE</a>
    </div>
    <hr/>
    <p class="text-center">Tambien puede visualizar el documento de cierre en los siguientes formatos:</p>
    <div class="panel-footer text-center">
        <button class="btn btn-default" onclick="generar_s_reporte('impresion');">IMPRIMIR</button>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button class="btn btn-success active" onclick="generar_s_reporte('excel');">EXCEL</button>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button class="btn btn-info active" onclick="generar_s_reporte('word');">WORD</button>
    </div>
    <script>

        function generar_s_reporte(formato) {
            window.open('http://cursos.bo/contenido/paginas.admin/ajax/ajax.impresion.cursos-participantes.exportar-lista.php?id_curso=<?php echo $id_curso; ?>&' + formato + '=true&' + '<?php echo $data_required; ?>' + '&rimp_cod_documento=<?php echo $cod_documento; ?>', 'popup', 'width=700,height=500');
        }
    </script>
    <?php
} else {
    ?>
    <b>Reporte generado segun las especificaciones solicitadas</b>
    <p>Seleccione la modalidad de exportaci&oacute;n de reporte</p>
    <hr/>
    <div class="panel-footer text-center">
        <button class="btn btn-default" onclick="generar_s_reporte('impresion');">IMPRIMIR</button>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button class="btn btn-success active" onclick="generar_s_reporte('excel');">EXCEL</button>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button class="btn btn-info active" onclick="generar_s_reporte('word');">WORD</button>
    </div>
    <script>

        function generar_s_reporte(formato) {
            window.open('http://cursos.bo/contenido/paginas.admin/ajax/ajax.impresion.cursos-participantes.exportar-lista.php?id_curso=<?php echo $id_curso; ?>&' + formato + '=true&' + '<?php echo $data_required; ?>', 'popup', 'width=700,height=500');
        }
    </script>
    <?php
}
