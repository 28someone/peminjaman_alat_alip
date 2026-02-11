<?php

namespace App\Support;

use Carbon\Carbon;

class LoanFineCalculator
{
    public function __construct(
        private readonly int $finePerDay = 0,
        private readonly int $damageFine = 0
    ) {
    }

    public static function fromConfig(): self
    {
        return new self(
            (int) config('loan.fine_per_day', 5000),
            (int) config('loan.damage_fine', 50000)
        );
    }

    public function calculate(?string $dueDate, ?string $receivedDate, ?string $conditionAfterReturn = null): array
    {
        $lateDays = 0;
        if ($dueDate && $receivedDate) {
            $due = Carbon::parse($dueDate)->startOfDay();
            $received = Carbon::parse($receivedDate)->startOfDay();
            $lateDays = max(0, $due->diffInDays($received, false));
        }

        $lateFine = (float) ($lateDays * $this->finePerDay);
        $isDamaged = $this->isDamaged($conditionAfterReturn);
        $damageFine = $isDamaged ? (float) $this->damageFine : 0.0;
        $totalFine = $lateFine + $damageFine;

        return [
            'late_days' => $lateDays,
            'fine_per_day' => $this->finePerDay,
            'late_fine' => $lateFine,
            'is_damaged' => $isDamaged,
            'damage_fine' => $damageFine,
            'fine' => $totalFine,
        ];
    }

    private function isDamaged(?string $conditionAfterReturn): bool
    {
        if (!$conditionAfterReturn) {
            return false;
        }

        $condition = strtolower(trim($conditionAfterReturn));
        if ($condition === '') {
            return false;
        }

        $keywords = ['rusak', 'retak', 'pecah', 'patah', 'mati', 'error', 'tidak berfungsi'];
        foreach ($keywords as $keyword) {
            if (str_contains($condition, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
