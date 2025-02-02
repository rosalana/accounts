<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Rosalana\Accounts\Facades\RosalanaAuth;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Attempt to register the request's credentials.
     *
     * @return void
     *
     * @throws \Rosalana\Accounts\Exceptions\RosalanaAuthException
     * @throws \Rosalana\Accounts\Exceptions\RosalanaCredentialsException
     */
    public function register()
    {
        RosalanaAuth::register($this->only('name', 'email', 'password', 'password_confirmation'));
    }
}
