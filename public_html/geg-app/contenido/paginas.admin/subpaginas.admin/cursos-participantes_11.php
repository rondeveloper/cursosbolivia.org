<?php
$mensaje = "";

/* id de curso */
$id_curso = 0;
if (isset($get[2])) {
    $id_curso = (int) $get[2];
}

/* id de turno */
$id_turno_curso = 0;
$qr_turno = '';
if (isset($get[3])) {
    $id_turno_curso = (int) $get[3];
    $qr_turno = " AND id_turno='$id_turno_curso' ";
}

/* sw de habilitacion de procesos */
$rqvhc1 = query("SELECT estado FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqvhc2 = mysql_fetch_array($rqvhc1);
if ($rqvhc2['estado'] == '1' || $rqvhc2['estado'] == '2') {
    $sw_habilitacion_procesos = true;
} else {
    $sw_habilitacion_procesos = false;
}

$resultado1 = query("SELECT * FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' $qr_busqueda $qr_turno ORDER BY id DESC ");
/* res aux numeracion */
$resultado_aux_numeracion1 = query("SELECT numeracion FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' $qr_busqueda $qr_turno ORDER BY numeracion DESC LIMIT 1 ");
$resultado_aux_numeracion2 = mysql_fetch_array($resultado_aux_numeracion1);
$numeracion_por_participantes = $resultado_aux_numeracion2['numeracion'];

/* datos del curso */
$rqc1 = query("SELECT titulo,titulo_identificador,fecha,imagen,costo,costo2,costo3,costo_e,costo_e2,id_certificado,id_certificado_2,(select codigo from cursos_certificados where id_curso=c.id order by id asc limit 1 )codigo_certificado,inicio_numeracion FROM cursos c WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$rqc2 = mysql_fetch_array($rqc1);
$nombre_del_curso = $rqc2['titulo'];
$inicio_numeracion = $rqc2['inicio_numeracion'];
$titulo_identificador_del_curso = $rqc2['titulo_identificador'];
$fecha_del_curso = $rqc2['fecha'];
$codigo_de_certificado_del_curso = $rqc2['codigo_certificado'];
$id_certificado_curso = $rqc2['id_certificado'];
$id_certificado_2_curso = $rqc2['id_certificado_2'];
if ($rqc2['imagen'] !== '') {
    $url_imagen_del_curso = "https://www.infosicoes.com/paginas/" . $rqc2['imagen'] . ".size=4.img";
} else {
    $url_imagen_del_curso = "https://www.infosicoes.com/images/banner-cursos.png.size=4.img";
}
$costo_curso = $rqc2['costo'];
$costo2_curso = $rqc2['costo2'];
$costo3_curso = $rqc2['costo3'];
$costoe_curso = $rqc2['costo_e'];
$costoe2_curso = $rqc2['costo_e2'];

if ($numeracion_por_participantes > $inicio_numeracion) {
    $inicio_numeracion = (int) $numeracion_por_participantes + 1;
}


$cnt = mysql_num_rows($resultado1);
?>
<style>
    .modal-header{
        background: #00789f;
    }
    .modal-title{
        color:#FFF;
    }
    .modal-footer .btn-default{
        background: #00789f;
        color: #FFF;
    }
</style>
<script>
    var VAR_modo_de_pago = 'todos';
</script>



<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>">Panel Principal</a></li>
            <li><a href="cursos-listar.adm">Cursos</a></li>
            <li class="active">Participantes</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <div class="hidden-sm">
                <?php
                if (acceso_cod('adm-cursos-estado')) {
                    ?>
                    <span id="box-desactivar-curso">
                        <?php
                        if ($rqvhc2['estado'] == '1') {
                            ?>
                            <i class="btn btn-xs btn-success">Activado</i>
                            <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'temporal');">Temporal</i>
                            <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'desactivado');">Desactivado</i>
                            <?php
                        } elseif ($rqvhc2['estado'] == '2') {
                            ?>
                            <i class="btn btn-xs btn-danger">Temporal</i>
                            <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'activado');">Activado</i>
                            <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'desactivado');">Desactivado</i>
                            <?php
                        } else {
                            ?>
                            <i class="btn btn-xs btn-default active">Desactivado</i>
                            <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'activado');">Activado</i>
                            <i class="btn btn-xs btn-default" onclick="cambiar_estado_curso('<?php echo $id_curso; ?>', 'temporal');">Temporal</i>
                            <?php
                        }
                        ?>
                        &nbsp;|&nbsp;
                    </span>
                    <?php
                }
                ?>
                <a href="<?php echo $titulo_identificador_del_curso; ?>.html" target="_blank" class="btn btn-sm btn-info active"><i class="fa fa-eye"></i> VISUALIZAR CURSO</a>
                &nbsp;|&nbsp;
                <a href="cursos-editar/<?php echo $id_curso; ?>.adm" class="btn btn-sm btn-info active"><i class="fa fa-edit"></i> EDITAR CURSO</a>
                &nbsp;|&nbsp;
                <a href="cursos-listar.adm" class="btn btn-sm btn-info active"><i class="fa fa-list"></i> LISTAR CURSOS</a>
            </div>
            <div class="hidden-md">
                <a href="<?php echo $titulo_identificador_del_curso; ?>.html" target="_blank" class="btn btn-xs btn-info active"><i class="fa fa-eye"></i> VISUALIZAR </a>
                &nbsp;|&nbsp;
                <a href="cursos-editar/<?php echo $id_curso; ?>.adm" class="btn btn-xs btn-info active"><i class="fa fa-edit"></i> EDITAR </a>
                &nbsp;|&nbsp;
                <a href="cursos-listar.adm" class="btn btn-xs btn-info active"><i class="fa fa-list"></i> LISTAR </a>
            </div>
        </div>
        <h4 class="page-header"> PARTICIPANTES DEL CURSO <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h4>
        <blockquote class="page-information hidden">
            <p>
                Listado de cursos de Cursos
            </p>
        </blockquote>

        <form action="" method="post" onsubmit="return busca_participantes(<?php echo $id_curso; ?>, <?php echo $id_turno_curso; ?>, document.getElementById('inputbuscador').value);">
            <div class="input-group col-sm-12">
                <span class="input-group-addon"><i class="fa fa-search"></i> &nbsp; Buscador: </span>
                <input type="text" name="input-buscador" value="" id="inputbuscador" class="form-control" placeholder="Nombres / Apellidos / Celular / Correo / Codigo de registro ..."/>
            </div>
        </form>
    </div>
</div>

<?php
echo $mensaje;
?>

<?php
/*
  echo "<h5>AUXILIAR WORKING</h5>";
  echo "<hr/>";
  $rqdr1 = query("SELECT * FROM cursos_proceso_registro WHERE 1 ");
  while($rqdr2 = mysql_fetch_array($rqdr1)){
  $modo_pago = '';
  if($rqdr2['metodo_de_pago']=='pago-en-oficina'){
  $modo_pago = 'oficina';
  }
  if($rqdr2['metodo_de_pago']=='deposito'){
  $modo_pago = 'transferencia';
  }
  if($rqdr2['metodo_de_pago']=='tarjeta'){
  $modo_pago = 'khipu';
  }
  if($rqdr2['metodo_de_pago']=='tigomoney'){
  $modo_pago = 'tigomoney';
  }
  echo $rqdr2['codigo']." id: ".$rqdr2['id']." MP: $modo_pago<br/>";
  $rqdppr1 = query("SELECT id,nombres FROM cursos_participantes WHERE id_proceso_registro='".$rqdr2['id']."' ");
  while($rqdppr2 = mysql_fetch_array($rqdppr1)){
  echo " &nbsp;&nbsp; ".$rqdppr2['nombres']." id: ".$rqdppr2['id']."<br/>";
  query("UPDATE cursos_participantes SET modo_pago='$modo_pago' WHERE id='".$rqdppr2['id']."' LIMIT 1 ");
  }
  }
  echo "<hr/>";
 */
