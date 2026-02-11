@extends('layouts.app', ['title' => 'Data Peminjaman'])

@section('content')
    <div class="card">
        <div class="actions" style="justify-content: space-between; margin-bottom:10px;">
            <h2 style="margin:0;">Data Peminjaman</h2>
            <a href="{{ route('admin.loans.create') }}" class="btn">Tambah Peminjaman</a>
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
                <thead>
                <tr><th>Kode</th><th>Peminjam</th><th>Alat</th><th>Tanggal</th><th>Qty</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                @forelse($loans as $loan)
                    <tr>
                        <td>{{ $loan->code }}</td>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->tool->name }}</td>
                        <td>{{ $loan->loan_date?->format('d-m-Y') }} s/d {{ $loan->due_date?->format('d-m-Y') }}</td>
                        <td>{{ $loan->qty }}</td>
                        <td><span class="badge badge-{{ $loan->status }}">{{ $loan->status }}</span></td>
                        <td class="actions">
                            <a href="{{ route('admin.loans.edit', $loan) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('admin.loans.destroy', $loan) }}" method="post" data-confirm="Hapus data peminjaman ini?">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">Belum ada data peminjaman.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $loans->links() }}</div>
    </div>
@endsection
