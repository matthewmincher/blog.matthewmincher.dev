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

class TagControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     * @test
     */
    public function it_should_return_empty_tag_list()
    {
        $response = $this->get(route('api.v1.tags.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    /**
     * @test
     */
    public function it_should_return_tag_list()
    {
        $createCount = 10;
        BlogTag::factory($createCount)->create()->first();

        $response = $this->get(route('api.v1.tags.index'));
        $response->assertStatus(Response::HTTP_OK);
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
    public function it_should_return_a_tag(){
        $tag = BlogTag::factory(1)->create()->first();

        $response = $this->get(route('api.v1.tags.show', ['blog_tag' => $tag]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'id' => $tag->id,
                'title' => $tag->title,
                'slug' => $tag->slug,
                'content' => $tag->content
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_create_a_tag(){
        $this->post(route('api.v1.tags.store'))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_edit_a_tag(){
        $tag = BlogTag::factory(1)->create()->first();

        $this->put(route('api.v1.tags.update', ['blog_tag' => $tag]))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_delete_a_tag(){
        $tag = BlogTag::factory(1)->create()->first();

        $this->delete(route('api.v1.tags.destroy', ['blog_tag' => $tag]))->assertStatus(Response::HTTP_FORBIDDEN);
    }


    /**
     * @test
     * @dataProvider requiredFormValidationProvider
     */
    public function it_should_fail_validation_when_creating_a_tag($formInput, $formInputValue){
        $author = $this->author();
        $response = $this->actingAs($author)->post(route('api.v1.tags.store'), [
            $formInput => $formInputValue
        ]);

        $response->assertJsonValidationErrors($formInput);
    }

    /**
     * @test
     * @dataProvider requiredFormValidationProvider
     */
    public function it_should_fail_validation_when_updating_a_tag($formInput, $formInputValue){
        $author = $this->author();
        $tag = BlogTag::factory(1)->create()->first();

        $response = $this->actingAs($author)->put(route('api.v1.tags.update', ['blog_tag' => $tag]), [
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
    public function it_should_create_a_tag(){
        $author = $this->author();

        $postArgs = [
            'title' => $this->faker->words(3, true),
            'content' => $this->faker->sentences(3, true)
        ];

        $this->actingAs($author)->post(route('api.v1.tags.store', $postArgs));
        $this->assertDatabaseHas('blog_tags', ['title' => $postArgs['title'], 'content' => $postArgs['content']]);
    }

    /**
     * @test
     */
    public function it_should_edit_a_tag(){
        $initialArgs = ['title' => 'Title', 'content' => 'Content'];

        $author = $this->author();
        $tag = BlogTag::factory(1)->create($initialArgs)->first();

        $this->assertDatabaseHas('blog_tags', ['title' => $initialArgs['title'], 'content' => $initialArgs['content']]);

        $postArgs = [
            'title' => $this->faker->words(3, true),
            'content' => $this->faker->sentences(3, true)
        ];
        $this->actingAs($author)->put(route('api.v1.tags.update', ['blog_tag' => $tag]), $postArgs);
        $this->assertDatabaseHas('blog_tags', ['title' => $postArgs['title'], 'content' => $postArgs['content']]);
    }

    /**
     * @test
     */
    public function it_should_delete_a_tag(){
        $author = $this->author();
        $tag = BlogTag::factory(1)->create()->first();

        $this->assertDatabaseHas('blog_tags', ['id' => $tag->id]);
        $this->actingAs($author)->delete(route('api.v1.tags.destroy', ['blog_tag' => $tag]));
        $this->assertDatabaseMissing('blog_tags', ['id' => $tag->id]);
    }
}

