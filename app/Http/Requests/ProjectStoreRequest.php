<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Arrayable;

class ProjectStoreRequest extends FormRequest
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
        $projectId = $this->route('project'); // For update requests
        
        return [
            'title_en' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'unique:projects,title_en,' . $projectId
            ],
            'title_ar' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'unique:projects,title_ar,' . $projectId
            ],
            'description_en' => [
                'required',
                'string',
                'min:10',
                'max:500'
            ],
            'description_ar' => [
                'required',
                'string',
                'min:10',
                'max:500'
            ],
            'content_en' => [
                'sometimes',
                'nullable',
                'string',
                'max:5000'
            ],
            'content_ar' => [
                'sometimes',
                'nullable',
                'string',
                'max:5000'
            ],
            'category' => [
                'required',
                'string',
                'in:web,ai,iot,mobile,desktop'
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
            'challenges_en' => [
                'sometimes',
                'nullable',
                'array',
                new Arrayable()
            ],
            'challenges_en.*' => [
                'required',
                'string',
                'max:255'
            ],
            'challenges_ar' => [
                'sometimes',
                'nullable',
                'array',
                new Arrayable()
            ],
            'challenges_ar.*' => [
                'required',
                'string',
                'max:255'
            ],
            'solutions_en' => [
                'sometimes',
                'nullable',
                'array',
                new Arrayable()
            ],
            'solutions_en.*' => [
                'required',
                'string',
                'max:255'
            ],
            'solutions_ar' => [
                'sometimes',
                'nullable',
                'array',
                new Arrayable()
            ],
            'solutions_ar.*' => [
                'required',
                'string',
                'max:255'
            ],
            'github_url' => [
                'sometimes',
                'nullable',
                'url',
                'max:500'
            ],
            'live_url' => [
                'sometimes',
                'nullable',
                'url',
                'max:500'
            ],
            'demo_url' => [
                'sometimes',
                'nullable',
                'url',
                'max:500'
            ],
            'thumbnail_url' => [
                'sometimes',
                'nullable',
                'url',
                'max:500'
            ],
            'images' => [
                'sometimes',
                'nullable',
                'array',
                'max:10'
            ],
            'images.*' => [
                'required',
                'url',
                'max:500'
            ],
            'tags' => [
                'sometimes',
                'nullable',
                'array',
                'max:10'
            ],
            'tags.*' => [
                'required',
                'string',
                'max:50'
            ],
            'is_featured' => [
                'sometimes',
                'boolean'
            ],
            'is_active' => [
                'sometimes',
                'boolean'
            ],
            'sort_order' => [
                'sometimes',
                'nullable',
                'integer',
                'min:0',
                'max:999'
            ],
            'completed_at' => [
                'sometimes',
                'nullable',
                'date',
                'before_or_equal:today'
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
            'category.required' => __('portfolio.category_required'),
            'category.in' => __('portfolio.category_invalid'),
            'technologies_en.required' => __('portfolio.technologies_en_required'),
            'technologies_en.min' => __('portfolio.technologies_en_min'),
            'technologies_ar.required' => __('portfolio.technologies_ar_required'),
            'technologies_ar.min' => __('portfolio.technologies_ar_min'),
            'github_url.url' => __('portfolio.github_url_invalid'),
            'live_url.url' => __('portfolio.live_url_invalid'),
            'demo_url.url' => __('portfolio.demo_url_invalid'),
            'thumbnail_url.url' => __('portfolio.thumbnail_url_invalid'),
            'completed_at.before_or_equal' => __('portfolio.completed_at_future'),
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
            'content_en' => __('portfolio.content_en'),
            'content_ar' => __('portfolio.content_ar'),
            'category' => __('portfolio.category'),
            'technologies_en' => __('portfolio.technologies_en'),
            'technologies_ar' => __('portfolio.technologies_ar'),
            'challenges_en' => __('portfolio.challenges_en'),
            'challenges_ar' => __('portfolio.challenges_ar'),
            'solutions_en' => __('portfolio.solutions_en'),
            'solutions_ar' => __('portfolio.solutions_ar'),
            'github_url' => __('portfolio.github_url'),
            'live_url' => __('portfolio.live_url'),
            'demo_url' => __('portfolio.demo_url'),
            'thumbnail_url' => __('portfolio.thumbnail_url'),
            'images' => __('portfolio.images'),
            'tags' => __('portfolio.tags'),
            'is_featured' => __('portfolio.is_featured'),
            'is_active' => __('portfolio.is_active'),
            'sort_order' => __('portfolio.sort_order'),
            'completed_at' => __('portfolio.completed_at'),
        ];
    }
}
