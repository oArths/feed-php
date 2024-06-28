<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Article;

class ArticleLike extends Model
{
    use HasFactory;

    protected $fillable = ['article_id', 'user_id'];

    public function article(){
        return $this->belongTo(Article::class);
    }

    public function user(){
        return $this->belongTo(User::class);
    }

}
