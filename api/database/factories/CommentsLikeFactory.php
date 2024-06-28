<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Article;
use App\Models\User;
use App\Models\Comment;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentsLike>
 */
class CommentsLikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'article_id' => Article::factory(),
            'user_id' => User::factory(),        
            'comment_id' => Comment::factory(),        
        ];
    }
}
