<?php

namespace App\Http\Requests;

use App\Models\Specialization;
use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => ['required', 'in:patient,doctor'],
            'phone' => ['required', 'string', 'min:10'],
        ];
    
        if ($this->input('role') === 'patient') {
            $rules['age'] = ['required', 'integer', 'min:1', 'max:120'];
        }
    
        if ($this->input('role') === 'doctor') {
            $rules ['specialization'] = [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Specialization::where('name', $value)->exists()) {
                        $fail("The selected {$attribute} is invalid.");
                    }
                },
            ];
        }
    
        return $rules;
    }
}
