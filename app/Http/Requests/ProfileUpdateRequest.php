<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => ['required', 'string', 'max:15'],
        ];
    }
    public function messages(): array
{
    return [
        'name.required' => 'Vui lòng nhập họ và tên.',
        'email.required' => 'Địa chỉ email không được để trống.',
        'email.email' => 'Địa chỉ email không đúng định dạng.',
        'email.unique' => 'Email này đã được sử dụng bởi tài khoản khác.',
        'phone.required' => 'Vui lòng nhập số điện thoại.',
        'phone.min' => 'Số điện thoại chưa hợp lệ.',
    ];
}
}
