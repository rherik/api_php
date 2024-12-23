<?php
namespace App\Models;

use App\Models\Conn;

class User extends Conn{
    public static function createTable(){
        $mysqli = self::getConnection();

        $query = "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                salario VARCHAR(100) NOT NULL,
                departamento VARCHAR(100) NOT NULL,
                projeto VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        if(!$mysqli->query($query)){
            throw new \Exception("Erro ao criar a tabela: " . $mysqli->error);
        }
    }
    public static function save(array $data){
        self::createTable();
        
        $mysqli = self::getConnection();

        $stmt = $mysqli->prepare(
            //name, salario, departamento e projeto
            "INSERT INTO users (name, salario, departamento, projeto) 
            VALUES (?, ?, ?, ?)"
        );
        if(!$stmt){
            throw new \Exception("Erro ao preparar consulta: " . $mysqli->error);
        }
        $stmt->bind_param(
            "ssss",
            $data['name'],
            $data['salario'],
            $data['departamento'],
            $data['projeto']
        );
        if($stmt->execute()){
            return $mysqli->insert_id > 0;
        }else{
            throw new \Exception("Erro ao executar a consulta: " . $stmt->error);
        }
    }
    public static function find(int|string $id){
        $mysqli = self::getConnection();
        $stmt = $mysqli->prepare(
            "SELECT id, name, salario, departamento, projeto FROM users WHERE id = ?");

        if (!$stmt) {
            throw new \Exception("Erro ao preparar a consulta: " . $mysqli->error);
        }
        $stmt->bind_param("s", $id); 

        if (!$stmt->execute()) {
            throw new \Exception("Erro ao executar a consulta: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if (!$result) {
            throw new \Exception("Erro ao obter o resultado: " . $mysqli->error);
        }

        return $result->fetch_assoc();
    }
    public static function all() {
        $mysqli = self::getConnection();
        $result = $mysqli->query("SELECT * FROM users");

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);  // Retorna todos os usuários como um array associativo
        }

        return [];  // Retorna um array vazio se não houver usuários
    }
    public static function update(int $id, array $data) {
        try {
            $mysqli = self::getConnection();
            
            $query = "UPDATE users SET name = ?, salario = ?, departamento = ?, projeto = ? WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            
            if (!$stmt) {
                throw new \Exception("Erro ao preparar a consulta: " . $mysqli->error);
            }
            
            $stmt->execute([
                $data['name'],
                $data['salario'],
                $data['departamento'],
                $data['projeto'],
                $id
            ]);
            
            return $stmt->affected_rows > 0;
            
        } catch (\Exception $e) {
            throw new \Exception("Erro ao atualizar o usuário: " . $e->getMessage());
        }
    }
    public static function delete(string $id) {
        try {
            $mysqli = self::getConnection();
            
            $query = "DELETE FROM users WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            
            if (!$stmt) {
                throw new \Exception("Erro ao preparar a consulta: " . $mysqli->error);
            }
            
            $stmt->execute([$id]);
            
            return $stmt->affected_rows > 0;
            
        } catch (\Exception $e) {
            throw new \Exception("Erro ao remover o usuário: " . $e->getMessage());
        }
    }
}