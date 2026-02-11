@extends('layouts.app', ['title' => 'Katalog Alat'])

@section('content')
    <div class="card">
        <h2>Daftar Alat</h2>
        <form method="get" class="actions" style="margin-bottom: 12px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama alat">
            <button type="submit">Cari</button>
        </form>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Stok Tersedia</th><th>Kondisi</th></tr></thead>
                <tbody>
                @forelse($tools as $tool)
                    <tr>
                        <td>{{ $tool->code }}</td>
                        <td>{{ $tool->name }}</td>
                        <td>{{ $tool->category->name }}</td>
                        <td>{{ $tool->available_stock }}</td>
                        <td>{{ $tool->condition }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">Belum ada alat tersedia.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $tools->links() }}</div>
    </div>
@endsection
