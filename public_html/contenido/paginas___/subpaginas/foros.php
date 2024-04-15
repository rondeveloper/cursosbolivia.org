
<div style="height:140px"></div>
<div class="wrapsemibox">
    <section class="container">
        <div style="height:10px"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="TituloArea">
                    <h3>FOROS DE DISCUSI&Oacute;N</h3> 
                </div>

                <div style="padding:2px;">

                    <div class="form-group">
                        <div class="col-sm-5">
                            <input class="form-control" placeholder="Busca el tema de discusi&oacute;n que te interesa..."/>	
                        </div>
                        <div class="col-sm-5">
                            <select class="form-control string" name="">
                                <option value="">Todas las categorias</option>
                            </select>	
                        </div>      
                        <div class="col-sm-2">
                            <input type="submit" class="btn btn-danger" value="BUSCAR"/>	
                        </div>
                    </div>

                    <table class="table table-hover calendar">
                        <thead>
                            <tr>
                                <th>
                                    Tema de discusi&oacute;n
                                </th>
                                <th>
                                    Categoria
                                </th>
                                <th>
                                    Fecha de publicaci&oacute;n
                                </th>  
<!--                                <th>
                                    &Uacute;ltima respuesta
                                </th> -->
                                <th>
                                    Cantidad de respuestas
                                </th> 
                                <th>
                                    -
                                </th>                    
                            </tr>
                        </thead>
                        <tbody id="lista_curso">

                            <?php
                            $rqf1 = query("SELECT f.id,f.tema,f.fecha_registro,f.fecha_ultima_respuesta,f.cnt_respuestas,c.titulo,(select count(*) from cursos_foros_respuestas where id_foro=f.id)cnt_respuestas_2 FROM cursos_foros f INNER JOIN cursos_categorias c ON f.id_categoria=c.id ORDER BY f.id DESC ");
                            while ($rqf2 = fetch($rqf1)) {
                                $id_foro = $rqf2['id'];
                                $tema_foro = $rqf2['tema'];
                                $categoria_foro = $rqf2['titulo'];
                                $fecha_registro_foro = date("d / M / Y",strtotime($rqf2['fecha_registro']));
                                $fecha_ultima_respuesta_foro = $rqf2['fecha_ultima_respuesta'];
                                $cnt_respuestas_foro = $rqf2['cnt_respuestas_2'];
                                ?>
                                <tr>
                                    <td class="td_color2">
                                        <a href="foro/<?php echo substr(limpiar_enlace(str_replace('�','',$tema_foro)),0,75); ?>/<?php echo $id_foro; ?>.html" title="<?php echo $tema_foro; ?>">
                                            <?php echo $tema_foro; ?>
                                        </a>
                                    </td>
                                    <td class="td_color">
                                        <?php echo $categoria_foro; ?>
                                    </td>
                                    <td class="td_color">
                                        <?php echo $fecha_registro_foro; ?>
                                    </td>  
<!--                                    <td class="td_color">
                                        <?php //echo $fecha_ultima_respuesta_foro; ?>
                                    </td>-->
                                    <td class="td_color">
                                        <?php echo $cnt_respuestas_foro; ?>
                                    </td>
                                    <td class="td_color">
                                        <a href="foro/<?php echo substr(limpiar_enlace(str_replace('�','',$tema_foro)),0,75); ?>/<?php echo $id_foro; ?>.html" style="text-decoration:underline;">
                                            Ver discusi&oacute;n
                                        </a>
                                    </td>                                       
                                </tr>
                                <?php
                            }
                            ?>

                        </tbody>
                    </table>

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


