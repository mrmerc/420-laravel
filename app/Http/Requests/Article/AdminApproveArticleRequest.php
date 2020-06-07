<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class AdminApproveArticleRequest extends FormRequest
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
            'articleId' => 'required|numeric|min:1|exists:\App\Models\Article,id',
        ];
    }
}
