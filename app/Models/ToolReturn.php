<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'requested_return_date',
        'received_date',
        'status',
        'condition_after_return',
        'fine',
        'note',
        'processed_by',
    ];

    protected function casts(): array
    {
        return [
            'requested_return_date' => 'date',
            'received_date' => 'date',
            'fine' => 'decimal:2',
        ];
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
