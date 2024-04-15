<?php
/* variable de inicio */
if (isset_get('seccion')) {
    if (file_exists('pages/loadpages/lp.ajax__' . $get[1] . '.php')) {
        $ar1 = $get;
        $page = $ar1[1];
        unset($ar1[0]);
        unset($ar1[1]);
        $data = implode('/', array_reverse($ar1));
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
    } elseif (file_exists('pages/subpages/' . $get[1] . '.php')) {
        include_once 'pages/subpages/' . $get[1] . '.php';
    } elseif (file_exists('pages/' . $get[1] . '/' . $get[2] . '.php')) {
        include_once 'pages/' . $get[1] . '/' . $get[2] . ".php";
    } else {
        echo "<h4>ACCESO DENEGADO</h4>";
    }
} else {
    ?>
    <script>
        load_page('inicio', '');
    </script>
    <?php
}
