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
            background: linear-gradient(135deg, #003580 0%, #0056b3 50%, #0078d4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
            color: white;
        }

        .login-logo {
            width: 76px;
            height: 76px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .login-header h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.85;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            padding: 36px 32px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.25);
        }

        .login-card h2 {
            font-size: 20px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 24px;
            text-align: center;
        }

        .form-floating label {
            color: #6c757d;
        }

        .form-floating .form-control {
            border-radius: 10px;
            border-color: #dee2e6;
        }

        .form-floating .form-control:focus {
            border-color: #003580;
            box-shadow: 0 0 0 0.2rem rgba(0,53,128,0.15);
        }

        .btn-login {
            background: linear-gradient(135deg, #003580, #0056b3);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #002560, #003580);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,53,128,0.3);
        }

        .back-link {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: white;
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
        <h2><i class="bi bi-shield-lock text-primary"></i> Đăng nhập Quản trị</h2>

        @if($errors->has('login'))
            <div class="alert alert-danger alert-sm mb-3">
                <i class="bi bi-exclamation-triangle"></i> {{ $errors->first('login') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success mb-3">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ url('/admin/login') }}">
            @csrf

            <div class="form-floating mb-3">
                <input type="text"
                       class="form-control @error('username') is-invalid @enderror"
                       id="username"
                       name="username"
                       placeholder="Tên đăng nhập"
                       value="{{ old('username') }}"
                       autofocus>
                <label for="username"><i class="bi bi-person"></i> Tên đăng nhập</label>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating mb-4">
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       id="password"
                       name="password"
                       placeholder="Mật khẩu">
                <label for="password"><i class="bi bi-lock"></i> Mật khẩu</label>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
            </button>
        </form>
    </div>

    <a href="{{ route('home') }}" class="back-link">
        <i class="bi bi-arrow-left"></i> Quay về trang chủ
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
