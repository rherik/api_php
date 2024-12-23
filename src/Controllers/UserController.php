<?php
namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Services\UserService;

class UserController{
    public function store(Request $request, Response $response){
        $body = $request::body();

        $userService = UserService::create($body);

        if(isset($userService['error'])){
            return $response::json([
                'error' => true,
                'success' => false,
                'message' => $userService['error']
            ], 400);
        }

        $response::json([
            'error' => false,
            'success' => true,
            'data' => $userService
        ], 201);
    }
    public function fetch(Request $request, Response $response){
        $userService = UserService::fetch();

        if (isset($userService['error'])) {
            return $response::json([
                'error'   => true,
                'success' => false,
                'message' => $userService['error']
            ], 400);
        }

        $response::json([
            'error'   => false,
            'success' => true,
            'data'    => $userService
        ], 200);
        return;
    }
    public function update(Request $request, Response $response){
        $data = $request::body();
        $id = $data['id'] ?? null;

        if (!$id) {
            return $response::json([
                'error' => true,
                'message' => 'User ID is required'
            ], 400);
        }

        $result = UserService::update($id, $data);

        if (isset($result['error'])) {
            return $response::json([
                'error' => true,
                'message' => $result['error']
            ], 400);
        }

        return $response::json([
            'error' => false,
            'message' => $result['success']
        ], 200);
    }
    public function remove(Request $request, Response $response, array $id){
        if (!$id) {
            return $response::json([
                'error' => true,
                'message' => 'User ID is required'
            ], 400);
        }

        $result = UserService::delete($id[0]);

        if (isset($result['error'])) {
            return $response::json([
                'error' => true,
                'message' => $result['error']
            ], 400);
        }

        return $response::json([
            'error' => false,
            'message' => $result['success']
        ], 200);
    }
}