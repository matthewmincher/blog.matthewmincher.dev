<?php

namespace App\Http\Resources\V1;

use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin BlogTag
 */
class BlogTagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content
        ];
    }
}
