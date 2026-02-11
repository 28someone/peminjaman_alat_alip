@extends('layouts.app', ['title' => 'Login'])

@section('content')
    <style>
        .login-shell {
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
        .login-info {
            padding: 36px 32px;
            background:
                radial-gradient(circle at 18% 20%, rgba(20, 184, 166, 0.26), transparent 48%),
                radial-gradient(circle at 85% 78%, rgba(14, 165, 233, 0.24), transparent 44%),
                linear-gradient(150deg, #0f172a, #0b1220 55%, #0f1f42);
            color: #e2e8f0;
            position: relative;
        }
        .login-chip {
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
        .login-title {
            font-size: clamp(26px, 3vw, 34px);
            line-height: 1.2;
            margin: 18px 0 10px;
            color: #f8fafc;
        }
        .login-desc {
            margin: 0;
            color: #cbd5e1;
            max-width: 350px;
        }
        .tool-icons {
            margin-top: 30px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .tool-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            border: 1px solid rgba(148, 163, 184, 0.28);
            display: grid;
            place-items: center;
            background: rgba(15, 23, 42, 0.42);
            backdrop-filter: blur(2px);
        }
        .tool-icon svg {
            width: 24px;
            height: 24px;
            stroke: #5eead4;
        }
        .login-form-wrap {
            padding: 30px 24px;
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
            font-size: 29px;
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
        .password-wrap {
            position: relative;
        }
        .password-wrap input {
            padding-right: 44px;
        }
        .toggle-password {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: transparent;
            color: var(--muted);
            display: grid;
            place-items: center;
            cursor: pointer;
            padding: 0;
        }
        .toggle-password svg {
            width: 17px;
            height: 17px;
            stroke: currentColor;
        }
        .toggle-password:hover {
            color: var(--text);
            border-color: color-mix(in oklab, var(--primary) 50%, var(--line));
        }
        .remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--muted);
        }
        .remember input {
            width: auto;
        }
        .btn-login {
            width: 100%;
            height: 46px;
            border-radius: 11px;
            font-weight: 700;
            letter-spacing: 0.25px;
        }
        .login-note {
            margin: 14px 0 0;
            border-top: 1px solid var(--line);
            padding-top: 14px;
        }
        @media (max-width: 900px) {
            .login-shell {
                grid-template-columns: 1fr;
            }
            .login-info {
                padding: 28px 22px;
            }
            .login-form-wrap {
                padding: 18px;
            }
        }
    </style>

    <div class="login-shell">
        <aside class="login-info">
            <span class="login-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14 8.5a4.5 4.5 0 0 0-6.5 5.8L3 19l2 2 4.7-4.6A4.5 4.5 0 1 0 14 8.5Z"></path>
                </svg>
                Sistem Inventaris Alat
            </span>
            <h1 class="login-title">Kelola Peminjaman Alat Lebih Cepat</h1>
            <p class="login-desc">Masuk ke dashboard untuk memproses peminjaman, approval, dan pengembalian alat dalam satu tempat.</p>
            <div class="tool-icons">
                <span class="tool-icon" title="Kunci Inggris">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M14.7 6.3a4 4 0 0 0-5.5 5.5L3 18l3 3 6.2-6.2a4 4 0 0 0 5.5-5.5l-3.2 3.2-2.2-2.2 2.4-4Z"></path>
                    </svg>
                </span>
                <span class="tool-icon" title="Obeng">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M8 7l9 9"></path>
                        <path d="M5 4l3 3-2 2-3-3z"></path>
                        <path d="M17 16l3 3-2 2-3-3z"></path>
                    </svg>
                </span>
                <span class="tool-icon" title="Perkakas">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="8" width="18" height="11" rx="2"></rect>
                        <path d="M9 8V6a3 3 0 0 1 6 0v2"></path>
                    </svg>
                </span>
            </div>
        </aside>

        <section class="login-form-wrap">
            <div class="form-card">
                <div class="form-head">
                    <h2>Login Pengguna</h2>
                    <p class="muted">Masuk sesuai akun role: Admin, Petugas, atau Peminjam.</p>
                </div>

                <form action="{{ route('login.attempt') }}" method="post" class="grid">
                    @csrf
                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="field">
                        <label>Password</label>
                        <div class="password-wrap">
                            <input type="password" id="password" name="password" required>
                            <button type="button" id="toggle-password" class="toggle-password" aria-label="Tampilkan password" aria-pressed="false">
                                <svg id="eye-open" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg id="eye-closed" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none;">
                                    <path d="M3 3 21 21"></path>
                                    <path d="M10.6 10.7a3 3 0 0 0 4.2 4.2"></path>
                                    <path d="M9.9 5.1A10.6 10.6 0 0 1 12 5c6.5 0 10 7 10 7a17.8 17.8 0 0 1-3 3.8"></path>
                                    <path d="M6.7 6.7A17.6 17.6 0 0 0 2 12s3.5 7 10 7a10.3 10.3 0 0 0 4.3-.9"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1"> Ingat saya
                    </label>
                    <button type="submit" class="btn-login">Login</button>
                </form>

                <p class="muted login-note">Contoh akun default tersedia setelah seeding database.</p>
            </div>
        </section>
    </div>
    <script>
        (function () {
            const input = document.getElementById('password');
            const toggle = document.getElementById('toggle-password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            if (!input || !toggle || !eyeOpen || !eyeClosed) return;

            toggle.addEventListener('click', () => {
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                toggle.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                toggle.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
                eyeOpen.style.display = isHidden ? 'none' : 'block';
                eyeClosed.style.display = isHidden ? 'block' : 'none';
            });
        })();
    </script>
@endsection
