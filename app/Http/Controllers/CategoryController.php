<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(BlogCategory::class);
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return view('categories.index', [
            'categories' => BlogCategory::orderBy('title')->withCount(['posts'])->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = BlogCategory::create($validated);

        return redirect()->route('categories.show', ['blog_category' => $category]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     */
    public function show(BlogCategory $blogCategory)
    {
        return view('categories.show', [
            'category' => $blogCategory,
            'posts' => $blogCategory->posts()->paginate(5)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     */
    public function edit(BlogCategory $blogCategory)
    {
        return view('categories.edit', [
            'category' => $blogCategory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoryRequest $request, BlogCategory $blogCategory)
    {
        $validated = $request->validated();
        $blogCategory->fill($validated)->save();

        return redirect()->route('categories.show', ['blog_category' => $blogCategory]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     */
    public function destroy(BlogCategory $blogCategory)
    {
        $postCount = $blogCategory->posts()->count();
        if($postCount !== 0){
            //todo: feels like this should be done/configured somewhere else and parsed? Localization?
            return redirect()->back()->withErrors(['generic' => 'This category has '.$postCount.' '.Str::plural('post', $postCount).'. Move '.trans_choice('it|them', $postCount).' elsewhere before deleting it.']);
        }

        $blogCategory->delete();

        return redirect()->route('categories.index');
    }
}
