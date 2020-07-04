<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @urlParam articleId required Article ID (min: 1, max: PHP_INT_MAX). No-example
 */
class GetArticleRequest extends FormRequest
{
    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['article_id'] = $this->route('article_id');
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
            'article_id' => 'required|numeric|min:1|max:'. PHP_INT_MAX . '|exists:\App\Models\Article,id',
        ];
    }
}
