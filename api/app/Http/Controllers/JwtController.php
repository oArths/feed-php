<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class JwtController extends Controller
{

    private $key;

    public function __construct()
    {
        $this->key = env('JWT');
    }

    public function Token($email){
        
        $header = [
            'typ' => 'JWT',
            'alg'=> 'hs256'
        ];
        $now = time();
        $payload = [
            'created' => $now,
            'exp' => $now + 3600,
            'email' => $email
        ];

        $header = base64_encode(json_encode($header));
        $payload = base64_encode(json_encode($payload));
    
        $sing = base64_encode(hash_hmac('sha256', $header . "." . $payload, $this->key, true));

        $token = "Bearer " . $header . "." . $payload . "." .$sing;

        $user = User::where('email', $email)->first();

        if($user){
            $clear = explode(" ", $token);
            $user->update(['token'=> $clear[1]]);
            return $token;
        }



    } 

}
