<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>E-Library UNM | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 20px;
        }

        /* ==================== ANIMATED BACKGROUND ==================== */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            animation: float 15s infinite ease-in-out;
        }

        body::before {
            width: 500px;
            height: 500px;
            top: -200px;
            left: -200px;
        }

        body::after {
            width: 400px;
            height: 400px;
            bottom: -150px;
            right: -100px;
            animation-delay: 5s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.05); }
        }

        /* ==================== LOGIN CONTAINER ==================== */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 950px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease-out;
            min-height: 600px;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ==================== LEFT SIDE (BRANDING) ==================== */
        .login-branding {
            padding: 60px 45px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            transition: background 0.5s ease;
        }

        /* Admin Theme */
        .login-branding.admin-theme {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Member Theme */
        .login-branding.member-theme {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .login-branding::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -30%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .login-branding::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -20%;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .brand-logo-icon {
            width: 55px;
            height: 55px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.25);
            transition: all 0.3s;
        }

        .brand-logo-text {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .brand-logo-text span {
            font-weight: 400;
            opacity: 0.9;
        }

        .brand-content {
            position: relative;
            z-index: 1;
            transition: opacity 0.3s;
        }

        .brand-content h1 {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.3;
            margin-bottom: 15px;
        }

        .brand-content p {
            font-size: 0.95rem;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            opacity: 0.95;
        }

        .brand-feature-icon {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            backdrop-filter: blur(10px);
            flex-shrink: 0;
        }

        .brand-footer {
            position: relative;
            z-index: 1;
            font-size: 0.8rem;
            opacity: 0.75;
        }

        /* ==================== RIGHT SIDE (FORM) ==================== */
        .login-form-wrapper {
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            margin-bottom: 25px;
            text-align: center;
        }

        .login-header h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #212529;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* ==================== TAB SWITCH ==================== */
        .login-tabs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #f8f9fa;
            border-radius: 14px;
            padding: 6px;
            margin-bottom: 30px;
            position: relative;
            border: 1px solid #e9ecef;
        }

        .login-tab {
            padding: 12px 16px;
            text-align: center;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            color: #6c757d;
            transition: all 0.3s;
            position: relative;
            z-index: 2;
            border: none;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            user-select: none;
        }

        .login-tab i {
            font-size: 1rem;
        }

        .login-tab.active {
            color: #fff;
        }

        .login-tab.active.admin-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .login-tab.active.member-active {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            box-shadow: 0 4px 12px rgba(17, 153, 142, 0.4);
        }

        .login-tab:not(.active):hover {
            color: #212529;
            background: #fff;
        }

        /* ==================== FORM ==================== */
        .form-tab-content {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .form-tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group-modern {
            margin-bottom: 18px;
        }

        .form-label-modern {
            font-size: 0.85rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }

        .input-group-modern {
            position: relative;
        }

        .input-group-modern .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            font-size: 1rem;
            transition: color 0.2s;
            z-index: 5;
            pointer-events: none;
        }

        .form-control-modern {
            width: 100%;
            padding: 14px 16px 14px 45px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: #f8f9fa;
            height: auto;
        }

        .form-control-modern:focus {
            outline: none;
            background: #fff;
        }

        .form-control-modern.admin-focus:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-control-modern.member-focus:focus {
            border-color: #11998e;
            box-shadow: 0 0 0 4px rgba(17, 153, 142, 0.1);
        }

        .input-group-modern:focus-within .input-icon {
            color: #667eea;
        }

        .member-theme-active .input-group-modern:focus-within .input-icon {
            color: #11998e;
        }

        /* ==================== TOGGLE PASSWORD ==================== */
        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #adb5bd;
            cursor: pointer;
            padding: 4px 8px;
            font-size: 0.95rem;
            transition: color 0.2s;
            z-index: 5;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        .member-theme-active .toggle-password:hover {
            color: #11998e;
        }

        /* ==================== BUTTON LOGIN ==================== */
        .btn-login {
            width: 100%;
            padding: 14px 20px;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-login.admin-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.35);
        }

        .btn-login.admin-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(102, 126, 234, 0.45);
        }

        .btn-login.member-btn {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            box-shadow: 0 8px 20px rgba(17, 153, 142, 0.35);
        }

        .btn-login.member-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(17, 153, 142, 0.45);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* ==================== SPINNER ==================== */
        .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-login.loading .spinner {
            display: inline-block;
        }

        .btn-login.loading .btn-text {
            display: none;
        }

        /* ==================== FOOTER ==================== */
        .login-form-footer {
            margin-top: 25px;
            text-align: center;
            font-size: 0.8rem;
            color: #adb5bd;
        }

        /* ==================== INFO BOX ==================== */
        .info-hint {
            background: #f8f9fa;
            border-left: 3px solid #667eea;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 15px;
        }

        .info-hint.member-hint {
            border-left-color: #11998e;
        }

        .info-hint i {
            color: #667eea;
            margin-right: 5px;
        }

        .info-hint.member-hint i {
            color: #11998e;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 900px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 450px;
                min-height: auto;
            }

            .login-branding {
                padding: 30px;
            }

            .brand-content h1 {
                font-size: 1.5rem;
            }

            .brand-content p {
                font-size: 0.85rem;
                margin-bottom: 15px;
            }

            .brand-features {
                display: none;
            }

            .login-form-wrapper {
                padding: 35px 30px;
            }

            .login-header h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .login-form-wrapper {
                padding: 25px 20px;
            }

            .brand-logo-icon {
                width: 45px;
                height: 45px;
                font-size: 20px;
            }

            .brand-logo-text {
                font-size: 1.2rem;
            }

            .login-tab {
                padding: 10px 8px;
                font-size: 0.8rem;
            }
        }

        /* ==================== SHAKE ANIMATION ==================== */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-10px); }
            40%, 80% { transform: translateX(10px); }
        }

        .shake {
            animation: shake 0.5s;
        }
    </style>
