<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // \App\Models\Article::factory(10)->create();
        User::factory(10)->create()->each(function ($user){
            Article::factory(5)->create(['user_id' => $user->id])->each(function ($article) use ($user){
                Comment::factory(3)->create(
                ['user_id' => $user->id, 'article_id' => $article->id ]);

            });
        });



    }
}
