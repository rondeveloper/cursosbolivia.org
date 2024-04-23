<?php

/* mensaje */
$mensaje = '';

/* data */
$id_participante = $get[2];
$hash = $get[3];

/* verificacion */
/*
if (md5(md5('auteditco104511'.$id_ref)) != $hash && false) {
    echo "<script>alert('DENEGADO');location.href='$dominio';</script>";
    exit;
}
*/

/* participante */
$rqdp1 = query("SELECT * FROM cursos_participantes WHERE id='$id_participante' ORDER BY id DESC limit 1 ");
$participante = fetch($rqdp1);
$nombre = $participante['nombres'] . ' ' . $participante['apellidos'];
$correo = $participante['correo'];
$id_curso = $participante['id_curso'];

/* curso */
$rqdc1 = query("SELECT titulo FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
$curso = fetch($rqdc1);
$nombre_curso = $curso['titulo'];

/* registro de asistencia */
$rqv1 = query("SELECT id FROM cursos_asistencia WHERE id_curso='$id_curso' AND id_participante='$id_participante' ORDER BY id DESC limit 1 ");
if (num_rows($rqv1)==0) {
    query("INSERT INTO cursos_asistencia 
    (id_curso, id_participante, fecha) 
    VALUES 
    ('$id_curso','$id_participante',CURDATE()) ");
}

?>

<div style="height:140px"></div>
<div class="wrapsemibox">

    <?php echo $mensaje; ?>

    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">

                <div class="TituloArea">
                    <h3>REGISTRO DE ASISTENCIA</h3>
                </div>

                <div class="Titulo_texto1">
                    <div style="background: #f3fbff;padding: 20px;font-size: 12pt;text-align: justify;border: 1px solid #e0e0e0;">
                        Hola <?php echo $nombre; ?>
                        <br>
                        <br>
                        Tu asistencia al curso ya fue registrada, muchas gracias por participar en nuestros cursos.
                        <br>
                        <br>

                        <div style="text-align:center;padding: 20px 0px;">
                                <table class="table table-bordered" style="background: #FFF;">
                                    <tr>
                                        <td style="padding: 15px;"><b>Participante:</b></td>
                                        <td style="padding: 15px;"><?php echo $nombre; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 15px;"><b>Curso:</b></td>
                                        <td style="padding: 15px;"><?php echo $nombre_curso; ?></td>
                                    </tr>
                                </table>
                                <br>
                                <a href="<?= $dominio ?>" class="btn btn-success" style="width: auto;border-radius: 5px;padding: 10px 20px;">
                                    FINALIZAR
                                </a>
                        </div>
                    </div>
                    <div style="background: #ffefd3;padding: 20px;font-size: 12pt;text-align: justify;">
                        ASISTENCIA REGISTRADA
                    </div>
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

