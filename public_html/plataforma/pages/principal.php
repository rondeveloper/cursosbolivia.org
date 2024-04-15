<?php
/* principal */
if (isset($get[1])) {
    if (file_exists('pages/subpages/' . $get[1] . '.php')) {
        include_once 'pages/subpages/' . $get[1] . '.php';
    } else {
        include_once 'pages/inicio.php';
    }
} else {
    include_once 'pages/inicio.php';
}
