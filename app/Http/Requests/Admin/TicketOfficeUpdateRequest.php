<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TicketOfficeUpdateRequest extends FormRequest
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
            'name' => 'required|between:3,40',
            'print_code' => 'required|between:6,7|unique:users',
        ];
    }

    public function messages() {
        return [
            'name.required' => 'El nombre es requerido',
            'name.between' => 'El nombre debe poseer entre :min y :max caracteres',
            'print_code.required' => 'El código de impresión es requerido',
            'print_code.between' => 'El código de impresión debe poseer entre :min y :max caracteres',
            'print_code.unique' => 'El código de impresión ya esta siendo usado',
        ];
    }
}
