<?php

namespace App\Http\Requests\Index;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'username' => 'required|max:15',
            'password' => 'required|max:15',
        ];
    }

    /**
     * @return array
     */
    public function messages() {
        return [
            'username.required' => 'El usuario es requerido',
            'username.max' => 'El usuario no puede superar los :max caracteres',
            'password.required' => 'La contraseña es requerida',
            'password.max' => 'La contraseña no puede superar los :max caracteres',
        ];
    }
}
