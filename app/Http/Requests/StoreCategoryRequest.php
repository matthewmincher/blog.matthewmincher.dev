<?php

namespace App\Http\Requests;

use App\Models\BlogCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class StoreCategoryRequest extends FormRequest
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
        /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => "required|min:3|max:30",
            'content' => 'required|min:10'
        ];
    }

    public function withValidator(Validator $validator){
        $validator->after(function(Validator $validator){
            $this->validateSlug($validator);
        });
    }

    protected function validateSlug(Validator $validator){
        $slug = Str::slug($this->title);

        $categoryWithSlug = BlogCategory::whereSlug($slug);
        if($this->blog_category !== null){
            $categoryWithSlug->where('id', '!=', $this->blog_category->id);
        }

        if($categoryWithSlug->exists()){
            $validator->errors()->add('title', 'The title has already been taken');
        }
    }
}
