<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\JwtController;
use App\Http\Middleware\JwtValidation;
use App\Http\Requests\UserSiguin;

class UserContrller extends Controller
{
    public function create_user(UserRequest $params){
        $jwt = new JwtController;

       User::create([
            'username' => $params['username'],
            'email' => $params['email'],
            'password' => password_hash($params['password'], PASSWORD_DEFAULT)
        ]);
        
        $token = $jwt->Token($params['email']);

        if($token){
            return response()->json([
                "message" => "usuario cadastrado com sucesso",
                "token" => $token
            ], 201);
        }

    }
    public function login_user(UserSiguin $params){
        $jwt = new JwtValidation;
        $newtoken = new JwtController;

        $user = User::where('email', $params['email'] )->first();

        if(!$user || !password_verify($params['password'], $user->password)){
            return response()->json([
                'message'=> 'credenciais invalidas'
            ], 401);
        }

        $oldtoken = $user['token'];

        $valid = $jwt->valid_token($oldtoken);

        if(!$valid){
            $token = $newtoken->Token($params['email']);
            return response()->json([
                'message' => "logado com sucesso",
                'token' => $token
            ]);
        }

        return response()->json([
            'message' => "logado com sucesso",
            'token' => $user['token']
        ]);;

    }
}
