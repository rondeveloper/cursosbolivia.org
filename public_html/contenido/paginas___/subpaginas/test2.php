<?php
exit;
/* carga composer autoload */
require_once $___path_raiz . '../vendor/autoload.php';
?>
<div style="padding:70px;">

    <h3>TESTING</h3>

    <hr/>
    <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
    <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
    <hr/>


    <?php
    //SISTsendEmail_DEV("brayan.desteco@gmail.com", "Desarrollo del testing de correo secundario", "Saludos, Se efectua el desarrollo del testing de correo secundario");
    //SISTsendEmail_DEV("brayan.desteco@gmail.com", "Desarrollo del testing de correo secundario", "Saludos, Se efectua el desarrollo del testing de correo secundario");
    echo "SEND EMAIL TESTS 4651654422";
    ?>

    <?php
    if (isset($_COOKIE['token_nav']) && false) {
        $token_nav = $_COOKIE['token_nav'];
        $rqdt1 = query("SELECT id_departamento FROM cursos_suscnav WHERE token='$token_nav' ORDER BY id DESC limit 1 ");
        $rqdt2 = fetch($rqdt1);
        if ($rqdt2['id_departamento'] == '0') {
            ?>

            <style>
                .box-ask-bottom{
                    border-radius: 10px 10px 0px 0px;
                    border: 1px solid #258fad;
                    background: #e4eff7;
                    position: fixed;
                    left: 2%;
                    bottom: 0px;
                    width: 96%;
                    /*            height: 300px;*/
                    height: 0px;
                    z-index: 1;
                    box-shadow: 0px -1px 20px 0px #949494;
                    transition: .5s;
                }
                .ask-bottom{
                    padding: 20px;
                }
                .img1-ask{
                    width: 100%;
                    overflow: hidden;
                    opacity: .8;
                    border-radius: 20%;
                    border: 1px solid #318cb8;
                }
                .ask-close{
                    background: red;
                    font-size: 15pt;
                    color: #FFF;
                    padding: 5px 10px;
                    font-weight: bold;
                    border-radius: 8px;
                    border: 1px solid #FFF;
                    cursor: pointer;
                    box-shadow: 1px 1px 7px 0px #8e8e8e
                }
                .ask-box-dep{
                    border: 1px solid #75c6ec;
                    padding: 10px;
                    margin: 10px;
                }
                .ask-select-d{
                    background: #FFF;
                    margin-bottom: 5px;
                    border-radius: 5px;
                    cursor: pointer;
                    padding: 5px 10px;
                    border: 1px solid #d4d4d4;
                    transition: .5s;
                }
                .ask-select-d:hover{
                    background: #75c6ec;
                    color: #000;
                    transition: .5s;
                }
                .closeask{
                    height: 0px;
                    transition: .5s;
                    border:0px;
                }
                .openask{
                    height: 300px;
                    transition: .4s;
                }
            </style>

            <div class="box-ask-bottom" id="closeask">
                <div class="ask-bottom">
                    <div class="col-md-2 col-xs-4">
                        <img src="contenido/imagenes/images/map-bolivia.jpg" class="img1-ask"/>
                    </div>
                    <div class="col-md-10 col-xs-8">
                        <div class="pull-right ask-close" onclick='$("#closeask").removeClass("openask");
                                $("#closeask").addClass("closeask");'>X</div>

                        <h3 style="color:red;" class="hidden-xs">QUEREMOS NOTIFICARTE OPORTUNAMENTE</h3>
                        <h5 style="color:red;" class="hidden-lg">QUEREMOS NOTIFICARTE OPORTUNAMENTE</h5>
                        <b>ACERCA DE LOS NUEVOS CURSOS DE TU INTER&Eacute;S</b>
                        <div class="ask-box-dep">
                            <p class="text-center"><b style="color:#1b5b77;">Selecciona el departamento donde te interesan recibir notificaciones de cursos</b></p>
                            <div class="row">
                                <?php
                                $rqdd1 = query("SELECT nombre,id FROM departamentos WHERE tipo='1' ORDER BY orden ASC ");
                                while ($rqdd2 = fetch($rqdd1)) {
                                    ?>
                                    <div class="col-md-4 col-xs-6">
                                        <div class="ask-select-d" onclick="ask_selec_departamento('<?php echo $rqdd2['id']; ?>');">
                                            <?php echo $rqdd2['nombre']; ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                setTimeout(function() {
                    $("#closeask").addClass("openask");
                }, 3000);
            </script>
            <script>
                function ask_selec_departamento(id_departamento) {
                    $("#closeask").removeClass("openask");
                    $("#closeask").addClass("closeask");
                    $.ajax({
                        url: 'contenido/paginas/ajax/ajax.ask.ask_selec_departamento.php',
                        data: {id_departamento: id_departamento},
                        type: 'POST',
                        dataType: 'html',
                        success: function(data) {

                        }
                    });
                }
            </script>

            <?php
        }
    }
    ?>




</div>



