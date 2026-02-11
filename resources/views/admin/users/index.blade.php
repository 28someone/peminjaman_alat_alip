@extends('layouts.app', ['title' => 'Manajemen User'])

@section('content')
    <div class="card">
        <div class="actions" style="justify-content: space-between; margin-bottom: 10px;">
            <h2 style="margin: 0;">Data Pengguna</h2>
            <a href="{{ route('admin.users.create') }}" class="btn">Tambah User</a>
        </div>

        <form method="get" class="grid grid-2" style="margin-bottom: 12px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/email/no hp">
            <select name="role">
                <option value="">Semua Role</option>
                <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                <option value="petugas" @selected(request('role') === 'petugas')>Petugas</option>
                <option value="peminjam" @selected(request('role') === 'peminjam')>Peminjam</option>
            </select>
            <button type="submit">Filter</button>
        </form>

        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>No HP</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ strtoupper($user->role) }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td class="actions">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="post" data-confirm="Hapus user ini?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Belum ada data pengguna.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">{{ $users->links() }}</div>
    </div>
@endsection
