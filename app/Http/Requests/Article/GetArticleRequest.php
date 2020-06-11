<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class GetArticleRequest extends FormRequest
{
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['articleId'] = $this->route('articleId');
        return $data;
    }

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
            'articleId' => 'required|numeric|min:1|max:'. PHP_INT_MAX . '|exists:\App\Models\Article,id',
        ];
    }
}
