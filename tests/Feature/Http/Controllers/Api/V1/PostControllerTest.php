<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /**
     * @test
     */
    public function it_should_return_empty_post_list()
    {
        $response = $this->get(route('api.v1.posts.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    /**
     * @test
     */
    public function it_should_return_post_list()
    {
        $author = $this->author();
        BlogCategory::factory(1)->create()->first();

        $createCount = 5;
        BlogPost::factory($createCount)->create();

        $response = $this->get(route('api.v1.posts.index'));

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'title', 'blog_category_id', 'slug', 'content', 'user_id'
                ]
            ]
        ]);
        $response->assertJsonCount($createCount, 'data');
    }

    /**
     * @test
     */
    public function it_should_expand_properties_in_the_post_list(){
        foreach(['category', 'tags', 'user'] as $expandable){
            $response = $this->get(route('api.v1.posts.index', ['expand' => $expandable]));
            $response->assertJsonStructure([
                'data' => [
                    '*' => [
                        $expandable
                    ]
                ]
            ]);
        }
    }

    /**
     * @test
     */
    public function it_should_return_a_post(){
        $author = $this->author();
        BlogCategory::factory(1)->create()->first();

        $post = BlogPost::factory()->create()->first();

        $response = $this->get(route('api.v1.posts.show', ['blog_post' => $post]));
        $response->assertJson([
            'data' => [
                'id' => $post->id,
                'blog_category_id' => $post->blog_category_id,
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'user_id' => $post->user_id
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_should_expand_properties_in_a_post(){
        $author = $this->author();
        BlogCategory::factory(1)->create()->first();

        $post = BlogPost::factory()->create()->first();

        foreach(['category', 'tags', 'user'] as $expandable){
            $response = $this->get(route('api.v1.posts.show', ['blog_post' => $post, 'expand' => $expandable]));
            $response->assertJsonStructure([
                'data' => [
                    $expandable
                ]
            ]);
        }
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_create_a_post(){
        $this->post(route('api.v1.posts.store'))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_edit_a_post(){
        $author = $this->author();
        BlogCategory::factory(1)->create()->first();

        $post = BlogPost::factory()->create()->first();

        $this->put(route('api.v1.posts.update', ['blog_post' => $post]))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_delete_a_tag(){
        $author = $this->author();
        BlogCategory::factory(1)->create()->first();

        $post = BlogPost::factory()->create()->first();

        $this->delete(route('api.v1.posts.destroy', ['blog_post' => $post]))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @dataProvider requiredFormValidationProvider
     */
    public function it_should_fail_validation_when_creating_a_post($formInput, $formInputValue){
        $author = $this->author();
        $response = $this->actingAs($author)->post(route('api.v1.posts.store'), [
            $formInput => $formInputValue
        ]);

        $response->assertJsonValidationErrors($formInput);
    }

    /**
     * @test
     * @dataProvider requiredFormValidationProvider
     */
    public function it_should_fail_validation_when_updating_a_post($formInput, $formInputValue){
        $author = $this->author();
        BlogCategory::factory(1)->create()->first();
        $post = BlogPost::factory()->create()->first();

        $response = $this->actingAs($author)->put(route('api.v1.posts.update', ['blog_post' => $post]), [
            $formInput => $formInputValue
        ]);

        $response->assertJsonValidationErrors($formInput);
    }

    public function requiredFormValidationProvider(){
        return [
            'Missing title' => ['title', ''],
            'Title too short' => ['title', 's'],
            'Title too long' => ['title', 'thisstringislongerthanthirtycharacters'],
            'Missing content ' => ['content', ''],
            'Content too short' => ['content', 'short'],
            'Missing category' => ['blog_category_id', ''],
            'Non-existent category' => ['blog_category_id', 0]
        ];
    }

    /**
     * @test
     */
    public function it_should_create_a_post(){
        $author = $this->author();
        $category = BlogCategory::factory()->create()->first();

        $postArgs = [
            'title' => $this->faker->words(3, true),
            'content' => $this->faker->sentences(3, true),
            'blog_category_id' => $category->id,
            'slug' => '',
            'tags' => null,
            'user_id' => $author->id,
            'published' => true
        ];

        $this->actingAs($author)->post(route('api.v1.posts.store', $postArgs))->assertSuccessful();
    }

    /**
     * @test
     */
    public function it_should_edit_a_post(){
        $initialArgs = ['title' => 'Title', 'content' => 'Content'];

        $author = $this->author();
        $category = BlogCategory::factory(1)->create($initialArgs)->first();
        $post = BlogPost::factory()->create()->first();

        $postArgs = [
            'title' => $this->faker->words(3, true),
            'content' => $this->faker->sentences(3, true),
            'blog_category_id' => $post->category->id
        ];
        $this->actingAs($author)->put(route('api.v1.posts.update', ['blog_post' => $post]), $postArgs)->assertSuccessful();
    }

    /**
     * @test
     */
    public function it_should_delete_a_post(){
        $author = $this->author();
        $category = BlogCategory::factory(1)->create()->first();
        $post = BlogPost::factory()->create()->first();

        $response = $this->actingAs($author)->delete(route('api.v1.posts.destroy', ['blog_post' => $post]));
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
