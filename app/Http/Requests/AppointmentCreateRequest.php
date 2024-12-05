<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentCreateRequest extends FormRequest
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
            
            'doctor_id' => 'required|exists:doctors,id',

            'service_id' => 'required|exists:services,id',

            'date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'start_time' => [
                'required',
                function ($attribute, $value, $fail) {
                    $date = $this->input('date');
                    if ($date === today()->toDateString() && $value < now()->format('H:i:s')) {
                        $fail('The start time must be after the current time for today.');
                    }
                },
            ],
            'end_time' => [
                'required',
                'after:start_time',
                function ($attribute, $value, $fail) {
                    $date = $this->input('date');
                    if ($date === today()->toDateString() && $value < now()->format('H:i:s')) {
                        $fail('The end time must be after the current time for today.');
                    }
                },
            ],
            'description' => 'required',
        ];
    }
}
