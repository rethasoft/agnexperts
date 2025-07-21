<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date_format:Y-m-d H:i:s',
            'end_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:start_date',
            'type' => 'required|string|in:standard,vacation,sick_leave,meeting,personal',
            'status' => 'required|string|in:scheduled,confirmed,completed,cancelled',
            'is_all_day' => 'boolean',
            'is_available' => 'boolean',
            'employee_id' => 'required|exists:employees,id',
            'meta' => 'nullable|array',
        ];
    }
} 