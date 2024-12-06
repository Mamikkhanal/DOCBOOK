<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ScheduleCreateRequest extends FormRequest
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
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'start_time' => [
                'required',
                function ($attribute, $value, $fail) {
                    $date = Carbon::parse($this->input('date'));
                    $startTime = Carbon::parse($value);
                    $now = Carbon::now();

                    if ($date->isToday() && $startTime->lessThanOrEqualTo($now)) {
                        $fail('The start time must be after the current time for today.');
                    }
                },
            ],
            'end_time' => [
                'required',
                function ($attribute, $value, $fail) {
                    $date = Carbon::parse($this->input('date'));
                    $endTime = Carbon::parse($value);
                    $startTime = Carbon::parse($this->input('start_time'));
                    $now = Carbon::now();

                    if ($date->isToday() && $endTime->lessThanOrEqualTo($now)) {
                        $fail('The end time must be after the current time for today.');
                    }

                    if ($endTime->lessThanOrEqualTo($startTime)) {
                        $fail('The end time must be after the start time.');
                    }
                },
            ],
        ];
    }
}
