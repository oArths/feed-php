<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentDeleteRequest;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRrequest;
use App\Http\Requests\LikeCommentsRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Models\Comment;
use App\Models\ArticleLike;
use App\Models\Article;
use App\Models\CommentsLike;
use App\Models\User;


class CommentController extends Controller
{
    public function list_comment($params = null){

        $email = request();
        $user = User::where('email', $email->Auth['email'])->first();
        $userId =  $user['id'];
        
        if(is_null($params)){
            return jsonResponse('É necessario infromar o id');
        } 

        $article = Article::where('id', $params)->withCount('likes')->withCount(['likes', 'comments'])->with(['user' => function($query){
            $query->select('id', 'username', 'image');
        }])->get();
        
        $article->each(function($article) use ($userId) {
            $article->liked_by_user = $article->likes->contains('user_id', $userId);
            unset($article->likes);
        });
        $comment = Comment::where('article_id', $params)->with(['replies', 'likesComments'])->withCount('likesComments')->with(['user' => function($query){
            $query->select('id', 'username', 'image');
        }])->get();

        $comment->each(function($comment) use ($userId){
            $comment->like_by_user_comment = $comment->likesComments->contains('user_id', $userId);
            unset($comment->likesComments);
        });

        // if($comment->isEmpty()){
        //     return jsonResponse('Comentario inexistente');
        // }

        $results = [
            'article' => $article,
            'comments' => $comment
        ];
        return jsonResponse('Comentarios do artigo', 200 , $results);

    }
    public function Create_Comment(CommentRrequest $prams){

        $create = [
            'user_id' => $prams->user_id,
            'article_id' => $prams->article_id,
            'content' => $prams->content,
            'parent_id' => $prams->parent_id,
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

        if(!$exist){
            $like = CommentsLike::create([
                'article_id' => $parms->article_id,
                'user_id' => $parms->user_id,
                'comment_id' => $parms->comment_id,
            ]);
            return jsonResponse('Like criado com Sucesso', 201);

        }
           
        return jsonResponse('like de comenatrio ja existe', 201);

    }
    public function likeCommentDelete(LikeCommentsRequest $parms){


        $exist = CommentsLike::where('article_id', $parms->article_id)->where('user_id', $parms->user_id)->where('comment_id', $parms->comment_id,)->first(); 

        if($exist){
            $exist->delete();
            return jsonResponse('Like apagado com Sucesso', 201);

        }
  
        return jsonResponse('comentario ja foi curtido', 200);
        

    }

}
