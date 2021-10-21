<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class TagController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(BlogTag::class,null, [
            'except' => ['index']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlogTag  $blogTag
     * @return \Illuminate\Http\Response
     */
    public function show(BlogTag $blogTag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlogTag  $blogTag
     * @return \Illuminate\Http\Response
     */
    public function edit(BlogTag $blogTag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlogTag  $blogTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlogTag $blogTag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlogTag  $blogTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogTag $blogTag)
    {
        //
    }
}
