@extends('layouts.app', ['title' => 'Peminjaman Saya'])

@section('content')
    <div class="card">
        <div class="actions" style="justify-content: space-between; margin-bottom: 10px;">
            <h2 style="margin:0;">Peminjaman Saya</h2>
            <a href="{{ route('peminjam.loans.create') }}" class="btn">Ajukan Peminjaman</a>
        </div>

        <form method="get" class="actions" style="margin-bottom: 12px;">
            <select name="status">
                <option value="">Semua Status</option>
                @foreach(['pending','borrowed','rejected','return_requested','returned','cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
            <button type="submit">Filter</button>
        </form>

        <div class="table-wrap">
            <table>
                <thead><tr><th>Kode</th><th>Alat</th><th>Tanggal</th><th>Qty</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($loans as $loan)
                    <tr>
                        <td>{{ $loan->code }}</td>
                        <td>{{ $loan->tool->name }}</td>
                        <td>{{ $loan->loan_date?->format('d-m-Y') }} s/d {{ $loan->due_date?->format('d-m-Y') }}</td>
                        <td>{{ $loan->qty }}</td>
                        <td><span class="badge badge-{{ $loan->status }}">{{ $loan->status }}</span></td>
                        <td>
                            @if(in_array($loan->status, ['borrowed', 'return_requested']))
                                <form action="{{ route('peminjam.loans.request-return', $loan) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">Ajukan Pengembalian</button>
                                </form>
                            @else
                                <span class="muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Belum ada riwayat peminjaman.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $loans->links() }}</div>
    </div>
@endsection
