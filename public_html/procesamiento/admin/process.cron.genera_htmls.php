<?php

session_start();
include_once '../../contenido/configuracion/config.php';
include_once '../../contenido/configuracion/funciones.php';
include_once '../../contenido/librerias/phpmailer/vendor/autoload.php';
$mysqli = mysqli_connect($env_hostname,$env_username,$env_password,$env_database);
//header("Access-Control-Allow-Origin: ".trim($dominio_admin,'/'));

/* page */
$page = '';
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

/* estado actual */
$fp = fopen("../../contenido/configuracion/sw_ghtmls.dat", "r");
$estado_actual = (int) fgets($fp);
fclose($fp);

/* desactivar */
$archivo = fopen("../../contenido/configuracion/sw_ghtmls.dat", "w");
fwrite($archivo, '0');
fclose($archivo);

/* variables iniciales */
$ruta_htmls = '../../contenido/htmls/';
$prefijo_de_archivo = 'DAT_';
$formato = '.txt';


switch ($page) {
    case 'activados':
        /* genera : activados */
        //elimina_htmls_cursos();
        genera_activados(0);
        break;
    case 'curso-individual':
        /* genera : curso-individual activado */
        genera_activados((int) $_GET['id_curso']);
        break;
    case 'inicio':
        /* genera : index */
        genera_index();
        break;
    case 'elimina-htmls-cursos':
        /* genera : index */
        elimina_htmls_cursos();
        break;
    case 'elimina-htmls-cursos-no-activos':
        /* genera : index */
        elimina_htmls_cursos_noactivos();
        break;
    case 'cursos-por-ciudad':
        /* genera : activados cursos-por-ciudad */
        genera_cursos_por_ciudad();
        break;
    default:
        /* genera : index */
        genera_index();
        /* genera : activados */
        genera_activados(0);
        break;
}

/* activar */
$archivo = fopen("../../contenido/configuracion/sw_ghtmls.dat", "w");
fwrite($archivo, $estado_actual);
fclose($archivo);

echo "OK";


/* genera_index */

function genera_index() {
    global $ruta_htmls, $prefijo_de_archivo, $formato, $dominio;
    /* genera : index */
    $nombre_denominador = 'index';
    $ruta_final_archivo = $ruta_htmls . $prefijo_de_archivo . $nombre_denominador . $formato;
    if (file_exists($ruta_final_archivo)) {
        unlink($ruta_final_archivo);
    }
    $html = file_get_contents($dominio);
    //$compress = TinyMinify::html($html);
    $compress = $html;
    /* crea archivo fisico */
    $archivo = fopen($ruta_final_archivo, "w");
    fwrite($archivo, $compress);
    fclose($archivo);
    echo "Index generado<br/>";
}

/* genera_activados */

function genera_activados($id_curso_especifico) {
    global $ruta_htmls, $prefijo_de_archivo, $formato, $dominio;
    if (false) {
        echo "Funcion desactivada<br/>";
    } else {
        /* elimina htmls cursos existentes */
        //elimina_htmls_cursos();
        /* listado de cursos activos */
        if ($id_curso_especifico == 0) {
            $rqc1 = query("SELECT titulo_identificador FROM cursos WHERE estado='1' ORDER BY id DESC limit 200 ");
        } else {
            $rqc1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso_especifico' ");
        }
        //$rqc1 = query("SELECT titulo_identificador FROM cursos WHERE estado='1' ");
        $cnt = 0;
        while ($rqc2 = fetch($rqc1)) {
            $titulo_identificador = $rqc2['titulo_identificador'];
            /* genera : activados */
            $nombre_denominador = $titulo_identificador;
            $ruta_final_archivo = $ruta_htmls . $prefijo_de_archivo . $nombre_denominador . $formato;
            /*
            if (file_exists($ruta_final_archivo)) {
                //unlink($ruta_final_archivo);
                echo "Existe :: $nombre_denominador <br/>";
            } else {
                $html = file_get_contents($dominio . $titulo_identificador . ".html");
                $compress = TinyMinify::html($html);
                / * crea archivo fisico * /
                $archivo = fopen($ruta_final_archivo, "w");
                fwrite($archivo, $compress);
                fclose($archivo);
                $cnt++;
                echo ":: $nombre_denominador [CREADO]<br/>";
            }
            */
            if (file_exists($ruta_final_archivo)) {
                unlink($ruta_final_archivo);
                echo "Existe :: $nombre_denominador (ELIMINADO) <br/>";
            }
                $html = file_get_contents($dominio . $titulo_identificador . ".html");
                //$compress = TinyMinify::html($html);
                $compress = $html;

                //*echo "(($compress))".strlen($html)."-".strlen($compress);exit;
                /* crea archivo fisico */
                $archivo = fopen($ruta_final_archivo, "w");
                fwrite($archivo, $compress);
                fclose($archivo);
                $cnt++;
                echo ":: $nombre_denominador [CREADO]<br/>";
            
        }
        echo "<br/>Cursos activados generados [$cnt]<br/>";
    }
}

