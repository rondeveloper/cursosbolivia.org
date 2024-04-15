<?php
class Util {

    public static function fechaFormatoLiteralCompleto($fecha) {
        $dias = array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado");
        $nombredia = $dias[date("w", strtotime($fecha))];
        $dia = date("d", strtotime($fecha));
        $meses = array("none", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $nombremes = $meses[(int) date("m", strtotime($fecha))];
        $anio = date("Y", strtotime($fecha));
        return "$nombredia, $dia de $nombremes de $anio";
    }

    public static function fecha_final_cupon($id_cupon){
        $rqdcp1 = query("SELECT fecha_expiracion FROM cursos_cupones_infosicoes WHERE id='$id_cupon' ORDER BY id DESC limit 1 ");
        $rqdcp2 = fetch($rqdcp1);
        $d_day = date("d", strtotime($rqdcp2['fecha_expiracion']));
        $d_month = date("m", strtotime($rqdcp2['fecha_expiracion']));
        $d_year = date("Y", strtotime($rqdcp2['fecha_expiracion']));
        $mes = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
        return $d_day .' '.$mes[(int)$d_month].' '.$d_year;
    }

    public static function redondeoMontoCodigoControl($monto_a_facturar){
        $monto = (double) $monto_a_facturar;
        $bolivianos = (int) $monto;
        $centavos = round(($monto - (int)$monto) * 100);
        if($centavos>=50){
            $redondeo = $bolivianos + 1;
        } else {
            $redondeo = $bolivianos;
        }
        return $redondeo;
    }
    
}
