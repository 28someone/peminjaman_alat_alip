<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\ToolReturn;
use App\Support\ActivityLogger;
use App\Support\LoanFineCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function index(Request $request): View
    {
        $returns = ToolReturn::with(['loan.user', 'loan.tool', 'processor'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.returns.index', compact('returns'));
    }

    public function create(): View
    {
        $loans = Loan::with(['user', 'tool'])
            ->whereIn('status', [Loan::STATUS_BORROWED, Loan::STATUS_RETURN_REQUESTED])
            ->orderByDesc('id')
            ->get();

        return view('admin.returns.create', [
            'loans' => $loans,
            'finePerDay' => $this->finePerDay(),
            'damageFine' => $this->damageFine(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);
        $loan = Loan::with('tool')->findOrFail($validated['loan_id']);

        if ($loan->status === Loan::STATUS_RETURNED) {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan.');
        }

        DB::transaction(function () use ($validated, $loan): void {
            $payload = [
                ...$validated,
                'processed_by' => auth()->id(),
            ];

            if ($validated['status'] === 'verified') {
                $payload['received_date'] = $validated['received_date'] ?? now()->toDateString();
                $payload['fine'] = $this->calculateFine(
                    $loan,
                    $payload['received_date'],
                    $validated['condition_after_return'] ?? null
                );
            } else {
                $payload['fine'] = 0;
            }

            $return = ToolReturn::updateOrCreate(
                ['loan_id' => $validated['loan_id']],
                $payload
            );

            if ($return->status === 'verified') {
                $this->finalizeReturn($loan, $return);
            }

            ActivityLogger::log('admin.return.create', "Admin memproses pengembalian untuk {$loan->code}.");
        });

        return redirect()->route('admin.returns.index')->with('success', 'Data pengembalian berhasil disimpan.');
    }

    public function edit(ToolReturn $toolReturn): View
    {
        $toolReturn->load(['loan.user', 'loan.tool']);
        $loans = Loan::with(['user', 'tool'])->orderByDesc('id')->get();

        return view('admin.returns.edit', [
            'return' => $toolReturn,
            'loans' => $loans,
            'finePerDay' => $this->finePerDay(),
            'damageFine' => $this->damageFine(),
        ]);
    }

    public function update(Request $request, ToolReturn $toolReturn): RedirectResponse
    {
        $validated = $this->validateData($request);

        DB::transaction(function () use ($validated, $toolReturn): void {
            $loan = Loan::with('tool')->findOrFail($validated['loan_id']);
            $payload = [
                ...$validated,
                'processed_by' => auth()->id(),
            ];

            if ($validated['status'] === 'verified') {
                $payload['received_date'] = $validated['received_date'] ?? now()->toDateString();
                $payload['fine'] = $this->calculateFine(
                    $loan,
                    $payload['received_date'],
                    $validated['condition_after_return'] ?? null
                );
            } else {
                $payload['fine'] = 0;
            }

            $toolReturn->update([
                ...$payload,
            ]);

            if ($toolReturn->status === 'verified') {
                $this->finalizeReturn($loan, $toolReturn);
            }

            ActivityLogger::log('admin.return.update', "Admin mengubah pengembalian ID {$toolReturn->id}.");
        });

        return redirect()->route('admin.returns.index')->with('success', 'Data pengembalian berhasil diperbarui.');
    }

    public function destroy(ToolReturn $toolReturn): RedirectResponse
    {
        if ($toolReturn->status === 'verified') {
            return back()->with('error', 'Pengembalian terverifikasi tidak boleh dihapus.');
        }

        $toolReturn->delete();
        ActivityLogger::log('admin.return.delete', "Admin menghapus data pengembalian ID {$toolReturn->id}.");

        return redirect()->route('admin.returns.index')->with('success', 'Data pengembalian berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'loan_id' => ['required', 'exists:loans,id'],
            'requested_return_date' => ['nullable', 'date'],
            'received_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['pending', 'verified'])],
            'condition_after_return' => ['nullable', 'string', 'max:100'],
            'fine' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
        ]);
    }

    private function calculateFine(Loan $loan, ?string $receivedDate, ?string $conditionAfterReturn): float
    {
        return LoanFineCalculator::fromConfig()
            ->calculate($loan->due_date?->toDateString(), $receivedDate, $conditionAfterReturn)['fine'];
    }

    private function finePerDay(): int
    {
        return (int) config('loan.fine_per_day', 5000);
    }

    private function damageFine(): int
    {
        return (int) config('loan.damage_fine', 50000);
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
