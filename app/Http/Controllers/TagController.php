<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Models\BlogTag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(BlogTag::class);
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return view('tags.index', [
            'tags' => BlogTag::withCount(['publishedPosts'])->orderBy('published_posts_count', 'desc')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(StoreTagRequest $request)
    {
        $validated = $request->validated();

        if(BlogTag::whereSlug(Str::slug($validated['title']))->exists()){
            return redirect()->back()->withInput()->withErrors(['title' => 'The title has already been taken.']);
        }

        $tag = BlogTag::create($validated);

        return redirect()->route('tags.show', ['blog_tag' => $tag]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlogTag  $blogTag
     */
    public function show(BlogTag $blogTag)
    {
        return view('tags.show', [
            'tag' => $blogTag,
            'posts' => $blogTag->publishedPosts()->with(['tags', 'category', 'user'])->paginate(5)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlogTag  $blogTag
     */
    public function edit(BlogTag $blogTag)
    {
        return view('tags.edit', [
            'tag' => $blogTag
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTagRequest $request, BlogTag $blogTag)
    {
        $validated = $request->validated();

        if(BlogTag::whereSlug(Str::slug($validated['title']))->exists()){
            return redirect()->back()->withInput()->withErrors(['title' => 'The title has already been taken.']);
        }

        $blogTag->fill($validated)->save();

        return redirect()->route('tags.show', ['blog_tag' => $blogTag]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlogTag  $blogTag
     */
    public function destroy(BlogTag $blogTag)
    {
        $blogTag->posts()->detach();
        $blogTag->delete();

        return redirect()->route('tags.index');
    }
}
