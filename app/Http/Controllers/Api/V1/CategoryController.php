<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\V1\BlogCategoryResource;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

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
        $postCount = $blogCategory->posts()->count();
        if($postCount !== 0){
            return response()->json([
                'errors' => ['generic' => 'This category has '.$postCount.' '.Str::plural('post', $postCount).'. Move '.trans_choice('it|them', $postCount).' elsewhere before deleting it.']
            ], Response::HTTP_CONFLICT);
        }

        $blogCategory->delete();
        return response()->noContent();
    }
}
