@extends('layouts.app', ['title' => 'Kategori'])

@section('content')
    <div class="card">
        <div class="actions" style="justify-content: space-between; margin-bottom: 10px;">
            <h2 style="margin: 0;">Data Kategori</h2>
            <a href="{{ route('admin.categories.create') }}" class="btn">Tambah Kategori</a>
        </div>

        <form method="get" class="actions" style="margin-bottom: 12px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama kategori">
            <button type="submit">Cari</button>
        </form>

        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama</th><th>Deskripsi</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description ?: '-' }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="post" data-confirm="Hapus kategori ini?">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">Belum ada kategori.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $categories->links() }}</div>
    </div>
@endsection
