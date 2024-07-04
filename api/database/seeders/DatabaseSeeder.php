<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\Comment;
use App\Models\CommentsLike;
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

        $tags = Tag::factory(20)->create();

        // Cria usuários
        User::factory(50)->create()->each(function ($user) use ($tags) {
            // Cria artigos para cada usuário
            Article::factory(5)->create(['user_id' => $user->id])->each(function ($article) use ($user, $tags) {
                // Associa um conjunto aleatório de tags a cada artigo
                $article->tags()->attach($tags->random(3)->pluck('id')->toArray());

                // Cria likes para os artigos
                ArticleLike::factory(3)->create([
                    'article_id' => $article->id,
                    'user_id' => $user->id,
                ]);
                // Cria comentários para os artigos
                $comments = Comment::factory(3)->create([
                    'user_id' => $user->id,
                    'article_id' => $article->id
                ]);
                $comments->each(function ($comments) use ($article, $user){

                    CommentsLike::factory(2)->create([
                        'article_id' => $article->id,
                        'user_id' => $user->id,
                        'comment_id' => $comments->id
                    ]);
                });
            });
        });



    }
}
