<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\ArticleIdValidation;
use App\Models\Article;
use App\Models\User;

use function PHPUnit\Framework\isNull;

class ArticleController extends Controller
{
public function create_article(ArticleRequest $parms ){

    $user = User::where('email', $parms['Auth']['email'])->first();

    $article = [
        'title' => $parms['title'],
        'description' => $parms['description'],
        'image' => $parms['image'],
        'user_id' => $user->id,
    ];
    
    $parms['image'] ? array_merge($article, ['image' => $parms['image']]) : array_merge($article, ['image' => null]) ;
    
    Article::create($article);
    return jsonResponse('Artigo criado com sucesso!!', 201);


}
public function list_article(){

    $articles = Article::all();

    return jsonResponse('Artigos ', 200, $articles);
}

public function get_user_article( $id = null){

    if(is_null($id)){
        return jsonError('Id não informado', 404);
    }
    $articles = Article::where('user_id', $id)->get();

    if($articles->isEmpty()){
        return jsonError('Usuario não possui artigos', 404);
    }else{
        return jsonResponse('Artigos do usuario', 200, $articles);
    }

}
public function get_article( $id = null){

    if(is_null($id)){
        return jsonError('Id não informado', 404);
    }
    $articles = Article::find($id);

    if(Empty($articles)){
        return jsonError('Artigo não exsite', 404);
    }else{
        return jsonResponse('Artigo', 200, $articles);
    }

}
public function delete_article($id = null){

    $email = request();
    $user = User::where('email', $email['Auth']['email'])->first();
    
    if(is_null($id)){
        return jsonError('Id não informado', 404);
    }
    $articles = Article::find($id);
    
    if(Empty($articles)){
        return jsonError('Artigo não exsite', 404);
    }
    
    $valid = Article::where('id', $id)->where('user_id', $user->id)->first();
    
    if(empty($valid)){
        return jsonError('Artigo não pertecente a esse usuario');
    }else{
        $valid->delete();
        return jsonError('Artigo deletado com sucesso');
    }

}
public function update_article(ArticleRequest $parms){
     
    $exist = Article::find($parms->id);

    if(!$exist){
        return jsonError('Artigo inexistente');
    }

    $article = [
        'title' => $parms['title'],
        'description' => $parms['description'],
        'image' => $parms['image'],
    ];
    
    $parms['image'] ? array_merge($article, ['image' => $parms['image']]) : array_merge($article, ['image' => null]) ;
    
    Article::where('id', $parms->id)->update($article);

    return jsonResponse('Artigo atualizado com sucesso!!', 201);
} 
public function recently_article(){

    $articles = Article::orderBy('created_at', 'desc')->get();

    return jsonResponse('Artigos recentes', 200, $articles);
}
}
