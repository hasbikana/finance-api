<?php

namespace App\Http\Requests;

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

    public function messages(): array
    {
        return [
            'name.required' => 'Nama target wajib diisi.',
            'name.max' => 'Nama target maksimal 255 karakter.',
            'target_amount.required' => 'Jumlah target wajib diisi.',
            'target_amount.integer' => 'Jumlah target harus berupa angka.',
            'target_amount.min' => 'Jumlah target minimal 1.',
            'target_date.date' => 'Format tanggal tidak valid.',
            'target_date.date_format' => 'Format tanggal harus YYYY-MM-DD.',
            'target_date.after' => 'Tanggal target harus setelah hari ini.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
        ];
    }
}