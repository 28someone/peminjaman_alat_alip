<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\ActivityLogger;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function index(Request $request): View
    {
        $loans = Loan::with(['user', 'tool', 'approver'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.loans.index', compact('loans'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();
        $tools = Tool::where('status', 'active')->orderBy('name')->get();

        return view('admin.loans.create', compact('users', 'tools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);
        $tool = Tool::findOrFail($validated['tool_id']);
        $shouldConsumeStock = $this->consumesStock($validated['status']);

        try {
            DB::transaction(function () use ($validated, $tool, $shouldConsumeStock): void {
                if ($shouldConsumeStock) {
                    if ($tool->available_stock < $validated['qty']) {
                        throw new \RuntimeException('Stok alat tidak mencukupi untuk transaksi ini.');
                    }
                    $tool->decrement('available_stock', $validated['qty']);
                }

                $loan = Loan::create([
                    ...$validated,
                    'code' => $this->generateLoanCode(),
                    'approved_by' => $shouldConsumeStock ? auth()->id() : null,
                    'returned_verified_by' => $validated['status'] === Loan::STATUS_RETURNED ? auth()->id() : null,
                    'return_date' => $validated['status'] === Loan::STATUS_RETURNED ? now()->toDateString() : null,
                ]);

                ActivityLogger::log('admin.loan.create', "Admin menambah transaksi pinjaman {$loan->code}.");
            });
        } catch (\RuntimeException $exception) {
            return back()->with('error', $exception->getMessage())->withInput();
        }

        return redirect()->route('admin.loans.index')->with('success', 'Data peminjaman berhasil ditambahkan.');
    }

    public function edit(Loan $loan): View
    {
        $users = User::orderBy('name')->get();
        $tools = Tool::where('status', 'active')->orderBy('name')->get();

        return view('admin.loans.edit', compact('loan', 'users', 'tools'));
    }

    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $validated = $this->validateData($request);
        $newTool = Tool::findOrFail($validated['tool_id']);

        try {
            DB::transaction(function () use ($loan, $validated, $newTool): void {
                $oldTool = Tool::findOrFail($loan->tool_id);
                $oldConsumes = $this->consumesStock($loan->status);
                $newConsumes = $this->consumesStock($validated['status']);

                if ($oldConsumes) {
                    $oldTool->increment('available_stock', $loan->qty);
                }

                if ($newConsumes && $newTool->available_stock < $validated['qty']) {
                    throw new \RuntimeException('Stok alat tidak mencukupi setelah perubahan data.');
                }

                if ($newConsumes) {
                    $newTool->decrement('available_stock', $validated['qty']);
                }

                $loan->update([
                    ...$validated,
                    'approved_by' => $newConsumes ? ($loan->approved_by ?? auth()->id()) : null,
                    'returned_verified_by' => $validated['status'] === Loan::STATUS_RETURNED ? auth()->id() : null,
                    'return_date' => $validated['status'] === Loan::STATUS_RETURNED ? ($loan->return_date ?? now()->toDateString()) : null,
                ]);

                ActivityLogger::log('admin.loan.update', "Admin mengubah transaksi pinjaman {$loan->code}.");
            });
        } catch (\RuntimeException $exception) {
            return back()->with('error', $exception->getMessage())->withInput();
        }

        return redirect()->route('admin.loans.index')->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function destroy(Loan $loan): RedirectResponse
    {
        DB::transaction(function () use ($loan): void {
            if ($this->consumesStock($loan->status)) {
                $loan->tool()->increment('available_stock', $loan->qty);
            }

            $loan->delete();
            ActivityLogger::log('admin.loan.delete', "Admin menghapus transaksi pinjaman {$loan->code}.");
        });

        return redirect()->route('admin.loans.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'tool_id' => ['required', 'exists:tools,id'],
            'loan_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:loan_date'],
            'qty' => ['required', 'integer', 'min:1'],
            'purpose' => ['required', 'string'],
            'status' => ['required', Rule::in([
                Loan::STATUS_PENDING,
                Loan::STATUS_BORROWED,
                Loan::STATUS_REJECTED,
                Loan::STATUS_RETURN_REQUESTED,
                Loan::STATUS_RETURNED,
                Loan::STATUS_CANCELLED,
            ])],
            'approval_note' => ['nullable', 'string'],
            'return_note' => ['nullable', 'string'],
        ]);
    }

    private function consumesStock(string $status): bool
    {
        return in_array($status, [Loan::STATUS_BORROWED, Loan::STATUS_RETURN_REQUESTED], true);
    }

    private function generateLoanCode(): string
    {
        return 'LOAN-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));
    }
}
