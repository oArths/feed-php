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
    
    // $parms['image'] ? array_merge($article, ['image' => $parms['image']]) : array_merge($article, ['image' => null]) ;
    
    if($parms->hasfile('image') && $parms->file('image')->isValid()){
        
        $requestImage = $parms->file('image');
        $extendion = $requestImage->extension();
        $imageName = md5($requestImage->getClientOriginalName() . strtotime('now')) . '.' . $extendion;
        
        $requestImage->move(public_path('img/user'), $imageName);
    }else{
        $imageName = null;
    }

    $article = [
        'title' => $parms['title'],
        'description' => $parms['description'],
        'image' => $imageName,
        'user_id' => $user->id,
    ];
    
    Article::create($article);
    return jsonResponse('Artigo criado com sucesso!!', 201);


}
public function list_article(){

    $articles = Article::withCount('likes')->all();

    return jsonResponse('Artigos ', 200, $articles);
}

public function get_user_article( $id = null){

    $user = User::find($id);


    if(is_null($id) || is_null($user)){
        return jsonError('Id não informado ou invalido', 404);
    }
    $articles = $user->articles()->withCount('likes')->get();

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
    $articles = Article::withCount('likes')->find($id);

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

    if($parms->hasfile('image') && $parms->file('image')->isValid()){
        
        $requestImage = $parms->file('image');
        $extendion = $requestImage->extension();
        $imageName = md5($requestImage->getClientOriginalName() . strtotime('now')) . '.' . $extendion;
        
        $requestImage->move(public_path('img/user'), $imageName);
    }else{
        $imageName = null;
    }

    $article = [
        'title' => $parms['title'],
        'description' => $parms['description'],
        'image' => $imageName,
    ];
    
    
    Article::where('id', $parms->id)->update($article);

    return jsonResponse('Artigo atualizado com sucesso!!', 201);
} 
public function recently_article(){

    $articles = Article::orderBy('created_at', 'desc')->withCount('likes')->get();

    return jsonResponse('Artigos recentes', 200, $articles);
}
public function articleTagsUser($userId = null){

    $getUser =  User::find($userId);

    if(!$getUser){
        return jsonError('Usuario não encontrado', 401);
    }

    // with('tags') -> faz o relacionamneto dos articgos com as tags e traz o artigo com a tag relaconada
    //pluck('tags') -> extrai o campo tag dos artigos e cria uma coleção com as tags
    // flatten() ->achata a coleção de coleçoes que excistia em apenas uma  com todas as tags
    // unique('id') -> remove as duplicadas de tags mantendo apenas tags unicas
    $tags = $getUser->articles()->with('tags')->get()->pluck('tags')->flatten()->unique('id');

    $articles = Article::whereHas('tags', function($query) use ($tags){
        $query->whereIn('tags.id', $tags->pluck('id'));
    })->withCount('likes')->get();


    return jsonResponse('artigos associados as tags', 201, $articles);

}
public function TagsArticleUser($userId = null){
   $userExist = User::find($userId);

   if(!$userExist || is_null($userExist)){
        return jsonError('id inexistente ou inexistente');
   }
    //USEI o ".*." pra acessar todos os dados se um subArray que existia em tags
    $tags = $userExist->articles()->with('tags')->get()->pluck('tags.*.pivot.tag_id')->flatten()->unique();

    // return $tags;
    //procure em artigos "wherehas -> onde tem" tags e passo função anonima e que ela vai usar "use" $tags
    // executei a $query e "whereIn -> em que" O id da tag fosse o mesmo das do usuario
    // condicionei que "where ->  onde" o o user_id fosse diferente "!=" do usuario que faz requisição
    // juntei com tags "with" pra poder utilizar no front end
   $articles = Article::whereHas('tags', function($query) use ($tags){
        $query->whereIn('tag_id', $tags);
   })->where('user_id', '!=', $userExist->id)->with('tags')->withCount('likes')->get();

   return jsonResponse('Artigos relacionasdos as Tags', 200, $articles);


}
}