?>

<div class="row">
    <div class="col-md-12NOT">
        <div class="panelNOT">

            <div class="row">
                <div class="col-md-6">
                    <h4>
                        <i class='btn btn-success active hidden-sm'><?php echo date("d  M  y", strtotime($fecha_del_curso)); ?></i> | 
                        <?php echo $nombre_del_curso; ?>

                    </h4>
                </div>
                <div class="col-md-6">
                    <div class="text-right" style="padding:5px 0px;">
                        <a onclick="VAR_modo_de_pago = 'todos';
                                lista_participantes(<?php echo $id_curso; ?>, 0);
                                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);" id="btnmodopago-todos" class="btnmodopago btn btn-xs active btn-success" style="font-size: 8pt;"><i class="fa fa-clock-o"></i> TODOS </a>
                        <a onclick="VAR_modo_de_pago = 'oficina';
                                lista_participantes(<?php echo $id_curso; ?>, 0);
                                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);" id="btnmodopago-oficina" class="btnmodopago btn btn-xs active btn-warning" style="font-size: 8pt;"><i class="fa fa-clock-o"></i> OFICINA</a>
                        <a onclick="VAR_modo_de_pago = 'transferencia';
                                lista_participantes(<?php echo $id_curso; ?>, 0);
                                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);" id="btnmodopago-transferencia" class="btnmodopago btn btn-xs active btn-warning" style="font-size: 8pt;"><i class="fa fa-clock-o"></i> TRANSFERENCIA</a>
                        <a onclick="VAR_modo_de_pago = 'tigomoney';
                                lista_participantes(<?php echo $id_curso; ?>, 0);
                                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);" id="btnmodopago-tigomoney" class="btnmodopago btn btn-xs active btn-warning" style="font-size: 8pt;"><i class="fa fa-clock-o"></i> TIGOMONEY</a>
                        <a onclick="VAR_modo_de_pago = 'khipu';
                                lista_participantes(<?php echo $id_curso; ?>, 0);
                                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);" id="btnmodopago-khipu" class="btnmodopago btn btn-xs active btn-warning" style="font-size: 8pt;"><i class="fa fa-clock-o"></i> KHIPU</a>
                        <a onclick="VAR_modo_de_pago = 'sinpago';
                                lista_participantes(<?php echo $id_curso; ?>, 0);
                                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);" id="btnmodopago-sinpago" class="btnmodopago btn btn-xs active btn-warning" style="font-size: 8pt;"><i class="fa fa-clock-o"></i> SIN PAGO</a>
                    </div>
                    <?php
                    $sw_turnos = false;
                    $rqtc1 = query("SELECT id,titulo FROM cursos_turnos WHERE id_curso='$id_curso' ");
                    if (mysql_num_rows($rqtc1) > 0) {
                        $aux_class_tc = "btn-success";
                        if ($id_turno_curso !== 0) {
                            $aux_class_tc = "btn-info";
                        }
                        ?>
                        <span class="pull-right">
                            <a onclick="lista_participantes(<?php echo $id_curso; ?>, 0);
                                    lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);" id="btnturno-0" class="btnturno btn btn-xs active <?php echo $aux_class_tc; ?>"><i class="fa fa-clock-o"></i> Todos los turnos</a>
                               <?php
                               $turno = array();
                               $turno[0] = 'Sin turno';
                               $sw_turnos = true;
                               while ($rqtc2 = mysql_fetch_array($rqtc1)) {
                                   $turno[$rqtc2['id']] = $rqtc2['titulo'];
                                   $aux_class_tc = "btn-info";
                                   if ($id_turno_curso == $rqtc2['id']) {
                                       $aux_class_tc = "btn-success";
                                   }
                                   ?>
                                <a onclick="lista_participantes(<?php echo $id_curso; ?>,<?php echo $rqtc2['id']; ?>);
                                        lista_participantes_eliminados(<?php echo $id_curso; ?>, <?php echo $rqtc2['id']; ?>);" id="btnturno-<?php echo $rqtc2['id']; ?>" class="btnturno btn btn-xs active <?php echo $aux_class_tc; ?>"><i class="fa fa-clock-o"></i> <?php echo $rqtc2['titulo']; ?></a>
                                   <?php
                               }
                               ?>
                            &nbsp;&nbsp;|&nbsp;&nbsp;
                        </span>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <?php
            if ($sw_habilitacion_procesos) {
                ?>

                <div class="panel-bodyNOT" style="padding-bottom: 0px;padding-top: 0px;" id="div-add-participante">
                    <form id="form-agrega-participante" name="form_add_participante">
                        <div class="panel-body" style="background: #f1f1f1;border: 1px solid #3498db;border-radius: 5px;padding-bottom: 0px;margin-bottom: 7px;">
                            <input type='hidden' name='id_curso' value='<?php echo $id_curso; ?>'/>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="col-md-8" style="padding:0px;">
                                        <input type="text" name="ci" class="form-control" placeholder="C.I." id="f-ci" autocomplete="off" onkeyup="checkParticipante(this.value);"/>
                                    </div>
                                    <div class="col-md-4" style="padding:0px;">
                                        <select class="form-control" name="ci_expedido" id="f-exp">
                                            <option value="">...</option>
                                            <option value="LP">LP</option>
                                            <option value="CB">CBBA</option>
                                            <option value="SC">SC</option>
                                            <option value="OR">ORURO</option>
                                            <option value="PS">POTOSI</option>
                                            <option value="CH">CHUQUISACA</option>
                                            <option value="PD">PANDO</option>
                                            <option value="BN">BENI</option>
                                            <option value="TJ">TARIJA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1 text-right"><input type="text" name="prefijo" id="f-pref" class="form-control" placeholder="Sr. / Dr. / Arq. / Ing. " /></div>
                                <div class="col-md-3"><input type="text" name="nombres" class="form-control" placeholder="Nombres" id="f-nom" required="" autocomplete="off" onkeyup="this.value = this.value.toUpperCase()"/></div>
                                <div class="col-md-3"><input type="text" name="apellidos" class="form-control" placeholder="Apellidos" id="f-ape" required="" autocomplete="off" onkeyup="this.value = this.value.toUpperCase()"/></div>

                                <div class="col-md-2 hidden-sm"><b class="btn btn-success active btn-block" onclick="agrega_participante();"><i class="fa fa-plus"></i> Add participante</b></div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 text-right">
                                    <?php
                                    $costo_curso;
                                    $costo2_curso = $rqc2['costo2'];
                                    $costo3_curso = $rqc2['costo3'];
                                    $costoe_curso = $rqc2['costo_e'];
                                    $costoe2_curso = $rqc2['costo_e2'];
                                    ?>                                    
                                    <select name="monto_pago" class="form-control">
                                        <option value="<?php echo $costo_curso; ?>"><?php echo $costo_curso; ?></option>
                                        <?php
                                        if ($costo2_curso > 0) {
                                            ?>
                                            <option value="<?php echo $costo2_curso; ?>"><?php echo $costo2_curso; ?></option>
                                            <?php
                                        }
                                        if ($costo3_curso > 0) {
                                            ?>
                                            <option value="<?php echo $costo3_curso; ?>"><?php echo $costo3_curso; ?></option>
                                            <?php
                                        }
                                        if ($costoe_curso > 0) {
                                            ?>
                                            <option value="<?php echo $costoe_curso; ?>"><?php echo $costoe_curso; ?></option>
                                            <?php
                                        }
                                        if ($costoe2_curso > 0) {
                                            ?>
                                            <option value="<?php echo $costoe2_curso; ?>"><?php echo $costoe2_curso; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
    <!--                                    <input type="number" name="monto_pago" class="form-control" placeholder="Monto..." required="" value="<?php echo $costo_curso; ?>"/>-->
                                </div>
                                <div class="col-md-3"><input type="text" name="razon_social" class="form-control" placeholder="Razon Social" id="f-raz" autocomplete="off" onkeyup="this.value = this.value.toUpperCase()"/></div>
                                <div class="col-md-3"><input type="text" name="nit" class="form-control" placeholder="NIT" id="f-nit" autocomplete="off" onkeyup="this.value = this.value.toUpperCase()"/></div>
                                <div class="col-md-3"><input type="text" name="celular" class="form-control" placeholder="Celular" autocomplete="off"/></div>
                                <div class="col-md-2 text-right">
                                    <b class="btn btn-default btn-xs" title="Agregar participantes mediante CSV" data-toggle="modal" data-target="#MODAL-agregar_participantes_csv"><i class="fa fa-upload"></i></b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 text-right">
                                    <input type="text" name="numeracion" class="form-control" id="numeracion-nuevo-participante" value="<?php echo $inicio_numeracion; ?>" />
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="correo" id="f-email" class="form-control" placeholder="Correo electr&oacute;nico..." autocomplete="off" onkeyup="this.value = this.value.toLowerCase()"/>
                                </div>
                                <div class="col-md-3">
                                    <select name="modo_pago" class="form-control">
                                        <?php
                                        if (date("Y-m-d") > $fecha_del_curso) {
                                            ?>
                                            <option value="fuera_de_fecha">Registro fuera fecha</option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="dia_curso">Pago dia del curso</option>
                                            <option value="oficina">Pago en oficina</option>
                                            <option value="deposito">Deposito bancario</option>
                                            <option value="transferencia">Transferencia bancaria</option>
                                            <option value="tigomoney">Tigo Money</option>
                                            <option value="khipu">Pago con tarjeta</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="observaciones" class="form-control" placeholder="Observaciones..." />
                                </div>
                                <div class="col-md-2">
                                    <b onclick="nombreFact(1);" class="btn btn-xs btn-primary">NF1</b>
                                    &nbsp;
                                    <b onclick="nombreFact(2);" class="btn btn-xs btn-primary">NF2</b>
                                    &nbsp;
                                    <b onclick="nombreFact(3);" class="btn btn-xs btn-primary">NF3</b>
                                    &nbsp;
                                    <b onclick="nitFact(3);" class="btn btn-xs btn-primary">CiNit</b>
                                    &nbsp;&nbsp;
                                    <label for="impficha">
                                        <input type="checkbox" name="impficha" value="1" id="impficha"/> FICHA
                                    </label>
                                </div>
                                <div class="hidden-md">
                                    <br/>
                                    <b class="btn btn-success active btn-block" onclick="agrega_participante();"><i class="fa fa-plus"></i> Add participante</b>
                                    <br/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- DIV CONTENT AJAX :: AGREGA PARTICIPANTE -->
                <div id="ajaxloading-agrega_participante"></div>
                <div id="ajaxbox-agrega_participante">
                    <!-- .... -->
                </div>


                <script>
                    function nombreFact(dat) {
                        var nom = document.getElementById("f-nom").value;
                        var ape = document.getElementById("f-ape").value;
                        var nfact = "";

                        if (dat === 3) {
                            nfact = nom + " " + ape;
                        } else if (dat === 2) {
                            var araux1 = nom.split(' ');
                            var araux2 = ape.split(' ');
                            nfact = araux1[0] + " " + araux2[0];
                        } else {
                            var araux2 = ape.split(' ');
                            nfact = araux2[0];
                        }

                        document.getElementById("f-raz").value = nfact.toUpperCase();
                    }
                    function nitFact() {
                        var ci = document.getElementById("f-ci").value;
                        document.getElementById("f-nit").value = ci;
                    }
                </script>
                <script>
                    function nombreFact2(dat) {

                        var nom = document.getElementById("f-nom-p").value;
                        var ape = document.getElementById("f-ape-p").value;
                        var nfact = "";

                        if (dat === 3) {
                            nfact = nom + " " + ape;
                        } else if (dat === 2) {
                            var araux1 = nom.split(' ');
                            var araux2 = ape.split(' ');
                            nfact = araux1[0] + " " + araux2[0];
                        } else {
                            var araux2 = ape.split(' ');
                            nfact = araux2[0];
                        }

                        document.getElementById("f-raz-p").value = nfact.toUpperCase();
                    }
                    function nitFact2() {
                        var ci = document.getElementById("f-ci-p").value;
                        document.getElementById("f-nit-p").value = ci;
                    }
                </script>

                <?php
            }
            ?>



            <?php
            if (mysql_num_rows($resultado1) == 0) {
                echo "<p>No se registraron participantes para este curso</p>";
            }
            ?>

            <div class="panel-bodyNOT" style="padding-top: 0px;">


                <!-- DIV CONTENT AJAX :: LISTADO DE PARTICIPANTES -->
                <div id="ajaxloading-lista_participantes"></div>
                <div id="ajaxbox-lista_participantes">
                    ....
                </div>


                <!-- DIV CONTENT AJAX :: LISTADO DE PARTICIPANTES ELIMINADOS -->
                <div id="ajaxloading-lista_participantes_eliminados"></div>
                <div id="ajaxbox-lista_participantes_eliminados">
                    ....
                </div>


            </div>
        </div>
    </div>
</div>

<script>
    function show_v_objeto(objeto, id) {
        $.ajax({
            url: 'contenido/cursos.admin/ajax/ajax.show_v_objeto.php',
            data: {id: id, objeto: objeto},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#show-v-div-" + objeto + "-" + id).html(data);
            }
        });
    }
</script>

<!-- ajax de emision de certificados -->





<!-- ajax habilitacion de participante -->
<script>
    function habilitar_participante(dat) {

        if (confirm("Desea habilitar nuevamente al participante?")) {

            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.instant.curso_habilitar_participante.php',
                data: {dat: dat},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    //$("#ajaxbox-tr-participante-" + dat).html(data);
                    alert('Participante-habilitado');
                    location.href = 'cursos-participantes/<?php echo $id_curso; ?>.adm';
                }
            });

        }

    }
