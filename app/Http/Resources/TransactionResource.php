<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
            'category_name' => $this->category?->name,
            'wallet_id' => $this->wallet_id,
            'wallet_name' => $this->wallet?->name,
            'type' => $this->type,
            'amount' => (int) $this->amount,
            'description' => $this->description,
            'date' => $this->date->toDateString(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}