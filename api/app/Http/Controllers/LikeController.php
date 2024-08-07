<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Http\Requests\LikeRequest;
use App\Models\ArticleLike;

class LikeController extends Controller
{

    public function like(LikeRequest $parms){

        $exist = ArticleLike::where('article_id', $parms->article_id)->where('user_id', $parms->user_id)->first(); 

        if(!$exist){
            $like = ArticleLike::create([
                'article_id' => $parms->article_id,
                'user_id' => $parms->user_id,
            ]);
            return jsonResponse('Artigo Curtido com Sucesso', 201);
        }
        return jsonResponse('Like ja existe', 400);

    }
    public function likeDelete(LikeRequest $parms){

        $exist = ArticleLike::where('article_id', $parms->article_id)->where('user_id', $parms->user_id)->first(); 

        if($exist){
            $exist->delete();
            return jsonResponse('Like apagado com Sucesso', 201);

        }
        return jsonResponse('Artigo ja  foi Curtido', 400);
        

    }

}