</script>


<!-- ajax imprimir certificados multiple -->
<script>
    function imprimir_certificados_multiple(dat) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.aux.cursos-participantes.imprimir_certificados_multiple.php',
            data: {id_curso: <?php echo $id_curso; ?>, nro_certificado: dat, ids: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                window.open(data, 'popup', 'width=700,height=500');
                setTimeout(function() {
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                }, 5000);
            }
        });
    }
</script>

<!-- ajax imprimir certificado individual -->
<script>
    function imprimir_certificado_individual(dat) {

        if (dat > 0) {
            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.aux.cursos-participantes.imprimir_certificado_individual.php',
                data: {dat: dat},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    window.open(data, 'popup', 'width=700,height=500');
                    setTimeout(function() {
                        lista_participantes(<?php echo $id_curso; ?>, 0);
                    }, 2000);
                }
            });
        } else {
            alert('Error en el ID de certificado');
        }
    }
</script>

<!-- ajax imprimir dos_certificados -->
<script>
    function imprimir_dos_certificados(dat) {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.aux.cursos-participantes.imprimir_dos_certificados.php',
            data: {id_curso: <?php echo $id_curso; ?>, ids: dat},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                window.open(data, 'popup', 'width=700,height=500');
                setTimeout(function() {
                    lista_participantes(<?php echo $id_curso; ?>, 0);
                }, 5000);
            }
        });
    }