function genera_activados_PRE($id_curso_especifico) {
    global $ruta_htmls, $prefijo_de_archivo, $formato,$dominio;
    if (false) {
        echo "Funcion desactivada<br/>";
    } else {
        /* elimina htmls cursos existentes */
        //elimina_htmls_cursos();
        /* listado de cursos activos */
        if ($id_curso_especifico == 0) {
            $rqc1 = query("SELECT titulo_identificador FROM cursos WHERE estado='1' ORDER BY rand() limit 3 ");
        } else {
            $rqc1 = query("SELECT titulo_identificador FROM cursos WHERE id='$id_curso_especifico' ");
        }
        //$rqc1 = query("SELECT titulo_identificador FROM cursos WHERE estado='1' ");
        $cnt = 0;
        while ($rqc2 = fetch($rqc1)) {
            $titulo_identificador = $rqc2['titulo_identificador'];
            /* genera : activados */
            $nombre_denominador = $titulo_identificador;
            $ruta_final_archivo = $ruta_htmls . $prefijo_de_archivo . $nombre_denominador . $formato;
            if (file_exists($ruta_final_archivo)) {
                unlink($ruta_final_archivo);
            }
            $html = file_get_contents($dominio . $titulo_identificador . ".html");
            //$compress = TinyMinify::html($html);
            $compress = $html;
            /* crea archivo fisico */
            $archivo = fopen($ruta_final_archivo, "w");
            fwrite($archivo, $compress);
            fclose($archivo);
            $cnt++;
            echo ":: $nombre_denominador [CREADO]<br/>";
        }
        echo "Cursos activados generados [$cnt]<br/>";
    }
}

function genera_cursos_por_ciudad() {
    global $ruta_htmls, $prefijo_de_archivo, $formato, $dominio;
    /* listado de departamentos */
    $rqc1 = query("SELECT titulo_identificador FROM departamentos WHERE estado='1' ORDER BY rand() limit 3 ");
    //$rqc1 = query("SELECT titulo_identificador FROM cursos WHERE estado='1' ");
    $cnt = 0;
    while ($rqc2 = fetch($rqc1)) {
        $titulo_identificador = $rqc2['titulo_identificador'];
        /* genera : activados */
        $nombre_denominador = $titulo_identificador;
        $ruta_final_archivo = $ruta_htmls . $prefijo_de_archivo . $nombre_denominador . $formato;
        if (file_exists($ruta_final_archivo)) {
            unlink($ruta_final_archivo);
        }
        $html = file_get_contents($dominio . $titulo_identificador . ".html");
        //$compress = TinyMinify::html($html);
        $compress = $html;
        /* crea archivo fisico */
        $archivo = fopen($ruta_final_archivo, "w");
        fwrite($archivo, $compress);
        fclose($archivo);
        $cnt++;
        echo ":: $nombre_denominador [CREADO]<br/>";
    }
    echo "Cursos activados generados [$cnt]<br/>";
}

