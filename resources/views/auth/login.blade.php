<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — TMJ Operations System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #0F172A; min-height: 100vh; overflow-x: hidden; }

        /* ── Animated Gradient Background ── */
        .bg-animated {
            position: fixed; inset: 0; z-index: 0;
            background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 50%, #312E81 100%);
        }
        .bg-animated::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(99,102,241,0.15) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.1) 0%, transparent 50%);
            animation: bgPulse 8s ease-in-out infinite alternate;
        }
        @keyframes bgPulse {
            0% { opacity: 0.6; }
            100% { opacity: 1; }
        }

        /* ── Main Layout ── */
        .page-wrapper {
            position: relative; z-index: 1;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
        }

        /* ── Bento Grid Container ── */
        .bento-grid {
            width: 100%; max-width: 960px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        @media (min-width: 768px) {
            .bento-grid {
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: auto auto auto;
            }
            .bento-form { grid-column: 1 / 3; grid-row: 1 / 4; }
            .bento-brand { grid-column: 3; grid-row: 1; }
            .bento-stats { grid-column: 3; grid-row: 2; }
            .bento-security { grid-column: 3; grid-row: 3; }
        }

        /* ── Bento Card Base ── */
        .bento-card {
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .bento-card:hover { transform: translateY(-2px); }

        /* Card Variants */
        .bento-form {
            background: #FFFFFF;
            border: none;
            padding: 2.5rem;
        }
        .bento-dark {
            background: rgba(255,255,255,0.04);
            color: white;
        }
        .bento-accent {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            color: white;
            border: none;
        }

        /* ── Form Inputs ── */
        .login-input {
            width: 100%;
            border: 1.5px solid #E2E8F0;
            background: #F8FAFC;
            border-radius: 14px;
            padding: 14px 14px 14px 44px;
            font-weight: 600;
            font-size: 14px;
            color: #0F172A;
            transition: all 0.2s ease;
            outline: none;
        }
        .login-input:focus {
            border-color: #6366F1;
            background: #FFF;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.08);
        }
        .login-input::placeholder { color: #94A3B8; font-weight: 500; }

        /* ── Button ── */
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #1E1B4B, #312E81);
            color: white;
            font-weight: 800;
            font-size: 12px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 16px;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #312E81, #4338CA);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(30,27,75,0.4);
        }
        .btn-login:active { transform: translateY(0); }

        /* ── Mobile ── */
        @media (max-width: 767px) {
            .page-wrapper { padding: 0.75rem; align-items: center; }
            .bento-grid { gap: 8px; }
            .bento-card { padding: 1.25rem; border-radius: 16px; }
            .bento-form { padding: 1.5rem 1.25rem; }
            .login-input { font-size: 16px !important; padding: 10px 10px 10px 38px; }
            .btn-login { padding: 14px; font-size: 11px; }
            .hide-mobile { display: none !important; }
            
            .bento-form > div:first-child { margin-bottom: 1.25rem !important; }
            .bento-form > div:nth-child(2) { margin-bottom: 1.25rem !important; }
            h1 { font-size: 18px !important; }
            p { font-size: 12px !important; }
        }

        /* ── Floating Particles (CSS-only) ── */
        .particle {
            position: absolute; border-radius: 50%;
            background: rgba(99,102,241,0.15);
            animation: float 20s infinite ease-in-out;
        }
        .particle:nth-child(1) { width: 200px; height: 200px; top: 10%; left: 5%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 120px; height: 120px; bottom: 15%; right: 10%; animation-delay: -7s; animation-duration: 25s; }
        .particle:nth-child(3) { width: 80px; height: 80px; top: 60%; left: 60%; animation-delay: -14s; background: rgba(139,92,246,0.1); }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.05); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }
    </style>
</head>