</script>

<!-- ajax imprimir copia legalizada -->
<script>
    function imprimir_copia_legalizada(dat) {

        if (dat > 0) {
            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.aux.cursos-participantes.imprimir_copia_legalizada.php',
                data: {dat: dat},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    window.open(data, 'popup', 'width=700,height=500');
                    /*
                     setTimeout(function() {
                     lista_participantes(<?php //echo $id_curso;          ?>, 0);
                     }, 2000);
                     */
                }
            });
        } else {
            alert('Error en el ID de certificado');
        }
    }
</script>

<!-- ajax emision certificados masivamente -->
<script>
    function emision_multiple_certificados() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_certificados.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-multiple").html(data);
                //lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!-- ajax emision certificados masivamente Certificado 2 -->
<script>
    function emision_multiple_c2_certificados() {
        var ids_to_send;
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();

        var aux_idsalmacenador_2 = '0';
        var arraux1 = aux_idsalmacenador.split(',');
        for (var index = 0; index < arraux1.length; ++index) {
            if (array_check_participante[arraux1[index]] !== undefined) {
                //console.log(arraux1[index]);
                aux_idsalmacenador_2 = aux_idsalmacenador_2 + ',' + arraux1[index];
            }
        }
        if (aux_idsalmacenador_2 === '0') {
            ids_to_send = ids.join(',');
        } else {
            ids_to_send = aux_idsalmacenador_2;
        }

        //alert(ids_to_send);
        if (true) {

            $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
            $.ajax({
                url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_c2_certificados.php',
                data: {dat: ids_to_send},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
                    $("#box-modal_emision_certificados-multiple").html(data);
                    //lista_participantes(<?php echo $id_curso; ?>, 0);
                }
            });

        }
    }
</script>

<!-- ajax emision certificados a eleccion -->
<script>
    function emision_certificados_a_eleccion() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-a-eleccion").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion p2 -->
<script>
    function emision_certificados_a_eleccion_p2() {
        var ids;
        var id_modelo_certificado;
        id_modelo_certificado = $("#id-modelo-certificado").val();
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion_p2.php',
            data: {dat: ids.join(','), id_modelo_certificado: id_modelo_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-a-eleccion").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion p3 -->
<script>
    function emision_certificados_a_eleccion_p3() {
        var ids;
        var id_modelo_certificado;
        id_modelo_certificado = $("#id-modelo-certificado").val();
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_certificados_a_eleccion_p3.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso, id_modelo_certificado: id_modelo_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-a-eleccion").html(data);
            }
        });
    }
</script>



<!-- ajax emision certificados a eleccion -->
<script>
    function imprime_certificados_a_eleccion() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_impresion_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.impresion_certificados_a_eleccion.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_impresion_certificados-a-eleccion").html(data);
            }
        });
    }
</script>

<!-- ajax emision certificados a eleccion p2 -->
<script>
    function imprime_certificados_a_eleccion_p2() {
        var ids;
        var id_modelo_certificado;
        id_modelo_certificado = $("#id-modelo-certificado-imp").val();
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_impresion_certificados-a-eleccion").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.impresion_certificados_a_eleccion_p2.php',
            data: {dat: ids.join(','), id_modelo_certificado: id_modelo_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_impresion_certificados-a-eleccion").html(data);
            }
        });
    }
</script>




<!-- ajax emision certificados masivamente p2 -->
<script>
    function emision_multiple_certificados_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_certificados_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-multiple").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!-- ajax emision certificados masivamente p2 Certificado 2 -->
<script>
    function emision_multiple_c2_certificados_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_2_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_c2_certificados_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_certificados-multiple").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>




<!-- ajax imprimir facturas masivamente -->
<script>
    function imprimir_facturas() {

        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();

        //alert('IDS: ' + ids.join(','));

        window.open('http://www.infosicoes.com/contenido/librerias/fpdf/tutorial/factura-1-masivo.php?id_participantes=' + ids.join(','), 'popup', 'width=700,height=500');


    }
</script>

<!-- ajax emision facturas masivamente -->
<script>
    function emision_multiple_facturas() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        $("#box-modal_emision_facturas-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_facturas.php',
            data: {dat: ids.join(',')},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_facturas-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax emision facturas masivamente p2 -->
<script>
    function emision_multiple_facturas_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_certificado = '<?php echo $id_certificado_curso; ?>';
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_facturas-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_facturas_p2.php',
            data: {dat: ids.join(','), id_certificado: id_certificado, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_facturas-multiple").html(data);
            }
        });
    }
</script>

<!-- ajax procesar_certificados_secundarios -->
<script>
    function procesar_certificados_secundarios(id_participante) {
        var id_curso = '<?php echo $id_curso; ?>';
        $("#BOX-AJAX-certificados-secundarios").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.procesar_certificados_secundarios.php',
            data: {id_participante: id_participante, id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#BOX-AJAX-certificados-secundarios").html(data);
            }
        });
    }
</script>

<!-- ajax procesar_certificados_secundarios p2 -->
<script>
    function procesar_certificados_secundarios_p2() {
        var arraydata = $("#formajax1").serialize();
        $("#BOX-AJAX-certificados-secundarios").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.procesar_certificados_secundarios_p2.php',
            data: arraydata,
            type: 'POST',
            success: function(data) {
                $("#BOX-AJAX-certificados-secundarios").html(data);
            }
        });
    }
</script>

<!-- ajax deshabilita participantes no seleccionados p1 -->
<script>
    function deshabilita_participantes_no_seleccionados() {

        var aux_idsalmacenador_2 = '0';

        var arraux1 = aux_idsalmacenador.split(',');
        for (var index = 0; index < arraux1.length; ++index) {
            if (array_check_participante[arraux1[index]] === undefined) {
                //console.log(arraux1[index]);
                aux_idsalmacenador_2 = aux_idsalmacenador_2 + ',' + arraux1[index];
            }
        }
        //alert(aux_idsalmacenador_2);

        $("#ajaxloading-deshabilita_participantes_no_seleccionados").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.deshabilita_participantes_no_seleccionados_p1.php',
            data: {dat: aux_idsalmacenador_2},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-deshabilita_participantes_no_seleccionados").html('');
                $("#ajaxbox-deshabilita_participantes_no_seleccionados").html(data);
            }
        });
    }
</script>

