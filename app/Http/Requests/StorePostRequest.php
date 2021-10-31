<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tags' => $this->formatTags($this->tags),
            'published' => ($this->published ?? 'off') === 'on'
        ]);
    }

    protected function formatTags(?string $tags){
        if($tags === null){
            return null;
        }

        $array = json_decode($tags);

        if(is_array($array)){
            $array = Arr::pluck($array, 'value');
        }

        return $array;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'title' => 'required|min:3|max:30',
            'content' => 'required|min:10',
            'blog_category_id' => 'required|exists:App\Models\BlogCategory,id',
            'tags' => 'nullable|array',
            'tags.*' => 'required|min:3|max:30',
            'published' => 'required|boolean',
            'slug' => 'nullable|alpha_dash|min:3|max:30'
        ];
    }
}
