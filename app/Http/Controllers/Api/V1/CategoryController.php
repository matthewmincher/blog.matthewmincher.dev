<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\V1\BlogCategoryResource;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {

        return BlogCategoryResource::collection(BlogCategory::all());
    }


    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        $category = BlogCategory::create($validated);
        return new BlogCategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     */
    public function show(BlogCategory $blogCategory)
    {
        return new BlogCategoryResource($blogCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(StoreCategoryRequest $request, BlogCategory $blogCategory)
    {
        $validated = $request->validated();
        $blogCategory->fill($validated)->save();

        return new BlogCategoryResource($blogCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     */
    public function destroy(BlogCategory $blogCategory)
    {
        if($blogCategory->posts_count > 0){
            return response()->setStatusCode(409)->json([
                'message' => 'That category has '.$blogCategory->posts_count.' posts. Move them elsewhere before deleting it.'
            ]);
        }

        $blogCategory->delete();
        return response()->noContent();
    }
}