</head>
<body>

<div class="login-container">

    {{-- ==================== LEFT: BRANDING ==================== --}}
    <div class="login-branding admin-theme" id="brandingPanel">

        {{-- Logo --}}
        <div class="brand-logo">
            <div class="brand-logo-icon">
                <i class="fas fa-book-reader"></i>
            </div>
            <div class="brand-logo-text">
                E-Library <span>UNM</span>
            </div>
        </div>

        {{-- Content --}}
        <div class="brand-content" id="brandContent">
            <h1>Sistem Perpustakaan Digital</h1>
            <p>Kelola koleksi buku, anggota, dan transaksi peminjaman dengan mudah & cepat.</p>

            <div class="brand-features">
                <div class="brand-feature">
                    <div class="brand-feature-icon"><i class="fas fa-book"></i></div>
                    <span>Manajemen buku & kategori</span>
                </div>
                <div class="brand-feature">
                    <div class="brand-feature-icon"><i class="fas fa-users"></i></div>
                    <span>Kelola anggota perpustakaan</span>
                </div>
                <div class="brand-feature">
                    <div class="brand-feature-icon"><i class="fas fa-exchange-alt"></i></div>
                    <span>Transaksi peminjaman & pengembalian</span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="brand-footer">
            &copy; {{ date('Y') }} Universitas Nusa Mandiri
        </div>

    </div>

    {{-- ==================== RIGHT: FORM ==================== --}}
    <div class="login-form-wrapper">

        {{-- Header --}}
        <div class="login-header">
            <h2>Selamat Datang! 👋</h2>
            <p>Pilih tipe akun untuk melanjutkan</p>
        </div>

        {{-- Tab Switch --}}
        <div class="login-tabs">
            <button type="button" class="login-tab active admin-active" onclick="switchTab('admin')">
                <i class="fas fa-user-shield"></i> Admin
            </button>
            <button type="button" class="login-tab" onclick="switchTab('member')">
                <i class="fas fa-user"></i> Member
            </button>
        </div>

        {{-- ==================== FORM ADMIN ==================== --}}
        <div class="form-tab-content active" id="form-admin">
            <form method="POST" action="{{ route('login') }}" id="formAdmin">
                @csrf

                {{-- Email --}}
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-envelope text-primary mr-1"></i> Email Admin
                    </label>
                    <div class="input-group-modern">
                        <input type="email" name="email" class="form-control-modern admin-focus"
                               placeholder="admin@example.com"
                               value="{{ old('email') }}"
                               required autofocus autocomplete="email">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-lock text-primary mr-1"></i> Password
                    </label>
                    <div class="input-group-modern">
                        <input type="password" name="password" id="passwordAdmin"
                               class="form-control-modern admin-focus"
                               placeholder="Masukkan password"
                               required autocomplete="current-password">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="toggle-password" onclick="togglePassword('passwordAdmin', 'toggleIconAdmin')">
                            <i class="fas fa-eye" id="toggleIconAdmin"></i>
                        </button>
                    </div>
                </div>

                {{-- Button --}}
                <button type="submit" class="btn-login admin-btn" id="btnAdmin">
                    <span class="spinner"></span>
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt"></i> Login sebagai Admin
                    </span>
                </button>

                {{-- Hint --}}
                <div class="info-hint">
                    <i class="fas fa-info-circle"></i>
                    Akun default: <strong>admin@example.com</strong> / <strong>password</strong>
                </div>

            </form>
        </div>

        {{-- ==================== FORM MEMBER ==================== --}}
        <div class="form-tab-content" id="form-member">
            <form method="POST" action="{{ route('loginMember') }}" id="formMember">
                @csrf

                {{-- Email --}}
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-envelope text-success mr-1"></i> Email Member
                    </label>
                    <div class="input-group-modern">
                        <input type="email" name="email" class="form-control-modern member-focus"
                               placeholder="member@gmail.com"
                               required autocomplete="email">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <i class="fas fa-lock text-success mr-1"></i> Password
                    </label>
                    <div class="input-group-modern">
                        <input type="password" name="password" id="passwordMember"
                               class="form-control-modern member-focus"
                               placeholder="Masukkan password"
                               required autocomplete="current-password">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="toggle-password" onclick="togglePassword('passwordMember', 'toggleIconMember')">
                            <i class="fas fa-eye" id="toggleIconMember"></i>
                        </button>
                    </div>
                </div>

                {{-- Button --}}
                <button type="submit" class="btn-login member-btn" id="btnMember">
                    <span class="spinner"></span>
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt"></i> Login sebagai Member
                    </span>
                </button>

                {{-- Register Hint --}}
                <div class="info-hint member-hint">
                    <i class="fas fa-user-plus"></i>
                    Belum punya akun?
                    <a href="{{ url('/') }}" style="color: #11998e; font-weight: 600;">
                        Daftar di sini
                    </a>
                </div>

            </form>
        </div>

        {{-- Footer --}}
        <div class="login-form-footer">
            <i class="fas fa-shield-alt"></i>
            Sistem dilindungi & aman
        </div>

    </div>

