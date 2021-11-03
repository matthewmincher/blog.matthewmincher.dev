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

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     * @test
     */
    public function it_should_render_empty_category_list()
    {
        $response = $this->get(route('categories.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText('No categories found');
    }

    /**
     * @test
     */
    public function it_should_render_category_list()
    {

        $category = BlogCategory::factory(1)->create()->first();

        $response = $this->get(route('categories.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText($category->title);
    }

    /**
     * @test
     */
    public function it_should_render_a_category_with_no_posts(){
        $category = BlogCategory::factory(1)->create()->first();

        $response = $this->get(route('categories.show', ['blog_category' => $category]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText($category->title);
        $response->assertSeeText('No posts');
    }

    /**
     * @test
     */
    public function it_should_render_a_category_with_posts(){
        $author = $this->author();
        $category = BlogCategory::factory(1)->create()->first();

        $post = BlogPost::factory(1)->create()->first();

        $response = $this->get(route('categories.show', ['blog_category' => $category]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText($post->title);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_create_a_category(){
        $this->get(route('categories.create'))->assertStatus(Response::HTTP_FORBIDDEN);
        $this->post(route('categories.store'))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_edit_a_category(){
        $category = BlogCategory::factory(1)->create()->first();

        $this->get(route('categories.edit', ['blog_category' => $category]))->assertStatus(Response::HTTP_FORBIDDEN);
        $this->put(route('categories.update', ['blog_category' => $category]))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_should_require_authentication_to_delete_a_tag(){
        $category = BlogCategory::factory(1)->create()->first();

        $this->delete(route('categories.destroy', ['blog_category' => $category]))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function it_should_render_the_new_category_form(){
        $author = $this->author();

        $response = $this->actingAs($author)->get(route('categories.create'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText('New Category');
    }

    /**
     * @test
     */
    public function it_should_render_the_edit_category_form(){
        $category = BlogCategory::factory(1)->create()->first();
        $author = $this->author();

        $response = $this->actingAs($author)->get(route('categories.edit', ['blog_category' => $category]));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText('Edit Category');
        $response->assertSee($category->title, false);
    }

    /**
     * @test
     * @dataProvider requiredFormValidationProvider
     */
    public function it_should_fail_validation_when_creating_a_category($formInput, $formInputValue){
        $author = $this->author();
        $response = $this->actingAs($author)->post(route('categories.store'), [
            $formInput => $formInputValue
        ]);

        $response->assertSessionHasErrors($formInput);
    }

    /**
     * @test
     * @dataProvider requiredFormValidationProvider
     */
    public function it_should_fail_validation_when_updating_a_category($formInput, $formInputValue){
        $author = $this->author();
        $category = BlogCategory::factory(1)->create()->first();

        $response = $this->actingAs($author)->put(route('categories.update', ['blog_category' => $category]), [
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
    public function it_should_create_a_category(){
        $author = $this->author();

        $postArgs = [
            'title' => $this->faker->words(3, true),
            'content' => $this->faker->sentences(3, true)
        ];

        $this->actingAs($author)->post(route('categories.store', $postArgs));
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
        $this->actingAs($author)->put(route('categories.update', ['blog_category' => $category]), $postArgs);
        $this->assertDatabaseHas('blog_categories', ['title' => $postArgs['title'], 'content' => $postArgs['content']]);
    }

    /**
     * @test
     */
    public function it_should_delete_an_empty_category(){
        $author = $this->author();
        $category = BlogCategory::factory(1)->create()->first();

        $this->assertDatabaseHas('blog_categories', ['id' => $category->id]);
        $this->actingAs($author)->delete(route('categories.destroy', ['blog_category' => $category]));
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

        $response = $this->actingAs($author)->delete(route('categories.destroy', ['blog_category' => $category]));
        $response->assertSessionHasErrors([
            'generic' => 'This category has 1 post. Move it elsewhere before deleting it.'
        ]);
    }
}
