<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ArticleLike;
use App\Models\CommentsLike;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'username',
        'email',
        'bio',
        'password',
        'token',
        'created_at',
        'updated_at'

    ];

    public function articles() {
        return $this->hasMany(Article::class);
    }
    public function articleLikes()
    {
        return $this->hasMany(ArticleLike::class);
    }
    public function likesComments()
    {
        return $this->hasMany(CommentsLike::class);
    }

}