</div>

{{-- ==================== JS ==================== --}}
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>

<script>
    // ==================== TAB SWITCH ====================
    function switchTab(type) {
        const branding = document.getElementById('brandingPanel');
        const brandContent = document.getElementById('brandContent');
        const tabs = document.querySelectorAll('.login-tab');
        const forms = document.querySelectorAll('.form-tab-content');

        // Reset semua tab
        tabs.forEach(tab => tab.classList.remove('active', 'admin-active', 'member-active'));
        forms.forEach(form => form.classList.remove('active'));

        if (type === 'admin') {
            // Admin theme
            branding.classList.remove('member-theme');
            branding.classList.add('admin-theme');
            tabs[0].classList.add('active', 'admin-active');
            document.getElementById('form-admin').classList.add('active');

            // Update branding content
            brandContent.innerHTML = `
                <h1>Sistem Perpustakaan Digital</h1>
                <p>Kelola koleksi buku, anggota, dan transaksi peminjaman dengan mudah & cepat.</p>
                <div class="brand-features">
                    <div class="brand-feature">
                        <div class="brand-feature-icon"><i class="fas fa-book"></i></div>
                        <span>Manajemen buku & kategori</span>
                    </div>
                    <div class="brand-feature">
                        <div class="brand-feature-icon"><i class="fas fa-users"></i></div>
                        <span>Kelola anggota perpustakaan</span>
                    </div>
                    <div class="brand-feature">
                        <div class="brand-feature-icon"><i class="fas fa-exchange-alt"></i></div>
                        <span>Transaksi peminjaman & pengembalian</span>
                    </div>
                </div>
            `;
        } else {
            // Member theme
            branding.classList.remove('admin-theme');
            branding.classList.add('member-theme');
            tabs[1].classList.add('active', 'member-active');
            document.getElementById('form-member').classList.add('active');

            // Update branding content
            brandContent.innerHTML = `
                <h1>Selamat Datang, Member!</h1>
                <p>Pinjam buku favoritmu dari mana saja dengan mudah dan cepat.</p>
                <div class="brand-features">
                    <div class="brand-feature">
                        <div class="brand-feature-icon"><i class="fas fa-search"></i></div>
                        <span>Cari buku favorit</span>
                    </div>
                    <div class="brand-feature">
                        <div class="brand-feature-icon"><i class="fas fa-bookmark"></i></div>
                        <span>Booking buku online</span>
                    </div>
                    <div class="brand-feature">
                        <div class="brand-feature-icon"><i class="fas fa-history"></i></div>
                        <span>Riwayat peminjaman</span>
                    </div>
                </div>
            `;
        }
    }

    // ==================== TOGGLE PASSWORD ====================
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // ==================== LOADING STATE ====================
    $('#formAdmin').on('submit', function() {
        const btn = $('#btnAdmin');
        btn.addClass('loading');
        btn.prop('disabled', true);
    });

    $('#formMember').on('submit', function() {
        const btn = $('#btnMember');
        btn.addClass('loading');
        btn.prop('disabled', true);
    });

    // ==================== TOASTR ====================
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000",
    };

    @if (Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
        $('.login-container').addClass('shake');
        setTimeout(() => $('.login-container').removeClass('shake'), 500);
    @endif

    @if (Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif

    @if ($errors->any())
        toastr.error("{{ $errors->first() }}");
        $('.login-container').addClass('shake');
        setTimeout(() => $('.login-container').removeClass('shake'), 500);
    @endif
</script>

</body>
</html>