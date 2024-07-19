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
                'UserId' => "$user->id",
                'token' => $token
            ]);
        }

       return response()->json([
            'message' => "logado com sucesso",
            'UserId' => "$user->id",
            'token' => "Bearer " . $user['token']
        ]);;

    }
    public function update_user(Request $parms){
        
        $messages = [
            'username.required' => 'O campo nome de usuário é obrigatório.',
            'username.string' => 'O nome de usuário deve ser uma string.',
            'username.max' => 'O nome de usuário não pode ter mais de 255 caracteres.',
            'image.image' => 'O arquivo deve ser uma imagem.',
            'image.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg, gif.',
            'image.max' => 'A imagem não pode ser maior que 2048 kilobytes.',
        ];

        $validator = Validator::make($parms->all(), [
            'username' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $parms['Auth']['email'])->first();

        $userinfo = [
            'username' => $parms['username'],
        ];

        if(isset($parms->bio)){
            $bio = $parms->bio;
            $userinfo['bio'] = $bio ;
        }else{
            $userinfo['bio'] = null;
        };

        if($parms->hasfile('image') && $parms->file('image')->isValid()){
        
            $requestImage = $parms->file('image');
            $extendion = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime('now')) . '.' . $extendion;
            
            $requestImage->move(public_path('img/user'), $imageName);
            $userinfo['image'] = $imageName;
        }else{
            $valid = User::where('image', $parms->imageName)->get();
            if(empty($valid)){
                $userinfo['image'] = null;
            }
            $userinfo['image'] =  $parms->imageName;
        }

        User::where('id', $user->id)->update($userinfo);

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
    public function getUser(Request $params){
        $User = User::where('email', $params->Auth['email'])->first();

        return jsonResponse('Dados do Usuario',  200, $User  );
    }
}
