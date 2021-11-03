<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Comment;
use Illuminate\Database\Seeder;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->state(['is_author' => true])->create();
        BlogTag::factory(25)->create();
        BlogCategory::factory(2)->create();
        BlogPost::factory(50)->create();

        BlogPost::all()->each(function(BlogPost $post){
           $post->tags()->sync(BlogTag::inRandomOrder()->limit(random_int(0, 5))->get());

           $hasComments = random_int(0, 1) === 1;
           if($hasComments){
               $commentCount = random_int(1, 10);

               $comments = Comment::factory($commentCount)->make();
               $post->comments()->saveMany($comments);
           }
        });
        // \App\Models\User::factory(10)->create();
    }
}
