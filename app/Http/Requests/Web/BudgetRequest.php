<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class BudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:1'],
            'period' => ['required', 'in:monthly,yearly'],
            'month' => ['nullable', 'string'],
        ];
    }
}
