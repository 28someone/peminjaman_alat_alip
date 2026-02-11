@extends('layouts.app', ['title' => 'Tambah User'])

@section('content')
    <div class="card">
        <h2>Tambah Pengguna</h2>
        <form action="{{ route('admin.users.store') }}" method="post" class="grid">
            @csrf
            <div class="grid grid-2">
                <div><label>Nama</label><input type="text" name="name" value="{{ old('name') }}" required></div>
                <div><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required></div>
                <div>
                    <label>Role</label>
                    <select name="role" required>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        <option value="petugas" @selected(old('role') === 'petugas')>Petugas</option>
                        <option value="peminjam" @selected(old('role', 'peminjam') === 'peminjam')>Peminjam</option>
                    </select>
                </div>
                <div><label>No HP</label><input type="text" name="phone" value="{{ old('phone') }}" inputmode="numeric" pattern="[0-9]*" data-phone-only></div>
            </div>
            <div><label>Password</label><input type="password" name="password" required></div>
            <div class="actions">
                <button type="submit">Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection

