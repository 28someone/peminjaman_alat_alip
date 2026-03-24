<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $loans = $this->filteredLoans($request)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('petugas.reports.index', compact('loans'));
    }

    public function export(Request $request): Response
    {
        $loans = $this->filteredLoans($request)
            ->latest()
            ->get();

        $filename = 'laporan-peminjaman-' . now()->format('Ymd-His') . '.xls';
        $content = view('petugas.reports.export_excel', compact('loans'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0, no-cache, must-revalidate, proxy-revalidate',
            'Pragma' => 'public',
            'Expires' => '0',
        ]);
    }

    private function filteredLoans(Request $request): Builder
    {
        $start = $request->date('start_date');
        $end = $request->date('end_date');
        $status = $request->string('status')->toString();

        return Loan::with(['user', 'tool'])
            ->when($start, fn (Builder $query) => $query->whereDate('loan_date', '>=', $start))
            ->when($end, fn (Builder $query) => $query->whereDate('loan_date', '<=', $end))
            ->when(filled($status), fn (Builder $query) => $query->where('status', $status));
    }
}