<!-- ajax deshabilita participantes no seleccionados p2 -->
<script>
    function deshabilita_participantes_no_seleccionados_p2() {
        var aux_idsalmacenador_2 = '0';
        var arraux1 = aux_idsalmacenador.split(',');
        for (var index = 0; index < arraux1.length; ++index) {
            if (array_check_participante[arraux1[index]] === undefined) {
                aux_idsalmacenador_2 = aux_idsalmacenador_2 + ',' + arraux1[index];
            }
        }
        $("#ajaxloading-deshabilita_participantes_no_seleccionados_p2").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.deshabilita_participantes_no_seleccionados_p2.php',
            data: {dat: aux_idsalmacenador_2},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-deshabilita_participantes_no_seleccionados_p2").html('');
                $("#ajaxbox-deshabilita_participantes_no_seleccionados_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!-- ajax emision cupones descuento -->
<script>
    function emision_cupones_descuento() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_cupones_descuento.php',
            data: {dat: ids.join(','), id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_cupones-descuento").html(data);
                //lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!-- ajax emision cupones descuento2 -->
<script>
    function emision_cupones_descuento_p2() {
        var ids;
        ids = $('input[type=checkbox]:checked').map(function() {
            return $(this).attr('id');
        }).get();
        var id_curso = '<?php echo $id_curso; ?>';
        $("#box-modal_emision_certificados-multiple").html('<img src="contenido/imagenes/images/load_ajax.gif"/>');
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.modal.cursos-participantes.emision_multiple_cupones_descuento_p2.php',
            data: {dat: ids.join(','), id_curso: id_curso},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_emision_cupones-descuento").html(data);
                //lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<style>
    .fila_seleccionada{
        background: #dadada;
    }
    .fila_seleccionada:hover td{
        background: #dadada !important;
    }
</style>
<script>
    function check_participante(dat) {
        if (array_check_participante[dat] === undefined) {
            //$("#ajaxbox-tr-participante-" + dat).css("background", "#dadada");
            $("#ajaxbox-tr-participante-" + dat).addClass("fila_seleccionada");
            array_check_participante[dat] = true;
        } else {
            array_check_participante[dat] = undefined;
            //alert(array_check_participante[dat]);
            //$("#ajaxbox-tr-participante-" + dat).css("background", "#ffffff");
            $("#ajaxbox-tr-participante-" + dat).removeClass("fila_seleccionada");
        }
    }

    function procesa_checked_participantes() {
        alert(JSON.stringify(array_check_participante));
    }

    function deshabilita_participantes_no_seleccionados_cero() {
        //alert("YEY2 -> "+aux_idsalmacenador;
        var aux_idsalmacenador_2 = '0';

        var arraux1 = aux_idsalmacenador.split(',');
        for (var index = 0; index < arraux1.length; ++index) {
            if (array_check_participante[arraux1[index]] === undefined) {
                //console.log(arraux1[index]);
                aux_idsalmacenador_2 = aux_idsalmacenador_2 + ',' + arraux1[index];
            }
        }
        alert(aux_idsalmacenador_2);
    }
</script>


<!-- MODALS -->


<!-- Modal deshabilita de participantes no seleccionados -->
<div id="MODAL-deshabilita_participantes_no_seleccionados" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DESHABILITACION DE PARTICIPANTES</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: DESHABILITACION DE PARTICIPANTES P1 -->
                <div id="ajaxloading-deshabilita_participantes_no_seleccionados"></div>
                <div id="ajaxbox-deshabilita_participantes_no_seleccionados">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>
<!-- END Modal deshabilita de participantes no seleccionados -->

<!-- Modal edicion de participante -->
<div id="MODAL-edicion-participante" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EDICI&Oacute;N DE PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: EDCICION DE PARTICIPANTE P1 -->
                <div id="ajaxloading-edita_participante_p1"></div>
                <div id="ajaxbox-edita_participante_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>
<!-- END Modal edicion de participante -->


<!-- Modal emision de Certificado -->
<div id="MODAL-emite-certificado" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION DE CERTIFICADO</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: EMITE CERTIFICADO P1 -->
                <div id="ajaxloading-emite_certificado_p1"></div>
                <div id="ajaxbox-emite_certificado_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal emision de Certificado -->


<!-- Modal Datos de registro -->
<div id="MODAL-datos-registro" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DATOS DE REGISTRO</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: DATOS DE REGISTRO -->
                <div id="ajaxloading-datos_registro"></div>
                <div id="ajaxbox-datos_registro">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Datos de registro -->


<!-- Modal Facturacion -->
<div id="MODAL-emite-factura" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DATOS DE FACTURACION</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: EMITE FACTURA P1 -->
                <div id="ajaxloading-emite_factura_p1"></div>
                <div id="ajaxbox-emite_factura_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Facturacion -->

<!-- Modal Elimina participante -->
<div id="MODAL-elimina-participante" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content panel-danger">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ELIMINACION DE PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: ELIMINA PARTICIPANTE P1 -->
                <div id="ajaxloading-elimina_participante_p1"></div>
                <div id="ajaxbox-elimina_participante_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Elimina participante -->


<!-- Modal Habilita participante -->
<div id="MODAL-habilita-participante" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">HABILITACION DE PARTICIPANTE</h4>
            </div>
            <div class="modal-body">

                <!-- DIV CONTENT AJAX :: HABILITACION PARTICIPANTE P1 -->
                <div id="ajaxloading-habilita_participante_p1"></div>
                <div id="ajaxbox-habilita_participante_p1">
                    ....
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal Elimina participante -->



<!-- Modal emitir certificados - multiple -->
<div id="MODAL-emite-certificados-multiple" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION MULTIPLE DE CERTIFICADOS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <h5 class="text-center">
                    Emisi&oacute;n de certificados para
                </h5>
                <div class="text-center" id='box-modal_emision_certificados-multiple'>
                    <!-- ajax content -->
                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal emitir certificados - multiple -->


<!-- Modal emitir cupones descuento -->
<div id="MODAL-emite-cupones-descuento" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION DE CUPONES DESCUENTO</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <h5 class="text-center">
                    Emisi&oacute;n de cupones para
                </h5>
                <div class="text-center" id='box-modal_emision_cupones-descuento'>
                    <!-- ajax content -->
                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal emitir cupones descuento -->

<!-- Modal emitir certificados a eleccion -->
<div id="MODAL-emite-certificados-a-eleccion" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION DE CERTIFICADOS A ELECCION</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="text-center" id='box-modal_emision_certificados-a-eleccion'>

                    <!-- ajax content -->

                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal emitir certificados a eleccion -->

<!-- Modal imprime certificados a eleccion -->
<div id="MODAL-imprime-certificados-a-eleccion" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">IMRPESION DE CERTIFICADOS A ELECCION</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="text-center" id='box-modal_impresion_certificados-a-eleccion'>

                    <!-- ajax content -->

                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal imprime certificados a eleccion -->

<!-- Modal emitir facturas - multiple -->
<div id="MODAL-emite-facturas-multiple" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION MULTIPLE DE FACTURAS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <h5 class="text-center">
                    Emisi&oacute;n de facturas para
                </h5>
                <div class="text-center" id='box-modal_emision_facturas-multiple'>
                    <!-- ajax content -->
                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal emitir facturas - multiple -->


<!-- Modal-certificados-secundarios -->
<div id="MODAL-certificados-secundarios" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">EMISION DE CERTIFICADOS SECUNDARIOS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                        <br/>
                    </div>
                    <div class="col-md-6 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div id="BOX-AJAX-certificados-secundarios">
                        <!-- ajax content -->

                    </div>
                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-certificados-secundarios -->

<!-- Modal-generar reporte -->
<div id="MODAL-generar-reporte" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">GENERAR REPORTE</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 text-left">
                        <b>CURSO:</b> &nbsp; <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> &nbsp; <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-4 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:100%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-12 text-left">
                        <p>Selecciona los campos necesarios para el reporte.</p>

                        <table class="table table-striped">
                            <tr>
                                <td>Prefijo</td>
                                <td class="text-center">
                                    <label for="f0a"><input name="data_report_prefijo" type="radio" value="1" id="f0a"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f0b"><input name="data_report_prefijo" type="radio" value="0" id="f0b" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Nombres</td>
                                <td class="text-center">
                                    <label for="f1"><input name="data_report_nombres" type="radio" value="1" id="f1" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f2"><input name="data_report_nombres" type="radio" value="0" id="f2"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Apellidos</td>
                                <td class="text-center">
                                    <label for="f3"><input name="data_report_apellidos" type="radio" value="1" id="f3" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f4"><input name="data_report_apellidos" type="radio" value="0" id="f4"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Datos de Facturaci&oacute;n</td>
                                <td class="text-center">
                                    <label for="f5"><input name="data_report_datosfacturacion" type="radio" value="1" id="f5" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f6"><input name="data_report_datosfacturacion" type="radio" value="0" id="f6"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Monto de pago</td>
                                <td class="text-center">
                                    <label for="f-mp5"><input name="data_report_montopago" type="radio" value="1" id="f-mp5"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f-mp6"><input name="data_report_montopago" type="radio" value="0" id="f-mp6" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Datos de Contacto</td>
                                <td class="text-center">
                                    <label for="f7"><input name="data_report_datoscontacto" type="radio" value="1" id="f7" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f8"><input name="data_report_datoscontacto" type="radio" value="0" id="f8"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Modo de registro</td>
                                <td class="text-center">
                                    <label for="f7a"><input name="data_report_modoregistro" type="radio" value="1" id="f7a"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f8a"><input name="data_report_modoregistro" type="radio" value="0" id="f8a" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Fecha de registro</td>
                                <td class="text-center">
                                    <label for="f7b"><input name="data_report_fecharegistro" type="radio" value="1" id="f7b"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f8b"><input name="data_report_fecharegistro" type="radio" value="0" id="f8b" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Firma</td>
                                <td class="text-center">
                                    <label for="f9"><input name="data_report_firma" type="radio" value="1" id="f9" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f10"><input name="data_report_firma" type="radio" value="0" id="f10"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Participantes eliminados</td>
                                <td class="text-center">
                                    <label for="f9a"><input name="data_report_eliminados" type="radio" value="1" id="f9a"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f10a"><input name="data_report_eliminados" type="radio" value="0" id="f10a" checked="checked"/> No Incluido</label>
                                </td>
                            </tr>
                            <tr>
                                <td>Numeraci&oacute;n de certificado</td>
                                <td class="text-center">
                                    <label for="f11"><input name="data_numeracion_certificado" type="radio" value="1" id="f11" checked="checked"/> Incluido</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label for="f12"><input name="data_numeracion_certificado" type="radio" value="0" id="f12"/> No Incluido</label>
                                </td>
                            </tr>
                        </table>

                        <hr/>

                        <input type="hidden" id="idturno" value="0"/>

                        <div class="panel-footer text-center">
                            <button class="btn btn-default active" onclick="generar_reporte('impresion');">IMPRIMIR</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="btn btn-success active" onclick="generar_reporte('excel');">EXCEL</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <button class="btn btn-info active" onclick="generar_reporte('word');">WORD</button>
                        </div>

                    </div>
                </div>
                <hr/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal-generar reporte -->


<!-- Modal agregar participantes CSV --> 
<div id="MODAL-agregar_participantes_csv" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">AGREGADO DE PARTIPANTES MEDIANTE CSV (Excel)</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 text-left">
                        <b>CURSO:</b> <?php echo $nombre_del_curso; ?>
                        <br/>
                        <b>FECHA:</b> <?php echo $fecha_del_curso; ?>
                    </div>
                    <div class="col-md-5 text-right">
                        <img src="<?php echo $url_imagen_del_curso; ?>" style="width:50%;border:1px solid #DDD;padding:1px;">
                    </div>
                </div>
                <hr/>
                <p>Para hacer el cargado de participantes via hoja excel, se debe descargar la siguiente plantilla e ir agregando en ella los participantes correspondinetes.</p>
                <h5 class="text-center">
                    <a href="contenido/archivos/cursos/plantilla-nuevos-participantes.csv" style="text-decoration:underline;"><b>Descarga de plantilla CSV <i class="fa fa-download"></i></b></a>
                </h5>
                <hr/>
                <div class="text-center" id='BOX-agregar_participantes_csv'>
                    <!-- ajax content -->
                    <p>El archivo con los participantes agregados mediante la plantilla debe ser guardado en formato <br/><b>CSV (delimitado por comas) (*.csv)</b> para evitar problemas de compatibilidad.</p>
                    <form id="FORM-agregar_participantes_csv" name="FORM-agregar_participantes_csv" method="post" enctype="multipart/form-data">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <td><b>Plantilla:</b></td>
                                <td><input type="file" class="form-control" name="plantilla"/></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>"/>
                                    <b class="btn btn-success btn-block" onclick="agregar_participantes_csv();">SUBIR PARTICIPANTES</b>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <hr/>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal agregar participantes CSV -->

<script>
    function generar_reporte(formato) {

        var data_report_prefijo = "0";
        var array_data_report_prefijo = document.getElementsByName("data_report_prefijo");
        for (var i = 0; i < array_data_report_prefijo.length; i++) {
            if (array_data_report_prefijo[i].checked)
                data_report_prefijo = array_data_report_prefijo[i].value;
        }

        var data_report_nombres = "0";
        var array_data_report_nombres = document.getElementsByName("data_report_nombres");
        for (var i = 0; i < array_data_report_nombres.length; i++) {
            if (array_data_report_nombres[i].checked)
                data_report_nombres = array_data_report_nombres[i].value;
        }

        var data_report_apellidos = "0";
        var array_data_report_apellidos = document.getElementsByName("data_report_apellidos");
        for (var i = 0; i < array_data_report_apellidos.length; i++) {
            if (array_data_report_apellidos[i].checked)
                data_report_apellidos = array_data_report_apellidos[i].value;
        }

        var data_report_datosfacturacion = "0";
        var array_data_report_datosfacturacion = document.getElementsByName("data_report_datosfacturacion");
        for (var i = 0; i < array_data_report_datosfacturacion.length; i++) {
            if (array_data_report_datosfacturacion[i].checked)
                data_report_datosfacturacion = array_data_report_datosfacturacion[i].value;
        }

        var data_report_datoscontacto = "0";
        var array_data_report_datoscontacto = document.getElementsByName("data_report_datoscontacto");
        for (var i = 0; i < array_data_report_datoscontacto.length; i++) {
            if (array_data_report_datoscontacto[i].checked)
                data_report_datoscontacto = array_data_report_datoscontacto[i].value;
        }

        var data_report_modoregistro = "0";
        var array_data_report_modoregistro = document.getElementsByName("data_report_modoregistro");
        for (var i = 0; i < array_data_report_modoregistro.length; i++) {
            if (array_data_report_modoregistro[i].checked)
                data_report_modoregistro = array_data_report_modoregistro[i].value;
        }

        var data_report_montopago = "0";
        var array_data_report_montopago = document.getElementsByName("data_report_montopago");
        for (var i = 0; i < array_data_report_montopago.length; i++) {
            if (array_data_report_montopago[i].checked)
                data_report_montopago = array_data_report_montopago[i].value;
        }

        var data_report_fecharegistro = "0";
        var array_data_report_fecharegistro = document.getElementsByName("data_report_fecharegistro");
        for (var i = 0; i < array_data_report_fecharegistro.length; i++) {
            if (array_data_report_fecharegistro[i].checked)
                data_report_fecharegistro = array_data_report_fecharegistro[i].value;
        }

        var data_report_firma = "0";
        var array_data_report_firma = document.getElementsByName("data_report_firma");
        for (var i = 0; i < array_data_report_firma.length; i++) {
            if (array_data_report_firma[i].checked)
                data_report_firma = array_data_report_firma[i].value;
        }

        var data_report_eliminados = "0";
        var array_data_report_eliminados = document.getElementsByName("data_report_eliminados");
        for (var i = 0; i < array_data_report_eliminados.length; i++) {
            if (array_data_report_eliminados[i].checked)
                data_report_eliminados = array_data_report_eliminados[i].value;
        }

        var data_numeracion_certificado = "0";
        var array_data_numeracion_certificado = document.getElementsByName("data_numeracion_certificado");
        for (var i = 0; i < array_data_numeracion_certificado.length; i++) {
            if (array_data_numeracion_certificado[i].checked)
                data_numeracion_certificado = array_data_numeracion_certificado[i].value;
        }

        var data_id_turno = document.getElementById("idturno").value;

        var data_required = 'data_report_nombres=' + data_report_nombres + '&data_report_apellidos=' + data_report_apellidos + '&data_report_datosfacturacion=' + data_report_datosfacturacion + '&data_report_datoscontacto=' + data_report_datoscontacto + '&data_report_firma=' + data_report_firma + '&data_report_prefijo=' + data_report_prefijo + '&data_report_fecharegistro=' + data_report_fecharegistro + '&data_report_modoregistro=' + data_report_modoregistro + '&data_report_eliminados=' + data_report_eliminados + '&data_numeracion_certificado=' + data_numeracion_certificado + '&data_report_montopago=' + data_report_montopago + '&data_id_turno=' + data_id_turno;

        window.open('http://cursos.bo/contenido/paginas.admin/ajax/ajax.impresion.cursos-participantes.exportar-lista.php?id_curso=<?php echo $id_curso; ?>&' + formato + '=true&' + data_required, 'popup', 'width=700,height=500');

    }
</script>



<!-- envio de factura -->
<script>
    function enviar_factura(id) {

        var email = $("#correo-de-envio-" + id).val();
        $("#box-modal_envia-factura-" + id).html("Enviando correo...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.instant.enviar_factura.php?nro_factura=' + id + '&email_a_enviar=' + email,
            data: {id: id},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#box-modal_envia-factura-" + id).html(data);
            }
        });
    }
</script>
<script>
    function enviar_factura2(id) {

        var email = $("#correo-de-envio-" + id).val();
        $("#ffl-" + id).html("Enviando correo...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.instant.enviar_factura.php?nro_factura=' + id + '&email_a_enviar=' + email,
            data: {id: id},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ffl-" + id).html('<i class="btn btn-xs btn-default"><b class="fa fa-send"></b> Enviado!</i>');
            }
        });
    }
