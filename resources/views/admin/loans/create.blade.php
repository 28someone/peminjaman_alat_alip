@extends('layouts.app', ['title' => 'Tambah Peminjaman'])

@section('content')
    <div class="card">
        <h2>Tambah Data Peminjaman</h2>
        <form action="{{ route('admin.loans.store') }}" method="post" class="grid">
            @csrf
            <div class="grid grid-2">
                <div>
                    <label>Peminjam</label>
                    <select name="user_id" required>
                        <option value="">Pilih user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }} ({{ $user->role }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Alat</label>
                    <select name="tool_id" required>
                        <option value="">Pilih alat</option>
                        @foreach($tools as $tool)
                            <option value="{{ $tool->id }}" @selected(old('tool_id') == $tool->id)>{{ $tool->name }} [{{ $tool->available_stock }} stok]</option>
                        @endforeach
                    </select>
                </div>
                <div><label>Tanggal Pinjam</label><input type="date" name="loan_date" value="{{ old('loan_date', now()->toDateString()) }}" required></div>
                <div><label>Jatuh Tempo</label><input type="date" name="due_date" value="{{ old('due_date', now()->addDays(3)->toDateString()) }}" required></div>
                <div><label>Qty</label><input type="number" name="qty" min="1" value="{{ old('qty', 1) }}" required></div>
                <div>
                    <label>Status</label>
                    <select name="status" required>
                        @foreach(['pending','borrowed','rejected','return_requested','returned','cancelled'] as $status)
                            <option value="{{ $status }}" @selected(old('status', 'pending') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div><label>Tujuan Peminjaman</label><textarea name="purpose" required>{{ old('purpose') }}</textarea></div>
            <div><label>Catatan Approval</label><textarea name="approval_note">{{ old('approval_note') }}</textarea></div>
            <div><label>Catatan Pengembalian</label><textarea name="return_note">{{ old('return_note') }}</textarea></div>
            <div class="actions">
                <button type="submit">Simpan</button>
                <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection
