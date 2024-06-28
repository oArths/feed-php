<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\Comment;
use App\Models\User;
use App\Models\Tag;
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
    
        // $tags = Tag::factory(20)->create();
        // $like = ArticleLike::factory(20)->create();

        // User::factory(10)->create()->each(function ($user) use ($tags) {
        //     Article::factory(5)->create(['user_id' => $user->id])->each(function ($article) use ($user, $tags) {
        //         $article->tags()->attach($tags->random(3));
        //         $article->likes()->attach($like->random(3));

        //         Comment::factory(3)->create([
        //             'user_id' => $user->id,
        //             'article_id' => $article->id
        //         ]);
        //     });
        // });
        $tags = Tag::factory(20)->create();

        // Cria curtidas
        $likes = ArticleLike::factory(20)->create();

        // Cria usuários
        User::factory(10)->create()->each(function ($user) use ($tags, $likes) {
            // Cria artigos para cada usuário
            Article::factory(5)->create(['user_id' => $user->id])->each(function ($article) use ($user, $tags, $likes) {
                // Associa um conjunto aleatório de tags a cada artigo
                $article->tags()->attach($tags->random(3)->pluck('id')->toArray());

                // Associa um conjunto aleatório de likes a cada artigo
                $likes->random(3)->each(function ($like) use ($article) {
                    $article->likes()->create([
                        'user_id' => $like->user_id,
                    ]);
                });

                // Cria comentários para os artigos
                Comment::factory(3)->create([
                    'user_id' => $user->id,
                    'article_id' => $article->id
                ]);
            });
        });



    }
}
