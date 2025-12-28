<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Arrayable;

class ServiceStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // For now, allow anyone. Add authentication later if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $serviceId = $this->route('service'); // For update requests
        
        return [
            'title_en' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'unique:services,title_en,' . $serviceId
            ],
            'title_ar' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'unique:services,title_ar,' . $serviceId
            ],
            'description_en' => [
                'required',
                'string',
                'min:10',
                'max:1000'
            ],
            'description_ar' => [
                'required',
                'string',
                'min:10',
                'max:1000'
            ],
            'technologies_en' => [
                'required',
                'array',
                'min:1',
                new Arrayable()
            ],
            'technologies_en.*' => [
                'required',
                'string',
                'max:100'
            ],
            'technologies_ar' => [
                'required',
                'array',
                'min:1',
                new Arrayable()
            ],
            'technologies_ar.*' => [
                'required',
                'string',
                'max:100'
            ],
            'features_en' => [
                'sometimes',
                'nullable',
                'array',
                new Arrayable()
            ],
            'features_en.*' => [
                'required',
                'string',
                'max:255'
            ],
            'features_ar' => [
                'sometimes',
                'nullable',
                'array',
                new Arrayable()
            ],
            'features_ar.*' => [
                'required',
                'string',
                'max:255'
            ],
            'icon' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9\-_]+$/'
            ],
            'image_url' => [
                'sometimes',
                'nullable',
                'url',
                'max:500'
            ],
            'sort_order' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
                'max:999'
            ],
            'is_active' => [
                'sometimes',
                'boolean'
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
            'title_en.required' => __('portfolio.title_en_required'),
            'title_en.unique' => __('portfolio.title_en_unique'),
            'title_ar.required' => __('portfolio.title_ar_required'),
            'title_ar.unique' => __('portfolio.title_ar_unique'),
            'description_en.required' => __('portfolio.description_en_required'),
            'description_en.min' => __('portfolio.description_en_min'),
            'description_ar.required' => __('portfolio.description_ar_required'),
            'description_ar.min' => __('portfolio.description_ar_min'),
            'technologies_en.required' => __('portfolio.technologies_en_required'),
            'technologies_en.min' => __('portfolio.technologies_en_min'),
            'technologies_ar.required' => __('portfolio.technologies_ar_required'),
            'technologies_ar.min' => __('portfolio.technologies_ar_min'),
            'icon.required' => __('portfolio.icon_required'),
            'icon.regex' => __('portfolio.icon_invalid'),
            'image_url.url' => __('portfolio.image_url_invalid'),
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
            'title_en' => __('portfolio.title_en'),
            'title_ar' => __('portfolio.title_ar'),
            'description_en' => __('portfolio.description_en'),
            'description_ar' => __('portfolio.description_ar'),
            'technologies_en' => __('portfolio.technologies_en'),
            'technologies_ar' => __('portfolio.technologies_ar'),
            'features_en' => __('portfolio.features_en'),
            'features_ar' => __('portfolio.features_ar'),
            'icon' => __('portfolio.icon'),
            'image_url' => __('portfolio.image_url'),
            'sort_order' => __('portfolio.sort_order'),
            'is_active' => __('portfolio.is_active'),
        ];
    }
}
