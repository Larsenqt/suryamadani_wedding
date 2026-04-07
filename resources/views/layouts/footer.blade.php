<footer class="customer-footer">
    <div class="footer-container">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <span class="logo-text">SuryaMadani</span>
                    </div>
                    <p class="footer-description">Wedding & Party Rentals terpercaya untuk acara spesial Anda.</p>
                </div>
            </div>
            
            <div class="footer-col">
                <h4 class="footer-title">Tautan Cepat</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('customer.catalog') }}">Katalog</a></li>
                    <li><a href="{{ route('customer.orders.history') }}">Riwayat Pesanan</a></li>
                    <li><a href="{{ route('customer.checkout') }}">Keranjang</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4 class="footer-title">Hubungi Kami</h4>
                <ul class="footer-contact">
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <span>+62 813 4648 067</span>
                    </li>
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <span>info@suryamadani.com</span>
                    </li>
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span>Maospati - Magetan</span>
                    </li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4 class="footer-title">Jam Operasional</h4>
                <ul class="footer-hours">
                    <li>
                        <span>Senin - Sabtu</span>
                        <span>09:00 - 18:00</span>
                    </li>
                    <li>
                        <span>Minggu</span>
                        <span>Tutup</span>
                    </li>
                </ul>
                <div class="footer-social">
                    <a href="#" class="social-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"/>
                            <path d="M7 2v20M17 2v20M2 12h20M2 7h5M2 17h5M17 17h5M17 7h5"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} SuryaMadani Wedding & Party Rentals. All rights reserved.</p>
            <p class="footer-tagline">Dedicated to your special day ❤️</p>
        </div>
    </div>
</footer>

<style>
    .customer-footer {
        background: #0f172a;
        color: #94a3b8;
        margin-top: 3rem;
        padding: 3rem 0 1rem;
    }

    .footer-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
        margin-bottom: 2rem;
    }

    /* Brand Section */
    .footer-brand {
        margin-bottom: 1rem;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .logo-icon {
        font-size: 1.75rem;
    }

    .logo-text {
        font-size: 1.25rem;
        font-weight: 700;
        background: linear-gradient(135deg, #60a5fa, #3b82f6);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .footer-description {
        font-size: 0.875rem;
        line-height: 1.6;
        color: #94a3b8;
    }

    /* Footer Titles */
    .footer-title {
        color: white;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        position: relative;
        display: inline-block;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 30px;
        height: 2px;
        background: #3b82f6;
        border-radius: 2px;
    }

    /* Footer Links */
    .footer-links {
        list-style: none;
    }

    .footer-links li {
        margin-bottom: 0.5rem;
    }

    .footer-links a {
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.2s;
        display: inline-block;
    }

    .footer-links a:hover {
        color: #60a5fa;
        transform: translateX(3px);
    }

    /* Contact List */
    .footer-contact li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
        color: #94a3b8;
    }

    .footer-contact svg {
        flex-shrink: 0;
        stroke: #60a5fa;
    }

    /* Hours */
    .footer-hours li {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        color: #94a3b8;
    }

    /* Social Icons */
    .footer-social {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .social-icon {
        width: 36px;
        height: 36px;
        background: #1e293b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .social-icon svg {
        stroke: #94a3b8;
        width: 18px;
        height: 18px;
    }

    .social-icon:hover {
        background: #2563eb;
        transform: translateY(-2px);
    }

    .social-icon:hover svg {
        stroke: white;
    }

    /* Footer Bottom */
    .footer-bottom {
        text-align: center;
        padding-top: 1.5rem;
        border-top: 1px solid #1e293b;
        font-size: 0.75rem;
    }

    .footer-tagline {
        margin-top: 0.5rem;
        font-size: 0.7rem;
        color: #64748b;
    }

    /* Responsive */
    @media (max-width: 968px) {
        .footer-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
    }

    @media (max-width: 640px) {
        .footer-container {
            padding: 0 1rem;
        }
        
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .footer-bottom {
            padding-top: 1rem;
        }
    }
</style>