<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Putra Jaya Motor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">

    <div class="max-w-md w-full p-6">
        <div class="bg-white rounded-[32px] shadow-2xl shadow-blue-100 overflow-hidden border border-slate-100">
            <div class="p-8">
                <div class="flex items-center gap-2 mb-8 justify-center">
                    <div class="bg-blue-600 p-2 rounded-lg text-white">
                        <i class="fas fa-motorcycle text-xl"></i>
                    </div>
                    <span class="text-xl font-extrabold uppercase tracking-tighter">Putra<span class="text-blue-600">Jaya</span></span>
                </div>

                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-900">Buat Akun Baru</h2>
                    <p class="text-slate-500 text-sm">Daftar untuk memantau riwayat servis motor kamu.</p>
                </div>

                <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl text-xs mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3.5 px-5 focus:ring-2 focus:ring-blue-500 outline-none transition text-sm" placeholder="Contoh: Budi Santoso" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Alamat Email</label>
                        <input type="email" name="email" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3.5 px-5 focus:ring-2 focus:ring-blue-500 outline-none transition text-sm" placeholder="email@contoh.com" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Password</label>
                        <input type="password" name="password" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3.5 px-5 focus:ring-2 focus:ring-blue-500 outline-none transition text-sm" placeholder="••••••••" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2 ml-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-3.5 px-5 focus:ring-2 focus:ring-blue-500 outline-none transition text-sm" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white font-bold py-4 rounded-2xl hover:bg-blue-600 transition shadow-lg shadow-blue-100 mt-4 uppercase tracking-widest text-xs">
                        Daftar Sekarang
                    </button>
                </form>
            </div>
            
            <div class="bg-slate-50 p-6 text-center border-t border-slate-100">
                <p class="text-sm text-slate-500">Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Login di sini</a></p>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <a href="/" class="text-slate-400 text-sm hover:text-slate-600 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>