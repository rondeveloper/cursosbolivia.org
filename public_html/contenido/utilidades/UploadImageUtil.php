<?php
class UploadImageUtil {
    /* Para subir las imagenes del curso y convertirlas en resoluciones livianas */
    public static function uploadCourseImage($file_name, $id_curso, $number_image, $default_image) {
        global $___path_raiz;
        if (isset_archivo($file_name)) {
            $sizes = [
                'small' => 90,
                'medium' => 380,
                'large' => 730
            ];
            $uploadedFile = $_FILES[$file_name];
            $img_extension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
            $img_name = "cur-".$id_curso."_img-" . $number_image . "." . $img_extension;
            $img_path = $___path_raiz."contenido/imagenes/paginas/" . $img_name;
    
            move_uploaded_file(archivo($file_name), $img_path);
            foreach ($sizes as $sizeName => $newWidth) {
                $ruta_destino =  $___path_raiz."contenido/imagenes/paginas/" . $sizeName . "-" . $img_name;
                UploadImageUtil::resizeImage($img_path, $ruta_destino, $newWidth);
            }
            return $img_name;
        } else {
            return $default_image;
        }
    }
    public static function resizeImage($sourcePath, $targetPath, $newWidth) {
        // Obtener información de la imagen original
        list($width, $height, $type) = getimagesize($sourcePath);
        // Calcular nuevas dimensiones manteniendo la proporción
        $newHeight = ($height / $width) * $newWidth;
        $image = null;
        // Crear imagen desde el archivo original
        switch ($type) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($sourcePath);
                break;
            default:
                echo "Formato de imagen no soportado. ($type) [$sourcePath]";
                return;
        }
        
        // Crear lienzo para la imagen redimensionada
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Redimensionar la imagen
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Guardar la imagen redimensionada
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($resizedImage, $targetPath);
                break;
            case IMAGETYPE_PNG:
                imagepng($resizedImage, $targetPath);
                break;
            case IMAGETYPE_GIF:
                imagegif($resizedImage, $targetPath);
                break;
        }
        
        // Liberar memoria
        imagedestroy($image);
        imagedestroy($resizedImage);
    }
    
}

