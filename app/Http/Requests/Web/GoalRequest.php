<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class GoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'target_amount' => ['required', 'integer', 'min:1'],
            'target_date' => ['nullable', 'date', 'date_format:Y-m-d', 'after:today'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
