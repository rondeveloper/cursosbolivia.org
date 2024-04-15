<?php
$mensaje = '';
if(isset_post('curso')){
    $id_curso = post('curso');
    $ci = post('ci');

    $rqdp1 = query("SELECT id_proceso_registro FROM cursos_participantes WHERE id_curso='$id_curso' AND ci='$ci' ORDER BY id DESC limit 1 ");
    if(num_rows($rqdp1)==0){
        $rqdca1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso' ORDER BY id DESC limit 1 ");
        $rqdca2 = fetch($rqdca1);
        $titulo_identificador = $rqdca2['titulo_identificador'];
        $mensaje = '<div class="alert alert-warning">
  <strong>AVISO</strong> no se encontro algun participante con CI '.$ci.'.
</div>
<p>Puede registrarse al curso desde el siguiente enlace: <a href="'.$dominio.'registro-curso/'.$titulo_identificador.'.html" class="btn btn-xs btn-info">REGISTRO</a></p>
<br>
';
    }else{
        $rqdp2 = fetch($rqdp1);
        $id_proceso_registro = $rqdp2['id_proceso_registro'];
        $rqdr1 = query("SELECT codigo FROM cursos_proceso_registro WHERE id='$id_proceso_registro' ORDER BY id DESC limit 1 ");
        $rqdr2 = fetch($rqdr1);
        $codigo = $rqdr2['codigo'];
        echo '<script>location.href="'.$dominio.'registro-curso-p5c/'.md5('idr-' . $id_proceso_registro).'/'.$id_proceso_registro.'.html";</script>';
        exit;
    }
}
?>
<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="TituloArea">
                    <h3>REPORTAR PAGO</h3>
                </div>
                <div class="Titulo_texto1" style="min-height: 570px;padding-bottom: 40px;line-height: 2;">
                    <p>Del siguiente listado selecciona el curso al cual deseas reportar el pago, luego ingresa la C.I. con el que te registraste.</p>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <?php echo $mensaje; ?>
                            <form action="" method="post">
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <td>
                                            <br>
                                            <select class="form-control" name="curso">
                                                <?php
                                                $rq1 = query("SELECT titulo,titulo_identificador,id FROM cursos WHERE estado='1' ");
                                                while ($rq2 = fetch($rq1)) {
                                                    ?>
                                                    <option value="<?php echo $rq2['id']; ?>">
                                                        <?php echo $rq2['titulo']; ?>
                                                    </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br>
                                            <input type="number" name="ci" class="form-control" placeholder="Numero de C.I."/>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <br>
                                            <button class="btn btn-block btn-warning">REPORTAR PAGO</button>
                                            <br>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