/* elimina_htmls_cursos */

function elimina_htmls_cursos() {
    global $ruta_htmls, $prefijo_de_archivo, $formato;
    $directorio = opendir($ruta_htmls);
    $cnt = 0;
    while ($archivo = readdir($directorio)) {
        $ruta_final_archivo = $ruta_htmls . $archivo;
        if (!is_dir($archivo) && ($archivo !== $prefijo_de_archivo . 'index' . $formato) && file_exists($ruta_final_archivo)) {
            unlink($ruta_final_archivo);
            $cnt++;
        }
    }
    echo "Html cursos (no activos) eliminados [$cnt]<br/>";
}

/* elimina_htmls_cursos_noactivos */

function elimina_htmls_cursos_noactivos() {
    global $ruta_htmls, $prefijo_de_archivo, $formato;
    $directorio = opendir($ruta_htmls);
    $cnt = 0;
    while ($archivo = readdir($directorio)) {
        /* titulo_identificador */
        $tit_identif = str_replace(array($prefijo_de_archivo, $formato), '', $archivo);
        $rqveca1 = query("SELECT id FROM cursos WHERE estado='1' AND titulo_identificador='$tit_identif' ORDER BY id DESC limit 1 ");
        $ruta_final_archivo = $ruta_htmls . $archivo;
        if (!is_dir($archivo) && ($archivo !== $prefijo_de_archivo . 'index' . $formato) && num_rows($rqveca1) == 0 && file_exists($ruta_final_archivo)) {
            unlink($ruta_final_archivo);
            $cnt++;
        }
    }
    echo "Html cursos eliminados [$cnt]<br/>";
}

/* CLASES UTILIZADAS */

class TinyHtmlMinifier {

    function __construct($options) {
        $this->options = $options;
        $this->output = '';
        $this->build = [];
        $this->skip = 0;
        $this->skipName = '';
        $this->head = false;
        $this->elements = [
            'skip' => [
                'code',
                'pre',
                'textarea',
                'script'
            ],
            'inline' => [
                'b',
                'big',
                'i',
                'small',
                'tt',
                'abbr',
                'acronym',
                'cite',
                'code',
                'dfn',
                'em',
                'kbd',
                'strong',
                'samp',
                'var',
                'a',
                'bdo',
                'br',
                'img',
                'map',
                'object',
                'q',
                'span',
                'sub',
                'sup',
            ],
            'hard' => [
                '!doctype',
                'body',
                'html',
            ]
        ];
    }

    // Run minifier
    function minify($html) {
        $html = $this->removeComments($html);

        $rest = $html;
        while (!empty($rest)) :

            $parts = explode('<', $rest, 2);
            $this->walk($parts[0]);
            $rest = (isset($parts[1])) ? $parts[1] : '';
        endwhile;
        return $this->output;
    }

    // Walk trough html
    function walk(&$part) {
        $tag_parts = explode('>', $part);
        $tag_content = $tag_parts[0];

        if (!empty($tag_content)) {
            $name = $this->findName($tag_content);
            $element = $this->toElement($tag_content, $part, $name);
            $type = $this->toType($element);
            if ($name == 'head') {
                $this->head = ($type == 'open') ? true : false;
            }
            $this->build[] = [
                'name' => $name,
                'content' => $element,
                'type' => $type
            ];
            $this->setSkip($name, $type);
            if (!empty($tag_content)) {
                $content = (isset($tag_parts[1])) ? $tag_parts[1] : '';
                if ($content !== '') {
                    $this->build[] = [
                        'content' => $this->compact($content, $name, $element),
                        'type' => 'content'
                    ];
                }
            }
            $this->buildHtml();
        }
    }

    // Remove comments
    function removeComments($content = '') {
        return preg_replace('/<!--(.|\s)*?-->/', '', $content);
    }

    // Check if string contains string
    function contains($needle, $haystack) {
        return strpos($haystack, $needle) !== false;
    }

