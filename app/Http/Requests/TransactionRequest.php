<?php

namespace App\Http\Requests;

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
            'category_id' => ['required', 'integer'],
            'wallet_id' => ['nullable', 'integer'],
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
            'category_id.integer' => 'ID kategori tidak valid.',
            'type.required' => 'Tipe transaksi wajib dipilih.',
            'type.in' => 'Tipe harus income atau expense.',
            'amount.required' => 'Jumlah wajib diisi.',
            'amount.integer' => 'Jumlah harus berupa angka.',
            'amount.min' => 'Jumlah harus lebih dari 0.',
            'date.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'date.date_format' => 'Format tanggal harus YYYY-MM-DD.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
        ];
    }
}