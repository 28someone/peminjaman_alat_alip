@extends('layouts.app', ['title' => 'Monitoring Pengembalian'])

@section('content')
    <div class="card">
        <h2>Monitoring Pengembalian</h2>
        <form method="get" class="actions" style="margin-bottom: 12px;">
            <select name="status">
                <option value="">Semua Status</option>
                <option value="return_requested" @selected(request('status') === 'return_requested')>return_requested</option>
                <option value="returned" @selected(request('status') === 'returned')>returned</option>
            </select>
            <button type="submit">Filter</button>
        </form>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kode</th><th>Peminjam</th><th>Alat</th><th>Status</th><th>Tgl Kembali</th><th>Info Return</th></tr></thead>
                <tbody>
                @forelse($loans as $loan)
                    <tr>
                        <td>{{ $loan->code }}</td>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->tool->name }}</td>
                        <td><span class="badge badge-{{ $loan->status }}">{{ $loan->status }}</span></td>
                        <td>{{ $loan->return_date?->format('d-m-Y') ?? '-' }}</td>
                        <td>
                            @if($loan->toolReturn)
                                {{ $loan->toolReturn->status }} | Denda: Rp{{ number_format((float)$loan->toolReturn->fine, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Belum ada data monitoring.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $loans->links() }}</div>
    </div>
@endsection
