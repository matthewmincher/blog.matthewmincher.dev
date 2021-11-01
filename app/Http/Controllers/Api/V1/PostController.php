<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\V1\BlogPostResource;
use App\Models\BlogPost;
use App\Services\BlogPostService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(BlogPost::class);
    }

    private static function getPostExpandables(Request $request){
        $expandable = ['category', 'tags', 'user'];
        return array_intersect(explode(',', $request->query('expand', '')), $expandable);
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

        $expand = self::getPostExpandables($request);

        return BlogPostResource::collection($posts->with($expand)->paginate(5));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request, BlogPostService $postService)
    {
        $validated = $request->validated();

        $post = $postService->createPostForUser($validated, $request->user());

        $expand = self::getPostExpandables($request);
        return new BlogPostResource($post->load($expand));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, BlogPost $blogPost)
    {
        $expand = self::getPostExpandables($request);
        return new BlogPostResource($blogPost->load($expand));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePostRequest $request, BlogPost $blogPost, BlogPostService $postService)
    {
        $validated = $request->validated();
        $postService->updatePost($validated, $blogPost);

        $expand = self::getPostExpandables($request);
        return new BlogPostResource($blogPost->load($expand));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blogPost, BlogPostService $blogPostService)
    {
        $blogPostService->deletePost($blogPost);

        return response('', Response::HTTP_NO_CONTENT);
    }
}