</script>





<!--FUNCIONES AJAX DE CONTEXTO-->
<script>
    function lista_participantes(id_curso, id_turno) {
        $("#ajaxloading-lista_participantes").html(text__loading_tres);
        document.getElementById('inputbuscador').value = "";
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.lista_participantes.php',
            data: {id_curso: id_curso, id_turno: id_turno, modo_pago: VAR_modo_de_pago},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes").html("");
                $("#ajaxbox-lista_participantes").html(data);

                $(".btnmodopago").removeClass("btn-success");
                $(".btnmodopago").addClass("btn-warning");
                $("#btnmodopago-" + VAR_modo_de_pago).removeClass("btn-warning");
                $("#btnmodopago-" + VAR_modo_de_pago).addClass("btn-success");

                $(".btnturno").removeClass("btn-success");
                $(".btnturno").addClass("btn-info");
                $("#btnturno-" + id_turno).removeClass("btn-info");
                $("#btnturno-" + id_turno).addClass("btn-success");

                document.getElementById("idturno").value = id_turno;
            }
        });
    }
    function lista_participantes_INICIO(id_curso, id_turno) {
        $("#ajaxloading-lista_participantes").html(text__loading_uno);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.lista_participantes.php',
            data: {id_curso: id_curso, id_turno: id_turno, modo_pago: VAR_modo_de_pago},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes").html("");
                $("#ajaxbox-lista_participantes").html(data);

                document.getElementById("idturno").value = id_turno;
            }
        });
    }
    function busca_participantes(id_curso, id_turno, busc) {
        $("#ajaxloading-lista_participantes").html(text__loading_tres);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.lista_participantes.php',
            data: {id_curso: id_curso, id_turno: id_turno, busc: busc, modo_pago: VAR_modo_de_pago},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes").html("");
                $("#ajaxbox-lista_participantes").html(data);

                $(".btnmodopago").removeClass("btn-success");
                $(".btnmodopago").addClass("btn-warning");
                $("#btnmodopago-" + id_turno).removeClass("btn-warning");
                $("#btnmodopago-" + id_turno).addClass("btn-success");

                $(".btnturno").removeClass("btn-success");
                $(".btnturno").addClass("btn-info");
                $("#btnturno-" + id_turno).removeClass("btn-info");
                $("#btnturno-" + id_turno).addClass("btn-success");
            }
        });

        $("#ajaxloading-lista_participantes_eliminados").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.lista_participantes_eliminados.php',
            data: {id_curso: id_curso, id_turno: id_turno, busc: busc, modo_pago: VAR_modo_de_pago},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes_eliminados").html("");
                $("#ajaxbox-lista_participantes_eliminados").html(data);
            }
        });

        document.getElementById("idturno").value = id_turno;

        return false;
    }
    function lista_participantes_eliminados(id_curso, id_turno) {
        $("#ajaxloading-lista_participantes_eliminados").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.lista_participantes_eliminados.php',
            data: {id_curso: id_curso, id_turno: id_turno, modo_pago: VAR_modo_de_pago},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-lista_participantes_eliminados").html("");
                $("#ajaxbox-lista_participantes_eliminados").html(data);
            }
        });
    }
    function edita_participante_p1(id_participante, nro_lista) {
        $("#ajaxloading-edita_participante_p1").html(text__loading_dos);
        $("#ajaxbox-edita_participante_p1").html("");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.edita_participante_p1.php',
            data: {id_participante: id_participante, nro_lista: nro_lista},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-edita_participante_p1").html("");
                $("#ajaxbox-edita_participante_p1").html(data);
            }
        });
    }
    function edita_participante_p2(id_participante) {
        $("#ajaxloading-edita_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.edita_participante_p2.php',
            data: $("#form-participante-" + id_participante).serialize(),
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-edita_participante_p2").html("");
                $("#ajaxbox-edita_participante_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
    function emite_certificado_p1(id_participante, nro_certificado) {
        /*if (nro_certificado === 2) {
         alert('PARA CERTIFICADO 2 USAR EMISION MULTIPLE (los botones de abajo)');
         $("#ajaxloading-emite_certificado_p1").html("");
         $("#ajaxbox-emite_certificado_p1").html("");
         } else {
         */

        $("#ajaxloading-emite_certificado_p1").html(text__loading_dos);
        $("#ajaxbox-emite_certificado_p1").html("");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.emite_certificado_p1.php',
            data: {id_participante: id_participante, nro_certificado: nro_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-emite_certificado_p1").html("");
                $("#ajaxbox-emite_certificado_p1").html(data);
            }
        });

        /*}*/
    }
    function emite_certificado_p2(id_participante, nro_certificado) {

        var receptor_de_certificado = $("#receptor_de_certificado-" + id_participante).val();
        var id_certificado = $("#id_certificado-" + id_participante).val();
        var id_curso = $("#id_curso-" + id_participante).val();

        $("#ajaxloading-emite_certificado_p2").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.emite_certificado_p2.php',
            data: {receptor_de_certificado: receptor_de_certificado, id_certificado: id_certificado, id_curso: id_curso, id_participante: id_participante, nro_certificado: nro_certificado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                if (nro_certificado === 1) {
                    $("#box-modal_emision_certificado-button-" + id_participante).html('<i class="btn-sm btn-default active">Emitido</i>');
                } else {
                    $("#box-modal_emision_certificado-button-2-" + id_participante).html('<i class="btn-sm btn-default active">Emitido</i>');
                }
                lista_participantes(<?php echo $id_curso; ?>, 0);
                $("#ajaxloading-emite_certificado_p2").html("");
                $("#ajaxbox-emite_certificado_p2").html(data);
            }
        });
    }
    function datos_registro(id_participante) {
        $("#ajaxbox-datos_registro").html("");
        $("#ajaxloading-datos_registro").html(text__loading_uno);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.datos_registro.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-datos_registro").html("");
                $("#ajaxbox-datos_registro").html(data);
            }
        });
    }
    function emite_factura_p1(id_participante) {
        $("#ajaxloading-emite_factura_p1").html(text__loading_dos);
        $("#ajaxbox-emite_factura_p1").html("");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.emite_factura_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-emite_factura_p1").html("");
                $("#ajaxbox-emite_factura_p1").html(data);
            }
        });
    }
    function emite_factura_p2(id_participante) {
        var data_form = $("#form-emite-factura-" + id_participante).serialize();
        $("#ajaxloading-emite_factura_p2").html(text__loading_dos);
        $("#ajaxbox-emite_factura_p2").html("");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.emite_factura_p2.php',
            data: data_form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-emite_factura_p2").html("");
                $("#ajaxbox-emite_factura_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });
    }
    function elimina_participante_p1(id_participante) {
        $("#ajaxbox-elimina_participante_p1").html("");
        $("#ajaxloading-elimina_participante_p1").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.elimina_participante_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-elimina_participante_p1").html("");
                $("#ajaxbox-elimina_participante_p1").html(data);
            }
        });
    }
    function elimina_participante_p2(id_participante) {
        $("#ajaxbox-elimina_participante_p2").html("");
        $("#ajaxloading-elimina_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.elimina_participante_p2.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-elimina_participante_p2").html("");
                $("#ajaxbox-elimina_participante_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
    function habilita_participante_p1(id_participante) {
        $("#ajaxbox-habilita_participante_p1").html("");
        $("#ajaxloading-habilita_participante_p1").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.habilita_participante_p1.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-habilita_participante_p1").html("");
                $("#ajaxbox-habilita_participante_p1").html(data);
            }
        });
    }
    function habilita_participante_p2(id_participante) {
        $("#ajaxbox-habilita_participante_p2").html("");
        $("#ajaxloading-habilita_participante_p2").html(text__loading_dos);
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.habilita_participante_p2.php',
            data: {id_participante: id_participante},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#ajaxloading-habilita_participante_p2").html("");
                $("#ajaxbox-habilita_participante_p2").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
    function agrega_participante() {
        var data_form = $("#form-agrega-participante").serialize();
        $("#ajaxloading-agrega_participante").html(text__loading_dos);
        $("#ajaxbox-agrega_participante").html("");
        var numeracionParticipante = parseInt($("#numeracion-nuevo-participante").val()) + 1;
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.agrega_participante.php',
            data: data_form,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                var data_json_parsed = JSON.parse(data);
                $("#ajaxloading-agrega_participante").html("");
                $("#ajaxbox-agrega_participante").html(data_json_parsed['mensaje']);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                if (document.getElementById('impficha').checked === true) {
                    if (data_json_parsed['estado'] === 1) {
                        window.open(data_json_parsed['url_ficha'], 'popup', 'width=700,height=500');
                    }
                }
                document.getElementById("form-agrega-participante").reset();
                $("#numeracion-nuevo-participante").val(numeracionParticipante);
            }
        });
    }
    function agregar_participantes_csv() {

        var myform = document.getElementById("FORM-agregar_participantes_csv");
        var fd = new FormData(myform);
        $.ajax({
            url: "contenido/paginas.admin/ajax/ajax.cursos-participantes.agregar_participantes_csv.php",
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(dataofconfirm) {
                // do something with the result
                $("#BOX-agregar_participantes_csv").html(dataofconfirm);
                lista_participantes(<?php echo $id_curso; ?>, 0);
            }
        });


    }
