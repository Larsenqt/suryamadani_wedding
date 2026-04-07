<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Register</title>

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
        .register-fullscreen {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        /* Left Side - Full Image */
        .register-image {
            flex: 1.2;
            position: relative;
            overflow: hidden;
        }

        .register-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Right Side - Form */
        .register-form {
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

        /* Form Styles */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.25rem;
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
            padding: 0.75rem 1rem;
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

        /* Button */
        .btn-register {
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
            margin-top: 0.5rem;
            margin-bottom: 1rem;
        }

        .btn-register:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        /* Login Link */
        .login-link {
            text-align: center;
            font-size: 0.813rem;
            color: #64748b;
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
        .register-form::-webkit-scrollbar {
            width: 4px;
        }
        
        .register-form::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .register-form::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .register-fullscreen {
                flex-direction: column;
            }
            
            .register-image {
                flex: 0.5;
                min-height: 280px;
            }
            
            .register-form {
                flex: 0.5;
                padding: 2rem;
                overflow-y: auto;
            }
            
            body {
                overflow: auto;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }

        @media (max-width: 480px) {
            .register-form {
                padding: 1.5rem;
            }
            
            .brand h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-fullscreen">
        <!-- Left Side - Full Image -->
        <div class="register-image">
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
        <div class="register-form">
            <div class="brand">
                <h1>Buat Akun Baru</h1>
                <p>Daftar sekarang untuk mulai memesan perlengkapan acara</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label class="form-label" for="name">
                        Nama Lengkap
                    </label>
                    <input 
                        id="name" 
                        type="text" 
                        name="name" 
                        class="form-input @error('name') error @enderror" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        autocomplete="name"
                        placeholder="Nama lengkap Anda"
                    >
                    @error('name')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

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
                        autocomplete="username"
                        placeholder="nama@email.com"
                    >
                    @error('email')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Phone and Address Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="phone">
                            Nomor Telepon
                        </label>
                        <input 
                            id="phone" 
                            type="tel" 
                            name="phone" 
                            class="form-input @error('phone') error @enderror" 
                            value="{{ old('phone') }}" 
                            placeholder="081234567890"
                        >
                        @error('phone')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="address">
                            Alamat
                        </label>
                        <input 
                            id="address" 
                            type="text" 
                            name="address" 
                            class="form-input @error('address') error @enderror" 
                            value="{{ old('address') }}" 
                            placeholder="Jl. Contoh No. 123, Kota"
                        >
                        @error('address')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="form-row">
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
                            autocomplete="new-password"
                            placeholder="••••••••"
                        >
                        @error('password')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">
                            Konfirmasi Password
                        </label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            class="form-input" 
                            required 
                            autocomplete="new-password"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn-register">
                    Daftar
                </button>

                <!-- Divider -->
                <div class="divider">
                    <span>Sudah punya akun?</span>
                </div>

                <!-- Login Link -->
                <div class="login-link">
                    Masuk
                    <a href="{{ route('login') }}">
                        Login di sini
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>