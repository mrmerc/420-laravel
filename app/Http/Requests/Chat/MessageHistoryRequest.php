<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class MessageHistoryRequest extends FormRequest
{
    use SanitizesInput;

    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['roomId'] = $this->route('roomId');
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
            'roomId' => 'required|numeric|min:1|max:'. PHP_INT_MAX . '|exists:\App\Models\Room,id',
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
            'roomId'  => 'trim|escape',
        ];
    }
}
