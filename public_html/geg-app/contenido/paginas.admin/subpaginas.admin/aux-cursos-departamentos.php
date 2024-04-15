<?php
echo "Cargando...";
/* agregar-administrador */
if (isset_post('actualizar-imagen')) {
    if (isset_archivo('imagen')) {
        $id_departamento = post('id_departamento');
        $imagen = 'BD' . $id_departamento . '-' . str_replace("'", "", archivoName('imagen'));
        move_uploaded_file(archivo('imagen'), "contenido/imagenes/departamentos/$imagen");
        query("UPDATE departamentos SET img_banner='$imagen' WHERE id='$id_departamento' LIMIT 1 ");
    }
}
echo "<script>location.href='cursos-departamentos.adm';</script>";


