<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Article;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'article_id',
        'user_id',
        'content',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function article(){
        return $this->hasMany(Article::class);
    }

}
