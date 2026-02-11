<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $start = $request->date('start_date');
        $end = $request->date('end_date');
        $status = $request->string('status')->toString();

        $loans = Loan::with(['user', 'tool'])
            ->when($start, fn ($query) => $query->whereDate('loan_date', '>=', $start))
            ->when($end, fn ($query) => $query->whereDate('loan_date', '<=', $end))
            ->when(filled($status), fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('petugas.reports.index', compact('loans'));
    }
}
