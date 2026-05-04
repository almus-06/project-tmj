<x-guest-layout>
    <div style="margin-bottom:1.5rem; text-align:center;">
        <h2 style="font-size:22px; font-weight:900; color:#0F172A; letter-spacing:-0.02em;">Pendaftaran Personel</h2>
        <p style="font-size:13px; color:#64748B; margin-top:4px;">Buat akun untuk akses sistem kontrol terpadu.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name --}}
        <div style="margin-bottom:16px;">
            <label for="name" style="display:block; font-size:10px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:0.15em; margin-bottom:8px;">Nama Lengkap</label>
            <input id="name" class="g-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Contoh: Budi Santoso" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
        </div>

        {{-- Username --}}
        <div style="margin-bottom:16px;">
            <label for="username" style="display:block; font-size:10px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:0.15em; margin-bottom:8px;">Username</label>
            <input id="username" class="g-input" type="text" name="username" :value="old('username')" required autocomplete="username" placeholder="Pilih username unik" />
            <x-input-error :messages="$errors->get('username')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
        </div>

        {{-- Email --}}
        <div style="margin-bottom:16px;">
            <label for="email" style="display:block; font-size:10px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:0.15em; margin-bottom:8px;">Email (Opsional)</label>
            <input id="email" class="g-input" type="email" name="email" :value="old('email')" autocomplete="username" placeholder="nama@tmj.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
        </div>

        {{-- Password --}}
        <div style="margin-bottom:16px;">
            <label for="password" style="display:block; font-size:10px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:0.15em; margin-bottom:8px;">Password</label>
            <input id="password" class="g-input" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
        </div>

        {{-- Confirm Password --}}
        <div style="margin-bottom:24px;">
            <label for="password_confirmation" style="display:block; font-size:10px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:0.15em; margin-bottom:8px;">Konfirmasi Password</label>
            <input id="password_confirmation" class="g-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
        </div>

        <button type="submit" style="width:100%; background:linear-gradient(135deg,#1E1B4B,#312E81); color:white; font-weight:800; font-size:12px; letter-spacing:0.15em; text-transform:uppercase; padding:16px; border-radius:14px; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:10px; transition:all 0.3s;">
            Daftarkan Akun
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </button>

        <div style="text-align:center; margin-top:16px;">
            <a href="{{ route('login') }}" style="font-size:10px; font-weight:800; color:#94A3B8; text-decoration:none; text-transform:uppercase; letter-spacing:0.15em;">Sudah punya akun? <span style="color:#6366F1;">Masuk di sini</span></a>
        </div>
    </form>
</x-guest-layout>
