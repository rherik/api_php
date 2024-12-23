<?php
namespace App\Services;

use App\Utils\Validator;
use App\Models\User;

use Exception;

class UserService{
    public static function create(array $data){
        try {
            $fields = Validator::validate([
                //name, salario, departamento e projeto
                'name' => $data['name'] ?? '',
                'salario' => $data['salario'] ?? '',
                'departamento' => $data['departamento'] ?? '',
                'projeto' => $data['projeto'] ?? ''
            ]);

            $user = User::save($fields);

            if (!$user) return ['error' => 'Sorry, we could not create your account.'];
            return "User created successfully";
        }
        catch(Exception $e){
            return ['error' => $e->getMessage()];
        }
    }
    public static function fetch(){
        try {
            // Buscar todos os usuários
            $users = User::all();  // Supondo que você tenha um método `all` no modelo `User`
            
            if (empty($users)) {
                return ['error' => 'No users found'];
            }

            return $users;  // Retorna os dados dos usuários

        } catch (Exception $e) {
            // Retornar erro caso algo falhe
            return ['error' => $e->getMessage()];
        }
    }
    public static function update(int $id, array $data) {
        try {
            $updated = User::update($id, $data);
            
            if (!$updated) {
                return ['error' => 'User update failed'];
            }

            return ['success' => 'User updated successfully'];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    public static function delete(int|string $id) {
        try {
            $removed = User::delete($id);

            if (!$removed) {
                return ['error' => 'User removal failed'];
            }

            return ['success' => 'User removed successfully'];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}