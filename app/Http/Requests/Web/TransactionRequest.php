<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'wallet_id' => ['nullable', 'integer', 'exists:wallets,id'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'integer', 'min:1'],
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'type.required' => 'Tipe wajib dipilih.',
            'amount.required' => 'Jumlah wajib diisi.',
            'amount.min' => 'Jumlah minimal 1.',
            'date.required' => 'Tanggal wajib diisi.',
        ];
    }
}
