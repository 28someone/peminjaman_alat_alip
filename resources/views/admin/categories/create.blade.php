@extends('layouts.app', ['title' => 'Tambah Kategori'])

@section('content')
    <div class="card">
        <h2>Tambah Kategori</h2>
        <form action="{{ route('admin.categories.store') }}" method="post" class="grid">
            @csrf
            <div><label>Nama</label><input type="text" name="name" value="{{ old('name') }}" required></div>
            <div><label>Deskripsi</label><textarea name="description">{{ old('description') }}</textarea></div>
            <div class="actions">
                <button type="submit">Simpan</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection
