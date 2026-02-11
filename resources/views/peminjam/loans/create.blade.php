@extends('layouts.app', ['title' => 'Ajukan Peminjaman'])

@section('content')
    <div class="card">
        <h2>Form Pengajuan Peminjaman</h2>
        <form action="{{ route('peminjam.loans.store') }}" method="post" class="grid">
            @csrf
            <div>
                <label>Pilih Alat</label>
                <select name="tool_id" required>
                    <option value="">Pilih alat</option>
                    @foreach($tools as $tool)
                        <option value="{{ $tool->id }}" @selected(old('tool_id') == $tool->id)>{{ $tool->name }} ({{ $tool->category->name }}) - stok {{ $tool->available_stock }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-2">
                <div><label>Tanggal Pinjam</label><input type="date" name="loan_date" value="{{ old('loan_date', now()->toDateString()) }}" required></div>
                <div><label>Jatuh Tempo</label><input type="date" name="due_date" value="{{ old('due_date', now()->addDays(3)->toDateString()) }}" required></div>
                <div><label>Jumlah</label><input type="number" min="1" name="qty" value="{{ old('qty', 1) }}" required></div>
            </div>
            <div><label>Tujuan Peminjaman</label><textarea name="purpose" required>{{ old('purpose') }}</textarea></div>
            <div class="actions">
                <button type="submit">Kirim Pengajuan</button>
                <a href="{{ route('peminjam.loans.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection
