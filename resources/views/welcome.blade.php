<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> Wedding & Party Rentals</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', 'Inter', sans-serif;
            background: #ffffff;
            color: #1f2937;
            scroll-behavior: smooth;
        }

        /* Navbar */
        .navbar {
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 1rem 2rem;
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo img {
            height: 40px;
            width: auto;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-links a {
            text-decoration: none;
            color: #475569;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #2563eb;
        }

        /* Login Button */
        .btn-login {
            padding: 0.5rem 1.25rem;
            background: #2563eb;
            color: white !important;
            border: none;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn-login:hover {
            background: #1d4ed8;
            color: white !important;
        }

        /* Register Button */
        .btn-register {
            padding: 0.5rem 1.25rem;
            background: #0f172a;
            color: white !important;
            border: none;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn-register:hover {
            background: #1e293b;
            color: white !important;
        }

        /* Dashboard / Katalog Button */
        .btn-dashboard {
            padding: 0.5rem 1.25rem;
            background: #0f172a;
            color: white !important;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn-dashboard:hover {
            background: #1e293b;
            color: white !important;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            padding: 4rem 2rem;
        }

        .hero-container {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero-content h1 span {
            color: #2563eb;
        }

        .hero-content p {
            color: #64748b;
            font-size: 1.125rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Hero Primary Button */
        .btn-primary {
            padding: 0.75rem 1.5rem;
            background: #2563eb;
            color: white;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        /* Hero Secondary Button */
        .btn-outline {
            padding: 0.75rem 1.5rem;
            background: #0f172a;
            color: #ffffff;
            border: none;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s;
        }

        .btn-outline:hover {
            background: #1e293b;
        }

        .hero-image img {
            width: 100%;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
        }

        /* Section Styles */
        .section {
            padding: 5rem 2rem;
        }

        .section-container {
            max-width: 1280px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .section-title p {
            color: #64748b;
            font-size: 1rem;
        }

        .section-title .underline {
            width: 60px;
            height: 3px;
            background: #2563eb;
            margin: 1rem auto 0;
            border-radius: 2px;
        }

        /* Steps Grid */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }

        .step-card {
            text-align: center;
            padding: 1.5rem;
            background: white;
            border-radius: 1rem;
            transition: all 0.3s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .step-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -12px rgba(0, 0, 0, 0.1);
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: #eff6ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2563eb;
        }

        .step-card h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .step-card p {
            color: #64748b;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* Portfolio/Gallery Grid */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        .portfolio-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .portfolio-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15);
            border-color: #bfdbfe;
        }

        .portfolio-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .portfolio-image-placeholder {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: #4338ca;
        }

        .portfolio-content {
            padding: 1.25rem;
        }

        .portfolio-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .portfolio-description {
            color: #64748b;
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }

        .portfolio-date {
            font-size: 0.7rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Pagination */
        .pagination-container {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-item .page-link {
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            color: #475569;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: white;
            cursor: pointer;
        }

        .page-item .page-link:hover {
            background: #eff6ff;
            border-color: #2563eb;
            color: #2563eb;
        }

        .page-item.active .page-link {
            background: #2563eb;
            border-color: #2563eb;
            color: white;
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Loading Spinner */
        .loading-spinner {
            text-align: center;
            padding: 2rem;
            display: none;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #e2e8f0;
            border-top-color: #2563eb;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* CTA Section */
        .cta-section {
            background: #f8fafc;
            text-align: center;
            padding: 4rem 2rem;
            border-radius: 1.5rem;
            margin-top: 2rem;
        }

        .cta-section h2 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1rem;
        }

        .cta-section p {
            color: #64748b;
            margin-bottom: 2rem;
        }

        /* Footer */
        .footer {
            background: #0a0f1a;
            color: #9ca3af;
            padding: 3rem 2rem 1rem;
        }

        .footer-container {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }

        .footer-col h4 {
            color: white;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1rem;
            position: relative;
            display: inline-block;
        }

        .footer-col h4::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 30px;
            height: 2px;
            background: #2563eb;
            border-radius: 1px;
        }

        .footer-col p {
            font-size: 0.875rem;
            line-height: 1.6;
            margin-bottom: 0.5rem;
        }

        .footer-col a {
            color: #9ca3af;
            text-decoration: none;
            font-size: 0.875rem;
            display: block;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }

        .footer-col a:hover {
            color: #60a5fa;
            transform: translateX(3px);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            width: 36px;
            height: 36px;
            background: #1e293b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .social-links a:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid #1e293b;
            font-size: 0.75rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .hero-buttons {
                justify-content: center;
            }
            .steps-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .footer-container {
                grid-template-columns: repeat(2, 1fr);
            }
            .nav-links {
                display: none;
            }
            .portfolio-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .steps-grid {
                grid-template-columns: 1fr;
            }
            .footer-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <img src="{{ asset('images/navbar.png') }}" alt="Logo" style="height: 40px;">
            </div>
            <div class="nav-links">
                <a href="#home">Beranda</a>
                <a href="#steps">Cara Pemesanan</a>
                <a href="#portfolio">Dokumentasi</a>
                @if (Route::has('login'))
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn-dashboard">Dashboard</a>
                        @else
                            <a href="{{ route('customer.catalog') }}" class="btn-dashboard">Katalog</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-login">Login</a>
                        <a href="{{ route('register') }}" class="btn-register">Register</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1>Wedding & Party <span>Rentals</span></h1>
                <p>Solusi lengkap perlengkapan untuk acara spesial Anda. Dari dekorasi, kursi, panggung, hingga sound system berkualitas tinggi.</p>
                <div class="hero-buttons">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn-primary">Dashboard Admin</a>
                        @else
                            <a href="{{ route('customer.catalog') }}" class="btn-primary">Lihat Katalog</a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="btn-primary">Daftar Sekarang</a>
                        <a href="{{ route('login') }}" class="btn-outline">Login</a>
                    @endauth
                </div>
            </div>
            <div class="hero-image">
                @php
                    $welcomeImage = public_path('images/homepage.png');
                    $imageExists = file_exists($welcomeImage);
                @endphp
                @if($imageExists)
                    <img src="{{ asset('images/homepage.png') }}" alt="Wedding Decoration">
                @else
                    <div style="background: linear-gradient(135deg, #2563eb, #1e40af); border-radius: 1rem; padding: 3rem; text-align: center; color: white;">
                        <div style="font-size: 4rem;">🎪</div>
                        <p style="margin-top: 1rem;">Wedding & Party Rentals</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Steps Section -->
    <section id="steps" class="section">
        <div class="section-container">
            <div class="section-title">
                <h2>Cara Pemesanan</h2>
                <p>Mudah dan cepat, hanya 4 langkah sederhana</p>
                <div class="underline"></div>
            </div>
            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h3>Pilih Item</h3>
                    <p>Jelajahi katalog kami dan pilih item yang Anda butuhkan untuk acara</p>
                </div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h3>Tentukan Jumlah</h3>
                    <p>Tentukan jumlah item yang diperlukan untuk acara Anda</p>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h3>Isi Data Diri</h3>
                    <p>Lengkapi informasi pemesanan seperti tanggal dan alamat</p>
                </div>
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h3>Konfirmasi Pesanan</h3>
                    <p>Pesanan akan diproses dan dikonfirmasi oleh admin kami</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio/Documentation Section -->
    <section id="portfolio" class="section" style="background: #f8fafc;">
        <div class="section-container">
            <div class="section-title">
                <h2>Dokumentasi Acara</h2>
                <p>Galeri dokumentasi acara yang telah menggunakan jasa kami</p>
                <div class="underline"></div>
            </div>
            
            <div id="portfolio-container">
                <!-- Portfolio content will be loaded here via AJAX -->
                <div class="loading-spinner" id="loading-spinner">
                    <div class="spinner"></div>
                </div>
                <div id="portfolio-grid" class="portfolio-grid"></div>
                <div id="pagination-container" class="pagination-container"></div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section">
        <div class="section-container">
            <div class="cta-section">
                <h2>Siap Membuat Acara Spesial Anda?</h2>
                <p>Dapatkan penawaran terbaik dan konsultasi gratis dengan tim kami</p>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn-primary">Kelola Sistem</a>
                    @else
                        <a href="{{ route('customer.catalog') }}" class="btn-primary">Mulai Pesan Sekarang</a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-primary">Daftar Sekarang</a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-col">
                <h4>SuryaMadani</h4>
                <p>Wedding & Party Rentals solusi lengkap untuk acara spesial Anda.</p>
                <div class="social-links">
                        <a href="https://www.instagram.com/suryamadani_wedding?igsh=MTRhaWt3bW54MHRmMw==">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                <path d="M16 11.37a4 4 0 1 1-7.75 1.13 4 4 0 0 1 7.75-1.13z"/>
                                <line x1="17.5" y1="6.5" x2="17.5" y2="6.5"/>
                            </svg>
                        </a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Tautan Cepat</h4>
                <a href="#home">Beranda</a>
                <a href="#steps">Cara Pemesanan</a>
                <a href="#portfolio">Dokumentasi</a>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                    @else
                        <a href="{{ route('customer.catalog') }}">Katalog</a>
                    @endif
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
            <div class="footer-col">
                <h4>Kontak</h4>
                <p>📞 Admin 1: 082-244-735-038</p>
                <p>📞 Admin 2: 0888 4088 042</p>
                <p>📍 Desa Sugihwaras No. 19 RT.26 / 02 Maospati Kab. Magetan </p>
                <p>🕐 Senin - Sabtu: 09:00 - 18:00</p>
            </div>
            <div class="footer-col">
                <h4>Jam Operasional</h4>
                <p>Senin - Sabtu: 09:00 - 18:00</p>
                <p>Minggu: Tutup</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} SuryaMadani Wedding & Party Rentals. All rights reserved. | Designed with ❤️ for your special day</p>
        </div>
    </footer>

    <script>
        let currentPage = 1;

        function loadTestimonials(page = 1) {
            $('#loading-spinner').show();
            $('#portfolio-grid').hide();
            
            $.ajax({
                url: '{{ route("testimonials.paginate") }}',
                type: 'GET',
                data: { page: page },
                success: function(response) {
                    renderPortfolio(response.data);
                    renderPagination(response);
                    currentPage = page;
                    $('#loading-spinner').hide();
                    $('#portfolio-grid').fadeIn();
                },
                error: function(xhr) {
                    console.error('Error loading testimonials:', xhr);
                    $('#loading-spinner').hide();
                }
            });
        }

        function renderPortfolio(testimonials) {
            let html = '';
            
            testimonials.forEach(function(item) {
                let imageHtml = '';
                if (item.image && item.image !== null) {
                    imageHtml = `<img src="/storage/${item.image}" class="portfolio-image" alt="${item.title}">`;
                } else {
                    imageHtml = `<div class="portfolio-image-placeholder">🎪</div>`;
                }
                
                html += `
                    <div class="portfolio-card">
                        ${imageHtml}
                        <div class="portfolio-content">
                            <h3 class="portfolio-title">${item.title}</h3>
                            <p class="portfolio-description">${item.description ? item.description.substring(0, 100) + (item.description.length > 100 ? '...' : '') : '-'}</p>
                            <div class="portfolio-date">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                ${new Date(item.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            $('#portfolio-grid').html(html);
        }

        function renderPagination(pagination) {
            let html = '<ul class="pagination">';
            
            if (pagination.prev_page_url) {
                html += `<li class="page-item"><button class="page-link" onclick="loadTestimonials(${pagination.current_page - 1})">« Sebelumnya</button></li>`;
            } else {
                html += `<li class="page-item disabled"><span class="page-link">« Sebelumnya</span></li>`;
            }
            
            for (let i = 1; i <= pagination.last_page; i++) {
                if (i === pagination.current_page) {
                    html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                } else {
                    html += `<li class="page-item"><button class="page-link" onclick="loadTestimonials(${i})">${i}</button></li>`;
                }
            }
            
            if (pagination.next_page_url) {
                html += `<li class="page-item"><button class="page-link" onclick="loadTestimonials(${pagination.current_page + 1})">Selanjutnya »</button></li>`;
            } else {
                html += `<li class="page-item disabled"><span class="page-link">Selanjutnya »</span></li>`;
            }
            
            html += '</ul>';
            $('#pagination-container').html(html);
        }

        $(document).ready(function() {
            loadTestimonials(1);
        });
    </script>
</body>
</html>