<?php

/* principal */
if (isset($dir[1]) && false) {
    if (file_exists('contenido/paginas/subpaginas/' . $dir[1] . '.php')) {
        include_once 'contenido/paginas/subpaginas/' . $dir[1] . '.php';
    } else {
        //include_once 'contenido/paginas/subpaginas/cursos.php';
        include_once 'contenido/paginas/subpaginas/ingreso-de-usuarios.php';
    }
} elseif (isset($get[1])) {
    /* listado-2 por departamento */
    if (isset($get[1]) && (substr($get[1], 0, 10) == 'cursos-en-')) {
        if (isset($get[2])) {
            $get[3] = $get[2];
        }
        $get[2] = str_replace('cursos-en-', '', $get[1]);
        $get[1] = 'cursos-por-ciudad';
    }

    if (file_exists('contenido/paginas/subpaginas/' . $get[1] . '.php')) {
        include_once 'contenido/paginas/subpaginas/' . $get[1] . '.php';
    } else {
        //include_once 'contenido/paginas/subpaginas/cursos.php';
        include_once 'contenido/paginas/subpaginas/ingreso-de-usuarios.php';
    }
} else {
    //include_once 'contenido/paginas/subpaginas/cursos.php';
    include_once 'contenido/paginas/subpaginas/ingreso-de-usuarios.php';
}
