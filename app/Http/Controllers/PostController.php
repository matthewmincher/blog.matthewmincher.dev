<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\V1\BlogCategoryResource;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(BlogPost::class,null, [
            'except' => ['index']
        ]);
    }

    private static function synchroniseTags($tagList, BlogPost $blogPost){
        $existingTags = BlogTag::all();
        $selectedTags = new Collection();

        foreach($tagList as $tagTitle){
            $tag = $existingTags->firstWhere('title', $tagTitle);

            if($tag === null){
                $tag = BlogTag::create(['title' => $tagTitle, 'slug' => Str::slug($tagTitle)]);
            }

            $selectedTags->add($tag);
        }

        $blogPost->tags()->sync($selectedTags);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('posts.index', [
            'posts' => BlogPost::with(['category', 'tags', 'user'])->paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tagNames = [];
        foreach(BlogTag::all() as $tag){
            $tagNames[] = $tag->title;
        }

        return view('posts.create', [
            'categories' => BlogCategoryResource::collection(BlogCategory::orderBy('title')->get()),
            'tagNames' => $tagNames
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePostRequest  $request
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['title']);
        $validated['published'] = false;

        $post = $request->user()->posts()->create($validated);

        self::synchroniseTags($validated['tags'], $post);

        return redirect()->route('posts.show', ['blog_post' => $post]);
    }



    /**
     * Display the specified resource.
     *
     * @param  BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function show(BlogPost $blogPost)
    {

        return view('posts.show', [
            'post' => $blogPost
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  BlogPost  $blogPost
     */
    public function edit(BlogPost $blogPost)
    {

        $tagNames = [];
        foreach(BlogTag::all() as $tag){
            $tagNames[] = $tag->title;
        }

        $tagifyValue = json_encode(
            $blogPost->tags->map(function(BlogTag $tag){
                return ['value' => $tag->title];
            })
        );

        return view('posts.edit', [
            'tagNames' => $tagNames,
            'categories' => BlogCategoryResource::collection(BlogCategory::orderBy('title')->get()),
            'post' => $blogPost,
            'tagifyValue' => $tagifyValue,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StorePostRequest $request
     * @param  BlogPost  $blogPost
     */
    public function update(StorePostRequest $request, BlogPost $blogPost)
    {
        $validated = $request->validated();

        $blogPost->fill($validated)->save();
        self::synchroniseTags($validated['tags'], $blogPost);

        return redirect()->route('posts.show', ['blog_post' => $blogPost]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogPost $blogPost)
    {
        //
    }
}
