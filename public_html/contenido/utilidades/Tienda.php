<?php
class Tienda {

    public static function getCursos($id_curso) {
        Tienda::checkCarrito();
        $rq1 = query("SELECT * FROM administradores ORDER BY id DESC LIMIT 2");
        return num_rows($rq1)."<<<<================";
    }

    public static function agregarAlCarrito($id_curso) {
        Tienda::checkCarrito();
        $rq1 = query("SELECT * FROM administradores ORDER BY id DESC LIMIT 2");
        return num_rows($rq1)."<<<<================";
    }

    private function checkCarrito() {
        if(!isset($_SESSION['cur-tienda'])){
            $_SESSION['cur-tienda'] = array();
        }
    }
}
