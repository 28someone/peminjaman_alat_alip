@extends('layouts.app', ['title' => 'Edit Alat'])

@section('content')
    <div class="card">
        <h2>Edit Alat</h2>
        <form method="post" action="{{ route('admin.tools.update', $tool) }}" class="grid">
            @csrf @method('PUT')
            <div class="grid grid-2">
                <div><label>Kode Alat</label><input type="text" name="code" value="{{ old('code', $tool->code) }}" required></div>
                <div><label>Nama Alat</label><input type="text" name="name" value="{{ old('name', $tool->name) }}" required></div>
                <div>
                    <label>Kategori</label>
                    <select name="category_id" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $tool->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label>Total Stok</label><input type="number" min="0" name="total_stock" value="{{ old('total_stock', $tool->total_stock) }}" required></div>
                <div><label>Kondisi</label><input type="text" name="condition" value="{{ old('condition', $tool->condition) }}" required></div>
                <div><label>Lokasi</label><input type="text" name="location" value="{{ old('location', $tool->location) }}"></div>
                <div>
                    <label>Status</label>
                    <select name="status" required>
                        <option value="active" @selected(old('status', $tool->status) === 'active')>active</option>
                        <option value="inactive" @selected(old('status', $tool->status) === 'inactive')>inactive</option>
                    </select>
                </div>
            </div>
            <div><label>Deskripsi</label><textarea name="description">{{ old('description', $tool->description) }}</textarea></div>
            <p class="muted">Stok tersedia saat ini: {{ $tool->available_stock }}</p>
            <div class="actions">
                <button type="submit">Update</button>
                <a href="{{ route('admin.tools.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection
