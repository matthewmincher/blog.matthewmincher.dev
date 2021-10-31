<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Resources\V1\BlogTagResource;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

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
        return BlogTagResource::collection(BlogTag::all());
    }


    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(StoreTagRequest $request)
    {
        $validated = $request->validated();

        $tag = BlogTag::create($validated);
        return new BlogTagResource($tag);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlogTag  $blogTag
     */
    public function show(BlogTag $blogTag)
    {
        return new BlogTagResource($blogTag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\BlogTag  $blogTag
     */
    public function update(StoreTagRequest $request, BlogTag $blogTag)
    {
        $validated = $request->validated();

        $blogTag->fill($validated)->save();
        return new BlogTagResource($blogTag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlogTag  $blogTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogTag $blogTag)
    {
        $blogTag->posts()->detach();
        $blogTag->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }
}
