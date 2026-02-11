<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <title>{{ $title ?? 'Aplikasi Peminjaman Alat' }}</title>
    <style>
        :root {
            --bg: #f3f6fb;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --line: #dbe3ef;
            --primary: #0d9488;
            --danger: #dc2626;
            --warning: #d97706;
            --success: #16a34a;
            --nav-bg: #0f172a;
            --nav-link: #d1d5db;
            --nav-link-hover: #ffffff;
            --table-bg: #ffffff;
            --table-head: #f8fafc;
            --input-bg: #ffffff;
        }
        [data-theme="dark"] {
            --bg: #0b1220;
            --card: #0f172a;
            --text: #e2e8f0;
            --muted: #94a3b8;
            --line: #1f2a44;
            --primary: #14b8a6;
            --danger: #f87171;
            --warning: #f59e0b;
            --success: #22c55e;
            --nav-bg: #020617;
            --nav-link: #9ca3af;
            --nav-link-hover: #ffffff;
            --table-bg: #0f172a;
            --table-head: #111827;
            --input-bg: #0b1220;
        }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; font-family: "Segoe UI", Tahoma, sans-serif; color: var(--text); background: var(--bg); }
        .container { width: min(1120px, 94vw); margin: 24px auto; }
        .card { background: var(--card); border: 1px solid var(--line); border-radius: 14px; padding: 16px; }
        .topbar { background: var(--nav-bg); color: #fff; padding: 10px 0; }
        .topbar .wrap { width: min(1120px, 94vw); margin: 0 auto; display: flex; gap: 12px; align-items: center; justify-content: space-between; }
        .topbar strong { letter-spacing: 0.2px; }
        .app-shell {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }
        .sidebar {
            background:
                radial-gradient(circle at 0% 0%, rgba(20, 184, 166, 0.14), transparent 38%),
                radial-gradient(circle at 100% 100%, rgba(56, 189, 248, 0.1), transparent 40%),
                var(--nav-bg);
            border-right: 1px solid var(--line);
            padding: 20px 14px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: sticky;
            top: 0;
            height: 100vh;
        }
        .sidebar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 20px;
            line-height: 1.2;
            padding: 10px 10px 14px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
            color: #99f6e4;
            border: 1px solid rgba(45, 212, 191, 0.35);
            border-radius: 999px;
            padding: 4px 10px;
            background: rgba(15, 23, 42, 0.45);
        }
        .brand-badge svg {
            width: 14px;
            height: 14px;
            stroke: #5eead4;
        }
        .brand-title {
            display: block;
            margin-top: 8px;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.35;
            letter-spacing: 0.2px;
            color: #f8fafc;
        }
        .brand-subtitle {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            font-weight: 500;
            color: #94a3b8;
        }
        .sidebar-nav {
            display: grid;
            gap: 4px;
            margin-top: 6px;
        }
        .sidebar-nav a {
            color: var(--nav-link);
            text-decoration: none;
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 9px 10px;
            display: flex;
            align-items: center;
            gap: 9px;
            transition: 0.18s ease;
        }
        .menu-icon {
            width: 18px;
            height: 18px;
            stroke: #7dd3fc;
            flex: 0 0 auto;
        }
        .sidebar-nav a:hover {
            color: var(--nav-link-hover);
            border-color: rgba(148, 163, 184, 0.25);
            background: rgba(30, 41, 59, 0.5);
        }
        .sidebar-nav a.active {
            color: #fff;
            background: rgba(20, 184, 166, 0.18);
            border-color: rgba(45, 212, 191, 0.42);
        }
        .sidebar-nav a.active .menu-icon {
            stroke: #5eead4;
        }
        .sidebar-bottom {
            margin-top: auto;
            display: grid;
            gap: 10px;
            border-top: 1px solid rgba(148, 163, 184, 0.2);
            padding-top: 12px;
        }
        .sidebar-user {
            color: var(--nav-link);
            font-size: 13px;
            line-height: 1.35;
            padding: 0 2px;
        }
        .sidebar-logout {
            width: 100%;
        }
        .main {
            min-width: 0;
        }
        .grid { display: grid; gap: 12px; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        input, select, textarea, button { width: 100%; border: 1px solid var(--line); border-radius: 10px; padding: 10px; font: inherit; background: var(--input-bg); color: var(--text); }
        textarea { min-height: 90px; resize: vertical; }
        button, .btn { width: auto; background: var(--primary); color: #fff; border: none; padding: 10px 14px; text-decoration: none; display: inline-block; cursor: pointer; border-radius: 10px; }
        .btn-secondary { background: #334155; }
        .btn-danger { background: var(--danger); }
        .btn-warning { background: var(--warning); }
        .btn-success { background: var(--success); }
        .btn-ghost { background: transparent; border: 1px solid var(--line); color: var(--text); }
        .theme-toggle {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: var(--card);
            color: var(--text);
            cursor: pointer;
        }
        .sidebar .theme-toggle {
            width: 100%;
            justify-content: space-between;
            background: rgba(15, 23, 42, 0.35);
            border-color: rgba(148, 163, 184, 0.3);
            color: #e2e8f0;
        }
        .sidebar .theme-toggle .label {
            color: #cbd5e1;
        }
        .theme-toggle .label {
            font-size: 12px;
            color: var(--muted);
        }
        .theme-toggle .switch {
            width: 40px;
            height: 22px;
            background: #0f172a;
            border-radius: 999px;
            position: relative;
            transition: background 0.2s ease;
            border: 1px solid var(--line);
        }
        [data-theme="dark"] .theme-toggle .switch {
            background: #14b8a6;
        }
        .theme-toggle .switch::after {
            content: "";
            position: absolute;
            top: 2px;
            left: 2px;
            width: 16px;
            height: 16px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        [data-theme="dark"] .theme-toggle .switch::after {
            transform: translateX(18px);
        }
        .table-wrap { overflow: auto; }
        table { border-collapse: collapse; width: 100%; background: var(--table-bg); }
        th, td { border-bottom: 1px solid var(--line); padding: 9px; text-align: left; vertical-align: top; }
        th { background: var(--table-head); }
        .muted { color: var(--muted); font-size: 13px; }
        .badge { padding: 3px 8px; border-radius: 999px; font-size: 12px; display: inline-block; }
        .badge-pending { background: #fff7ed; color: #9a3412; }
        .badge-borrowed { background: #eff6ff; color: #1d4ed8; }
        .badge-rejected { background: #fef2f2; color: #b91c1c; }
        .badge-return_requested { background: #fefce8; color: #854d0e; }
        .badge-returned { background: #ecfdf5; color: #15803d; }
        .badge-cancelled { background: #f1f5f9; color: #334155; }
        .alert { padding: 10px 12px; border-radius: 10px; margin-bottom: 12px; }
        .alert-success { background: #ecfdf5; color: #166534; }
        .alert-error { background: #fef2f2; color: #991b1b; }
        .pagination { margin-top: 14px; }
        .confirm-overlay[hidden],
        .confirm-dialog[hidden] {
            display: none !important;
        }
        .confirm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, 0.6);
            backdrop-filter: blur(2px);
            z-index: 999;
        }
        .confirm-dialog {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: min(450px, 92vw);
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: 0 20px 48px rgba(2, 6, 23, 0.38);
            z-index: 1000;
            padding: 18px;
        }
        .confirm-head {
            margin: 0 0 8px;
            font-size: 20px;
            font-weight: 700;
        }
        .confirm-text {
            margin: 0;
            color: var(--muted);
            line-height: 1.45;
        }
        .confirm-actions {
            margin-top: 16px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }
        .confirm-actions .btn {
            min-width: 92px;
        }
        .success-overlay[hidden],
        .success-dialog[hidden] {
            display: none !important;
        }
        .success-overlay {
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, 0.7);
            backdrop-filter: blur(3px);
            z-index: 1100;
            opacity: 0;
            animation: fade-in 0.24s ease forwards;
        }
        .success-dialog {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.96);
            width: min(520px, 92vw);
            border-radius: 20px;
            border: 1px solid color-mix(in oklab, var(--line) 64%, #34d399);
            background:
                radial-gradient(circle at 8% -6%, rgba(52, 211, 153, 0.22), transparent 42%),
                radial-gradient(circle at 100% 106%, rgba(45, 212, 191, 0.18), transparent 47%),
                var(--card);
            box-shadow: 0 26px 54px rgba(2, 6, 23, 0.42);
            z-index: 1101;
            overflow: hidden;
            animation: dialog-in 0.28s ease forwards;
        }
        .success-top {
            padding: 28px 24px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .success-icon {
            width: 66px;
            height: 66px;
            border-radius: 18px;
            border: 1px solid color-mix(in oklab, var(--line) 60%, #34d399);
            display: grid;
            place-items: center;
            background: linear-gradient(145deg, rgba(16, 185, 129, 0.2), rgba(20, 184, 166, 0.15));
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.35);
        }
        .success-icon svg {
            width: 34px;
            height: 34px;
            stroke: #10b981;
        }
        .success-close {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1px solid var(--line);
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            padding: 0;
            font-size: 19px;
            line-height: 1;
        }
        .success-close:hover {
            color: var(--text);
            border-color: color-mix(in oklab, var(--line) 60%, var(--text));
        }
        .success-body {
            padding: 0 24px 22px;
        }
        .success-title {
            margin: 0;
            font-size: clamp(24px, 3vw, 30px);
            line-height: 1.2;
        }
        .success-message {
            margin: 10px 0 0;
            color: var(--muted);
            line-height: 1.5;
        }
        .success-progress-wrap {
            padding: 0 24px 18px;
        }
        .success-progress {
            width: 100%;
            height: 6px;
            border-radius: 999px;
            border: 1px solid var(--line);
            overflow: hidden;
            background: color-mix(in oklab, var(--bg) 70%, var(--card));
        }
        .success-progress-bar {
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #10b981, #14b8a6, #22c55e);
            transform-origin: left center;
        }
        .success-actions {
            display: flex;
            justify-content: flex-end;
            padding: 0 24px 24px;
        }
        .success-action-btn {
            min-width: 136px;
            font-weight: 700;
        }
        .success-burst {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 999px;
            opacity: 0;
            pointer-events: none;
            animation: burst 0.9s ease forwards;
        }
        @keyframes fade-in {
            to { opacity: 1; }
        }
        @keyframes dialog-in {
            to { transform: translate(-50%, -50%) scale(1); }
        }
        @keyframes burst {
            0% { opacity: 0.9; transform: translate(0, 0) scale(0.6); }
            100% { opacity: 0; transform: translate(var(--tx), var(--ty)) scale(1.3); }
        }
        @media (max-width: 980px) {
            .app-shell {
                grid-template-columns: 1fr;
            }
            .sidebar {
                position: static;
                height: auto;
            }
        }
        @media (max-width: 820px) {
            .grid-2 { grid-template-columns: 1fr; }
            .topbar .wrap { flex-direction: column; align-items: flex-start; }
        }
    </style>
    <script>
        (function () {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = stored || (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body>
@auth
<div class="app-shell">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="brand-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14.7 6.3a4 4 0 0 0-5.5 5.5L3 18l3 3 6.2-6.2a4 4 0 0 0 5.5-5.5l-3.2 3.2-2.2-2.2 2.4-4Z"></path>
                </svg>
                INVENTARIS
            </span>
            <span class="brand-title">Aplikasi Peminjaman Alat</span>
            <span class="brand-subtitle">Sistem Manajemen Inventaris</span>
        </div>
        <button type="button" id="theme-toggle" class="theme-toggle" aria-label="Ganti tema">
            <span class="label" id="theme-label">Mode Gelap</span>
            <span class="switch" aria-hidden="true"></span>
        </button>
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 13h8V3H3z"></path><path d="M13 21h8v-8h-8z"></path><path d="M13 3h8v6h-8z"></path><path d="M3 21h8v-6H3z"></path></svg>
                Dashboard
            </a>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><path d="M20 8v6"></path><path d="M23 11h-6"></path></svg>
                    User
                </a>
                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7l9-4 9 4-9 4-9-4Z"></path><path d="M3 12l9 4 9-4"></path><path d="M3 17l9 4 9-4"></path></svg>
                    Kategori
                </a>
                <a href="{{ route('admin.tools.index') }}" class="{{ request()->routeIs('admin.tools.*') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.7 6.3a4 4 0 0 0-5.5 5.5L3 18l3 3 6.2-6.2a4 4 0 0 0 5.5-5.5l-3.2 3.2-2.2-2.2 2.4-4Z"></path></svg>
                    Alat
                </a>
                <a href="{{ route('admin.loans.index') }}" class="{{ request()->routeIs('admin.loans.*') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="6" width="20" height="14" rx="2"></rect><path d="M16 12h.01"></path><path d="M8 12h4"></path></svg>
                    Peminjaman
                </a>
                <a href="{{ route('admin.returns.index') }}" class="{{ request()->routeIs('admin.returns.*') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 14 4 9l5-5"></path><path d="M20 20v-6a5 5 0 0 0-5-5H4"></path></svg>
                    Pengembalian
                </a>
                <a href="{{ route('admin.activity-logs.index') }}" class="{{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"></path><path d="m18 9-5 5-3-3-4 4"></path></svg>
                    Log
                </a>
            @elseif(auth()->user()->role === 'petugas')
                <a href="{{ route('petugas.approvals.index') }}" class="{{ request()->routeIs('petugas.approvals.*') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 12 2 2 4-4"></path><path d="M21 12a9 9 0 1 1-9-9"></path></svg>
                    Approval
                </a>
                <a href="{{ route('petugas.returns.index') }}" class="{{ request()->routeIs('petugas.returns.*') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 1 1-3-6.7"></path><path d="M21 3v6h-6"></path></svg>
                    Monitoring
                </a>
                <a href="{{ route('petugas.reports.index') }}" class="{{ request()->routeIs('petugas.reports.*') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19h16"></path><path d="M7 15V9"></path><path d="M12 15V5"></path><path d="M17 15v-3"></path></svg>
                    Laporan
                </a>
            @else
                <a href="{{ route('peminjam.catalog.index') }}" class="{{ request()->routeIs('peminjam.catalog.*') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7h18"></path><path d="M6 7V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"></path><rect x="3" y="7" width="18" height="14" rx="2"></rect></svg>
                    Daftar Alat
                </a>
                <a href="{{ route('peminjam.loans.index') }}" class="{{ request()->routeIs('peminjam.loans.*') ? 'active' : '' }}">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 10h10"></path><path d="M7 14h7"></path><rect x="4" y="3" width="16" height="18" rx="2"></rect></svg>
                    Peminjaman Saya
                </a>
            @endif
        </nav>
        <div class="sidebar-bottom">
            <div class="sidebar-user">{{ auth()->user()->name }} ({{ auth()->user()->role }})</div>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="btn-secondary sidebar-logout">Logout</button>
            </form>
        </div>
    </aside>
    <main class="main">
        <div class="container">
            @if(session('success') && !session('register_popup'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <strong>Validasi gagal:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </main>
</div>
@else
<div class="topbar">
    <div class="wrap">
        <strong>Aplikasi Peminjaman Alat</strong>
        <button type="button" id="theme-toggle" class="theme-toggle" aria-label="Ganti tema">
            <span class="label" id="theme-label">Mode Gelap</span>
            <span class="switch" aria-hidden="true"></span>
        </button>
    </div>
</div>
<div class="container">
    @if(session('success') && !session('register_popup'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            <strong>Validasi gagal:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</div>
@endauth
<div id="confirm-overlay" class="confirm-overlay" hidden></div>
<div id="confirm-dialog" class="confirm-dialog" role="dialog" aria-modal="true" aria-labelledby="confirm-title" hidden>
    <h3 id="confirm-title" class="confirm-head">Konfirmasi Hapus</h3>
    <p id="confirm-message" class="confirm-text">Apakah Anda yakin ingin menghapus data ini?</p>
    <div class="confirm-actions">
        <button type="button" id="confirm-cancel" class="btn btn-secondary">Batal</button>
        <button type="button" id="confirm-ok" class="btn btn-danger">Ya, Hapus</button>
    </div>
</div>
<div id="success-overlay" class="success-overlay" hidden></div>
<div id="success-dialog" class="success-dialog" role="dialog" aria-modal="true" aria-labelledby="success-title" hidden>
    <div class="success-top">
        <div class="success-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m5 13 4 4L19 7"></path>
            </svg>
        </div>
        <button type="button" id="success-close" class="success-close" aria-label="Tutup popup">&times;</button>
    </div>
    <div class="success-body">
        <h3 id="success-title" class="success-title"></h3>
        <p id="success-message" class="success-message"></p>
    </div>
    <div class="success-progress-wrap">
        <div class="success-progress">
            <div id="success-progress-bar" class="success-progress-bar"></div>
        </div>
    </div>
    <div class="success-actions">
        <button type="button" id="success-ok" class="btn btn-success success-action-btn">Lanjut ke Dashboard</button>
    </div>
</div>
<script>
    (function () {
        const toggle = document.getElementById('theme-toggle');
        const label = document.getElementById('theme-label');
        if (!toggle) return;
        const setTheme = (theme) => {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            if (label) {
                label.textContent = theme === 'dark' ? 'Mode Terang' : 'Mode Gelap';
            }
        };
        const current = document.documentElement.getAttribute('data-theme') || 'light';
        setTheme(current);
        toggle.addEventListener('click', () => {
            const next = (document.documentElement.getAttribute('data-theme') === 'dark') ? 'light' : 'dark';
            setTheme(next);
        });
    })();

    (function () {
        const overlay = document.getElementById('confirm-overlay');
        const dialog = document.getElementById('confirm-dialog');
        const message = document.getElementById('confirm-message');
        const cancelBtn = document.getElementById('confirm-cancel');
        const okBtn = document.getElementById('confirm-ok');
        if (!overlay || !dialog || !message || !cancelBtn || !okBtn) return;

        let pendingForm = null;

        const closeDialog = () => {
            overlay.hidden = true;
            dialog.hidden = true;
            pendingForm = null;
        };

        const openDialog = (form) => {
            pendingForm = form;
            const text = form.getAttribute('data-confirm') || 'Apakah Anda yakin ingin menghapus data ini?';
            message.textContent = text;
            overlay.hidden = false;
            dialog.hidden = false;
            okBtn.focus();
        };

        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) return;
            if (!form.hasAttribute('data-confirm')) return;
            if (form.dataset.confirmed === '1') {
                form.dataset.confirmed = '';
                return;
            }
            event.preventDefault();
            openDialog(form);
        }, true);

        cancelBtn.addEventListener('click', closeDialog);
        overlay.addEventListener('click', closeDialog);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !dialog.hidden) closeDialog();
        });

        okBtn.addEventListener('click', () => {
            if (!pendingForm) return;
            pendingForm.dataset.confirmed = '1';
            pendingForm.submit();
            closeDialog();
        });
    })();

    (function () {
        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) return;

            const phoneInputs = form.querySelectorAll('input[data-phone-only]');
            for (const input of phoneInputs) {
                const value = input.value.trim();
                if (value === '') continue;

                if (!/^\d+$/.test(value)) {
                    event.preventDefault();
                    alert('Nomor telepon hanya boleh berisi angka.');
                    input.focus();
                    return;
                }
            }
        }, true);
    })();

    (function () {
        const popupData = @json(session('register_popup'));
        if (!popupData) return;

        const overlay = document.getElementById('success-overlay');
        const dialog = document.getElementById('success-dialog');
        const title = document.getElementById('success-title');
        const message = document.getElementById('success-message');
        const closeBtn = document.getElementById('success-close');
        const okBtn = document.getElementById('success-ok');
        const progressBar = document.getElementById('success-progress-bar');
        if (!overlay || !dialog || !title || !message || !closeBtn || !okBtn || !progressBar) return;

        title.textContent = popupData.title || 'Berhasil';
        message.textContent = popupData.message || 'Aksi berhasil diproses.';

        overlay.hidden = false;
        dialog.hidden = false;

        const colors = ['#10b981', '#14b8a6', '#22c55e', '#34d399', '#2dd4bf'];
        for (let i = 0; i < 20; i += 1) {
            const piece = document.createElement('span');
            piece.className = 'success-burst';
            piece.style.left = `${45 + Math.random() * 10}%`;
            piece.style.top = `${24 + Math.random() * 12}%`;
            piece.style.background = colors[Math.floor(Math.random() * colors.length)];
            piece.style.setProperty('--tx', `${(Math.random() - 0.5) * 240}px`);
            piece.style.setProperty('--ty', `${(Math.random() - 0.5) * 180}px`);
            piece.style.animationDelay = `${Math.random() * 0.14}s`;
            dialog.appendChild(piece);
            setTimeout(() => piece.remove(), 1000);
        }

        let closed = false;
        let startTime = null;
        const duration = 6200;
        let frameId = null;

        const closePopup = () => {
            if (closed) return;
            closed = true;
            overlay.hidden = true;
            dialog.hidden = true;
            if (frameId) {
                cancelAnimationFrame(frameId);
            }
        };

        const tick = (ts) => {
            if (closed) return;
            if (!startTime) startTime = ts;
            const elapsed = ts - startTime;
            const progress = Math.max(0, 1 - (elapsed / duration));
            progressBar.style.transform = `scaleX(${progress})`;
            if (elapsed >= duration) {
                closePopup();
                return;
            }
            frameId = requestAnimationFrame(tick);
        };
        frameId = requestAnimationFrame(tick);

        closeBtn.addEventListener('click', closePopup);
        okBtn.addEventListener('click', closePopup);
        overlay.addEventListener('click', closePopup);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !dialog.hidden) closePopup();
        });
    })();
</script>
</body>
</html>


