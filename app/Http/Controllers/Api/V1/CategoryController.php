<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BlogCategoryResource;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(BlogCategory::class, null, [
            'except' => ['index', 'show']
        ]);
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
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     */
    public function edit(BlogCategory $blogCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlogCategory  $blogCategory
     */
    public function update(Request $request, BlogCategory $blogCategory)
    {
        //
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
