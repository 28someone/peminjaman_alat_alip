@extends('layouts.app', ['title' => 'Laporan Peminjaman'])

@section('content')
    <div class="card">
        <h2>Laporan Peminjaman</h2>
        <form method="get" class="grid grid-2" style="margin-bottom:12px;">
            <div><label>Tanggal Mulai</label><input type="date" name="start_date" value="{{ request('start_date') }}"></div>
            <div><label>Tanggal Selesai</label><input type="date" name="end_date" value="{{ request('end_date') }}"></div>
            <div>
                <label>Status</label>
                <select name="status">
                    <option value="">Semua</option>
                    @foreach(['pending','borrowed','rejected','return_requested','returned','cancelled'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="actions" style="align-items: end;"><button type="submit">Tampilkan</button></div>
        </form>
        <p class="muted">Gunakan Ctrl+P untuk mencetak halaman ini sebagai laporan.</p>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kode</th><th>Peminjam</th><th>Alat</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($loans as $loan)
                    <tr>
                        <td>{{ $loan->code }}</td>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->tool->name }}</td>
                        <td>{{ $loan->loan_date?->format('d-m-Y') }}</td>
                        <td>{{ $loan->due_date?->format('d-m-Y') }}</td>
                        <td><span class="badge badge-{{ $loan->status }}">{{ $loan->status }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6">Tidak ada data laporan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $loans->links() }}</div>
    </div>
@endsection
