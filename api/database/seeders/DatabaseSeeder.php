<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Article;
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
        // User::factory(10)->create()->each(function ($user){
        //     Article::factory(5)->create(['user_id' => $user->id])->each(function ($article) use ($user){
        //         Comment::factory(3)->create(
        //         ['user_id' => $user->id, 'article_id' => $article->id ]);
        //         $tags = Tag::factory(3)->create();
        //         $article->tags()->attach($tags);
        //     });
        // });
        $tags = Tag::factory(20)->create();

        // Cria usuários
        User::factory(10)->create()->each(function ($user) use ($tags) {
            // Cria artigos para cada usuário
            Article::factory(5)->create(['user_id' => $user->id])->each(function ($article) use ($user, $tags) {
                // Associa um conjunto aleatório de tags a cada artigo
                $article->tags()->attach($tags->random(3));

                // Cria comentários para os artigos
                Comment::factory(3)->create([
                    'user_id' => $user->id,
                    'article_id' => $article->id
                ]);
            });
        });



    }
}
