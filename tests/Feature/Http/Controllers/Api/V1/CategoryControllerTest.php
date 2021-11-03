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

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     * @test
     */
    public function it_should_return_empty_category_list()
    {
        $response = $this->get(route('api.v1.categories.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    /**
     * @test
     */
    public function it_should_return_category_list()
    {
        $createCount = 10;
        BlogCategory::factory($createCount)->create()->first();

        $response = $this->get(route('api.v1.categories.index'));

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'title', 'slug', 'content'
                ]
            ]
        ]);
        $response->assertJsonCount($createCount, 'data');
    }

    /**
     * @test
     */
    public function it_should_return_a_category(){
        $category = BlogCategory::factory(1)->create()->first();

        $response = $this->get(route('api.v1.categories.show', ['blog_category' => $category]));
        $response->assertJson([
            'data' => [
                'id' => $category->id,
                'title' => $category->title,
                'slug' => $category->slug,
                'content' => $category->content
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_create_a_category(){
        $this->post(route('api.v1.categories.store'))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_edit_a_category(){
        $category = BlogCategory::factory(1)->create()->first();

        $this->put(route('api.v1.categories.update', ['blog_category' => $category]))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_delete_a_tag(){
        $category = BlogCategory::factory(1)->create()->first();

        $this->delete(route('api.v1.categories.destroy', ['blog_category' => $category]))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @dataProvider requiredFormValidationProvider
     */
    public function it_should_fail_validation_when_creating_a_category($formInput, $formInputValue){
        $author = $this->author();
        $response = $this->actingAs($author)->post(route('api.v1.categories.store'), [
            $formInput => $formInputValue
        ]);

        $response->assertJsonValidationErrors($formInput);
    }

    /**
     * @test
     * @dataProvider requiredFormValidationProvider
     */
    public function it_should_fail_validation_when_updating_a_category($formInput, $formInputValue){
        $author = $this->author();
        $category = BlogCategory::factory(1)->create()->first();

        $response = $this->actingAs($author)->put(route('api.v1.categories.update', ['blog_category' => $category]), [
            $formInput => $formInputValue
        ]);

        $response->assertJsonValidationErrors($formInput);
    }

    public function requiredFormValidationProvider(){
        return [
            ['title', ''],
            ['title', 's'],
            ['content', ''],
            ['content', 'short']
        ];
    }

    /**
     * @test
     */
    public function it_should_create_a_category(){
        $author = $this->author();

        $postArgs = [
            'title' => $this->faker->words(3, true),
            'content' => $this->faker->sentences(3, true)
        ];

        $this->actingAs($author)->post(route('api.v1.categories.store', $postArgs));
        $this->assertDatabaseHas('blog_categories', ['title' => $postArgs['title'], 'content' => $postArgs['content']]);
    }

    /**
     * @test
     */
    public function it_should_edit_a_category(){
        $initialArgs = ['title' => 'Title', 'content' => 'Content'];

        $author = $this->author();
        $category = BlogCategory::factory(1)->create($initialArgs)->first();

        $this->assertDatabaseHas('blog_categories', ['title' => $initialArgs['title'], 'content' => $initialArgs['content']]);

        $postArgs = [
            'title' => $this->faker->words(3, true),
            'content' => $this->faker->sentences(3, true)
        ];
        $this->actingAs($author)->put(route('api.v1.categories.update', ['blog_category' => $category]), $postArgs);
        $this->assertDatabaseHas('blog_categories', ['title' => $postArgs['title'], 'content' => $postArgs['content']]);
    }

    /**
     * @test
     */
    public function it_should_delete_an_empty_category(){
        $author = $this->author();
        $category = BlogCategory::factory(1)->create()->first();

        $this->assertDatabaseHas('blog_categories', ['id' => $category->id]);
        $response = $this->actingAs($author)->delete(route('api.v1.categories.destroy', ['blog_category' => $category]));
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertSoftDeleted('blog_categories', ['id' => $category->id]);
    }

    /**
     * @test
     */
    public function it_should_not_delete_a_category_containing_posts(){
        $author = $this->author();
        $category = BlogCategory::factory(1)->create()->first();

        BlogPost::factory()->create(['blog_category_id' => $category->id])->first();

        $this->assertDatabaseHas('blog_categories', ['id' => $category->id]);

        $response = $this->actingAs($author)->delete(route('api.v1.categories.destroy', ['blog_category' => $category]));
        $response->assertStatus(Response::HTTP_CONFLICT);
        $response->assertJsonValidationErrors([
            'generic' => 'This category has 1 post. Move it elsewhere before deleting it.'
        ]);
    }
}
