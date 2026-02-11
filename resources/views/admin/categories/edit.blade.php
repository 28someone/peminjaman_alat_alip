@extends('layouts.app', ['title' => 'Edit Kategori'])

@section('content')
    <div class="card">
        <h2>Edit Kategori</h2>
        <form action="{{ route('admin.categories.update', $category) }}" method="post" class="grid">
            @csrf @method('PUT')
            <div><label>Nama</label><input type="text" name="name" value="{{ old('name', $category->name) }}" required></div>
            <div><label>Deskripsi</label><textarea name="description">{{ old('description', $category->description) }}</textarea></div>
            <div class="actions">
                <button type="submit">Update</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection
