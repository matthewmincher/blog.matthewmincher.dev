<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BlogPost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = ucfirst($this->faker->words(3, true));

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->sentences(20, true),
            'published' => true,
            'user_id' => User::inRandomOrder()->first()->id,
            'blog_category_id' => BlogCategory::inRandomOrder()->first()->id
        ];
    }
}
