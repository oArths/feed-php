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
    // public function tags(){
    //     return $this->belongsToMany(Tags::class);
    // }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
