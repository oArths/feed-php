<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Controllers\JwtController;
use App\Http\Middleware\JwtValidation;
use App\Http\Requests\UserSiguin;


use Illuminate\Support\Facades\Validator;

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

        // return jsonResponse("logado com sucesso", 201,$user['token'] );
       return response()->json([
            'message' => "logado com sucesso",
            'token' => $user['token']
        ]);;

    }
    public function update_user(Request $parms){
        
        $messages = [
            'username.required' => 'O campo nome de usuário é obrigatório.',
            'username.string' => 'O nome de usuário deve ser uma string.',
            'username.max' => 'O nome de usuário não pode ter mais de 255 caracteres.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.string' => 'O e-mail deve ser uma string.',
            'email.email' => 'O e-mail deve ser um endereço de e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',
            'password.string' => 'A senha deve ser uma string.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'image.image' => 'O arquivo deve ser uma imagem.',
            'image.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg, gif.',
            'image.max' => 'A imagem não pode ser maior que 2048 kilobytes.',
        ];

        $validator = Validator::make($parms->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255' ,
            'password' => 'nullable|string|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $parms['Auth']['email'])->first();


        if($parms->hasfile('image') && $parms->file('image')->isValid()){
        
            $requestImage = $parms->file('image');
            $extendion = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime('now')) . '.' . $extendion;
            
            $requestImage->move(public_path('img/user'), $imageName);
        }else{
            $imageName = null;
        }
        return $parms;

        User::where('id', $user->id)->update([
            'username' => $parms['username'],
            'email' => $parms['email'],
            'password' => password_hash($parms['password'], PASSWORD_DEFAULT),
            'image' => $imageName

        ]);

        return jsonResponse('Usuario atualizado com sucesso', 200);
    }
    public function userLogOut(Request $params ){
        $request = request();
        return $request;
       $user = User::where('email', $request->Auth['email'])->first();
       $user->token = null;
       $user->save();
        // return $user;
    }
}
