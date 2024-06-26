<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentDeleteRequest;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRrequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function list_comment($params = null){

        if(is_null($params)){
            return jsonResponse('É necessario infromar o id');
        } 

        $comment = Comment::where('article_id', $params)->get();


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
}
