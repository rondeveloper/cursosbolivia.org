<?php
/* datos del curso */
$titulo_identificador_curso = $get[2];
$rq1 = query("SELECT * FROM cursos WHERE titulo_identificador='$titulo_identificador_curso' AND estado IN (1,2) ORDER BY FIELD(estado,1,2),id DESC limit 1 ");
$curso = fetch($rq1);
$id_curso = $curso['id'];
$sw_recomendaciones_curso = $curso['sw_recomendaciones'];
$rec_limitdesc_curso = $curso['rec_limitdesc'];

if ($sw_recomendaciones_curso == '0') {
    echo "<script>location.href='$dominio';</script>";
    exit;
}

/* regitro de recomendacion */
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
    $ip_actual = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip_actual = $_SERVER['REMOTE_ADDR'];
}
$rqver1 = query("SELECT id FROM recomendaciones WHERE id_curso='$id_curso' AND ip_registro='$ip_actual' AND DATE(fecha_registro)=CURDATE() ");
if (num_rows($rqver1) == 0) {
    query("INSERT INTO recomendaciones (id_curso,ip_registro,fecha_registro) VALUES ('$id_curso','$ip_actual',NOW())");
    $id_recomendacion = insert_id();
} else {
    $rqver2 = fetch($rqver1);
    $id_recomendacion = $rqver2['id'];
}
query("INSERT INTO recomendaciones_referidos (id_recomendacion) VALUES ('$id_recomendacion')");
$id_referido = insert_id();

$title_pr = str_replace('?', '', utf8_decode($curso['titulo']));
$link_pr = $dominio.'r/' . $id_referido . '/';
$msj_whatsapp = 'Hola__Te recomiendo este curso:__' . $title_pr . '__' . $link_pr . '';
$txt_whatsapp = utf8_encode(str_replace('__', '%0A', str_replace(' ', '%20', $msj_whatsapp)));


