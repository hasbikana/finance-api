<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class QuickTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'integer', 'min:1'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'wallet_id' => ['nullable', 'integer', 'exists:wallets,id'],
            'date' => ['nullable', 'date', 'date_format:Y-m-d'],
        ];
    }
}
