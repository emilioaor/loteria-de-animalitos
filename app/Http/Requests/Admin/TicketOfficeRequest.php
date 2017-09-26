<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TicketOfficeRequest extends FormRequest
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
            'username' => 'required|between:4,15',
            'name' => 'required|between:3,40',
            'password' => 'required|between:6,15|confirmed',
        ];
    }

    public function messages() {
        return [
            'username.required' => 'El nombre de usuario es requerido',
            'name.required' => 'El nombre es requerido',
            'password.required' => 'La contraseña es requerida',
            'username.between' => 'El nombre de usuario debe poseer entre :min y :max caracteres',
            'name.between' => 'El nombre debe poseer entre :min y :max caracteres',
            'password.between' => 'La contraseña debe poseer entre :min y :max caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ];
    }
}
