<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class StoreUserPreferencesRequest extends FormRequest
{
    public const PREFERENCE_KEYS = ['email_on_comment'];

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
        $merge = [];
        foreach(self::PREFERENCE_KEYS as $key){
            $merge[$key] = ($this->{$key} ?? 'off') === 'on';
        }

        $this->merge($merge);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        foreach(self::PREFERENCE_KEYS as $key){
            $rules[$key] = 'required|boolean';
        }

        return $rules;
    }
}
