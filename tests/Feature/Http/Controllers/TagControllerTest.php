<?php

namespace Tests\Feature\Http\Controllers;

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
    public function it_should_render_empty_tag_list()
    {
        $response = $this->get(route('tags.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText('No tags found');
    }

    /**
     * @test
     */
    public function it_should_render_tag_list()
    {

        $tag = BlogTag::factory(1)->create()->first();

        $response = $this->get(route('tags.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText($tag->title);
    }

    /**
     * @test
     */
    public function it_should_render_a_tag_with_no_posts(){
        $tag = BlogTag::factory(1)->create()->first();

        $response = $this->get(route('tags.show', ['blog_tag' => $tag]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText($tag->title);
        $response->assertSeeText('No posts');
    }

    /**
     * @test
     */
    public function it_should_render_a_tag_with_posts(){
        User::factory(1)->create()->first();
        BlogCategory::factory(1)->create()->first();

        $tag = BlogTag::factory(1)->create()->first();

        $post = BlogPost::factory(1)->create()->first();
        $post->tags()->attach($tag);

        $response = $this->get(route('tags.show', ['blog_tag' => $tag]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText($post->title);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_create_a_tag(){
        $this->get(route('tags.create'))->assertStatus(Response::HTTP_FORBIDDEN);
        $this->post(route('tags.store'))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_edit_a_tag(){
        $tag = BlogTag::factory(1)->create()->first();

        $this->get(route('tags.edit', ['blog_tag' => $tag]))->assertStatus(Response::HTTP_FORBIDDEN);
        $this->put(route('tags.update', ['blog_tag' => $tag]))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_delete_a_tag(){
        $tag = BlogTag::factory(1)->create()->first();

        $this->delete(route('tags.destroy', ['blog_tag' => $tag]))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_should_render_the_new_tag_form(){
        $author = User::factory()->me()->create()->first();

        $response = $this->actingAs($author)->get(route('tags.create'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText('New Tag');
    }

    /**
     * @test
     */
    public function it_should_render_the_edit_tag_form(){
        $tag = BlogTag::factory(1)->create()->first();
        $author = User::factory()->me()->create()->first();

        $response = $this->actingAs($author)->get(route('tags.edit', ['blog_tag' => $tag]));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText('Edit Tag');
        $response->assertSee($tag->title, false);
    }

    /**
     * @test
     * @dataProvider requiredFormValidationProvider
     */
    public function it_should_fail_validation_when_creating_a_tag($formInput, $formInputValue){
        $author = User::factory()->me()->create()->first();
        $response = $this->actingAs($author)->post(route('tags.store'), [
            $formInput => $formInputValue
        ]);

        $response->assertSessionHasErrors($formInput);
    }

    /**
     * @test
     * @dataProvider requiredFormValidationProvider
     */
    public function it_should_fail_validation_when_updating_a_tag($formInput, $formInputValue){
        $author = User::factory()->me()->create()->first();
        $tag = BlogTag::factory(1)->create()->first();

        $response = $this->actingAs($author)->put(route('tags.update', ['blog_tag' => $tag]), [
            $formInput => $formInputValue
        ]);

        $response->assertSessionHasErrors($formInput);
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
        $author = User::factory()->me()->create()->first();

        $postArgs = [
            'title' => $this->faker->words(3, true),
            'content' => $this->faker->sentences(3, true)
        ];

        $this->actingAs($author)->post(route('tags.store', $postArgs));
        $this->assertDatabaseHas('blog_tags', ['title' => $postArgs['title'], 'content' => $postArgs['content']]);
    }

    /**
     * @test
     */
    public function it_should_edit_a_tag(){
        $initialArgs = ['title' => 'Title', 'content' => 'Content'];

        $author = User::factory()->me()->create()->first();
        $tag = BlogTag::factory(1)->create($initialArgs)->first();

        $this->assertDatabaseHas('blog_tags', ['title' => $initialArgs['title'], 'content' => $initialArgs['content']]);

        $postArgs = [
            'title' => $this->faker->words(3, true),
            'content' => $this->faker->sentences(3, true)
        ];
        $response = $this->actingAs($author)->put(route('tags.update', ['blog_tag' => $tag]), $postArgs);
        $tag->refresh();

        $response->assertRedirect(route('tags.show', ['blog_tag' => $tag]));
        $this->assertEquals($tag->title, $postArgs['title']);
        $this->assertEquals($tag->content, $postArgs['content']);
    }

    /**
     * @test
     */
    public function it_should_delete_a_tag(){
        $author = User::factory()->me()->create()->first();
        $tag = BlogTag::factory(1)->create()->first();

        $this->assertDatabaseHas('blog_tags', ['id' => $tag->id]);
        $this->actingAs($author)->delete(route('tags.destroy', ['blog_tag' => $tag]));
        $this->assertDatabaseMissing('blog_tags', ['id' => $tag->id]);
    }
}
