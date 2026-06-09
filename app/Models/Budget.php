<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'amount',
        'spent',
        'period',
        'month',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'integer',
        'spent' => 'integer',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->amount == 0) {
            return 0;
        }
        return min(100, (int) (($this->spent / $this->amount) * 100));
    }

    public function getRemainingAmountAttribute(): int
    {
        return max(0, $this->amount - $this->spent);
    }

    public function getIsOverBudgetAttribute(): bool
    {
        return $this->spent > $this->amount;
    }
}
