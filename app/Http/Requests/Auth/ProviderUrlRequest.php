<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class ProviderUrlRequest extends FormRequest
{
    use SanitizesInput;

    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['provider'] = $this->route('provider');
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
            'provider' => 'required|string|min:1|max:32',
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
            'provider'  => 'trim|escape',
        ];
    }
}
