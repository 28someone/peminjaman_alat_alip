<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Loan extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_BORROWED = 'borrowed';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_RETURN_REQUESTED = 'return_requested';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'code',
        'user_id',
        'tool_id',
        'approved_by',
        'returned_verified_by',
        'loan_date',
        'due_date',
        'return_date',
        'qty',
        'purpose',
        'status',
        'approval_note',
        'return_note',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function returnVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_verified_by');
    }

    public function toolReturn(): HasOne
    {
        return $this->hasOne(ToolReturn::class);
    }

    public function isStockConsumed(): bool
    {
        return in_array($this->status, [
            self::STATUS_BORROWED,
            self::STATUS_RETURN_REQUESTED,
        ], true);
    }
}
