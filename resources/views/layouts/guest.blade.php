<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TMJ') }} | Otentikasi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #0F172A; min-height: 100vh; }

        .guest-wrapper {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 50%, #312E81 100%);
            position: relative; overflow: hidden;
        }
        .guest-wrapper::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse at 30% 50%, rgba(99,102,241,0.12) 0%, transparent 50%);
        }

        .guest-card {
            width: 100%; max-width: 480px;
            background: #FFFFFF;
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
            position: relative; z-index: 1;
        }

        .g-input {
            width: 100%; border: 1.5px solid #E2E8F0; background: #F8FAFC;
            border-radius: 14px; padding: 14px 16px; font-weight: 600; font-size: 14px;
            color: #0F172A; transition: all 0.2s; outline: none;
        }
        .g-input:focus {
            border-color: #6366F1; background: #FFF;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.08);
        }
        .g-input::placeholder { color: #94A3B8; font-weight: 500; }

        @media (max-width: 640px) {
            .guest-wrapper { padding: 0.75rem; align-items: center; }
            .guest-card { padding: 1.75rem 1.25rem; border-radius: 16px; }
            .g-input { font-size: 16px !important; padding: 12px 14px; }
        }
    </style>
</head>
<body class="antialiased">
    <div class="guest-wrapper">
        <div class="guest-card">
            {{-- Logo --}}
            <div style="display:flex; align-items:center; justify-content:center; gap:12px; margin-bottom:2rem;">
                <div style="width:40px; height:40px; background:linear-gradient(135deg,#4F46E5,#7C3AED); border-radius:12px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(99,102,241,0.3);">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span style="font-weight:900; font-size:18px; color:#0F172A;">TMJ <span style="color:#6366F1;">Secure</span></span>
            </div>

            <div style="position:relative; z-index:10;">
                {{ $slot }}
            </div>

            <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid #F1F5F9; text-align:center;">
                <p style="font-size:10px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:0.15em;">&copy; {{ date('Y') }} PT. Tri Machmud Jaya</p>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.from(".guest-card", {
                duration: 1,
                y: 40,
                opacity: 0,
                ease: "power3.out"
            });

            gsap.from(".guest-card > div", {
                duration: 0.8,
                y: 20,
                opacity: 0,
                stagger: 0.1,
                delay: 0.3,
                ease: "power2.out"
            });
        });
    </script>
</body>
</html>
