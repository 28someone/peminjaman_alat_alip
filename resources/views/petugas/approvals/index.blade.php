@extends('layouts.app', ['title' => 'Approval Peminjaman'])

@section('content')
    <div class="card">
        <h2>Approval Peminjaman</h2>
        <form method="get" class="actions" style="margin-bottom: 12px;">
            <select name="status">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status') === 'pending')>pending</option>
                <option value="borrowed" @selected(request('status') === 'borrowed')>borrowed</option>
                <option value="return_requested" @selected(request('status') === 'return_requested')>return_requested</option>
            </select>
            <button type="submit">Filter</button>
        </form>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kode</th><th>Peminjam</th><th>Alat</th><th>Qty</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($loans as $loan)
                    <tr>
                        <td>{{ $loan->code }}</td>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->tool->name }}</td>
                        <td>{{ $loan->qty }}</td>
                        <td><span class="badge badge-{{ $loan->status }}">{{ $loan->status }}</span></td>
                        <td>
                            @if($loan->status === 'pending')
                                <div class="actions">
                                    <form action="{{ route('petugas.approvals.approve', $loan) }}" method="post">
                                        @csrf
                                        <button class="btn btn-success" type="submit">Setujui</button>
                                    </form>
                                    <form action="{{ route('petugas.approvals.reject', $loan) }}" method="post">
                                        @csrf
                                        <input type="text" name="approval_note" placeholder="Alasan (opsional)">
                                        <button class="btn btn-danger" type="submit">Tolak</button>
                                    </form>
                                </div>
                            @elseif($loan->status === 'return_requested')
                                <div class="actions">
                                    <form action="{{ route('petugas.approvals.accept-return', $loan) }}" method="post">
                                        @csrf
                                        <input type="text" name="condition_after_return" placeholder="Kondisi (baik/rusak)">
                                        <input type="text" name="return_note" placeholder="Catatan (opsional)">
                                        <button class="btn btn-success" type="submit">Terima Pengembalian</button>
                                    </form>
                                    <form action="{{ route('petugas.approvals.reject-return', $loan) }}" method="post">
                                        @csrf
                                        <input type="text" name="return_note" placeholder="Alasan (opsional)">
                                        <button class="btn btn-danger" type="submit">Tolak Pengembalian</button>
                                    </form>
                                </div>
                            @else
                                <span class="muted">Tidak ada aksi</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $loans->links() }}</div>
    </div>
@endsection
