<?php
/* mensaje */
$mensaje = '';

/* data */
$id_participante = $get[2];
$hash = $get[3];

/* verificacion */
if (md5($id_participante . 'key15613') != $hash) {
    echo "<script>alert('DENEGADO');location.href='$dominio';</script>";
    exit;
}

/* participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$participante = fetch($rqdp1);
$nombre_participante = $participante['nombres'] . ' ' . $participante['apellidos'];
$correo_participante = $participante['correo'];
$id_proceso_registro = $participante['id_proceso_registro'];

$sw_registrado = false;

/* confirmar-registro */
if (isset_post('confirmar-registro')) {
    $id_curso = 2344;

    $rqdprreg1 = query("SELECT * FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
    $rqdprreg2 = fetch($rqdprreg1);
    /* datos del curso */
    $id_turno = '0';
    $correo_proceso_registro = $correo_participante;
    $celular_proceso_registro = $rqdprreg2['celular_contacto'];
    $nombre_institucion = '';
    $telefono_institucion = '';
    $razon_social = '';
    $nit = '';

    $rq1 = query("SELECT * FROM cursos WHERE id='$id_curso' ORDER BY estado DESC,id DESC limit 1 ");
    $curso = fetch($rq1);

    /* INSCRIPCION */
    $fecha_registro = date("Y-m-d H:i:s");

    $titulo_curso = $curso['titulo'];
    $url_curso = $curso['titulo_identificador'];
    $fecha_curso = $curso['fecha'];
    $ciudad_curso = $curso['ciudad'];
    $lugar_curso = $curso['lugar'];
    $horario_curso = $curso['horarios'];
    $cod_reg = $id_curso . '-' . date("ymdh");
    $sw_pago_enviado = '1';

    $nombres = $participante['nombres'];
    $apellidos = $participante['apellidos'] ;
    $prefijo = $participante['prefijo'];
    $correo = $participante['correo'] ;
    $ci = $participante['ci'] ;
    $ci_expedido = $participante['ci_expedido'];
    $tel_cel = $participante['celular'];
    $id_dep = $participante['id_departamento'];
    $monto_deposito = '0';

    /* proceso registro */
    $hashcod_registro = substr(md5(rand(0, 99999)), 4, 7);
    query("INSERT INTO cursos_proceso_registro(id_curso, fecha_registro, id_modo_pago, cod_reg, razon_social, nit, cnt_participantes, correo_contacto, celular_contacto,imagen_deposito,monto_deposito,id_turno,estado,sw_pago_enviado,hash_cod) VALUES ('$id_curso','$fecha_registro','10','$cod_reg','$razon_social','$nit','1','$correo_proceso_registro','$celular_proceso_registro','','$monto_deposito','$id_turno','1','$sw_pago_enviado','$hashcod_registro')");
    $id_proceso_registro = insert_id();

    $codigo_de_registro = "R00$id_proceso_registro";
    query("UPDATE cursos_proceso_registro SET codigo='$codigo_de_registro' WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");

    $rqvp1 = query("SELECT id FROM cursos_participantes WHERE id_curso='$id_curso' AND nombres LIKE '$nombres' AND apellidos LIKE '$apellidos' ORDER BY id DESC limit 1 ");
    if (num_rows($rqvp1) == 0) {

        /* numeracion */
        $rqln1 = query("SELECT numeracion FROM cursos_participantes WHERE id_curso='$id_curso' AND estado='1' ORDER BY numeracion DESC limit 1 ");
        $rqln2 = fetch($rqln1);
        $numeracion = ((int) $rqln2['numeracion']) + 1;

        query("INSERT INTO cursos_participantes (
                   id_curso,
                   id_proceso_registro,
                   id_modo_pago,
                   id_departamento,
                   id_turno,
                   numeracion,
                   sw_pago,
                   nombres,
                   apellidos,
                   prefijo,
                   correo,
                   ci,
                   ci_expedido,
                   celular,
                   institucion,
                   tel_institucion
                   ) VALUES (
                   '$id_curso',
                   '$id_proceso_registro',
                   '10',
                   '$id_dep',
                   '$id_turno',
                   '$numeracion',
                   '$sw_pago_enviado',
                   '$nombres',
                   '$apellidos',
                   '$prefijo',
                   '$correo',
                   '$ci',
                   '$ci_expedido',
                   '$tel_cel',
                   '$nombre_institucion',
                   '$telefono_institucion'
                   ) ");
        $id_participante = insert_id();
        logcursos('Registro de participante [' . $codigo_de_registro . '][SIST-REG][GRATUITO]', 'participante-registro', 'participante', $id_participante);

        $mensaje = '<br><br><div class="alert alert-success">
  <strong>REGISTRO EXITOSO</strong>
se ha registrado sus datos como participante del curso.
</div>';
        $sw_registrado = true;
    }else{
        $mensaje .= '<br><br><div class="alert alert-warning">
  <strong>AVISO</strong> usted ya se registro anteriormente.
</div>';
    }

}

?>

<div style="height:140px"></div>
<div class="wrapsemibox">
    <?php echo $mensaje; ?>
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">

                <div class="TituloArea">
                    <h3>REGISTRO A CURSO GRATUITO</h3>
                </div>

                <div class="Titulo_texto1">
                    <?php if(!$sw_registrado){ ?>
                    <div style="background: #ffefd3;padding: 20px;font-size: 12pt;text-align: justify;">
                        Estimad@ <?php echo $nombre_participante; ?>, notamos que usted tomó varios cursos en nuestra plataforma y tiene la intención de seguir aprendiendo, es por eso que queremos ofrecerle el siguiente curso de manera completamente gratuita.
                        <br>
                        <h2 style="color:red;text-align: center;font-size: 16pt;">Curso Gerencia Pública en el Marco de la Gestión por Resultados En VIVO mediante Google Meet (Requisito Tener Laptop, Computadora o SmartPhone con Conexión a Internet)</h2>
                        <br>
                        Para poder incribirse a este curso solamente debe hacer clic en el siguiente bot&oacute;n:
                        <br>
                        <br>

                        <div style="text-align:center;padding: 20px 0px;">
                            <form action="" method="post">
                                <input type="submit" name="confirmar-registro" value="CONFIRMAR MI ASISTENCIA" class="btn btn-success" style="width: auto;border-radius: 5px;padding: 10px 20px;">
                            </form>
                        </div>
                    </div>
                    <?php }else{ ?>
                        <div style="background: #ffefd3;padding: 20px;font-size: 12pt;text-align: justify;">
                           Gracias por realizar su inscripci&oacute;n, se le enviaran por correo electr&oacute;nico los accesos al curso.
                        </div>
                    <?php } ?>
                </div>
                <hr />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
            </div>
        </div>
    </section>
</div>