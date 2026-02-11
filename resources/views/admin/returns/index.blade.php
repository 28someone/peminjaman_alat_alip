@extends('layouts.app', ['title' => 'Data Pengembalian'])

@section('content')
    <div class="card">
        <div class="actions" style="justify-content: space-between; margin-bottom:10px;">
            <h2 style="margin:0;">Data Pengembalian</h2>
            <a href="{{ route('admin.returns.create') }}" class="btn">Tambah Pengembalian</a>
        </div>
        <form method="get" class="actions" style="margin-bottom: 12px;">
            <select name="status">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status') === 'pending')>pending</option>
                <option value="verified" @selected(request('status') === 'verified')>verified</option>
            </select>
            <button type="submit">Filter</button>
        </form>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kode Loan</th><th>Peminjam</th><th>Alat</th><th>Status</th><th>Denda</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($returns as $return)
                    <tr>
                        <td>{{ $return->loan->code }}</td>
                        <td>{{ $return->loan->user->name }}</td>
                        <td>{{ $return->loan->tool->name }}</td>
                        <td>{{ $return->status }}</td>
                        <td>Rp{{ number_format((float)$return->fine, 0, ',', '.') }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.returns.edit', $return) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('admin.returns.destroy', $return) }}" method="post" data-confirm="Hapus data pengembalian ini?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Belum ada data pengembalian.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $returns->links() }}</div>
    </div>
@endsection
