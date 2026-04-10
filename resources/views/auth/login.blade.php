<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            overflow: hidden;
        }

        /* Full Screen Container */
        .login-fullscreen {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        /* Left Side - Full Image */
        .login-image {
            flex: 1.2;
            position: relative;
            overflow: hidden;
        }

        .login-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Right Side - Form */
        .login-form {
            flex: 0.8;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 4rem;
            overflow-y: auto;
        }

        /* Brand */
        .brand {
            margin-bottom: 2.5rem;
        }

        .brand h1 {
            font-size: 2rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .brand p {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.813rem;
            font-weight: 500;
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.875rem;
            font-family: inherit;
            transition: all 0.2s;
            background: #ffffff;
        }

        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.05);
        }

        .form-input.error {
            border-color: #ef4444;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        /* Checkbox */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .checkbox-label input {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #2563eb;
        }

        .checkbox-label span {
            font-size: 0.813rem;
            color: #475569;
        }

        .forgot-link {
            font-size: 0.813rem;
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #1e40af;
        }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 0.875rem;
            background: #0f172a;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 1rem;
        }

        .btn-login:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Register Link */
        .register-link {
            text-align: center;
            font-size: 0.813rem;
            color: #64748b;
        }

        .register-link a {
            color: #0f172a;
            text-decoration: none;
            font-weight: 600;
            margin-left: 0.25rem;
            transition: color 0.2s;
        }

        .register-link a:hover {
            color: #2563eb;
        }

        /* Session Status */
        .session-status {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            margin-bottom: 1.5rem;
            color: #0f172a;
            font-size: 0.813rem;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .divider span {
            padding: 0 1rem;
            color: #94a3b8;
            font-size: 0.75rem;
        }

        /* Scrollbar */
        .login-form::-webkit-scrollbar {
            width: 4px;
        }
        
        .login-form::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .login-form::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .login-fullscreen {
                flex-direction: column;
            }
            
            .login-image {
                flex: 0.5;
                min-height: 280px;
            }
            
            .login-form {
                flex: 0.5;
                padding: 2rem;
                overflow-y: auto;
            }
            
            body {
                overflow: auto;
            }
        }

        @media (max-width: 480px) {
            .login-form {
                padding: 1.5rem;
            }
            
            .brand h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-fullscreen">
        <!-- Left Side - Full Image -->
        <div class="login-image">
            @php
                $imagePath = public_path('images/homepage.png');
                $imageExists = file_exists($imagePath);
            @endphp
            
            @if($imageExists)
                <img src="{{ asset('images/homepage.png') }}" alt="Wedding Decoration">
            @else
                <div style="width: 100%; height: 100%; background: #0f172a; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                    <div style="text-align: center;">
                        <div style="margin-top: 16px;">SuryaMadani</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Side - Form -->
        <div class="login-form">
            <div class="brand">
                <h1>Selamat Datang</h1>
                <p>Masuk ke akun Anda untuk mulai memesan perlengkapan acara</p>
            </div>

            <!-- Session Status -->
            @if(session('status'))
                <div class="session-status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label class="form-label" for="email">
                        Email
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        class="form-input @error('email') error @enderror" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="nama@email.com"
                    >
                    @error('email')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">
                        Password
                    </label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        class="form-input @error('password') error @enderror" 
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="checkbox-wrapper">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember_me">
                        <span>Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn-login">
                    Masuk
                </button>

                <!-- Divider -->
                <div class="divider">
                    <span>Belum punya akun?</span>
                </div>

                <!-- Register Link -->
                <div class="register-link">
                    Daftar
                    <a href="{{ route('register') }}">
                        Buat akun baru
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>