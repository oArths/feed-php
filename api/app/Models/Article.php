<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Article extends Model
{
    use HasFactory;

    protected $fillable =[
        'id',
        'title',
        'description',
        'image',
        'user_id'

    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function tags(){
        return $this->belongsToMany(Tag::class, 'article_tag');
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
    public function likes()
    {
        return $this->hasMany(ArticleLike::class);
    }
    public function likesCount()
    {
        return $this->likes()->count();
    }
    public function likesComments()
    {
        return $this->hasMany(CommentsLike::class);
    }

}