/* numero de participantes */
$nro_participantes = 1;
?>
<script>
    function setCookie(cname, cvalue) {
        var d = new Date();
        d.setTime(d.getTime() + (2 * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
</script>
<script>
    function isMobile() {
        var userAgent = navigator.userAgent || navigator.vendor || window.opera;
        var ret = false;
        if (/windows phone/i.test(userAgent)) {
            ret = true;
        }
        if (/android/i.test(userAgent)) {
            ret = true;
        }
        if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
            ret = true;
        }
        return ret;
    }
</script>
<script>
    function recomendar() {
        if (isMobile()) {
            $("#boton-rec").attr('disabled', true);
            //window.open(url_data_rec);
            location.href = url_data_rec;
            porcentaje_desc++;
            $.ajax({
                url: 'contenido/paginas/ajax/ajax.recomendar.check_recomendar.php',
                data: {id_referido: '<?php echo $id_referido; ?>'},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    url_data_rec = data;
                    setTimeout(function () {
                        update_data_r();
                    }, 3000);
                }
            });
        } else {
            alert('FUNCION SOLO DISPONIBLE PARA DISPOSITIVOS MOVILES, lamentamos la molestia, debe ingresar a esta seccion desde un dispositivo movil.');
        }
    }
    function update_data_r() {
        $(".p-desc").html(porcentaje_desc);
        setCookie('cookie_p-desc-<?php echo $id_curso; ?>', porcentaje_desc);
        if(porcentaje_desc<=20){
            $("#pro-bar").css('width',(10+ (porcentaje_desc*90/20))+'%');
        }
        $("#boton-rec").attr('disabled', false);
    }
    function start_data_r() {
        $(".p-desc").html(porcentaje_desc);
        if(porcentaje_desc<=20){
            $("#pro-bar").css('width',(10+ (porcentaje_desc*90/20))+'%');
        }
    }
    function utilizar_descuento() {
        if (porcentaje_desc === 0) {
            alert('El descuento es 0% , debe realizar algunas recomendaciones para optar por el descuento. (1% por cada recomendacion)');
        } else {
            location.href = 'registro-curso/<?php echo $titulo_identificador_curso; ?>/<?php echo $id_recomendacion; ?>/<?php echo md5(md5($id_recomendacion . 'desc-c') . '8431'); ?>.html';
        }
    }
</script>
<script>
    var porcentaje_desc = getCookie('cookie_p-desc-<?php echo $id_curso; ?>');
    if (porcentaje_desc === null || porcentaje_desc === '') {
        porcentaje_desc = 0;
    }
    var url_data_rec = 'https://api.whatsapp.com/send?text=<?php echo $txt_whatsapp; ?>';
</script>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <style>
            .tabla-rec{
                background: #FFF;
            }
            .tabla-rec td{
                padding: 10px !important;
                font-size: 12pt !important;
            }
        </style>
        <div class="box_seccion_a" style="width:100%;">
            <div class="seccion_a">
                <div class="contenido_seccion white-content-one">

                    <div class="areaRegistro1 ftb-registro-5">
                        <h3 class="tit-02">INSCRIPCI&Oacute;N DE PARTICIPANTES</h3>
                        <div class="row">
                            <?php
                            include_once 'contenido/paginas/items/item.m.datos_curso.php';
                            ?>
                        </div>

                        <h3 style="background:#DDD;color:#444;margin-top: 20px;padding: 5px 10px;">DESCUENTO EN RECOMENDACIONES</h3>
                        <p>
                            Puedes obtener un <b>porcentaje de descuento</b> para este curso, desde 1% hasta <?php echo $rec_limitdesc_curso; ?>% (obtendras 1% de descuento por cada recomendaci&oacute;n que realices)
                            <br>
                            Para ello debes recomendar este curso via whatsapp con el siguiente formulario:
                        </p>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div style="background: #c7e0ff;padding: 10px;border-radius: 10px;">
                                    <table class="table table-striped table-hover table-bordered tabla-rec">
                                        <tr>
                                            <td><b>CURSO:</b></td>
                                            <td><?php echo $curso['titulo']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>ACUMULADO:</b>
                                                <br>
                                                <span style="font-size:10pt;">(Porcentaje de descuento)</span>
                                            </td>
                                            <td class="text-center">
                                                <b style="font-size: 30pt;color: #2b8adc;">
                                                    <span class="p-desc">0</span> <span style="font-size:20pt;">%</span>
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <b>Meta: 20%</b> <span style="font-size:8pt;">(Este curso esta habilitado hasta <?php echo $rec_limitdesc_curso; ?>% de descuento)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div>
                                                    0%
                                                    <span class="pull-right">20%</span>
                                                </div>
                                                <div id="pro-bar-box" class="progress progress-striped active" style="display: block;border: 1px solid #318cb8;background: #fffaf3;border-radius: 10px;height: 35px;">
                                                    <div id="pro-bar" class="progress-bar progress-bar-primary" style="width: 10%;"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>C&oacute;digo de descuento:</b>
                                                <br>
                                                <span style="font-size:10pt;">(por recomendaciones)</span>
                                            </td>
                                            <td>
                                                RCD000<?php echo $id_recomendacion; ?>
                                                <br>
                                                <br>
                                                <a onclick="utilizar_descuento();" style="color: #002bff;text-decoration: underline;cursor:pointer;font-size: 10pt;">
                                                    Usar descuento <span class="p-desc">0</span>%
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-center" style="padding: 10px 0px;">
                                                <br>
                                                <b class="btn btn-success btn-lg" onclick="recomendar();" id="boton-rec" style="border-radius: 8px;">RECOMENDAR (Obtener 1%)</b>
                                                <br>
                                                &nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <br>
                        <br>

                        <br/>
                        <br/>

                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <?php echo $___nombre_del_sitio; ?>
                                </div>
                            </div>
                        </div>                    
                    </div>
                    <hr/>
                </div>
            </div>
        </div>
    </section>
</div>                     

<script>
    start_data_r();
</script>






<script>
    function checkParticipante(dat, p) {
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.registro-curso.checkParticipante.php',
            data: {dat: dat},
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                var data_json_parsed = JSON.parse(data);
                if (data_json_parsed['estado'] === 1) {
                    $("#nombres_p" + p).val(data_json_parsed['nombres']);
                    $("#apellidos_p" + p).val(data_json_parsed['apellidos']);
                    $("#correo_p" + p).val(data_json_parsed['correo']);
                    $("#prefijo_p" + p).val(data_json_parsed['prefijo']).change();
                }
            }
        });
    }
</script>



<?php

function fecha_curso($dat) {
    $ar1 = explode("-", $dat);
    $array_meses = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $ar1[2] . " de " . $array_meses[(int) $ar1[1]];
}
