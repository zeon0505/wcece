<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WH CHINA by CECE — Jasa Forwarding China ke Indonesia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Pastel theme overrides */
        body { 
            background: linear-gradient(135deg, var(--blue) 0%, var(--pink) 100%);
            background-attachment: fixed;
            color: var(--ink);
        }
        
        .landing-navbar {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
            position: fixed; width: 100%; top: 0; z-index: 1000;
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 5%; transition: all 0.3s ease;
        }
        
        .landing-brand { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.5rem; color: var(--ink); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; }
        .landing-nav { display: flex; gap: 2rem; align-items: center; }
        .landing-nav a { color: var(--ink); text-decoration: none; font-weight: 500; font-size: 0.9rem; transition: color 0.2s; }
        .landing-nav a:hover { color: var(--blue-deep); }
        @media (max-width: 768px) { .landing-nav { display: none; } }

        .hero {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 8rem 5% 4rem; position: relative; overflow: hidden;
        }
        .blob-1 { position: absolute; top: 0; left: -10%; width: 50vw; height: 50vw; border-radius: 50%; background: radial-gradient(circle, rgba(95, 168, 211, 0.4) 0%, rgba(255,255,255,0) 70%); z-index: 0; filter: blur(60px); }
        .blob-2 { position: absolute; bottom: -20%; right: -10%; width: 60vw; height: 60vw; border-radius: 50%; background: radial-gradient(circle, rgba(224, 143, 178, 0.3) 0%, rgba(255,255,255,0) 70%); z-index: 0; filter: blur(80px); }
        
        .hero-content { position: relative; z-index: 10; max-width: 800px; text-align: center; }
        .hero-title { font-family: 'Space Grotesk', sans-serif; font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; color: var(--ink); line-height: 1.1; margin-bottom: 1.5rem; letter-spacing: -0.03em; }
        .hero-subtitle { font-size: 1.125rem; color: var(--ink-soft); margin-bottom: 2.5rem; max-width: 600px; margin-inline: auto; line-height: 1.6; }
        
        .hero-tracking { 
            background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); 
            padding: 0.5rem; border-radius: 50px; display: flex; 
            box-shadow: 0 10px 30px rgba(224, 143, 178, 0.15); 
            max-width: 500px; margin: 0 auto; 
            border: 1px solid rgba(255,255,255,0.8); transition: box-shadow 0.3s ease; 
        }
        .hero-tracking:focus-within { box-shadow: 0 15px 40px rgba(95, 168, 211, 0.3); border-color: var(--white); background: var(--white); }
        .hero-tracking input { flex: 1; border: none; padding: 1rem 1.5rem; border-radius: 50px; font-size: 1rem; outline: none; background: transparent; }
        .hero-tracking button { background: var(--ink); color: var(--white); border: none; padding: 0 2rem; border-radius: 50px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .hero-tracking button:hover { background: #2d3340; }

        .section { padding: 5rem 5%; position: relative; z-index: 10; }
        
        /* Glassmorphism Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        /* Pastel Cards */
        .card-blue {
            background: linear-gradient(135deg, rgba(214, 238, 249, 0.9) 0%, rgba(191, 226, 245, 0.9) 100%);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(95, 168, 211, 0.15);
            padding: 2rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .card-blue:hover { transform: translateY(-5px); }

        .card-pink {
            background: linear-gradient(135deg, rgba(250, 228, 237, 0.9) 0%, rgba(246, 211, 226, 0.9) 100%);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(224, 143, 178, 0.15);
            padding: 2rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .card-pink:hover { transform: translateY(-5px); }

        .section-header { text-align: center; margin-bottom: 3rem; }
        .section-title { font-family: 'Space Grotesk', sans-serif; font-size: 2.2rem; font-weight: 700; color: var(--ink); margin-bottom: 1rem; }
        .section-subtitle { color: var(--ink-soft); max-width: 600px; margin: 0 auto; line-height: 1.6; }

        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; max-width: 1200px; margin: 0 auto; }
        .feature-card { padding: 2.5rem 2rem; transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(224, 143, 178, 0.15); background: rgba(255, 255, 255, 0.85); }
        
        .feature-icon { width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; font-size: 1.5rem; }
        .feature-card:nth-child(1) .feature-icon { background: rgba(191, 226, 245, 0.8); color: var(--blue-deep); }
        .feature-card:nth-child(2) .feature-icon { background: rgba(246, 211, 226, 0.8); color: var(--pink-deep); }
        .feature-card:nth-child(3) .feature-icon { background: rgba(214, 238, 249, 0.8); color: var(--blue-deep); }
        
        .feature-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: var(--ink); }
        .feature-text { color: var(--ink-soft); line-height: 1.6; font-size: 0.95rem; }

        /* Pricing & Info */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; max-width: 1200px; margin: 0 auto; align-items: center; }
        .info-card { padding: 2rem; margin-bottom: 1.5rem; }
        .info-card h4 { font-family: 'Space Grotesk', sans-serif; font-size: 1.25rem; font-weight: 700; color: var(--ink); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .info-list { list-style: none; margin: 0; padding: 0; }
        .info-list li { margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px dashed rgba(208, 215, 224, 0.6); display: flex; justify-content: space-between; align-items: center; }
        .info-list li:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
        .info-label { font-weight: 600; color: var(--ink); font-size: 0.95rem; }
        .info-value { color: var(--ink-soft); font-family: 'Space Mono', monospace; background: rgba(255,255,255,0.7); padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.85rem; font-weight: 700; }

        /* Addresses */
        .address-box { margin-bottom: 1.5rem; }
        .address-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; font-weight: 700; color: var(--ink); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
        .address-row { display: grid; grid-template-columns: 100px 1fr; gap: 1rem; align-items: start; background: rgba(255,255,255,0.6); padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 0.75rem; }
        .address-row strong { color: var(--ink); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.1rem; }
        .address-row span { color: var(--ink-soft); font-size: 1rem; line-height: 1.5; font-weight: 500; }

        /* Terms & Conditions */
        .tnc-list { list-style: none; padding: 0; max-width: 800px; margin: 0 auto; }
        .tnc-list li { position: relative; padding-left: 2rem; margin-bottom: 1.25rem; color: var(--ink-soft); line-height: 1.6; font-size: 0.95rem; }
        .tnc-list li::before { content: '→'; position: absolute; left: 0; top: 2px; color: var(--pink-deep); font-weight: 700; }
        .tnc-list li strong { color: var(--ink); }
        .tnc-alert { background: rgba(251, 225, 223, 0.7); border: 1px solid var(--red); color: var(--red); padding: 1rem 1.5rem; border-radius: 12px; font-weight: 600; margin-bottom: 2rem; display: flex; gap: 0.75rem; align-items: flex-start; }

        .cta { margin: 4rem 5%; padding: 4rem; background: linear-gradient(135deg, var(--blue-deep) 0%, var(--pink-deep) 100%); border-radius: 30px; color: var(--white); text-align: center; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(224, 143, 178, 0.3); }
        .cta-content { position: relative; z-index: 10; max-width: 600px; margin: 0 auto; }
        .cta h2 { font-family: 'Space Grotesk', sans-serif; font-size: 2.2rem; color: var(--white); margin-bottom: 1rem; }
        .cta p { color: rgba(255,255,255,0.9); margin-bottom: 2rem; }
        .cta-buttons { display: flex; gap: 1rem; justify-content: center; }
        .btn-white { background: var(--white); color: var(--ink); padding: 1rem 2.5rem; border-radius: 50px; font-weight: 600; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .btn-white:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        
        .landing-footer { padding: 2rem 5%; background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(10px); border-top: 1px solid rgba(255,255,255,0.6); display: flex; justify-content: space-between; align-items: center; color: var(--ink-soft); font-size: 0.9rem; }

        @media (max-width: 900px) { .info-grid { grid-template-columns: 1fr; gap: 2rem; } }
        @media (max-width: 768px) {
            .landing-nav { display: none; }
            .hero-tracking { flex-direction: column; border-radius: 20px; padding: 1rem; background: rgba(255,255,255,0.8); }
            .hero-tracking input { background: var(--white); border: 1px solid var(--line); margin-bottom: 0.5rem; padding: 1rem; border-radius: 12px; }
            .hero-tracking button { padding: 1rem; border-radius: 12px; }
            .cta { padding: 3rem 1.5rem; margin: 2rem 5%; border-radius: 20px; }
            .cta-buttons { flex-direction: column; }
            .section-title { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

    <nav class="landing-navbar">
        <a href="{{ route('home') }}" class="landing-brand">
            <span style="width:12px;height:12px;background:var(--pink-deep);border-radius:50%;display:inline-block;"></span>
            WH CHINA by CECE
        </a>
        <div class="landing-nav">
            <a href="{{ request()->routeIs('home') ? '#cara-kerja' : route('home') . '#cara-kerja' }}">Cara Kerja</a>
            <a href="{{ request()->routeIs('home') ? '#harga' : route('home') . '#harga' }}">Harga &amp; Info</a>
            <a href="{{ route('tnc') }}" style="{{ request()->routeIs('tnc') ? 'color:var(--blue-deep); font-weight:700;' : '' }}">Syarat &amp; Ketentuan</a>
            <a href="{{ route('privacy') }}" style="{{ request()->routeIs('privacy') ? 'color:var(--blue-deep); font-weight:700;' : '' }}">Kebijakan Privasi</a>
            @auth
                <a href="{{ route('dashboard') }}" style="background:var(--ink); color:var(--white); padding:0.6rem 1.25rem; border-radius:50px; font-weight:600; text-decoration:none;">Ke Dashboard</a>
            @else
                <a href="{{ route('login') }}" style="font-weight:600;">Masuk</a>
                <a href="{{ route('register') }}" style="background:var(--ink); color:var(--white); padding:0.6rem 1.25rem; border-radius:50px; font-weight:600; text-decoration:none;">Daftar</a>
            @endauth
        </div>
    </nav>

    <section class="hero">
        <div class="blob-1"></div>
        <div class="blob-2"></div>
        <div class="hero-content">
            <h1 class="hero-title">Kirim Barang dari China Makin Mudah</h1>
            <p class="hero-subtitle">Layanan forwarding terpercaya. Cukup belanja dari supplier favorit Anda, kirim ke gudang kami di China, dan pantau statusnya hingga tiba di depan pintu rumah Anda.</p>
            
            <form action="{{ route('login') }}" class="hero-tracking">
                <input type="text" placeholder="Masukkan nomor resi..." aria-label="Tracking Resi">
                <button type="submit">Lacak Paket</button>
            </form>
            <p style="font-size: 0.8rem; color: var(--ink-soft); margin-top: 1rem;">*Masuk atau daftar untuk mulai melacak resi Anda secara real-time.</p>
        </div>
    </section>

    <section id="cara-kerja" class="section">
        <div class="section-header">
            <h2 class="section-title">3 Langkah Mudah</h2>
            <p class="section-subtitle">Sistem kami dirancang untuk memberikan kemudahan dan transparansi penuh bagi bisnis Anda.</p>
        </div>
        
        <div class="features-grid">
            <div class="glass-card feature-card">
                <div class="feature-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="feature-title">1. Daftar & Dapatkan Alamat</h3>
                <p class="feature-text">Buat akun WH CHINA by CECE dan dapatkan alamat unik gudang kami di China beserta marking code khusus Anda.</p>
            </div>
            
            <div class="glass-card feature-card">
                <div class="feature-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="feature-title">2. Belanja & Kirim ke Gudang</h3>
                <p class="feature-text">Belanja di platform e-commerce China (1688, Taobao, dll). Gunakan alamat gudang kami sebagai tujuan pengiriman domestik.</p>
            </div>

            <div class="glass-card feature-card">
                <div class="feature-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="feature-title">3. Terima di Indonesia</h3>
                <p class="feature-text">Pantau status pengiriman. Bayar tagihan ongkos kirim saat tiba di Indonesia, dan paket siap diantar ke alamat Anda.</p>
            </div>
        </div>
    </section>

    <!-- Poster & Pricing Section -->
    <section id="harga" class="section">
        <div class="section-header">
            <h2 class="section-title">Informasi & Harga</h2>
            <p class="section-subtitle">Transparan tanpa biaya tersembunyi. Hitung biaya kirim Anda dengan mudah.</p>
        </div>

        <div style="max-width: 1200px; margin: 0 auto;">
            <!-- Poster Centered -->
            <div style="max-width: 700px; margin: 0 auto 3rem auto; text-align: center;">
                <img src="/images/poster.webp" alt="Poster Harga WH CHINA by CECE" style="width: 100%; border-radius: 20px; box-shadow: 0 20px 40px rgba(95, 168, 211, 0.2); border: 6px solid rgba(255,255,255,0.7);">
                <p style="text-align: center; font-size: 0.8rem; color: var(--ink-soft); margin-top: 1rem;">
                  
                </p>
            </div>

            <!-- 3 Cards Horizontally -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; align-items: start;">
                <div class="card-blue info-card" style="margin-bottom: 0;">
                    <h4>
                        <span style="display:inline-block; width:12px; height:12px; background:var(--blue-deep); border-radius:50%;"></span>
                        Hitung Volume
                    </h4>
                    <ul class="info-list">
                        <li>
                            <span class="info-label">Air Cargo</span>
                            <span class="info-value">p x l x t / 6000</span>
                        </li>
                        <li>
                            <span class="info-label">Sea Cargo</span>
                            <span class="info-value">p x l x t / 5000</span>
                        </li>
                    </ul>
                </div>

                <div class="card-pink info-card" style="margin-bottom: 0;">
                    <h4>
                        <span style="display:inline-block; width:12px; height:12px; background:var(--pink-deep); border-radius:50%;"></span>
                        Additional Fee
                    </h4>
                    <ul class="info-list">
                        <li>
                            <span class="info-label">Req Video Unboxing / Video Poca</span>
                            <span class="info-value" style="font-size:0.75rem;">Rp 10.000</span>
                        </li>
                        <li>
                            <span class="info-label">Req Extra Packing BBW</span>
                            <span class="info-value" style="font-size:0.75rem;">Rp 5.000</span>
                        </li>
                    </ul>
                </div>

                <div class="card-blue info-card" style="margin-bottom: 0;">
                    <h4>
                        <span style="display:inline-block; width:12px; height:12px; background:var(--ink); border-radius:50%;"></span>
                        Jadwal Pengiriman
                    </h4>
                    <p style="font-size:0.85rem; color:var(--ink-soft); margin-bottom:1rem;">(Saat barang sudah sampai WH Ina & sudah CO Shopee)</p>
                    <ul class="info-list">
                        <li>
                            <span class="info-label">JNE / SICEPAT</span>
                            <span class="info-value" style="font-size:0.75rem;">Per 2-3 hari</span>
                        </li>
                        <li>
                            <span class="info-label">SPX</span>
                            <span class="info-value" style="font-size:0.75rem;">1 Minggu 1x</span>
                        </li>
                    </ul>
                    <div style="background: rgba(251, 225, 223, 0.7); color: var(--red); padding: 0.75rem; border-radius: 8px; margin-top: 1rem; text-align: center; font-size: 0.85rem; font-weight: 600; border: 1px dashed var(--red);">
                        WEEKEND / TGL MERAH: OFF
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="cta">
        <div class="cta-content">
            <h2>Mulai Import Hari Ini</h2>
            <p>Bergabung dengan ratusan pebisnis lainnya yang telah mempercayakan pengiriman barangnya bersama WH CHINA by CECE.</p>
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn-white">Daftar Sekarang — Gratis</a>
            </div>
        </div>
    </section>

    <footer class="landing-footer">
        <div class="landing-brand" style="font-size: 1.25rem;">
            <span style="width:10px;height:10px;background:var(--pink-deep);border-radius:50%;display:inline-block;"></span>
            WH CHINA by CECE
        </div>
        <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap; justify-content:center;">
            <a href="{{ route('tnc') }}" style="color:var(--ink-soft); text-decoration:none; font-size:0.85rem; font-weight:600; transition:color 0.2s;" onmouseover="this.style.color='var(--ink)';" onmouseout="this.style.color='var(--ink-soft)';">Syarat &amp; Ketentuan</a>
            <a href="{{ route('privacy') }}" style="color:var(--ink-soft); text-decoration:none; font-size:0.85rem; font-weight:600; transition:color 0.2s;" onmouseover="this.style.color='var(--ink)';" onmouseout="this.style.color='var(--ink-soft)';">Kebijakan Privasi</a>
            <span style="color:var(--ink-soft); font-size:0.85rem;">&copy; {{ date('Y') }} WH CHINA by CECE. Hak Cipta Dilindungi.</span>
        </div>
    </footer>

</body>
</html>
