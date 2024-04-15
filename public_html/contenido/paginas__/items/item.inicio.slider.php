<section class="carousel carousel-fade slide home-slider" id="c-slide" data-ride="carousel" data-interval="4500" data-pause="false">
    <ol class="carousel-indicators">
        <!-- <li data-target="#c-slide" data-slide-to="0" class="active"></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="1" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="2" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="3" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="4" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="5" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="6" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="7" class=""></li>-->
        <!-- <li data-target="#c-slide" data-slide-to="8" class=""></li>-->
    </ol>
    <div class="carousel-inner">
        <?php
        $rqb1 = query("SELECT imagen FROM cursos_banners WHERE estado='1' ORDER BY id DESC");
        $sw_first = true;
        while ($rqb2 = fetch($rqb1)) {
            $text_active = '';
            if ($sw_first) {
                $text_active = 'active';
                $sw_first = false;
            }
        ?>
            <div class="item <?php echo $text_active; ?>">
                <img src="<?php echo $dominio_www; ?>contenido/imagenes/banners/<?php echo $rqb2['imagen']; ?>" style="width:100%;" class="img-responsive" alt="BANNER">
            </div>
        <?php
        }
        ?>
    </div>
    <a class="left carousel-control animated fadeInLeft" href="<?php echo $dominio; ?>#c-slide" data-slide="prev"><i class="icon-angle-left"></i></a>
    <a class="right carousel-control animated fadeInRight" href="<?php echo $dominio; ?>#c-slide" data-slide="next"><i class="icon-angle-right"></i></a>
</section>