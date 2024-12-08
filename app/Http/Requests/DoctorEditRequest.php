<?php

namespace App\Http\Requests;

use App\Models\Specialization;
use Illuminate\Foundation\Http\FormRequest;

class DoctorEditRequest extends FormRequest
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
            'specialization' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Specialization::where('name', $value)->exists()) {
                        $fail("The selected {$attribute} is invalid.");
                    }
                },
            ],
        ];   
    }
}
