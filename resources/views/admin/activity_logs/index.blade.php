@extends('layouts.app', ['title' => 'Log Aktivitas'])

@section('content')
    <style>
        .pagination {
            margin-top: 16px;
            display: grid;
            gap: 12px;
        }
        .pager {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }
        .pager-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 38px;
            padding: 0 12px;
            border-radius: 10px;
            border: 1px solid var(--line);
            text-decoration: none;
            background: color-mix(in oklab, var(--card) 84%, var(--bg));
            color: var(--text);
            font-weight: 600;
            transition: 0.15s ease;
        }
        .pager-btn:hover {
            border-color: color-mix(in oklab, var(--primary) 55%, var(--line));
            background: color-mix(in oklab, var(--primary) 20%, var(--card));
            color: #fff;
        }
        .pager-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        .pager-btn.disabled {
            opacity: 0.48;
            pointer-events: none;
            cursor: not-allowed;
        }
        .pagination-summary {
            color: var(--muted);
            font-size: 13px;
        }
    </style>
    <div class="card">
        <h2>Log Aktivitas</h2>
        <form method="get" class="actions" style="margin-bottom: 12px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari action/deskripsi">
            <button type="submit">Cari</button>
        </form>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Waktu</th><th>User</th><th>Action</th><th>Deskripsi</th><th>IP</th></tr></thead>
                <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at?->format('d-m-Y H:i:s') }}</td>
                        <td>{{ $log->user?->name ?? 'system' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->description ?: '-' }}</td>
                        <td>{{ $log->ip_address ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">Belum ada log.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="pagination">
                <div class="pager">
                    @if($logs->onFirstPage())
                        <span class="pager-btn disabled">Previous</span>
                    @else
                        <a class="pager-btn" href="{{ $logs->previousPageUrl() }}">Previous</a>
                    @endif

                    @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                        @if($page == $logs->currentPage())
                            <span class="pager-btn active">{{ $page }}</span>
                        @else
                            <a class="pager-btn" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($logs->hasMorePages())
                        <a class="pager-btn" href="{{ $logs->nextPageUrl() }}">Next</a>
                    @else
                        <span class="pager-btn disabled">Next</span>
                    @endif
                </div>
                <div class="pagination-summary">
                    Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} data log.
                </div>
            </div>
        @endif
    </div>
@endsection
