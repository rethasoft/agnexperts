<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KeuringFormRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'files.*' => 'mimes:pdf,jpg,jpeg,png|max:10000',
            'invoice' => 'mimes:pdf,jpg,jpeg,png|max:10000',
            // Add more validation rules as needed
        ];
    }

    /**
     * Get the validation messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'files.*.mimes' => 'De factuur moet een bestand zijn van het type: pdf, jpg, jpeg, png.',
            'invoice.mimes' => 'De factuur moet een bestand zijn van het type: pdf, jpg, jpeg, png.',
            // Customize validation messages for specific rules
        ];
    }
}
