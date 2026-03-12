<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Putra Jaya Motor - Sistem Booking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-50 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-blue-900 text-white flex flex-col shadow-xl z-20">
            <div class="h-16 flex items-center justify-center border-b border-slate-700 font-bold text-xl tracking-wider">
                <span class="text-blue-500 mr-2">🔧</span> PJM Bengkel
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="#" class="block px-4 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 transition font-medium">Dashboard</a>
                <a href="{{ route('sparepart.index') }}" class="block px-4 py-3 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition font-medium">Data Master</a>
                <a href="#" class="block px-4 py-3 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition font-medium">Booking Antrean</a>
                <a href="#" class="block px-4 py-3 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition font-medium">Kasir & Transaksi</a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col relative overflow-y-auto overflow-x-hidden">
            
            <header class="h-16 bg-white shadow-sm flex items-center justify-end px-6 z-10">
                <div class="flex items-center gap-3 cursor-pointer hover:bg-slate-50 p-2 rounded-lg transition">
                    <span class="text-sm font-semibold text-slate-700">Halo, Admin</span>
                    <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold shadow-md">A</div>
                </div>
            </header>

            <main class="p-6">
                {{ $slot }}
            </main>

        </div>
    </div>
</body>
</html>