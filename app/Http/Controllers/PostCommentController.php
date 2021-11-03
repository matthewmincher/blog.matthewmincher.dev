<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostCommentRequest;
use App\Models\BlogPost;
use App\Models\Comment;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostCommentRequest $request, BlogPost $blogPost)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        $blogPost->comments()->create($validated);

        return redirect()->route('posts.show', ['blog_post' => $blogPost]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blogPost, Comment $comment)
    {
        $comment->delete();
        return redirect()->back();
    }
}
