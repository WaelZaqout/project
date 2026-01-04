<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'funding_goal' => 'required|numeric|min:0',
            'funded_amount' => 'nullable|numeric|min:0',
            'interest_rate' => 'required|numeric|min:1|max:50',
            'term_months' => 'required|integer|min:1',
            'status' => 'nullable|in:pending,approved,rejected,completed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB
            'gallery' => 'nullable|array', // تأكد أن gallery مصفوفة
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120', // كل صورة في المصفوفة
        ];
    }
}
