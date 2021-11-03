<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Resources\V1\BlogCategoryResource;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use App\Services\BlogPostService;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(BlogPost::class);
    }



    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(optional($request->user())->is_author){
            $posts = BlogPost::ordered();
        } else {
            $posts = BlogPost::published()->ordered();
        }

        return view('posts.index', [
            'posts' => $posts->withCount(['comments'])->with(['category', 'tags', 'user'])->paginate(5)
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
    public function store(StorePostRequest $request, BlogPostService $postService)
    {
        $validated = $request->validated();

        $post = $postService->createPostForUser($validated, $request->user());

        return redirect()->route('posts.show', ['blog_post' => $post]);
    }



    /**
     * Display the specified resource.
     *
     * @param  BlogPost  $blogPost
     */
    public function show(Request $request, BlogPost $blogPost)
    {
        $combinedSlug = $request->route()->originalParameter('blog_post');
        if($combinedSlug !== $blogPost->combined_slug){
            return redirect()->route('posts.show', ['blog_post' => $blogPost])->setStatusCode(Response::HTTP_MOVED_PERMANENTLY);
        }

        $blogPost->load(['comments.user']);

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
    public function update(StorePostRequest $request, BlogPost $blogPost, BlogPostService $postService)
    {
        $validated = $request->validated();

        $postService->updatePost($validated, $blogPost);

        return redirect()->route('posts.show', ['blog_post' => $blogPost]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blogPost, BlogPostService $postService)
    {
        $postService->deletePost($blogPost);

        return redirect()->route('posts.index');
    }
}
