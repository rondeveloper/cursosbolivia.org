<div style="padding-top:20px">
    <form action="" method="post">
        <div class="input-group input-group-lg">
        <span class="input-group-addon"><i class="fa fa-search"></i></span>
        <input type="text" name="input-buscar" class="form-control" placeholder="Buscar curso..." value="<?php echo (isset_post('input-buscar')?post('input-buscar'):''); ?>" required/>
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit">BUSCAR</button>
            </span>
        </div>
    </form>
</div>
