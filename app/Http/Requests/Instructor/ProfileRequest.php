<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,'.Auth::id()],
            'professional_title' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'about_me' => ['nullable', 'string'],
            'gender' => ['nullable', 'in:male,female,other'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['nullable', 'exists:skills,id'],
            'certificate_title' => ['nullable', 'array'],
            'certificate_title.*' => ['nullable', 'string'],
            'certificate_date' => ['nullable', 'array'],
            'certificate_date.*' => ['nullable', 'date'],
            'award_title' => ['nullable', 'array'],
            'award_title.*' => ['nullable', 'string'],
            'award_year' => ['nullable', 'array'],
            'award_year.*' => ['nullable', 'date'],
            'social_link' => ['nullable', 'array'],
            'social_link.*' => ['nullable', 'url'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'dimensions:min_width=300,min_height=300,max_width=300,max_height=300', 'max:1024']
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'certificate_title.*' => 'certificate title',
            'certificate_date.*' => 'certificate date',
            'award_title.*' => 'award title',
            'award_year.*' => 'award year',
        ];
    }
}