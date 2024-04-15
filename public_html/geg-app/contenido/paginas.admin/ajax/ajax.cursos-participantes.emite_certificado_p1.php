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

/* datos recibidos */
$id_participante = post('id_participante');
$nro_certificado = post('nro_certificado');

/* datos de participante */
$resultado1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$participante = mysql_fetch_array($resultado1);

/* datos de curso */
$id_curso = $participante['id_curso'];
$rqc1 = query("SELECT titulo,titulo_identificador,fecha,imagen,costo,id_certificado,id_certificado_2,id_certificado_3,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado,id_modalidad FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = mysql_fetch_array($rqc1);
$nombre_del_curso = $rqc2['titulo'];
$titulo_identificador_del_curso = $rqc2['titulo_identificador'];
$fecha_del_curso = $rqc2['fecha'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
$id_certificado_3_curso = $rqc2['id_certificado_3'];
if ($rqc2['imagen'] !== '') {
    if (!file_exists("../../imagenes/paginas/" . $rqc2['imagen'])) {
        $url_imagen_del_curso = "https://www.infosicoes.com/paginas/" . $rqc2['imagen'] . ".size=4.img";
    } else {
        $url_imagen_del_curso = $dominio . "paginas/" . $rqc2['imagen'] . ".size=4.img";
    }
} else {
    $url_imagen_del_curso = "https://www.infosicoes.com/images/banner-cursos.png.size=4.img";
}
$costo_curso = $rqc2['costo'];
$id_modalidad_curso = $rqc2['id_modalidad'];

/* codigo de certificado */
if ((int) $nro_certificado == 1) {
    $codigo_de_certificado_del_curso = $rqc2['codigo_certificado'];
    $txt_verif_id_emision_certificado = $participante['id_emision_certificado'];
} elseif ((int) $nro_certificado == 2) {
    $id_certificado_curso = $id_certificado_2_curso;
    $rqc_c2_1 = query("select codigo from cursos_certificados where id='$id_certificado_2_curso' ");
    $rqc_c2_2 = mysql_fetch_array($rqc_c2_1);
    $codigo_de_certificado_del_curso = $rqc_c2_2['codigo'];
    $txt_verif_id_emision_certificado = $participante['id_emision_certificado_2'];
} elseif ((int) $nro_certificado == 3) {
    $id_certificado_curso = $id_certificado_3_curso;
    $rqc_c2_1 = query("select codigo from cursos_certificados where id='$id_certificado_3_curso' ");
    $rqc_c2_2 = mysql_fetch_array($rqc_c2_1);
    $codigo_de_certificado_del_curso = $rqc_c2_2['codigo'];
    $txt_verif_id_emision_certificado = $participante['id_emision_certificado_3'];
} elseif ((int) $nro_certificado == 12) {
    $id_certificado_curso .= '|AND|' . $id_certificado_2_curso;
    $rqc_c2_1 = query("select codigo from cursos_certificados where id='$id_certificado_2_curso' ");
    $rqc_c2_2 = mysql_fetch_array($rqc_c2_1);
    $codigo_de_certificado_del_curso = $rqc2['codigo_certificado'] . " & " . $rqc_c2_2['codigo'];
    $txt_verif_id_emision_certificado = 0;
} elseif ((int) $nro_certificado == 123) {
    $id_certificado_curso .= '|AND|' . $id_certificado_2_curso.'|AND|' . $id_certificado_3_curso;
    $rqc_c2_1 = query("select codigo from cursos_certificados where id='$id_certificado_2_curso' ");
    $rqc_c2_2 = mysql_fetch_array($rqc_c2_1);
    $rqc_c3_1 = query("select codigo from cursos_certificados where id='$id_certificado_3_curso' ");
    $rqc_c3_2 = mysql_fetch_array($rqc_c3_1);
    $codigo_de_certificado_del_curso = $rqc2['codigo_certificado'] . " & " . $rqc_c2_2['codigo']. " & " . $rqc_c3_2['codigo'];
    $txt_verif_id_emision_certificado = 0;
} elseif ((int) $nro_certificado == 0) {
    $codigo_de_certificado_del_curso = 'CERTIFICADOS ADICIONALES';
    $txt_verif_id_emision_certificado = 99;
} elseif ((int) $nro_certificado > 100) {
    $id_certificado_curso = $nro_certificado;
    /* cert adicional */
    $rqc_c2_1 = query("select codigo from cursos_certificados where id='$id_certificado_curso' ");
    $rqc_c2_2 = mysql_fetch_array($rqc_c2_1);
    $codigo_de_certificado_del_curso = $rqc_c2_2['codigo'];
    $txt_verif_id_emision_certificado = 0;
}


/* sw certificados adiconales */
$sw_certificados_adicionales = false;
$rqvca1 = query("SELECT id FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso' LIMIT 1 ");
if (mysql_num_rows($rqvca1) > 0 && ((int) $nro_certificado == 0)) {
    $sw_certificados_adicionales = true;
}

/* onclick="imprimir_certificado_individual('<?php echo $participante['id_emision_certificado']; ?>');" */
?>
<div class="row">
    <div class="col-md-6 text-left">
        <?php
        if ($nro_certificado == '2') {
            ?>
            <b>SEGUNDO CERTIFICADO</b> 2do Cert
            <br/>
            <?php
        } elseif ($nro_certificado == '12') {
            ?>
            <b>PRIMER Y SEGUNDO CERTIFICADO</b>
            <br/>
            <?php
        } elseif ($nro_certificado == '123') {
            ?>
            <b>PRIMER, SEGUNDO Y TERCER CERTIFICADO</b>
            <br/>
            <?php
        }
        ?>
        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
        <br/>
        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
        <br/>
        <b>CERTIFICADO:</b> <?php echo $codigo_de_certificado_del_curso; ?>
        <br/>
        <b>PARTICIPANTE:</b> <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
    </div>
    <div class="col-md-6 text-right">
        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-md-12 text-left text-center" style="line-height: 0.8;">
        <h3 class="text-center" style="font-size: 20pt;
            text-transform: uppercase;
            color: #00789f;font-weight: bold;">
            <?php echo trim($participante['nombres'] . ' ' . $participante['apellidos']); ?>
        </h3>
        <b style="font-size: 17pt;color: gray;">
            CI: &nbsp; <?php echo trim($participante['ci'] . ' ' . $participante['ci_expedido']); ?>
        </b>
        <br/>
        <br/>
        <b style="font-size: 12pt;">
            <?php echo trim($participante['correo'] . ' - ' . $participante['celular']); ?>
        </b>
    </div>
</div>
<hr/>

<?php
if ($sw_certificados_adicionales) {
    ?>
    <div>
        <div id="AJAXCONTENT-emitir_certificados_adicionales">
            <?php 
            $ids_emisiones_adicionales = ''; 
            $rqvpca1 = query("SELECT id_emision_certificado FROM cursos_rel_partcertadicional WHERE id_participante='$id_participante' AND id_certificado IN (SELECT id_certificado FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso') ");
            $sw_certs_adicionales_emitidos = false;
            while($rqvpca2 = mysql_fetch_array($rqvpca1)){
                $sw_certs_adicionales_emitidos = true;
                $ids_emisiones_adicionales .= ','.$rqvpca2['id_emision_certificado']; 
            }
            if($sw_certs_adicionales_emitidos){
                ?>
                <b class="btn btn-block btn-default" onclick="imprimir_certificados_adicionales('<?php echo trim($ids_emisiones_adicionales,','); ?>');">IMPRIMIR TODOS ADICIONALES</b>
                <?php 
            }else{
                ?>
                <b class="btn btn-block btn-success" onclick="emitir_certificados_adicionales();">EMITIR TODOS ADICIONALES</b>
                <?php 
            }
            ?>
            <hr/>
        <?php
        $rqmc1 = query("SELECT * FROM cursos_rel_cursocertificado WHERE id_curso='$id_curso' ORDER BY id ASC ");
        while ($rqmc2 = mysql_fetch_array($rqmc1)) {
            $rqdcrt1 = query("SELECT * FROM cursos_certificados WHERE id='" . $rqmc2['id_certificado'] . "' LIMIT 1 ");
            $rqdcrt2 = mysql_fetch_array($rqdcrt1);
            $id_certificado_adicional = $rqdcrt2['id'];
            ?>
            <b class="btn btn-xs btn-block btn-primary active">CERTIFICADO <?php echo $rqdcrt2['codigo']; ?></b>
            <table class="table table-striped table-bordered">
                <tr>
                    <td>CODIGO</td>
                    <td><?php echo $rqdcrt2['codigo']; ?></td>
                </tr>
                <tr>
                    <td>TEXTO 2</td>
                    <td><?php echo $rqdcrt2['cont_dos']; ?></td>
                </tr>
                <tr>
                    <td>TEXTO 3</td>
                    <td><?php echo $rqdcrt2['cont_tres']; ?></td>
                </tr>
                <?php
                $rqvpca1 = query("SELECT id_emision_certificado FROM cursos_rel_partcertadicional WHERE id_participante='$id_participante' AND id_certificado='$id_certificado_adicional' LIMIT 1 ");
                if (mysql_num_rows($rqvpca1) > 0) {
                    $rqvpca2 = mysql_fetch_array($rqvpca1);
                    $id_emision_certificado = $rqvpca2['id_emision_certificado'];
                    $rqdc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id='$id_emision_certificado' LIMIT 1 ");
                    $rqdc2 = mysql_fetch_array($rqdc1);
                    $rqvec1 = query("SELECT sw_enviado FROM cursos_envio_certificados WHERE id_emision_certificado='$id_certificado_adicional' ");
                    $txt_estado_envio = "SIN ENVIO";
                    if (mysql_num_rows($rqvec1) > 0) {
                        $rqvec2 = mysql_fetch_array($rqvec1);
                        if ($rqvec2['sw_enviado'] == 1) {
                            $txt_estado_envio = "<b style='color:#00cc33;'>ENVIADO</b>";
                        } else {
                            $txt_estado_envio = "<b style='color:#006f93;'>A ENVIAR</b>";
                        }
                    }

                    echo '<tr>';
                    echo '<td>ID de certificado</td>';
                    echo '<td>' . $rqdc2['certificado_id'] . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Visualizaci&oacute;n</td>';
                    echo '<td><button class="btn btn-success btn-xs btn-block" onclick="imprimir_certificado_individual(\'' . $id_emision_certificado . '\');"><i class="fa fa-eye"></i> MOSTRAR CERTIFICADO</button></td>';
                    echo '</tr>';

                    if ($id_modalidad_curso == '2') {
                        echo '<tr>';
                        echo '<td>Edici&oacute;n</td>';
                        echo '<td><button class="btn btn-warning btn-xs btn-block" onclick="edita_certificado_individual(\'' . $id_emision_certificado . '\');" data-toggle="modal" data-target="#MODAL-edita_certificado_individual"><i class="fa fa-edit"></i> EDITAR CERTIFICADO</button></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td>Copia legalizada cert. principal</td>';
                    echo '<td><button class="btn btn-default btn-xs btn-block" onclick="imprimir_copia_legalizada(\'' . $id_emision_certificado . '\');"><i class="fa fa-copy"></i> COPIA LEGALIZADA</button></td>';
                    echo '</tr>';

                    if ($id_modalidad_curso == '2') {
                        echo '<tr>';
                        echo '<td>Certificado digital</td>';
                        echo '<td><button class="btn btn-info btn-xs btn-block" onclick="visualizar_certificado_digital(\'' . $id_emision_certificado . '\');"><i class="fa fa-exchange"></i> CERTIFICADO DIGITAL</button></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td>Envio fisico de certificado</td>';
                    echo '<td>' . $txt_estado_envio . ' <button class="btn btn-default btn-xs pull-right" onclick="proceso_envio_de_certificado(\'' . $id_emision_certificado . '\');" data-toggle="modal" data-target="#MODAL-proceso_envio_de_certificado"><i class="fa fa-list" style="color:#8f8f8f;"></i></button></td>';
                    echo '</tr>';
                    if ($id_modalidad_curso == '2') {
                        ?>
                        <tr>
                            <td>
                                Envia el certificado digital en forma de PDF.
                            </td>
                            <td id="box-enviar_cert_digital-<?php echo $id_emision_certificado; ?>">
                                <b class="btn btn-default btn-xs btn-block" onclick="enviar_cert_digital('<?php echo $id_emision_certificado; ?>');">
                                    <i class="fa fa-send"></i> &nbsp; ENVIAR CERT. DIGITAL
                                </b>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="2"><b>AVISO</b> El participante no tiene este certificado.</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            <a onclick="emite_certificado_p1(<?php echo $id_participante; ?>, <?php echo $id_certificado_adicional; ?>);" class="btn btn-sm btn-warning">EMITIR CERTIFICADO</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        }
        ?>
        </div> 
    
        <?php
        if ((int) $nro_certificado == 1) {
            $rqdc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id='" . $participante['id_emision_certificado'] . "' ");
            $rqdc2 = mysql_fetch_array($rqdc1);
            ?>
            <b class="btn btn-xs btn-block btn-primary active">PRIMER CERTIFICADO</b>
            <table class="table table-striped table-bordered">
                <?php
                if ($participante['id_emision_certificado'] !== '0') {
                    $rqvec1 = query("SELECT sw_enviado FROM cursos_envio_certificados WHERE id_emision_certificado='" . $participante['id_emision_certificado'] . "' ");
                    $txt_estado_envio = "SIN ENVIO";
                    if (mysql_num_rows($rqvec1) > 0) {
                        $rqvec2 = mysql_fetch_array($rqvec1);
                        if ($rqvec2['sw_enviado'] == 1) {
                            $txt_estado_envio = "<b style='color:#00cc33;'>ENVIADO</b>";
                        } else {
                            $txt_estado_envio = "<b style='color:#006f93;'>A ENVIAR</b>";
                        }
                    }

                    echo '<tr>';
                    echo '<td>ID de certificado</td>';
                    echo '<td>' . $rqdc2['certificado_id'] . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Visualizaci&oacute;n</td>';
                    echo '<td><button class="btn btn-success btn-xs btn-block" onclick="imprimir_certificado_individual(\'' . $participante['id_emision_certificado'] . '\');"><i class="fa fa-eye"></i> MOSTRAR CERTIFICADO</button></td>';
                    echo '</tr>';

                    if ($id_modalidad_curso == '2') {
                        echo '<tr>';
                        echo '<td>Edici&oacute;n</td>';
                        echo '<td><button class="btn btn-warning btn-xs btn-block" onclick="edita_certificado_individual(\'' . $participante['id_emision_certificado'] . '\');" data-toggle="modal" data-target="#MODAL-edita_certificado_individual"><i class="fa fa-edit"></i> EDITAR CERTIFICADO</button></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td>Copia legalizada cert. principal</td>';
                    echo '<td><button class="btn btn-default btn-xs btn-block" onclick="imprimir_copia_legalizada(\'' . $participante['id_emision_certificado'] . '\');"><i class="fa fa-copy"></i> COPIA LEGALIZADA</button></td>';
                    echo '</tr>';

                    if ($id_modalidad_curso == '2') {
                        echo '<tr>';
                        echo '<td>Certificado digital</td>';
                        echo '<td><button class="btn btn-info btn-xs btn-block" onclick="visualizar_certificado_digital(\'' . $participante['id_emision_certificado'] . '\');"><i class="fa fa-exchange"></i> CERTIFICADO DIGITAL</button></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td>Envio fisico de certificado</td>';
                    echo '<td>' . $txt_estado_envio . ' <button class="btn btn-default btn-xs pull-right" onclick="proceso_envio_de_certificado(\'' . $participante['id_emision_certificado'] . '\');" data-toggle="modal" data-target="#MODAL-proceso_envio_de_certificado"><i class="fa fa-list" style="color:#8f8f8f;"></i></button></td>';
                    echo '</tr>';
                    if ($id_modalidad_curso == '2') {
                        ?>
                        <tr>
                            <td>
                                Envia el certificado digital en forma de PDF.
                            </td>
                            <td id="box-enviar_cert_digital-<?php echo $participante['id_emision_certificado']; ?>">
                                <b class="btn btn-default btn-xs btn-block" onclick="enviar_cert_digital('<?php echo $participante['id_emision_certificado']; ?>');">
                                    <i class="fa fa-send"></i> &nbsp; ENVIAR CERT. DIGITAL
                                </b>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr>';
                    echo '<td colspan="2">No tiene primer certificado</td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <?php
        } elseif ((int) $nro_certificado == 2) {
            ?>
            <b class="btn btn-xs btn-block btn-primary active">SEGUNDO CERTIFICADO</b>
            <table class="table table-striped table-bordered">
                <?php
                if ($participante['id_emision_certificado_2'] !== '0') {
                    $rqdc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id='" . $participante['id_emision_certificado_2'] . "' ");
                    $rqdc2 = mysql_fetch_array($rqdc1);
                    echo '<tr>';
                    echo '<td>ID de certificado</td>';
                    echo '<td>' . $rqdc2['certificado_id'] . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Visualizaci&oacute;n</td>';
                    echo '<td><button class="btn btn-success btn-xs btn-block" onclick="imprimir_certificado_individual(\'' . $participante['id_emision_certificado_2'] . '\');">MOSTRAR CERTIFICADO</button></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Copia legalizada cert. principal</td>';
                    echo '<td><button class="btn btn-default btn-xs btn-block" onclick="imprimir_copia_legalizada(\'' . $participante['id_emision_certificado_2'] . '\');">COPIA LEGALIZADA</button></td>';
                    echo '</tr>';
                } else {
                    echo '<tr>';
                    echo '<td colspan="2">No tiene segundo certificado</td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <?php
        } elseif ((int) $nro_certificado == 3) {
            ?>
            <b class="btn btn-xs btn-block btn-primary active">TERCER CERTIFICADO</b>
            <table class="table table-striped table-bordered">
                <?php
                if ($participante['id_emision_certificado_3'] !== '0') {
                    $rqdc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id='" . $participante['id_emision_certificado_3'] . "' ");
                    $rqdc2 = mysql_fetch_array($rqdc1);
                    echo '<tr>';
                    echo '<td>ID de certificado</td>';
                    echo '<td>' . $rqdc2['certificado_id'] . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Visualizaci&oacute;n</td>';
                    echo '<td><button class="btn btn-success btn-xs btn-block" onclick="imprimir_certificado_individual(\'' . $participante['id_emision_certificado_3'] . '\');">MOSTRAR CERTIFICADO</button></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Copia legalizada cert. principal</td>';
                    echo '<td><button class="btn btn-default btn-xs btn-block" onclick="imprimir_copia_legalizada(\'' . $participante['id_emision_certificado_3'] . '\');">COPIA LEGALIZADA</button></td>';
                    echo '</tr>';
                } else {
                    echo '<tr>';
                    echo '<td colspan="2">No tiene tercer certificado</td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <?php
        }
        ?>
    </div>
    <?php
} elseif ($txt_verif_id_emision_certificado == 0) {
    ?>

    <input type="hidden" id="id_certificado-<?php echo $participante['id']; ?>" value="<?php echo $id_certificado_curso; ?>" />
    <input type="hidden" id="id_curso-<?php echo $participante['id']; ?>" value="<?php echo $id_curso; ?>" />
    <input type="hidden" id="id_participante-<?php echo $participante['id']; ?>" value="<?php echo $participante['id']; ?>" />

    <!-- DIV CONTENT AJAX :: EMITE CERTIFICADO P2 -->
    <div id="ajaxloading-emite_certificado_p2"></div>
    <div class="text-center" id='ajaxbox-emite_certificado_p2'>
        <h5 class="text-center">
            Emision de certificado para
        </h5>
        <div class="row">
            <div class="col-md-12 text-left">
                <input type="text" class="form-control text-center" value="<?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>" readonly=""/>
                <input type="hidden" id="receptor_de_certificado-<?php echo $participante['id']; ?>" value="<?php echo trim($participante['prefijo'] . ' ' . $participante['nombres'] . ' ' . $participante['apellidos']); ?>"/>
            </div>
        </div>
        <?php
        if ($id_modalidad_curso == '2' && false) {
            $d = date("d");
            $d_begin = ((int) $d - 5);
            if ($d_begin < 1) {
                $d_begin = 1;
            }
            if ((int) $d_begin < 10) {
                $d_begin = '0' . (int) $d_begin;
            }
            $ar_meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            $mes = $ar_meses[(int) date("m")];
            $y = date("Y");

            /* cont cert */
            if ((int) $nro_certificado == 1) {
                $rqdcr1 = query("SELECT cont_dos,cont_tres,texto_qr FROM cursos_certificados WHERE id='$id_certificado_curso' ");
                $rqdcr2 = mysql_fetch_array($rqdcr1);
                $cont_dos = $rqdcr2['cont_dos'];
                $cont_tres = $rqdcr2['cont_tres'];
                $texto_qr = $rqdcr2['texto_qr'];
            } elseif ((int) $nro_certificado == 2) {
                $rqdcr1 = query("SELECT cont_dos,cont_tres,texto_qr FROM cursos_certificados WHERE id='$id_certificado_2_curso' ");
                $rqdcr2 = mysql_fetch_array($rqdcr1);
                $cont_dos = $rqdcr2['cont_dos'];
                $cont_tres = $rqdcr2['cont_tres'];
                $texto_qr = $rqdcr2['texto_qr'];
            } elseif ((int) $nro_certificado == 3) {
                $rqdcr1 = query("SELECT cont_dos,cont_tres,texto_qr FROM cursos_certificados WHERE id='$id_certificado_3_curso' ");
                $rqdcr2 = mysql_fetch_array($rqdcr1);
                $cont_dos = $rqdcr2['cont_dos'];
                $cont_tres = $rqdcr2['cont_tres'];
                $texto_qr = $rqdcr2['texto_qr'];
            }
            ?>
            <h5 class="text-center">
                Texto mension del curso
            </h5>
            <div class="row">
                <div class="col-md-12 text-left">
                    <input type="text" id="cont_dos" class="form-control" value='<?php echo $cont_dos; ?>' placeholder="Texto mension del curso..."/>
                </div>
            </div>
            <h5 class="text-center">
                Texto lugar/fecha de realizaci&oacute;n
            </h5>
            <script>
                function cambia_departamento_e_cert() {
                    $("#cont_tres").val();
                }
            </script>
            <div class="row">
                <div class="col-xs-3 col-md-3 text-left" style="padding-right: 0px;">
                    <select class="form-control" onchange="$('#cont_tres').val(this.value);">
                        <option value="Realizado en Bolivia del <?php echo $d_begin; ?> al <?php echo $d; ?> del mes de <?php echo $mes; ?> de <?php echo $y; ?>">Sin ciudad</option>
                        <?php
                        $rqdcd1 = query("SELECT nombre FROM departamentos WHERE tipo='1' ORDER BY orden ");
                        while ($rqdcd2 = mysql_fetch_array($rqdcd1)) {
                            ?>
                            <option value="Realizado en <?php echo $rqdcd2['nombre']; ?> - Bolivia del <?php echo $d_begin; ?> al <?php echo $d; ?> del mes de <?php echo $mes; ?> de <?php echo $y; ?>"><?php echo $rqdcd2['nombre']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-xs-9 col-md-9 text-left" style="padding-left: 0px;">
                    <input type="text" id="cont_tres" class="form-control" value="Realizado en Bolivia del <?php echo $d_begin; ?> al <?php echo $d; ?> del mes de <?php echo $mes; ?> de <?php echo $y; ?>" placeholder="Fecha de realizaci&oacute;n..."/>
                </div>
            </div>
            <h5 class="text-center">
                Texto QR
            </h5>
            <div class="row">
                <div class="col-md-12 text-left">
                    <input type="text" id="texto_qr" class="form-control" value='<?php echo $texto_qr; ?>' placeholder="Fecha QR"/>
                </div>
            </div>
            <h5 class="text-center">
                Fecha QR
            </h5>
            <div class="row">
                <div class="col-md-12 text-left">
                    <input type="date" id="fecha_qr" class="form-control" value="<?php echo date("Y-m-d"); ?>" placeholder="Fecha QR"/>
                </div>
            </div>
            <hr/>
            <?php
        } else {
            echo "<input type='hidden' id='cont_tres' value=''/>";
            echo "<input type='hidden' id='fecha_qr' value=''/>";
            echo "<input type='hidden' id='cont_dos' value=''/>";
            echo "<input type='hidden' id='texto_qr' value=''/>";
        }
        ?>

        <br/>
        <br/>

        <button class="btn btn-success" onclick="emite_certificado_p2('<?php echo $participante['id']; ?>',<?php echo (int) $nro_certificado; ?>);">EMITIR CERTIFICADO</button>
        &nbsp;&nbsp;&nbsp;
        <button class="btn btn-danger" onclick="" data-dismiss="modal">CANCELAR</button>
    </div>
    <hr/>

    <?php
} else {
    ?>
    <div>
        <?php
        if ((int) $nro_certificado == 1) {
            $rqdc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id='" . $participante['id_emision_certificado'] . "' ");
            $rqdc2 = mysql_fetch_array($rqdc1);
            ?>
            <b class="btn btn-xs btn-block btn-primary active">PRIMER CERTIFICADO</b>
            <table class="table table-striped table-bordered">
                <?php
                if ($participante['id_emision_certificado'] !== '0') {
                    $rqvec1 = query("SELECT sw_enviado FROM cursos_envio_certificados WHERE id_emision_certificado='" . $participante['id_emision_certificado'] . "' ");
                    $txt_estado_envio = "SIN ENVIO";
                    if (mysql_num_rows($rqvec1) > 0) {
                        $rqvec2 = mysql_fetch_array($rqvec1);
                        if ($rqvec2['sw_enviado'] == 1) {
                            $txt_estado_envio = "<b style='color:#00cc33;'>ENVIADO</b>";
                        } else {
                            $txt_estado_envio = "<b style='color:#006f93;'>A ENVIAR</b>";
                        }
                    }

                    echo '<tr>';
                    echo '<td>ID de certificado</td>';
                    echo '<td>' . $rqdc2['certificado_id'] . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Visualizaci&oacute;n</td>';
                    echo '<td><button class="btn btn-success btn-xs btn-block" onclick="imprimir_certificado_individual(\'' . $participante['id_emision_certificado'] . '\');"><i class="fa fa-eye"></i> MOSTRAR CERTIFICADO</button></td>';
                    echo '</tr>';

                    if ($id_modalidad_curso == '2') {
                        echo '<tr>';
                        echo '<td>Edici&oacute;n</td>';
                        echo '<td><button class="btn btn-warning btn-xs btn-block" onclick="edita_certificado_individual(\'' . $participante['id_emision_certificado'] . '\');" data-toggle="modal" data-target="#MODAL-edita_certificado_individual"><i class="fa fa-edit"></i> EDITAR CERTIFICADO</button></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td>Copia legalizada cert. principal</td>';
                    echo '<td><button class="btn btn-default btn-xs btn-block" onclick="imprimir_copia_legalizada(\'' . $participante['id_emision_certificado'] . '\');"><i class="fa fa-copy"></i> COPIA LEGALIZADA</button></td>';
                    echo '</tr>';

                    if ($id_modalidad_curso == '2') {
                        echo '<tr>';
                        echo '<td>Certificado digital</td>';
                        echo '<td><button class="btn btn-info btn-xs btn-block" onclick="visualizar_certificado_digital(\'' . $participante['id_emision_certificado'] . '\');"><i class="fa fa-exchange"></i> CERTIFICADO DIGITAL</button></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td>Envio fisico de certificado</td>';
                    echo '<td>' . $txt_estado_envio . ' <button class="btn btn-default btn-xs pull-right" onclick="proceso_envio_de_certificado(\'' . $participante['id_emision_certificado'] . '\');" data-toggle="modal" data-target="#MODAL-proceso_envio_de_certificado"><i class="fa fa-list" style="color:#8f8f8f;"></i></button></td>';
                    echo '</tr>';
                    if ($id_modalidad_curso == '2') {
                        ?>
                        <tr>
                            <td>
                                Envia el certificado digital en forma de PDF.
                            </td>
                            <td id="box-enviar_cert_digital-<?php echo $participante['id_emision_certificado']; ?>">
                                <b class="btn btn-default btn-xs btn-block" onclick="enviar_cert_digital('<?php echo $participante['id_emision_certificado']; ?>');">
                                    <i class="fa fa-send"></i> &nbsp; ENVIAR CERT. DIGITAL
                                </b>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr>';
                    echo '<td colspan="2">No tiene primer certificado</td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <?php
        } elseif ((int) $nro_certificado == 2) {
            ?>
            <b class="btn btn-xs btn-block btn-primary active">SEGUNDO CERTIFICADO</b>
            <table class="table table-striped table-bordered">
                <?php
                if ($participante['id_emision_certificado_2'] !== '0') {
                    $rqdc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id='" . $participante['id_emision_certificado_2'] . "' ");
                    $rqdc2 = mysql_fetch_array($rqdc1);
                    echo '<tr>';
                    echo '<td>ID de certificado</td>';
                    echo '<td>' . $rqdc2['certificado_id'] . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Visualizaci&oacute;n</td>';
                    echo '<td><button class="btn btn-success btn-xs btn-block" onclick="imprimir_certificado_individual(\'' . $participante['id_emision_certificado_2'] . '\');">MOSTRAR CERTIFICADO</button></td>';
                    echo '</tr>';

                    if ($id_modalidad_curso == '2') {
                        echo '<tr>';
                        echo '<td>Edici&oacute;n</td>';
                        echo '<td><button class="btn btn-warning btn-xs btn-block" onclick="edita_certificado_individual(\'' . $participante['id_emision_certificado_2'] . '\');" data-toggle="modal" data-target="#MODAL-edita_certificado_individual"><i class="fa fa-edit"></i> EDITAR CERTIFICADO</button></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td>Copia legalizada cert. principal</td>';
                    echo '<td><button class="btn btn-default btn-xs btn-block" onclick="imprimir_copia_legalizada(\'' . $participante['id_emision_certificado_2'] . '\');"><i class="fa fa-copy"></i> COPIA LEGALIZADA</button></td>';
                    echo '</tr>';

                    if ($id_modalidad_curso == '2') {
                        echo '<tr>';
                        echo '<td>Certificado digital</td>';
                        echo '<td><button class="btn btn-info btn-xs btn-block" onclick="visualizar_certificado_digital(\'' . $participante['id_emision_certificado_2'] . '\');"><i class="fa fa-exchange"></i> CERTIFICADO DIGITAL</button></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td>Envio fisico de certificado</td>';
                    echo '<td>' . $txt_estado_envio . ' <button class="btn btn-default btn-xs pull-right" onclick="proceso_envio_de_certificado(\'' . $participante['id_emision_certificado_2'] . '\');" data-toggle="modal" data-target="#MODAL-proceso_envio_de_certificado"><i class="fa fa-list" style="color:#8f8f8f;"></i></button></td>';
                    echo '</tr>';
                    if ($id_modalidad_curso == '2') {
                        ?>
                        <tr>
                            <td>
                                Envia el certificado digital en forma de PDF.
                            </td>
                            <td id="box-enviar_cert_digital-<?php echo $participante['id_emision_certificado_2']; ?>">
                                <b class="btn btn-default btn-xs btn-block" onclick="enviar_cert_digital('<?php echo $participante['id_emision_certificado_2']; ?>');">
                                    <i class="fa fa-send"></i> &nbsp; ENVIAR CERT. DIGITAL
                                </b>
                            </td>
                        </tr>
                        <?php
                    }
                    
                } else {
                    echo '<tr>';
                    echo '<td colspan="2">No tiene segundo certificado</td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <?php
        } elseif ((int) $nro_certificado == 3) {
            ?>
            <b class="btn btn-xs btn-block btn-primary active">TERCER CERTIFICADO</b>
            <table class="table table-striped table-bordered">
                <?php
                if ($participante['id_emision_certificado_3'] !== '0') {
                    $rqdc1 = query("SELECT certificado_id FROM cursos_emisiones_certificados WHERE id='" . $participante['id_emision_certificado_3'] . "' ");
                    $rqdc2 = mysql_fetch_array($rqdc1);
                    echo '<tr>';
                    echo '<td>ID de certificado</td>';
                    echo '<td>' . $rqdc2['certificado_id'] . '</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td>Visualizaci&oacute;n</td>';
                    echo '<td><button class="btn btn-success btn-xs btn-block" onclick="imprimir_certificado_individual(\'' . $participante['id_emision_certificado_3'] . '\');">MOSTRAR CERTIFICADO</button></td>';
                    echo '</tr>';
                    
                    if ($id_modalidad_curso == '2') {
                        echo '<tr>';
                        echo '<td>Edici&oacute;n</td>';
                        echo '<td><button class="btn btn-warning btn-xs btn-block" onclick="edita_certificado_individual(\'' . $participante['id_emision_certificado_3'] . '\');" data-toggle="modal" data-target="#MODAL-edita_certificado_individual"><i class="fa fa-edit"></i> EDITAR CERTIFICADO</button></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td>Copia legalizada cert. principal</td>';
                    echo '<td><button class="btn btn-default btn-xs btn-block" onclick="imprimir_copia_legalizada(\'' . $participante['id_emision_certificado_3'] . '\');"><i class="fa fa-copy"></i> COPIA LEGALIZADA</button></td>';
                    echo '</tr>';

                    if ($id_modalidad_curso == '2') {
                        echo '<tr>';
                        echo '<td>Certificado digital</td>';
                        echo '<td><button class="btn btn-info btn-xs btn-block" onclick="visualizar_certificado_digital(\'' . $participante['id_emision_certificado_3'] . '\');"><i class="fa fa-exchange"></i> CERTIFICADO DIGITAL</button></td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td>Envio fisico de certificado</td>';
                    echo '<td>' . $txt_estado_envio . ' <button class="btn btn-default btn-xs pull-right" onclick="proceso_envio_de_certificado(\'' . $participante['id_emision_certificado_3'] . '\');" data-toggle="modal" data-target="#MODAL-proceso_envio_de_certificado"><i class="fa fa-list" style="color:#8f8f8f;"></i></button></td>';
                    echo '</tr>';
                    if ($id_modalidad_curso == '2') {
                        ?>
                        <tr>
                            <td>
                                Envia el certificado digital en forma de PDF.
                            </td>
                            <td id="box-enviar_cert_digital-<?php echo $participante['id_emision_certificado_3']; ?>">
                                <b class="btn btn-default btn-xs btn-block" onclick="enviar_cert_digital('<?php echo $participante['id_emision_certificado_3']; ?>');">
                                    <i class="fa fa-send"></i> &nbsp; ENVIAR CERT. DIGITAL
                                </b>
                            </td>
                        </tr>
                        <?php
                    }
                    
                } else {
                    echo '<tr>';
                    echo '<td colspan="2">No tiene tercer certificado</td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <?php
        }
        ?>
    </div>
    <?php
}
?>
 
<!--enviar_cert_digital-->
<script>
    function enviar_cert_digital(id_emision_certificado) {
        if(confirm('Desea enviar el certificado por correo ?')){
            $("#box-enviar_cert_digital-"+id_emision_certificado).html('Procesando...');
            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.enviar_cert_digital.php',
                data: {id_emision_certificado: id_emision_certificado},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#box-enviar_cert_digital-"+id_emision_certificado).html(data);
                }
            });
        }
    }
</script>
    
    
<script>
    function emitir_certificados_adicionales() {
        if(confirm('Desea emitir todos los certificados adicionales ?')){
            $("#AJAXCONTENT-emitir_certificados_adicionales").html('Procesando...');
            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.emitir_certificados_adicionales.php',
                data: {id_participante: '<?php echo $id_participante; ?>'},
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $("#AJAXCONTENT-emitir_certificados_adicionales").html(data);
                }
            });
        }
    }
</script>

<!-- ajax imprimir_certificados_adicionales -->
<script>
    function imprimir_certificados_adicionales(ids_emisiones) {
        window.open('http://cursos.bo/contenido/librerias/fpdf/tutorial/certificado-3-masivo.php?ids_emisiones=' + ids_emisiones, 'popup', 'width=700,height=500');
    }
</script>