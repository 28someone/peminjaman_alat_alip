<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Peminjam</th>
                <th>Email</th>
                <th>Alat</th>
                <th>Tanggal Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Tanggal Kembali</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
                <tr>
                    <td>{{ $loan->code }}</td>
                    <td>{{ $loan->user->name ?? '-' }}</td>
                    <td>{{ $loan->user->email ?? '-' }}</td>
                    <td>{{ $loan->tool->name ?? '-' }}</td>
                    <td>{{ optional($loan->loan_date)->format('d-m-Y') }}</td>
                    <td>{{ optional($loan->due_date)->format('d-m-Y') }}</td>
                    <td>{{ optional($loan->return_date)->format('d-m-Y') }}</td>
                    <td>{{ $loan->qty }}</td>
                    <td>{{ $loan->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Tidak ada data laporan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
