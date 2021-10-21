<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
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
        User::factory()->me()->create();
        BlogTag::factory(10)->create();
        BlogCategory::factory(2)->create();
        // \App\Models\User::factory(10)->create();
    }
}
