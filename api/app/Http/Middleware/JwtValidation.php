<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JwtValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    private $key;
    public function __construct(){
        $this->key = env("JWT");
    }
    public function handle(Request $request, Closure $next)
    {
        $data = $this->getToken($request);
        if(!$data){
            return response()->json([
                'message' => 'token inexistente'
            ], 401);
        }
        $token = $this->valid_token($data); 

        if(!$token){
            return response()->json([
                'message' => 'token invalido ou expirado'
            ], 401);
        }
        $request->merge(['Auth' => (array) $token]);

        return $next($request);
    }
    public function valid_token($token){
        
        list($header, $payload, $sing) = explode(".", $token);

        $dec_payload = json_decode(base64_decode($payload));
        $valid_sing = base64_encode(hash_hmac('sha256', $header . "." . $payload, $this->key, true));

        if($dec_payload->exp < time()){
            return false;
        }
        if($sing !== $valid_sing){
            return false;
        }
        return $dec_payload;
    }
    public function getToken($request){
        $data = $request->header('Authorization');

        if(empty($data)){
            return false;
        }
        $token = explode(' ', $data);
        return $token[1];

    }
}
