@extends('layouts.app', ['title' => 'Edit User'])

@section('content')
    <div class="card">
        <h2>Edit Pengguna</h2>
        <form action="{{ route('admin.users.update', $user) }}" method="post" class="grid">
            @csrf @method('PUT')
            <div class="grid grid-2">
                <div><label>Nama</label><input type="text" name="name" value="{{ old('name', $user->name) }}" required></div>
                <div><label>Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
                <div>
                    <label>Role</label>
                    <select name="role" required>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                        <option value="petugas" @selected(old('role', $user->role) === 'petugas')>Petugas</option>
                        <option value="peminjam" @selected(old('role', $user->role) === 'peminjam')>Peminjam</option>
                    </select>
                </div>
                <div><label>No HP</label><input type="text" name="phone" value="{{ old('phone', $user->phone) }}" inputmode="numeric" pattern="[0-9]*" data-phone-only></div>
            </div>
            <div><label>Password Baru (opsional)</label><input type="password" name="password"></div>
            <div class="actions">
                <button type="submit">Update</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection

