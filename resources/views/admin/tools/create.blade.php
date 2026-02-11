@extends('layouts.app', ['title' => 'Tambah Alat'])

@section('content')
    <div class="card">
        <h2>Tambah Alat</h2>
        <form method="post" action="{{ route('admin.tools.store') }}" class="grid">
            @csrf
            <div class="grid grid-2">
                <div><label>Kode Alat</label><input type="text" name="code" value="{{ old('code') }}" required></div>
                <div><label>Nama Alat</label><input type="text" name="name" value="{{ old('name') }}" required></div>
                <div>
                    <label>Kategori</label>
                    <select name="category_id" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label>Total Stok</label><input type="number" min="0" name="total_stock" value="{{ old('total_stock', 0) }}" required></div>
                <div><label>Kondisi</label><input type="text" name="condition" value="{{ old('condition', 'baik') }}" required></div>
                <div><label>Lokasi</label><input type="text" name="location" value="{{ old('location') }}"></div>
                <div>
                    <label>Status</label>
                    <select name="status" required>
                        <option value="active" @selected(old('status', 'active') === 'active')>active</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>inactive</option>
                    </select>
                </div>
            </div>
            <div><label>Deskripsi</label><textarea name="description">{{ old('description') }}</textarea></div>
            <div class="actions">
                <button type="submit">Simpan</button>
                <a href="{{ route('admin.tools.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection
