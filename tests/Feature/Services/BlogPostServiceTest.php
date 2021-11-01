<?php

namespace Tests\Feature\Services;

use App\Models\BlogCategory;
use App\Models\User;
use App\Services\BlogPostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogPostServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function it_creates_a_post_for_a_user(){
        $service = resolve(BlogPostService::class);
        $user = User::factory(1)->create()->first();
        $category = BlogCategory::factory(1)->create()->first();

        $postArgs = [
            'title' => $this->faker->words(3, true),
            'content' => $this->faker->sentences(3, true),
            'blog_category_id' => $category->id,
            'published' => true,
            'slug' => null,
            'tags' => null
        ];

        $service->createPostForUser($postArgs, $user);
        $this->assertDatabaseHas('blog_posts', [
            'title' => $postArgs['title'],
            'content' => $postArgs['content'],
            'blog_category_id' => $postArgs['blog_category_id'],
            'published' => true,
            'user_id' => $user->id
        ]);
    }

    /**
     * @test
     */
    public function it_creates_missing_tags_for_a_post(){
        $service = resolve(BlogPostService::class);
        $user = User::factory(1)->create()->first();
        $category = BlogCategory::factory(1)->create()->first();

        $tags = ['Tag One', 'Tag Two'];

        $postArgs = [
            'title' => $this->faker->words(3, true),
            'content' => $this->faker->sentences(3, true),
            'blog_category_id' => $category->id,
            'published' => true,
            'slug' => null,
            'tags' => $tags
        ];

        $service->createPostForUser($postArgs, $user);
        foreach($tags as $tag){
            $this->assertDatabaseHas('blog_tags', ['title' => $tag]);
        }
    }

    /**
     * @test
     */
    public function it_removes_tag_relationships_for_deleted_posts(){
        $service = resolve(BlogPostService::class);
        $user = $this->author();
        $category = BlogCategory::factory(1)->create()->first();

        $postArgs = [
            'title' => $this->faker->words(3, true),
            'content' => $this->faker->sentences(3, true),
            'blog_category_id' => $category->id,
            'published' => true,
            'slug' => null,
            'tags' => ['Tag One', 'Tag Two']
        ];

        $post = $service->createPostForUser($postArgs, $user);
        $service->deletePost($post);
        $this->assertDatabaseCount('blog_post_tags', 0);
    }
}
