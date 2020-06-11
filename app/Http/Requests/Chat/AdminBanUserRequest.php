<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class AdminBanUserRequest extends FormRequest
{
    use SanitizesInput;
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
            'userId' => 'required|bail|numeric|min:1|max:'. PHP_INT_MAX . '|exists:\App\Models\User,id',
            'deleteMessageHistory' => 'required|boolean'
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     *  @return array
     */
    public function filters()
    {
        return [
            'userId'  => 'trim|escape',
            'deleteMessageHistory' => 'trim|escape',
        ];
    }
}
