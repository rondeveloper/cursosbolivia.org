
<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="TituloArea">
                    <h3>REGISTRARSE</h3>
                </div>
                <div class="Titulo_texto1" style="min-height: 570px;padding-bottom: 40px;line-height: 2;">
                    <p>Del siguiente listado selecciona el curso al cual deseas registrarte como participante:</p>
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <td>
                                        <br>
                                        <select class="form-control" id="cur">
                                            <?php
                                            $rq1 = query("SELECT titulo,titulo_identificador FROM cursos WHERE estado='1' ");
                                            while ($rq2 = fetch($rq1)) {
                                                ?>
                                                <option value="<?php echo $rq2['titulo_identificador']; ?>">
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
                                        <button class="btn btn-block btn-info" onclick="registrar();">REGISTRAME</button>
                                        <br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function registrar(){
        var cur = $("#cur").val();
        location.href="<?php echo $dominio; ?>registro-curso/"+cur+".html";
    }
</script>