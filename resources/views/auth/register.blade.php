<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-xl font-black text-slate-900 tracking-tight uppercase">Pendaftaran Personel</h2>
        <p class="text-slate-500 text-xs font-medium mt-1">Buat akun untuk akses sistem kontrol terpadu.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
            <input id="name" class="industrial-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Contoh: Budi Santoso" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Email Perusahaan</label>
            <input id="email" class="industrial-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@tmj.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kunci Keamanan</label>
            <input id="password" class="industrial-input" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Kunci</label>
            <input id="password_confirmation" class="industrial-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kunci" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-[10px] text-red-500 font-black uppercase tracking-tight" />
        </div>

        <div class="pt-2 flex flex-col gap-4">
            <button type="submit" class="w-full bg-slate-900 text-white font-black text-xs uppercase tracking-[0.2em] px-6 py-4 rounded-xl flex justify-center items-center gap-3 hover:bg-slate-800 transition-all shadow-lg active:scale-95">
                Daftarkan Akun
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </button>
            
            <a class="text-center text-[10px] font-black text-slate-400 hover:text-blue-600 transition uppercase tracking-widest" href="{{ route('login') }}">
                Sudah punya akun? Masuk di sini
            </a>
        </div>
    </form>
</x-guest-layout>
