<?php

namespace App\Http\Requests;

use App\Rules\UpdateProfileRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'name'=>['nullable','string','min:3','max:255'],
            'username' =>['nullable', 'max:255',Rule::unique('users')->ignore(auth()->id()),new UpdateProfileRule()],
            'email'   =>['nullable', 'max:255','email',Rule::unique('users')->ignore(auth()->id())],
        ];
    }
}
