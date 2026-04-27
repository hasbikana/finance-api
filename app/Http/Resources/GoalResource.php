<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'target_amount' => (int) $this->target_amount,
            'current_amount' => (int) $this->current_amount,
            'remaining_amount' => (int) max(0, $this->target_amount - $this->current_amount),
            'progress_percentage' => $this->progress_percentage,
            'target_date' => $this->target_date?->toDateString(),
            'status' => $this->status,
            'description' => $this->description,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}