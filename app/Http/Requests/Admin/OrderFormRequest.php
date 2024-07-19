<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|integer',
            'user_id' => 'nullable|integer',
            'status' => 'nullable|string',
            'payment' => 'required|string',
            'numOrder' => 'nullable|string',
            'orderDate' => 'nullable|date',
            'total' => 'required|string',
            'products' => 'required|array|min:1',
            'quantities' => 'required|array|min:1',
            'firstname' => 'nullable|string',
            'lastname' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'sex' => 'nullable|string',
        ];
    }
}
