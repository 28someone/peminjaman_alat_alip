@extends('layouts.app', ['title' => 'Register'])

@section('content')
    <style>
        .register-shell {
            max-width: 940px;
            margin: 28px auto;
            border-radius: 20px;
            border: 1px solid var(--line);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            background: var(--card);
            box-shadow: 0 18px 40px rgba(2, 6, 23, 0.24);
        }
        .register-info {
            padding: 36px 32px;
            background:
                radial-gradient(circle at 18% 20%, rgba(20, 184, 166, 0.26), transparent 48%),
                radial-gradient(circle at 85% 78%, rgba(14, 165, 233, 0.24), transparent 44%),
                linear-gradient(150deg, #0f172a, #0b1220 55%, #0f1f42);
            color: #e2e8f0;
        }
        .register-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(148, 163, 184, 0.35);
            background: rgba(15, 23, 42, 0.56);
            border-radius: 999px;
            padding: 7px 12px;
            font-size: 12px;
            letter-spacing: 0.3px;
        }
        .register-chip svg {
            width: 14px;
            height: 14px;
            stroke: #5eead4;
        }
        .register-title {
            font-size: clamp(24px, 3vw, 32px);
            line-height: 1.2;
            margin: 16px 0 10px;
            color: #f8fafc;
        }
        .register-desc {
            margin: 0;
            color: #cbd5e1;
            max-width: 350px;
        }
        .register-points {
            margin-top: 24px;
            display: grid;
            gap: 10px;
        }
        .register-point {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #dbeafe;
            font-size: 14px;
        }
        .register-point svg {
            width: 16px;
            height: 16px;
            stroke: #5eead4;
            flex: 0 0 auto;
        }
        .register-form-wrap {
            padding: 28px 24px;
            background: var(--card);
        }
        .form-card {
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 20px;
            background: color-mix(in oklab, var(--card) 80%, var(--bg));
        }
        .form-head {
            margin-bottom: 14px;
        }
        .form-head h2 {
            margin: 0;
            font-size: 28px;
        }
        .form-head p {
            margin: 8px 0 0;
        }
        .field {
            display: grid;
            gap: 6px;
        }
        .field label {
            font-weight: 600;
            font-size: 14px;
        }
        .field input {
            height: 44px;
            border-radius: 11px;
        }
        .btn-register {
            width: 100%;
            height: 46px;
            border-radius: 11px;
            font-weight: 700;
            letter-spacing: 0.2px;
        }
        .auth-links {
            margin: 14px 0 0;
            border-top: 1px solid var(--line);
            padding-top: 14px;
            font-size: 14px;
            color: var(--muted);
        }
        .auth-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }
        .auth-links a:hover {
            text-decoration: underline;
        }
        @media (max-width: 900px) {
            .register-shell {
                grid-template-columns: 1fr;
            }
            .register-info {
                padding: 28px 22px;
            }
            .register-form-wrap {
                padding: 18px;
            }
        }
    </style>

    <div class="register-shell">
        <aside class="register-info">
            <span class="register-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 2v20"></path>
                    <path d="M5 9h14"></path>
                    <path d="M5 15h14"></path>
                </svg>
                Akun Peminjam Baru
            </span>
            <h1 class="register-title">Daftar Untuk Mulai Meminjam Alat</h1>
            <p class="register-desc">Buat akun peminjam untuk mengakses katalog alat dan melakukan pengajuan peminjaman secara online.</p>
            <div class="register-points">
                <span class="register-point">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m5 13 4 4L19 7"></path>
                    </svg>
                    Role akun otomatis sebagai Peminjam
                </span>
                <span class="register-point">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m5 13 4 4L19 7"></path>
                    </svg>
                    Setelah daftar akan langsung login
                </span>
                <span class="register-point">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m5 13 4 4L19 7"></path>
                    </svg>
                    Data bisa diperbarui oleh admin
                </span>
            </div>
        </aside>

        <section class="register-form-wrap">
            <div class="form-card">
                <div class="form-head">
                    <h2>Form Registrasi</h2>
                    <p class="muted">Isi data berikut untuk membuat akun baru.</p>
                </div>

                <form action="{{ route('register.store') }}" method="post" class="grid">
                    @csrf
                    <div class="field">
                        <label for="name">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="field">
                        <label for="phone">No. HP</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" inputmode="numeric" pattern="[0-9]*" data-phone-only>
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required>
                    </div>
                    <div class="field">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn-register">Buat Akun</button>
                </form>

                <p class="auth-links">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Login di sini</a>
                </p>
            </div>
        </section>
    </div>
@endsection

