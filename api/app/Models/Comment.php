<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Article;
use App\Models\CommentsLike;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'article_id',
        'user_id',
        'content',
        'parent_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function article(){
        return $this->hasMany(Article::class);
    }
    public function likesComments(){
        return $this->hasMany(CommentsLike::class);
    }
    public function likesCountComments()
    {
        return $this->likesComments()->count();
    }
    public function parent(){
        return $this->belongsTo(Comment::class, 'parent_id');
    }
    public function replies(){
        return $this->hasMany(Comment::class, 'parent_id');
    }

}
