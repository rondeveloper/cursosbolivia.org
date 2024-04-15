<?php
/* datos de configuracion */
$enum_id_interfaz = $__CONFIG_MANAGER->getEnumValue('enum_id_interfaz');

/* slider */
include_once "contenido/paginas/items/item.inicio.slider.php";
?>

<div class="gris">
    <section class="container container-home">
        <style>
            .cont-short-couses {
                clear: both;
                max-height: 650px;
                overflow: hidden;
            }
            .cover-short-courses {
                background: white;
                box-shadow: 0px -16px 20px 20px white;
                height: 100px;
                text-align: center;
                padding: 30px;
                position: absolute;
                width: 1160px;
                max-width: 100%;
                margin-top: -100px;
                opacity: .95;
                cursor: pointer;
                border-bottom: 1px solid #258fad;
                transition: .3s;
            }
            .cover-short-courses:hover {
                opacity: .99;
                border-bottom: 3px solid #258fad;
                transition: .3s;
            }
            .cover-short-courses:hover .cover-short-courses-label {
                text-decoration: underline;
                text-decoration-color: #DDD;
            }
            .cover-short-courses-label {
                font-size: 25pt;
                color: #258fad;
            }
        </style>

        <?php
        /* buscador */
        include_once "contenido/paginas/items/item.inicio.buscador.php";

        if ($enum_id_interfaz == 2) {
            /* cursos virtuales , 2 columnas */
            include_once "contenido/paginas/items/item.inicio.cursos_virtuales_dos_columnas.php";
        } else {
            /* cursos presenciales */
            include_once "contenido/paginas/items/item.inicio.cursos_presenciales.php";

            /* cursos virtuales */
            include_once "contenido/paginas/items/item.inicio.cursos_virtuales.php";
        }
        ?>
        <br>
    </section>
</div>

<?php
/* validador de certificados */
include_once "contenido/paginas/items/item.inicio.validador_certificados.php";
?>


<script>
    function actualiza_ciudades() {
        $("#select_ciudad").html('<option>Cargando...</option>');
        var id_departamento = $("#select_departamento").val();
        $.ajax({
            url: 'contenido/paginas/ajax/ajax.actualiza_ciudades.php',
            data: {
                id_departamento: id_departamento
            },
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                $("#select_ciudad").html(data);
            }
        });
    }
</script>

<script>
    function copyToClipboard(cod) {
        alert('Se ha copiado la informacion del curso al portapapeles (Ctrl + C)');
        var container = document.createElement('div');
        container.innerHTML = document.getElementById("contentInfo-" + cod).innerHTML;
        var activeSheets = Array.prototype.slice.call(document.styleSheets).filter(function(sheet) {
            return !sheet.disabled;
        });
        document.body.appendChild(container);
        window.getSelection().removeAllRanges();
        var range = document.createRange();
        range.selectNode(container);
        window.getSelection().addRange(range);
        document.execCommand('copy');
        document.body.removeChild(container);
    }
</script>


<?php

function fecha_curso_PRE($fecha)
{
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    $anio = date("Y", strtotime($fecha));
    return "$nombredia, $dia de $nombremes de $anio";
}
function fecha_curso($fecha)
{
    $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
    $nombredia = $dias[date("w", strtotime($fecha))];
    $dia = date("d", strtotime($fecha));
    $meses = array("none", "Ene.", "Febr.", "Mar.", "Abr.", "May.", "Jun.", "Jul.", "Ago.", "Sept.", "Oct.", "Nov.", "Dic.");
    $nombremes = $meses[(int) date("m", strtotime($fecha))];
    return "$nombredia $dia de $nombremes";
}

function fecha_corta($data)
{
    $d = date("d", strtotime($data));
    $m = date("m", strtotime($data));
    $me = array('none', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    return $d . " de " . $me[(int) $m];
}
