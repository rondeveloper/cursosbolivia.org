<?php
$mensaje = '';

if (isset_post('crear-curso')) {

    $contenido = post_html('formulario');
    $titulo = post('titulo');
    $titulo_identificador = limpiar_enlace($titulo);
    $estado = post('estado');
    $sw_siguiente_semana = post('sw_siguiente_semana');
    $incrustacion = post_html('incrustacion');
    $google_maps = post_html('google_maps');

    $id_departamento = post('id_departamento');
    $fecha = post('fecha');
    $lugar = post('lugar');
    $horarios = post('horarios');
    $expositor = post('expositor');
    $colaborador = post('colaborador');
    $gastos = post('gastos');
    $observaciones = post('observaciones');

    $imagen = post('preimagen');
    $imagen2 = post('preimagen2');
    $imagen3 = post('preimagen3');
    $imagen4 = post('preimagen4');

    $short_link = post_html('short_link');
    
    $inicio_numeracion = post('inicio_numeracion');

    if (isset_archivo('imagen')) {
        $imagen = time() . archivoName('imagen');
        move_uploaded_file(archivo('imagen'), "contenido/imagenes/paginas/$imagen");
        //sube_imagen(archivo('imagen'), "contenido/imagenes/paginas/$imagen");
    }
    if (isset_archivo('imagen2')) {
        $imagen2 = time() . archivoName('imagen2');
        move_uploaded_file(archivo('imagen2'), "contenido/imagenes/paginas/$imagen2");
    }
    if (isset_archivo('imagen3')) {
        $imagen3 = time() . archivoName('imagen3');
        move_uploaded_file(archivo('imagen3'), "contenido/imagenes/paginas/$imagen3");
    }
    if (isset_archivo('imagen4')) {
        $imagen4 = time() . archivoName('imagen4');
        move_uploaded_file(archivo('imagen4'), "contenido/imagenes/paginas/$imagen4");
    }

    query("INSERT INTO cursos (
                 titulo,
                 titulo_identificador,
                 estado,
                 sw_siguiente_semana,
                 contenido,
                 google_maps,
                 incrustacion,
                 id_ciudad,
                 lugar,
                 horarios,
                 fecha,
                 expositor,
                 colaborador,
                 gastos,
                 observaciones,
                 imagen,
                 imagen2,
                 imagen3,
                 imagen4,
                 short_link,
                 inicio_numeracion
                 ) VALUES (
                 '$titulo',
                 '$titulo_identificador',
                 '$estado',
                 '$sw_siguiente_semana',
                 '$contenido',
                 '$google_maps',
                 '$incrustacion',
                 '$id_departamento',
                 '$lugar',
                 '$horarios',
                 '$fecha',
                 '$expositor',
                 '$colaborador',
                 '$gastos',
                 '$observaciones',
                 '$imagen',
                 '$imagen2',
                 '$imagen3',
                 '$imagen4',
                 '$short_link',
                 '$inicio_numeracion'
                 ) ");

    $rqc1 = query("SELECT id FROM cursos WHERE titulo_identificador='$titulo_identificador' ORDER BY id DESC limit 1 ");
    $rqc2 = mysql_fetch_array($rqc1);
    $id_curso = $rqc2['id'];

    movimiento('Creacion de curso', 'creacion-curso', 'curso', $id_curso);

    $mensaje .= "<h4>Pagina Creada!</h4>";

    echo "<script>alert('Registro exitoso!');location.href='cursos-listar.adm';</script>";
    exit;
}

editorTinyMCE('editor1');
?>

<div class="row">
    <div class="col-mod-12">
        <ul class="breadcrumb">
            <?php
            include_once 'contenido/paginas.admin/items/item.enlaces_top.php';
            ?>
            <li><a href="<?php echo $dominio; ?>admin">Panel Principal</a></li>
            <li><a >Cursos</a></li>
            <li class="active">Creaci&oacute;n de curso</li>
        </ul>
        <div class="form-group hiddn-minibar pull-right">
            <form action="" method="post">
                <input type="text" name="buscar" class="form-control form-cascade-control " size="20" placeholder="Buscar en el Sitio">
                <span class="input-icon fui-search"></span>
            </form>
        </div>
        <h3 class="page-header"> Creaci&oacute;n de Curso <i class="fa fa-info-circle animated bounceInDown show-info"></i> </h3>
        <blockquote class="page-information hidden">
            <p>
                Creaci&oacute;n de curso.
            </p>
        </blockquote>
    </div>
