<?php

if(isset_usuario()){
    
    include_once 'contenido/paginas/subpaginas/acount.certificados.php';
    
}else{


$nombres = '';
$apellidos = '';
$email = '';
$celular = '';


if (isset($get[2]) && $get[2] == 'cuenta-google-no-encontrada') {
    $mensaje .= '<div class="alert alert-danger">
  <strong>Aviso</strong> no se encontro cuenta de usuario vinculada a la cuenta Google ingresada.
</div>';
}
?>

<div style="height:140px" class="hidden-xs"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="TituloArea">
                    <h3>CERTIFICADOS</h3>
                </div>
                <div class="Titulo_texto1">
                    <p>
                        Ingresa tu correo electr&oacute;nico para poder descargar tus certificados.
                    </p>
                    <br>
                </div>

                <?php echo $mensaje; ?>

                <?php
                if (!$sw_ingreso) {
                    ?>
                    <div class="boxForm ajusta_form_contacto">
                        <h5 class="hidden-xs">INGRESA TU CORREO</h5>
                        <hr class="hidden-xs"/>
                        <form action="ingreso-de-usuarios.html" class="form-horizontal validable" id="contactform" method="post" enctype="application/x-www-form-urlencoded" autocomplete="Off">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <input class="form-control required string" type="email" name="email" placeholder="Correo electr&oacute;nico..." required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 text-center">
                                    <input type="submit" name="ingresar-a-cuenta" class="btn btn-success btn-lg" value="INGRESAR"/>
                                </div>
                                <br class="hidden-xs">
                                <br class="hidden-xs">
                            </div>
                            <hr class="hidden-xs"/>
                            <div class="form-group text-center" style="display: none;">
                                <span><b style="font-weight:bold;">&iquest; No tienes una cuenta ?</b> registrate con el siguiente enlace:</span>
                                <br/>
                                <br/>
                                <div class="col-md-12 text-center">
                                    <a href="registro.html" type="submit" class="btn btn-primary">CREAR UNA CUENTA</a>
                                </div>
                            </div>
                        </form>

                    </div>
                    <?php
                } else {
                    ?>
                    Bienvenido a la plataforma Cursos.BO
                    <hr/>
                    <a href="<?php echo $dominio; ?>" class="btn btn-warning">CONTINUAR</a>
                    <?php
                    /* ingreso desde curso */
                    if (isset($get[3]) && ($get[2] == 'curso')) {
                        $id_curso = $get[3];
                        $rqdc1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
                        $rqdc2 = mysql_fetch_array($rqdc1);
                        $titulo_identificador_curso = $rqdc2['titulo_identificador'];
                        echo "<script>location.href='" . $dominio . "registro-curso/$titulo_identificador_curso.html';</script>";
                    }
                    /* ingreso desde foro */
                    if (isset($get[3]) && ($get[2] == 'foro')) {
                        $id_foro = $get[3];
                        $rqdc1 = query("SELECT tema FROM cursos_foros WHERE id='$id_foro' ORDER BY id DESC limit 1 ");
                        $rqdc2 = mysql_fetch_array($rqdc1);
                        $tema_foro = $rqdc2['tema'];
                        echo "<script>location.href='" . $dominio . "foro/" . limpiar_enlace($tema_foro) . "/$id_foro.html';</script>";
                    }
                }
                ?>


                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <br />


            </div>
            <div class="col-md-2">
                <style>
                    .wtt-aux{
                        padding-left: 50px;
                    }
                </style>

            </div>
        </div>
        <br>
        <br>

    </section>
</div>                     


<?php
}