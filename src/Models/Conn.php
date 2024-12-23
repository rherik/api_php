<?php
namespace App\Models;

class Conn{
    protected static $connection;

    public static function getConnection(){
        if(!self::$connection){
            $host = "24.199.95.139";
            $user = "root";
            $password = "php123";
            $database = "herik_crud";
        
        self::$connection = new \mysqli($host, $user, $password);
        if (self::$connection->connect_error) {
            throw new \Exception("Erro ao conectar ao MySQL: " . self::$connection->connect_error);
        }
        $createDbQuery = "CREATE DATABASE IF NOT EXISTS `$database`";
            if (!self::$connection->query($createDbQuery)) {
                throw new \Exception("Erro ao criar o banco de dados: " . self::$connection->error);
            }
            if (!self::$connection->select_db($database)) {
                throw new \Exception("Erro ao selecionar o banco de dados: " . self::$connection->error);
            }
        }
    return self::$connection;
    }
}