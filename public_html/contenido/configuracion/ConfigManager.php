<?php
class ConfigManager {
    protected $arrayVariables = array();
    protected $conexion;

    function __construct(){
        global $dominio_www;
        $this->conexion  = (new Conexion())->connect();
        $sql = "SELECT codigo,valor,tipo FROM configuracion_sistema ";
        $execute = $this->conexion->query($sql);
        $request = $execute->fetchAll(PDO::FETCH_ASSOC);

        foreach ($request as $key => $value) {
            if($value['tipo']==4){
                $this->arrayVariables[$value['codigo']] = explode('_',$value['valor'])[0];    
            }elseif($value['tipo']==3){
                $this->arrayVariables[$value['codigo']] = $dominio_www.'contenido/imagenes/'.$value['valor'];    
            }elseif($value['tipo']==2){
                $this->arrayVariables[$value['codigo']] = $value['valor']=='1'? true : false; 
            }else{
                $this->arrayVariables[$value['codigo']] = $value['valor'];  
            }
        }
    }

    function getData($codigo){
        if(array_key_exists($codigo, $this->arrayVariables)){
            return $this->arrayVariables[$codigo];
        }else{
            $sql = "SELECT id FROM configuracion_sistema WHERE codigo = ? LIMIT 1 ";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute([$codigo]);
            if ($sentencia->rowCount() <= 0 && $codigo != "") {
                $tipo = 1;
                $valor = "";
                $descripcion = "[NUEVO] Sin descripcion";
                $sql = "INSERT INTO configuracion_sistema (codigo,valor,descripcion,tipo) VALUES (:codigo,:valor,:descripcion,:tipo)";
                $sentencia = $this->conexion->prepare($sql);
                $sentencia->bindParam(':codigo',$codigo,PDO::PARAM_STR);
                $sentencia->bindParam(':valor',$valor,PDO::PARAM_STR);
                $sentencia->bindParam(':descripcion',$descripcion,PDO::PARAM_STR);
                $sentencia->bindParam(':tipo',$tipo,PDO::PARAM_STR);
                $sentencia->execute();
                $this->arrayVariables[$codigo] = "";
            }
            return "";
        }
    }

    function getSw($codigo){
        if(array_key_exists($codigo, $this->arrayVariables)){
            return $this->arrayVariables[$codigo];
        }else{
            $sql = "SELECT id FROM configuracion_sistema WHERE codigo = ? LIMIT 1 ";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute([$codigo]);
            if ($sentencia->rowCount() <= 0 && $codigo != "") {
                $tipo = 2;
                $valor = "0";
                $descripcion = "[NUEVO][SW] Sin descripcion";
                $sql = "INSERT INTO configuracion_sistema (codigo,valor,descripcion) VALUES (:codigo,:valor,:descripcion,:tipo)";
                $sentencia = $this->conexion->prepare($sql);
                $sentencia->bindParam(':codigo',$codigo,PDO::PARAM_STR);
                $sentencia->bindParam(':valor',$valor,PDO::PARAM_STR);
                $sentencia->bindParam(':descripcion',$descripcion,PDO::PARAM_STR);
                $sentencia->bindParam(':tipo',$tipo,PDO::PARAM_STR);
                $sentencia->execute();
                $this->arrayVariables[$codigo] = false;
            }
            return false;
        }
    }

    function getImg($codigo){
        if(array_key_exists($codigo, $this->arrayVariables)){
            return $this->arrayVariables[$codigo];
        }else{
            $sql = "SELECT id FROM configuracion_sistema WHERE codigo = ? LIMIT 1 ";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute([$codigo]);
            if ($sentencia->rowCount() <= 0 && $codigo != "") {
                $tipo = 3;
                $valor = "";
                $descripcion = "[NUEVO][SW] Sin descripcion";
                $sql = "INSERT INTO configuracion_sistema (codigo,valor,descripcion) VALUES (:codigo,:valor,:descripcion,:tipo)";
                $sentencia = $this->conexion->prepare($sql);
                $sentencia->bindParam(':codigo',$codigo,PDO::PARAM_STR);
                $sentencia->bindParam(':valor',$valor,PDO::PARAM_STR);
                $sentencia->bindParam(':descripcion',$descripcion,PDO::PARAM_STR);
                $sentencia->bindParam(':tipo',$tipo,PDO::PARAM_STR);
                $sentencia->execute();
                $this->arrayVariables[$codigo] = "";
            }
            return "";
        }
    }

    function getEnumValue($codigo){
        if(array_key_exists($codigo, $this->arrayVariables)){
            return $this->arrayVariables[$codigo];
        }else{
            $sql = "SELECT id FROM configuracion_sistema WHERE codigo = ? LIMIT 1 ";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute([$codigo]);
            if ($sentencia->rowCount() <= 0 && $codigo != "") {
                $tipo = 4;
                $valor = "1_1";
                $descripcion = "[NUEVO][ENUM] Sin descripcion";
                $sql = "INSERT INTO configuracion_sistema (codigo,valor,descripcion) VALUES (:codigo,:valor,:descripcion,:tipo)";
                $sentencia = $this->conexion->prepare($sql);
                $sentencia->bindParam(':codigo',$codigo,PDO::PARAM_STR);
                $sentencia->bindParam(':valor',$valor,PDO::PARAM_STR);
                $sentencia->bindParam(':descripcion',$descripcion,PDO::PARAM_STR);
                $sentencia->bindParam(':tipo',$tipo,PDO::PARAM_STR);
                $sentencia->execute();
                $this->arrayVariables[$codigo] = "1";
            }
            return "1";
        }
    }
}