    // Return type of element
    function toType($element) {
        $type = (substr($element, 1, 1) == '/') ? 'close' : 'open';
        return $type;
    }

    // Create element
    function toElement($element, $noll, $name) {
        $element = $this->stripWhitespace($element);
        $element = $this->addChevrons($element, $noll);
        $element = $this->removeSelfSlash($element);
        $element = $this->removeMeta($element, $name);
        return $element;
    }

    // Remove unneeded element meta
    function removeMeta($element, $name) {
        if ($name == 'style') {
            $element = str_replace([
                ' type="text/css"',
                "' type='text/css'"
                    ], ['', ''], $element);
        } elseif ($name == 'script') {
            $element = str_replace([
                ' type="text/javascript"',
                " type='text/javascript'"
                    ], ['', ''], $element);
        }
        return $element;
    }

    // Strip whitespace from element
    function stripWhitespace($element) {
        if ($this->skip == 0) {
            $element = preg_replace('/\s+/', ' ', $element);
        }
        return $element;
    }

    // Add chevrons around element
    function addChevrons($element, $noll) {
        $char = ($this->contains('>', $noll)) ? '>' : '';
        $element = '<' . $element . $char;
        return $element;
    }

    // Remove unneeded self slash
    function removeSelfSlash($element) {
        if (substr($element, -3) == ' />') {
            $element = substr($element, 0, -3) . '>';
        }
        return $element;
    }

    // Compact content
    function compact($content, $name, $element) {
        if ($this->skip != 0) {
            $name = $this->skipName;
        } else {
            $content = preg_replace('/\s+/', ' ', $content);
        }
        if (
                $this->isSchema($name, $element) &&
                !empty($this->options['collapse_json_ld'])
        ) {
            return json_encode(json_decode($content));
        } if (in_array($name, $this->elements['skip'])) {
            return $content;
        } elseif (
                in_array($name, $this->elements['hard']) ||
                $this->head
        ) {
            return $this->minifyHard($content);
        } else {
            return $this->minifyKeepSpaces($content);
        }
    }

    function isSchema($name, $element) {
        if ($name != 'script')
            return false;
        $element = strtolower($element);
        if ($this->contains('application/ld+json', $element))
            return true;
        return false;
    }

    // Build html
    function buildHtml() {
        foreach ($this->build as $build) {
            if (!empty($this->options['collapse_whitespace'])) {

                if (strlen(trim($build['content'])) == 0)
                    continue;

                elseif ($build['type'] != 'content' && !in_array($build['name'], $this->elements['inline']))
                    trim($build['content']);
            }
            $this->output .= $build['content'];
        }
        $this->build = [];
    }

    // Find name by part
    function findName($part) {
        $name_cut = explode(" ", $part, 2)[0];
        $name_cut = explode(">", $name_cut, 2)[0];
        $name_cut = explode("\n", $name_cut, 2)[0];
        $name_cut = preg_replace('/\s+/', '', $name_cut);
        $name_cut = strtolower(str_replace('/', '', $name_cut));
        return $name_cut;
    }

    // Set skip if elements are blocked from minification
    function setSkip($name, $type) {
        foreach ($this->elements['skip'] as $element) {
            if ($element == $name && $this->skip == 0) {
                $this->skipName = $name;
            }
        }
        if (in_array($name, $this->elements['skip'])) {
            if ($type == 'open') {
                $this->skip++;
            }
            if ($type == 'close') {
                $this->skip--;
            }
        }
    }

    // Minify all, even spaces between elements
    function minifyHard($element) {
        $element = preg_replace('!\s+!', ' ', $element);
        $element = trim($element);
        return trim($element);
    }

    // Strip but keep one space
    function minifyKeepSpaces($element) {
        return preg_replace('!\s+!', ' ', $element);
    }

}

class TinyMinify {

    static function html($html, $options = []) {
        $minifier = new TinyHtmlMinifier($options);
        return $minifier->minify($html);
    }

}
