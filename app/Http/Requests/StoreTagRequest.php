<?php

namespace App\Http\Requests;

use App\Models\BlogTag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class StoreTagRequest extends FormRequest
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
            'title' => 'required|min:3|max:30',
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

        $tagWithSlug = BlogTag::whereSlug($slug);
        if($this->blog_tag !== null){
            $tagWithSlug->where('id', '!=', $this->blog_tag->id);
        }

        if($tagWithSlug->exists()){
            $validator->errors()->add('title', 'The title has already been taken');
        }
    }
}