</div>

<?php
echo $mensaje;
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">



            <div class="panel-body">

                <form enctype="multipart/form-data" action="" method="post">
                    <table style="margin:auto;">
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; T&iacute;tulo del curso: </span>
                                    <input type="text" name="titulo" value="" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Estado: </span>
                                    <p class="form-control text-center">
                                        <input type="radio" name="estado" checked="true" value="1" id="act"/> <label for="act">Activado</label> 
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="estado" value="0" id="dact"/> <label for="dact"> Desactivado</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="estado" value="2" id="temp"/> <label for="temp"> Temporal</label>
                                    </p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Semana del curso: </span>
                                    <p class="form-control text-center">
                                        <input type="radio" name="sw_siguiente_semana" checked="true" value="0" id="act_s"/> <label for="act_s">Esta Semana</label> 
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="sw_siguiente_semana" value="1" id="dact_s"/> <label for="dact_s"> Siguiente Semana</label>
                                    </p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-pagelines"></i> &nbsp; Fecha del curso: </span>
                                    <input type="date" name="fecha" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Departamento: </span>
                                    <select class="form-control" name="id_departamento">
                                        <?php
                                        $rqd1 = query("SELECT id,nombre FROM departamentos ORDER BY orden ");
                                        while ($rqd2 = mysql_fetch_array($rqd1)) {
                                            echo '<option value="' . $rqd2['id'] . '">' . $rqd2['nombre'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Lugar: </span>
                                    <input type="text" name="lugar" value="" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Google Maps: </span>
                                    <input type="text" name="google_maps" class="form-control" value="" placeholder='<iframe src="https://www.google.com/maps/embed?pb=!1m17...'>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Horarios: </span>
                                    <input type="text" name="horarios" value="" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Expositor: </span>
                                    <input type="text" name="expositor" value="" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Colaborador: </span>
                                    <input type="text" name="colaborador" value="" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Gastos: </span>
                                    <input type="text" name="gastos" value="" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Observaciones: </span>
                                    <input type="text" name="observaciones" value="" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; URL corta: </span>
                                    <input type="text" name="short_link" value="" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> &nbsp; Inicio numeraci&oacute;n: </span>
                                    <input type="text" name="inicio_numeracion" value="" class="form-control" id="date">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia <b>1</b>: </span>
                                    <input type="file" name="imagen" class="form-control">
                                </div>
                            </td>
                            <td>
                                <div class="input-group col-sm-12 text-center">
                                    <img src="paginas/.size=1.img" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia 2: </span>
                                    <input type="file" name="imagen2" class="form-control">
                                </div>
                            </td>
                            <td>
                                <div class="input-group col-sm-12 text-center">
                                    <img src="paginas/.size=1.img" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia 3: </span>
                                    <input type="file" name="imagen3" class="form-control">
                                </div>
                            </td>
                            <td>
                                <div class="input-group col-sm-12 text-center">
                                    <img src="paginas/.size=1.img" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group col-sm-12">
                                    <span class="input-group-addon"><i class="fa fa-picture-o"></i> &nbsp; Imagen referencia 4: </span>
                                    <input type="file" name="imagen4" class="form-control">
                                </div>
                            </td>
                            <td>
                                <div class="input-group col-sm-12 text-center">
                                    <img src="paginas/.size=1.img" style="height:35px;border:1px solid #AAA;border-radius:3px;padding:2px;"/>
                                </div>
                            </td>
                        </tr>

                        <?php
                        $cont_predef = '
                <p style="text-align: center;"><span style="font-size: 12pt;"><strong><span style="color: #ff0000;">Contenido del Curso (ORIENTADO A PROFESIONALES y CONSULTORES)<br></span></strong></span></p>
<p><span style="font-size: 12pt; color: #000000;">Curso taller del SABS(Sistema de Administracion de Bienes y Servicios), SICOES(Sistema de Contrataciones estatales), la plataforma del INFOSICOES en la ciudad de Santa Cruz<br></span></p>
<p><span style="font-size: 12pt; color: #000000;">Dirigido a Consultores, Profesionales(<strong>Medicos, Dentistas, Enfermeras, Arquitectos, Ingenieros, Administradores, Contadores, Abogados, Psicólogos, Trabajadoras sociales,&nbsp; etc</strong>),Secretarias, Choferes, Serenos, Estudiantes, Pasantes</span><span style="font-size: 12pt;"><span style="color: #000000;">.</span><br></span></p>
<p>&nbsp;</p>
<p style="text-align: center;"><span style="font-size: 12pt;"><span style="color: #000000;"> [imagen-1] </span></span></p>
<p style="text-align: center;"><span style="font-size: 12pt;"> <br></span></p>
<p>&nbsp;<span style="font-size: 12pt;"><strong><span style="color: #ff0000;">CONTENIDO DEL CURSO (1ra PARTE - TEORICO)</span></strong></span><br><span style="font-size: 12pt;"><span style="color: #000000;">• Requisitos para participar en procesos de Licitación</span><br><span style="color: #000000;">• Requisitos y registro en SIGEP</span><br><span style="color: #000000;">• Requisitos y registro en RUPE</span><br><span style="color: #000000;">• Procesos de contratación SABS - SICOES </span><br><span style="color: #000000;">• Modalidades(Contrataciones menores, ANPE, Licitacion Pública, Directa)</span><br><span style="color: #000000;">• Plazos definidos por norma</span><br><span style="color: #000000;">• Contenidos de un Documento Base de Contratación</span>&nbsp;<br></span><br>&nbsp;<span style="font-size: 12pt;"><strong><span style="color: #ff0000;">CONTENIDO DEL CURSO (2da PARTE – USANDO INTERNET)</span></strong></span><br><span style="font-size: 12pt;"><span style="color: #000000;">• Interpretación del DBC y llenado de formularios&nbsp;</span><br><span style="color: #000000;">• Recuperación de password de RUPE</span><br><span style="color: #000000;">• Generación de certificado RUPE</span><br><span style="color: #000000;">• Generación certificado SIGEP</span><br><span style="color: #000000;">• Técnicas especiales de búsquedas de licitaciones&nbsp;</span><br><span style="font-size: 12pt; color: #000000;">• Experiencias en proceso de Compras Menores<br><span style="font-size: 12pt;">• Experiencias en licitaciones</span><br>• Manejo y Registro en el InfoSICOES<br>• Panel de administración y personalización de InfoSICOES</span><br><span style="font-size: 12pt; color: #000000;">• Plan Anual de Contrataciones personalizado en el InfoSICOES(Compras Menores y Licitaciones)</span><br></span><br>&nbsp;<span style="font-size: 12pt;"><strong><span style="color: #ff0000;">CONTENIDO DEL CURSO (3ra PARTE – TALLER LLENADO FORMULARIOS) <span style="color: #ffff00; background-color: #ff0000;">OPCIONAL</span></span></strong></span><br><span style="font-size: 12pt; color: #000000;">• Llenado de formularios de postulación para CONSULTORES<br>•&nbsp;simulación presentación de propuestas<br></span><span style="font-size: 12pt; color: #000000;">• Evaluación del curso<br></span><br style="font-size: 12pt;"><span style="color: #000000;">&nbsp;</span><span style="font-size: 12pt;"><span style="color: #000000;">confirme su participación&nbsp;</span><span style="color: #000080;">&nbsp;</span></span></p>
<p>&nbsp;</p>
<p>&nbsp;<span style="font-size: 12pt;"><strong><span style="color: #ff0000;">DIA Y HORA:</span> Domingo 09 <span style="color: #000000;">de abril 2017</span></strong></span></p>
<p>&nbsp;</p>
<p><span style="font-size: 12pt;"><strong><span style="color: #ff0000;"><br>LUGAR DEL EVENTO: </span>AUDITORIO HOTEL LP EQUIPETROL[3er Anillo Interno]<br><br></strong></span><span style="font-size: 12pt;"><span style="color: #ff0000;"><strong>DIRECCIÓN:</strong></span> <strong>Calle Viador Pinto Nro 200 Hotel LP Equipetrol Salon Entre Gilberto Molina y Av. Marcelo Terceros Banzer frente al hotel Casa Blanca 3er Anillo Externo<br></strong></span></p>
<p><span style="font-size: 12pt;"><strong>La empresa NEMABOL organiza 2 CURSOS del mismo contenido en 2 HORARIOS</strong></span></p>
<p><span style="font-size: 12pt;"><strong><span style="background-color: #ff0000; color: #ffff00;">CURSO 1</span><br><span style="color: #000000;">Primera Parte(09:00 AM - 11:00 AM)&nbsp;TEÓRICO</span><br><span style="color: #000000;">Segunda Parte(11:00 AM - 12:30 M) USANDO INTERNET</span><br><span style="color: #000000;">Tercera Parte(12:30 M - 1:00 PM)&nbsp;TALLER PRÁCTICO</span> [<span style="color: #ff0000;"><span style="color: #ffff00; background-color: #ff0000;">OPCIONAL</span></span>]</strong></span></p>
<p><span style="color: #ff0000;"><strong><span style="font-size: 12pt;"><br><span style="color: #ffff00; background-color: #ff0000;">CURSO 2</span><br><span style="color: #000000;">Primera Parte(2:00 PM - 4:00 PM)&nbsp;TEÓRICO</span><br><span style="color: #000000;">Segunda Parte(4:00 PM - 5:30 PM) USANDO INTERNET</span><br><span style="color: #000000;">Tercera Parte(5:30 PM - 6:00 PM)&nbsp;TALLER PRÁCTICO</span> [<span style="color: #ffff00; background-color: #ff0000;">OPCIONAL</span>]<br><br>INVERSION 200 Bs <br></span></strong></span></p>
<p><span style="font-size: 12pt;"><strong><span style="font-size: 12pt; color: #000000;"><span style="color: #ff0000;">DESCUENTOS(mediante depósito Bancarios y/o Transferencias):</span><br></span></strong></span><strong><span style="font-size: 12pt; color: #000000;">150 Bs. Hasta el viernes 07&nbsp; de abril<br>180 Bs. Hasta el Sabado 08&nbsp; de abril</span></strong></p>
<p>&nbsp;<span style="font-size: 12pt;"><strong><span style="color: #ff0000;"><span style="color: #ffff00; background-color: #ff0000;">__CUPOS LIMITADOS__</span></span></strong></span></p>
<p><span style="font-size: 12pt;"><strong><span style="color: #ff0000;">EL CURSO INCLUYE:</span></strong>&nbsp;<span style="color: #000000;">Material de Apoyo, Bolígrafo, Certificado de Participación y Asesoramiento Post Curso. Ticket suscripción gratuita PAQUETE GOLD(Sistema de notificación de licitaciones) por 6 meses exclusivo para <strong>consultores</strong>.&nbsp;</span><br><br></span></p>
<p><span style="font-size: 12pt; color: #000000;">Las inscripciones se realizan en el mismo lugar del evento quince minutos antes del inicio del Curso Especializado del InfoSICOES para cada CURSO<br></span></p>
<p>&nbsp;</p>
<p><span style="font-size: 12pt;"><strong><span style="color: #ff0000;">CUENTA BANCARIA</span></strong></span></p>
<p><span style="font-size: 12pt;"><strong><span style="color: #ff0000;">A nombre de : </span></strong></span><span style="font-family: arial,helvetica,sans-serif;"><span style="font-size: 12pt;"><strong><span style="color: #ff0000;"><span style="color: #000000;">NEMABOL <br>Banco UNION cuenta 1-24033578</span></span></strong></span></span></p>
<p><span style="color: #000000; font-size: 12pt;"><span style="font-size: 12pt;">Luego de realizar el depósito reportar con una foto el comprobante de depósito vía WhatsApp incluyeno el <em><strong>CURSO 1 (MAÑANA)</strong></em> o <em><strong>CURSO 2 (TARDE)</strong></em> al numero <strong>76712006 </strong>incluyendo el nombre completo del participante y los datos para su factura.<br><br>Consultas WhatsApp 76712006</span></span></p>';
                        ?>
                        <tr>
                            <td colspan="2">
                                <br/>
                                <b>Palabras reservadas:</b> [imagen-1] , [imagen-2] , [imagen-3] , [imagen-4] 
                                <br/>
                                <br/>
                                <textarea name="formulario" id="editor1" style="width:100%;margin:auto;" rows="25"><?php echo utf8_encode($cont_predef); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <br/>
                                <b>Incrustaci&oacute;n de Html</b>
                                <br/>
                                <textarea name="incrustacion" style="width:100%;margin:auto;" rows="5" class="form-control"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="text-align: center;padding:20px;">
                                    <input type="submit" value="CREAR CURSO" name="crear-curso" class="btn btn-success btn-lg btn-animate-demo"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>