<body class="antialiased">
    <div class="bg-animated">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="page-wrapper">
        <div class="bento-grid">

            {{-- ═══ FORM CARD (Main — spans 2 cols on desktop) ═══ --}}
            <div class="bento-card bento-form">
                {{-- Logo --}}
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:2rem;">
                    <div style="width:40px; height:40px; background:linear-gradient(135deg,#4F46E5,#7C3AED); border-radius:12px; display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <p style="font-weight:900; font-size:18px; color:#0F172A; letter-spacing:-0.02em;">TMJ <span style="color:#6366F1;">System</span></p>
                        <p style="font-size:10px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:0.15em;">Operations Platform</p>
                    </div>
                </div>

                <div style="margin-bottom:2rem;">
                    <h1 style="font-size:24px; font-weight:900; color:#0F172A; letter-spacing:-0.02em;">Selamat Datang</h1>
                    <p style="font-size:14px; color:#64748B; margin-top:4px;">Masuk untuk mengakses dashboard kontrol operasional.</p>
                </div>

                {{-- Session Status --}}
                <x-auth-session-status class="mb-6 text-sm font-bold text-green-600 bg-green-50 p-4 rounded-xl border border-green-100" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Username --}}
                    <div style="margin-bottom:20px;">
                        <label for="username" style="display:block; font-size:10px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:0.15em; margin-bottom:8px;">Username</label>
                        <div style="position:relative;">
                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94A3B8;">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <input id="username" class="login-input" type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Masukkan username" />
                        </div>
                        <x-input-error :messages="$errors->get('username')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
                    </div>

                    {{-- Password --}}
                    <div style="margin-bottom:24px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <label for="password" style="font-size:10px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:0.15em;">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" style="font-size:10px; font-weight:800; color:#6366F1; text-decoration:none; text-transform:uppercase; letter-spacing:0.1em;">Lupa?</a>
                            @endif
                        </div>
                        <div style="position:relative;">
                            <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94A3B8;">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input id="password" class="login-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
                    </div>

                    {{-- Remember --}}
                    <div style="display:flex; align-items:center; margin-bottom:28px;">
                        <input id="remember_me" type="checkbox" name="remember" style="width:16px; height:16px; border-radius:5px; accent-color:#6366F1; cursor:pointer;">
                        <label for="remember_me" style="margin-left:8px; font-size:13px; font-weight:600; color:#64748B; cursor:pointer;">Biarkan tetap masuk</label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-login">
                        Masuk ke Dashboard
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #F1F5F9; text-align:center;">
                    <p style="font-size:10px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:0.15em;">&copy; {{ date('Y') }} PT. Tri Machmud Jaya</p>
                </div>
            </div>

            {{-- ═══ BRAND CARD ═══ --}}
            <div class="bento-card bento-accent hide-mobile">
                <div style="width:48px; height:48px; background:rgba(255,255,255,0.2); border-radius:14px; display:flex; align-items:center; justify-content:center; margin-bottom:1.5rem; backdrop-filter:blur(10px);">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h2 style="font-size:20px; font-weight:900; line-height:1.2; margin-bottom:8px;">Operational<br>Control Center</h2>
                <p style="font-size:12px; opacity:0.8; line-height:1.5;">Manajemen armada & tenaga kerja dalam satu platform terpadu.</p>
            </div>

            {{-- ═══ STATS CARD ═══ --}}
            <div class="bento-card bento-dark hide-mobile">
                <p style="font-size:10px; font-weight:800; color:#818CF8; text-transform:uppercase; letter-spacing:0.15em; margin-bottom:12px;">Live Status</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div style="background:rgba(255,255,255,0.05); border-radius:12px; padding:14px; text-align:center;">
                        <p style="font-size:22px; font-weight:900; color:#A5B4FC;">72</p>
                        <p style="font-size:9px; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:0.1em; margin-top:2px;">Unit</p>
                    </div>
                    <div style="background:rgba(255,255,255,0.05); border-radius:12px; padding:14px; text-align:center;">
                        <p style="font-size:22px; font-weight:900; color:#A5B4FC;">24/7</p>
                        <p style="font-size:9px; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:0.1em; margin-top:2px;">Monitor</p>
                    </div>
                </div>
            </div>

            {{-- ═══ SECURITY CARD ═══ --}}
            <div class="bento-card bento-dark hide-mobile">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                    <span style="width:8px; height:8px; border-radius:50%; background:#22C55E; box-shadow:0 0 8px rgba(34,197,94,0.5);"></span>
                    <p style="font-size:10px; font-weight:800; color:#22C55E; text-transform:uppercase; letter-spacing:0.15em;">Sistem Aman</p>
                </div>
                <p style="font-size:11px; color:#64748B; line-height:1.5;">Data terenkripsi end-to-end dengan standar keamanan industri.</p>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // GSAP Entrance Animations
            const tl = gsap.timeline({ defaults: { ease: "power4.out" } });

            tl.from(".bento-card", {
                duration: 1.2,
                y: 60,
                opacity: 0,
                stagger: 0.15,
                skewY: 2
            });

            tl.from(".bento-form > *", {
                duration: 0.8,
                y: 20,
                opacity: 0,
                stagger: 0.1
            }, "-=0.8");

            // Subtle mouse movement parallax for background particles
            document.addEventListener('mousemove', (e) => {
                const { clientX, clientY } = e;
                const xPos = (clientX / window.innerWidth - 0.5) * 30;
                const yPos = (clientY / window.innerHeight - 0.5) * 30;

                gsap.to(".particle", {
                    duration: 2,
                    x: xPos,
                    y: yPos,
                    ease: "power2.out"
                });
            });
        });
    </script>
</body>
</html>