<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Email;

class ContactFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow anyone to submit contact form
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s\-\'\.]+$/'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                new Email()
            ],
            'message' => [
                'required',
                'string',
                'min:10',
                'max:1000'
            ],
            'phone' => [
                'sometimes',
                'nullable',
                'string',
                'regex:/^[\+]?[1-9][\d]{0,15}$/'
            ],
            'subject' => [
                'sometimes',
                'nullable',
                'string',
                'max:255'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('portfolio.name_required'),
            'name.min' => __('portfolio.name_min'),
            'name.regex' => __('portfolio.name_invalid'),
            'email.required' => __('portfolio.email_required'),
            'email.email' => __('portfolio.email_invalid'),
            'message.required' => __('portfolio.message_required'),
            'message.min' => __('portfolio.message_min'),
            'message.max' => __('portfolio.message_max'),
            'phone.regex' => __('portfolio.phone_invalid'),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => __('portfolio.name'),
            'email' => __('portfolio.email'),
            'message' => __('portfolio.message'),
            'phone' => __('portfolio.phone'),
            'subject' => __('portfolio.subject'),
        ];
    }
}
