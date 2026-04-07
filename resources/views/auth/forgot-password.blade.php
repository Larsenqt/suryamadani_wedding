<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Lupa Password</title>

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
        .forgot-fullscreen {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        /* Left Side - Full Image */
        .forgot-image {
            flex: 1.2;
            position: relative;
            overflow: hidden;
        }

        .forgot-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Right Side - Form */
        .forgot-form {
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
            margin-bottom: 2rem;
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

        /* Info Box */
        .info-box {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.813rem;
            color: #475569;
            line-height: 1.5;
        }

        .info-box svg {
            flex-shrink: 0;
            margin-top: 1px;
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

        /* Session Status */
        .session-status {
            background: #dcfce7;
            border: 1px solid #a7f3d0;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            margin-bottom: 1.5rem;
            color: #166534;
            font-size: 0.813rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Button */
        .btn-submit {
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

        .btn-submit:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Login Link */
        .login-link {
            text-align: center;
            font-size: 0.813rem;
            color: #64748b;
            margin-top: 1rem;
        }

        .login-link a {
            color: #0f172a;
            text-decoration: none;
            font-weight: 600;
            margin-left: 0.25rem;
            transition: color 0.2s;
        }

        .login-link a:hover {
            color: #2563eb;
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
        .forgot-form::-webkit-scrollbar {
            width: 4px;
        }
        
        .forgot-form::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .forgot-form::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .forgot-fullscreen {
                flex-direction: column;
            }
            
            .forgot-image {
                flex: 0.5;
                min-height: 280px;
            }
            
            .forgot-form {
                flex: 0.5;
                padding: 2rem;
                overflow-y: auto;
            }
            
            body {
                overflow: auto;
            }
        }

        @media (max-width: 480px) {
            .forgot-form {
                padding: 1.5rem;
            }
            
            .brand h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-fullscreen">
        <!-- Left Side - Full Image -->
        <div class="forgot-image">
            @php
                $imagePath = public_path('images/homepage.png');
                $imageExists = file_exists($imagePath);
            @endphp
            
            @if($imageExists)
                <img src="{{ asset('images/homepage.png') }}" alt="Wedding Decoration">
            @else
                <div style="width: 100%; height: 100%; background: #0f172a; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                    <div style="text-align: center;">
                        <div style="font-size: 48px;">🎪</div>
                        <div style="margin-top: 16px;">SuryaMadani</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Side - Form -->
        <div class="forgot-form">
            <div class="brand">
                <h1>Lupa Password?</h1>
                <p>Masukkan email Anda untuk menerima link reset password</p>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4M12 8h.01"/>
                </svg>
                <span>Masukkan alamat email yang terdaftar, kami akan mengirimkan link untuk mereset password Anda.</span>
            </div>

            <!-- Session Status -->
            @if(session('status'))
                <div class="session-status">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6L9 17l-5-5"/>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
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
                        placeholder="nama@email.com"
                    >
                    @error('email')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    Kirim Link Reset Password
                </button>

                <!-- Divider -->
                <div class="divider">
                    <span>Kembali ke</span>
                </div>

                <!-- Login Link -->
                <div class="login-link">
                    <a href="{{ route('login') }}">
                        Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>