</script>

<script>
    function cambiar_estado_curso(id_curso, estado) {
        $("#box-desactivar-curso").html("Cargando...");
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.cambiar_estado_curso.php',
            data: {id_curso: id_curso, estado: estado},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                if (estado === 'desactivado') {
                    $("#div-add-participante").html('');
                    $("#div-add-participante").css('display', 'none');
                }
                $("#box-desactivar-curso").html(data);
                lista_participantes(<?php echo $id_curso; ?>, 0);
                lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);
            }
        });
    }
</script>

<!--FUNCIONES DE INICIO DE PAGINA-->
<script>
    var text__loading_uno = "<div style='text-align:center;'><img src='contenido/imagenes/images/loader.gif'/></div>";
    var text__loading_dos = "Cargando...";
    var text__loading_tres = "<div style='background: #FFF;padding: 10px;border: 1px solid gray;border-radius: 5px;position: absolute;box-shadow: 2px 2px 8px 0px #80808087;'>Actualizando...</div>";

    lista_participantes_INICIO(<?php echo $id_curso; ?>, 0);
    lista_participantes_eliminados(<?php echo $id_curso; ?>, 0);

</script>

<!-- AUTOCOMPLETADO DE PARTICIPANTE POR CI -->
<script>
    function checkParticipante(dat) {
        $.ajax({
            url: 'contenido/paginas.admin/ajax/ajax.cursos-participantes.checkParticipante.php',
            data: {dat: dat},
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                var data_json_parsed = JSON.parse(data);
                if (data_json_parsed['estado'] === 1) {
                    if ($("#f-nom").val() === '') {
                        $("#f-nom").val((data_json_parsed['nombres']).toUpperCase());
                    }
                    if ($("#f-ape").val() === '') {
                        $("#f-ape").val((data_json_parsed['apellidos']).toUpperCase());
                    }
                    if ($("#f-email").val() === '') {
                        $("#f-email").val((data_json_parsed['correo']).toLowerCase());
                    }
                    if ($("#f-pref").val() === '') {
                        $("#f-pref").val((data_json_parsed['prefijo']).toUpperCase());
                    }
                    if ($("#f-exp").val() === '') {
                        $("#f-exp").val(data_json_parsed['ci_expedido']).change();
                    }
                    if ($("#f-raz").val() === '') {
                        $("#f-raz").val((data_json_parsed['razon_social']).toUpperCase());
                    }
                    if ($("#f-nit").val() === '') {
                        $("#f-nit").val((data_json_parsed['nit']).toUpperCase());
                    }
                } else {
                    $("#f-nom").val('');
                    $("#f-ape").val('');
                    $("#f-email").val('');
                    $("#f-pref").val('');
                    $("#f-exp").val('').change();
                    $("#f-raz").val('');
                    $("#f-nit").val('');
                }
            }
        });
    }
</script>


<?php

function my_date_curso($dat) {
    if ($dat == '0000-00-00') {
        return "00 Mes 00";
    } else {
        $ar1 = explode('-', $dat);
        $arraymes = array('none', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
        return $ar1[2] . " " . $arraymes[(int) $ar1[1]] . " " . substr($ar1[0], 2, 2);
    }
}
