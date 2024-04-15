<?php
/* mensaje */
$mensaje = "";

/* id foro */
$id_foro = $get[3];

$rqf1 = query("SELECT * FROM cursos_foros WHERE id='$id_foro' LIMIT 1 ");
$rqf2 = fetch($rqf1);

$tema_foro = $rqf2['tema'];
$descripcion_foro = $rqf2['descripcion'];
$id_categoria_foro = $rqf2['id_categoria'];
$id_usuario_foro = $rqf2['id_usuario'];
$fecha_registro_foro = $rqf2['fecha_registro'];

/* categoria */
$rqc1 = query("SELECT titulo,titulo_identificador FROM cursos_categorias WHERE id='$id_categoria_foro' LIMIT 1 ");
$rqc2 = fetch($rqc1);
$titulo_categoria = $rqc2['titulo'];
$titulo_identificador_categoria = $rqc2['titulo_identificador'];

/* usuario */
$rqdu1 = query("SELECT * FROM cursos_usuarios WHERE id='$id_usuario_foro' ");
$rqdu2 = fetch($rqdu1);
$nombre_usuario = $rqdu2['nombres'] . ' ' . $rqdu2['apellidos'];

/* agregado de respuesta */
if(isset_post('ingresar-respuesta') && (isset_usuario() || isset_docente())){
    $respuesta = post('respuesta');
    $id_usuario = usuario('id');
    $sw_docente = '0';
    if(isset_docente()){
        $id_usuario = docente('id');
        $sw_docente = '1';
    }
    $fecha_registro = date("Y-m-d H:i");
    query("INSERT INTO cursos_foros_respuestas(
          id_foro, 
          id_usuario, 
          respuesta, 
          sw_docente, 
          fecha_registro, 
          estado
          ) VALUES (
          '$id_foro',
          '$id_usuario',
          '$respuesta',
          '$sw_docente',
          '$fecha_registro',
          '1'
          )");
    $mensaje = '<div class="alert alert-success">
  <strong>Exito!</strong> el registro fue agregado correctamente.
</div>';
}
?>
<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-12">

                <ul class="breadcrumb">
                    <li><a href="<?php echo $domino; ?>">Inicio</a></li>
                    <li><a href="foros.html">Foros</a></li>
                    <li><a href="foros/<?php echo $titulo_identificador_categoria; ?>.html"><?php echo $titulo_categoria; ?></a></li>
                    <li><?php echo substr($tema_foro, 0, 50); ?>...</li>
                </ul>


                <div>
                    <div style="border:1px solid #CCC;border-radius: 5px;">
                        <div class="row">
                            <div class='col-md-9'>
                                <div style="padding:0px 7px;border-right: 1px solid #CCC;">
                                    <div class="TituloArea">
                                        <h3><?php echo $tema_foro; ?></h3>
                                    </div>
                                    <p><?php echo $descripcion_foro; ?></p>
                                </div>
                            </div>
                            <div class='col-md-3'>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div style="padding:5px 7px;">
                                            Usuario: <?php echo $nombre_usuario; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-xs-12">
                                        <div style="padding:0px 7px; font-size: 8pt;">
                                            Fecha: <?php echo $fecha_registro_foro; ?>
                                            <br/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 hidden-xs">
                                        <div class="text-center">
                                            <img src="<?php echo $dominio; ?>contenido/imagenes/images/user.png" style="width:90%;"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                </div>
                
                <?php echo $mensaje; ?>

                <div style="padding:2px;">

                    <table class="table table-hover calendar">
                        <thead>
                            <tr>
                                <th>
                                    Respuestas
                                </th>
                                <th>
                                    Usuarios
                                </th>
                                <th>
                                    Fecha de respuesta
                                </th>
                            </tr>
                        </thead>
                        <tbody id="lista_curso">
                            <?php
                            $rqf1 = query("SELECT r.respuesta,r.fecha_registro,u.nombres,u.apellidos FROM cursos_foros_respuestas r INNER JOIN cursos_usuarios u ON r.id_usuario=u.id WHERE r.id_foro='$id_foro' ORDER BY r.id DESC ");
                            while ($rqf2 = fetch($rqf1)) {
                                $respuesta_foro = $rqf2['respuesta'];
                                $fecha_registro_respuesta_foro = $rqf2['fecha_registro'];
                                $usuario_respuesta_foro = $rqf2['nombres'].' '.$rqf2['apellidos'];
                                ?>
                                <tr>
                                    <td class="td_color">
                                        <?php echo $respuesta_foro; ?>
                                    </td>
                                    <td class="td_color">
                                        <?php echo $usuario_respuesta_foro; ?>
                                    </td>
                                    <td class="td_color">
                                        <?php echo $fecha_registro_respuesta_foro; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>

                    <hr/>

                    <?php
                    if (!isset_usuario() && !isset_docente()) {
                        ?>
                        <p>Participa en este y otros foros, dejando tu opini&oacute;n acerca de los distintos temas de discuci&oacute;n.</p>
                        <a class="btn btn-lg btn-block btn-success" data-toggle="modal" data-target="#myModal">
                            <i class="icon-shopping-cart text-contrast"></i> PARTICIPAR DEL FORO
                        </a>
                        <?php
                    } else {
                        ?>
                        <form action="" method="post">
                            <div class='panel'>
                                <div class='panel-body'>
                                    <b>Ingresa tu opini&oacute;n acerca de este tema de discusi&oacute;n.</b>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-1 col-xs-2">
                                            <img src="contenido/imagenes/images/user.png" style="height: 50px;">
                                        </div>
                                        <div class="col-md-11 col-xs-10">
                                            <textarea class="form-control" name="respuesta" style="margin-bottom:0px;" placeholder="Ingresa tu opini&oacute;n aqui..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="ingresar-respuesta" value="true"/>
                            <button type="submit" name="ingresar-opinion" class="btn btn-lg btn-block btn-success">
                                <i class="icon-shopping-cart text-contrast"></i> ENVIAR RESPUESTA
                            </button>
                        </form>
                        <?php
                    }
                    ?>

                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <hr/>

                </div>


            </div>

        </div>


        <div style="height:10px"></div>
    </section>
</div>                     








<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PARTICIPA EN ESTE FORO</h4>
            </div>
            <div class="modal-body">
                <p>
                    Para poder participar en este y otros foros de la plataforma <b><?php echo $___nombre_del_sitio; ?></b> es necesario que ingreses a tu cuenta, puedes hacerlo a traves de el siguiente formulario. 
                </p>
                <div>
                    <div class="boxForm">
                        <h5>INGRESA A TU CUENTA</h5>
                        <hr/>
                        <form action="ingreso-de-usuarios/foro/<?php echo $id_foro; ?>.html" class="form-horizontal validable" id="contactform" method="post" enctype="application/x-www-form-urlencoded" autocomplete="Off">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input class="form-control required string" type="email" name="email" placeholder="Correo electr&oacute;nico..." required="">
                                </div>
                                <div class="col-sm-12">
                                    <input class="form-control required string" type="password" name="password" placeholder="Contrase&ntilde;a..." required="">
                                </div>
                            </div>                    					
                            <div class="form-group">
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="ingresar" class="btn btn-success" value="INGRESAR A MI CUENTA"/>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group">
                                <span><b style="font-weight:bold;">&iquest; No tienes una cuenta ?</b> registrate con el siguiente enlace:</span>
                                <br/>
                                <br/>
                                <div class="col-md-12 text-center">
                                    <a href="registro-de-usuarios/foro/<?php echo $id_foro; ?>.html" type="submit" rel="nofollow" class="btn btn-primary">CREAR UNA CUENTA</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
