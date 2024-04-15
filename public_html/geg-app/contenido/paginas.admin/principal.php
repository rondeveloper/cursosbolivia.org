<?php
/* variable de inicio */
if (isset_get('seccion')) {

    /* manejo de parametros get */
    $get = explode('/', str_replace('.adm', '', get('seccion')));
    for ($cn_ge = count($get); $cn_ge > 0; $cn_ge--) {
        $get[$cn_ge] = $get[$cn_ge - 1];
    }

    if (file_exists('contenido/paginas.admin/loadpages/lp.ajax__' . $get[1] . '.php')) {
        $ar1 = $get;
        $page = $ar1[1];
        unset($ar1[0]);
        unset($ar1[1]);
        $data = implode('/', $ar1);
        /* postdata */
        $postdata = '';
        if(isset($_POST)){
            $postdata = base64_encode(json_encode($_POST));
        }
        ?>
        <script>
            load_page('<?php echo $page; ?>', '<?php echo $data; ?>', '<?php echo $postdata; ?>');
        </script>
        <?php
        //include_once 'contenido/paginas.admin/loadpages/loadpage.ajax.' . $get[1] . '.php';
    } elseif (file_exists('contenido/paginas.admin/subpaginas.admin/' . $get[1] . '.php')) {
        include_once 'contenido/paginas.admin/subpaginas.admin/' . $get[1] . '.php';
    } elseif (file_exists('contenido/paginas.admin/' . $get[1] . '/' . $get[2] . '.php')) {
        include_once 'contenido/paginas.admin/' . $get[1] . '/' . $get[2] . ".php";
    } else {
        echo "<h4>Acceso denegado!</h4>";
    }
} else {
    //include_once 'contenido/paginas.admin/inicio.php';
    ?>
    <script>
        load_page('inicio', '');
    </script>
    <?php
}


