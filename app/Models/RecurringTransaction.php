<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'wallet_id',
        'type',
        'amount',
        'description',
        'frequency',
        'start_date',
        'end_date',
        'last_run',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'last_run' => 'datetime',
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

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
