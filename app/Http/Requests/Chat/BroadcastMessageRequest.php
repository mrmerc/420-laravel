<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class BroadcastMessageRequest extends FormRequest
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
            'body' => 'required|bail|string|min:1|max:1024',
            'attachments' => 'array|min:0|max:6',
            'attachments.*.type' => 'required|bail|string|min:1|max:24',
            'attachments.*.source' => 'required|bail|string|min:129',
            'timestamp' => 'required|bail|digits:13',
            'user_id' => 'required|bail|numeric|min:1|max:'. PHP_INT_MAX . '|exists:\App\Models\User,id',
            'room_id' => 'required|bail|numeric|min:1|max:'. PHP_INT_MAX . '|exists:\App\Models\Room,id',
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
            'body'  => 'trim|escape',
            'attachments.*.type' => 'trim|escape',
            'attachments.*.source' => 'trim|escape',
            'timestamp' => 'trim|escape|digit',
            'user_id' => 'trim|escape',
            'room_id' => 'trim|escape',
        ];
    }
}
