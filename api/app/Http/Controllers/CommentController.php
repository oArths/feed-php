<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentDeleteRequest;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRrequest;
use App\Http\Requests\LikeCommentsRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;
use App\Models\ArticleLike;
use App\Models\CommentsLike;

class CommentController extends Controller
{
    public function list_comment($params = null){

        if(is_null($params)){
            return jsonResponse('É necessario infromar o id');
        } 

        $comment = Comment::where('article_id', $params)->withCount('likesComments')->get();


        if($comment->isEmpty()){
            return jsonResponse('Comentario inexistente');
        }
        return jsonResponse('Comentarios do artigo', 200 , $comment);

    }
    public function Create_Comment(CommentRrequest $prams){

        $create = [
            'user_id' => $prams->user_id,
            'article_id' => $prams->article_id,
            'content' => $prams->content,
        ];

        $comment = Comment::create($create);
        if($comment){
            return jsonResponse('Comentario cadastrado com sucesso', 201);
        }
    }
    public function Update_comment(CommentUpdateRequest $prams){
        $create = [
            'id' => $prams->id,
            'content' => $prams->content,
        ];

        $comment = Comment::where('id', $prams->id)->update($create);

        if($comment){
            return jsonResponse('Comentario cadastrado com sucesso', 201);
        }
    }
    public function delete_comment( $params = null, $id = null ){
        
        if(is_null($params)){
            return jsonResponse('É necessario infromar o id');

        } 

        $exist = Comment::find($params);
        
        if(empty($exist)){
            return jsonResponse('Comentario inexistente');
        }

        Comment::where('id', $params)->delete();
        return jsonResponse('Comentario excluido com sucesso');


    }
    public function likeComment(LikeCommentsRequest $parms){


        $exist = CommentsLike::where('article_id', $parms->article_id)->where('user_id', $parms->user_id)->where('comment_id', $parms->comment_id,)->first(); 

        if($exist){
            $exist->delete();
            return jsonResponse('Like apagado com Sucesso', 201);

        }
        if(empty($exist)){
            $like = CommentsLike::create([
                'article_id' => $parms->article_id,
                'user_id' => $parms->user_id,
                'comment_id' => $parms->comment_id,
            ]);
            return jsonResponse('Artigo Curtido com Sucesso', 201, $like);
        }

    }
}
