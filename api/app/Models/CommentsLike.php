<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article;

class CommentsLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'article_id',
        'comment_id'
    ];


    public function articles(){
        return $this->belongsTo(Article::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function comments(){
        return $this->belongsTo(Comment::class);
    }
}
