<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\ToolReturn;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoanRequestController extends Controller
{
    public function index(Request $request): View
    {
        $loans = Loan::with(['tool', 'toolReturn'])
            ->where('user_id', auth()->id())
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peminjam.loans.index', compact('loans'));
    }

    public function create(): View
    {
        $tools = Tool::with('category')
            ->where('status', 'active')
            ->where('available_stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('peminjam.loans.create', compact('tools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tool_id' => ['required', 'exists:tools,id'],
            'loan_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:loan_date'],
            'qty' => ['required', 'integer', 'min:1'],
            'purpose' => ['required', 'string'],
        ]);

        $tool = Tool::findOrFail($validated['tool_id']);
        if ($validated['qty'] > $tool->total_stock) {
            return back()->withErrors([
                'qty' => 'Jumlah pinjam melebihi total stok alat.',
            ])->withInput();
        }

        Loan::create([
            ...$validated,
            'code' => 'LOAN-'.now()->format('Ymd').'-'.Str::upper(Str::random(5)),
            'user_id' => auth()->id(),
            'status' => Loan::STATUS_PENDING,
        ]);

        ActivityLogger::log('peminjam.loan.create', 'Peminjam mengajukan peminjaman alat.');

        return redirect()->route('peminjam.loans.index')->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }

    public function requestReturn(Loan $loan): RedirectResponse
    {
        if ($loan->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($loan->status, [Loan::STATUS_BORROWED, Loan::STATUS_RETURN_REQUESTED], true)) {
            return back()->with('error', 'Peminjaman ini belum dapat diajukan pengembalian.');
        }

        $loan->update(['status' => Loan::STATUS_RETURN_REQUESTED]);

        ToolReturn::updateOrCreate(
            ['loan_id' => $loan->id],
            [
                'requested_return_date' => now()->toDateString(),
                'status' => 'pending',
                'note' => 'Permintaan pengembalian dari peminjam.',
            ]
        );

        ActivityLogger::log('peminjam.loan.request_return', "Peminjam mengajukan pengembalian {$loan->code}.");

        return back()->with('success', 'Permintaan pengembalian berhasil dikirim.');
    }
}
