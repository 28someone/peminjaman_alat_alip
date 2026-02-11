@extends('layouts.app', ['title' => 'Data Alat'])

@section('content')
    <div class="card">
        <div class="actions" style="justify-content: space-between; margin-bottom: 10px;">
            <h2 style="margin:0;">Data Alat</h2>
            <a href="{{ route('admin.tools.create') }}" class="btn">Tambah Alat</a>
        </div>
        <form method="get" class="actions" style="margin-bottom: 12px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama alat">
            <button type="submit">Cari</button>
        </form>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Stok</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($tools as $tool)
                    <tr>
                        <td>{{ $tool->code }}</td>
                        <td>{{ $tool->name }}<div class="muted">{{ $tool->location ?: '-' }}</div></td>
                        <td>{{ $tool->category->name }}</td>
                        <td>{{ $tool->available_stock }}/{{ $tool->total_stock }}</td>
                        <td>{{ $tool->status }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.tools.edit', $tool) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('admin.tools.destroy', $tool) }}" method="post" data-confirm="Hapus alat ini?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Belum ada alat.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $tools->links() }}</div>
    </div>
@endsection
