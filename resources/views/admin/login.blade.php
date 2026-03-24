<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Quản trị - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #003580 0%, #0056b3 55%, #0078d4 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 16px;
        }

        .login-wrapper { width: 100%; max-width: 400px; }

        .login-header { text-align: center; margin-bottom: 28px; color: white; }

        .login-logo {
            width: 72px; height: 72px; background: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 34px; margin: 0 auto 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,.2);
        }

        .login-header h1 { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
        .login-header p  { font-size: 13px; opacity: .85; margin: 0; }

        .login-card {
            background: white; border-radius: 16px; padding: 32px 28px;
            box-shadow: 0 8px 40px rgba(0,0,0,.25);
        }

        .login-card h2 {
            font-size: 18px; font-weight: 700; color: #212529;
            margin-bottom: 22px; text-align: center;
        }

        .form-control {
            border-radius: 10px; border-color: #dee2e6;
            font-size: 14px; padding: 10px 14px;
        }

        .form-control:focus {
            border-color: #003580;
            box-shadow: 0 0 0 3px rgba(0,53,128,.12);
        }

        .input-wrap { position: relative; }

        .input-wrap .ico {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%); color: #6c757d;
            font-size: 15px; pointer-events: none;
        }

        .input-wrap input { padding-left: 36px; }

        .input-wrap .btn-eye {
            position: absolute; right: 4px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #6c757d;
            padding: 6px 10px; cursor: pointer; font-size: 15px; line-height: 1;
        }

        .btn-login {
            background: linear-gradient(135deg, #003580, #0056b3);
            color: white; border: none; border-radius: 10px;
            padding: 11px; font-size: 15px; font-weight: 600; width: 100%;
            transition: all .2s;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #002560, #003580); color: white;
            transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,53,128,.3);
        }

        .back-link {
            color: rgba(255,255,255,.85); text-decoration: none; font-size: 13px;
            display: flex; align-items: center; justify-content: center;
            gap: 6px; margin-top: 18px; transition: color .2s;
        }

        .back-link:hover { color: white; }

        @media (max-width: 400px) {
            .login-card { padding: 24px 18px; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-header">
        <div class="login-logo">🗺️</div>
        <h1>{{ config('app.name') }}</h1>
        <p>Cổng thông tin địa lý điện tử</p>
    </div>

    <div class="login-card">
        <h2><i class="bi bi-shield-lock text-primary me-2"></i>Đăng nhập Quản trị</h2>

        @if($errors->has('login'))
            <div class="alert alert-danger mb-3 py-2" style="font-size:13px;">
                <i class="bi bi-exclamation-triangle me-1"></i>{{ $errors->first('login') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success mb-3 py-2" style="font-size:13px;">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ url('/admin/login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13px;">Tên đăng nhập</label>
                <div class="input-wrap">
                    <i class="bi bi-person ico"></i>
                    <input type="text"
                           class="form-control @error('username') is-invalid @enderror"
                           name="username" value="{{ old('username') }}"
                           placeholder="Nhập tên đăng nhập" autofocus autocomplete="username">
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px;">Mật khẩu</label>
                <div class="input-wrap">
                    <i class="bi bi-lock ico"></i>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="pwdInput" name="password"
                           placeholder="Nhập mật khẩu" autocomplete="current-password">
                    <button type="button" class="btn-eye" id="togglePwd" tabindex="-1">
                        <i class="bi bi-eye" id="eyeIco"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
            </button>
        </form>
    </div>

    <a href="{{ route('home') }}" class="back-link">
        <i class="bi bi-arrow-left"></i> Quay về trang chủ
    </a>
</div>

<script>
    document.getElementById('togglePwd').addEventListener('click', function () {
        const i = document.getElementById('pwdInput');
        const e = document.getElementById('eyeIco');
        i.type = i.type === 'password' ? 'text' : 'password';
        e.className = i.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    });
</script>
</body>
</html>
