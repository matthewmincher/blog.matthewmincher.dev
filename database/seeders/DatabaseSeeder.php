<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
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
        User::factory()->state(['is_author' => true])->create();
        BlogTag::factory(25)->create();
        BlogCategory::factory(2)->create();
        BlogPost::factory(50)->create();

        BlogPost::all()->each(function(BlogPost $post){
           $post->tags()->sync(BlogTag::inRandomOrder()->limit(5)->get());
        });
        // \App\Models\User::factory(10)->create();
    }
}
