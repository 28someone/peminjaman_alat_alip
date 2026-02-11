@extends('layouts.app', ['title' => 'Edit Peminjaman'])

@section('content')
    <div class="card">
        <h2>Edit Data Peminjaman</h2>
        <form action="{{ route('admin.loans.update', $loan) }}" method="post" class="grid">
            @csrf @method('PUT')
            <div class="grid grid-2">
                <div>
                    <label>Peminjam</label>
                    <select name="user_id" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id', $loan->user_id) == $user->id)>{{ $user->name }} ({{ $user->role }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Alat</label>
                    <select name="tool_id" required>
                        @foreach($tools as $tool)
                            <option value="{{ $tool->id }}" @selected(old('tool_id', $loan->tool_id) == $tool->id)>{{ $tool->name }} [{{ $tool->available_stock }} stok]</option>
                        @endforeach
                    </select>
                </div>
                <div><label>Tanggal Pinjam</label><input type="date" name="loan_date" value="{{ old('loan_date', $loan->loan_date?->format('Y-m-d')) }}" required></div>
                <div><label>Jatuh Tempo</label><input type="date" name="due_date" value="{{ old('due_date', $loan->due_date?->format('Y-m-d')) }}" required></div>
                <div><label>Qty</label><input type="number" name="qty" min="1" value="{{ old('qty', $loan->qty) }}" required></div>
                <div>
                    <label>Status</label>
                    <select name="status" required>
                        @foreach(['pending','borrowed','rejected','return_requested','returned','cancelled'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $loan->status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div><label>Tujuan Peminjaman</label><textarea name="purpose" required>{{ old('purpose', $loan->purpose) }}</textarea></div>
            <div><label>Catatan Approval</label><textarea name="approval_note">{{ old('approval_note', $loan->approval_note) }}</textarea></div>
            <div><label>Catatan Pengembalian</label><textarea name="return_note">{{ old('return_note', $loan->return_note) }}</textarea></div>
            <div class="actions">
                <button type="submit">Update</button>
                <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection
