<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\ToolReturn;
use App\Support\ActivityLogger;
use App\Support\LoanFineCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function index(Request $request): View
    {
        $loans = Loan::with(['user', 'tool'])
            ->whereIn('status', [Loan::STATUS_PENDING, Loan::STATUS_BORROWED, Loan::STATUS_RETURN_REQUESTED])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('petugas.approvals.index', compact('loans'));
    }

    public function approve(Loan $loan): RedirectResponse
    {
        if ($loan->status !== Loan::STATUS_PENDING) {
            return back()->with('error', 'Hanya peminjaman dengan status pending yang dapat disetujui.');
        }

        if ($loan->tool->available_stock < $loan->qty) {
            return back()->with('error', 'Stok alat tidak mencukupi.');
        }

        DB::transaction(function () use ($loan): void {
            $loan->tool()->decrement('available_stock', $loan->qty);
            $loan->update([
                'status' => Loan::STATUS_BORROWED,
                'approved_by' => auth()->id(),
            ]);

            ActivityLogger::log('petugas.loan.approve', "Petugas menyetujui {$loan->code}.");
        });

        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject(Request $request, Loan $loan): RedirectResponse
    {
        if ($loan->status !== Loan::STATUS_PENDING) {
            return back()->with('error', 'Hanya peminjaman dengan status pending yang dapat ditolak.');
        }

        $data = $request->validate([
            'approval_note' => ['nullable', 'string'],
        ]);

        $loan->update([
            'status' => Loan::STATUS_REJECTED,
            'approved_by' => auth()->id(),
            'approval_note' => $data['approval_note'] ?? null,
        ]);

        ActivityLogger::log('petugas.loan.reject', "Petugas menolak {$loan->code}.");

        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function acceptReturn(Request $request, Loan $loan): RedirectResponse
    {
        if ($loan->status !== Loan::STATUS_RETURN_REQUESTED) {
            return back()->with('error', 'Hanya peminjaman dengan status return_requested yang dapat diterima.');
        }

        $data = $request->validate([
            'return_note' => ['nullable', 'string'],
            'condition_after_return' => ['nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($loan, $data): void {
            $receivedDate = now()->toDateString();
            $fine = LoanFineCalculator::fromConfig()
                ->calculate(
                    $loan->due_date?->toDateString(),
                    $receivedDate,
                    $data['condition_after_return'] ?? null
                )['fine'];

            $return = ToolReturn::updateOrCreate(
                ['loan_id' => $loan->id],
                [
                    'requested_return_date' => now()->toDateString(),
                    'received_date' => $receivedDate,
                    'status' => 'verified',
                    'condition_after_return' => $data['condition_after_return'] ?? null,
                    'fine' => $fine,
                    'note' => $data['return_note'] ?? 'Pengembalian diterima oleh petugas.',
                    'processed_by' => auth()->id(),
                ]
            );

            $this->finalizeReturn($loan, $return);

            ActivityLogger::log('petugas.return.accept', "Petugas menerima pengembalian {$loan->code}.");
        });

        return back()->with('success', 'Pengembalian berhasil diterima.');
    }

    public function rejectReturn(Request $request, Loan $loan): RedirectResponse
    {
        if ($loan->status !== Loan::STATUS_RETURN_REQUESTED) {
            return back()->with('error', 'Hanya peminjaman dengan status return_requested yang dapat ditolak.');
        }

        $data = $request->validate([
            'return_note' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($loan, $data): void {
            ToolReturn::updateOrCreate(
                ['loan_id' => $loan->id],
                [
                    'requested_return_date' => now()->toDateString(),
                    'status' => 'pending',
                    'note' => $data['return_note'] ?? 'Permintaan pengembalian ditolak oleh petugas.',
                    'processed_by' => auth()->id(),
                ]
            );

            $loan->update([
                'status' => Loan::STATUS_BORROWED,
                'return_note' => $data['return_note'] ?? null,
                'returned_verified_by' => null,
                'return_date' => null,
            ]);

            ActivityLogger::log('petugas.return.reject', "Petugas menolak pengembalian {$loan->code}.");
        });

        return back()->with('success', 'Permintaan pengembalian berhasil ditolak.');
    }

    public function monitorReturns(Request $request): View
    {
        $loans = Loan::with(['user', 'tool', 'toolReturn'])
            ->whereIn('status', [Loan::STATUS_RETURN_REQUESTED, Loan::STATUS_RETURNED])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('petugas.returns.index', compact('loans'));
    }

    private function finalizeReturn(Loan $loan, ToolReturn $return): void
    {
        if ($loan->status !== Loan::STATUS_RETURNED) {
            $loan->tool()->increment('available_stock', $loan->qty);
        }

        $loan->update([
            'status' => Loan::STATUS_RETURNED,
            'return_date' => $return->received_date ?? now()->toDateString(),
            'returned_verified_by' => auth()->id(),
            'return_note' => $return->note,
        ]);
    }
}
