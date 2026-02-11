@extends('layouts.app', ['title' => 'Edit Pengembalian'])

@section('content')
    <div class="card">
        <h2>Edit Data Pengembalian</h2>
        <form action="{{ route('admin.returns.update', $return) }}" method="post" class="grid">
            @csrf @method('PUT')
            <div>
                <label>Pilih Peminjaman</label>
                <select name="loan_id" id="loan_id" required>
                    @foreach($loans as $loan)
                        <option
                            value="{{ $loan->id }}"
                            data-due-date="{{ $loan->due_date?->format('Y-m-d') }}"
                            @selected(old('loan_id', $return->loan_id) == $loan->id)
                        >
                            {{ $loan->code }} - {{ $loan->user->name }} - {{ $loan->tool->name }} (Jatuh tempo: {{ $loan->due_date?->format('d-m-Y') ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-2">
                <div><label>Tgl Permintaan Kembali</label><input type="date" name="requested_return_date" value="{{ old('requested_return_date', $return->requested_return_date?->format('Y-m-d')) }}"></div>
                <div><label>Tgl Diterima</label><input type="date" id="received_date" name="received_date" value="{{ old('received_date', $return->received_date?->format('Y-m-d')) }}"></div>
                <div>
                    <label>Status</label>
                    <select name="status" id="return_status" required>
                        <option value="pending" @selected(old('status', $return->status) === 'pending')>pending</option>
                        <option value="verified" @selected(old('status', $return->status) === 'verified')>verified</option>
                    </select>
                </div>
                <div>
                    <label>Kondisi Setelah Kembali</label>
                    <input type="text" id="condition_after_return" name="condition_after_return" value="{{ old('condition_after_return', $return->condition_after_return) }}" placeholder="Contoh: baik / rusak ringan / rusak berat">
                </div>
                <div>
                    <label>Denda</label>
                    <input type="number" id="fine" step="0.01" min="0" name="fine" value="{{ old('fine', $return->fine) }}" readonly>
                    <p id="fine-info" class="muted" style="margin:6px 0 0;">Denda dihitung otomatis saat status verified.</p>
                </div>
            </div>
            <div><label>Catatan</label><textarea name="note">{{ old('note', $return->note) }}</textarea></div>
            <div class="actions">
                <button type="submit">Update</button>
                <a href="{{ route('admin.returns.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
    <script>
        (function () {
            const loanSelect = document.getElementById('loan_id');
            const receivedDate = document.getElementById('received_date');
            const statusSelect = document.getElementById('return_status');
            const conditionInput = document.getElementById('condition_after_return');
            const fineInput = document.getElementById('fine');
            const fineInfo = document.getElementById('fine-info');
            const finePerDay = Number(@json($finePerDay));
            const damageFine = Number(@json($damageFine));
            if (!loanSelect || !receivedDate || !statusSelect || !conditionInput || !fineInput || !fineInfo) return;

            const formatRupiah = (num) => `Rp${Number(num).toLocaleString('id-ID')}`;
            const diffDays = (start, end) => {
                const s = new Date(`${start}T00:00:00`);
                const e = new Date(`${end}T00:00:00`);
                return Math.floor((e - s) / 86400000);
            };
            const isDamaged = (conditionText) => /(rusak|retak|pecah|patah|mati|error|tidak berfungsi)/i.test(conditionText || '');

            const calculate = () => {
                if (statusSelect.value !== 'verified') {
                    fineInput.value = '0';
                    fineInfo.textContent = 'Denda dihitung otomatis saat status verified.';
                    return;
                }

                if (!receivedDate.value) {
                    receivedDate.value = new Date().toISOString().slice(0, 10);
                }

                const selected = loanSelect.options[loanSelect.selectedIndex];
                const dueDate = selected?.dataset?.dueDate || '';
                const lateDays = dueDate ? Math.max(0, diffDays(dueDate, receivedDate.value)) : 0;
                const lateFine = lateDays * finePerDay;
                const damageExtraFine = isDamaged(conditionInput.value) ? damageFine : 0;
                const totalFine = lateFine + damageExtraFine;
                fineInput.value = String(totalFine);

                const details = [];
                if (dueDate) {
                    details.push(`Terlambat ${lateDays} hari x ${formatRupiah(finePerDay)} = ${formatRupiah(lateFine)}`);
                } else {
                    details.push('Jatuh tempo tidak tersedia = Rp0');
                }
                if (damageExtraFine > 0) {
                    details.push(`Kondisi rusak = ${formatRupiah(damageExtraFine)}`);
                }
                fineInfo.textContent = `${details.join(' + ')}. Total: ${formatRupiah(totalFine)}.`;
            };

            loanSelect.addEventListener('change', calculate);
            receivedDate.addEventListener('change', calculate);
            statusSelect.addEventListener('change', calculate);
            conditionInput.addEventListener('input', calculate);
            calculate();
        })();
    </script>
@endsection
