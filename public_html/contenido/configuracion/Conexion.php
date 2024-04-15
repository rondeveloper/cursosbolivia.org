<?php
class Conexion {
    private $hostname;
    private $username;
    private $password;
    private $database;
    private $connect;

    function __construct() {

        $this->hostname = $_ENV['DDBB_HOST'];
        $this->username = $_ENV['DDBB_USER'];
        $this->password = $_ENV['DDBB_PASSWORD'];
        $this->database = $_ENV['DDBB_NAME'];

        $connectionString = "mysql:hos=".$this->hostname.";dbname=".$this->database.";charset=utf8";

        try {
            $this->connect = new PDO($connectionString,$this->username,$this->password);
            $this->connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "ERROR: " . $e->getMessage();
        }
        
    }

    public function connect(){
        return $this->connect;
    }
}
